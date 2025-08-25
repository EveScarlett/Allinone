<?php

namespace app\controllers;

use app\models\ProgramaSalud;
use app\models\ProgramaSaludSearch;
use app\models\Trabajadores;
use app\models\TrabajadoresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use Yii;

use app\models\Programasaludempresa;
/**
 * ProgramasaludController implements the CRUD actions for ProgramaSalud model.
 */
class ProgramasaludController extends Controller
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
     * Lists all ProgramaSalud models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProgramaSaludSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexps()
    {
        $searchModel = new TrabajadoresSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexps', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgramaSalud model.
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
     * Creates a new ProgramaSalud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgramaSalud();
        $model->status = 1;
        $model->scenario = 'create';
        $model->empresas_select2 = [Yii::$app->user->identity->id_empresa];

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {

                $model->save();

                $this->guardarEmpresas($model);
                
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
     * Updates an existing ProgramaSalud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        $empresas_select = [];
        foreach($model->empresas as $key=>$empresa){
            array_push($empresas_select, $empresa->id_empresa);
        }

        if($empresas_select && count($empresas_select)>0){
            $model->empresas_select2 = $empresas_select;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 2){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                } else {
                    $model->status = $status_anterior;
                }
            
            }
            
            $model->save();
            $this->guardarEmpresas($model);
                
            return $this->redirect(['index']);

                
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgramaSalud model.
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
     * Finds the ProgramaSalud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProgramaSalud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProgramaSalud::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    protected function guardarEmpresas($model) {
        //dd($model);
        $id_array = [];

        if($model->empresas_select2 > 0){
            
            foreach($model->empresas_select2 as $key=>$id_empresa){
                
                $nuevo = Programasaludempresa::find()->where(['id_programa'=>$model->id])->andWhere(['id_empresa'=>$id_empresa])->one();
                if(!$nuevo){
                    $nuevo = new Programasaludempresa();
                }
    
                $nuevo->id_programa = $model->id;
                $nuevo->id_empresa = $id_empresa;
                $nuevo->save();
    
                
                if($nuevo){
                    array_push($id_array, $nuevo->id);
                }   
            
            }

        } else {
            $nuevo = Programasaludempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['id_empresa'=>Yii::$app->user->identity->id_empresa])->one();
            if(!$nuevo){
                $nuevo = new Programasaludempresa();
            }

            $nuevo->id_programa = $model->id;
            $nuevo->id_empresa = Yii::$app->user->identity->id_empresa;
            $nuevo->save();

            
            if($nuevo){
                array_push($id_array, $nuevo->id);
            }  
                 
        }
        

        $deletes = Programasaludempresa::find()->where(['id_programa'=>$model->id])->andWhere(['not in','id',$id_array])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

        $model->empresas_select2 = null;
        return null;
    }
}