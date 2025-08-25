<?php

namespace app\controllers;

use app\models\TipoServicios;
use app\models\Servicios;
use app\models\TipoServiciosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * TipoServiciosController implements the CRUD actions for TipoServicios model.
 */
class TiposerviciosController extends Controller
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
     * Lists all TipoServicios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TipoServiciosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoServicios model.
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
     * Creates a new TipoServicios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TipoServicios();
        $model->status = 1;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $colores = ['#864AF9','#FF6868','#37B5B6','#4942E4','#96EFFF','#9400FF','#37B5B6','#FF9800','#F29727','#F24C3D','#05BFDB','#6DA9E4','#A084E8','#B9EDDD','#D61355','#00D7FF','#876445','#65B741','#9ADE7B','#1F8A70','#FDE767','#F4F27E','#E9B824','#6D2932','#7E6363','#B99470','#FE7A36','#EA906C','#FF6C22','#E25E3E','#EF4040','#BE3144','#FF6666','#FF9BD2','#F875AA','#FF52A2','#6C22A6','#DC84F3','#BEADFA','#3652AD','#596FB7','#A0BFE0','#3D3B40','#0F0F0F','#B4B4B3','#9BA4B5','#332C39'];
                $model->logo =  $colores[rand(1,(count($colores)-1))];
                
                $ultimo = TipoServicios::find()->orderBy(['orden'=>SORT_DESC])->one();
                
                if($ultimo){
                    $model->orden = $ultimo->orden + 1;
                } else{
                    $model->orden = 1;
                }
                $model->save();

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
     * Updates an existing TipoServicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
            if(!isset($model->logo) && $model->logo == null && $model->logo == ''){
                $model->logo =  $colores[rand(1,(count($colores)-1))];
                $model->save();
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TipoServicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSort($id)
    {
        $model = $this->findModel($id);

        if ($model->load($this->request->post())) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("listadoestudios");

            $clean = $param;
            $clean = str_replace('["', "", $clean);
            $clean = str_replace('"]', "", $clean);
            $clean = str_replace('","', ",", $clean);
            $clean = str_replace('",', ",", $clean);
            $clean = str_replace(',"', ",", $clean);
            $clean = explode(",", $clean);
           
            if($param){
                $index = 1;
                foreach($clean as $key=>$estudio){
                    $ordenar = Servicios::find()->where(['nombre'=>$estudio])->one();
                    if($ordenar){
                        $ordenar->orden = $index;
                        $ordenar->save();
                    }
                    $index++;
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('sort', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing TipoServicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSortcategoria()
    {
    
        if ($this->request->isPost) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("listadoestudios");

            $clean = $param;
            $clean = str_replace('["', "", $clean);
            $clean = str_replace('"]', "", $clean);
            $clean = str_replace('","', ",", $clean);
            $clean = str_replace('",', ",", $clean);
            $clean = str_replace(',"', ",", $clean);
            $clean = explode(",", $clean);
           
            if($param){
                $index = 1;
                foreach($clean as $key=>$estudio){
                    $ordenar = TipoServicios::find()->where(['nombre'=>$estudio])->one();
                    if($ordenar){
                        $ordenar->orden = $index;
                        $ordenar->save();
                    }
                    $index++;
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('sortcategoria', [
           
        ]);
    }

    private function ordenarEstudios($model){
        $categorias_ordenado = ArrayHelper::map(TipoServicios::find()->orderBy(['orden'=>SORT_ASC])->all(), 'id', 'nombre');
        $estudios_ordenado = [];
        foreach ($categorias_ordenado as $key=>$cat){
            $init_estudiosord = ArrayHelper::map(Servicios::find()->where(['id_tiposervicio'=>$key])->orderBy(['orden'=>SORT_ASC])->all(), 'id', 'nombre');
            $estudios_ordenado = $estudios_ordenado+ $init_estudiosord;
        }

        $index = 1;
        foreach ($estudios_ordenado as $key=>$ordenado){
            $estudios_order = Poeestudio::find()->where(['id_poe'=>$model->id])->andWhere(['id_estudio'=>$key])->all();
            if($estudios_order){
                foreach ($estudios_order as $key=>$est){
                    $est->orden = $index;
                    $est->save();

                    $index++;
                }
            }
        }

    }


    /**
     * Deletes an existing TipoServicios model.
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
     * Finds the TipoServicios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TipoServicios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TipoServicios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
