<?php

namespace app\controllers;

use app\models\Vacantes;
use app\models\VacantesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empresas;
use app\models\Vacantetrabajador;
use yii\helpers\ArrayHelper;

use app\models\Paises;
use app\models\Paisempresa;
use Yii;

use app\models\NivelOrganizacional1;

/**
 * VacantesController implements the CRUD actions for Vacantes model.
 */
class VacantesController extends Controller
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
     * Lists all Vacantes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new VacantesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vacantes model.
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
     * Creates a new Vacantes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Vacantes();
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $model->scenario = 'create';

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->create_date = date('Y-m-d');
                return $this->redirect(['index']);
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
     * Updates an existing Vacantes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'create';

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 0){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                } else {
                    $model->status = $status_anterior;
                }
            
            }
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Vacantes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddworkers($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'add';

        //dd($model->trabajadoresvac);
        foreach($model->trabajadoresvac as $key=>$trabajador){
            $model->aux_trabajadores[$key]['id'] = $trabajador->id;
            $model->aux_trabajadores[$key]['trabajador'] = $trabajador->id_trabajador;
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Vacantes");

            if(isset($param['aux_trabajadores'])){
                
                $id_vacantestra = [];
                foreach($param['aux_trabajadores'] as $key=>$trabajador){
                    if(isset($trabajador['id']) && $trabajador['id'] != null && $trabajador['id'] != ''){
                        $vt = Vacantetrabajador::find()->where(['id'=> $trabajador['id']])->one();
                    } else {
                        $vt = new Vacantetrabajador();
                        $vt->id_vacante = $model->id;
                    }

                    if(isset($trabajador['trabajador']) && $trabajador['trabajador'] != null && $trabajador['trabajador'] != ''){
                        $vt->id_trabajador = $trabajador['trabajador'];
                        $vt->save();
    
                        if($vt){
                            array_push($id_vacantestra, $vt->id);
                        }
                    }
                    
                }
            }

            $deletes = Vacantetrabajador::find()->where(['id_vacante'=>$model->id])->andWhere(['not in','id',$id_vacantestra])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

            $trabajadores = Vacantetrabajador::find()->where(['id_vacante'=> $model->id])->all();
            $model->vacantesllenas = count($trabajadores);
            $model->save();
            
            return $this->redirect(['index']);
        }

        return $this->render('trabajadores', [
            'model' => $model,
        ]);
    }


    public function buscarTrabajadorespto($puesto){
        dd($puesto);
    }

    /**
     * Deletes an existing Vacantes model.
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
     * Finds the Vacantes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Vacantes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vacantes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionTraerpuestos(){
        $id = Yii::$app->request->post('id');
        $empresa = Empresas::find()->where(['id'=>$id])->one();
        
        $puestos =[];
        

        if($empresa){
            if($empresa->puestos){
                foreach($empresa->puestos as $key=>$puesto){
                    $puestos[$puesto->id] = $puesto->nombre;
                }
            }

            $id_paises = [];
        
            $id_empresa = $empresa->id;
        
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
        }

        return \yii\helpers\Json::encode(['puestos' => $puestos,'paises'=>$paises]);
    }

}