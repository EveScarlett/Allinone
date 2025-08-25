<?php

namespace app\controllers;

use app\models\Trabajadores;
use app\models\TrabajadorespsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ProgramaTrabajador;
use app\models\Puestostrabajo;
use app\models\Consultas;
use app\models\hccohc;
use app\models\Poes;
use app\models\Cuestionario;

/**
 * ProgramatrabajadorController implements the CRUD actions for Trabajadores model.
 */
class ProgramatrabajadorController extends Controller
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
     * Lists all Trabajadores models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TrabajadorespsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trabajadores model.
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
     * Creates a new Trabajadores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Trabajadores();
        $model->scenario = 'create';

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
     * Updates an existing Trabajadores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if($model){
            $programas = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['status'=>SORT_ASC,'fecha_inicio'=>SORT_DESC])->all();

            if($programas){
                foreach($programas as $key=>$estudio){
                $model->aux_programas[$key]['id_programa'] = $estudio->id_programa;
                $model->aux_programas[$key]['fecha_inicio'] = $estudio->fecha_inicio;
                $model->aux_programas[$key]['fecha_fin'] = $estudio->fecha_fin;
                $model->aux_programas[$key]['conclusion'] = $estudio->conclusion;
                $model->aux_programas[$key]['status'] = $estudio->status;
                $model->aux_programas[$key]['id'] = $estudio->id;
                }
            }
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            //dd($model);

            $this->saveMultiple($model);

            $this->statusPSTrabajador($model->id);
            $this->cumplimientosTrabajador($model->id);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    private function saveMultiple($model){
        //dd($model);
        
        $id_programas = [];
        if(isset($model->aux_programas) && $model->aux_programas != null && $model->aux_programas != ''){
            foreach($model->aux_programas as $key => $data){

                if(isset($data['id']) && $data['id'] != null && $data['id'] != ''){
                    $new = ProgramaTrabajador::findOne($data['id']);
                } else {
                    $new = new ProgramaTrabajador();
                    $new->id_trabajador = $model->id;
                }

                if(isset($data['id_programa'])){

                    $new->id_programa = $data['id_programa'];
                    $new->fecha_inicio = $data['fecha_inicio'];
                    $new->fecha_fin = $data['fecha_fin'];
                    $new->conclusion = $data['conclusion'];
                    $new->status = $data['status'];
                    $new->save();

                    if($new){
                        array_push($id_programas, $new->id);
                    }
                    
                }
            }
        }

        $deletes = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id])->andWhere(['not in','id',$id_programas])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->status_baja = 1;
            $delete->save(false);
        }

    }


    private function statusPSTrabajador($id_trabajador){
        $trabajador = Trabajadores::findOne($id_trabajador);

        if($trabajador){
            $ps = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
            $ps_activos = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['status'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
              
            if($ps){
                $trabajador->ps_status = 1;
                $trabajador->save(false);
            } else {
                $trabajador->ps_status = 2;
                $trabajador->ps_activos = 2;
                $trabajador->save(false);
            }

            if($ps_activos){
                $trabajador->ps_activos = 1;
                $trabajador->save(false);
            } else {
                $trabajador->ps_activos = 2;
                $trabajador->save(false);
            }
        }
        
    }

    /**
     * Deletes an existing Trabajadores model.
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
     * Finds the Trabajadores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Trabajadores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trabajadores::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function cumplimientosTrabajador($id_trabajador){
        //'puesto_cumplimiento' => Yii::t('app', 'Cumplimiento Puesto'),
        //'riesgo_cumplimiento' => Yii::t('app', 'Cumplimiento Riesgo'),
        //'programasalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programa de Salud'),
        //'expediente_cumplimiento' => Yii::t('app', 'Cumplimiento Expediente Medicamento'),

        // 'hc_cumplimiento' => Yii::t('app', 'Cumplimiento HC'),
        // 'poe_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Médicos'),
        // 'cuestionario_cumplimiento' => Yii::t('app', 'Cumplimiento Cuestionario Nórdico'),
        // 'antropometrica_cumplimiento' => Yii::t('app', 'Cumplimiento Medidas Antropométricas'),
        // 'programassalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programas de Salud'),
        // 'porcentaje_cumplimiento' => Yii::t('app', 'Cumplimiento Trabajador'),
        date_default_timezone_set('America/Costa_Rica');
        $hoy = date('Y-m-d');
        $year_before = strtotime($hoy.' -1 year');
        $year_before = date("Y-m-d", $year_before);


        $no_cumplimientos = 9;

        $solo_anio = date('Y');

        $trabajador = Trabajadores::findOne($id_trabajador);

        //dd('hoy: '.$hoy.' | hace 1 año: '.$year_before.' | solo_anio: '.$solo_anio);
          
        if($trabajador){
            // 'puesto_cumplimiento' => Yii::t('app', 'Cumplimiento Puesto'),
            $puesto = Puestostrabajo::find()->where(['id'=>$trabajador->id_puesto])->one();
            if($puesto){
                $trabajador->puesto_cumplimiento = 100;
            } else {
                $trabajador->puesto_cumplimiento = 0;
            }


            // 'riesgo_cumplimiento' => Yii::t('app', 'Cumplimiento Riesgo'),
            if($puesto){
                if($puesto->riesgos){
                    $trabajador->riesgo_cumplimiento = 100;
                } else {
                    $trabajador->riesgo_cumplimiento = 0;
                }
            } else {
                $trabajador->riesgo_cumplimiento = 0;
            }


            // 'riesgohistorico_cumplimiento' => Yii::t('app', 'Cumplimiento  Histórico'),
            $puesto = Puestotrabajohriesgo::find()->where(['id_trabajador'=>$trabajador->id])->orderBy(['fecha_inicio'=>SORT_ASC,'create_date'=>SORT_ASC])->all();
            if($puesto){
                $trabajador->riesgohistorico_cumplimiento = 100;
            } else {
                $trabajador->riesgohistorico_cumplimiento = 0;
            }


            // 'programasalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programa de Salud'),
            $programastrabajador = ProgramaTrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['status'=>1])->all();
            if($programastrabajador){
                $cantidad_ps = count($programastrabajador);
                $sumatoria_ps = 0;
                $porcentaje_ps = 0;

                foreach($programastrabajador as $j=>$programa){
                    $consultas = Consultas::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['tipo'=>7])->andWhere(['id_programa'=>$programa->id_programa])->andWhere(['>=','fecha',$year_before])->all();

                    if($consultas){
                        $sumatoria_ps += 100;
                    }
                }

                if($cantidad_ps > 0){
                    $porcentaje_ps = $sumatoria_ps/$cantidad_ps;
                    $porcentaje_ps = number_format($porcentaje_ps, 2, '.', ',');
                }
                $trabajador->programasalud_cumplimiento = $porcentaje_ps;
            } else {
                $trabajador->programasalud_cumplimiento = 100;
            }


            // 'expediente_cumplimiento' => Yii::t('app', 'Cumplimiento Expediente Médico'),
            //['1'=>'CUMPLE','2'=>'NO CUMPLE','0'=>'PENDIENTE']
            if($trabajador->status_documentos == 1){
                $trabajador->expediente_cumplimiento = 100;
            } else {
                $trabajador->expediente_cumplimiento = 0;
            }


            // 'hc_cumplimiento' => Yii::t('app', 'Cumplimiento HC'),
            $hc = hccohc::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['in','status',[1,2]])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->andWhere(['>=','fecha',$year_before])->one();
            if($hc){
                $trabajador->hc_cumplimiento = 100;
            } else {
                $trabajador->hc_cumplimiento = 0;
            }


            // 'poe_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Médicos'),
            $poes = Poes::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['<>','status',2])->andWhere(['>=','anio',$solo_anio])->orderBy(['id'=>SORT_DESC])->one();
            if($poes){
                //->andWhere(['status_entrega'=>1])
                if($poes->estudios){
                    $cantidad_estudios = count($poes->estudios);
                    $cantidad_estudios += 1;
                    $sumatoria_estudios = 0;
                    $porcentaje_estudios = 0;

                    foreach($poes->estudios as $j=>$poeestudio){
                        if($poeestudio->obligatorio == 1){
                            if((isset($poeestudio->condicion) && $poeestudio->condicion != null && $poeestudio->condicion != '') && (isset($poeestudio->evolucion) && $poeestudio->evolucion != null && $poeestudio->evolucion != '') && ($poeestudio->evidencia != null || $poeestudio->evidencia2 != null || $poeestudio->evidencia3 != null)){
                                $sumatoria_estudios += 100;
                            }
                        } else {
                            $sumatoria_estudios += 100;
                        }
                    }

                    if($poes->status_entrega == 1){
                        $sumatoria_estudios += 100;
                    }


                    if($cantidad_estudios > 0){
                        $porcentaje_estudios = $sumatoria_estudios/$cantidad_estudios;
                        $porcentaje_estudios = number_format($porcentaje_estudios, 2, '.', ',');
                    }

                    $trabajador->poe_cumplimiento = $porcentaje_estudios;

                } else {
                    $trabajador->poe_cumplimiento = 0;
                }
            } else {
                $trabajador->poe_cumplimiento = 0;
            }


            // 'cuestionario_cumplimiento' => Yii::t('app', 'Cumplimiento Cuestionario Nórdico'),
            $cuestionario = Cuestionario::find()->where(['id_paciente'=>$id_trabajador])->andWhere(['id_tipo_cuestionario'=>1])->andWhere(['>=','fecha_cuestionario',$year_before])->one();
            if($cuestionario){
                $trabajador->cuestionario_cumplimiento = 100;
            } else {
                $trabajador->cuestionario_cumplimiento = 0;
            }


            // 'antropometrica_cumplimiento' => Yii::t('app', 'Cumplimiento Medidas Antropométricas'),
            $antropometrica = Cuestionario::find()->where(['id_paciente'=>$id_trabajador])->andWhere(['id_tipo_cuestionario'=>4])->one();
            if($antropometrica){
                $trabajador->antropometrica_cumplimiento = 100;
            } else {
                $trabajador->antropometrica_cumplimiento = 0;
            }


            $sumatoria = $trabajador->puesto_cumplimiento + $trabajador->riesgo_cumplimiento +$trabajador->riesgohistorico_cumplimiento+ $trabajador->programasalud_cumplimiento + $trabajador->expediente_cumplimiento + $trabajador->hc_cumplimiento + $trabajador->poe_cumplimiento +  $trabajador->cuestionario_cumplimiento + $trabajador->antropometrica_cumplimiento;
            $porcentaje = ($sumatoria/$no_cumplimientos);
            $porcentaje = number_format($porcentaje, 2, '.', ',');

            $status_cumplimiento = 0;
            $trabajador->porcentaje_cumplimiento = $porcentaje;

            if($porcentaje >= 50){
                $status_cumplimiento = 1;
            } else {
                $status_cumplimiento = 0;
            }

            $trabajador->status_cumplimiento = $status_cumplimiento;
            $trabajador->refreshupdated_5 = 1;
            $trabajador->save(false);
            //dd('$trabajador',$trabajador,'$hc',$hc,'$poes',$poes,'$cuestionario',$cuestionario,'$antropometrica',$antropometrica,'$sumatoria',$sumatoria,'$porcentaje',$porcentaje,'$status_cumplimiento',$status_cumplimiento);
        }

    }
}
