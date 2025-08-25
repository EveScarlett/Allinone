<?php

namespace app\controllers;

use app\models\Consultas;
use app\models\ConsultasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Diagnosticoscie;
use app\models\Empresas;
use app\models\Movimientos;
use app\models\Almacen;
use app\models\Lotes;
use app\models\Insumos;
use app\models\Detallemovimiento;
use app\models\ProgramaTrabajador;
use app\models\Trabajadores;
use yii\db\Query;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use Carbon\Carbon;
use Yii;

use app\models\NivelOrganizacional1;
use app\models\Cieconsulta;
use app\models\Usuarios;

/**
 * ConsultasController implements the CRUD actions for Consultas model.
 */
class ConsultasController extends Controller
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
     * Lists all Consultas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConsultasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if(Yii::$app->user->identity->empresa_all == 0){
            $searchModel->id_empresa = Yii::$app->user->identity->id_empresa;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Consultas model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Consultas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Consultas();
       
        $model->fecha = date('Y-m-d');
        $model->hora_inicio = date('H:i');
        $model->scenario = 'create';
        $model->status = 1;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }
        $model->model_ = 'consultas';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Consultas");
                $model->txt_base64_foto = $param['txt_base64_foto'];

                if($model->envia_form == '1'){ 
                    $model->create_date = date('Y-m-d');
                    $model->create_user = Yii::$app->user->identity->id;
                    //dd($model);
                    //dd($model->id_programa);
                    if($model->tipo == 7){
                        if($model->id_programa){
                            foreach($model->id_programa as $key=>$programa){
                                $programatrab = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['id_programa'=>$programa])->andWhere(['status'=>1])->one();
                                if(!$programatrab){
                                    $programatrab = new ProgramaTrabajador();
                                    $programatrab->id_trabajador = $model->id_trabajador;
                                    $programatrab->id_programa = $programa;
                                    $programatrab->status = 1;
                                    $programatrab->save();
                                }
                            }

                            $test = $this->Arraytocomas( $model->id_programa);
                            $model->id_programa = $test;
                        }



                        if($model->ps_diabetes3){
                            $test = $this->Arraytocomas( $model->ps_diabetes3);
                            $model->ps_diabetes3 = $test;
                        }

                        if($model->ps_hipertension6){
                            $test = $this->Arraytocomas( $model->ps_hipertension6);
                            $model->ps_hipertension6 = $test;
                        }

                        if($model->ps_lactancia7){
                            $test = $this->Arraytocomas( $model->ps_lactancia7);
                            $model->ps_lactancia7 = $test;
                        }

                        if($model->ps_hiperdiabe3){
                            $test = $this->Arraytocomas( $model->ps_hiperdiabe3);
                            $model->ps_hiperdiabe3 = $test;
                        }
                    }

                    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                    if($empresa){
                        $model->id_empresa =  $empresa->id;
                        $model->empresa =  $empresa->comercial;
                    }

                    $trabajador = Trabajadores::find()->where(['id'=>$model->id_trabajador])->one();
                    if($trabajador){
                        $model->sexo =  $trabajador->sexo;
                        $model->area =  $trabajador->id_area;
                    }
                    //dd($param);
                    if($model->diagnosticocie){
                        $ar = ',';
                        foreach($model->diagnosticocie as $k=>$value){
                            $ar .= $value;
                            if($k < (count($model->diagnosticocie)-1)){
                                $ar .= ',';
                            }
                        }
                        $model->diagnosticocie = $ar;
                    }
    
                    if($model->aparatos){
                        $df = ',';
                        foreach($model->aparatos as $k=>$value){
                            $df .= $value;
                            if($k < (count($model->aparatos)-1)){
                                $df .= ',';
                            }
                        }
                        $model->aparatos = $df;
                    }
    
                    $archivo = UploadedFile::getInstance($model,'file_evidencia');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador;
                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'CONSULTA_'.$model->id_trabajador.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                        $model->evidencia = $nombre_archivo; 
                        $model->save();
                    }
    
                    $model->hora_fin = date('H:i');
                    $model->save();



                    define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/');

                    if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){

                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                        $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                
                        $this->actionCarpetas( $directorios);
                        $nombre_foto = $this->saveImagen('CONSENTIMIENTO_'.$model->id.'_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                        $model->foto_web = $nombre_foto;
                        $model->save();
                            
                    }
            
                    if($model->fecha_c == null || $model->fecha_c == '' || $model->fecha_c == ' '){
                        $model->fecha_c = date('Y-m-d');
                        $model->hora_c = date('H:i:s');
                    }


                    //Guardar la firma del trabajador---------------------------------
                    if(isset($model->firma) && $model->firma != 'data:,'){
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                        $this->actionCarpetas($directorios);
                        $nombre_firma = $this->saveFirma($model->firma,$model);
                        $model->firma_ruta = $nombre_firma;
                        $model->save();
                    }

                    if(isset($model->aux_medicamentos) && $model->aux_medicamentos != null && $model->aux_medicamentos != ''){
                        $movimiento = new Movimientos();
                        $movimiento->create_date = date('Y-m-d');
                        $movimiento->id_empresa = $model->id_empresa;
                        $movimiento->e_s = 2;
                        $movimiento->tipo = 6;
                        $movimiento->id_consultorio = $model->id_consultorio;
                        $movimiento->id_consultahc = $model->id;
                        $movimiento->folio = $this->createClave2($movimiento);
                        $movimiento->save();

                        if($movimiento){
                            $movimiento->aux_medicamentos = $model->aux_medicamentos;
                            $this->saveMultiple($movimiento);
                        }
                    }

                    $this->actionActualizacie($model);

                    $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'empresa','nombre_medico');

                    $this->statusPSTrabajador($model->id_trabajador);

                    return $this->redirect(['view', 'id' => $model->id]);
                } else{
                    //dd($model);
                    if($model->envia_empresa == '1' || $model->envia_consultorio == '1'){
                        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                        $fecha = date('Ymd');

                        if($empresa){
                            $model->folio = $this->createClave('CM'.$empresa->abreviacion.$fecha,'app\models\Consultas');
                        
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
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


    private function createClave2($model){
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


    private function saveMultiple($model){
        //dd($model);
        
        $id_medicamentos = [];
        if(isset($model->aux_medicamentos) && $model->aux_medicamentos != null && $model->aux_medicamentos != ''){
            foreach($model->aux_medicamentos as $key => $medicamento){

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
                        if($almacen->stock_unidad>0){
                            if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                                $stock = intval($almacen->stock_unidad / $insumo->unidades_individuales);
                                $almacen->stock = $stock;
                            }
                        } else{
                            $almacen->stock = 0;
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

    private function actualizaAlmacen($model){
        //date_default_timezone_set('America/Mazatlan');
    
        if($model->medicamentos){
            //dd($model->medicamentos);
            foreach($model->medicamentos as $key=>$medicamento){
                $almacen = Almacen::find()->where(['id_consultorio'=>$model->id_consultorio])->andWhere(['id_insumo'=>$medicamento->id_insumo])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();
                if($almacen){

                    $insumo = Insumos::find()->where(['id'=>$almacen->id_insumo])->one();

                    $almacen->stock_unidad = $almacen->stock_unidad - $medicamento->cantidad_unidad;

                    if($insumo){
                        if(isset($insumo->unidades_individuales) &&  $insumo->unidades_individuales != null &&  $insumo->unidades_individuales != '' &&  $insumo->unidades_individuales != " "){
                            $stock = intval($almacen->stock_unidad / $insumo->unidades_individuales);
                            $almacen->stock = $stock;
                        }
                    }
                    $almacen->update_date = date('Y-m-d');

                    $almacen->save();
                }
            }
        }
    }

    /**
     * Updates an existing Consultas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);

        $this->actionActualizacie($model);

        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }
        $model->model_ = 'consultas';


        if($model->tipo == 7){
            if($model->id_programa){
                $array = explode(',', $model->id_programa);
                if($array && count($array)>0){
                    $model->id_programa = $array;
                }
            }

            if($model->ps_diabetes3){
                $array = explode(',', $model->ps_diabetes3);
                if($array && count($array)>0){
                    $model->ps_diabetes3 = $array;
                }
            }

            if($model->ps_hipertension6){
                $array = explode(',', $model->ps_hipertension6);
                if($array && count($array)>0){
                    $model->ps_hipertension6 = $array;
                }
            }

            if($model->ps_lactancia7){
                $array = explode(',', $model->ps_lactancia7);
                if($array && count($array)>0){
                    $model->ps_lactancia7 = $array;
                }
            }

            if($model->ps_hiperdiabe3){
                $array = explode(',', $model->ps_hiperdiabe3);
                if($array && count($array)>0){
                    $model->ps_hiperdiabe3 = $array;
                }
            }
        }

        $movimiento = Movimientos::find()->where(['id_consultahc'=>$model->id])->one();

        if($movimiento){
            //dd($movimiento->medicamentos);
            foreach($movimiento->medicamentos as $key=>$medicamento){
                $almacen = Almacen::find()->where(['id_insumo'=>$medicamento->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['fecha_caducidad'=>$medicamento->fecha_caducidad])->one();
                //dd($almacen);

                if($almacen){
                    $model->aux_medicamentos[$key]['index'] = ($key+1);
                    $model->aux_medicamentos[$key]['id_insumo'] = $almacen->id;
                    $model->aux_medicamentos[$key]['unidad'] = $medicamento->insumo->unidades_individuales;
                    $model->aux_medicamentos[$key]['fecha_caducidad'] =  $almacen->fecha_caducidad;
                    $model->aux_medicamentos[$key]['stock'] = $almacen->stock_unidad;
                    $model->aux_medicamentos[$key]['cantidad'] = $medicamento->cantidad_unidad;
                    $model->aux_medicamentos[$key]['id'] = $medicamento->id;
                }
                
            }
    
        }
        //dd($model);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Consultas");
            $model->txt_base64_foto = $param['txt_base64_foto'];

            $model->update_user = Yii::$app->user->identity->id;

            define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/');

            if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){

                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/';
                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                
                $this->actionCarpetas( $directorios);
                $nombre_foto = $this->saveImagen('CONSENTIMIENTO_'.$model->id.'_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                $model->foto_web = $nombre_foto;
                $model->save();
                            
            }
            
            if($model->fecha_c == null || $model->fecha_c == '' || $model->fecha_c == ' '){
                $model->fecha_c = date('Y-m-d');
                $model->hora_c = date('H:i:s');
            }

            $model->save();

            $this->actionActualizacie($model);

            $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'empresa','nombre_medico');

            $this->statusPSTrabajador($model->id_trabajador);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionActualizacie($model){
        if($model){
            
            $id_cies = [];

            $array = explode(',', $model->diagnosticocie);
    
            foreach($array as $key2=>$cie){
                if($cie != ''){
                    $existente = Cieconsulta::find()->where(['id_consulta'=>$model->id])->andWhere(['id_cie'=>$cie])->one();
                    if(!$existente){
                        $existente = new Cieconsulta();
                    }
                    $existente->id_empresa = $model->id_empresa;
                    $existente->id_nivel1 = $model->id_nivel1;
                    $existente->id_nivel2 = $model->id_nivel2;
                    $existente->id_nivel3 = $model->id_nivel3;
                    $existente->id_nivel4 = $model->id_nivel4;
                    $existente->id_consulta = $model->id;
                    $existente->id_cie = $cie;

                    $existente->fecha = $model->fecha;

                    if($cie){
                        $diag = Diagnosticoscie::findOne($cie);
                        if($diag){
                            $existente->clave = $diag->clave;
                            $existente->diagnostico = $diag->diagnostico;
                        }
                    }

                    $existente->save();

                    if($existente){
                        array_push($id_cies, $existente->id);
                    }
                }
            }

            
            $deletes = Cieconsulta::find()->where(['id_consulta'=>$model->id])->andWhere(['not in','id',$id_cies])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }
        }
    }

    /**
     * Deletes an existing Consultas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model){
            $model->status_baja = 1;
            $model->save(false);

            //$this->saveTrash($model,'Trabajadores');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Consultas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Consultas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Consultas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDiagnosticos($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'diagnostico' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, diagnostico AS diagnostico')
                ->from('diagnosticoscie')
                ->where(['or', ['like','diagnostico', $q],['like','clave', $q]])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);

            $ids= [];
            foreach($out['results'] as $key=>$getid){
                $diag = Diagnosticoscie::findOne($getid['id']);
                if($diag){
                    $out['results'][$key]['diagnostico'] = '('.$diag->clave.') '.$diag->diagnostico;
                }
            }
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'diagnostico' => '('.Diagnosticoscie::find($id)->clave.') '.Diagnosticoscie::find($id)->diagnostico];
        }
        return $out;
    }

    protected function actionCarpetas(&$dir1) {
        foreach($dir1 as $key=>$dir){
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                umask(0);
                chmod($dir, 0777);
            } else {
                umask(0);
                chmod($dir, 0777);
            }
        }
    }

    public function createClave($indicio,$modelo){
        $ultimo_guardado = $modelo::find()->orderBy(['id'=>SORT_DESC])->one();
       
        if($ultimo_guardado){
            $clave = $ultimo_guardado->folio;
            $parte_numerica =intval(str_replace ( $indicio,'',$clave));
            $parte_numerica++;
            $clave_devuelta = $indicio.str_pad($parte_numerica, 6, "0", STR_PAD_LEFT);
        }else{
            $clave_devuelta = $indicio.'000001';
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

    public function actionPdf($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Consultas::findOne($id);
        $model->firmado = $firmado;

        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);

        $pdffile = 'pdf.php';

        if($model->tipo == 4){
            $pdffile = 'pdfincapacidad.php';
        }
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($pdffile,['model' => $model]),
            'options' => [
                // any mpdf options you wish to set
            ],
            'marginTop'=>'20',
            'marginHeader'=>'0',
            'marginLeft'=>'0',
            'marginRight'=>'0',
            'marginBottom'=>'27',
            'marginFooter'=>'0',
          /*   'cssInline' => $css, */
            'cssFile' => 'css/pdf.css',
            'methods' => [
                'SetTitle' => 'CONSULTA CLINICA '.$model->folio.'.pdf',
                'SetSubject' => 'CONSULTA CLINICA',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }


    protected function saveFirma($firma,$model) {
        
        $img = $firma;
        $img = str_replace('data:image/png;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  uniqid() . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
    }


    protected function Arraytocomas($valor) {
        $result = null;
        foreach($valor as $key=>$data){
            $result .= $data;
            if($key < (count($valor)-1)){
                $result .= ',';
            }
        }
        return $result;
    }


    public function actionCalculafecha(){
        $fecha_inicio = Yii::$app->request->post('fecha_inicio');
        $dias = Yii::$app->request->post('dias');
        $fechafin = null;

        if(isset($fecha_inicio) && $fecha_inicio != null &&  $fecha_inicio != '' && isset($dias) && $dias != null &&  $dias != ''){
            $date = Carbon::parse($fecha_inicio.' 00:00:00');//Convertir a Carbon la fecha de contratación
        
            $new_date = $date->addDays(($dias-1));  
            $fechafin = $new_date->format('Y-m-d'); 
        }

        return \yii\helpers\Json::encode(['fechafin' => $fechafin]);
    }


    protected function saveImagen($nombre,$imagen,$model) {
        
        $img = $imagen;
        $img = str_replace('data:image/jpeg;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  $nombre . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
    }


    public function actionConsentimientopdf($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Consultas::findOne($id);

        $image = 'resources/images/empty.png';
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('consentimientopdf.php',['model' => $model]),
            'options' => [
                // any mpdf options you wish to set
            ],
            'marginTop'=>'20',
            'marginHeader'=>'0',
            'marginLeft'=>'0',
            'marginRight'=>'0',
            'marginBottom'=>'27',
            'marginFooter'=>'0',
          /*   'cssInline' => $css, */
            'cssFile' => 'css/pdf.css',
            'methods' => [
                'SetTitle' => 'CONSENTIMIENTO '.$model->nombre.' '.$model->apellidos.'.pdf',
                'SetSubject' => 'Consentimientos',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="'.$image.'"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }


    protected function getNombreinfo($model,$id_empresa,$id_medico,$atributo_empresa,$atributo_medico){
        if($model){
            $get_empresa = Empresas::findOne($id_empresa);
            $get_medico = Usuarios::findOne($id_medico);
            $get_trabajador = Trabajadores::findOne($model->id_trabajador);

            if($get_empresa){
                //$model[$atributo_empresa] = $get_empresa->comercial;
            }

            if($get_medico){
                $model[$atributo_medico] = $get_medico->name;
            }

            if($get_trabajador){
                $model->nombre = $get_trabajador->nombre;
            }

            if($get_trabajador){
                $model->apellidos = $get_trabajador->apellidos;
            }

            $model->save(false);
        }
    }


    public function actionRefreshnames()
    {
        $consultas = Consultas::find()->where(['IS', 'updated', new \yii\db\Expression('NULL')])->limit(2000)->all();
        //dd($consultas);
        if($consultas){
            foreach($consultas as $key=>$model){
                $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'empresa','nombre_medico');
                $model->updated = 1;
                $model->save();
            }
        }
        return $this->redirect(['index']);
    }

    public function getAgeupdated($model,$fecha_doc,$id_trabajador){
        $trabajador = Trabajadores::findOne($id_trabajador);

        if($trabajador && $trabajador->fecha_nacimiento != null && $fecha_doc != null){
            $dateOfBirth = $trabajador->fecha_nacimiento;
            $specificDate = $fecha_doc; // The date you want to calculate the age at

            $ageDetails = $this->calculateAgeAtSpecificDate($dateOfBirth, $specificDate);

            $model->edad = $ageDetails;
            $model->save(false);
        }
    }

    public function calculateAgeAtSpecificDate($dateOfBirth, $specificDate) {
        // Create DateTime objects for the date of birth and the specific date
        $dob = new \DateTime($dateOfBirth);
        $targetDate = new \DateTime($specificDate);

        // Calculate the difference between the two dates
        $interval = $targetDate->diff($dob);

        // Extract years, months, and days from the DateInterval object
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        return $years;
        
        /* return [
        'years' => $years,
        'months' => $months,
        'days' => $days
        ]; */
    }


    private function statusPSTrabajador($id_trabajador){
        $trabajador = Trabajadores::findOne($id_trabajador);

        if($trabajador){
            $ps = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
            $ps_activos = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['status'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
              
            if($ps){
                $trabajador->ps_status = 1;
                $trabajador->save(false);
            } else {
                $trabajador->ps_status = 2;
                $trabajador->ps_activos = 2;
                $trabajador->save(false);
            }

            if($ps_activos){
                $trabajador->ps_activos = 1;
                $trabajador->save(false);
            } else {
                $trabajador->ps_activos = 2;
                $trabajador->save(false);
            }
        }
        
    }

}

