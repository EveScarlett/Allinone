<?php

namespace app\controllers;

use Yii;
use app\models\Cuestionario;
use app\models\Areascuestionario;
use app\models\CuestionarioSearch;
use app\models\DetalleCuestionario;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Pacientes;
use app\models\Preguntas;
use app\models\TipoCuestionario;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


use kartik\mpdf\Pdf;

use app\models\NivelOrganizacional1;

use app\models\Hccohc;
use app\models\Poes;
use app\models\ProgramaTrabajador;
use app\models\Consultas;

require_once('funciones.php');

/**
 * AntropometricaController implements the CRUD actions for Cuestionario model.
 */
class AntropometricaController extends Controller
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
     * Lists all Cuestionario models.
     *
     * @return string
     */
    public function actionIndex($tipo)
    {
        //dd($tipo);
        $searchModel = new CuestionarioSearch();
        $searchModel->id_tipo_cuestionario = $tipo;
        $dataProvider = $searchModel->search($this->request->queryParams);

        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo'=>$tipo
        ]);
    }

    /**
     * Displays a single Cuestionario model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('evaluacion_antropometrica-ver')) {
            throw new ForbiddenHttpException("No tiene los permisos suficientes para acceder a este sitio!");
        }

        $model = $this->findModel($id);

        $this->getAgeupdated($model,$model->fecha_cuestionario,$model->id_paciente);

        $m_pacientes = Pacientes::findOne(['id' => $model->id_paciente]);
        $m_detalle = DetalleCuestionario::findAll(['id_cuestionario' => $model->id, 'status' => 1]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'm_pacientes' => $m_pacientes,
            'm_detalle' => $m_detalle
        ]);
    }

    /**
     * Creates a new Cuestionario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

        $model = new Cuestionario();
        $m_trabajadores = new Trabajadores();
        $m_detalle = new DetalleCuestionario();
        $m_preguntas = Preguntas::find()->where(['id_tipo_cuestionario' => 4, 'status' => 1])->all();

        $model->id_form = 1;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

       //Seleccionar las empresas que puede ver el usuario
       $empresas = explode(',', Yii::$app->user->identity->empresas_select);
       if(Yii::$app->user->identity->empresa_all != 1) {
           $empresas = Empresas::find()->select(['id', 'razon', 'comercial', 'status'])->where(['in','id',$empresas])->orderBy('comercial')->all();
       } else{
           $empresas = Empresas::find()->select(['id', 'razon', 'comercial', 'status'])->orderBy('comercial')->all();
       }

       $ids_empresas = [];
       foreach($empresas as $item){
           if(!in_array($item->id, $ids_empresas)){
               array_push($ids_empresas, $item->id);
           }
       }

       $model->id_medico = Yii::$app->user->identity->id;
        
        //------------------------------------------------
        $m_empresas = $empresas;
        $m_medicos = Usuarios::find()->where(["in",'id_empresa',$ids_empresas])->orderBy(['name' => SORT_ASC])->all();

        //dd($m_medicos);
        if (Yii::$app->user->identity->rol != 2) {
            $model->scenario = "no_medico";
        }
        
        if ($model->load($this->request->post()) && $m_detalle->load($this->request->post()) && $m_trabajadores->load($this->request->post())) {
            $errors = null;
            $transactions = Yii::$app->db->beginTransaction();

            //dd($m_trabajadores);
            if (isset($m_detalle->_preguntas) && $m_detalle->_preguntas != null) {
                try {
                    $bitacora = bitacora('crear');

                    if ($bitacora) {
                        $model->nombre_empresa = Trabajadores::getCompany($model->id_paciente);

                        $model->id_bitacora = $bitacora->id;
                        $model->id_area = $m_trabajadores->id_area;
                        $model->id_puesto = $m_trabajadores->id_puesto;
                        $model->sexo = intval($m_trabajadores->sexo);
                        $model->fecha_nacimiento = $m_trabajadores->fecha_nacimiento;
                        $model->edad = intval($m_trabajadores->edad);
                        $model->id_tipo_cuestionario = 4;
                        $model->fecha_cuestionario = date('Y-m-d H:i:s');
                        $model->status = 1;

                        if ($model->save()) {
                            //dd($model);
                            foreach ($m_detalle->_preguntas as $key_pregunta => $areas) {
                                foreach ($areas as $key_respuesta => $respuesta) {
                                    $resp_ = insertRespuesta($model->id, $key_pregunta, $key_respuesta, $respuesta);
                                    
                                    if (!$resp_) {
                                        $errors = "(4): Las respuestas no fueron guardadas! no se guardo el cuestionario.";
                                        break 2;
                                    }
                                }
                            }
                        } else {
                            //dd($model);
                            $errors = "(3): No se guardo el cuestionario!!";
                        }
                    } else {
                        $errors = "(2): No se registro en la bitacora";
                    }
                } catch (\Throwable $th) {
                    $errors = str_replace("'", '"',explode("\n", $th->getMessage())[0]);
                }
            } else {
                $errors = "(1): No se recibieron los datos necesarios";
            }

            if ($errors !== null) {
                $transactions->rollBack();

                return $this->render('create', [
                    'model' => $model,
                    'm_trabajadores' => $m_trabajadores,
                    'm_empresas' => $m_empresas,
                    'm_preguntas' => $m_preguntas,
                    'm_detalle' => $m_detalle,
                    'm_medicos' => $m_medicos,
                    'message' => "Error " . $errors,
                    'type' => "error"
                ]);
            } else {
                $transactions->commit();

                $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,$model->id_paciente,'nombre_empresa','nombre_medico','nombre','apellidos');

                try {
                    $this->cumplimientosTrabajador($model->id_paciente);
                } catch (\Throwable $th) {
                    //throw $th;
                }

                return $this->redirect(['create', 'message' => "Se guardo correctamente!!", 'type' => "success"]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'm_trabajadores' => $m_trabajadores,
            'm_empresas' => $m_empresas,
            'm_preguntas' => $m_preguntas,
            'm_detalle' => $m_detalle,
            'm_medicos' => $m_medicos
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

    public function actionPdf($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Cuestionario::findOne($id);
        $model->firmado = $firmado;

        $this->getAgeupdated($model,$model->fecha_cuestionario,$model->id_paciente);

        $infofooter = 'Email: comercial@medicalfil.com | Teléfono: (462) 962 0066 | www.medicalfil.com';
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('pdf.php',['model' => $model]),
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
                'SetTitle' => 'EVALUACION ANTROPOMETRICA '.$model->id.'.pdf',
                'SetSubject' => 'EVALUACION ANTROPOMETRICA',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;"></div>',
                'SetHTMLFooter' =>'<div style="width:80%;position: absolute;top: 1090px;left: 30px;">'.$infofooter.'</div><div style="width:20%;position: absolute;top: 1090px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }

    /**
     * Updates an existing Cuestionario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (true) {
            throw new ForbiddenHttpException("No tiene los permisos suficientes para acceder a este sitio!");
        }

        $model = $this->findModel($id);

        $this->getAgeupdated($model,$model->fecha_cuestionario,$model->id_paciente);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,$model->id_paciente,'nombre_empresa','nombre_medico','nombre','apellidos');

            try {
                $this->cumplimientosTrabajador($model->id_paciente);
            } catch (\Throwable $th) {
                //throw $th;
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cuestionario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (true) {
            throw new ForbiddenHttpException("No tiene los permisos suficientes para acceder a este sitio!");
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cuestionario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cuestionario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuestionario::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function getNombreinfo($model,$id_empresa,$id_medico,$id_trabajador,$atributo_empresa,$atributo_medico,$atributo_nombre,$atributo_apellidos){
        if($model){
            $get_empresa = Empresas::findOne($id_empresa);
            $get_medico = Usuarios::findOne($id_medico);
            $get_trabajador = Trabajadores::findOne($id_trabajador);

            if($get_empresa){
                $model[$atributo_empresa] = $get_empresa->comercial;
            }

            if($get_medico){
                $model[$atributo_medico] = $get_medico->name;
            }

            if($get_trabajador){
                $model[$atributo_nombre] = $get_trabajador->nombre;
                $model[$atributo_apellidos] = $get_trabajador->apellidos;
            }

            $model->save(false);
        }
    }


    public function actionRefreshnames()
    {
        $cuestionarios = Cuestionario::find()->all();
        //dd($consultas);
        if($cuestionarios){
            foreach($cuestionarios as $key=>$model){
                $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,$model->id_paciente,'nombre_empresa','nombre_medico','nombre','apellidos');
            }
        }
        return $this->redirect(['index']);
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


    public function getAgeupdated($model,$fecha_doc,$id_trabajador){
        $trabajador = Trabajadores::findOne($id_trabajador);

        if($trabajador && $trabajador->fecha_nacimiento != null && $fecha_doc != null){
            $dateOfBirth = $trabajador->fecha_nacimiento;
            $specificDate = $fecha_doc; // The date you want to calculate the age at

            $ageDetails = $this->calculateAgeAtSpecificDate($dateOfBirth, $specificDate);

            $model->edad = $ageDetails;
            $model->save(false);
        }
    }

    public function calculateAgeAtSpecificDate($dateOfBirth, $specificDate) {
        // Create DateTime objects for the date of birth and the specific date
        $dob = new \DateTime($dateOfBirth);
        $targetDate = new \DateTime($specificDate);

        // Calculate the difference between the two dates
        $interval = $targetDate->diff($dob);

        // Extract years, months, and days from the DateInterval object
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        return $years;
        
        /* return [
        'years' => $years,
        'months' => $months,
        'days' => $days
        ]; */
    }
}
