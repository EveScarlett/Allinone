<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\UsuariosSearch;
use app\models\Roles;
use app\models\Firmas;
use app\models\Empresas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;

use app\models\NivelOrganizacional1;

use app\models\Usuariotrabajador;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
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
     * Lists all Usuarios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
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
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Usuarios();
        $model->scenario = 'create';
        $model->status = 1;
        $firma = new Firmas();
        $model->id_empresa = Yii::$app->user->identity->id_empresa;
        $model->empresas_select = [Yii::$app->user->identity->id_empresa];
        $model->empresa_all = null;

        $this->actionGetniveles($model);

        $showempresa = true;
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);

        if(Yii::$app->user->identity->empresa_all != 1){
            if(count($empresas) == 1){
                $showempresa = false;
                $model->empresas_select = [Yii::$app->user->identity->id_empresa];
            }
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                //dd($model);

                if($model->envia_form == '1'){
                    //dd('Entro a Guardar');
                    if($model->rol == 0){
                        if($model->otra_rol != "" && $model->otra_rol != null){
                            $nuevo = new Roles();
                            $nuevo->nombre = $model->otra_rol;
                            $nuevo->save();
                            $model->rol = $nuevo->id;
                        } else{
                            $model->rol = null;
                        }
                    }
    
                    if($model->empresa_all == 0){
                        if(isset($model->empresas_select) && $model->empresas_select != '' && count($model->empresas_select)>0){
                            $test = $this->Arraytocomas($model->empresas_select);
                            $model->empresas_select = $test;
                        }else{
                            $model->empresas_select = null;
                        }
                    } else{
                        $model->empresas_select = null;
                    }


                    if($model->nivel1_all == 0){
                        if(isset($model->nivel1_select) && $model->nivel1_select != '' && count($model->nivel1_select)>0){
                            $test = $this->Arraytocomas($model->nivel1_select);
                            $model->nivel1_select = $test;
                        }else{
                            $model->nivel1_select = null;
                        }
                    } else{
                        $model->nivel1_select = null;
                    }


                    if($model->nivel2_all == 0){
                        if(isset($model->nivel2_select) && $model->nivel2_select != '' && count($model->nivel2_select)>0){
                            $test = $this->Arraytocomas($model->nivel2_select);
                            $model->nivel2_select = $test;
                        }else{
                            $model->nivel2_select = null;
                        }
                    } else{
                        $model->nivel2_select = null;
                    }


                    if($model->nivel3_all == 0){
                        if(isset($model->nivel3_select) && $model->nivel3_select != '' && count($model->nivel3_select)>0){
                            $test = $this->Arraytocomas($model->nivel3_select);
                            $model->nivel3_select = $test;
                        }else{
                            $model->nivel3_select = null;
                        }
                    } else{
                        $model->nivel3_select = null;
                    }


                    if($model->nivel4_all == 0){
                        if(isset($model->nivel4_select) && $model->nivel4_select != '' && count($model->nivel4_select)>0){
                            $test = $this->Arraytocomas($model->nivel4_select);
                            $model->nivel4_select = $test;
                        }else{
                            $model->nivel4_select = null;
                        }
                    } else{
                        $model->nivel4_select = null;
                    }


                    if($model->areas_all == 0){
                        if(isset($model->areas_select) && $model->areas_select != '' && count($model->areas_select)>0){
                            $test = $this->Arraytocomas($model->areas_select);
                            $model->areas_select = $test;
                        }else{
                            $model->areas_select = null;
                        }
                    } else{
                        $model->areas_select = null;
                    }


                    if($model->consultorios_all == 0){
                        if(isset($model->consultorios_select) && $model->consultorios_select != '' && count($model->consultorios_select)>0){
                            $test = $this->Arraytocomas($model->consultorios_select);
                            $model->consultorios_select = $test;
                        }else{
                            $model->consultorios_select = null;
                        }
                    } else{
                        $model->consultorios_select = null;
                    }


                    if($model->programas_all == 0){
                        if(isset($model->programas_select) && $model->programas_select != '' && count($model->programas_select)>0){
                            $test = $this->Arraytocomas($model->programas_select);
                            $model->programas_select = $test;
                        }else{
                            $model->programas_select = null;
                        }
                    } else{
                        $model->programas_select = null;
                    }


                    

                    $model->hidden = 0;
                    $model->save();
                    
    
                    $archivo = UploadedFile::getInstance($model,'file_foto');
                    $dir_ticket = __DIR__ . '/../web/resources/images/perfil/';
                    $directorios = ['0'=>$dir_ticket];
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'profile_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                        $model->foto = $nombre_archivo; 
                        $model->save();
                    }
    
                    if($model->save()){
                        $this->crearPermisos($model);
    
                        $rfirma = $this->saveFirma($model,$firma);
                        if($rfirma){
                            $model->id_firma = $rfirma->id;
                            $model->save();
                        }
    
                        return $this->redirect(['index']);
                    }
                } else {
                    $model->envia_form = 0;
                    if(isset($model->rol) && $model->rol != null && $model->rol != ''){
                        
                        $rol = Roles::findOne($model->rol);
                        $permisosusuario = $model->permisos;

                        foreach($permisosusuario as $key=>$permiso){
                            $model[$key]=0;
                        }

                        if($rol->permisos){
                            foreach($rol->permisos as $key=>$permiso){
                                if(array_key_exists($permiso->id_permiso, $permisosusuario)){
                                    $model[$permiso->id_permiso]=1;
                                } 
                             }
                        }
                        return $this->render('create', [
                            'model' => $model,
                        ]);
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
                    $model->nivel1_select = $array_nivel1;
                    
                }
            }

            if(Yii::$app->user->identity->nivel2_all != 1){
                $array_nivel2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(count($array_nivel2) == 1){
                    $model->nivel2_select = $array_nivel2;
                }
            }

            if(Yii::$app->user->identity->nivel3_all != 1){
                $array_nivel3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(count($array_nivel3) == 1){
                    $model->nivel3_select = $array_nivel3;
                }
            }

            if(Yii::$app->user->identity->nivel4_all != 1){
                $array_nivel4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(count($array_nivel4) == 1){
                    $model->nivel4_select = $array_nivel4;
                }
            }
        }
        
    }

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $this->cargarpermisos($model);
        //date_default_timezone_set('America/Mazatlan');

        if($model->firmaa){
            $firma = $model->firmaa;

            $model->f_nombre = $firma->nombre;
            $model->f_universidad = $firma->universidad;
            $model->f_cedula = $firma->cedula;
            $model->f_titulo = $firma->titulo;
            $model->f_abreviado_titulo = $firma->abreviado_titulo;
            $model->f_registro_sanitario = $firma->registro_sanitario;
            $model->f_firma = $firma->firma;
        }else{
            $firma = new Firmas();
        }

        $array = explode(',', $model->empresas_select);
        if($array && count($array)>0){
            $model->empresas_select = $array;
        }

        $array = explode(',', $model->nivel1_select);
        if($array && count($array)>0){
            $model->nivel1_select = $array;
        }

        $array = explode(',', $model->nivel2_select);
        if($array && count($array)>0){
            $model->nivel2_select = $array;
        }

        $array = explode(',', $model->nivel3_select);
        if($array && count($array)>0){
            $model->nivel3_select = $array;
        }

        $array = explode(',', $model->nivel4_select);
        if($array && count($array)>0){
            $model->nivel4_select = $array;
        }

        $array = explode(',', $model->areas_select);
        if($array && count($array)>0){
            $model->areas_select = $array;
        }

        $array = explode(',', $model->consultorios_select);
        if($array && count($array)>0){
            $model->consultorios_select = $array;
        }

        $array = explode(',', $model->programas_select);
        if($array && count($array)>0){
            $model->programas_select = $array;
        }


        $array_trabajadores = [];
        if($model->trabajadores){
            foreach($model->trabajadores as $key=>$data){
                array_push($array_trabajadores, $data->id_trabajador);
            }
        }
        if($array_trabajadores && count($array_trabajadores)>0){
            $model->trabajadores_select = $array_trabajadores;
        }


        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {

            if($model->envia_form == '1'){
                $status_anterior = intval($model->getOldAttribute('status'));
                $status_actual = intval($model->status);
                if($status_actual == 2){
                    if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                    } else {
                        $model->status = $status_anterior;
                    }
                }

                if($model->rol == 0){
                    if($model->otra_rol != "" && $model->otra_rol != null){
                        $nuevo = new Roles();
                        $nuevo->nombre = $model->otra_rol;
                        $nuevo->save();
                        $model->rol = $nuevo->id;
                    } else{
                        $model->rol = null;
                    }
                }
    
                if($model->empresa_all == 0){
                    if(isset($model->empresas_select) && $model->empresas_select != '' && count($model->empresas_select)>0){
                        $test = $this->Arraytocomas($model->empresas_select);
                        $model->empresas_select = $test;
                    }else{
                        $model->empresas_select = null;
                    }
                } else{
                    $model->empresas_select = null;
                }


                if($model->nivel1_all == 0){
                        if(isset($model->nivel1_select) && $model->nivel1_select != '' && count($model->nivel1_select)>0){
                            $test = $this->Arraytocomas($model->nivel1_select);
                            $model->nivel1_select = $test;
                        }else{
                            $model->nivel1_select = null;
                        }
                    } else{
                        $model->nivel1_select = null;
                    }


                    if($model->nivel2_all == 0){
                        if(isset($model->nivel2_select) && $model->nivel2_select != '' && count($model->nivel2_select)>0){
                            $test = $this->Arraytocomas($model->nivel2_select);
                            $model->nivel2_select = $test;
                        }else{
                            $model->nivel2_select = null;
                        }
                    } else{
                        $model->nivel2_select = null;
                    }


                    if($model->nivel3_all == 0){
                        if(isset($model->nivel3_select) && $model->nivel3_select != '' && count($model->nivel3_select)>0){
                            $test = $this->Arraytocomas($model->nivel3_select);
                            $model->nivel3_select = $test;
                        }else{
                            $model->nivel3_select = null;
                        }
                    } else{
                        $model->nivel3_select = null;
                    }


                    if($model->nivel4_all == 0){
                        if(isset($model->nivel4_select) && $model->nivel4_select != '' && count($model->nivel4_select)>0){
                            $test = $this->Arraytocomas($model->nivel4_select);
                            $model->nivel4_select = $test;
                        }else{
                            $model->nivel4_select = null;
                        }
                    } else{
                        $model->nivel4_select = null;
                    }


                    if($model->areas_all == 0){
                        if(isset($model->areas_select) && $model->areas_select != '' && count($model->areas_select)>0){
                            $test = $this->Arraytocomas($model->areas_select);
                            $model->areas_select = $test;
                        }else{
                            $model->areas_select = null;
                        }
                    } else{
                        $model->areas_select = null;
                    }


                    if($model->consultorios_all == 0){
                        if(isset($model->consultorios_select) && $model->consultorios_select != '' && count($model->consultorios_select)>0){
                            $test = $this->Arraytocomas($model->consultorios_select);
                            $model->consultorios_select = $test;
                        }else{
                            $model->consultorios_select = null;
                        }
                    } else{
                        $model->consultorios_select = null;
                    }


                    if($model->programas_all == 0){
                        if(isset($model->programas_select) && $model->programas_select != '' && count($model->programas_select)>0){
                            $test = $this->Arraytocomas($model->programas_select);
                            $model->programas_select = $test;
                        }else{
                            $model->programas_select = null;
                        }
                    } else{
                        $model->programas_select = null;
                    }
                    

                
                $model->save();
    
                $archivo = UploadedFile::getInstance($model,'file_foto');
                $dir_ticket = __DIR__ . '/../web/resources/images/perfil/';
                $directorios = ['0'=>$dir_ticket];
                $this->actionCarpetas( $directorios);
                
                if($archivo){
                    $nombre_archivo = 'profile_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                    $model->foto = $nombre_archivo; 
                    $model->save();
                } 
    
                if($model->save()){
                    //dd($model);
                    $this->crearPermisos($model);
    
                    $rfirma = $this->saveFirma($model,$firma);
                    
                    if($rfirma){
                        $model->id_firma = $rfirma->id;
                        $model->save();
                    }


                    $this->usuariosTrabajador($model);
    
                    return $this->redirect(['index']);
                }
            } else {
                //dd($model);
                $model->envia_form = 0;

                if(!is_array($model->empresas_select)){
                    $array = explode(',', $model->empresas_select);
                    if($array && count($array)>0){
                        $model->empresas_select = $array;
                    }
                }
                
                
                if(isset($model->rol) && $model->rol != null && $model->rol != ''){
                    
                    $rol = Roles::findOne($model->rol);
                    $permisosusuario = $model->permisos;

                    foreach($permisosusuario as $key=>$permiso){
                        $model[$key]=0;
                    }

                    if($rol->permisos){
                        foreach($rol->permisos as $key=>$permiso){
                            if(array_key_exists($permiso->id_permiso, $permisosusuario)){
                                $model[$permiso->id_permiso]=1;
                            } 
                         }
                    }
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Usuarios model.
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
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function cargarPermisos($model){
        $auth = Yii::$app->authManager;
        $permisosusuario = $model->permisos;

        //dd( $permisosusuario);
        
        foreach($model->permisos as $keyy=>$permiso){
            $model[$keyy]=0;
        }

        $permisos = $auth->getPermissionsByUser($model->id);//OBTENER TODOS LOS PERMISOS DE X USUARIO
        
        //LLENAR EL ARRAY DE CHECKBOXS, de acuerdo a los permisos del usuario
        foreach($permisos as $keyy=>$permiso){
           if(array_key_exists($permiso->name, $permisosusuario)){
               $model[$permiso->name]=1;
           } 
        }
    }

    protected function saveFirma($model,$firma) {
        $result = null;

        $archivo = UploadedFile::getInstance($model,'file_firma');
        $dir_ticket = __DIR__ . '/../web/resources/firmas/';
        $directorios = ['0'=>$dir_ticket];
        $this->actionCarpetas( $directorios);

        $firma->nombre = $model->f_nombre;
        $firma->universidad = $model->f_universidad;
        $firma->cedula = $model->f_cedula;
        $firma->titulo = $model->f_titulo;
        $firma->abreviado_titulo = $model->f_abreviado_titulo;
        $firma->registro_sanitario = $model->f_registro_sanitario;

        if($archivo){
            $nombre_archivo = 'firma_'.$model->f_nombre.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
            $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
            
            $firma->firma = $nombre_archivo; 
           
            $firma->fecha_inicio = date('Y-m-d');
            $firma->status = 1;
            $firma->save();
        } else if((isset($firma->nombre) && $firma->nombre !="")|| (isset($firma->universidad) && $firma->universidad !="") || (isset($firma->cedula) && $firma->cedula !="") || (isset($firma->titulo) && $firma->titulo !="") || (isset($firma->abreviado_titulo) && $firma->abreviado_titulo !="") || (isset($firma->registro_sanitario) && $firma->registro_sanitario !="")){
            $firma->fecha_inicio = date('Y-m-d');
            $firma->status = 1;
            $firma->save();
        }      

        return $firma;
    }

    protected function crearPermisos($model){
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $permisos = $auth->getPermissionsByUser($model->id);
        //dd($model);

        if (count($auth->getRolesByUser($model->id)) == 2){
            $rol = $auth->createRole($model->rol .  "_" . $model->id);
            $auth->add($rol);
        }else{
            $nombre_rol = array_keys($auth->getRolesByUser($model->id))[2];
            $rol = $auth->getRole($nombre_rol);
        }

        //dd($model);

        foreach ($model->permisos as $key=>$permiso){
            if(isset($model->$key)){
                if ($model->$key == 1 && !array_key_exists($key,$permisos)) {
                    if(!$auth->getPermission($key)){
                        $nuevopermiso = $auth->createPermission($key);
                        $nuevopermiso->description = $permiso;
                        $auth->add($nuevopermiso);
                    }
                    $permisoauth = $auth->getPermission($key);
                    $auth->addChild($rol, $permisoauth);
                }else if ($model->$key == 0 && array_key_exists($key,$permisos)) {
                    $permisoauth = $auth->getPermission($key);
                    $auth->removeChild($rol, $permisoauth);
                }
            }
        }
       
        $auth->revoke($rol,$model->id);
        $auth->assign($rol, $model->id);
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

    public function actionChecardisponibles(){
        $id_company = Yii::$app->request->post('id_empresa');//Obtener la empresa 
        $id_rol = Yii::$app->request->post('id_rol');//Obtener el rol deseado
        
        $mensaje = null;//Mensaje a enviar si hay un error
        $rol = Roles::findOne($id_rol);
        $empresa = Empresas::findOne($id_company);

        $nombre_rol = '';
        $nombre_empresa = '';
        $disponible = true;
        $limite = 0;
        $usuarios = 0;

        if($empresa){
            $id_empresa = $empresa->id;
        } else {
            $id_empresa = 0;
            $mensaje = '¡Seleccione primero la empresa!';
        }

        //Buscar los usuarios existentes de acuerdo al rol y a la empresa, que esten activos
        $usuarios = count(Usuarios::find()->where(['id_empresa'=>$id_empresa])->andWhere(['rol'=>$id_rol])->andWhere(['hidden'=>0])->andWhere(['status'=>1])->all());
       
        if($id_rol == 1){
            if($empresa){
                $limite = $empresa->configuracion->cantidad_administradores;
            } 
        } else if($id_rol == 2){
            if($empresa){
                $limite = $empresa->configuracion->cantidad_medicos;
            }
        } else {
            $limite = 100000;
        }

        if($rol){
            $nombre_rol = $rol->nombre;
        }
        if($empresa){
            $nombre_empresa = $empresa->comercial;
        }

        if($usuarios >= $limite || !$empresa){
            $disponible = false; 
        } 

        $label = 'Empresa '.$nombre_empresa.' | Limite: '.$limite.' | Ocupados: '.$usuarios;
        
        return \yii\helpers\Json::encode(['disponible' => $disponible,'texto'=> $label,'rol'=>$nombre_rol,'message'=>$mensaje]);
    }


    public function actionChecarstatus(){
        $id_company = Yii::$app->request->post('id_empresa');//Obtener la empresa 
        $id_rol = Yii::$app->request->post('id_rol');//Obtener el rol deseado

        $empresa = Empresas::findOne($id_company);
        $mensaje = null;//Mensaje a enviar si hay un error
        $rol = Roles::find()->where(['id'=>$id_rol])->one();

        $nombre_rol = '';
        $disponible = true;
        $limite = 0;
        $usuarios = 0;
        $nombre_empresa = '';

        if($empresa){
            $id_empresa = $empresa->id;
        } else {
            $id_empresa = 0;
            $mensaje = '¡Seleccione primero la empresa!';
        }

        $usuarios = count(Usuarios::find()->where(['id_empresa'=>$id_empresa])->andWhere(['rol'=>$id_rol])->andWhere(['hidden'=>0])->andWhere(['status'=>1])->all());
        //dd($usuarios);
        if($id_rol == 1){
            if($empresa){
                $limite = $empresa->configuracion->cantidad_administradores;
            }
        } else if($id_rol == 2){
            if($empresa){
                $limite = $empresa->configuracion->cantidad_medicos;
            }
        } else {
            $limite = 100000;
        }

        if($rol){
            $nombre_rol = $rol->nombre;
        }
        if($empresa){
            $nombre_empresa = $empresa->comercial;
        }
        
        if($usuarios >= $limite || !$empresa){
            $disponible = false; 
        }

        $label = 'Empresa '.$nombre_empresa.' | Limite: '.$limite.' | Ocupados: '.$usuarios;
        return \yii\helpers\Json::encode(['disponible' => $disponible,'texto'=> $label,'rol'=>$nombre_rol,'message'=>$mensaje]);
    }


    public function usuariosTrabajador($model){
        $id_trabajadoresusuario = [];
        if($model->trabajadores_select != null){
            foreach($model->trabajadores_select as $key=>$trabajador){
                $trab_usuario = Usuariotrabajador::find()->where(['id_usuario'=>$model->id])->andWhere(['id_trabajador'=>$trabajador])->one();

                if(!$trab_usuario){
                    $trab_usuario = new Usuariotrabajador();
                    $trab_usuario->id_usuario = $model->id;
                    $trab_usuario->id_trabajador = $trabajador;
                    $trab_usuario->status = 1;
                    $trab_usuario->save();
                } else {
                    $trab_usuario->status = 1;
                    $trab_usuario->save();
                }

                if($trab_usuario){
                    array_push($id_trabajadoresusuario, $trab_usuario->id);
                }
            }
        }

        //Borramos todos aquellos trabajadores que ya no estén activos
        $deletes = Usuariotrabajador::find()->where(['id_usuario'=>$model->id])->andWhere(['not in','id',$id_trabajadoresusuario])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }
    }

    
}