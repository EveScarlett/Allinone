<?php

namespace app\controllers;

use app\models\Almacen;
use app\models\Insumos;
use app\models\AlmacenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Trashhistory;
use Yii;

/**
 * AlmacenController implements the CRUD actions for Almacen model.
 */
class AlmacenController extends Controller
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
     * Lists all Almacen models.
     *
     * @return string
     */
    public function actionIndex($tipo)
    {
        $searchModel = new AlmacenSearch();
        $searchModel->tipo_insumo = $tipo;
        $dataProvider = $searchModel->search($this->request->queryParams);

        if(Yii::$app->user->identity->empresa_all == 0){
            $searchModel->id_empresa = Yii::$app->user->identity->id_empresa;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Displays a single Almacen model.
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
     * Creates a new Almacen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Almacen();

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
     * Updates an existing Almacen model.
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
     * Deletes an existing Almacen model.
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
     * Finds the Almacen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Almacen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Almacen::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionInfoinsumo(){
        $id = Yii::$app->request->post('id');
        $almacen = Almacen::find()->where(['id'=>$id])->one();

        $insumo = null;
        if($almacen){

            $insumo = Insumos::find()->where(['id'=>$almacen->id_insumo])->one();
            if($insumo){
                if($insumo->presentacion){
                    $insumo->id_presentacion = $insumo->presentacion->presentacion;
                }
                if($insumo->unidad){
                    $insumo->id_unidad = $insumo->unidad->unidad;
                }
            }
        }

        return \yii\helpers\Json::encode(['insumo' => $insumo,'almacen'=>$almacen]);
    }
}
