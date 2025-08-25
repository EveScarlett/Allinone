<?php

namespace app\controllers;

use app\models\Insumostockmin;
use app\models\InsumostockminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Insumos;
use Yii;

/**
 * InsumostockminController implements the CRUD actions for Insumostockmin model.
 */
class InsumostockminController extends Controller
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
     * Lists all Insumostockmin models.
     *
     * @return string
     */
    public function actionIndex($tipo)
    {
        $searchModel = new InsumostockminSearch();
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
     * Displays a single Insumostockmin model.
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
     * Creates a new Insumostockmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($tipo)
    {
        $model = new Insumostockmin();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                //Ver si ya existe uno con el mismo insumo y consultorio para que no se repita
                $stockmin = Insumostockmin::find()->where(['id_insumo'=>$model->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->one();
               
                if($stockmin){
                    $stockmin->stock = $model->stock;
                    $stockmin->stock_unidad = $model->stock_unidad;
                    $stockmin->tipo_insumo = $tipo;
                    $stockmin->save();
                } else{
                    $model->tipo_insumo = $tipo;
                    $model->save();
                    //dd($model);
                }
                
                return $this->redirect(['index','tipo'=>$tipo]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Updates an existing Insumostockmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$tipo)
    {
        $model = $this->findModel($id);

        $model->cantidad_caja = 1;
        if($model->insumo){
            if(isset($model->insumo->unidades_individuales) && $model->insumo->unidades_individuales != '' && $model->insumo->unidades_individuales != ' ' && $model->insumo->unidades_individuales != null && $model->insumo->unidades_individuales != 0){
                $model->cantidad_caja = $model->insumo->unidades_individuales;
            } else{
                $model->cantidad_caja = 1;
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {

            $stockmin = Insumostockmin::find()->where(['id_insumo'=>$model->id_insumo])->andWhere(['id_consultorio'=>$model->id_consultorio])->one();
               
            if($stockmin){
                    $stockmin->stock = $model->stock;
                    $stockmin->stock_unidad = $model->stock_unidad;
                    $stockmin->save();
            } else{
                    $model->save();
            }
            return $this->redirect(['index','tipo'=>$tipo]);
        }

        return $this->render('update', [
            'model' => $model,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Deletes an existing Insumostockmin model.
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
     * Finds the Insumostockmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Insumostockmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insumostockmin::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionInfoinsumo(){
        $id = Yii::$app->request->post('id');
        $insumo = Insumos::find()->where(['id'=>$id])->one();
        
        //return \yii\helpers\Json::encode(['empresa' => $empresa,'trabajadores'=>$trabajadores]);
        return \yii\helpers\Json::encode(['insumo' => $insumo]);
    }
}