<?php

namespace app\controllers;

use app\models\Puestostrabajo;
use app\models\PuestostrabajoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Riesgos;
use app\models\Estudios;
use app\models\Empresas;
use app\models\PuestoRiesgo;
use app\models\PuestoEstudio;
use app\models\Epps;
use app\models\PuestoEpp;
use app\models\Trabajadorestudio;
use app\models\Parametros;
use app\models\Puestoparametro;

use app\models\Ni;
use app\models\Nirequisitos;
use Yii;

use app\models\NivelOrganizacional1;

/**
 * PuestosTrabajoController implements the CRUD actions for PuestosTrabajo model.
 */
class PuestostrabajoController extends Controller
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
     * Lists all PuestosTrabajo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PuestostrabajoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PuestosTrabajo model.
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
     * Creates a new PuestosTrabajo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Puestostrabajo();
        $model->status = 1;
        $model->scenario = 'create';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                //dd($model);
                //date_default_timezone_set('America/Mazatlan');
                $model->create_date = date('Y-m-d');
                $model->create_user = Yii::$app->user->identity->id;
                $model->save();
                

                //Guardar los nuevos riesgos del trabajo
                $id_riesgos = [];
                $id_estudios = [];
                $id_epps = [];
                if(isset($model->aux_otrosriesgos['riesgo'])){
                    foreach($model->aux_otrosriesgos['riesgo'] as $key=>$riesgo){
                        if($riesgo != null && $riesgo != '' && $riesgo != ' '){
                            $nuevo = new Riesgos();  
                            $nuevo->riesgo = $riesgo;
                            $nuevo->save();
                            if($nuevo){
                                array_push($id_riesgos, $nuevo->id);
                            }
                        }
                    }
                }
                
                if(isset($model->aux_riesgos) && $model->aux_riesgos != null && $model->aux_riesgos != ""){
                    foreach($model->aux_riesgos as $key=>$riesgo){
                        if($riesgo != null && $riesgo != null && $riesgo != ' '){
                            array_push($id_riesgos, $riesgo);
                        }
                    }
                }

                foreach($id_riesgos as $key=>$riesgo){
                    $nuevo = new PuestoRiesgo();  
                    $nuevo->id_puesto = $model->id;
                    $nuevo->id_riesgo = $riesgo;
                    $nuevo->save();
                }
                
                if(isset($model->aux_otrosepps['epp'])){
                    foreach($model->aux_otrosepps['epp'] as $key=>$epp){
                        if($epp != null && $epp != '' && $epp != ' '){
                            $nuevo = new Epps();  
                            $nuevo->epp = $epp;
                            $nuevo->save();
                            if(isset($nuevo)){
                                array_push($id_epps, $nuevo->id);
                            }else{
                                
                            }
                        }
                    }
                }
                
                if(isset($model->aux_epps) && $model->aux_epps != null && $model->aux_epps != ""){
                    foreach($model->aux_epps as $key=>$epp){
                        if($epp != null && $epp != null && $epp != ' '){
                            array_push($id_epps, $epp);
                        }
                    }
                }
               

                foreach($id_epps as $key=>$epp){
                    $nuevo = new PuestoEpp();  
                    $nuevo->id_puesto = $model->id;
                    $nuevo->id_epp = $epp;
                    $nuevo->save();
                }


                $this->saveMultiple($model);

                $this->guardarParametros($model);
                
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
     * Updates an existing PuestosTrabajo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        $aux_riesgos = [];
        $aux_epps = [];

        foreach($model->riesgos as $key=>$riesgo){
            array_push($aux_riesgos, $riesgo->id);
        }
        foreach($model->epps as $key=>$epp){
            array_push($aux_epps, $epp->id);
        }

        foreach($model->pestudiosactivos as $key=>$estudio){
            $model->aux_estudios[$key]['tipo'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_estudios[$key]['periodicidad'] = $estudio->periodicidad;
            $model->aux_estudios[$key]['fecha_apartir'] = $estudio->fecha_apartir;
            $model->aux_estudios[$key]['status'] = $estudio->id_status;
            $model->aux_estudios[$key]['id'] = $estudio->id;
        }

        
        foreach($model->parametros as $key=>$parametro){
            $model->aux_psicologico[$key]['parametro'] = $parametro->id_parametro;
            $model->aux_psicologico[$key]['valor'] = $parametro->valor;
            $model->aux_psicologico[$key]['id'] = $parametro->id;
        }


        foreach($model->requisitosni as $key=>$estudio){
            $model->aux_ni[$key]['id_requisito'] = $estudio->tipo_doc_examen.'_'.$estudio->id_requisito;
            $model->aux_ni[$key]['periodicidad'] = $estudio->periodicidad;
            $model->aux_ni[$key]['fecha_inicio'] = $estudio->fecha_inicio;
            $model->aux_ni[$key]['status'] = $estudio->status;
            $model->aux_ni[$key]['tipo_doc_examen'] = $estudio->tipo_doc_examen;
            $model->aux_ni[$key]['id'] = $estudio->id;
        }
        //dd($model);

        $model->aux_epps =  $aux_epps;
        $model->aux_riesgos =  $aux_riesgos;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            //dd($model);
            $model->update_date = date('Y-m-d');
            $model->update_user = Yii::$app->user->identity->id;

            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 0){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                } else {
                    $model->status = $status_anterior;
                }
            
            }
            $model->save();

            //Guardar los nuevos riesgos del trabajo
            $id_riesgos = [];
            $id_epps = [];



            if(isset($model->aux_otrosriesgos['riesgo'])){
                foreach($model->aux_otrosriesgos['riesgo'] as $key=>$riesgo){
                    if($riesgo != null && $riesgo != '' && $riesgo != ' '){
                        $nr = new Riesgos();  
                        $nr->riesgo = $riesgo;
                        $nr->save();
                        if($nr){
                            $nuevo = new PuestoRiesgo();  
                            $nuevo->id_puesto = $model->id;
                            $nuevo->id_riesgo = $nr->id;
                            $nuevo->save();

                            array_push($id_riesgos, $nuevo->id);
                        }
                    }
                }
            }

            //dd($model->aux_riesgos);
            if(isset($model->aux_riesgos) && $model->aux_riesgos != null && $model->aux_riesgos != ""){
                foreach($model->aux_riesgos as $key=>$riesgo){
                    if($riesgo != null && $riesgo != "" && $riesgo != ' '){
                        $briesgo = PuestoRiesgo::find()->where(['id_puesto'=>$model->id])->andWhere(['id_riesgo'=>$riesgo])->one(); 
                        
                        if($briesgo){
                            array_push($id_riesgos, $briesgo->id);
                        }else{
                            //dd($riesgo);
                            $nuevo = new PuestoRiesgo();  
                            $nuevo->id_puesto = $model->id;
                            $nuevo->id_riesgo = $riesgo;
                            $nuevo->save();
    
                            array_push($id_riesgos, $nuevo->id);
                        }
                    }
                }
            }
            
            $deletes = PuestoRiesgo::find()->where(['id_puesto'=>$model->id])->andWhere(['not in','id',$id_riesgos])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }



            if(isset($model->aux_otrosepps['epp'])){
                foreach($model->aux_otrosepps['epp'] as $key=>$epp){
                    if($epp != null && $epp != '' && $epp != ' '){
                        $nr = new Epps();  
                        $nr->epp = $epp;
                        $nr->save();
                        if($nr){
                            $nuevo = new PuestoEpp();  
                            $nuevo->id_puesto = $model->id;
                            $nuevo->id_epp = $nr->id;
                            $nuevo->save();

                            array_push($id_epps, $nuevo->id);
                        }
                    }
                }
            }

            if(isset($model->aux_epps) && $model->aux_epps != null && $model->aux_epps != ""){
                foreach($model->aux_epps as $key=>$epp){
                    if($epp != null && $epp != "" && $epp != ' '){
                        $briesgo = PuestoEpp::find()->where(['id_puesto'=>$model->id])->andWhere(['id_epp'=>$epp])->one(); 
                        
                        if($briesgo){
                            array_push($id_epps, $briesgo->id);
                        }else{
                            //dd($riesgo);
                            $nuevo = new PuestoEpp();  
                            $nuevo->id_puesto = $model->id;
                            $nuevo->id_epp = $epp;
                            $nuevo->save();
    
                            array_push($id_epps, $nuevo->id);
                        }
                    }
                }
            }
            
            $deletes = PuestoEpp::find()->where(['id_puesto'=>$model->id])->andWhere(['not in','id',$id_epps])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }


            $this->saveMultiple($model);

            $this->guardarParametros($model);
            
            
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveMultiple($model){
        //dd($model);
        $id_estudios = [];

        if(isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
            foreach($model->aux_estudios as $key=>$estudio){
                if($estudio['tipo'] != null && $estudio['tipo'] != '' && $estudio['tipo'] != ' '){
                   
                    if($estudio['estudio'] != null && $estudio['estudio'] != '' && $estudio['estudio'] != ' '){

                        if($estudio['estudio'] == 0){

                            //dd('Entra nuevo estudio:'.$estudio['estudio']);
                            if($estudio['otro'] != null && $estudio['otro'] != '' && $estudio['otro'] != ' '){
                                $ne = new Estudios();  
                                $ne->estudio = $estudio['otro'];
                                $ne->tipo = $estudio['tipo'];
                                $ne->save();
                                
                                if($ne){
                                    if($estudio['id'] == ""){
                                        $nuevo = new PuestoEstudio();  
                                    } else {
                                        $nuevo =  PuestoEstudio::find()->where(['id'=>$estudio['id']])->one(); 
                                    }
                                    $nuevo->id_puesto = $model->id;
                                    $nuevo->id_tipo = $estudio['tipo'];
                                    $nuevo->id_estudio = $ne->id;
                                    $nuevo->fecha_apartir = $estudio['fecha_apartir'];
                                    $nuevo->periodicidad = $estudio['periodicidad'];

                                    if(isset($estudio['status']) && $estudio['status'] != '' && $estudio['status'] != null && $estudio['status'] != ''){
                                        $nuevo->id_status = $estudio['status'];
                                    }else {
                                        $nuevo->id_status = 1;
                                    }

                                    $nuevo->save();
                                    if($nuevo){
                                        array_push($id_estudios, $nuevo->id);
                                    }
                                }
                                
                            }
                        } else{
                            if($estudio['id'] == ""){
                                $nuevo = new PuestoEstudio();  
                            } else {
                                $nuevo =  PuestoEstudio::find()->where(['id'=>$estudio['id']])->one(); 
                            }
                            $nuevo->id_puesto = $model->id;
                            $nuevo->id_estudio = $estudio['estudio'];
                            $nuevo->periodicidad = $estudio['periodicidad'];
                            $nuevo->fecha_apartir = $estudio['fecha_apartir'];
                            $nuevo->id_tipo = $estudio['tipo'];

                            if(isset($estudio['status']) && $estudio['status'] != '' && $estudio['status'] != null && $estudio['status'] != ''){
                                $nuevo->id_status = $estudio['status'];
                            }else {
                                $nuevo->id_status = 1;
                            }

                            $nuevo->save();
    
                            if(isset($nuevo)){
                                array_push($id_estudios, $nuevo->id);
                            }
                        }
                    }

                }
            }

            $deletes = PuestoEstudio::find()->where(['id_puesto'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }
        }


        //dd($model->aux_ni);
        $id_ni = [];
        if(isset($model->aux_ni) && $model->aux_ni != null && $model->aux_ni != ''){
            foreach($model->aux_ni as $key=>$estudio){
                if(true){
                   
                    if($estudio['id_requisito'] != null && $estudio['id_requisito'] != '' && $estudio['id_requisito'] != ' '){

                        if($estudio['id'] == ""){
                            $nuevo = new Nirequisitos();  
                        } else {
                            $nuevo =  Nirequisitos::findOne($estudio['id']); 
                        }

                        $tipo_requisito = explode('_', $estudio['id_requisito']);
                            
                        $nuevo->id_puesto = $model->id;
                        $nuevo->tipo_doc_examen = $tipo_requisito[0];
                        $nuevo->id_requisito = $tipo_requisito[1];
                        $nuevo->periodicidad = $estudio['periodicidad'];
                        $nuevo->fecha_inicio = $estudio['fecha_inicio'];
                
                        if(isset($estudio['status']) && $estudio['status'] != '' && $estudio['status'] != null && $estudio['status'] != ''){
                            $nuevo->status = $estudio['status'];
                        }else {
                            $nuevo->status = 1;
                        }

                        $nuevo->save();
    
                        if(isset($nuevo)){
                            array_push($id_ni, $nuevo->id);
                        }
                    }

                }
            }
        }
        $deletes = Nirequisitos::find()->where(['id_puesto'=>$model->id])->andWhere(['not in','id',$id_ni])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

        if($model->trabajadores){
            foreach($model->trabajadores as $key => $trabajador){
    
                $id_estudios = [];
                if($trabajador->id_puesto != null && $trabajador->id_puesto != "" && $trabajador->id_puesto != " "){
                    
                    $puesto = Puestostrabajo::find()->where(['id'=>$trabajador->id_puesto])->one();
                    
                    //CHECAMOS LOS REQUISITOS DEL PUESTO-------------------------------------------
                    //dd($model->pestudiosactivos);
                    if($model->pestudiosactivos){
                        foreach($model->pestudiosactivos as $key=>$requisito){

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
                    //CHECAMOS LOS REQUISITOS DEL PUESTO-------------------------------------------


                    //CHECAMOS LOS REQUISITOS MINIMOS-------------------------------------------
                    $empresa = $model->empresa;
                    if($empresa){
                        foreach($empresa->requisitosactivos as $key2=>$requisito){
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
                    }
                    //CHECAMOS LOS REQUISITOS MINIMOS-------------------------------------------
                    
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

    private function guardarParametros($model){
        
        $id_parametros = [];
       
        if(isset($model->aux_psicologico) && $model->aux_psicologico != null && $model->aux_psicologico != '' && $model->aux_psicologico != ' '){
            foreach($model->aux_psicologico as $key=>$psicologico){
                if($psicologico['parametro'] != null && $psicologico['parametro'] != '' && $psicologico['parametro'] != ' '){
                    
                    if($psicologico['parametro'] == 0){
                        if($psicologico['otroparametro'] != null && $psicologico['otroparametro'] != '' && $psicologico['otroparametro'] != ' '){
                            $parametro = new Parametros();
                            $parametro->nombre = $psicologico['otroparametro'];
                            $parametro->save();
                        }
                    } else{
                        $parametro = Parametros::findOne($psicologico['parametro']);
                    }
    
                    //dd($parametro);
    
                    if(isset($parametro)){
                        if($psicologico['id'] == ""){
                            $nuevo = new Puestoparametro();  
                        } else {
                            $nuevo =  Puestoparametro::find()->where(['id'=>$parametro['id']])->one(); 
                        }
                        $nuevo->id_puesto = $model->id;
                        $nuevo->id_parametro = $parametro->id;
                        $nuevo->valor = $psicologico['valor'];
                        $nuevo->save();
    
                        if($nuevo){
                            array_push($id_parametros, $nuevo->id);
                        }
                    }
                     
                }
                
            }
    
            //Borramos todos aquellos parametros que ya no estén activos
            $deletes = Puestoparametro::find()->where(['id_puesto'=>$model->id])->andWhere(['not in','id',$id_parametros])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }
    

        }
        
        return null;
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

    /**
     * Deletes an existing PuestosTrabajo model.
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
     * Finds the PuestosTrabajo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PuestosTrabajo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Puestostrabajo::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}