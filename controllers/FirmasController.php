<?php

namespace app\controllers;

use app\models\Firmas;
use app\models\FirmasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Firmaempresa;
use Yii;


use app\models\NivelOrganizacional1;

/**
 * FirmasController implements the CRUD actions for Firmas model.
 */
class FirmasController extends Controller
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
     * Lists all Firmas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FirmasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Firmas model.
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
     * Creates a new Firmas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Firmas();
        $model->scenario = 'create';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $showempresa = true;
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);

        if(Yii::$app->user->identity->empresa_all != 1){
            if(count($empresas) == 1){
                $showempresa = false;
                $model->empresas_select2 = [Yii::$app->user->identity->id_empresa];
            }
        }


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Firmas");
                

                $model->tipo =2;
                    $model->save();
                    
                    $this->guardarEmpresas($model);

                    $archivo = UploadedFile::getInstance($model,'file_firma');
                    $dir_ticket = __DIR__ . '/../web/resources/firmas/';
                    $directorios = ['0'=>$dir_ticket];
                    $this->actionCarpetas( $directorios);

                    if($archivo){
                        $nombre_archivo = 'firma_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                    
                        $model->firma = $nombre_archivo; 
                   
                        $model->fecha_inicio = date('Y-m-d');
                        $model->status = 1;
                        $model->save();
                    } else if((isset($model->nombre) && $model->nombre !="") || (isset($model->cedula) && $model->cedula !="")){
                        $model->fecha_inicio = date('Y-m-d');
                        $model->status = 1;
                        $model->save();
                    }      
                    return $this->redirect(['index']);
                
            }
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
     * Updates an existing Firmas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //dd($model);
        $model->scenario = 'update';

        $empresas_select = [];
        foreach($model->empresas as $key=>$empresa){
            array_push($empresas_select, $empresa->id_empresa);
        }

        if($empresas_select && count($empresas_select)>0){
            $model->empresas_select2 = $empresas_select;
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Firmas");
            //$model->id_pais = $param['id_pais'];
            //$model->id_linea = $param['id_linea'];
                

                $this->guardarEmpresas($model);
                $model->save();
                $archivo = UploadedFile::getInstance($model,'file_firma');
                $dir_ticket = __DIR__ . '/../web/resources/firmas/';
                $directorios = ['0'=>$dir_ticket];
                $this->actionCarpetas( $directorios);
    
                if($archivo){
                    $nombre_archivo = 'firma_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                
                    $model->firma = $nombre_archivo; 
               
                    $model->fecha_inicio = date('Y-m-d');
                    $model->status = 1;
                    $model->save();
                }
                
                return $this->redirect(['index']);

            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function guardarEmpresas($model) {
        //dd($model);
        $id_firmaempresa = [];

        if($model->empresas_select2 > 0){
            
            foreach($model->empresas_select2 as $key=>$id_empresa){
                
                $firmaempresa = Firmaempresa::find()->where(['id_firma'=>$model->id])->andWhere(['id_empresa'=>$id_empresa])->one();
                if(!$firmaempresa){
                    $firmaempresa = new Firmaempresa();
                }
    
                $firmaempresa->id_firma = $model->id;
                $firmaempresa->id_empresa = $id_empresa;
                $firmaempresa->save();
    
                
                if($firmaempresa){
                    array_push($id_firmaempresa, $firmaempresa->id);
                }   
            
            }

        } else {
            $firmaempresa = Firmaempresa::find()->where(['id_firma'=>$model->id])->andWhere(['id_empresa'=>$model->id_empresa])->one();
            if(!$firmaempresa){
                $firmaempresa = new Firmaempresa();
            }

            $firmaempresa->id_firma = $model->id;
            $firmaempresa->id_empresa = $model->id_empresa;
            $firmaempresa->save();

            
            if($firmaempresa){
                array_push($id_firmaempresa, $firmaempresa->id);
            }  
                 
        }
        

        $deletes = Firmaempresa::find()->where(['id_firma'=>$model->id])->andWhere(['not in','id',$id_firmaempresa])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

        $model->empresas_select2 = null;
        return null;
    }

    /**
     * Deletes an existing Firmas model.
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
     * Finds the Firmas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Firmas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Firmas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
}