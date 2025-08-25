<?php

namespace app\controllers;

use app\models\Poes;
use app\models\PoesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\Poeestudio;
use yii\web\UploadedFile;
use app\models\Trabajadores;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Empresas;
use app\models\Hccohc;

use app\models\Usuarios;
use app\models\Cuestionario;

use Yii;

use app\models\NivelOrganizacional1;
use app\models\Trashhistory;

use app\models\ProgramaTrabajador;
use app\models\Consultas;

/**
 * PoesController implements the CRUD actions for Poes model.
 */
class PoesController extends Controller
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
     * Lists all Poes models.
     *
     * @return string
     */
    public function actionIndex($id_trabajador = null,$id_empresa = null, $page=null)
    {
        $searchModel = new PoesSearch();

        if(isset($id_trabajador))
        {
            $searchModel->id_worker = $id_trabajador;
        }

        if($id_empresa != null && $id_empresa != '' && $id_empresa != ' '){
            $searchModel->id_empresa = $id_empresa;
        }
        
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all Poes models.
     *
     * @return string
     */
    public function actionIndexexportar($src_empresa,$src_trabajador,$src_condicion,$src_area,$src_puesto,$src_anio,$src_fecha,$src_estudio,$src_diagnostico,$src_evaluacion,$src_categoria,$src_entrega,$src_evidencia)
    {
        //dd($src_empresa,$src_trabajador,$src_condicion,$src_area,$src_puesto,$src_anio,$src_fecha,$src_estudio,$src_diagnostico,$src_evaluacion,$src_entrega,$src_evidencia);
        $searchModel = new PoesSearch();

        $searchModel->id_empresa = $src_empresa;
        $searchModel->id_trabajador = $src_trabajador;
        $searchModel->condicion = $src_condicion;
        $searchModel->id_area = $src_area;
        $searchModel->id_puesto = $src_puesto;
        $searchModel->anio = $src_anio;
        $searchModel->create_date = $src_fecha;
        $searchModel->estudios = $src_estudio;
        $searchModel->diagnosticos = $src_diagnostico;
        $searchModel->evolucion = $src_evaluacion;
        $searchModel->categoria = $src_categoria;
        $searchModel->entrega = $src_entrega;
        $searchModel->documento = $src_evidencia;

        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexexportar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Poes models.
     *
     * @return string
     */
    public function actionFormexportar()
    {
        $model = new Poes(); 
        $model->src_empresa = Yii::$app->user->identity->id_empresa;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);
                
                return $this->redirect(['indexexportar', 
                'src_empresa' => $model->src_empresa, 
                'src_trabajador' => $model->src_trabajador,
                'src_condicion' => $model->src_condicion,
                'src_area' => $model->src_area,
                'src_puesto' => $model->src_puesto,
                'src_anio' => $model->src_anio,
                'src_fecha' => $model->src_fecha,
                'src_estudio' => $model->src_estudio,
                'src_diagnostico' => $model->src_diagnostico,
                'src_evaluacion' => $model->src_evaluacion,
                'src_categoria' => $model->src_categoria,
                'src_entrega' => $model->src_entrega,
                'src_evidencia' => $model->src_evidencia,]);
            }
        }

        return $this->render('exportar', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Poes model.
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
     * Creates a new Poes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Poes(); 
        //date_default_timezone_set('America/Mazatlan');
        $model->anio = date('Y');
        $model->scenario = 'create';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }
        $model->model_ = 'poes';


        $servicios_iniciales = Servicios::find()->where(['in','id',[1,21]])->all();
        
        foreach($servicios_iniciales as $key=>$estudio) {
            $model->aux_estudios[$key]['categoria'] = $estudio->id_tiposervicio;
            $model->aux_estudios[$key]['showcategoria'] = $estudio->id_tiposervicio;
            $model->aux_estudios[$key]['estudio'] = $estudio->id;
            $model->aux_estudios[$key]['id'] = null;
            $model->aux_estudios[$key]['id_hc'] = null;
            $model->aux_estudios[$key]['id_poe'] = $model->id;
           
        }


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Poes");

                $model->tipo_poe = $param['tipo_poe'];
                $model->envia_form = $param['envia_form'];
                $model->model_ = $param['model_'];
                $model->id_empresa = $param['id_empresa'];
                $model->anio = $param['anio'];
                $model->id_trabajador = $param['id_trabajador'];
                $model->id_nivel1 = $param['id_nivel1'];
                $model->id_nivel2 = $param['id_nivel2'];
                $model->id_nivel3 = $param['id_nivel3'];
                $model->id_nivel4 = $param['id_nivel4'];
                $model->nombre = $param['nombre'];
                $model->apellidos = $param['apellidos'];
                $model->num_imss = $param['num_imss'];
                $model->num_trabajador = $param['num_trabajador'];
                $model->aux_estudios = $param['aux_estudios'];
                $model->observaciones = $param['observaciones'];
                //dd($model,$param);

                if($model->envia_form == '1'){
                    //dd($model);
                    $trabajador = Trabajadores::find()->where(['id'=>$model->id_trabajador])->one();
                    $model->sexo = $trabajador->sexo;
                    $model->fecha_nacimiento = $trabajador->fecha_nacimiento;
                    $model->id_puesto = $trabajador->id_puesto;
                    $model->id_area = $trabajador->id_area;

                    if($trabajador){
                        $model->id_nivel1 = $trabajador->id_nivel1;
                        $model->id_nivel2 = $trabajador->id_nivel2;
                        $model->id_nivel3 = $trabajador->id_nivel3;
                        $model->id_nivel4 = $trabajador->id_nivel4;

                        $model->save(false);
                    }
                
                    if($model && isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
                        $model->status = 0;
                        $model->create_user = Yii::$app->user->identity->id;
                        $model->create_date = date('Y-m-d');
                        $model->save(false);
    
                        $this->saveMultiple($model);

                        $this->actionOrdenarpoe($model);

                        $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'nombre_empresa','nombre_medico');
    
                        try {
                            $this->cumplimientosTrabajador($model->id_trabajador);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }

                        return $this->redirect(['index']);
                    }

                } else {
                    $model->id_empresa = $param['id_empresa'];
                    $model->anio = $param['anio'];
                    $empresa = Empresas::findOne($model->id_empresa);
                    if($empresa){
                        $model->nombre_empresa = $empresa->comercial;
                    }

                    foreach($model->aux_estudios as $key=>$estudio) {
                        $model->aux_estudios[$key]['showcategoria'] = $estudio['categoria'];
                    }

                    //dd($model,$param);
                    //dd($model,$param);
                    return $this->render('create', [
                        'model' => $model,
                    ]);
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


    private function crearHcporevidencia($model){
        $estudios = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['id_estudio'=>1])->all();
        //dd($estudios);
        if($estudios){
            foreach($estudios as $key=>$estudio){
                if(($estudio->id_hc == null || $estudio->id_hc == '' || $estudio->id_hc == '') && ($estudio->evidencia != null || $estudio->evidencia2 != null|| $estudio->evidencia3 != null)){
                    //dd($estudio);
                    $hc = new Hccohc();
                    $hc->id_poe = $estudio->id_poe;
                    $hc->id_estudio_poe = $estudio->id;

                    $poe = Poes::findOne($estudio->id_poe);
                    
                    
                    if($poe){
                        $hc->id_empresa =  $poe->id_empresa;

                        //['1'=>'NUEVO INGRESO','2'=>'POES PERIODICOS','3'=>'SALIDA'] POE
                        //['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'] HC
                        if($poe->tipo_poe != null && $poe->tipo_poe != '' && $poe->tipo_poe != ' '){
                            if($poe->tipo_poe == 1){
                                $hc->examen = 1;
                            } else if($poe->tipo_poe == 2){
                                $hc->examen = 3;
                            } else if($poe->tipo_poe == 3){
                                $hc->examen = 5;
                            }
                        }
                
                        if($poe->trabajador){
                            $hc->id_trabajador = $poe->trabajador->id;
                            $hc->id_nivel1 = $poe->trabajador->id_nivel1;
                            $hc->id_nivel2 = $poe->trabajador->id_nivel2;
                            $hc->id_nivel3 = $poe->trabajador->id_nivel3;
                            $hc->id_nivel4 = $poe->trabajador->id_nivel4;

                            $hc->nombre = $poe->trabajador->nombre;
                            $hc->apellidos = $poe->trabajador->apellidos;
                            $hc->sexo = $poe->trabajador->sexo;
                            $hc->fecha_nacimiento = $poe->trabajador->fecha_nacimiento;
                            $hc->edad = $poe->trabajador->edad;

                            $hc->num_trabajador = $poe->trabajador->num_trabajador;
                            $hc->area = $poe->trabajador->id_area;
                            $hc->puesto = $poe->trabajador->id_puesto;

                            $hc->nivel_lectura = $poe->trabajador->nivel_lectura;
                            $hc->nivel_escritura = $poe->trabajador->nivel_escritura;
                            $hc->estado_civil = $poe->trabajador->estado_civil;
                            $hc->grupo = $poe->trabajador->grupo;
                            $hc->rh = $poe->trabajador->rh;

                            $hc->tipo_hc_poe = 2;
                            $hc->poe_doc1 = $estudio->evidencia;
                            $hc->poe_doc2 = $estudio->evidencia2;
                            $hc->poe_doc3 = $estudio->evidencia3;
                            $hc->fecha = $estudio->fecha;

                            $hc->save(false);

                            if($hc){
                                $estudio->id_hc = $hc->id;
                                $estudio->save(false);
                            }
                    
                        }
                    }
                } else if($estudio->evidencia != null || $estudio->evidencia2 != null|| $estudio->evidencia3 != null) {
                    $hc = Hccohc::find()->where(['id_poe'=>$estudio->id_poe])->andWhere(['id_estudio_poe'=>$estudio->id])->one();
                    if($hc){
                        $hc->tipo_hc_poe = 2;
                        $hc->poe_doc1 = $estudio->evidencia;
                        $hc->poe_doc2 = $estudio->evidencia2;
                        $hc->poe_doc3 = $estudio->evidencia3;
                        $hc->save(false);
                    }
                }
            }
        }
    }


    private function actionOrdenarpoe($model){
        if($model){
            $orden = 0;
            
            //$servicios_iniciales = Servicios::find()->where(['in','id',[1,21]])->all();
            
            //buscar hc´s primero
            $estudios = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['id_estudio'=>1])->all();
            if($estudios){
                foreach($estudios as $key=>$estudio){
                    $estudio->orden = $orden;
                    $estudio->save(false);
                    $orden ++;
                }
            }

            //buscar los que no sean hc ni cal
            $estudios = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['not in','id_estudio',[1,21]])->all();
            if($estudios){
                foreach($estudios as $key=>$estudio){
                    $estudio->orden = $orden;
                    $estudio->save(false);
                    $orden ++;
                }
            }

            //buscar cals
            $estudios = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['id_estudio'=>21])->all();
            if($estudios){
                foreach($estudios as $key=>$estudio){
                    $estudio->orden = $orden;
                    $estudio->save(false);
                    $orden ++;
                }
            }
        }

    }


    private function saveMultiple($model){

        //dd($model);

        if(Yii::$app->user->identity->activo_eliminar != 2 || $model->scenario == 'create'){
            $id_estudios = [];
        if(isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
            foreach($model->aux_estudios as $key => $estudio){

                if(isset($estudio['id']) && $estudio['id'] != null && $estudio['id'] != ''){
                    $pe = Poeestudio::find()->where(['id'=> $estudio['id']])->one();
                } else {
                    $pe = new Poeestudio();
                    $pe->id_poe = $model->id;
                }

                if(isset($estudio['categoria']) || isset($estudio['otracategoria'])){
                   
                    if($estudio['categoria'] == '-1'){
                        if($estudio['otracategoria'] != "" && $estudio['otracategoria'] != null){
                            $tipo = new TipoServicios();
                            $tipo->nombre = $estudio['otracategoria'];
                            $tipo->save();
                            //dd($tipo);
                        }
                    }else{
                        $tipo = TipoServicios::find()->where(['id'=>$estudio['categoria']])->one();
                    }

                    if(isset($tipo)){
                        if($estudio['estudio'] == 0){
                            if($estudio['otroestudio'] != "" && $estudio['otroestudio'] != null){
                                $nestudio = new Servicios();
                                $nestudio->nombre = $estudio['otroestudio'];
                                $nestudio->id_tiposervicio = $tipo->id;
                                $nestudio->save();
                            }
                        }else{
                            $nestudio = Servicios::find()->where(['id'=>$estudio['estudio']])->one();
                        }
                    }

                    if(isset($tipo) && isset($nestudio)){
                        $pe->id_estudio = $nestudio->id;
                        $pe->id_tipo = $tipo->id;
                        $pe->obligatorio = $estudio['obligatorio'];
                        $pe->fecha = $estudio['fecha'];
                        $pe->hora = date('H:i:s');
                        $pe->comentario = $estudio['comentarios'];

                        
                        if($estudio['diagnostico'] == null || $estudio['diagnostico'] == ''){
                            $pe->condicion = 100;
                        } else{
                            $pe->condicion = $estudio['diagnostico'];
                        }
                        if($estudio['evolucion'] == null || $estudio['evolucion'] == ''){
                            $pe->evolucion = 100;
                        } else{
                            $pe->evolucion = $estudio['evolucion'];
                        }
                       
                        $pe->status = 0;
                        $pe->save();

                        if('aux_estudios' . '[' . $key . '][' . 'evidencia' . ']' != ""){
               
                            $archivo = 'aux_estudios' . '[' . $key . '][' . 'evidencia' . ']';
                            $save_archivo = UploadedFile::getInstance($model, $archivo);
                            if (!is_null($save_archivo)) {
    
                                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
                                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                                $this->actionCarpetas( $directorios);
    
                                if($pe->evidencia != null || $pe->evidencia != ''){
                                    try {
                                        unlink('resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$pe->evidencia);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    
                                }
    
                                $ruta_archivo = $pe->id_estudio .'_'.date('YmdHis'). '.' . $save_archivo->extension;
                                $save_archivo->saveAs($directorios[4] . '/' . $ruta_archivo);
                                $pe->evidencia= $ruta_archivo;
                                
                            }
                            $pe->save();
                        }

                        if('aux_estudios' . '[' . $key . '][' . 'evidencia2' . ']' != ""){
               
                            $archivo = 'aux_estudios' . '[' . $key . '][' . 'evidencia2' . ']';
                            $save_archivo = UploadedFile::getInstance($model, $archivo);
                            if (!is_null($save_archivo)) {
    
                                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
                                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                                $this->actionCarpetas( $directorios);
    
                                if($pe->evidencia2 != null || $pe->evidencia2 != ''){
                                    try {
                                        unlink('resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$pe->evidencia2);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    
                                }
    
                                $ruta_archivo = $pe->id_estudio .'_EVIDENCIA2'.'_'.date('YmdHis'). '.' . $save_archivo->extension;
                                $save_archivo->saveAs($directorios[4] . '/' . $ruta_archivo);
                                $pe->evidencia2= $ruta_archivo;
                                
                            }
                            $pe->save();
                        }


                        if('aux_estudios' . '[' . $key . '][' . 'evidencia3' . ']' != ""){
               
                            $archivo = 'aux_estudios' . '[' . $key . '][' . 'evidencia3' . ']';
                            $save_archivo = UploadedFile::getInstance($model, $archivo);
                            if (!is_null($save_archivo)) {
    
                                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
                                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                                $this->actionCarpetas( $directorios);
    
                                if($pe->evidencia3 != null || $pe->evidencia3 != ''){
                                    try {
                                        unlink('resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$pe->evidencia3);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    
                                }
    
                                $ruta_archivo = $pe->id_estudio .'_EVIDENCIA3'.'_'.date('YmdHis'). '.' . $save_archivo->extension;
                                $save_archivo->saveAs($directorios[4] . '/' . $ruta_archivo);
                                $pe->evidencia3= $ruta_archivo;
                                
                            }
                            $pe->save();
                        }


                        if($pe){
                            array_push($id_estudios, $pe->id);
                        }
                    }
                    
                }
            }
        }

        $deletes = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }
        }


        if($model){
            if($model->tipo_consentimiento == 1){//TIPO 1, se llena el consentimiento en un form
                if(($model->uso_consentimiento == 1 || $model->uso_consentimiento == 2) && ($model->retirar_consentimiento == 1 || $model->retirar_consentimiento == 2)){
                    $model->tiene_consentimiento = 1;
                    $model->save(false);
                } else {
                    $model->tiene_consentimiento = 3;
                    $model->save(false);
                }
            } else if($model->tipo_consentimiento == 2){
                if($model->evidencia_consentimiento != null && $model->evidencia_consentimiento != '' && $model->evidencia_consentimiento != ' '){
                    $model->tiene_consentimiento = 1;
                    $model->save(false);
                } else {
                    $model->tiene_consentimiento = 3;
                    $model->save(false);
                }
            } else {
                    $model->tiene_consentimiento = 3;
                    $model->save(false);
            }
        }

    }

    /**
     * Updates an existing Poes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        $this->actionOrdenarpoe($model);

        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);

        //$this->crearHcporevidencia($model);


        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }
         $model->model_ = 'poes';


        if($model->trabajador){
            $trabajador = $model->trabajador;

            if($model->id_nivel1 == null || $model->id_nivel1 == '' || $model->id_nivel1 == ' '){
                $model->id_nivel1 = $trabajador->id_nivel1;
            }
            if($model->id_nivel2 == null || $model->id_nivel2 == '' || $model->id_nivel2 == ' '){
                $model->id_nivel2 = $trabajador->id_nivel2;
            }
            if($model->id_nivel3 == null || $model->id_nivel3 == '' || $model->id_nivel3 == ' '){
                $model->id_nivel3 = $trabajador->id_nivel3;
            }
            if($model->id_nivel4 == null || $model->id_nivel4 == '' || $model->id_nivel4 == ' '){
                $model->id_nivel4 = $trabajador->id_nivel4;
            }
            $model->save(false);
             
        }

        /* if($model->trabajador && $model->trabajador->num_trabajador){
           $model->num_trabajador = $model->trabajador->num_trabajador;
           $model->num_imss = $model->trabajador->num_imss;
           $model->save();
        } */

        foreach($model->estudios as $key=>$estudio){
            $model->aux_estudios[$key]['categoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['showcategoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_estudios[$key]['fecha'] = $estudio->fecha;
            $model->aux_estudios[$key]['diagnostico'] = $estudio->condicion;
            $model->aux_estudios[$key]['evolucion'] = $estudio->evolucion;
            $model->aux_estudios[$key]['comentarios'] = $estudio->comentario;
            $model->aux_estudios[$key]['obligatorio'] = $estudio->obligatorio;
            $model->aux_estudios[$key]['id_hc'] = $estudio->id_hc;
            $model->aux_estudios[$key]['id_poe'] = $estudio->id_poe;

            if($estudio->evidencia != '' && $estudio->evidencia != null){
                $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia;
            }else{
                $model->aux_estudios[$key]['doc'] = null;
            }

            if($estudio->evidencia2 != '' && $estudio->evidencia2 != null){
                $model->aux_estudios[$key]['doc2'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia2;
            }else{
                $model->aux_estudios[$key]['doc2'] = null;
            }

            if($estudio->evidencia3 != '' && $estudio->evidencia3 != null){
                $model->aux_estudios[$key]['doc3'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio->evidencia3;
            }else{
                $model->aux_estudios[$key]['doc3'] = null;
            }
            
            $model->aux_estudios[$key]['id'] = $estudio->id;
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            $request = Yii::$app->request;
            $param = $request->getBodyParam("Poes");

            $model->tipo_poe = $param['tipo_poe'];
            $model->envia_form = $param['envia_form'];
            $model->model_ = $param['model_'];
            $model->id_empresa = $param['id_empresa'];
            $model->anio = $param['anio'];
            $model->id_trabajador = $param['id_trabajador'];
            $model->id_nivel1 = $param['id_nivel1'];
            $model->id_nivel2 = $param['id_nivel2'];
            $model->id_nivel3 = $param['id_nivel3'];
            $model->id_nivel4 = $param['id_nivel4'];
            $model->nombre = $param['nombre'];
            $model->apellidos = $param['apellidos'];
            $model->num_imss = $param['num_imss'];
            $model->num_trabajador = $param['num_trabajador'];
            $model->aux_estudios = $param['aux_estudios'];
            $model->observaciones = $param['observaciones'];

            if($model->envia_form == '1'){
                if($model && isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
                    $model->update_user = Yii::$app->user->identity->id;
                    $model->update_date = date('Y-m-d');
                    $model->save(false);
    
                    $this->saveMultiple($model);

                    $this->actionOrdenarpoe($model);

                     $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'nombre_empresa','nombre_medico');

                    //$this->crearHcporevidencia($model);

                    try {
                        $this->cumplimientosTrabajador($model->id_trabajador);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    
                    return $this->redirect(['index']);
                }
            }  else {
                $model->id_empresa = $param['id_empresa'];
                $model->anio = $param['anio'];
                $empresa = Empresas::findOne($model->id_empresa);
                if($empresa){
                    $model->nombre_empresa = $empresa->comercial;
                }

                foreach($model->aux_estudios as $key=>$estudio) {
                    $model->aux_estudios[$key]['showcategoria'] = $estudio['categoria'];
                }

                //dd($model,$param);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Entrega de Resultados an existing Poes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEntregaresultados($id)
    {
        $model = $this->findModel($id);
        //date_default_timezone_set('America/Monterrey');
        if(!isset($model->fecha_entrega) || $model->fecha_entrega == null || $model->fecha_entrega == ''){
            $model->fecha_entrega = date('Y-m-d');
        }
        if(!isset($model->hora_entrega) || $model->hora_entrega == null || $model->hora_entrega == ''){
            $model->hora_entrega = date('H:i');
        }

        foreach($model->estudios as $key=>$estudio){
            $model->aux_entregados[$estudio->id] = $estudio->status_entrega;
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            //dd($model);
            foreach($model->aux_entregados as $id=>$status){
                $estudio = Poeestudio::find()->where(['id'=>$id])->one();
                if($estudio){
                    $estudio->status_entrega = $status;
                    $estudio->save();
                }
                //dd('ID: '.$id.' | Status: '.$status);
            }

            $model->status_entrega = 1;
            $model->save();

            //Guardar la firma del trabajador---------------------------------
            if(isset($model->firma) && $model->firma != 'data:,'){
                define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/');
                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];

                $this->actionCarpetas($directorios);
                $nombre_firma = $this->saveFirma($model->firma,$model);
                $model->firma_ruta = $nombre_firma;
                $model->save();
            }
            return $this->redirect(['index']);
        }

        return $this->render('entrega', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Poes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id,$company=null,$page=null)
    {
        $model = $this->findModel($id);

        if($model){
            $model->status_backup = $model->status;
            $model->status = 2;
            $model->save();

            $this->saveTrash($model,'Poes');
        }

        if($page != null && $page != '' && $page != ' '){
            $page = $page +1;
        }

        $url = Url::to(['index', 'id_empresa' => $company,'page'=>$page]);
        return $this->redirect($url);
    }

    /**
     * Finds the Poes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Poes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Poes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionTraerestudios(){
        $id = Yii::$app->request->post('id');
        $tipo = TipoServicios::find()->where(['id'=>$id])->one();
       
        $estudios = [];

        if($tipo){
            $estudios = Servicios::find()->where(['id_tiposervicio'=>$tipo->id])->select(['id','nombre'])->all();
        }
       
        return \yii\helpers\Json::encode(['estudios' => $estudios]);
    }

    public function actionTraercategoria(){
        $id = Yii::$app->request->post('id');
        $servicio = Servicios::find()->where(['id'=>$id])->one();
       
        $categoria = null;

        if($servicio){
            $categoria = $servicio->id_tiposervicio;
        }
       
        return \yii\helpers\Json::encode(['categoria' => $categoria]);
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

    public function actionPdf($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Poes::findOne($id);
        $model->firmado = $firmado;


        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('pdf.php',['model' => $model]),
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
                'SetTitle' => 'ENTREGA DE RESULTADOS '.$model->nombre.'_'.$model->apellidos.'.pdf',
                'SetSubject' => 'ENTREGA DE RESULTADOS',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'Entrega de Resultados',
            ]
        ]);

        
        return $pdf->render();
        
    }


    public function actionCard($id)
    {
        //date_default_timezone_set('America/Mazatlan');
        $poeanterior = $this->findModel($id);

        return $this->renderAjax('poe', [
            'poeanterior' => $poeanterior,
        ]);
    }


    public function actionConsentimiento($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = $this->findModel($id);
        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Poes");
            $model->txt_base64_foto = $param['txt_base64_foto'];
               
            define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/');

            if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){

                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                
                $this->actionCarpetas( $directorios);
                $nombre_foto = $this->saveImagen('FOTOCAMARA_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                $model->foto_web = $nombre_foto;
                $model->save();
                            
            }
            
            if($model->fecha == null || $model->fecha == '' || $model->fecha == ' '){
                $model->fecha = date('Y-m-d');
                $model->hora = date('H:i:s');
            }
            $model->save(false);


            $archivo = UploadedFile::getInstance($model,'file_evidencia_consentimiento');
            $dir0 = __DIR__ . '/../web/resources/Empresas/';
            $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
            $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
            $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador;
            $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/';
            $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
            $this->actionCarpetas( $directorios);
                    
            if($archivo){
                $nombre_archivo = 'EVIDENCIACONSENTIMIENTO_'.$model->id_trabajador.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                $model->evidencia_consentimiento = $nombre_archivo; 
                $model->save();
            }

            if($model){
                if($model->tipo_consentimiento == 1){//TIPO 1, se llena el consentimiento en un form
                    if(($model->uso_consentimiento == 1 || $model->uso_consentimiento == 2) && ($model->retirar_consentimiento == 1 || $model->retirar_consentimiento == 2)){
                        $model->tiene_consentimiento = 1;
                        $model->save(false);
                    } else {
                        $model->tiene_consentimiento = 3;
                        $model->save(false);
                    }
                } else if($model->tipo_consentimiento == 2){
                    //dd('TIPO 2');
                    if($model->evidencia_consentimiento != null && $model->evidencia_consentimiento != '' && $model->evidencia_consentimiento != ' '){
                        $model->tiene_consentimiento = 1;
                        $model->save(false);
                    } else {
                        $model->tiene_consentimiento = 3;
                        $model->save(false);
                    }
                } else {
                    $model->tiene_consentimiento = 3;
                    $model->save(false);
                }
            }
            //dd($model);
            return $this->redirect(['index']);
        }

        return $this->render('consentimiento', [
            'model' => $model,
        ]);
    }

    public function actionConsentimientopdf($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Poes::findOne($id);

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


    public function actionEnlazar($id_poe,$id_estudio)
    {
        date_default_timezone_set('America/Costa_Rica');
        $poe = $this->findModel($id_poe);
        $estudio = Poeestudio::findOne($id_estudio);
        $trabajador= null;

        if($poe){
            $trabajador= Trabajadores::findOne($poe->id_trabajador);
        }

        if($poe && $estudio){
            $hc_actual = Hccohc::find()->where(['id_poe'=>$poe->id])->andWhere(['id_estudio_poe'=>$estudio->id])->one();
            if($hc_actual){
                $estudio->hc_enlazar[$hc_actual->id] = 1;
            }
        }

        if ($this->request->isPost) {
            if ($estudio->load($this->request->post())) {
                //dd($estudio);
                if($estudio->hc_enlazar != null && count($estudio->hc_enlazar)){
                    foreach($estudio->hc_enlazar as $key=>$hc){
                        
                        if($hc == 1){
                            
                            $hc_actual = Hccohc::find()->where(['id_poe'=>$poe->id])->andWhere(['id_estudio_poe'=>$estudio->id])->one();
                            if($hc_actual){
                                $hc_actual->id_poe = null;
                                $hc_actual->id_estudio_poe = null;
                                $hc_actual->save(false);
                            }

                            $estudio->id_hc = $key;
                            $estudio->save(false);

                            $hc_nueva = Hccohc::findOne($key);
                            //dd($hc_nueva);
                            if($hc_nueva){
                                $hc_nueva->id_poe = $poe->id;
                                $hc_nueva->id_estudio_poe = $estudio->id;
                                $hc_nueva->save(false);
                                //dd($hc_nueva);
                            }
                            break;
                        }
                    }
                }
                $estudio->save(false);
    
                return $this->redirect(['poes/update','id'=>$poe->id]);
            }
        }

        //dd('entra aqui');
        return $this->renderAjax('enlazar', [
            'estudio' => $estudio,
            'trabajador' => $trabajador,
        ]);
    }


     protected function getNombreinfo($model,$id_empresa,$id_medico,$atributo_empresa,$atributo_medico){
        if($model){
            $get_empresa = Empresas::findOne($id_empresa);
            $get_medico = Usuarios::findOne($id_medico);

            if($get_empresa){
                $model[$atributo_empresa] = $get_empresa->comercial;
            }

            if($get_medico){
                $model[$atributo_medico] = $get_medico->name;
            }

            $model->save(false);

            //dd($get_empresa,$get_medico,$model);
        }
    }


    public function actionRefreshnames()
    {
        $poes = Poes::find()->all();
        ///dd($hcs);
        if($poes){
            foreach($poes as $key=>$model){
                $this->getNombreinfo($model,$model->id_empresa,$model->create_user,'nombre_empresa','nombre_medico');
            }
        }
        return $this->redirect(['index']);
    }


    public function cumplimientosTrabajador($id_trabajador){
        //'puesto_cumplimiento' => Yii::t('app', 'Cumplimiento Puesto'),
        //'riesgo_cumplimiento' => Yii::t('app', 'Cumplimiento Riesgo'),
        //'programasalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programa de Salud'),
        //'expediente_cumplimiento' => Yii::t('app', 'Cumplimiento Expediente Medicamento'),

        // 'hc_cumplimiento' => Yii::t('app', 'Cumplimiento HC'),
        // 'poe_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Médicos'),
        // 'cuestionario_cumplimiento' => Yii::t('app', 'Cumplimiento Cuestionario Nórdico'),
        // 'antropometrica_cumplimiento' => Yii::t('app', 'Cumplimiento Medidas Antropométricas'),
        // 'programassalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programas de Salud'),
        // 'porcentaje_cumplimiento' => Yii::t('app', 'Cumplimiento Trabajador'),
        date_default_timezone_set('America/Costa_Rica');
        $hoy = date('Y-m-d');
        $year_before = strtotime($hoy.' -1 year');
        $year_before = date("Y-m-d", $year_before);


        $no_cumplimientos = 9;

        $solo_anio = date('Y');

        $trabajador = Trabajadores::findOne($id_trabajador);

        //dd('hoy: '.$hoy.' | hace 1 año: '.$year_before.' | solo_anio: '.$solo_anio);
          
        if($trabajador){
            // 'puesto_cumplimiento' => Yii::t('app', 'Cumplimiento Puesto'),
            $puesto = Puestostrabajo::find()->where(['id'=>$trabajador->id_puesto])->one();
            if($puesto){
                $trabajador->puesto_cumplimiento = 100;
            } else {
                $trabajador->puesto_cumplimiento = 0;
            }


            // 'riesgo_cumplimiento' => Yii::t('app', 'Cumplimiento Riesgo'),
            if($puesto){
                if($puesto->riesgos){
                    $trabajador->riesgo_cumplimiento = 100;
                } else {
                    $trabajador->riesgo_cumplimiento = 0;
                }
            } else {
                $trabajador->riesgo_cumplimiento = 0;
            }


            // 'riesgohistorico_cumplimiento' => Yii::t('app', 'Cumplimiento  Histórico'),
            $puesto = Puestotrabajohriesgo::find()->where(['id_trabajador'=>$trabajador->id])->orderBy(['fecha_inicio'=>SORT_ASC,'create_date'=>SORT_ASC])->all();
            if($puesto){
                $trabajador->riesgohistorico_cumplimiento = 100;
            } else {
                $trabajador->riesgohistorico_cumplimiento = 0;
            }


            // 'programasalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programa de Salud'),
            $programastrabajador = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['status'=>1])->all();
            if($programastrabajador){
                $cantidad_ps = count($programastrabajador);
                $sumatoria_ps = 0;
                $porcentaje_ps = 0;

                foreach($programastrabajador as $j=>$programa){
                    $consultas = Consultas::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['tipo'=>7])->andWhere(['id_programa'=>$programa->id_programa])->andWhere(['>=','fecha',$year_before])->all();

                    if($consultas){
                        $sumatoria_ps += 100;
                    }
                }

                if($cantidad_ps > 0){
                    $porcentaje_ps = $sumatoria_ps/$cantidad_ps;
                    $porcentaje_ps = number_format($porcentaje_ps, 2, '.', ',');
                }
                $trabajador->programasalud_cumplimiento = $porcentaje_ps;
            } else {
                $trabajador->programasalud_cumplimiento = 100;
            }


            // 'expediente_cumplimiento' => Yii::t('app', 'Cumplimiento Expediente Médico'),
            //['1'=>'CUMPLE','2'=>'NO CUMPLE','0'=>'PENDIENTE']
            if($trabajador->status_documentos == 1){
                $trabajador->expediente_cumplimiento = 100;
            } else {
                $trabajador->expediente_cumplimiento = 0;
            }


            // 'hc_cumplimiento' => Yii::t('app', 'Cumplimiento HC'),
            $hc = hccohc::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['in','status',[1,2]])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->andWhere(['>=','fecha',$year_before])->one();
            if($hc){
                $trabajador->hc_cumplimiento = 100;
            } else {
                $trabajador->hc_cumplimiento = 0;
            }


            // 'poe_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Médicos'),
            $poes = Poes::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['<>','status',2])->andWhere(['>=','anio',$solo_anio])->orderBy(['id'=>SORT_DESC])->one();
            if($poes){
                //->andWhere(['status_entrega'=>1])
                if($poes->estudios){
                    $cantidad_estudios = count($poes->estudios);
                    $cantidad_estudios += 1;
                    $sumatoria_estudios = 0;
                    $porcentaje_estudios = 0;

                    foreach($poes->estudios as $j=>$poeestudio){
                        if($poeestudio->obligatorio == 1){
                            if((isset($poeestudio->condicion) && $poeestudio->condicion != null && $poeestudio->condicion != '') && (isset($poeestudio->evolucion) && $poeestudio->evolucion != null && $poeestudio->evolucion != '') && ($poeestudio->evidencia != null || $poeestudio->evidencia2 != null || $poeestudio->evidencia3 != null)){
                                $sumatoria_estudios += 100;
                            }
                        } else {
                            $sumatoria_estudios += 100;
                        }
                    }

                    if($poes->status_entrega == 1){
                        $sumatoria_estudios += 100;
                    }


                    if($cantidad_estudios > 0){
                        $porcentaje_estudios = $sumatoria_estudios/$cantidad_estudios;
                        $porcentaje_estudios = number_format($porcentaje_estudios, 2, '.', ',');
                    }

                    $trabajador->poe_cumplimiento = $porcentaje_estudios;

                } else {
                    $trabajador->poe_cumplimiento = 0;
                }
            } else {
                $trabajador->poe_cumplimiento = 0;
            }


            // 'cuestionario_cumplimiento' => Yii::t('app', 'Cumplimiento Cuestionario Nórdico'),
            $cuestionario = Cuestionario::find()->where(['id_paciente'=>$id_trabajador])->andWhere(['id_tipo_cuestionario'=>1])->andWhere(['>=','fecha_cuestionario',$year_before])->one();
            if($cuestionario){
                $trabajador->cuestionario_cumplimiento = 100;
            } else {
                $trabajador->cuestionario_cumplimiento = 0;
            }


            // 'antropometrica_cumplimiento' => Yii::t('app', 'Cumplimiento Medidas Antropométricas'),
            $antropometrica = Cuestionario::find()->where(['id_paciente'=>$id_trabajador])->andWhere(['id_tipo_cuestionario'=>4])->one();
            if($antropometrica){
                $trabajador->antropometrica_cumplimiento = 100;
            } else {
                $trabajador->antropometrica_cumplimiento = 0;
            }


            $sumatoria = $trabajador->puesto_cumplimiento + $trabajador->riesgo_cumplimiento +$trabajador->riesgohistorico_cumplimiento+ $trabajador->programasalud_cumplimiento + $trabajador->expediente_cumplimiento + $trabajador->hc_cumplimiento + $trabajador->poe_cumplimiento +  $trabajador->cuestionario_cumplimiento + $trabajador->antropometrica_cumplimiento;
            $porcentaje = ($sumatoria/$no_cumplimientos);
            $porcentaje = number_format($porcentaje, 2, '.', ',');

            $status_cumplimiento = 0;
            $trabajador->porcentaje_cumplimiento = $porcentaje;

            if($porcentaje >= 50){
                $status_cumplimiento = 1;
            } else {
                $status_cumplimiento = 0;
            }

            $trabajador->status_cumplimiento = $status_cumplimiento;
            $trabajador->refreshupdated_5 = 1;
            $trabajador->save(false);
            //dd('$trabajador',$trabajador,'$hc',$hc,'$poes',$poes,'$cuestionario',$cuestionario,'$antropometrica',$antropometrica,'$sumatoria',$sumatoria,'$porcentaje',$porcentaje,'$status_cumplimiento',$status_cumplimiento);
        }

    }


    public function saveTrash($model,$tipo_modelo){
        $modelos = [
            '0' => 'AlergiaTrabajador',
            '1' => 'Almacen',
            '2' => 'Areas',
            '3' => 'Areascuestionario',
            '4' => 'Bitacora',
            '5' => 'Cargasmasivas',
            '6' => 'Configconsentimientos',
            '7' => 'Configuracion',
            '8' => 'Consentimientos',
            '9' => 'Consultas',
            '10' => 'Consultorios',
            '11' => 'ContactForm',
            '12' => 'Cuestionario',
            '13' => 'DetalleCuestionario',
            '14' => 'Detallehc',
            '15' => 'Detallemovimiento',
            '16' => 'Detalleordenpoe',
            '17' => 'Diagnosticoscie',
            '18' => 'Documentacion',
            '19' => 'DocumentoTrabajador',
            '20' => 'Empresamails',
            '21' => 'Empresas',
            '22' => 'Epps',
            '23' => 'Estudios',
            '24' => 'ExtraccionBd',
            '25' => 'Firmaempresa',
            '26' => 'Firmas',
            '27' => 'Hccohc',
            '28' => 'Hccohcestudio',
            '29' => 'Historialdocumentos',
            '30' => 'Historicoes',
            '31' => 'Insumos',
            '32' => 'Insumostockmin',
            '33' => 'Lineas',
            '34' => 'LoginForm',
            '35' => 'Lotes',
            '36' => 'Mantenimientoactividad',
            '37' => 'Mantenimientocomponentes',
            '38' => 'Mantenimientos',
            '39' => 'Maquinaria',
            '40' => 'Maquinariesgo',
            '41' => 'Medidas',
            '42' => 'Movimientos',
            '43' => 'NivelOrganizacional1',
            '44' => 'NivelOrganizacional2',
            '45' => 'NivelOrganizacional3',
            '46' => 'NivelOrganizacional4',
            '47' => 'Notification',
            '48' => 'Ordenespoes',
            '49' => 'Ordenpoetrabajador',
            '50' => 'Paisempresa',
            '51' => 'Paises',
            '52' => 'Parametros',
            '53' => 'Poeestudio',
            '54' => 'Poes',
            '55' => 'Preguntas',
            '56' => 'Presentaciones',
            '57' => 'Programaempresa',
            '58' => 'ProgramaSalud',
            '59' => 'Programasaludempresa',
            '60' => 'ProgramaTrabajador',
            '61' => 'PuestoEpp',
            '62' => 'PuestoEstudio',
            '63' => 'Puestoparametro',
            '64' => 'PuestoRiesgo',
            '65' => 'Puestostrabajo',
            '66' => 'Puestotrabajador',
            '67' => 'Requerimientoempresa',
            '68' => 'Riesgos',
            '69' => 'Roles',
            '70' => 'Rolpermiso',
            '71' => 'Secciones',
            '72' => 'Servicios',
            '73' => 'SolicitudesDelete',
            '74' => 'TipoCuestionario',
            '75' => 'TipoServicios',
            '76' => 'Trabajadorepp',
            '77' => 'Trabajadores',
            '78' => 'Trabajadorestudio',
            '79' => 'Trabajadormaquina',
            '80' => 'Trabajadorparametro',
            '81' => 'Trashhistory',
            '82' => 'Turnopersonal',
            '83' => 'Turnos',
            '84' => 'Ubicaciones',
            '84' => 'Unidades',
            '85' => 'Usuarios',
            '86' => 'Vacantes',
            '87' => 'Vacunacion',
            '88' => 'Vacantetrabajador',
            '89' => 'Vacunacion',
            '90' => 'Viasadministracion',
            '91' => 'Vigencias'
          
        ];

        $modelos2 = [
            'AlergiaTrabajador' => 'AlergiaTrabajador',
            'Almacen' => 'Almacen',
            'Areas' => 'Areas',
            'Areascuestionario' => 'Areascuestionario',
            'Bitacora' => 'Bitacora',
            'Cargasmasivas' => 'Cargasmasivas',
            'Configconsentimientos' => 'Configconsentimientos',
            'Configuracion' => 'Configuracion',
            'Consentimientos' => 'Consentimientos',
            'Consultas' => 'Consultas',
            'Consultorios' => 'Consultorios',
            'ContactForm' => 'ContactForm',
            'Cuestionario' => 'Cuestionario',
            'DetalleCuestionario' => 'DetalleCuestionario',
            'Detallehc' => 'Detallehc',
            'Detallemovimiento' => 'Detallemovimiento',
            'Detalleordenpoe' => 'Detalleordenpoe',
            'Diagnosticoscie' => 'Diagnosticoscie',
            'Documentacion' => 'Documentacion',
            'DocumentoTrabajador' => 'DocumentoTrabajador',
            'Empresamails' => 'Empresamails',
            'Empresas' => 'Empresas',
            'Epps' => 'Epps',
            'Estudios' => 'Estudios',
            'ExtraccionBd' => 'ExtraccionBd',
            'Firmaempresa' => 'Firmaempresa',
            'Firmas' => 'Firmas',
            'Hccohc' => 'Hccohc',
            'Hccohcestudio' => 'Hccohcestudio',
            'Historialdocumentos' => 'Historialdocumentos',
            'Historicoes' => 'Historicoes',
            'Insumos' => 'Insumos',
            'Insumostockmin' => 'Insumostockmin',
            'Lineas' => 'Lineas',
            'LoginForm' => 'LoginForm',
            'Lotes' => 'Lotes',
            'Mantenimientoactividad' => 'Mantenimientoactividad',
            'Mantenimientocomponentes' => 'Mantenimientocomponentes',
            'Mantenimientos' => 'Mantenimientos',
            'Maquinaria' => 'Maquinaria',
            'Maquinariesgo' => 'Maquinariesgo',
            'Medidas' => 'Medidas',
            'Movimientos' => 'Movimientos',
            'NivelOrganizacional1' => 'NivelOrganizacional1',
            'NivelOrganizacional2' => 'NivelOrganizacional2',
            'NivelOrganizacional3' => 'NivelOrganizacional3',
            'NivelOrganizacional4' => 'NivelOrganizacional4',
            'Notification' => 'Notification',
            'Ordenespoes' => 'Ordenespoes',
            'Ordenpoetrabajador' => 'Ordenpoetrabajador',
            'Paisempresa' => 'Paisempresa',
            'Paises' => 'Paises',
            'Parametros' => 'Parametros',
            'Poeestudio' => 'Poeestudio',
            'Poes' => 'Poes',
            'Preguntas' => 'Preguntas',
            'Presentaciones' => 'Presentaciones',
            'Programaempresa' => 'Programaempresa',
            'ProgramaSalud' => 'ProgramaSalud',
            'Programasaludempresa' => 'Programasaludempresa',
            'ProgramaTrabajador' => 'ProgramaTrabajador',
            'PuestoEpp' => 'PuestoEpp',
            'PuestoEstudio' => 'PuestoEstudio',
            'Puestoparametro' => 'Puestoparametro',
            'PuestoRiesgo' => 'PuestoRiesgo',
            'Puestostrabajo' => 'Puestostrabajo',
            'Puestotrabajador' => 'Puestotrabajador',
            'Requerimientoempresa' => 'Requerimientoempresa',
            'Riesgos' => 'Riesgos',
            'Roles' => 'Roles',
            'Rolpermiso' => 'Rolpermiso',
            'Secciones' => 'Secciones',
            'Servicios' => 'Servicios',
            'SolicitudesDelete' => 'SolicitudesDelete',
            'TipoCuestionario' => 'TipoCuestionario',
            'TipoServicios' => 'TipoServicios',
            'Trabajadorepp' => 'Trabajadorepp',
            'Trabajadores' => 'Trabajadores',
            'Trabajadorestudio' => 'Trabajadorestudio',
            'Trabajadormaquina' => 'Trabajadormaquina',
            'Trabajadorparametro' => 'Trabajadorparametro',
            'Trashhistory' => 'Trashhistory',
            'Turnopersonal' => 'Turnopersonal',
            'Turnos' => 'Turnos',
            'Ubicaciones' => 'Ubicaciones',
            'Unidades' => 'Unidades',
            'Usuarios' => 'Usuarios',
            'Vacantes' => 'Vacantes',
            'Vacunacion' => 'Vacunacion',
            'Vacantetrabajador' => 'Vacantetrabajador',
            'Vacunacion' => 'Vacunacion',
            'Viasadministracion' => 'Viasadministracion',
            'Vigencias' => 'Vigencias'
          
        ];

        date_default_timezone_set('America/Costa_Rica');
        $trash = new Trashhistory();
        $trash->model = $tipo_modelo;
        $trash->id_model = $model->id;
        $trash->id_empresa = $model->id_empresa;
        $trash->deleted_date = date('Y-m-d H:i:s');
        $trash->deleted_user = Yii::$app->user->identity->id;
        $trash->save();

        if($model->trabajador){
            $trash->contenido = $model->trabajador->nombre.' '.$model->trabajador->apellidos;
            $trash->save();
        }
        //dd($trash);

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
}