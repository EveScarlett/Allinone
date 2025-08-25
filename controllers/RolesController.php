<?php

namespace app\controllers;

use app\models\Roles;
use app\models\Usuarios;
use app\models\Rolpermiso;
use app\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
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
     * Lists all Roles models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Roles model.
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
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Usuarios();
        $model->scenario = 'rol';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $rol = new Roles();
                $rol->nombre = $model->nombre;
                $rol->color = $model->color;
                $rol->admite_firma = $model->admite_firma;

                $rol->tiempo_limitado = $model->tiempo_limitado;
                $rol->tiempo_dias = $model->tiempo_dias;
                $rol->tiempo_horas = $model->tiempo_horas;
                $rol->tiempo_minutos = $model->tiempo_minutos;
                $rol->save();

                if($rol){
                    $this->saveMultiple($model,$rol);
                    return $this->redirect(['index']);
                }
                
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $rol = $this->findModel($id);
        $model = new Usuarios();
        $model->id = $rol->id;
        $model->nombre = $rol->nombre;
        $model->color = $rol->color;
        $model->admite_firma = $rol->admite_firma;

        $model->tiempo_limitado = $rol->tiempo_limitado;
        $model->tiempo_dias = $rol->tiempo_dias;
        $model->tiempo_horas = $rol->tiempo_horas;
        $model->tiempo_minutos = $rol->tiempo_minutos;

        $model->scenario = 'rol';

        $permisosusuario = $model->permisos;

        if($rol->permisos){
            foreach($rol->permisos as $key=>$permiso){
                if(array_key_exists($permiso->id_permiso, $permisosusuario)){
                    $model[$permiso->id_permiso]=1;
                } 
             }
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $rol->nombre = $model->nombre;
            $rol->color = $model->color;
            $rol->admite_firma = $model->admite_firma;

            $rol->tiempo_limitado = $model->tiempo_limitado;
            $rol->tiempo_dias = $model->tiempo_dias;
            $rol->tiempo_horas = $model->tiempo_horas;
            $rol->tiempo_minutos = $model->tiempo_minutos;
            $rol->save();

            if($rol){
                $this->saveMultiple($model,$rol);

                if($rol->usuarios){
                    foreach($rol->usuarios as $key=>$usuario){

                        $permisosrol = Rolpermiso::find()->where(['id_rol'=>$rol->id])->all();
                        
                        foreach($permisosusuario as $key=>$permiso){//Limpiar antes de
                            $usuario[$key]='0';
                        }

                        if($permisosrol){
                            //dd($rol->permisos);
                            foreach($permisosrol as $key=>$permiso){
                                if(array_key_exists($permiso->id_permiso, $permisosusuario)){
                                    $usuario[$permiso->id_permiso]='1';
                                }
                            }
                        }

                        //dd($usuario);

                        $this->crearPermisos($usuario);

                    }
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Roles model.
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
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function saveMultiple($model,$rol){
        $id_permisos = [];
        foreach ($model->permisos as $key=>$permiso){
            if($model[$key] == 1){
                $rolpermiso = new Rolpermiso();
                $rolpermiso->id_rol = $rol->id;
                $rolpermiso->id_permiso = $key;
                $rolpermiso->status = 1; 
                $rolpermiso->save();
                
                if($rolpermiso){
                    array_push($id_permisos, $rolpermiso->id);
                }
            }
        }

        $deletes = Rolpermiso::find()->where(['id_rol'=>$rol->id])->andWhere(['not in','id',$id_permisos])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }
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
}