<?php

namespace app\controllers;

use app\models\Movimientos;
use app\models\Insumos;
use app\models\MovimientosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empresas;
use app\models\Detallemovimiento;
use app\models\Trabajadores;
use app\models\Lotes;
use app\models\Almacen;
use app\models\Trabajadorepp;

use Yii;

use app\models\NivelOrganizacional1;

/**
 * MovimientosController implements the CRUD actions for Movimientos model.
 */
class MovimientosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Movimientos models.
     *
     * @return string
     */
    public function actionEntradas()
    {
        $searchModel = new MovimientosSearch();
        $searchModel->e_s = 1;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Movimientos models.
     *
     * @return string
     */
    public function actionIndex($tipo)
    {
        $searchModel = new MovimientosSearch();
        $searchModel->tipo_insumo = $tipo;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Lists all Movimientos models.
     *
     * @return string
     */
    public function actionSalidas()
    {
        $searchModel = new MovimientosSearch();
        $searchModel->e_s = 2;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movimientos model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Movimientos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($tipo)
    {
        $model = new Movimientos();
        $model->scenario = 'create';
        //$model->titulo = $tipo;
        //date_default_timezone_set('America/Mazatlan');
        $model->create_date = date('Y-m-d');
        $model->envia_empresa = 0;
        $model->tipo_insumo = $tipo;
        $model->envia_form = 0;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $type = Yii::$app->getRequest()->getQueryParam('type');
        $worker = Yii::$app->getRequest()->getQueryParam('worker');
        if($type && $worker){

            $trabajador = Trabajadores::findOne($worker);
            if($trabajador){
                $model->id_empresa = $trabajador->id_empresa;
            }
            
            $model->id_trabajador = $worker;
            $model->e_s = 2;
            $model->tipo = $type;
            //dd('Tipo movimiento: '.$type.' | Trabajador préstamo: '.$worker);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                
                if($model->envia_form == '1' && ($model->aux_medicamentos != null || $model->aux_medicamentossalida != null || $model->aux_epp != null || $model->aux_eppsalida != null)){
                    //dd($model);
                    if($model->tipo== 5){
                        //dd($model);

                        $consultorio_entrada = $model->id_consultorio;
                        $consultorio_salida = $model->id_consultorio2;

                        //dd('Almacen salida: '.$consultorio_salida.' | Almacen entrada: '.$consultorio_entrada);

                        //Guardar salida
                        $movimiento_salida = new Movimientos();
                        $movimiento_salida = $model;
                        $movimiento_salida->e_s = 2;
                        $movimiento_salida->id_consultorio = $consultorio_salida;
                        $movimiento_salida->save();
                        $this->saveMultiple($movimiento_salida);

                        //dd($model);

                        //Guardad entrada
                        $movimiento_entrada = new Movimientos();
                        $movimiento_entrada->e_s = 1;
                        $movimiento_entrada->tipo_insumo = $movimiento_salida->tipo_insumo;
                        $movimiento_entrada->id_empresa = $movimiento_salida->id_empresa;
                        $movimiento_entrada->tipo = 2;
                        $movimiento_entrada->create_date = $movimiento_salida->create_date;
                        $movimiento_entrada->id_consultorio = $consultorio_entrada;
                        $movimiento_entrada->observaciones = $movimiento_salida->observaciones;
                        $movimiento_entrada->save();

                        //dd($movimiento_entrada);

                        $movimiento_entrada->aux_medicamentossalida = $movimiento_salida->aux_medicamentossalida;

                        //dd($movimiento_entrada);

                        $this->saveMultiple($movimiento_entrada);
                        $this->actualizaAlmacen($movimiento_entrada);

                        //dd($movimiento_entrada->medicamentos);
                        return $this->redirect(['index','tipo'=>$tipo]);

                    } else {
                        $model->save();
                        $this->saveMultiple($model);
                        if($model->e_s == 1){
                            $this->actualizaAlmacen($model);
                        }
                        return $this->redirect(['index','tipo'=>$tipo]);
                    }
                   
    
                } else{
                    if($model->envia_empresa == '1' || $model->envia_consultorio == '1' || $model->envia_trabajador == '1'){
                        $model->folio = $this->createClave($model);
                        $model->aux_eppsalida = null;
                        
                        $trabajador = Trabajadores::findOne($model->id_trabajador);

                        if($trabajador){
                            if(isset($trabajador->puesto)){
                                if($trabajador->puesto->epps){
                                    $index = 0;
                                    foreach($trabajador->puesto->epps as $key=>$epp){
                                        $trabajadorepp = Trabajadorepp::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_puestoepp'=>$epp->id])->andWhere(['status'=>1])->one();
                                        if($trabajadorepp){

                                            //Buscar el insumo en almacén que corresponde, si es que hay
                                            $insumoalmacen = Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_insumo'=>$trabajadorepp->id_epp])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['>','stock',0])->one();
                                            //dd($insumoalmacen);

                                            if($insumoalmacen){
                                                $model->aux_eppsalida[$index]['index'] = $index;
                                                $model->aux_eppsalida[$index]['id_insumo'] = $insumoalmacen->id;
                                                $model->aux_eppsalida[$index]['unidad'] = $insumoalmacen->insumo->unidades_individuales;
                                                $model->aux_eppsalida[$index]['fecha_caducidad'] =  $insumoalmacen->fecha_caducidad;
                                                $model->aux_eppsalida[$index]['stock'] = $insumoalmacen->stock_unidad;
                                                $model->aux_eppsalida[$index]['cantidad'] = 1;
                                                $index++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //dd($model);
                        return $this->render('create', [
                            'model' => $model,
                            'tipo'=>$tipo
                        ]);
                    }
                }
               
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'tipo'=>$tipo
        ]);
    }

    public function actionGetniveles($model){
        $empresa_actual = null;
        if(Yii::$app->user->identity->empresa_all != 1){
            $array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
            if(count($array_empresas) == 1){
                $empresa_actual = $array_empresas[0];
            }
        }

        //dd($model,Yii::$app->user->identity,$empresa_actual,Yii::$app->user->identity->id_empresa);
        if($empresa_actual == Yii::$app->user->identity->id_empresa){
            if(Yii::$app->user->identity->nivel1_all != 1){
                $array_nivel1 = explode(',', Yii::$app->user->identity->nivel1_select);
                if(count($array_nivel1) == 1){
                    $nivel1_= NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$array_nivel1[0]])->andWhere(['status'=>1])->one();
                    if($nivel1_){
                        
                        $model->id_nivel1 = $nivel1_->id;
                    }
                    
                }
            }

            if(Yii::$app->user->identity->nivel2_all != 1){
                $array_nivel2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(count($array_nivel2) == 1){
                    $model->id_nivel2 = $array_nivel2[0];
                }
            }

            if(Yii::$app->user->identity->nivel3_all != 1){
                $array_nivel3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(count($array_nivel3) == 1){
                    $model->id_nivel3 = $array_nivel3[0];
                }
            }

            if(Yii::$app->user->identity->nivel4_all != 1){
                $array_nivel4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(count($array_nivel4) == 1){
                    $model->id_nivel4 = $array_nivel4[0];
                }
            }
        }
        
    }

    /**
     * Updates an existing Movimientos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$tipo)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if($model->e_s == 1){
            foreach($model->medicamentos as $key=>$medicamento){
                $model->aux_medicamentos[$key]['index'] = ($key+1);
                $model->aux_medicamentos[$key]['id_insumo'] = $medicamento->id_insumo;
                $model->aux_medicamentos[$key]['presentacion'] = $medicamento->insumo->presentacion->presentacion;
                $model->aux_medicamentos[$key]['unidad'] = $medicamento->insumo->unidades_individuales;
                $model->aux_medicamentos[$key]['cantidad'] = $medicamento->cantidad;
                $model->aux_medicamentos[$key]['cantidad_unidades'] = $medicamento->cantidad_unidad;
                $model->aux_medicamentos[$key]['fecha_caducidad'] = $medicamento->fecha_caducidad;
                $model->aux_medicamentos[$key]['id'] = $medicamento->id;
            }
        } else{
            foreach($model->medicamentos as $key=>$medicamento){
                $almacen = Almacen::find()->where(['id_insumo'=>$medicamento->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();

                if($almacen){
                    $model->aux_medicamentossalida[$key]['index'] = ($key+1);
                    $model->aux_medicamentossalida[$key]['id_insumo'] = $almacen->id;
                    $model->aux_medicamentossalida[$key]['unidad'] = $medicamento->insumo->unidades_individuales;
                    $model->aux_medicamentossalida[$key]['fecha_caducidad'] =  $almacen->fecha_caducidad;
                    $model->aux_medicamentossalida[$key]['stock'] = $almacen->stock_unidad;
                    $model->aux_medicamentossalida[$key]['cantidad'] = $medicamento->cantidad_unidad;
                    $model->aux_medicamentossalida[$key]['id'] = $medicamento->id;
                }
                
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'tipo'=>$tipo
        ]);
    }


    private function saveMultiple($model){
        
        //dd($model);
        if($model->e_s == 1 ){//Hacemos las entradas
            if ($model->tipo!= 2 && $model->tipo!= 5){
                //dd('Entra 1');

                $array_insumos = [];

                if($model->tipo_insumo == 1){
                    $array_insumos = $model->aux_medicamentos;
                } else{
                    $array_insumos = $model->aux_epp;
                }

                $id_medicamentos = [];
                if(isset($array_insumos) &&  $array_insumos != null &&  $array_insumos != ''){
                    foreach($array_insumos as $key => $medicamento){
        
                        if(isset($medicamento['id']) && $medicamento['id'] != null && $medicamento['id'] != ''){
                            $dm = Detallemovimiento::find()->where(['id'=> $medicamento['id']])->one();
                        } else {
                            $dm = new Detallemovimiento();
                            $dm->id_movimiento = $model->id;
                        }
        
                        if(isset($medicamento['id_insumo']) || isset($medicamento['id_insumo'])){
        
                            if(isset($medicamento['cantidad']) && isset($medicamento['cantidad_unidades'])){
                                $dm->id_insumo = $medicamento['id_insumo'];
                                $dm->cantidad = $medicamento['cantidad'];

                                if($model->tipo_insumo == 1){
                                    $dm->cantidad_unidad = $medicamento['cantidad_unidades'];
                                    $dm->fecha_caducidad = $medicamento['fecha_caducidad'];
                                } else{
                                    $dm->cantidad_unidad = $medicamento['cantidad'];
                                }
                                
                               
                                $dm->fecha = $model->create_date;
                                $dm->save();
        
                                if($dm){
                                    if($model->tipo_insumo == 1){

                                        $lote = Lotes::find()->where(['id_insumo'=>$dm->id_insumo])->andWhere(['fecha_caducidad'=>$model->id_consultorio])->andWhere(['id_consultorio'=>$dm->fecha_caducidad])->one();
                                        if(!$lote){
                                            $lote = new Lotes();
                                            $lote->id_movimiento = $model->id;
                                            $lote->id_empresa = $model->id_empresa;
                                            $lote->id_consultorio = $model->id_consultorio;
                                            $lote->id_insumo = $dm->id_insumo;
                                            $lote->fecha_caducidad = $dm->fecha_caducidad;
                                            $lote->folio = $this->createClavelote($model,$dm->fecha_caducidad);
                                            $lote->cantidad = $dm->cantidad;
                                            $lote->cantidad_unidad = $dm->cantidad_unidad;
                                        } else {
                                            $lote->cantidad = $lote->cantidad + $dm->cantidad;
                                            $lote->cantidad_unidad = $lote->cantidad_unidad + $dm->cantidad_unidad;
                                        }
            
                                        $lote->save();
            
                                        if($lote){
                                            $dm->id_lote = $lote->id;
                                            $dm->save();
                                        }
                                        array_push($id_medicamentos, $dm->id);

                                    } else{
                                        array_push($id_medicamentos, $dm->id);
                                    }
                                }
                            }
                            
                        }
                    }
                }
        
                $deletes = Detallemovimiento::find()->where(['id_movimiento'=>$model->id])->andWhere(['not in','id',$id_medicamentos])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }

            } else{
                $id_medicamentos = [];

                $array_insumos = [];
                
                if($model->tipo_insumo == 1){
                    $array_insumos = $model->aux_medicamentossalida;
                } else{
                    $array_insumos = $model->aux_eppsalida;
                }
    
                    
                if(isset($array_insumos) && $array_insumos != null && $array_insumos != ''){
                    foreach($array_insumos as $key => $medicamento){
        
                        if(isset($medicamento['id']) && $medicamento['id'] != null && $medicamento['id'] != ''){
                            $dm = Detallemovimiento::find()->where(['id'=> $medicamento['id']])->one();
                        } else {
                            $dm = new Detallemovimiento();
                            $dm->id_movimiento = $model->id;
                        }
        
                        if(isset($medicamento['id_insumo']) || isset($medicamento['id_insumo'])){
                            $almacen = Almacen::find()->where(['id'=>$medicamento['id_insumo']])->one();
        
                            if($almacen){
                                $insumo = Insumos::find()->where(['id'=>$almacen->id_insumo])->one();
        
                                if($insumo && isset($medicamento['cantidad'])){
                                    $dm->id_insumo = $insumo->id;
                                   
                                    $dm->cantidad_unidad = $medicamento['cantidad'];
                                    $dm->fecha_caducidad = $almacen->fecha_caducidad;
        
        
                                    if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                                        if($medicamento['cantidad'] > $insumo->unidades_individuales){
                                            $dm->cantidad = intval($medicamento['cantidad']/$insumo->unidades_individuales);
                                        } else{
                                            $dm->cantidad = 1;
                                        }
                                    } else{
                                        $dm->cantidad = 1; 
                                    }
                                   
                                    $dm->fecha = $model->create_date;
                                    $dm->save();
        
                                    if($dm){
                                        array_push($id_medicamentos, $dm->id);
                                    }
            
                                }
        
                                //$almacen->stock_unidad = $almacen->stock_unidad - $medicamento['cantidad'];
                                if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                                    $stock = intval($almacen->stock_unidad / $insumo->unidades_individuales);
                                    $almacen->stock = $stock;
                                }
                                $almacen->save();
        
                            }
                            
                        }
                    }
                }
        
                $deletes = Detallemovimiento::find()->where(['id_movimiento'=>$model->id])->andWhere(['not in','id',$id_medicamentos])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }

            }
            
        }
        
        if($model->e_s == 2) {//Hacemos las salidas
            //dd('Entra 2');
            //dd('Entra aqui owo');
            $id_medicamentos = [];

            $array_insumos = [];
            
            if($model->tipo_insumo == 1){
                $array_insumos = $model->aux_medicamentossalida;
            } else{
                $array_insumos = $model->aux_eppsalida;
            }

                

            if(isset($array_insumos) && $array_insumos != null && $array_insumos != ''){
                foreach($array_insumos as $key => $medicamento){
    
                    if(isset($medicamento['id']) && $medicamento['id'] != null && $medicamento['id'] != ''){
                        $dm = Detallemovimiento::find()->where(['id'=> $medicamento['id']])->one();
                    } else {
                        $dm = new Detallemovimiento();
                        $dm->id_movimiento = $model->id;
                    }
    
                    if(isset($medicamento['id_insumo']) || isset($medicamento['id_insumo'])){
                        $almacen = Almacen::find()->where(['id'=>$medicamento['id_insumo']])->one();
    
                        if($almacen){
                            $insumo = Insumos::find()->where(['id'=>$almacen->id_insumo])->one();
    
                            if($insumo && isset($medicamento['cantidad'])){
                                $dm->id_insumo = $insumo->id;
                               
                                $dm->cantidad_unidad = $medicamento['cantidad'];
                                $dm->fecha_caducidad = $almacen->fecha_caducidad;
    
    
                                if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                                    if($medicamento['cantidad'] > $insumo->unidades_individuales){
                                        $dm->cantidad = intval($medicamento['cantidad']/$insumo->unidades_individuales);
                                    } else{
                                        $dm->cantidad = 1;
                                    }
                                } else{
                                    $dm->cantidad = 1; 
                                }
                               
                                $dm->fecha = $model->create_date;
                                $dm->save();
    
                                if($dm){
                                    array_push($id_medicamentos, $dm->id);
                                }
        
                            }
    
                            $almacen->stock_unidad = $almacen->stock_unidad - $medicamento['cantidad'];
                            if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                                $stock = intval($almacen->stock_unidad / $insumo->unidades_individuales);
                                $almacen->stock = $stock;
                            }
                            $almacen->save();
    
                        }
                        
                    }
                }
            }
    
            $deletes = Detallemovimiento::find()->where(['id_movimiento'=>$model->id])->andWhere(['not in','id',$id_medicamentos])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }
        
        }
        

    }

    private function actualizaAlmacen($model){

        //dd($model);
        //date_default_timezone_set('America/Mazatlan');
    
        if($model->medicamentos){
            foreach($model->medicamentos as $key=>$medicamento){

                if($model->tipo_insumo == 1){
                    $almacen = Almacen::find()->where(['id_consultorio'=>$model->id_consultorio])->andWhere(['id_insumo'=>$medicamento->id_insumo])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();
                } else{
                    $almacen = Almacen::find()->where(['id_consultorio'=>$model->id_consultorio])->andWhere(['id_insumo'=>$medicamento->id_insumo])->one();
                }

                if(!$almacen){
                    $almacen = new Almacen(); 
                    $almacen->id_empresa = $model->id_empresa;
                    $almacen->id_consultorio = $model->id_consultorio;
                    $almacen->tipo_insumo = $model->tipo_insumo;
                    $almacen->id_insumo = $medicamento->id_insumo;
                    $almacen->stock = $medicamento->cantidad;

                    if($model->tipo_insumo == 1){
                        $almacen->fecha_caducidad = $medicamento->fecha_caducidad;
                        $almacen->stock_unidad = $medicamento->cantidad_unidad;
                    } else{
                        $almacen->stock_unidad = $medicamento->cantidad; 
                    }
                    $almacen->update_date = date('Y-m-d');
                } else{
                    if($model->e_s == '1'){
                        $almacen->stock =  $almacen->stock + $medicamento->cantidad;
                        $almacen->stock_unidad = $almacen->stock_unidad + $medicamento->cantidad_unidad;
                    } else{
                        $almacen->stock =  $almacen->stock - $medicamento->cantidad;
                        $almacen->stock_unidad = $almacen->stock_unidad - $medicamento->cantidad_unidad;
                    }
                    
                }
                $almacen->save();
                
            }
        }
    }

    /**
     * Deletes an existing Movimientos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Movimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Movimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Movimientos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function createClave($model){
        //date_default_timezone_set('America/Mazatlan');

        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
        $fecha = date('ymd');

        $consultorios =[];

        if($empresa){
            $indicio = 'I'.$empresa->abreviacion.$fecha;
            $ultimo_guardado = Movimientos::find()->orderBy(['id'=>SORT_DESC])->one();
            $consultorios = $empresa->consultorios;
        }
        
       
        if($ultimo_guardado){
            $clave = $ultimo_guardado->folio;
            $parte_numerica =intval(str_replace ( $indicio,'',$clave));
            $parte_numerica++;
            $clave_devuelta = $indicio.str_pad($parte_numerica, 5, "0", STR_PAD_LEFT);
        }else{
            $clave_devuelta = $indicio.'00001';
        }   
        
        return $clave_devuelta;
    }


    private function createClavelote($model,$fecha_caducidad){
        //date_default_timezone_set('America/Mazatlan');

        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
        $fecha = date($fecha_caducidad);

        if($empresa){
            $indicio = 'L'.$empresa->abreviacion.$fecha;
            $ultimo_guardado = Lotes::find()->orderBy(['id'=>SORT_DESC])->one();
        }
        
        if($ultimo_guardado){
            $clave = $ultimo_guardado->folio;
            $parte_numerica =intval(str_replace ( $indicio,'',$clave));
            $parte_numerica++;
            $clave_devuelta = $indicio.str_pad($parte_numerica, 4, "0", STR_PAD_LEFT);
        }else{
            $clave_devuelta = $indicio.'0001';
        }   
        
        return $clave_devuelta;
    }


    public function actionInfoinsumo(){
        $id = Yii::$app->request->post('id');
        $insumo = Insumos::find()->where(['id'=>$id])->one();

        if($insumo){
            if($insumo->presentacion){
                $insumo->id_presentacion = $insumo->presentacion->presentacion;
            }
            if($insumo->unidad){
                $insumo->id_unidad = $insumo->unidad->unidad;
            }
        }

        return \yii\helpers\Json::encode(['insumo' => $insumo]);
    }
}