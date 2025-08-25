<?php

namespace app\controllers;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

use app\models\Insumos;
use app\models\Presentaciones;
use app\models\Unidades;
use app\models\Viasadministracion;
use app\models\InsumosSearch;
use app\models\Empresas;
use app\models\Insumostockmin;
use Yii;

use app\models\NivelOrganizacional1;

/**
 * InsumosController implements the CRUD actions for Insumos model.
 */
class InsumosController extends Controller
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
     * Lists all Insumos models.
     *
     * @return string
     */
    public function actionIndex($tipo)
    {
        $searchModel = new InsumosSearch();
        $searchModel->tipo = $tipo;
        $dataProvider = $searchModel->search($this->request->queryParams);

        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Displays a single Insumos model.
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
     * Creates a new Insumos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($tipo)
    {
        //date_default_timezone_set('America/Mazatlan');
        $model = new Insumos();
        $model->scenario = 'create';
        $model->status = 1;
        $model->tipo = $tipo;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        for($i = 0; $i <= 1; $i++){
            $model->aux_stocks[$i]['id_consultorio'] = '';
        }
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if($model->envia_form == '1'){

                    //dd($model);
                    $model->create_date = date('Y-m-d');

                    if($model->id_presentacion == 0){
                        if($model->otra_presentacion != "" && $model->otra_presentacion != null){
                            $nuevo = new Presentaciones();
                            $nuevo->presentacion = $model->otra_presentacion;
                            $nuevo->save();
                            $model->id_presentacion = $nuevo->id;
                        } else{
                            $model->id_presentacion = null;
                        }
                    }
    
                    if($model->id_unidad == 0){
                        if($model->otra_unidad != "" && $model->otra_unidad != null){
                            $nuevo = new Unidades();
                            $nuevo->unidad = $model->otra_unidad;
                            $nuevo->save();
                            $model->id_unidad = $nuevo->id;
                        } else{
                            $model->id_unidad = null;
                        }
                    }
    
                    if($model->tipo == 1){ //Solo si son medicamentos
                        if($model->via_administracion == 0){
                            if($model->otra_via_administracion != "" && $model->otra_via_administracion != null){
                                $nuevo = new Viasadministracion();
                                $nuevo->via_administracion = $model->otra_via_administracion;
                                $nuevo->save();
                                $model->via_administracion = $nuevo->id;
                            } else{
                                $model->id_via_administracion = null;
                            }
                        }
                    }
                    
    
                    $model->save();

                    $this->saveMultiple($model);


                    $carpeta = 'Medicamentos';
                    if($model->tipo == 2){
                        $carpeta = 'EPP';
                    }
           
                    $archivo = UploadedFile::getInstance($model,'file_foto');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/'.$model->id.'/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                    
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[3].'/'. $nombre_archivo);
                        $model->foto = $nombre_archivo; 
                        $model->save();
                    }     
                        
                    return $this->redirect(['index','tipo'=>$tipo]);

                }  else{

                    if($model->envia_empresa == '1'){
                        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();

                        if($empresa && $empresa->consultorios){
                            for($i = 0; $i <= count($empresa->consultorios); $i++){
                                $model->aux_stocks[$i]['id_consultorio'] = '';
                            }

                            foreach($empresa->consultorios as $key=>$consultorio){
                                $model->aux_stocks[$key]['id_consultorio'] = $consultorio->id;
                            }
                        }
                        return $this->render('create', [
                            'model' => $model,
                            'tipo'=>$tipo
                        ]);
                    }
                }
               
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'tipo'=>$tipo
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


    private function saveMultiple($model){
        if(isset($model->aux_stocks) && $model->aux_stocks != null && $model->aux_stocks != ''){
            $id_stocks = [];
            foreach($model->aux_stocks as $key => $stock){

                if(isset($stock['id']) && $stock['id'] != null && $stock['id'] != ''){
                    $ismin = Insumostockmin::find()->where(['id'=> $stock['id']])->one();
                } else {
                    $ismin = new Insumostockmin();
                    $ismin->id_insumo = $model->id;
                }

                if(isset($stock['id_consultorio']) || isset($stock['stock'])){

                    $ismin->id_consultorio = $stock['id_consultorio'];
                    $ismin->stock = $stock['stock'];

                    if(isset($model->unidades_individuales) && $model->unidades_individuales != null && $model->unidades_individuales != '' && $model->unidades_individuales != ' '){
                        $ismin->stock_unidad = intval($model->unidades_individuales) * intval($stock['stock']);
                    } else{
                        $ismin->stock_unidad = intval($stock['stock']);
                    }  
                    $ismin->tipo_insumo = $model->tipo;
                    $ismin->save();

                    if($ismin){
                        array_push($id_stocks, $ismin->id);
                    }
                }
            }
        }

        $deletes = Insumostockmin::find()->where(['id_insumo'=>$model->id])->andWhere(['not in','id',$id_stocks])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }
    }

    /**
     * Updates an existing Insumos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$tipo)
    {
        $model = $this->findModel($id);
        //date_default_timezone_set('America/Mazatlan');
        $model->scenario = 'update';

        if($model->id_empresa){
            $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
            if($empresa && $empresa->consultorios){
                for($i = 0; $i <= count($empresa->consultorios); $i++){
                    $model->aux_stocks[$i]['id_consultorio'] = '';
                }

                foreach($empresa->consultorios as $key=>$consultorio){
                    $model->aux_stocks[$key]['id_consultorio'] = $consultorio->id;

                    $almacen = Insumostockmin::find()->where(['id_consultorio'=>$consultorio->id])->andWhere(['id_insumo'=>$model->id])->one();
                    if($almacen){
                        $model->aux_stocks[$key]['stock'] = $almacen->stock;
                        $model->aux_stocks[$key]['id'] = $almacen->id;
                    }
                }
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            //dd($model);
            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 2){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                } else {
                    $model->status = $status_anterior;
                }
            
            }

            $model->update_date = date('Y-m-d');
            if(isset($model->id_presentacion) && $model->id_presentacion == 0){
                if($model->otra_presentacion != "" && $model->otra_presentacion != null){
                    $nuevo = new Presentaciones();
                    $nuevo->presentacion = $model->otra_presentacion;
                    $nuevo->save();
                    $model->id_presentacion = $nuevo->id;
                } else{
                    $model->id_presentacion = null;
                }
            }

            if(isset($model->id_unidad) && $model->id_unidad == 0){
                if($model->otra_unidad != "" && $model->otra_unidad != null){
                    $nuevo = new Unidades();
                    $nuevo->unidad = $model->otra_unidad;
                    $nuevo->save();
                    $model->id_unidad = $nuevo->id;
                } else{
                    $model->id_unidad = null;
                }
            }

            if(isset($model->via_administracion) && $model->via_administracion == 0){
                if(isset($model->otra_via_administracion) && $model->otra_via_administracion != "" && $model->otra_via_administracion != null){
                    $nuevo = new Viasadministracion();
                    $nuevo->via_administracion = $model->otra_via_administracion;
                    $nuevo->save();
                    $model->via_administracion = $nuevo->id;
                } else{
                    $model->via_administracion = null;
                }
            }

            $model->save();

            $carpeta = 'Medicamentos';
            if($model->tipo == 2){
                $carpeta = 'EPP';
            }

            $archivo = UploadedFile::getInstance($model,'file_foto');
            $dir0 = __DIR__ . '/../web/resources/Empresas/';
            $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
            $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/';
            $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/'.$model->id.'/';
            $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
            
            $this->actionCarpetas( $directorios);
            
            if($archivo){
                if($model->foto != null || $model->foto != ''){
                    unlink('resources/Empresas/'.$model->id_empresa.'/Medicamentos/'.$model->id.'/'. $model->foto);
                }

                $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                $archivo->saveAs($directorios[3].'/'. $nombre_archivo);
                $model->foto = $nombre_archivo; 
                $model->save();
            }

            $this->saveMultiple($model);

                
            return $this->redirect(['index','tipo'=>$tipo]);
        }

        return $this->render('update', [
            'model' => $model,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Deletes an existing Insumos model.
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
     * Finds the Insumos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Insumos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insumos::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    public function actionChecarinsumo(){
        $id = Yii::$app->request->post('epp');
        $epp = Insumos::find()->where(['id'=>$id])->one();

        $medidas = [];
        $medidascuerpo = [
            '1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS',
            '2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS',
            '3'=>'MEX 26 | US 6 | EUR 32 | INTER S',
            '4'=>'MEX 28 | US 8 | EUR 34 | INTER M',
            '5'=>'MEX 30 | US 10 | EUR 34 | INTER M',
            '6'=>'MEX 32 | US 12 | EUR 36 | INTER L',
            '7'=>'MEX 36 | US 14 | EUR 36 | INTER L',
            '8'=>'MEX 38 | US 16 | EUR 38 | INTER XL',
            '9'=>'MEX 40 | US 18 | EUR 38 | INTER XL',
            '10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL',
            '11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',
        ];
        $medidascabezamano = ['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL'];
        $medidascalzado = ['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13'];
       
        if($epp && isset($epp->parte_corporal)){
            if($epp->parte_corporal == 1 || $epp->parte_corporal == 5){
                $medidas = $medidascabezamano;
            } else if($epp->parte_corporal == 2 || $epp->parte_corporal == 3 || $epp->parte_corporal == 4){
                $medidas = $medidascuerpo;
            } else if($epp->parte_corporal == 6){
                $medidas = $medidascalzado;
            }
            
        }

        return \yii\helpers\Json::encode(['epp' => $epp,'medidas'=>$medidas]);
    }
}