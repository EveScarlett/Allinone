<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\EmpresasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Ubicaciones;
use app\models\Areas;
use app\models\Consultorios;
use app\models\Turnos;
use app\models\Programaempresa;
use app\models\ProgramaSalud;
use app\models\Configuracion;
use app\models\Empresamails;
use yii\helpers\ArrayHelper;

use app\models\Paises;
use app\models\Paisempresa;
use app\models\Lineas;
use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Consultas;
use app\models\Poes;

use app\models\Puestostrabajo;

use app\models\Kpis;
use app\models\Trabajadores;
use app\models\PuestoRiesgo;

use app\models\ProgramaTrabajador;
use app\models\Usuarios;

use Yii;
require_once __DIR__ . '/../web/phpqrcode/qrlib.php';
/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class EmpresasController extends Controller
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
     * Lists all Empresas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmpresasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Empresas model.
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
     * Creates a new Empresas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Empresas();
        $model->scenario = 'create';
        $model->cantidad_niveles = 1;
        $model->nivel1 = 1;
        $model->label_nivel1 = 'PAISES';
        $model->status = 1;
        $model->mostrar_nivel_pdf = 1;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                //dd($model);
                $archivo = UploadedFile::getInstance($model,'file_logo');
                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id.'/';
                $directorios = ['0'=>$dir0,'1'=>$dir1];
                $this->actionCarpetas( $directorios);
                
                if($archivo){
                    $nombre_archivo = 'logo_'.$model->comercial.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                    $model->logo = $nombre_archivo; 
                    $model->save();
                } 

                $model->create_date = date('Y-m-d');
                $model->save(); 

                

                /* if(isset($model->aux_ubicaciones) && $model->aux_ubicaciones != '' && $model->aux_ubicaciones != null){
                    foreach($model->aux_ubicaciones as $key=>$ubicacion){
                        if($ubicacion['ubicacion'] != null && $ubicacion['ubicacion'] != '' && $ubicacion['ubicacion'] != ' '){
                            $nuevo = new Ubicaciones();  
                            $nuevo->id_empresa = $model->id;
                            $nuevo->ubicacion = $ubicacion['ubicacion'];
                            $nuevo->status = 1;
                            $nuevo->save();
                        }
                    }
                } */

                /* if(isset($model->aux_areas) && $model->aux_areas != '' && $model->aux_areas != null){
                    foreach($model->aux_areas as $key=>$area){
                        if($area['area'] != null && $area['area'] != '' && $area['area'] != ' '){
                            $nuevo = new Areas();  
                            $nuevo->id_empresa = $model->id;
                            $nuevo->area = $area['area'];
                            $nuevo->status = 1;
                            $nuevo->save();
                        }
                    }
                } */

                /* if(isset($model->aux_consultorios) && $model->aux_consultorios != '' && $model->aux_consultorios != null){
                    foreach($model->aux_consultorios as $key=>$consultorio){
                        if($consultorio['consultorio'] != null && $consultorio['consultorio'] != '' && $consultorio['consultorio'] != ' '){
                            $nuevo = new Consultorios();  
                            $nuevo->id_empresa = $model->id;
                            $nuevo->consultorio = $consultorio['consultorio'];
                            $nuevo->status = 1;
                            $nuevo->save();
                        }
                    }
                } */

                /* if(isset($model->aux_turnos) && $model->aux_turnos != '' && $model->aux_consultorios != null){
                    foreach($model->aux_turnos as $key=>$turno){
                        if($turno['turno'] != null && $turno['turno'] != '' && $turno['turno'] != ' '){
                            $nuevo = new Turnos();  
                            $nuevo->id_empresa = $model->id;
                            $nuevo->turno = $turno['turno'];
                            $nuevo->orden = $turno['orden'];
                            $nuevo->status = 1;
                            $nuevo->save();
                        }
                    }
                } */

                /* if(isset($model->aux_programas) && $model->aux_programas != '' && $model->aux_programas != null){
                    foreach($model->aux_programas as $key=>$programa){
                        if($programa['programa'] != null && $programa['programa'] != '' && $programa['programa'] != ' '){

                            $nuevo = new Programaempresa(); 
                            
                            if($programa['programa'] == 0){
                                if($programa['programa'] != "" && $programa['otroprograma'] != null){
                                    $nprograma = new ProgramaSalud();
                                    $nprograma->nombre = $programa['otroprograma'];
                                    $nprograma->status = 1;
                                    $nprograma->save();
                                }
                            }else{
                                $nprograma = ProgramaSalud::find()->where(['id'=>$programa['programa']])->one();
                            }

                            if($nprograma){
                                $nuevo->id_empresa = $model->id;
                                $nuevo->id_programa = $nprograma->id;
                                $nuevo->status = 1;
                                $nuevo->save();
                            }
                        }
                    }
                } */

                if(!$model->configuracion){
                    $config = new Configuracion();
                    $config->id_empresa = $model->id;
                    $config->cantidad_trabajadores = 1000;
                    $config->cantidad_usuarios = 5;
                    $config->cantidad_administradores = 1;
                    $config->save();
                }

                $this->saveMultiple($model);

                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Empresas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->cantidad_niveles == null || $model->cantidad_niveles == '' || $model->cantidad_niveles == ' '){
            $model->cantidad_niveles = 1;
            $model->nivel1 = 1;
            $model->label_nivel1 = 'PAISES';
            $model->save();
        }

        foreach($model->mails as $key => $detalle){
            $model->aux_mails[$key]['mail'] = $detalle->mail;
            $model->aux_mails[$key]['tipo_mail'] = $detalle->tipo_mail;
            $model->aux_mails[$key]['id'] = $detalle->id;
        }
        //dd($model->configuracion);

        
        foreach($model->niveles1 as $key=>$data){
            $model->aux_nivel1[$key]['id_pais'] = $data->id_pais;
            $model->aux_nivel1[$key]['id'] = $data->id;
        }



        foreach($model->paises as $key=>$pais){
            $model->aux_paises[$key]['id_pais'] = $pais->id_pais;
            $model->aux_paises[$key]['id'] = $pais->id;
        }

        foreach($model->lineas as $key=>$linea){
            $model->aux_lineas[$key]['linea'] = $linea->linea;
            $model->aux_lineas[$key]['id'] = $linea->id;
        }

        foreach($model->ubicaciones as $key=>$ubicacion){
            $model->aux_ubicaciones[$key]['ubicacion'] = $ubicacion->ubicacion;
            $model->aux_ubicaciones[$key]['id'] = $ubicacion->id;
        }
        foreach($model->areas as $key=>$area){
            $model->aux_areas[$key]['area'] = $area->area;
            $model->aux_areas[$key]['id'] = $area->id;
        }
        foreach($model->consultorios as $key=>$consultorio){
            $model->aux_consultorios[$key]['consultorio'] = $consultorio->consultorio;
            $model->aux_consultorios[$key]['id'] = $consultorio->id;
        }
        foreach($model->turnos as $key=>$turno){
            $model->aux_turnos[$key]['turno'] = $turno->turno;
            $model->aux_turnos[$key]['orden'] = $turno->orden;
            $model->aux_turnos[$key]['id'] = $turno->id;
        }
        foreach($model->programaempresa as $key=>$programa){
            $model->aux_programas[$key]['programa'] = $programa->id_programa;
            $model->aux_programas[$key]['id'] = $programa->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {

            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 0){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){

                } else {
                    $model->status = $status_anterior;
                }
            }

            if($model->cantidad_niveles < 2){
                $model->nivel2 = null;
                $model->label_nivel2 = null;
            } else if($model->cantidad_niveles < 3){
                $model->nivel3 = null;
                $model->label_nivel3 = null;
            } else if($model->cantidad_niveles < 4){
                $model->nivel4 = null;
                $model->label_nivel4 = null;
            }

            $archivo = UploadedFile::getInstance($model,'file_logo');
            $dir0 = __DIR__ . '/../web/resources/Empresas/';
            $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id.'/';
            $directorios = ['0'=>$dir0,'1'=>$dir1];
            $this->actionCarpetas( $directorios);
            
            if($archivo){
                $nombre_archivo = 'logo_'.$model->comercial.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                $archivo->saveAs($directorios[1].'/'. $nombre_archivo);
                $model->logo = $nombre_archivo; 
                $model->save();
            } 

            $id_ubicaciones = [];
            $id_areas = [];
            $id_consultorios = [];
            $id_turnos = [];
            $id_programaempresa = [];

            /* if(isset($model->aux_ubicaciones) && $model->aux_ubicaciones != '' && $model->aux_ubicaciones != null){
                foreach($model->aux_ubicaciones as $key=>$ubicacion){
                    if($ubicacion['ubicacion'] != null && $ubicacion['ubicacion'] != '' && $ubicacion['ubicacion'] != ' '){
                        if($ubicacion['id'] == ""){
                            $nuevo = new Ubicaciones();  
                        } else {
                            $nuevo =  Ubicaciones::find()->where(['id'=>$ubicacion['id']])->one(); 
                        }  
                        $nuevo->id_empresa = $model->id;
                        $nuevo->ubicacion = $ubicacion['ubicacion'];
                        $nuevo->status = 1;
                        $nuevo->save();

                        if($nuevo){
                            array_push($id_ubicaciones, $nuevo->id);
                        }
                    }
                }
            } */

            /* if(isset($model->aux_areas) && $model->aux_areas != '' && $model->aux_areas != null){
                foreach($model->aux_areas as $key=>$area){
                    if($area['area'] != null && $area['area'] != '' && $area['area'] != ' '){
                        if($area['id'] == ""){
                            $nuevo = new Areas();  
                        } else {
                            $nuevo =  Areas::find()->where(['id'=>$area['id']])->one(); 
                        }  
                        $nuevo->id_empresa = $model->id;
                        $nuevo->area = $area['area'];
                        $nuevo->status = 1;
                        $nuevo->save();

                        if($nuevo){
                            array_push($id_areas, $nuevo->id);
                        }
                    }
                }
            } */

            /* if(isset($model->aux_consultorios) && $model->aux_consultorios != '' && $model->aux_consultorios != null){
                foreach($model->aux_consultorios as $key=>$consultorio){
                    if($consultorio['consultorio'] != null && $consultorio['consultorio'] != '' && $consultorio['consultorio'] != ' '){
                        if($consultorio['id'] == ""){
                            $nuevo = new Consultorios();
                        } else {
                            $nuevo =  Consultorios::find()->where(['id'=>$consultorio['id']])->one(); 
                        }  
                        $nuevo->id_empresa = $model->id;
                        $nuevo->consultorio = $consultorio['consultorio'];
                        $nuevo->status = 1;
                        $nuevo->save();

                        if($nuevo){
                            array_push($id_consultorios, $nuevo->id);
                        }
                    }
                }
            } */

            /* if(isset($model->aux_turnos) && $model->aux_turnos != '' && $model->aux_turnos != null){
                foreach($model->aux_turnos as $key=>$turno){
                    if($turno['turno'] != null && $turno['turno'] != '' && $turno['turno'] != ' '){
                        if($turno['id'] == ""){
                            $nuevo = new Turnos();
                        } else {
                            $nuevo =  Turnos::find()->where(['id'=>$turno['id']])->one(); 
                        }  
                        $nuevo->id_empresa = $model->id;
                        $nuevo->turno = $turno['turno'];
                        $nuevo->orden = $turno['orden'];
                        $nuevo->status = 1;
                        $nuevo->save();

                        if($nuevo){
                            array_push($id_turnos, $nuevo->id);
                        }
                    }
                }
            } */

            /* if(isset($model->aux_programas) && $model->aux_programas != '' && $model->aux_programas != null){
                foreach($model->aux_programas as $key=>$programa){
                    if($programa['programa'] != null && $programa['programa'] != '' && $programa['programa'] != ' '){
                        if($programa['id'] == ""){
                            $nuevo = new Programaempresa();
                        } else {
                            $nuevo =  Programaempresa::find()->where(['id'=>$programa['id']])->one(); 
                        }  

                        if($programa['programa'] == 0){
                            if($programa['programa'] != "" && $programa['otroprograma'] != null){
                                $nprograma = new ProgramaSalud();
                                $nprograma->nombre = $programa['otroprograma'];
                                $nprograma->status = 1;
                                $nprograma->save();
                            }
                        }else{
                            $nprograma = ProgramaSalud::find()->where(['id'=>$programa['programa']])->one();
                        }

                        if($nprograma){
                            $nuevo->id_empresa = $model->id;
                            $nuevo->id_programa = $nprograma->id;
                            $nuevo->status = 1;
                            $nuevo->save();
    
                            if($nuevo){
                                array_push($id_programaempresa, $nuevo->id);
                            }
                        }
                    }
                }
            } */

            /* $deletes = Ubicaciones::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_ubicaciones])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

            $deletes = Areas::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_areas])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

            $deletes = Consultorios::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_consultorios])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

            $deletes = Turnos::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_turnos])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

            $deletes = Programaempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_programaempresa])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            } */

            $model->save();

            if(!$model->configuracion){
                $config = new Configuracion();
                $config->id_empresa = $model->id;
                $config->cantidad_trabajadores = 1000;
                $config->cantidad_usuarios = 5;
                $config->cantidad_administradores = 1;
                $config->save();
                //dd($config);
            }

            $this->saveMultiple($model); 
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Empresas model.
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
     * Finds the Empresas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Empresas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empresas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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


    public function actionRegistrarqrs($id)
    {
        $empresa = $this->findModel($id);

        if($empresa){
            if($empresa->trabajadoresactivos){
                foreach($empresa->trabajadoresactivos as $key=>$model){
                    if($model->id_link == null || $model->id_link == '' || $model->id_link == ' '){
                        $this->actionCrearqr($model);
                    }
                }
            }
        }
        
        return $this->redirect(['index']);
    }


    public function actionCrearqr($model){
        date_default_timezone_set('America/Costa_Rica');
        $id_unique = ''.$model->id.$model->id_empresa.''.uniqid();
        $model->id_link = $id_unique;
        $model->save();

        //dd($id_unique);

        $dir_qr = __DIR__ . '/../web/qrs';
        $dir_qr2 = __DIR__ . '/../web/qrs/'.$model->id;
        $directorios = ['0'=>$dir_qr,'1'=>$dir_qr2];
        $this->actionCarpetas( $directorios);
        
        $dominio = 'https://www.dmm-smo.com';
        $dirqr = __DIR__ . '/../web/qrs/' . $model->id;

        if (!(file_exists( $dirqr . '/' . $model->id . '.png'))) {
            //dd('entra');
            $contenido = $dominio.'/web/index.php?r=trabajadores%2Fqr&id=' . $model->id_link;
            $ECClevel = 'M';
            $pixelSize = 10;
            $frameSize = 4;
            \QRcode::png($contenido, $dirqr . '/' . $model->id . 'qr.png', $ECClevel, $pixelSize, $frameSize);
        }

        return null;
                
    }


    private function saveMultiple($model){

        if (true){

            $array = $model->aux_mails;

            $id_mails = [];
            if(isset($array) &&  $array != null &&  $array != ''){
                foreach($array as $key => $mail){
    
                    if(isset($mail['id']) && $mail['id'] != null && $mail['id'] != ''){
                        $dm = Empresamails::findOne($mail['id']);
                    } else {
                        $dm = new Empresamails();
                        $dm->id_empresa = $model->id;
                    }
                    $dm->status = 1;
    
                    if(isset($mail['mail'])){
    
                        if(isset($mail['mail']) && $mail['mail'] != null && $mail['mail'] != '' && $mail['mail'] != ' ' && isset($mail['tipo_mail']) && $mail['tipo_mail'] != null && $mail['tipo_mail'] != '' && $mail['tipo_mail'] != ' '){
                            $dm->mail = $mail['mail'];
                            $dm->tipo_mail = $mail['tipo_mail'];
                           
                            $dm->create_date = date('Y-m-d H:i:s');
                            $dm->create_user = Yii::$app->user->identity->id;
                            $dm->save();
    
                            if($dm){
                                array_push($id_mails, $dm->id);
                            }
                        }
                        
                    }
                }
            }
    
            $deletes = Empresamails::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_mails])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }



            $id_paises = [];
            if(isset($model->aux_paises) && $model->aux_paises != '' && $model->aux_paises != null){
                    foreach($model->aux_paises as $key=>$pais){
                        if($pais['id_pais'] != null && $pais['id_pais'] != '' && $pais['id_pais'] != ' '){
                            $nuevo = Paisempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais['id_pais']])->one();
                            if(!$nuevo){
                                $nuevo = new Paisempresa();  
                                $nuevo->id_empresa = $model->id;
                                $nuevo->id_pais = $pais['id_pais'];
                            }
                            
                            $nuevo->status = 1;
                            $nuevo->save();
                            
                            if($nuevo){
                                array_push($id_paises, $nuevo->id);
                            }
                        }
                    }
            }
            $deletes = Paisempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_paises])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->status = 2;
                $delete->save();
            }



            $id_nivel1 = [];
            if(isset($model->aux_nivel1) && $model->aux_nivel1 != '' && $model->aux_nivel1 != null){
                    foreach($model->aux_nivel1 as $key=>$data){
                        if($data['id_pais'] != null && $data['id_pais'] != '' && $data['id_pais'] != ' '){
                            $nuevo = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$data['id_pais']])->one();
                            if(!$nuevo){
                                $nuevo = new NivelOrganizacional1();  
                                $nuevo->id_empresa = $model->id;
                                $nuevo->id_pais = $data['id_pais'];
                            }
                            
                            $nuevo->status = 1;
                            $nuevo->save();
                            
                            if($nuevo){
                                array_push($id_nivel1, $nuevo->id);
                            }
                        }
                    }
            }
            $deletes = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_nivel1])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->status = 2;
                $delete->save();
            }
                

        }  

        return null;
    }


    public function actionGetpaises(){
        $id = Yii::$app->request->post('id');
        $empresa = Empresas::find()->where(['id'=>$id])->one();

        $paises = [];

        if($empresa){

            $paisempresa = Paisempresa::find()->where(['id_empresa'=>$empresa->id])->all();
            $id_paises = [];
            foreach($paisempresa as $key=>$pais){
                array_push($id_paises, $pais->id_pais);
            }
   
            $paises = Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all();

        }
    
        return \yii\helpers\Json::encode(['empresa' => $empresa,'paises'=>$paises]);
    }


    public function actionGetlineas(){
        $id_pais = Yii::$app->request->post('id_pais');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $lineas = Lineas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$id_pais])->andWhere(['status'=>1])->all();
    
        return \yii\helpers\Json::encode(['lineas' => $lineas,'id_pais'=>$id_pais,'id_empresa'=>$id_empresa]);
    }



    public function actionListpaises(){
        $id_paises = [];
        $id_lineas = [];
        $id_ubicaciones = [];
        
        $id_empresa = Yii::$app->request->post('id_empresa');
        
        if(1==2){
            $paisempresa = Paisempresa::find()->where(['id_empresa'=>$id_empresa])->select(['id_pais'])->distinct()->all(); 
        } else {
            $paisempresa = Paisempresa::find()->where(['id_empresa'=>$id_empresa])->andWhere(['in','id_pais',$id_paises])->select(['id_pais'])->distinct()->all(); 
        }

        $id_paises = [];
        foreach($paisempresa as $key=>$pais){
            array_push($id_paises, $pais->id_pais);
        }
   
        $paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');

    
        return \yii\helpers\Json::encode(['paises' => $paises]);
    }


    public function actionListlineas(){
        $id_paises = [];
        $id_lineas = [];
        $id_ubicaciones = [];
        
        $id_pais = Yii::$app->request->post('id_pais');
        $id_empresa = Yii::$app->request->post('id_empresa');
        
        if(1==2){
            $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$id_pais])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
            });
        } else {
            $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$id_pais])->andWhere(['in','id',$id_lineas])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
            });
        }
    
        return \yii\helpers\Json::encode(['lineas' => $lineas]);
    }

    public function actionListubicaciones(){
        $id_paises = [];
        $id_lineas = [];
        $id_ubicaciones = [];
        
        $id_linea = Yii::$app->request->post('id_linea');
        $id_empresa = Yii::$app->request->post('id_empresa');
        
        if(1==2){
            $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_linea'=>$id_linea])->orderBy('ubicacion')->all(), 'id', function($data){
                $rest = ' [';

                if($data->linea){
                    $rest .= $data->linea->linea.' - ';
                }

                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['ubicacion'].$rest;
            });
        } else {
            $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_linea'=>$id_linea])->andWhere(['in','id',$id_ubicaciones])->orderBy('ubicacion')->all(), 'id', function($data){
                $rest = ' [';

                if($data->linea){
                    $rest .= $data->linea->linea.' - ';
                }

                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['ubicacion'].$rest;
            });
        }
    
        return \yii\helpers\Json::encode(['ubicaciones' => $ubicaciones]);
    }


    public function actionAddnivel1($id_empresa)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = new NivelOrganizacional1();
        $model->scenario = 'create';
        $model->status = 1;

        if($empresa){
            $model->id_empresa = $empresa->id;
        }

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel1', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionAddnivel2($id_empresa,$id_nivel1)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $nivel1 = NivelOrganizacional1::findOne($id_nivel1);

        $model = new NivelOrganizacional2();
        $model->id_nivelorganizacional1 = $id_nivel1;
        $model->scenario = 'create';
        $model->status = 1;

        if($empresa){
            $model->id_empresa = $empresa->id;
        }

        if($nivel1){
            $model->id_nivel = $nivel1->id_pais;
        }

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel2', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionAddnivel3($id_empresa,$id_nivel1,$id_nivel2)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = new NivelOrganizacional3();
        $model->id_nivelorganizacional1 = $id_nivel1;
        $model->id_nivelorganizacional2 = $id_nivel2;
        $model->scenario = 'create';
        $model->status = 1;

        if($empresa){
            $model->id_empresa = $empresa->id;
        }

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel3', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionAddnivel4($id_empresa,$id_nivel1,$id_nivel2,$id_nivel3)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = new NivelOrganizacional4();
        $model->id_nivelorganizacional1 = $id_nivel1;
        $model->id_nivelorganizacional2 = $id_nivel2;
        $model->id_nivelorganizacional3 = $id_nivel3;
        $model->scenario = 'create';
        $model->status = 1;

        if($empresa){
            $model->id_empresa = $empresa->id;
        }

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel4', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }


    public function actionEditnivel1($id_empresa,$id_nivel1)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel1])->one();
        $model->scenario = 'update';

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_1 = $id_nivel1;

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);

                $empresa = Empresas::findOne($model->id_empresa);

                if($model->status != 1){
                    $model->status = 2;
                    $model->save();

                    //$niveles2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id])$niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$pais->id_pais])->andWhere(['id_nivelorganizacional2'=>$linea->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->all();
                    $niveles2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id])->all();
                    if($niveles2){
                        foreach ($niveles2 as $key_nivel2=>$nivel_inferior2){
                            $nivel_inferior2->status = 2;
                            $nivel_inferior2->save(false);

                            $niveles3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id])->andWhere(['id_nivelorganizacional2'=>$nivel_inferior2->id])->all();
                            if($niveles3){
                                foreach ($niveles3 as $key_nivel3=>$nivel_inferior3){
                                    $nivel_inferior3->status = 2;
                                    $nivel_inferior3->save(false);

                                    $niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id])->andWhere(['id_nivelorganizacional2'=>$nivel_inferior2->id])->andWhere(['id_nivelorganizacional3'=>$nivel_inferior3->id])->all();
                                    if($niveles4){
                                        foreach ($niveles4 as $key_nivel4=>$nivel_inferior4){
                                            $nivel_inferior4->status = 2;
                                            $nivel_inferior4->save(false);

                                            if($empresa){
                                                if($empresa->cantidad_niveles == 4){
                                                    $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                                    $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                                    $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                                    $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                                    if($retareas){
                                                        foreach($retareas as $key=>$data){
                                                            $data->status = 2;
                                                            $data->save(false);
                                                        }
                                                    }

                                                    if($retconsultorios){
                                                        foreach($retconsultorios as $key=>$data){
                                                            $data->status = 2;
                                                            $data->save(false);
                                                        }
                                                    }

                                                    if($retprogramas){
                                                        foreach($retprogramas as $key=>$data){
                                                            $data->status = 2;
                                                            $data->save(false);
                                                        }
                                                    }

                                                    if($retturnos){
                                                        foreach($retturnos as $key=>$data){
                                                            $data->status = 2;
                                                            $data->save(false);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if($empresa){
                                        if($empresa->cantidad_niveles == 3){
                                            $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                            if($retareas){
                                                foreach($retareas as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retconsultorios){
                                                foreach($retconsultorios as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retprogramas){
                                                foreach($retprogramas as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retturnos){
                                                foreach($retturnos as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if($empresa){
                                if($empresa->cantidad_niveles == 2){
                                    $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior2->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior2->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior2->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior2->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                    if($retareas){
                                        foreach($retareas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retconsultorios){
                                        foreach($retconsultorios as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retprogramas){
                                        foreach($retprogramas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retturnos){
                                        foreach($retturnos as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }
                                }
                            }
                        }
                    } 

                    if($empresa){
                        if($empresa->cantidad_niveles == 1){
                            $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>1])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>1])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>1])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retturnos = Turnos::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>1])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                            if($retareas){
                                foreach($retareas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retconsultorios){
                                foreach($retconsultorios as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retprogramas){
                                foreach($retprogramas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retturnos){
                                foreach($retturnos as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }
                        }
                    }
                }
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel1', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionEditnivel2($id_empresa,$id_nivel2)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional2::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel2])->one();
        $model->scenario = 'update';

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_2 = $id_nivel2;

        if($model){
            $id_nivel_1 = $model->id_nivelorganizacional1;
        }

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);

                $empresa = Empresas::findOne($model->id_empresa);

                if($model->status != 1){
                    $model->status = 2;
                    $model->save();

                    
                    $niveles3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id])->all();
                    if($niveles3){
                        foreach ($niveles3 as $key_nivel3=>$nivel_inferior3){
                            $nivel_inferior3->status = 2;
                            $nivel_inferior3->save(false);

                            $niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id])->andWhere(['id_nivelorganizacional3'=>$nivel_inferior3->id])->all();
                            if($niveles4){
                                foreach ($niveles4 as $key_nivel4=>$nivel_inferior4){
                                    $nivel_inferior4->status = 2;
                                    $nivel_inferior4->save(false);

                                    if($empresa){
                                        if($empresa->cantidad_niveles == 4){
                                            $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                            $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                            if($retareas){
                                                foreach($retareas as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retconsultorios){
                                                foreach($retconsultorios as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retprogramas){
                                                foreach($retprogramas as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }

                                            if($retturnos){
                                                foreach($retturnos as $key=>$data){
                                                    $data->status = 2;
                                                    $data->save(false);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if($empresa){
                                if($empresa->cantidad_niveles == 3){
                                    $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior3->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                    if($retareas){
                                        foreach($retareas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retconsultorios){
                                        foreach($retconsultorios as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retprogramas){
                                        foreach($retprogramas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retturnos){
                                        foreach($retturnos as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if($empresa){
                        if($empresa->cantidad_niveles == 2){
                            $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retturnos = Turnos::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>2])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                            if($retareas){
                                foreach($retareas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retconsultorios){
                                foreach($retconsultorios as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retprogramas){
                                foreach($retprogramas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retturnos){
                                foreach($retturnos as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }
                        }
                    }
                }
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel2', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionEditnivel3($id_empresa,$id_nivel3)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional3::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel3])->one();
        $model->scenario = 'update';
        
        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_3 = $id_nivel3;

        if($model){
            $id_nivel_2 = $model->id_nivelorganizacional2;

            $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
            if($get_nivel1){
                $id_nivel_1 = $get_nivel1->id;
            }
        }

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);

                $empresa = Empresas::findOne($model->id_empresa);

                if($model->status != 1){
                    $model->status = 2;
                    $model->save();

                    
                    $niveles4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id])->all();
                    if($niveles4){
                        foreach ($niveles4 as $key_nivel4=>$nivel_inferior4){
                            $nivel_inferior4->status = 2;
                            $nivel_inferior4->save(false);

                            if($empresa){
                                if($empresa->cantidad_niveles == 4){
                                    $retareas = Areas::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retconsultorios = Consultorios::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retprogramas = Programaempresa::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                                    $retturnos = Turnos::find()->where(['id_superior'=>$nivel_inferior4->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                                    if($retareas){
                                        foreach($retareas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retconsultorios){
                                        foreach($retconsultorios as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retprogramas){
                                        foreach($retprogramas as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }

                                    if($retturnos){
                                        foreach($retturnos as $key=>$data){
                                            $data->status = 2;
                                            $data->save(false);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if($empresa){
                        if($empresa->cantidad_niveles == 3){
                            $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retturnos = Turnos::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>3])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                            if($retareas){
                                foreach($retareas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retconsultorios){
                                foreach($retconsultorios as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retprogramas){
                                foreach($retprogramas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retturnos){
                                foreach($retturnos as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }
                        }
                    }
                }
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel3', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }

    public function actionEditnivel4($id_empresa,$id_nivel4)
    {
        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional4::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel4])->one();
        $model->scenario = 'update';

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_4 = $id_nivel4;

        if($model){
            $id_nivel_2 = $model->id_nivelorganizacional2;
            $id_nivel_3 = $model->id_nivelorganizacional3;

            $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
            if($get_nivel1){
                $id_nivel_1 = $get_nivel1->id;
            }
        }

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);

                $empresa = Empresas::findOne($model->id_empresa);

                if($model->status != 1){
                    $model->status = 2;
                    $model->save();

                    if($empresa){
                        if($empresa->cantidad_niveles == 4){
                            $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retconsultorios = Consultorios::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retprogramas = Programaempresa::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                            $retturnos = Turnos::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();     
                            
                            if($retareas){
                                foreach($retareas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retconsultorios){
                                foreach($retconsultorios as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retprogramas){
                                foreach($retprogramas as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }

                            if($retturnos){
                                foreach($retturnos as $key=>$data){
                                    $data->status = 2;
                                    $data->save(false);
                                }
                            }
                        }
                    }
                }
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('nivel4', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }



     public function actionEditkpinivel1($id_empresa,$id_nivel1)
    {
        $editar_nivel = false;
        $trabajadores = null;
        $trabajadores_activos =  null;

        $qty_kpis = 0;
        $id_inferiores =  null;

        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel1])->one();
        $model->scenario = 'update';

        $model->actualiza_fecha = date('Y-m-d');
        $model->actualiza_usuario = Yii::$app->user->identity->name;

        $model->nivel = 1;
        //dd($model);

        if($empresa){
            if($empresa->cantidad_niveles == 1){
                $editar_nivel = true;
            }
        }

        if($model->dias_sin_accidentes != null && $model->dias_sin_accidentes != '' && $model->dias_sin_accidentes != ' '){
            $model->aux_dias_sin_accidentes = $model->dias_sin_accidentes;
        }
        if($model->fecha_dias_sin_accidentes != null && $model->fecha_dias_sin_accidentes != '' && $model->fecha_dias_sin_accidentes != ' '){
            $model->aux_fecha_dias_sin_accidentes = date('Y-m-d', strtotime($model->fecha_dias_sin_accidentes));
        }
        if($model->actualiza_dias_sin_accidentes != null && $model->actualiza_dias_sin_accidentes != '' && $model->actualiza_dias_sin_accidentes != ' '){
            if($model->actualizadas){
                $model->aux_actualiza_dias_sin_accidentes = $model->actualizadas->name;
            }
        }

        $qty_kpis = 0;

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_1 = $model->id;


        if($model){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$model->id])->andWhere(['in','status',[1,3]])->all();
        }

        $id_trabajadores = [];
        if($trabajadores_activos){
            foreach($trabajadores_activos as $key=>$trab){
                array_push($id_trabajadores, $trab->id);
            }
        }


        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if($editar_nivel){
            $porcentaje_cumplimiento = 0;
            $sumatoria_cumplimiento = 0;
            $qty_cumplimiento = 0;

            $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();

            foreach($retkpis as $key=>$data){

                $qty_cumplimiento ++;
                if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                    $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                }


                $qty_trabajadores = 0;

                if($data->kpi == 'D'){
                    $model->aux_kpis[$key]['kpi'] = $data->id_programa;


                    $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                    if($trabajadores_kpi){
                        $qty_trabajadores = count($trabajadores_kpi);
                    }
                } else {
                    $model->aux_kpis[$key]['kpi'] = $data->kpi;


                    if($data->kpi == 'A'){//ACCIDENTES

                        $accidentes_kpi = [];
                        if($model->nivel == 1){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($accidentes_kpi);

                    } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                    } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                        $hoy = date('Y-m-d');
                        $incapacidades_kpi = [];
                        if($model->nivel == 1){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($incapacidades_kpi);

                    } else if($data->kpi == 'E'){//POES
                        $anio_before = date('Y-m-d', strtotime('-1 years'));
                        $poes_kpi = [];
                        if($model->nivel == 1){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 2){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 3){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 4){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        }
                    }
                }
                
                $model->aux_kpis[$key]['kpi_objetivo'] = $data->kpi_objetivo;
                $model->aux_kpis[$key]['kpi_real'] = $data->kpi_real;
                $model->aux_kpis[$key]['kpi_cumplimiento'] = $data->kpi_cumplimiento;
                $model->aux_kpis[$key]['kpi_responsable'] = $data->kpi_responsable;
                $model->aux_kpis[$key]['kpi_fecha'] = $data->kpi_fecha;
                $model->aux_kpis[$key]['kpi_actualiza'] = $data->kpi_actualiza;

                if($data->actualiza){
                    $model->aux_kpis[$key]['kpi_actualiza_aux'] = $data->actualiza->name;
                }

                $model->aux_kpis[$key]['qty_trabajadores'] = $qty_trabajadores;

                $model->aux_kpis[$key]['id'] = $data->id;
            }


            //Sumamos el cumplimiento de accidentes ---------------------------------
            $qty_cumplimiento ++;
            if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
            }
            //Sumamos el cumplimiento de accidentes ---------------------------------
        
            if($qty_cumplimiento > 0){
                $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
            }
            $model->kpi_cumplimiento = $porcentaje_cumplimiento;
            $model->save();
        } else {
            $id_inferiores = $this->obtenerInferiores($model,1,$empresa->cantidad_niveles,$nivel_1,$id_nivel_1,$nivel_2,$id_nivel_2,$nivel_3,$id_nivel_3,$nivel_4,$id_nivel_4);
        }   
        

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);

                $empresa = Empresas::findOne($model->id_empresa);

                $id_guardar = [];
                if(isset($model->aux_kpis) && $model->aux_kpis != '' && $model->aux_kpis != null){
                    foreach($model->aux_kpis as $key=>$kpi){
                        if($kpi['kpi'] != null && $kpi['kpi'] != '' && $kpi['kpi'] != ' '){
                            $nuevo = null;

                            if($kpi['id'] != null && $kpi['id'] != '' && $kpi['id'] != ' '){
                                $nuevo = Kpis::findOne($kpi['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Kpis();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $model->nivel;
                            
                            $nuevo->kpi_objetivo = floatval($kpi['kpi_objetivo']);
                            $nuevo->kpi_real = floatval($kpi['kpi_real']);
                            $nuevo->kpi_cumplimiento = floatval($kpi['kpi_cumplimiento']);
                            $nuevo->kpi_responsable = $kpi['kpi_responsable'];

                            if($kpi['kpi_fecha'] != null && $kpi['kpi_fecha'] != '' && $kpi['kpi_fecha'] != ' '){
                                $nuevo->kpi_fecha = $kpi['kpi_fecha'];
                            } else {
                                $nuevo->kpi_fecha = date('Y-m-d');
                            }
                            
                            if($kpi['kpi_actualiza'] != null && $kpi['kpi_actualiza'] != '' && $kpi['kpi_actualiza'] != ' '){
                                $nuevo->kpi_actualiza = $kpi['kpi_actualiza'];
                            } else {
                                $nuevo->kpi_actualiza = Yii::$app->user->identity->id;
                            }

                            if($kpi['kpi'] != 'A' && $kpi['kpi'] != 'B' && $kpi['kpi'] != 'C' && $kpi['kpi'] != 'E'){
                                $nuevo->kpi = 'D';
                                $nuevo->id_programa = $kpi['kpi'];
                            } else {
                                $nuevo->kpi = $kpi['kpi'];
                                $nuevo->id_programa =  null;
                            }
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Kpis::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }

    
                $porcentaje_cumplimiento = 0;
                $sumatoria_cumplimiento = 0;
                $qty_cumplimiento = 0;
                $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                foreach($retkpis as $key=>$data){
                    $qty_cumplimiento ++;
                    if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                        $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                    }
                }
                $qty_cumplimiento ++;
                if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                    $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
                }
                if($qty_cumplimiento > 0){
                    $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
                }
                $model->kpi_cumplimiento = $porcentaje_cumplimiento;
                $model->save();

                return $this->redirect(['diagramas/indexkpi','id'=>$empresa->id]);
            }
        }

        return $this->renderAjax('kpinivel1', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
            'qty_kpis' => $qty_kpis,
            'editar_nivel' => $editar_nivel,
            'id_inferiores'=>$id_inferiores
        ]);
    }

    public function actionEditkpinivel2($id_empresa,$id_nivel2)
    {
        $editar_nivel = false;
        $trabajadores = null;
        $trabajadores_activos =  null;

        $qty_kpis = 0;
        $id_inferiores =  null;

        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional2::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel2])->one();
        $model->scenario = 'update';

        $model->actualiza_fecha = date('Y-m-d');
        $model->actualiza_usuario = Yii::$app->user->identity->name;

        $model->nivel = 2;

        if($empresa){
            if($empresa->cantidad_niveles == 2){
                $editar_nivel = true;
            }
        }
        //dd($model);

        if($model->dias_sin_accidentes != null && $model->dias_sin_accidentes != '' && $model->dias_sin_accidentes != ' '){
            $model->aux_dias_sin_accidentes = $model->dias_sin_accidentes;
        }
        if($model->fecha_dias_sin_accidentes != null && $model->fecha_dias_sin_accidentes != '' && $model->fecha_dias_sin_accidentes != ' '){
            $model->aux_fecha_dias_sin_accidentes = date('Y-m-d', strtotime($model->fecha_dias_sin_accidentes));
        }
        if($model->actualiza_dias_sin_accidentes != null && $model->actualiza_dias_sin_accidentes != '' && $model->actualiza_dias_sin_accidentes != ' '){
            if($model->actualizadas){
                $model->aux_actualiza_dias_sin_accidentes = $model->actualizadas->name;
            }
        }

        $qty_kpis = 0;

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_2 = $id_nivel2;

        if($model){

            $id_nivel_1 = $model->id_nivelorganizacional1;
            
            $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
            if($get_nivel1){
                $id_nivel_1 = $get_nivel1->id;
            }
        }

        if($model){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$model->id])->andWhere(['in','status',[1,3]])->all();
        }

        $id_trabajadores = [];
        if($trabajadores_activos){
            foreach($trabajadores_activos as $key=>$trab){
                array_push($id_trabajadores, $trab->id);
            }
        }

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if($editar_nivel){
            $porcentaje_cumplimiento = 0;
            $sumatoria_cumplimiento = 0;
            $qty_cumplimiento = 0;

            $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();

            foreach($retkpis as $key=>$data){

                $qty_cumplimiento ++;
                if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                    $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                }    


                $qty_trabajadores = 0;

                if($data->kpi == 'D'){
                    $model->aux_kpis[$key]['kpi'] = $data->id_programa;


                    $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                    if($trabajadores_kpi){
                        $qty_trabajadores = count($trabajadores_kpi);
                    }
                } else {
                    $model->aux_kpis[$key]['kpi'] = $data->kpi;


                    if($data->kpi == 'A'){//ACCIDENTES

                        $accidentes_kpi = [];
                        if($model->nivel == 1){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($accidentes_kpi);

                    } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                    } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                        $hoy = date('Y-m-d');
                        $incapacidades_kpi = [];
                        if($model->nivel == 1){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($incapacidades_kpi);

                    } else if($data->kpi == 'E'){//POES
                        $anio_before = date('Y-m-d', strtotime('-1 years'));
                        $poes_kpi = [];
                        if($model->nivel == 1){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 2){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 3){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 4){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        }
                    }
                }
                
                $model->aux_kpis[$key]['kpi_objetivo'] = $data->kpi_objetivo;
                $model->aux_kpis[$key]['kpi_real'] = $data->kpi_real;
                $model->aux_kpis[$key]['kpi_cumplimiento'] = $data->kpi_cumplimiento;
                $model->aux_kpis[$key]['kpi_responsable'] = $data->kpi_responsable;
                $model->aux_kpis[$key]['kpi_fecha'] = $data->kpi_fecha;
                $model->aux_kpis[$key]['kpi_actualiza'] = $data->kpi_actualiza;

                if($data->actualiza){
                    $model->aux_kpis[$key]['kpi_actualiza_aux'] = $data->actualiza->name;
                }

                $model->aux_kpis[$key]['qty_trabajadores'] = $qty_trabajadores;

                $model->aux_kpis[$key]['id'] = $data->id;
            }

            //Sumamos el cumplimiento de accidentes ---------------------------------
            $qty_cumplimiento ++;
            if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
            }
            //Sumamos el cumplimiento de accidentes ---------------------------------

            if($qty_cumplimiento > 0){
                $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
            }
            $model->kpi_cumplimiento = $porcentaje_cumplimiento;
            $model->save();
        }  else {
            $id_inferiores = $this->obtenerInferiores($model,2,$empresa->cantidad_niveles,$nivel_1,$id_nivel_1,$nivel_2,$id_nivel_2,$nivel_3,$id_nivel_3,$nivel_4,$id_nivel_4);
        }


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);

                $empresa = Empresas::findOne($model->id_empresa);

                $id_guardar = [];
                if(isset($model->aux_kpis) && $model->aux_kpis != '' && $model->aux_kpis != null){
                    foreach($model->aux_kpis as $key=>$kpi){
                        if($kpi['kpi'] != null && $kpi['kpi'] != '' && $kpi['kpi'] != ' '){
                            $nuevo = null;

                            if($kpi['id'] != null && $kpi['id'] != '' && $kpi['id'] != ' '){
                                $nuevo = Kpis::findOne($kpi['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Kpis();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $model->nivel;
                            
                            $nuevo->kpi_objetivo = floatval($kpi['kpi_objetivo']);
                            $nuevo->kpi_real = floatval($kpi['kpi_real']);
                            $nuevo->kpi_cumplimiento = floatval($kpi['kpi_cumplimiento']);
                            $nuevo->kpi_responsable = $kpi['kpi_responsable'];

                            if($kpi['kpi_fecha'] != null && $kpi['kpi_fecha'] != '' && $kpi['kpi_fecha'] != ' '){
                                $nuevo->kpi_fecha = $kpi['kpi_fecha'];
                            } else {
                                $nuevo->kpi_fecha = date('Y-m-d');
                            }
                            
                            if($kpi['kpi_actualiza'] != null && $kpi['kpi_actualiza'] != '' && $kpi['kpi_actualiza'] != ' '){
                                $nuevo->kpi_actualiza = $kpi['kpi_actualiza'];
                            } else {
                                $nuevo->kpi_actualiza = Yii::$app->user->identity->id;
                            }

                            if($kpi['kpi'] != 'A' && $kpi['kpi'] != 'B' && $kpi['kpi'] != 'C' && $kpi['kpi'] != 'E'){
                                $nuevo->kpi = 'D';
                                $nuevo->id_programa = $kpi['kpi'];
                            } else {
                                $nuevo->kpi = $kpi['kpi'];
                                $nuevo->id_programa =  null;
                            }
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Kpis::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }

    
                $porcentaje_cumplimiento = 0;
                $sumatoria_cumplimiento = 0;
                $qty_cumplimiento = 0;
                $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                foreach($retkpis as $key=>$data){
                    $qty_cumplimiento ++;
                    if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                        $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                    }
                }
                $qty_cumplimiento ++;
                if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                    $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
                }
                if($qty_cumplimiento > 0){
                    $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
                }
                $model->kpi_cumplimiento = $porcentaje_cumplimiento;
                $model->save();

                return $this->redirect(['diagramas/indexkpi','id'=>$empresa->id]);
            }
        }

        return $this->renderAjax('kpinivel2', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
            'qty_kpis' => $qty_kpis,
            'editar_nivel' => $editar_nivel,
            'id_inferiores'=>$id_inferiores
        ]);
    }

    public function actionEditkpinivel3($id_empresa,$id_nivel3)
    {
        $editar_nivel = false;
        $trabajadores = null;
        $trabajadores_activos =  null;

        $qty_kpis = 0;
        $id_inferiores =  null;

        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional3::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel3])->one();
        $model->scenario = 'update';

        $model->actualiza_fecha = date('Y-m-d');
        $model->actualiza_usuario = Yii::$app->user->identity->name;

        $model->nivel = 3;

        if($empresa){
            if($empresa->cantidad_niveles == 3){
                $editar_nivel = true;
            }
        }
        //dd($model);

        if($model->dias_sin_accidentes != null && $model->dias_sin_accidentes != '' && $model->dias_sin_accidentes != ' '){
            $model->aux_dias_sin_accidentes = $model->dias_sin_accidentes;
        }
        if($model->fecha_dias_sin_accidentes != null && $model->fecha_dias_sin_accidentes != '' && $model->fecha_dias_sin_accidentes != ' '){
            $model->aux_fecha_dias_sin_accidentes = date('Y-m-d', strtotime($model->fecha_dias_sin_accidentes));
        }
        if($model->actualiza_dias_sin_accidentes != null && $model->actualiza_dias_sin_accidentes != '' && $model->actualiza_dias_sin_accidentes != ' '){
            if($model->actualizadas){
                $model->aux_actualiza_dias_sin_accidentes = $model->actualizadas->name;
            }
        }

        $qty_kpis = 0;

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_3 = $id_nivel3;

        if($model){
            $id_nivel_2 = $model->id_nivelorganizacional2;

            $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
            if($get_nivel1){
                $id_nivel_1 = $get_nivel1->id;
            }
        }

        if($model){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$model->id])->andWhere(['in','status',[1,3]])->all();
        }

        $id_trabajadores = [];
        if($trabajadores_activos){
            foreach($trabajadores_activos as $key=>$trab){
                array_push($id_trabajadores, $trab->id);
            }
        }


        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if($editar_nivel){
            $porcentaje_cumplimiento = 0;
            $sumatoria_cumplimiento = 0;
            $qty_cumplimiento = 0;

            $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();

            foreach($retkpis as $key=>$data){

                $qty_cumplimiento ++;
                if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                    $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                }


                $qty_trabajadores = 0;

                if($data->kpi == 'D'){
                    $model->aux_kpis[$key]['kpi'] = $data->id_programa;


                    $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                    if($trabajadores_kpi){
                        $qty_trabajadores = count($trabajadores_kpi);
                    }
                } else {
                    $model->aux_kpis[$key]['kpi'] = $data->kpi;


                    if($data->kpi == 'A'){//ACCIDENTES

                        $accidentes_kpi = [];
                        if($model->nivel == 1){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($accidentes_kpi);

                    } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                    } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                        $hoy = date('Y-m-d');
                        $incapacidades_kpi = [];
                        if($model->nivel == 1){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 2){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 3){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($model->nivel == 4){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($incapacidades_kpi);

                    } else if($data->kpi == 'E'){//POES
                        $anio_before = date('Y-m-d', strtotime('-1 years'));
                        $poes_kpi = [];
                        if($model->nivel == 1){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 2){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 3){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($model->nivel == 4){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        }
                    }
                }
                
                $model->aux_kpis[$key]['kpi_objetivo'] = $data->kpi_objetivo;
                $model->aux_kpis[$key]['kpi_real'] = $data->kpi_real;
                $model->aux_kpis[$key]['kpi_cumplimiento'] = $data->kpi_cumplimiento;
                $model->aux_kpis[$key]['kpi_responsable'] = $data->kpi_responsable;
                $model->aux_kpis[$key]['kpi_fecha'] = $data->kpi_fecha;
                $model->aux_kpis[$key]['kpi_actualiza'] = $data->kpi_actualiza;

                if($data->actualiza){
                    $model->aux_kpis[$key]['kpi_actualiza_aux'] = $data->actualiza->name;
                }

                $model->aux_kpis[$key]['qty_trabajadores'] = $qty_trabajadores;

                $model->aux_kpis[$key]['id'] = $data->id;
            }

            //Sumamos el cumplimiento de accidentes ---------------------------------
            $qty_cumplimiento ++;
            if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
            }
            //Sumamos el cumplimiento de accidentes ---------------------------------

            if($qty_cumplimiento > 0){
                $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
            }
            $model->kpi_cumplimiento = $porcentaje_cumplimiento;
            $model->save();
        } else {
            $id_inferiores = $this->obtenerInferiores($model,3,$empresa->cantidad_niveles,$nivel_1,$id_nivel_1,$nivel_2,$id_nivel_2,$nivel_3,$id_nivel_3,$nivel_4,$id_nivel_4);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);

                $empresa = Empresas::findOne($model->id_empresa);

                $id_guardar = [];
                if(isset($model->aux_kpis) && $model->aux_kpis != '' && $model->aux_kpis != null){
                    foreach($model->aux_kpis as $key=>$kpi){
                        if($kpi['kpi'] != null && $kpi['kpi'] != '' && $kpi['kpi'] != ' '){
                            $nuevo = null;

                            if($kpi['id'] != null && $kpi['id'] != '' && $kpi['id'] != ' '){
                                $nuevo = Kpis::findOne($kpi['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Kpis();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $model->nivel;
                            
                            $nuevo->kpi_objetivo = floatval($kpi['kpi_objetivo']);
                            $nuevo->kpi_real = floatval($kpi['kpi_real']);
                            $nuevo->kpi_cumplimiento = floatval($kpi['kpi_cumplimiento']);
                            $nuevo->kpi_responsable = $kpi['kpi_responsable'];

                            if($kpi['kpi_fecha'] != null && $kpi['kpi_fecha'] != '' && $kpi['kpi_fecha'] != ' '){
                                $nuevo->kpi_fecha = $kpi['kpi_fecha'];
                            } else {
                                $nuevo->kpi_fecha = date('Y-m-d');
                            }
                            
                            if($kpi['kpi_actualiza'] != null && $kpi['kpi_actualiza'] != '' && $kpi['kpi_actualiza'] != ' '){
                                $nuevo->kpi_actualiza = $kpi['kpi_actualiza'];
                            } else {
                                $nuevo->kpi_actualiza = Yii::$app->user->identity->id;
                            }

                            if($kpi['kpi'] != 'A' && $kpi['kpi'] != 'B' && $kpi['kpi'] != 'C' && $kpi['kpi'] != 'E'){
                                $nuevo->kpi = 'D';
                                $nuevo->id_programa = $kpi['kpi'];
                            } else {
                                $nuevo->kpi = $kpi['kpi'];
                                $nuevo->id_programa =  null;
                            }
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Kpis::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }

    
                $porcentaje_cumplimiento = 0;
                $sumatoria_cumplimiento = 0;
                $qty_cumplimiento = 0;
                $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                foreach($retkpis as $key=>$data){
                    $qty_cumplimiento ++;
                    if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                        $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                    }
                }
                $qty_cumplimiento ++;
                if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                    $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
                }
                if($qty_cumplimiento > 0){
                    $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
                }
                $model->kpi_cumplimiento = $porcentaje_cumplimiento;
                $model->save();

                return $this->redirect(['diagramas/indexkpi','id'=>$empresa->id]);
            }
        }

        return $this->renderAjax('kpinivel3', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
            'qty_kpis' => $qty_kpis,
            'editar_nivel' => $editar_nivel,
            'id_inferiores'=>$id_inferiores
        ]);
    }

    public function actionEditkpinivel4($id_empresa,$id_nivel4)
    {
        $editar_nivel = false;
        $trabajadores = null;
        $trabajadores_activos =  null;

        $qty_kpis = 0;

        date_default_timezone_set('America/Costa_Rica');
        $empresa = $this->findModel($id_empresa);
        $model = NivelOrganizacional4::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel4])->one();
        $model->scenario = 'update';

        $model->actualiza_fecha = date('Y-m-d');
        $model->actualiza_usuario = Yii::$app->user->identity->name;

        $model->nivel = 4;

        if($empresa){
            if($empresa->cantidad_niveles == 4){
                $editar_nivel = true;
            }
        }

        if($model->dias_sin_accidentes != null && $model->dias_sin_accidentes != '' && $model->dias_sin_accidentes != ' '){
            $model->aux_dias_sin_accidentes = $model->dias_sin_accidentes;
        }
        if($model->fecha_dias_sin_accidentes != null && $model->fecha_dias_sin_accidentes != '' && $model->fecha_dias_sin_accidentes != ' '){
            $model->aux_fecha_dias_sin_accidentes = date('Y-m-d', strtotime($model->fecha_dias_sin_accidentes));
        }
        if($model->actualiza_dias_sin_accidentes != null && $model->actualiza_dias_sin_accidentes != '' && $model->actualiza_dias_sin_accidentes != ' '){
            if($model->actualizadas){
                $model->aux_actualiza_dias_sin_accidentes = $model->actualizadas->name;
            }
        }

        $qty_kpis = 0;

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $id_nivel_4 = $id_nivel4;

        if($model){
            $id_nivel_2 = $model->id_nivelorganizacional2;
            $id_nivel_3 = $model->id_nivelorganizacional3;

            $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
            if($get_nivel1){
                $id_nivel_1 = $get_nivel1->id;
            }
        }

        if($model){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$model->id])->andWhere(['in','status',[1,3]])->all();
        }

        $id_trabajadores = [];
        if($trabajadores_activos){
            foreach($trabajadores_activos as $key=>$trab){
                array_push($id_trabajadores, $trab->id);
            }
        }


        $porcentaje_cumplimiento = 0;
        $sumatoria_cumplimiento = 0;
        $qty_cumplimiento = 0;

        $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();

        foreach($retkpis as $key=>$data){

            $qty_cumplimiento ++;
            if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                $sumatoria_cumplimiento += $data->kpi_cumplimiento;
            }


            $qty_trabajadores = 0;

            if($data->kpi == 'D'){
                $model->aux_kpis[$key]['kpi'] = $data->id_programa;


                $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                if($trabajadores_kpi){
                    $qty_trabajadores = count($trabajadores_kpi);
                }
            } else {
                $model->aux_kpis[$key]['kpi'] = $data->kpi;


                if($data->kpi == 'A'){//ACCIDENTES

                    $accidentes_kpi = [];
                    if($model->nivel == 1){
                        $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 2){
                        $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 3){
                        $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 4){
                        $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    }
                    $qty_trabajadores = count($accidentes_kpi);

                } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                    $hoy = date('Y-m-d');
                    $incapacidades_kpi = [];
                    if($model->nivel == 1){
                        $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 2){
                        $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 3){
                        $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    } else if($model->nivel == 4){
                        $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                    }
                    $qty_trabajadores = count($incapacidades_kpi);

                } else if($data->kpi == 'E'){//POES
                    $anio_before = date('Y-m-d', strtotime('-1 years'));
                    $poes_kpi = [];
                    if($model->nivel == 1){
                        $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                    } else if($model->nivel == 2){
                        $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                    } else if($model->nivel == 3){
                        $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                    } else if($model->nivel == 4){
                        $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                    }
                }
            }
                
            $model->aux_kpis[$key]['kpi_objetivo'] = $data->kpi_objetivo;
            $model->aux_kpis[$key]['kpi_real'] = $data->kpi_real;
            $model->aux_kpis[$key]['kpi_cumplimiento'] = $data->kpi_cumplimiento;
            $model->aux_kpis[$key]['kpi_responsable'] = $data->kpi_responsable;
            $model->aux_kpis[$key]['kpi_fecha'] = $data->kpi_fecha;
            $model->aux_kpis[$key]['kpi_actualiza'] = $data->kpi_actualiza;

            if($data->actualiza){
                $model->aux_kpis[$key]['kpi_actualiza_aux'] = $data->actualiza->name;
            }

            $model->aux_kpis[$key]['qty_trabajadores'] = $qty_trabajadores;

            $model->aux_kpis[$key]['id'] = $data->id;
        }


        //Sumamos el cumplimiento de accidentes ---------------------------------
        $qty_cumplimiento ++;
        if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
            $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
        }
        //Sumamos el cumplimiento de accidentes ---------------------------------

        if($qty_cumplimiento > 0){
            $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
        }
        $model->kpi_cumplimiento = $porcentaje_cumplimiento;
        $model->save();

            
        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);

                $empresa = Empresas::findOne($model->id_empresa);

                $id_guardar = [];
                if(isset($model->aux_kpis) && $model->aux_kpis != '' && $model->aux_kpis != null){
                    foreach($model->aux_kpis as $key=>$kpi){
                        if($kpi['kpi'] != null && $kpi['kpi'] != '' && $kpi['kpi'] != ' '){
                            $nuevo = null;

                            if($kpi['id'] != null && $kpi['id'] != '' && $kpi['id'] != ' '){
                                $nuevo = Kpis::findOne($kpi['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Kpis();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $model->nivel;
                            
                            $nuevo->kpi_objetivo = floatval($kpi['kpi_objetivo']);
                            $nuevo->kpi_real = floatval($kpi['kpi_real']);
                            $nuevo->kpi_cumplimiento = floatval($kpi['kpi_cumplimiento']);
                            $nuevo->kpi_responsable = $kpi['kpi_responsable'];

                            if($kpi['kpi_fecha'] != null && $kpi['kpi_fecha'] != '' && $kpi['kpi_fecha'] != ' '){
                                $nuevo->kpi_fecha = $kpi['kpi_fecha'];
                            } else {
                                $nuevo->kpi_fecha = date('Y-m-d');
                            }
                            
                            if($kpi['kpi_actualiza'] != null && $kpi['kpi_actualiza'] != '' && $kpi['kpi_actualiza'] != ' '){
                                $nuevo->kpi_actualiza = $kpi['kpi_actualiza'];
                            } else {
                                $nuevo->kpi_actualiza = Yii::$app->user->identity->id;
                            }

                            if($kpi['kpi'] != 'A' && $kpi['kpi'] != 'B' && $kpi['kpi'] != 'C' && $kpi['kpi'] != 'E'){
                                $nuevo->kpi = 'D';
                                $nuevo->id_programa = $kpi['kpi'];
                            } else {
                                $nuevo->kpi = $kpi['kpi'];
                                $nuevo->id_programa =  null;
                            }
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Kpis::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }

    
                $porcentaje_cumplimiento = 0;
                $sumatoria_cumplimiento = 0;
                $qty_cumplimiento = 0;
                $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$model->nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
                foreach($retkpis as $key=>$data){
                    $qty_cumplimiento ++;
                    if($data->kpi_cumplimiento != null && $data->kpi_cumplimiento != '' && $data->kpi_cumplimiento != ' '){
                        $sumatoria_cumplimiento += $data->kpi_cumplimiento;
                    }
                }
                $qty_cumplimiento ++;
                if($model->cumplimiento_dias_sin_accidentes != null && $model->cumplimiento_dias_sin_accidentes != '' && $model->cumplimiento_dias_sin_accidentes != ' '){
                    $sumatoria_cumplimiento += $model->cumplimiento_dias_sin_accidentes;
                }
                if($qty_cumplimiento > 0){
                    $porcentaje_cumplimiento = $sumatoria_cumplimiento / $qty_cumplimiento;
                }
                $model->kpi_cumplimiento = $porcentaje_cumplimiento;
                $model->save();
                
                return $this->redirect(['diagramas/indexkpi','id'=>$empresa->id]);
            }
        }

        return $this->renderAjax('kpinivel4', [
            'empresa' => $empresa,
            'model' => $model,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
            'qty_kpis' => $qty_kpis,
            'editar_nivel' => $editar_nivel
        ]);
    }


    public function obtenerInferiores($nivel,$numero_nivel,$cantidad_nivelesempresa,$nivel_1,$id_nivel_1,$nivel_2,$id_nivel_2,$nivel_3,$id_nivel_3,$nivel_4,$id_nivel_4){
        $inferiores = [];

        if($nivel){
            if($numero_nivel == 3){
                $cumplimiento_nivel = 0;
                $sumatoria_niveles = 0;
                $cantidad_niveles = 0;
                $menor_cantidad_accidentes = 1000000;
                
                $niveles_above = NivelOrganizacional4::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional3'=>$id_nivel_3])->andWhere(['status'=>1])->all();
                if($niveles_above){
                    $cantidad_niveles = count($niveles_above);
                    foreach($niveles_above as $key=>$inferior){
                        $sumatoria_niveles += $inferior->kpi_cumplimiento;

                        if($inferior->dias_sin_accidentes != null && $inferior->dias_sin_accidentes != '' && $inferior->dias_sin_accidentes != ' '){
                            if($inferior->dias_sin_accidentes < $menor_cantidad_accidentes){
                                $menor_cantidad_accidentes = $inferior->dias_sin_accidentes;
                            }
                        }

                        array_push($inferiores, $inferior->id);
                    }
                }

                if($cantidad_niveles > 0){
                    $cumplimiento_nivel = $sumatoria_niveles/$cantidad_niveles;
                }
                $nivel->kpi_cumplimiento = $cumplimiento_nivel;

                if($menor_cantidad_accidentes == 1000000){
                    $menor_cantidad_accidentes = 0;
                }
                
                $nivel->dias_sin_accidentes = $menor_cantidad_accidentes;

                $nivel->save(false);
                //dd($nivel);
            } else if($numero_nivel == 2){
                $cumplimiento_nivel = 0;
                $sumatoria_niveles = 0;
                $cantidad_niveles = 0;
                $menor_cantidad_accidentes = 1000000;

                
                $above_nivel3 = NivelOrganizacional3::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional2'=>$id_nivel_2])->andWhere(['status'=>1])->all();
                //dd($above_nivel3);
                if($above_nivel3){
                    $cantidad_niveles = count($above_nivel3);
                    foreach($above_nivel3 as $key=>$inferior_nivel3){
                        $sumatoria_niveles += $inferior_nivel3->kpi_cumplimiento;

                        if($inferior_nivel3->dias_sin_accidentes != null && $inferior_nivel3->dias_sin_accidentes != '' && $inferior_nivel3->dias_sin_accidentes != ' '){
                            if($inferior_nivel3->dias_sin_accidentes < $menor_cantidad_accidentes){
                                $menor_cantidad_accidentes = $inferior_nivel3->dias_sin_accidentes;
                            }
                        }

                        if($cantidad_nivelesempresa == 3){
                            array_push($inferiores, $inferior_nivel3->id);
                        } else {
                            $niveles_above = NivelOrganizacional4::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional3'=>$inferior_nivel3->id])->andWhere(['status'=>1])->all();
                            foreach($niveles_above as $key4=>$inferior){
                                array_push($inferiores, $inferior->id);
                            }
                        }
                    }
                }

                if($cantidad_niveles > 0){
                    $cumplimiento_nivel = $sumatoria_niveles/$cantidad_niveles;
                }
                $nivel->kpi_cumplimiento = $cumplimiento_nivel;

                if($menor_cantidad_accidentes == 1000000){
                    $menor_cantidad_accidentes = 0;
                }
                
                $nivel->dias_sin_accidentes = $menor_cantidad_accidentes;

                $nivel->save(false);
                //dd('$sumatoria_niveles',$sumatoria_niveles,'$cantidad_niveles',$cantidad_niveles);
            } else if($numero_nivel == 1){
                $cumplimiento_nivel = 0;
                $sumatoria_niveles = 0;
                $cantidad_niveles = 0;
                $menor_cantidad_accidentes = 1000000;

                
                $above_nivel2 = NivelOrganizacional2::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional1'=>$id_nivel_1])->andWhere(['status'=>1])->all();
                //dd($above_nivel2);
                if($above_nivel2){
                    $cantidad_niveles = count($above_nivel2);
                    foreach($above_nivel2 as $key=>$inferior_nivel2){
                        $sumatoria_niveles += $inferior_nivel2->kpi_cumplimiento;

                        if($inferior_nivel2->dias_sin_accidentes != null && $inferior_nivel2->dias_sin_accidentes != '' && $inferior_nivel2->dias_sin_accidentes != ' '){
                            if($inferior_nivel2->dias_sin_accidentes < $menor_cantidad_accidentes){
                                $menor_cantidad_accidentes = $inferior_nivel2->dias_sin_accidentes;
                            }
                        }

                        if($cantidad_nivelesempresa == 2){
                            array_push($inferiores, $inferior_nivel2->id);
                        } else {
                            $above_nivel3 = NivelOrganizacional3::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional2'=>$inferior_nivel2->id])->andWhere(['status'=>1])->all();
                
                            if($above_nivel3){
                                foreach($above_nivel3 as $key3=>$inferior_nivel3){
                                    if($cantidad_nivelesempresa == 3){
                                        array_push($inferiores, $inferior_nivel3->id);
                                    } else {
                                        $niveles_above = NivelOrganizacional4::find()->where(['id_empresa'=>$nivel->id_empresa])->andWhere(['id_nivelorganizacional3'=>$inferior_nivel3->id])->andWhere(['status'=>1])->all();
                                        foreach($niveles_above as $key4=>$inferior){
                                            array_push($inferiores, $inferior->id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                //dd($inferiores);

                if($cantidad_niveles > 0){
                    $cumplimiento_nivel = $sumatoria_niveles/$cantidad_niveles;
                }
                $nivel->kpi_cumplimiento = $cumplimiento_nivel;

                if($menor_cantidad_accidentes == 1000000){
                    $menor_cantidad_accidentes = 0;
                }
                
                $nivel->dias_sin_accidentes = $menor_cantidad_accidentes;

                $nivel->save(false);
                //dd($nivel);
            }
        }
        
        //dd('$nivel',$nivel,'$numero_nivel',$numero_nivel,'$cantidad_niveles',$cantidad_niveles,'$nivel_1',$nivel_1,'$id_nivel_1',$id_nivel_1,'$nivel_2',$nivel_2,'$id_nivel_2',$id_nivel_2,'$nivel_3',$nivel_3,'$id_nivel_3',$id_nivel_3,'$nivel_4',$nivel_4,'$id_nivel_4',$id_nivel_4);
        return $inferiores;
    }


    public function actionEditkpi($id_empresa)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = $this->findModel($id_empresa);
        

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->save(false);
    
                return $this->redirect(['diagramas/index']);
            }
        }

        return $this->renderAjax('editkpi', [
            'model' => $model,
        ]);
    }


    public function actionAddcontenido($id_empresa,$id_nivel,$nivel)
    {
        date_default_timezone_set('America/Costa_Rica');
        $anio_before = date('Y-m-d', strtotime('-1 years'));
        
        $empresa = $this->findModel($id_empresa);
        $model = null;
        $trabajadores = null;
        $trabajadores_activos =  null;

        $id_nivel_1 = null;
        $id_nivel_2 = null;
        $id_nivel_3 = null;
        $id_nivel_4 = null;

        $qty_areas = 0;
        $qty_puestos = 0;
        $qty_kpis = 0;
        

        if($nivel == 1){
            $model = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel])->one();
        
            if($model){
                $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$model->id])->andWhere(['in','status',[1,3]])->all();
                $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            }

            $id_nivel_1 = $id_nivel;

        }
        else if($nivel == 2){
            $model = NivelOrganizacional2::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel])->one();
        
            if($model){
            
                $id_nivel_1 = $model->id_nivelorganizacional1;

                $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$model->id])->andWhere(['in','status',[1,3]])->all();
                $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$model->id])->andWhere(['in','status',[1,3]])->all();

            }

            $id_nivel_2 = $id_nivel;

        }
        else if($nivel == 3){
            $model = NivelOrganizacional3::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel])->one();
        
            $get_nivel1 = null;

            if($model){
                
                $id_nivel_2 = $model->id_nivelorganizacional2;

                $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
                if($get_nivel1){
                    $id_nivel_1 = $get_nivel1->id;
                }

                $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$model->id])->andWhere(['in','status',[1,3]])->all();
                $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            }

            $id_nivel_3 = $id_nivel;

        }
        else if($nivel == 4){
            $model = NivelOrganizacional4::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id'=>$id_nivel])->one();
        
            $get_nivel1 = null;

            if($model){
            
                $id_nivel_2 = $model->id_nivelorganizacional2;
                $id_nivel_3 = $model->id_nivelorganizacional3;

                $get_nivel1 = NivelOrganizacional1::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_pais'=>$model->id_nivelorganizacional1])->andWhere(['status'=>1])->one();
                if($get_nivel1){
                    $id_nivel_1 = $get_nivel1->id;
                }

                $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$model->id])->andWhere(['in','status',[1,3]])->all();
                $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            }
            
            $id_nivel_4 = $id_nivel;

            //dd('$model',$model,'$id_nivel_1',$id_nivel_1,'$id_nivel_2',$id_nivel_2,'$id_nivel_3',$id_nivel_3);
        }


        if($id_nivel_1 != null && $id_nivel_1 != '' && $id_nivel_1 != ' '){
            $id_nivel_1 = intval($id_nivel_1);
        }
        if($id_nivel_2 != null && $id_nivel_2 != '' && $id_nivel_2 != ' '){
            $id_nivel_2 = intval($id_nivel_2);
        }
        if($id_nivel_3 != null && $id_nivel_3 != '' && $id_nivel_3 != ' '){
            $id_nivel_3 = intval($id_nivel_3);
        }
        if($id_nivel_4 != null && $id_nivel_4 != '' && $id_nivel_4 != ' '){
            $id_nivel_4 = intval($id_nivel_4);
        }

        if($model){

            $id_trabajadores = [];
            if($trabajadores_activos){
                foreach($trabajadores_activos as $key=>$trab){
                    array_push($id_trabajadores, $trab->id);
                }
            }
            //dd($id_trabajadores,$trabajadores_activos);


            $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
            $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
            $retpuestos = [];

            if($nivel == 1){
                $retpuestos = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['status'=>1])->all();
            } else if($nivel == 2){
                $retpuestos = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['status'=>1])->all();
            } else if($nivel == 3){
                $retpuestos = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['status'=>1])->all();
            } else if($nivel == 4){
                $retpuestos = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['status'=>1])->all();
            }

            $qty_areas = count($retareas);
            $qty_puestos = count($retpuestos);
            $qty_kpis = count($retkpis);

            $retconsultorios = Consultorios::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
            $retprogramas = Programaempresa::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
            $retturnos = Turnos::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->all();
            

            $id_areas = [];
            foreach($retareas as $key=>$data){
                $model->aux_areas[$key]['area'] = $data->area;
                $model->aux_areas[$key]['id'] = $data->id;

                if($nivel == 1){
                    $trabajadores_area = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_area'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 2){
                    $trabajadores_area = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_area'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 3){
                    $trabajadores_area = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_area'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 4){
                    $trabajadores_area = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['id_area'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                }
                
                $model->aux_areas[$key]['qty_trabajadores'] = count($trabajadores_area);
                $data->qty_trabajadores = count($trabajadores_area);
                $data->save(false);
                //array_push($id_areas, $data->id);
            }

            $id_puestos = [];
            foreach($retpuestos as $key=>$data){
                $ret_riesgos = [];

                foreach($data->riesgos as $key=>$riesgo){
                    array_push($ret_riesgos, $riesgo->id);
                }

                $model->aux_puestos[$key]['puesto'] = $data->nombre;
                $model->aux_puestos[$key]['riesgos'] = $ret_riesgos;
                $model->aux_puestos[$key]['id'] = $data->id;

                if($nivel == 1){
                    $trabajadores_puesto = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_puesto'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 2){
                    $trabajadores_puesto = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_puesto'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 3){
                    $trabajadores_puesto = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_puesto'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                } else if($nivel == 4){
                    $trabajadores_puesto = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['id_puesto'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                }
                
                $model->aux_puestos[$key]['qty_trabajadores'] = count($trabajadores_puesto);
                $data->qty_trabajadores = count($trabajadores_puesto);
                $data->save(false);

                //dd($data,$trabajadores_puesto);
                //array_push($id_puestos, $data->id);
            }
            //dd($model);
           
            $id_kpi = [];
            foreach($retkpis as $key=>$data){
                $qty_trabajadores = 0;

                if($data->kpi == 'D'){
                    $model->aux_kpis[$key]['kpi'] = $data->id_programa;


                    $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                    if($trabajadores_kpi){
                        $qty_trabajadores = count($trabajadores_kpi);
                    }
                } else {
                    $model->aux_kpis[$key]['kpi'] = $data->kpi;


                    if($data->kpi == 'A'){//ACCIDENTES

                        $accidentes_kpi = [];
                        if($nivel == 1){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 2){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 3){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 4){
                            $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($accidentes_kpi);

                    } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                    } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                        $hoy = date('Y-m-d');
                        $incapacidades_kpi = [];
                        if($nivel == 1){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 2){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 3){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        } else if($nivel == 4){
                            $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        }
                        $qty_trabajadores = count($incapacidades_kpi);

                    } else if($data->kpi == 'E'){//POES
                        $anio_before = date('Y-m-d', strtotime('-1 years'));
                        $poes_kpi = [];
                        if($nivel == 1){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($nivel == 2){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($nivel == 3){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        } else if($nivel == 4){
                            $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        }
                    }
                }
                
                $model->aux_kpis[$key]['qty_trabajadores'] = $qty_trabajadores;

                $model->aux_kpis[$key]['id'] = $data->id;
            }
             //dd($retkpis,$model);

            foreach($retconsultorios as $key=>$data){
                $model->aux_consultorios[$key]['consultorio'] = $data->consultorio;
                $model->aux_consultorios[$key]['id'] = $data->id;
            }

            foreach($retprogramas as $key=>$data){
                $model->aux_programas[$key]['programa'] = $data->id_programa;
                $model->aux_programas[$key]['id'] = $data->id;
            }

            foreach($retturnos as $key=>$data){
                $model->aux_turnos[$key]['turno'] = $data->turno;
                $model->aux_turnos[$key]['id'] = $data->id;
            }
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd('$model',$model,'$id_nivel',$id_nivel,'$nivel',$nivel);

                $id_guardar = [];
                if(isset($model->aux_areas) && $model->aux_areas != '' && $model->aux_areas != null){
                    foreach($model->aux_areas as $key=>$area){
                        if($area['area'] != null && $area['area'] != '' && $area['area'] != ' '){
                            $nuevo = null;

                            if($area['id'] != null && $area['id'] != '' && $area['id'] != ' '){
                                $nuevo = Areas::findOne($area['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Areas();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $nivel;
                            $nuevo->area = $area['area'];
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }



                $id_guardar = [];
                if(isset($model->aux_kpis) && $model->aux_kpis != '' && $model->aux_kpis != null){
                    foreach($model->aux_kpis as $key=>$kpi){
                        if($kpi['kpi'] != null && $kpi['kpi'] != '' && $kpi['kpi'] != ' '){
                            $nuevo = null;

                            if($kpi['id'] != null && $kpi['id'] != '' && $kpi['id'] != ' '){
                                $nuevo = Kpis::findOne($kpi['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Kpis();
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $nivel;
                            
                            $nuevo->kpi_cumplimiento =  0;

                            if($kpi['kpi'] != 'A' && $kpi['kpi'] != 'B' && $kpi['kpi'] != 'C' && $kpi['kpi'] != 'E'){
                                $nuevo->kpi = 'D';
                                $nuevo->id_programa = $kpi['kpi'];
                            } else {
                                $nuevo->kpi = $kpi['kpi'];
                                $nuevo->id_programa =  null;
                            }
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Kpis::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }



                $id_guardar = [];
                if(isset($model->aux_puestos) && $model->aux_puestos != '' && $model->aux_puestos != null){
                    foreach($model->aux_puestos as $key=>$puesto){
                        if($puesto['puesto'] != null && $puesto['puesto'] != '' && $puesto['puesto'] != ' '){
                            $nuevo = null;

                            if($puesto['id'] != null && $puesto['id'] != '' && $puesto['id'] != ' '){
                                $nuevo = Puestostrabajo::findOne($puesto['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Puestostrabajo();
                                $nuevo->create_date = date('Y-m-d');
                            }

                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->nombre = $puesto['puesto'];

                            if($nivel == 1){
                                $nuevo->id_nivel1 = $id_nivel_1;
                            } else if($nivel == 2){
                                $nuevo->id_nivel1 = $id_nivel_1;
                                $nuevo->id_nivel2 = $id_nivel_2;
                            } else if($nivel == 3){
                                $nuevo->id_nivel1 = $id_nivel_1;
                                $nuevo->id_nivel2 = $id_nivel_2;
                                $nuevo->id_nivel3 = $id_nivel_3;
                            } else if($nivel == 4){
                                $nuevo->id_nivel1 = $id_nivel_1;
                                $nuevo->id_nivel2 = $id_nivel_2;
                                $nuevo->id_nivel3 = $id_nivel_3;
                                $nuevo->id_nivel4 = $id_nivel_4;
                            }

                           
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);


                                $id_riesgos = [];
                                if(isset($puesto['riesgos']) && $puesto['riesgos'] != null && $puesto['riesgos'] != ""){
                                    foreach($puesto['riesgos'] as $key=>$riesgo){
                                        //dd($model->aux_puestos,$puesto['riesgos'],$key,$riesgo);
                                        if($riesgo != null && $riesgo != null && $riesgo != ' '){
                                            $nuevo_riesgo = PuestoRiesgo::find()->where(['id_puesto'=>$nuevo->id])->andWhere(['id_riesgo'=>$riesgo])->one();
                                            if(!$nuevo_riesgo){
                                                $nuevo_riesgo = new PuestoRiesgo(); 
                                            }
                                            $nuevo_riesgo->id_puesto = $nuevo->id;
                                            $nuevo_riesgo->id_riesgo = $riesgo;
                                            $nuevo_riesgo->save();

                                            if($nuevo_riesgo){
                                                array_push($id_riesgos, $nuevo_riesgo->id);
                                            }
                                        }
                                    }
                                }

                                $deletes_riesgo = PuestoRiesgo::find()->where(['id_puesto'=>$nuevo->id])->andWhere(['not in','id',$id_riesgos])->all();
                                foreach($deletes_riesgo as $delete_r){//Eliminar los que se hayan quitado
                                    $delete_r->delete();
                                }
                            }
                        }
                    }
                }

                if($nivel == 1){
                    $deletes = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['not in','id',$id_guardar])->all();
                    foreach($deletes as $delete){//Eliminar los que se hayan quitado
                        $delete->status = 2;
                        $delete->save();
                    }
                } else if($nivel == 2){
                    $deletes = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['not in','id',$id_guardar])->all();
                    foreach($deletes as $delete){//Eliminar los que se hayan quitado
                        $delete->status = 2;
                        $delete->save();
                    }
                } else if($nivel == 3){
                    $deletes = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['not in','id',$id_guardar])->all();
                    foreach($deletes as $delete){//Eliminar los que se hayan quitado
                        $delete->status = 2;
                        $delete->save();
                    }
                } else if($nivel == 4){
                    $deletes = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$id_nivel_1])->andWhere(['id_nivel2'=>$id_nivel_2])->andWhere(['id_nivel3'=>$id_nivel_3])->andWhere(['id_nivel4'=>$id_nivel_4])->andWhere(['not in','id',$id_guardar])->all();
                    foreach($deletes as $delete){//Eliminar los que se hayan quitado
                        $delete->status = 2;
                        $delete->save();
                    }
                }
                



                $id_guardar = [];
                if(isset($model->aux_consultorios) && $model->aux_consultorios != '' && $model->aux_consultorios != null){
                    foreach($model->aux_consultorios as $key=>$consultorio){
                        if($consultorio['consultorio'] != null && $consultorio['consultorio'] != '' && $consultorio['consultorio'] != ' '){
                            $nuevo = null;

                            if($consultorio['id'] != null && $consultorio['id'] != '' && $consultorio['id'] != ' '){
                                $nuevo = Consultorios::findOne($consultorio['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Consultorios();
                            }
                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $nivel;
                            $nuevo->consultorio = $consultorio['consultorio'];
                            $nuevo->status = 1;
                            $nuevo->save();

                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }



                $id_guardar = [];
                if(isset($model->aux_programas) && $model->aux_programas != '' && $model->aux_programas != null){
                    foreach($model->aux_programas as $key=>$programa){
                        if($programa['programa'] != null && $programa['programa'] != '' && $programa['programa'] != ' '){
                            $nuevo = null;

                            if($programa['id'] != null && $programa['id'] != '' && $programa['id'] != ' '){
                                $nuevo = Programaempresa::findOne($programa['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Programaempresa();
                            }
                            
                            if($programa['programa'] == 0){
                                if($programa['programa'] != "" && $programa['otroprograma'] != null){
                                    $nprograma = new ProgramaSalud();
                                    $nprograma->nombre = $programa['otroprograma'];
                                    $nprograma->status = 1;
                                    $nprograma->save();
                                }
                            }else{
                                $nprograma = ProgramaSalud::find()->where(['id'=>$programa['programa']])->one();
                            }

                            if($nprograma){
                                $nuevo->id_empresa = $empresa->id;
                                $nuevo->id_superior = $model->id;
                                $nuevo->nivel = $nivel;
                                $nuevo->id_programa = $nprograma->id;
                                $nuevo->status = 1;
                                $nuevo->save();

                                if($nuevo){
                                    array_push($id_guardar, $nuevo->id);
                                }
                            }
                        }
                    }
                }
                $deletes = Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }



                $id_guardar = [];
                if(isset($model->aux_turnos) && $model->aux_turnos != '' && $model->aux_consultorios != null){
                    foreach($model->aux_turnos as $key=>$turno){
                        if($turno['turno'] != null && $turno['turno'] != '' && $turno['turno'] != ' '){
                            $nuevo = null;

                            if($turno['id'] != null && $turno['id'] != '' && $turno['id'] != ' '){
                                $nuevo = Turnos::findOne($turno['id']);  
                            } 
                            if(!$nuevo){
                                $nuevo = new Turnos();
                            }
                            $nuevo->id_empresa = $empresa->id;
                            $nuevo->id_superior = $model->id;
                            $nuevo->nivel = $nivel;
                            $nuevo->turno = $turno['turno'];
                            $nuevo->orden = $key;
                            $nuevo->status = 1;
                            $nuevo->save();
                            if($nuevo){
                                array_push($id_guardar, $nuevo->id);
                            }
                        }
                    }
                }
                $deletes = Turnos::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['not in','id',$id_guardar])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->status = 2;
                    $delete->save();
                }
    

                $searchModel = new EmpresasSearch();
                $searchModel->id = $empresa->id;
                $dataProvider = $searchModel->search($this->request->queryParams);

                return $this->render('../diagramas/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
                //return $this->redirect(['diagramas/index']);
            }
        }

        $nivel_1 = NivelOrganizacional1::findOne($id_nivel_1);
        $nivel_2 = NivelOrganizacional2::findOne($id_nivel_2);
        $nivel_3 = NivelOrganizacional3::findOne($id_nivel_3);
        $nivel_4 = NivelOrganizacional4::findOne($id_nivel_4);

        //dd($trabajadores_activos);

        return $this->renderAjax('contenido', [
            'empresa' => $empresa,
            'model' => $model,
            'trabajadores' => $trabajadores,
            'trabajadores_activos' =>$trabajadores_activos,
            'qty_areas' => $qty_areas,
            'qty_puestos'=>$qty_puestos,
            'qty_kpis'=>$qty_kpis,
            'nivel_1' => $nivel_1,
            'nivel_2' => $nivel_2,
            'nivel_3' => $nivel_3,
            'nivel_4' => $nivel_4,
        ]);
    }


    public function actionListniveles2(){
        $id_nivel = Yii::$app->request->post('id_nivel');
        $nivel1 = NivelOrganizacional1::findOne($id_nivel);
        
        
        $niveles = [];

        if($nivel1){
            $niveles = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['id_pais'=>$id_pais])->andWhere(['in','id',$id_lineas])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
            });
        }
    
        return \yii\helpers\Json::encode(['niveles' => $niveles]);
    }

}