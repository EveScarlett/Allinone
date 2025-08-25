<?php

namespace app\controllers;

use app\models\Mantenimientos;
use app\models\MantenimientosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;

use app\models\Maquinaria;
use app\models\Empresas;
use app\models\Mantenimientoactividad;
use app\models\Mantenimientocomponentes;
use app\models\Paises;
use app\models\Paisempresa;

use app\models\NivelOrganizacional1;

use Yii;
/**
 * MantenimientosController implements the CRUD actions for Mantenimientos model.
 */
class MantenimientosController extends Controller
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
     * Lists all Mantenimientos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MantenimientosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mantenimientos model.
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
     * Creates a new Mantenimientos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = new Mantenimientos();
        $model->status = 1;
        $model->envia_form = 0;
        $model->scenario = 'create';
        $model->fecha = date('Y-m-d');
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        for($i = 0; $i <= 5; $i++){
            $model->aux_actividades[$i]['actividad'] = null;
            $model->aux_actividades[$i]['status'] = null;
            $model->aux_actividades[$i]['id'] = null;

            $model->aux_componentes[$i]['componente'] = null;
            $model->aux_componentes[$i]['numero_serie'] = null;
            $model->aux_componentes[$i]['descripcion'] = null;
            $model->aux_componentes[$i]['status'] = null;
            $model->aux_componentes[$i]['id'] = null;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {

                $request = Yii::$app->request;
                $param = $request->getBodyParam("Mantenimientos");
                if($model->envia_form == '1'){

                    $model->create_date = date('Y-m-d');
                    $model->create_user = Yii::$app->user->identity->id;
                    $model->update_date = date('Y-m-d');
                    $model->update_user = Yii::$app->user->identity->id;
                    $model->save();

                    //Guardar la firma del trabajador---------------------------------
                    if(isset($model->firma1) && $model->firma1 != 'data:,'){
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/');
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/';
                        $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/';
                        $dir5 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/'.$model->id.'/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4,'5'=>$dir5];
                        $this->actionCarpetas($directorios);
                        $nombre_firma = $this->saveFirma($model->firma1,$model);
                        $model->ruta_firma1 = $nombre_firma;
                        $model->save();
                    }
                    
                    if($model){
                        $this->saveMultiple($model);
                    }
                    return $this->redirect(['index']);
                } else {

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
     * Updates an existing Mantenimientos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        for($i = 0; $i <= 5; $i++){
            $model->aux_actividades[$i]['actividad'] = null;
            $model->aux_actividades[$i]['status'] = null;
            $model->aux_actividades[$i]['id'] = null;

            $model->aux_componentes[$i]['componente'] = null;
            $model->aux_componentes[$i]['numero_serie'] = null;
            $model->aux_componentes[$i]['descripcion'] = null;
            $model->aux_componentes[$i]['status'] = null;
            $model->aux_componentes[$i]['id'] = null;
        }

        foreach($model->actividades as $key=>$actividad){
            $model->aux_actividades[$key]['actividad'] = $actividad->actividad;
            $model->aux_actividades[$key]['status'] = $actividad->status;

            if($actividad->foto != '' && $actividad->foto != null){
                $model->aux_actividades[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/'.$model->id.'/'.$actividad->foto;
            }else{
                $model->aux_actividades[$key]['doc'] = null;
            }
            
            $model->aux_actividades[$key]['id'] = $actividad->id;
        }

        foreach($model->componentes as $key=>$componente){
            $model->aux_componentes[$key]['componente'] = $componente->componente;
            $model->aux_componentes[$key]['numero_serie'] = $componente->numero_serie;
            $model->aux_componentes[$key]['descripcion'] = $componente->descripcion;
            $model->aux_componentes[$key]['status'] = $componente->status;
            
            $model->aux_componentes[$key]['id'] = $componente->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
                $param = $request->getBodyParam("Mantenimientos");
                if($model->envia_form == '1'){

                    $model->update_date = date('Y-m-d');
                    $model->update_user = Yii::$app->user->identity->id;
                    $model->save();

                    //Guardar la firma del trabajador---------------------------------
                    if(isset($model->firma1) && $model->firma1 != 'data:,'){
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/');
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/';
                        $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/';
                        $dir5 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/'.$model->id.'/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4,'5'=>$dir5];
                        $this->actionCarpetas($directorios);
                        $nombre_firma = $this->saveFirma($model->firma1,$model);
                        $model->ruta_firma1 = $nombre_firma;
                        $model->save();
                    }
                    
                    if($model){
                        $this->saveMultiple($model);
                    }
                    return $this->redirect(['index']);
                } else {

                }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mantenimientos model.
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
     * Finds the Mantenimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Mantenimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mantenimientos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionInfoempresa(){
        $id = Yii::$app->request->post('id');
        $empresa = Empresas::find()->where(['id'=>$id])->one();
       
        $fecha = date('Ymd');

        $maquinas = [];

        if($empresa){
            $maquinas = $empresa->maquinasactivas;

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
        
        return \yii\helpers\Json::encode(['empresa' => $empresa,'maquinas'=>$maquinas,'paises'=>$paises]);
    }


    public function actionInfomaquina(){
        
        $id = Yii::$app->request->post('id');
        $maquina = Maquinaria::findOne(['id'=>$id]);

        return \yii\helpers\Json::encode(['maquina' => $maquina]);
    }

    protected function actionCarpetas(&$dir1) {
        foreach($dir1 as $key=>$dir){
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                umask(0);
                chmod($dir, 0777);
            } else {
                umask(0);
                chmod($dir, 0777);
            }
        }
    }

    protected function saveFirma($firma,$model) {
        
        $img = $firma;
        $img = str_replace('data:image/png;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  'firmamanten_'.date('Y_m_d_H_i_s_').uniqid() . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
    }


    private function saveMultiple($model){
        date_default_timezone_set('America/Costa_Rica');
        //dd($model);
        
        $id_actividades = [];
        if(isset($model->aux_actividades) && $model->aux_actividades != null && $model->aux_actividades != ''){
            foreach($model->aux_actividades as $key => $actividad){

                if(isset($actividad['id']) && $actividad['id'] != null && $actividad['id'] != ''){
                    $ma = Mantenimientoactividad::find()->where(['id'=> $actividad['id']])->one();
                } else {
                    $ma = new Mantenimientoactividad();
                    $ma->id_mantenimiento = $model->id;
                }

                if(isset($actividad['actividad']) && $actividad['actividad'] != null && $actividad['actividad'] != '' && $actividad['actividad'] != ' '){
                   
                    $ma->actividad = $actividad['actividad'];
                    $ma->status = $actividad['status'];
                    $ma->create_date = date('Y-m-d H:i:s');
                    $ma->create_user = Yii::$app->user->identity->id;

                    $ma->save();

                    if('aux_actividades' . '[' . $key . '][' . 'foto' . ']' != ""){
           
                        $archivo = 'aux_actividades' . '[' . $key . '][' . 'foto' . ']';
                        $save_archivo = UploadedFile::getInstance($model, $archivo);
                        if (!is_null($save_archivo)) {

                            $dir0 = __DIR__ . '/../web/resources/Empresas/';
                            $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                            $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/';
                            $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/';
                            $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/';
                            $dir5 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/'.$model->id.'/';
                            $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4,'5'=>$dir5];
                            $this->actionCarpetas( $directorios);

                            if($ma->foto != null || $ma->foto != ''){
                                unlink('resources/Empresas/'.$model->id_empresa.'/Maquinarias/'.$model->id_maquina.'/Mantenimientos/'.$model->id.'/'.$ma->foto);
                            }

                            $ruta_archivo = $ma->id .'_'.date('YmdHis'). '.' . $save_archivo->extension;
                            $save_archivo->saveAs($directorios[5] . '/' . $ruta_archivo);
                            $ma->foto= $ruta_archivo;
                            
                        }
                        $ma->save();
                    }

                    if($ma){
                        array_push($id_actividades, $ma->id);
                    }
                        
                    
                }
            }
        }

        $deletes = Mantenimientoactividad::find()->where(['id_mantenimiento'=>$model->id])->andWhere(['not in','id',$id_actividades])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }


        $id_componentes = [];
        if(isset($model->aux_componentes) && $model->aux_componentes != null && $model->aux_componentes != ''){
            foreach($model->aux_componentes as $key => $componente){

                if(isset($componente['id']) && $componente['id'] != null && $componente['id'] != ''){
                    $mc = Mantenimientocomponentes::find()->where(['id'=> $componente['id']])->one();
                } else {
                    $mc = new Mantenimientocomponentes();
                    $mc->id_mantenimiento = $model->id;
                }

                if(isset($componente['componente']) && $componente['componente'] != null && $componente['componente'] != '' && $componente['componente'] != ' '){
                   
                    $mc->componente = $componente['componente'];
                    $mc->numero_serie = $componente['numero_serie'];
                    $mc->descripcion = $componente['descripcion'];
                    $mc->status = $componente['status'];
                    $mc->create_date = date('Y-m-d H:i:s');
                    $mc->create_user = Yii::$app->user->identity->id;

                    $mc->save();

                    if($mc){
                        array_push($id_componentes, $mc->id);
                    }
                        
                }
            }
        }

        $deletes = Mantenimientocomponentes::find()->where(['id_mantenimiento'=>$model->id])->andWhere(['not in','id',$id_componentes])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }


        $maquina = Maquinaria::findOne($model->id_maquina);
        if($maquina){
            
            $ultimo_mantenimiento = Mantenimientos::find()->where(['id_maquina'=>$maquina->id])->andWhere(['status'=>1])->orderBy(['fecha'=>SORT_DESC,'id'=>SORT_DESC])->one();
                        
            if($ultimo_mantenimiento){
                $maquina->fecha_mantenimiento = $ultimo_mantenimiento->fecha;
                $maquina->save(false);
            }

            $fechahoy = date('Y-m-d');
            $ultimo_mantenimiento = Mantenimientos::find()->where(['id_maquina'=>$maquina->id])->andWhere(['status'=>1])->orderBy(['proximo_mantenimiento'=>SORT_DESC,'id'=>SORT_DESC])->one();
                            
            if($ultimo_mantenimiento){
                $fecha_prox = $ultimo_mantenimiento->proximo_mantenimiento;
                if($fecha_prox >=  $fechahoy){
                    $maquina->proximo_mantenimiento = $fecha_prox;
                    $maquina->save(false);
                }
                            
            }

        }
        

    }


    public function actionPdf($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Mantenimientos::findOne($id);
        $model->firmado = $firmado;

        $pdffile = 'pdf.php';
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($pdffile,['model' => $model]),
            'options' => [
                // any mpdf options you wish to set
            ],
            'marginTop'=>'20',
            'marginHeader'=>'0',
            'marginLeft'=>'0',
            'marginRight'=>'0',
            'marginBottom'=>'27',
            'marginFooter'=>'0',
          /*   'cssInline' => $css, */
            'cssFile' => 'css/pdf.css',
            'methods' => [
                'SetTitle' => 'FICHA DE MANTENIMIENTO '.$model->clave.'.pdf',
                'SetSubject' => 'FICHA DE MANTENIMIENTO',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }
}