<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Diagnosticoscie;
use app\models\DiagnosticoscieSearch;
use app\models\Consultas;
use app\models\ConsultasSearch;
use app\models\Empresas;
use app\models\Cieconsulta;
use app\models\CieconsultaSearch;

use Yii;

/**
 * DiagnosticoscieController implements the CRUD actions for Diagnosticoscie model.
 */
class DiagnosticoscieController extends Controller
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
     * Lists all Diagnosticoscie models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiagnosticoscieSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexcie()
    {
        //$this->calcularCies();
        //dd('terminó');

        $searchModel = new CieconsultaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexcie', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function calcularCies(){
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
       
        $show_nivel1 = false;
        $show_nivel2 = false;
        $show_nivel3 = false;
        $show_nivel4 = false;

        $consultas_diagnostico = Consultas::find();
        

        if(Yii::$app->user->identity->empresa_all != 1) {
            $consultas_diagnostico->andFilterWhere(['in', 'id_empresa', $empresas]);
        } else {
            $empresa_usuario = Empresas::findOne($empresas[0]);

            if($empresa_usuario){
                if($empresa_usuario->cantidad_niveles >= 1){
                     $show_nivel1 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 2){
                     $show_nivel2 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 3){
                     $show_nivel3 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 4){
                     $show_nivel4 = true;
                }
            }
        }

         if($show_nivel1){
            if(Yii::$app->user->identity->nivel1_all != 1) {

                $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
                $paises_nivel = NivelOrganizacional1::find()->where(['in', 'id_empresa', $empresas])->andWhere(['in', 'id_pais', $array_niveles_1])->all();
            
                $niveles_1 = [];
                foreach($paises_nivel as $item){
                    if(!in_array($item->id, $niveles_1)){
                        array_push($niveles_1, $item->id);
                    }
                }
                $consultas_diagnostico->andFilterWhere(['in', 'id_nivel1', $niveles_1]);
            }
        }
        
        if($show_nivel2){
            $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
            if(Yii::$app->user->identity->nivel2_all != 1) {
                $consultas_diagnostico->andFilterWhere(['in', 'id_nivel2', $niveles_2]);
            }
        }
        
        if($show_nivel3){
            $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
            if(Yii::$app->user->identity->nivel3_all != 1) {
                $consultas_diagnostico->andFilterWhere(['in', 'id_nivel3', $niveles_3]);
            } 
        }
        
        if($show_nivel4){
            $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
            if(Yii::$app->user->identity->nivel4_all != 1) {
                $consultas_diagnostico->andFilterWhere(['in', 'id_nivel4', $niveles_4]);
            }
        }

        $consultas_diagnostico->andWhere(['not', ['diagnosticocie' => '']]);
       
        $final = $consultas_diagnostico->all();

        $id_cies = [];
        if(count($final)>0){
            foreach($final as $key=>$data){
                $id_cies = [];

                $array = explode(',', $data->diagnosticocie);
                foreach($array as $key2=>$cie){
                    if($cie != ''){
                        $existente = Cieconsulta::find()->where(['id_consulta'=>$data->id])->andWhere(['id_cie'=>$cie])->one();
                        if(!$existente){
                            $existente = new Cieconsulta();
                        }
                        $existente->id_empresa = $data->id_empresa;
                        $existente->id_nivel1 = $data->id_nivel1;
                        $existente->id_nivel2 = $data->id_nivel2;
                        $existente->id_nivel3 = $data->id_nivel3;
                        $existente->id_nivel4 = $data->id_nivel4;
                        $existente->id_consulta = $data->id;
                        $existente->id_cie = $cie;
                        $existente->fecha = $data->fecha;

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

                $deletes = Cieconsulta::find()->where(['id_consulta'=>$data->id])->andWhere(['not in','id',$id_cies])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }
            }
        }
    }

    /**
     * Displays a single Diagnosticoscie model.
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
     * Creates a new Diagnosticoscie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Diagnosticoscie();

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
     * Updates an existing Diagnosticoscie model.
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
     * Deletes an existing Diagnosticoscie model.
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
     * Finds the Diagnosticoscie model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Diagnosticoscie the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagnosticoscie::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
