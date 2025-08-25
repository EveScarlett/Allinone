<?php

namespace app\controllers;

use app\models\Trashhistory;
use app\models\TrashhistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Usuarios;
use app\models\Trabajadores;
use app\models\Servicios;
use app\models\Hccohc;
use app\models\Poes;

use Yii;

/**
 * TrashhistoryController implements the CRUD actions for Trashhistory model.
 */
class TrashhistoryController extends Controller
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
     * Lists all Trashhistory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TrashhistorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trashhistory model.
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
     * Creates a new Trashhistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Trashhistory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Trashhistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Trashhistory model.
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
     * Finds the Trashhistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Trashhistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trashhistory::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionUp($id)
    {
        $model = Usuarios::findOne($id);

        if($model){
            $model->activo_eliminar = null;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionDown($id)
    {
        $model = Usuarios::findOne($id);

        if($model){
            $model->activo_eliminar = 2;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }


    public function actionRestore($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = $this->findModel($id);

        if($model){
            if($model->model != null && $model->model != '' && !$model->model != ' '){
                
                switch ($model->model) {
                    case 'Trabajadores':
                        $trabajador = Trabajadores::findOne($model->id_model);

                        if($trabajador){
                            if($trabajador->status_backup != null && $trabajador->status_backup != '' && $trabajador->status_backup != ' '){
                                $trabajador->status = $trabajador->status_backup;
                            }
                            $trabajador->status_baja = null;
                            $trabajador->save(false);
                        }

                        break;
                            
                    case 'Servicios':
                        $servicio = Servicios::findOne($model->id_model);

                        if($servicio){
                            if($servicio->status_backup != null && $servicio->status_backup != '' && $servicio->status_backup != ' '){
                                $servicio->status = $servicio->status_backup;
                            }
                            $servicio->status = 1;
                            $servicio->status_baja = null;
                            $servicio->save(false);
                        }

                        break;
                            
                    case 'Hccohc':
                        $hc = Hccohc::findOne($model->id_model);

                        if($hc){
                            if($hc->status_backup != null && $hc->status_backup != '' && $hc->status_backup != ' '){
                                $hc->status = $hc->status_backup;
                            }
                            $hc->status_baja = null;
                            $hc->save(false);
                        }
                        break;

                    case 'Poes':
                        $poe = Poes::findOne($model->id_model);

                        if($poe){
                            if($poe->status_backup != null && $poe->status_backup != '' && $poe->status_backup != ' '){
                                $poe->status = $poe->status_backup;
                            }
                            $poe->status_baja = null;
                            $poe->save(false);
                        }

                        break;
                            
                    default:
                        $ret = '';
                        break;
                }

                $model->restored_date = date('Y-m-d H:i:s');
                $model->restored_user = Yii::$app->user->identity->id;
                $model->save(false);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionRefresh()
    {
        $all = Trashhistory::find()->all();
        
        if($all){
            foreach($all as $key=>$data){
                if(true){
                    
                    $contenido  = '';

                    if($data->model != null && $data->model != '' && !$data->model != ' '){
                        switch ($data->model) {
                            case 'Trabajadores':
                                $trabajador = Trabajadores::findOne($data->id_model);

                                if($trabajador){
                                    $contenido = $trabajador->nombre.' '.$trabajador->apellidos;
                                }

                                break;
                            
                            case 'Servicios':
                                $servicio = Servicios::findOne($data->id_model);

                                if($servicio){
                                    $contenido = $servicio->nombre;
                                }

                                break;
                            
                            case 'Hccohc':
                                $hc = Hccohc::findOne($data->id_model);

                                if($hc){
                                    if($hc->trabajador){
                                        $contenido = $hc->trabajador->nombre.' '.$hc->trabajador->apellidos;
                                    }
                                }
                                break;

                            case 'Poes':
                                $poe = Poes::findOne($data->id_model);

                                if($poe){
                                    if($poe->trabajador){
                                        $contenido = $poe->trabajador->nombre.' '.$poe->trabajador->apellidos;
                                    }
                                }

                                break;
                            
                            default:
                                $ret = '';
                                break;
                        }
                    }


                    $data->contenido = $contenido;
                    $data->save(false);
                    //dd($data,$contenido);
                }
            }
        }
        return $this->redirect(['index']);
    }
}
