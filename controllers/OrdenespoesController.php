<?php

namespace app\controllers;

use app\models\Ordenespoes;
use app\models\OrdenespoesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Detalleordenpoe;
use app\models\Ordenpoetrabajador;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\Poes;
use app\models\Poeestudio;
use app\models\Trabajadores;
use Yii;

use app\models\NivelOrganizacional1;

/**
 * OrdenespoesController implements the CRUD actions for Ordenespoes model.
 */
class OrdenespoesController extends Controller
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
     * Lists all Ordenespoes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrdenespoesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ordenespoes model.
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
     * Creates a new Ordenespoes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ordenespoes();
        $model->scenario = 'create';
        $model->anio = date('Y');
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Ordenespoes");
                
                if($model->envia_form == '1'){
                    if($model->validate()){
                        //dd($model);
                        $model->create_date = date('Y-m-d');
                        $model->create_user = Yii::$app->user->identity->id;
                        $model->save();
                        $this->saveMultiple($model);
                        $this->saveTrabajadores($model);
                        $this->crearPoe($model);
    
                        return $this->redirect(['index']);
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

    /**
     * Updates an existing Ordenespoes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        dd($model->trabajadores);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ordenespoes model.
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
     * Finds the Ordenespoes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ordenespoes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ordenespoes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function saveMultiple($model){
        
        $id_estudios = [];
        if(isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
            foreach($model->aux_estudios as $key => $estudio){

                if(isset($estudio['id']) && $estudio['id'] != null && $estudio['id'] != ''){
                    $pe = Detalleordenpoe::find()->where(['id'=> $estudio['id']])->one();
                } else {
                    $pe = new Detalleordenpoe();
                    $pe->id_ordenpoe = $model->id;
                }

                if(isset($estudio['categoria']) || isset($estudio['estudio'])){
                   
                    $tipo = TipoServicios::find()->where(['id'=>$estudio['categoria']])->one();

                    if(isset($tipo)){
                        $nestudio = Servicios::find()->where(['id'=>$estudio['estudio']])->one();
                    }

                    if(isset($tipo) && isset($nestudio)){
                        $pe->id_estudio = $nestudio->id;
                        $pe->id_categoria = $tipo->id;
                        $pe->save();


                        if($pe){
                            array_push($id_estudios, $pe->id);
                        }
                    }
                    
                }
            }
        }

        $deletes = Detalleordenpoe::find()->where(['id_ordenpoe'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

    }

    private function saveTrabajadores($model){
        
        $id_trabajadores = [];
        if(isset($model->lista_trabajadores) && $model->lista_trabajadores != null && $model->lista_trabajadores != ''){

            foreach($model->lista_trabajadores as $key2 => $trabajador){
                if(isset($trabajador) && $trabajador != ""){
                    $dt = new Ordenpoetrabajador();
                    $dt->id_ordenpoe = $model->id;
                    $dt->id_trabajador = $trabajador;
                    $dt->save();
                    //dd($dt);
                }
                if($dt){
                    array_push($id_trabajadores, $dt->id);
                }
            }
        }

        $deletes = Ordenpoetrabajador::find()->where(['id_ordenpoe'=>$model->id])->andWhere(['not in','id',$id_trabajadores])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

    }

    private function crearPoe($model){
        if($model->trabajadores){
            foreach($model->trabajadores as $key=>$trabajador){
                $poe = new Poes();
                $poe->origen = 2;
                $poe->id_ordenpoetrabajador = $model->id;
                $poe->id_empresa = $model->id_empresa;
                $poe->id_trabajador = $trabajador->id_trabajador;
                $poe->anio = $model->anio;
                $poe->create_date = $model->create_date;
                $poe->create_user = $model->create_user;
                $poe->status = 0;

                if($trabajador->trabajador){//Guardar solo si existe el trabajador
                    $poe->nombre = $trabajador->trabajador->nombre;
                    $poe->apellidos = $trabajador->trabajador->apellidos;
                    $poe->sexo = $trabajador->trabajador->sexo;
                    $poe->fecha_nacimiento = $trabajador->trabajador->fecha_nacimiento;
                    $poe->num_imss = $trabajador->trabajador->num_trabajador;
                    $poe->id_puesto = $trabajador->trabajador->id_puesto;
                    $poe->id_area = $trabajador->trabajador->id_area;
                    $poe->save();

                    if($poe){
                        foreach($model->estudios as $key2=>$estudio){
                            $pe = new Poeestudio();
                            $pe->id_poe = $poe->id;
                            $pe->id_tipo = $estudio->id_categoria;
                            $pe->id_estudio = $estudio->id_estudio;
                            $pe->status = 0;
                            $pe->condicion = 100;
                            $pe->evolucion = 100;
                            $pe->save();
                        }
                    }
                }
                
            } 
        }

    }
}