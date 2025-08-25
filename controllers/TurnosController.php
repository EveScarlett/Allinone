<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\EmpresasSearch;
use app\models\Turnos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Turnopersonal;

/**
 * TurnosController implements the CRUD actions for Empresas model.
 */
class TurnosController extends Controller
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
        $model = $this->findModel($id);
        //dd($model);

        return $this->render('view', [
            'model' => $model,
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
     * Updates an existing Empresas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        foreach($model->turnos as $key=>$turno){
            $model->lunes_inicio[$turno->id] = $turno->lunes_inicio;
            $model->martes_inicio[$turno->id] = $turno->martes_inicio;
            $model->miercoles_inicio[$turno->id] = $turno->miercoles_inicio;
            $model->jueves_inicio[$turno->id] = $turno->jueves_inicio;
            $model->viernes_inicio[$turno->id] = $turno->viernes_inicio;
            $model->sabado_inicio[$turno->id] = $turno->sabado_inicio;
            $model->domingo_inicio[$turno->id] = $turno->domingo_inicio;

            $model->lunes_fin[$turno->id] = $turno->lunes_fin;
            $model->martes_fin[$turno->id] = $turno->martes_fin;
            $model->miercoles_fin[$turno->id] = $turno->miercoles_fin;
            $model->jueves_fin[$turno->id] = $turno->jueves_fin;
            $model->viernes_fin[$turno->id] = $turno->viernes_fin;
            $model->sabado_fin[$turno->id] = $turno->sabado_fin;
            $model->domingo_fin[$turno->id] = $turno->domingo_fin;

            $model->requiere_enfermeros[$turno->id] = $turno->requiere_enfermeros;
            $model->requiere_medicos[$turno->id] = $turno->requiere_medicos;
            $model->requiere_extras[$turno->id] = $turno->requiere_extras;
            $model->cantidad_enfermeros[$turno->id] = $turno->cantidad_enfermeros;
            $model->cantidad_medicos[$turno->id] = $turno->cantidad_medicos;
            $model->cantidad_extras[$turno->id] = $turno->cantidad_extras;
            
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            ////dd($model);
            if(isset($model->lunes_inicio) && $model->lunes_inicio != null && $model->lunes_inicio != ''){
                foreach($model->lunes_inicio as $key => $dturno){
                    $turno = Turnos::find()->where(['id'=>$key])->one();
                    if($turno){
                        $turno->lunes_inicio = $model->lunes_inicio[$key];
                        $turno->martes_inicio = $model->martes_inicio[$key];
                        $turno->miercoles_inicio = $model->miercoles_inicio[$key];
                        $turno->jueves_inicio = $model->jueves_inicio[$key];
                        $turno->viernes_inicio = $model->viernes_inicio[$key];
                        $turno->sabado_inicio = $model->sabado_inicio[$key];
                        $turno->domingo_inicio = $model->domingo_inicio[$key];

                        $turno->lunes_fin = $model->lunes_fin[$key];
                        $turno->martes_fin = $model->martes_fin[$key];
                        $turno->miercoles_fin = $model->miercoles_fin[$key];
                        $turno->jueves_fin = $model->jueves_fin[$key];
                        $turno->viernes_fin = $model->viernes_fin[$key];
                        $turno->sabado_fin = $model->sabado_fin[$key];
                        $turno->domingo_fin = $model->domingo_fin[$key];

                        $turno->requiere_enfermeros = $model->requiere_enfermeros[$key];
                        $turno->requiere_medicos = $model->requiere_medicos[$key];
                        $turno->requiere_extras = $model->requiere_extras[$key];
                        $turno->cantidad_enfermeros = $model->cantidad_enfermeros[$key];
                        $turno->cantidad_medicos = $model->cantidad_medicos[$key];
                        $turno->cantidad_extras = $model->cantidad_extras[$key];
                        $turno->save();
                    }
                }
            }

            $this->saveMultiple($model);

            //dd($model);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveMultiple($model){
        
        $id_personal = [];
        if(isset($model->aux_personal) && $model->aux_personal != null && $model->aux_personal != ''){
            foreach($model->aux_personal as $key => $personal){

                if(isset($personal['id']) && $personal['id'] != null && $personal['id'] != ''){
                    $tp = Turnopersonal::find()->where(['id'=> $personal['id']])->one();
                } else {
                    $tp = new Turnopersonal();
                    $tp->id_turno = $model->id;
                }

                if(isset($personal['personal']) || isset($personal['cantidad'])){
                   
                    $tp->nombre_personal = $estudio['personal'];
                    $tp->cantidad = $estudio['cantidad'];
                    $tp->save();

                    if($tp){
                        array_push($id_personal, $tp->id);
                    }
                    
                }
            }
        }

        $deletes = Turnopersonal::find()->where(['id_turno'=>$model->id])->andWhere(['not in','id',$id_personal])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

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

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
