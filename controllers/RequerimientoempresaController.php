<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\EmpresasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Requerimientoempresa;
use app\models\Estudios;
use app\models\Trabajadorestudio;
use Yii;

/**
 * RequerimientoempresaController implements the CRUD actions for Empresas model.
 */
class RequerimientoempresaController extends Controller
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
        $model->scenario = 'requerimientos';

        foreach($model->requisitos as $key=>$estudio){
            $model->aux_requisitos[$key]['tipo'] = $estudio->id_tipo;
            $model->aux_requisitos[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_requisitos[$key]['periodicidad'] = $estudio->id_periodicidad;
            $model->aux_requisitos[$key]['fecha_apartir'] = $estudio->fecha_apartir;
            $model->aux_requisitos[$key]['status'] = $estudio->id_status;
            
            $model->aux_requisitos[$key]['id'] = $estudio->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            //dd($model);
            $this->saveMultiple($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveMultiple($model){
        
        
        $id_estudios = [];
        if(isset($model->aux_requisitos) && $model->aux_requisitos != null && $model->aux_requisitos != ''){
            foreach($model->aux_requisitos as $key => $estudio){

                if(isset($estudio['id']) && $estudio['id'] != null && $estudio['id'] != ''){
                    $re = Requerimientoempresa::find()->where(['id'=> $estudio['id']])->one();
                } else {
                    $re = new Requerimientoempresa();
                    $re->id_empresa = $model->id;
                }

                if(isset($estudio['tipo'])){
                    if($estudio['estudio'] == 0){
                        if($estudio['otro'] != "" && $estudio['otro'] != null){
                            $nestudio = new Estudios();
                            $nestudio->estudio = $estudio['otro'];
                            $nestudio->tipo = $estudio['tipo'];
                            $nestudio->save();
                        }
                    }else{
                        $nestudio = Estudios::find()->where(['id'=>$estudio['estudio']])->one();
                    }
                }

                if(isset($estudio['tipo']) && $nestudio){
                    
                    $re->id_tipo = $estudio['tipo'];
                    $re->id_estudio = $nestudio->id;
                    $re->fecha_apartir = $estudio['fecha_apartir'];
                    
                    if(isset($estudio['periodicidad']) && $estudio['periodicidad'] != '' && $estudio['periodicidad'] != null && $estudio['periodicidad'] != ' '){
                        //dd('Si tiene: '.$estudio['periodicidad']);
                        $re->id_periodicidad = $estudio['periodicidad'];
                    }else {
                        //dd('No tiene: '.$estudio['periodicidad']);
                        $re->id_periodicidad = 0;
                    }

                    if(isset($estudio['status']) && $estudio['status'] != '' && $estudio['status'] != null && $estudio['status'] != ''){
                        $re->id_status = $estudio['status'];
                        //dd('Si tiene: '.$estudio['status']);
                    }else {
                        $re->id_status = 1;
                        //dd('No tiene: '.$estudio['status']);
                    }
                
                    $re->save();

                    if($re){
                        array_push($id_estudios, $re->id);
                    }
                }
            }
        }

        $deletes = Requerimientoempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

        //dd($model->requisitos);

        if($model->trabajadores){
            foreach($model->trabajadores as $key => $trabajador){

                $id_estudios = [];

                if($trabajador->id_puesto != null && $trabajador->id_puesto != "" && $trabajador->id_puesto != " "){
                    
                    //CHECAMOS LOS REQUISITOS MINIMOS-------------------------------------------
                    foreach($model->requisitosactivos as $key2=>$requisito){
                        $estudio = Trabajadorestudio::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_estudio'=>$requisito->id_estudio])->one();
                        if(!$estudio){
                            $estudio = new Trabajadorestudio();
                            $estudio->id_trabajador = $trabajador->id;
                            $estudio->id_estudio = $requisito->id_estudio;
                            $estudio->status = 0;
                        }
                        $estudio->fecha_apartir = $requisito->fecha_apartir;
                        $estudio->id_tipo = $requisito->id_tipo;
                        $estudio->id_periodicidad = $requisito->id_periodicidad;
                        $estudio->save();

                        if($estudio){
                            array_push($id_estudios, $estudio->id);
                        }
                    }
                    //CHECAMOS LOS REQUISITOS MINIMOS-------------------------------------------


                    //CHECAMOS LOS REQUISITOS DEL PUESTO-------------------------------------------
                    if($trabajador->puesto){
                        //dd($trabajador->puesto);
                        $puesto = $trabajador->puesto;
                        if($puesto->pestudiosactivos){

                            foreach($puesto->pestudiosactivos as $key3 =>$requisito){
                                
                                $estudio = Trabajadorestudio::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_estudio'=>$requisito->id_estudio])->one();
                                if(!$estudio){
                                    $estudio = new Trabajadorestudio();
                                    $estudio->id_trabajador = $trabajador->id;
                                    $estudio->id_estudio = $requisito->id_estudio;
                                    $estudio->status = 0;
                                }
                                $estudio->fecha_apartir = $requisito->fecha_apartir;
                                $estudio->id_tipo = $requisito->id_tipo;
                                $estudio->id_periodicidad = $requisito->periodicidad;
                                $estudio->save();
        
                                if($estudio){
                                    array_push($id_estudios, $estudio->id);
                                }

                            }
                        }
                    }
                    //CHECAMOS LOS REQUISITOS DEL PUESTO-------------------------------------------
                }


                //Borramos todos aquellos requisitos que ya no estén activos
                $deletes = Trabajadorestudio::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['not in','id',$id_estudios])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }
                $this->actualizaDocumento($trabajador);
                        
            }
        
        }

    }


    private function actualizaDocumento($trabajador){
        //date_default_timezone_set('America/Mazatlan');

        if($trabajador->testudios){
            //Revisamos todos los estudios del trabajador, por si hay que actualizar el status de alguno
            foreach($trabajador->testudios as $key=>$estudio){

                //Si la periodicidad es indefinida
                if($estudio->id_periodicidad == '0'){
                    //Checamos si se definio una fecha a partir de la cual se pide obligatoriamente el documento
                    if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){
                        
                        if($estudio->fecha_documento != null && $estudio->fecha_documento != '' && $estudio->fecha_documento != ' '){
                            $estudio->status = 1; 
                        } else{
                            $date_hoy = date('Y-m-d');
                            if($date_hoy > $estudio->fecha_apartir){//Si hoy supera la fecha a partir de la cual se pide el doc
                                $estudio->status = 2;
                            } else{
                                $estudio->status = 1;
                            }
                        }
                    } else{ //Si no se definio fecha a partir, lo ponemos en 0
                        $estudio->status = 0;
                    }
            
                } else { //Si la periodicidad no es indefinida, entonces debe tener fecha de vencimiento

                    //Si no tiene fecha de vencimiento, checamos nada mas si ya se paso la fecha a partir de la cual es obligatorio
                    //STATUS 0 = INDEFINIDO, 1 = VIGENTE, 2 = VENCIDO
                    if($estudio->fecha_vencimiento == '' || $estudio->fecha_vencimiento == null){

                        if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){
                            //dd('hay fecha a partir: '.$estudio['fecha_apartir']);
                            
                            $date_hoy = date('Y-m-d');
                            if($date_hoy > $estudio->fecha_apartir){
                                //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : YA VENCIO');
                                $estudio->status = 2;
                            } else{
                                //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : SIGUE VIGENTE');
                                $estudio->status = 0;
                            }
                        } else{
                            $estudio->status = 0;
                        }
                    } else{
                        
                        //Si si tenemos fecha de vencimiento vemos si ya se vencio ()
                        $date_hoy = date('Y-m-d');
                        $date_vencimiento = $estudio->fecha_vencimiento;

                        if($date_hoy > $date_vencimiento){
                            $estudio->status = 2;
                        } else {
                            $estudio->status = 1;
                        }
                    }
                }
                $estudio->save();
            }
        }

        //STATUS 0 => PENDIENTE, 1 = CUMPLE, 2 = NO CUMPLE
        $status_documentos = 0;
        $total_estudios = count($trabajador->testudios);
        $cumplen = 0;
        $nocumplen = 0;
        $pendientes = 0;

        //dd($total_estudios);
        if($trabajador->testudios){
            foreach($trabajador->testudios as $key=>$estudio){
                if($estudio->status == 0){
                    $pendientes ++;
                } else if($estudio->status == 1){
                    $cumplen ++;
                } else if($estudio->status == 2){
                    $nocumplen ++;
                }
            }
        }

        if ($total_estudios == $pendientes){
            $status_documentos = 0;
        } else if ($total_estudios == $cumplen){
            $status_documentos = 1;
        } else{
            $status_documentos = 2;
        }

        $trabajador->status_documentos = $status_documentos;
        $trabajador->save();
    }

    public function actionTipo(){
        $id = Yii::$app->request->post('id');
        $estudios = Estudios::find()->where(['tipo'=>$id])->all();
        
        
        //return \yii\helpers\Json::encode(['empresa' => $empresa,'trabajadores'=>$trabajadores]);
        return \yii\helpers\Json::encode(['estudios' => $estudios]);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}