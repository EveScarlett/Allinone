<?php

namespace app\controllers;

use app\models\Cuestionario;
use app\models\CuestionarioSearch;
use app\models\DetalleCuestionario;
use app\models\Empresas;
use app\models\Hccohc;
use app\models\Usuarios;
use app\models\Trabajadores;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use kartik\mpdf\Pdf;


use app\models\Paises;
use app\models\Paisempresa;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

use app\models\Poes;
use app\models\ProgramaTrabajador;
use app\models\Consultas;

use Yii;

require_once('funciones.php');

/**
 * CuestionarioController implements the CRUD actions for Cuestionario model.
 */
class CuestionarioController extends Controller
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
        $model = $this->findModel($id);

        $this->getAgeupdated($model,$model->fecha_cuestionario,$model->id_paciente);

        $m_detalle = DetalleCuestionario::findAll(['id_cuestionario' => $model->id, 'status' => 1]);

        return $this->render('view', [
            'model' => $this->findModel($id),
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

        $imgCanvas = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAADICAYAAADGFbfiAAAAAXNSR0IArs4c6QAABx1JREFUeF7t1bENACAMBDGy/9BBYgOuNjVprJdudnePR4AAAQIEPgVGQD7FfCdAgACBJyAghkCAAAECSUBAEpsjAgQIEBAQGyBAgACBJCAgic0RAQIECAiIDRAgQIBAEhCQxOaIAAECBATEBggQIEAgCQhIYnNEgAABAgJiAwQIECCQBAQksTkiQIAAAQGxAQIECBBIAgKS2BwRIECAgIDYAAECBAgkAQFJbI4IECBAQEBsgAABAgSSgIAkNkcECBAgICA2QIAAAQJJQEASmyMCBAgQEBAbIECAAIEkICCJzREBAgQICIgNECBAgEASEJDE5ogAAQIEBMQGCBAgQCAJCEhic0SAAAECAmIDBAgQIJAEBCSxOSJAgAABAbEBAgQIEEgCApLYHBEgQICAgNgAAQIECCQBAUlsjggQIEBAQGyAAAECBJKAgCQ2RwQIECAgIDZAgAABAklAQBKbIwIECBAQEBsgQIAAgSQgIInNEQECBAgIiA0QIECAQBIQkMTmiAABAgQExAYIECBAIAkISGJzRIAAAQICYgMECBAgkAQEJLE5IkCAAAEBsQECBAgQSAICktgcESBAgICA2AABAgQIJAEBSWyOCBAgQEBAbIAAAQIEkoCAJDZHBAgQICAgNkCAAAECSUBAEpsjAgQIEBAQGyBAgACBJCAgic0RAQIECAiIDRAgQIBAEhCQxOaIAAECBATEBggQIEAgCQhIYnNEgAABAgJiAwQIECCQBAQksTkiQIAAAQGxAQIECBBIAgKS2BwRIECAgIDYAAECBAgkAQFJbI4IECBAQEBsgAABAgSSgIAkNkcECBAgICA2QIAAAQJJQEASmyMCBAgQEBAbIECAAIEkICCJzREBAgQICIgNECBAgEASEJDE5ogAAQIEBMQGCBAgQCAJCEhic0SAAAECAmIDBAgQIJAEBCSxOSJAgAABAbEBAgQIEEgCApLYHBEgQICAgNgAAQIECCQBAUlsjggQIEBAQGyAAAECBJKAgCQ2RwQIECAgIDZAgAABAklAQBKbIwIECBAQEBsgQIAAgSQgIInNEQECBAgIiA0QIECAQBIQkMTmiAABAgQExAYIECBAIAkISGJzRIAAAQICYgMECBAgkAQEJLE5IkCAAAEBsQECBAgQSAICktgcESBAgICA2AABAgQIJAEBSWyOCBAgQEBAbIAAAQIEkoCAJDZHBAgQICAgNkCAAAECSUBAEpsjAgQIEBAQGyBAgACBJCAgic0RAQIECAiIDRAgQIBAEhCQxOaIAAECBATEBggQIEAgCQhIYnNEgAABAgJiAwQIECCQBAQksTkiQIAAAQGxAQIECBBIAgKS2BwRIECAgIDYAAECBAgkAQFJbI4IECBAQEBsgAABAgSSgIAkNkcECBAgICA2QIAAAQJJQEASmyMCBAgQEBAbIECAAIEkICCJzREBAgQICIgNECBAgEASEJDE5ogAAQIEBMQGCBAgQCAJCEhic0SAAAECAmIDBAgQIJAEBCSxOSJAgAABAbEBAgQIEEgCApLYHBEgQICAgNgAAQIECCQBAUlsjggQIEBAQGyAAAECBJKAgCQ2RwQIECAgIDZAgAABAklAQBKbIwIECBAQEBsgQIAAgSQgIInNEQECBAgIiA0QIECAQBIQkMTmiAABAgQExAYIECBAIAkISGJzRIAAAQICYgMECBAgkAQEJLE5IkCAAAEBsQECBAgQSAICktgcESBAgICA2AABAgQIJAEBSWyOCBAgQEBAbIAAAQIEkoCAJDZHBAgQICAgNkCAAAECSUBAEpsjAgQIEBAQGyBAgACBJCAgic0RAQIECAiIDRAgQIBAEhCQxOaIAAECBATEBggQIEAgCQhIYnNEgAABAgJiAwQIECCQBAQksTkiQIAAAQGxAQIECBBIAgKS2BwRIECAgIDYAAECBAgkAQFJbI4IECBAQEBsgAABAgSSgIAkNkcECBAgICA2QIAAAQJJQEASmyMCBAgQEBAbIECAAIEkICCJzREBAgQICIgNECBAgEASEJDE5ogAAQIEBMQGCBAgQCAJCEhic0SAAAECAmIDBAgQIJAEBCSxOSJAgAABAbEBAgQIEEgCApLYHBEgQICAgNgAAQIECCQBAUlsjggQIEBAQGyAAAECBJKAgCQ2RwQIECAgIDZAgAABAklAQBKbIwIECBAQEBsgQIAAgSQgIInNEQECBAgIiA0QIECAQBIQkMTmiAABAgQExAYIECBAIAkISGJzRIAAAQICYgMECBAgkAQEJLE5IkCAAAEBsQECBAgQSAICktgcESBAgICA2AABAgQIJAEBSWyOCBAgQEBAbIAAAQIEkoCAJDZHBAgQICAgNkCAAAECSUBAEpsjAgQIEBAQGyBAgACBJCAgic0RAQIECAiIDRAgQIBAEhCQxOaIAAECBC7aAB3WkK47KgAAAABJRU5ErkJggg==";
       
        $model->model_ = 'cuestionario';

        $m_trabajadores = new Trabajadores();
        $m_detalle = new DetalleCuestionario();

        $model->id_form = 1;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);


        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->name_empresa = $empresa->comercial;
        }
        $model->model_ = 'cuestionario';


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
            
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Cuestionario");
            $model->txt_base64_foto = $param['txt_base64_foto'];
            
            $err = null;
            $transactions = Yii::$app->db->beginTransaction();

            //dd($m_trabajadores);

            if (isset($m_detalle->_preguntas) && $m_detalle->_preguntas != null && $_POST["imagen2"]) {
                if ($imgCanvas == $_POST["imagen2"]) {
                    return $this->render('create', [
                        'model' => $model,
                        'm_trabajadores' => $m_trabajadores,
                        'm_empresas' => $m_empresas,
                        'm_detalle' => $m_detalle,
                        'm_medicos' => $m_medicos,
                        'message' => "El empleado debe de firmar el cuestionario!",
                        'type' => "warning"
                    ]);
                }
                
                try {
                    $Continuations = $m_detalle->_preguntas["p0"];
                    ArrayHelper::removeValue($m_detalle->_preguntas, $m_detalle->_preguntas["p0"]); // Se quita 'p0'

                    // Filtro 1, primer pregunta
                    $noContinuations = $Continuations;

                    foreach ($noContinuations as $key => $value) {
                        if ($value == "Si") {
                            unset($noContinuations[$key]);
                        }
                    }

                    // Aplicando filtro 1
                    foreach ($noContinuations as $key => $value) {
                        foreach ($m_detalle->_preguntas as $key2 => $value2) {
                            if ($key2 != "p1") {
                                unset($m_detalle->_preguntas[$key2][$key]);
                            }
                        }
                    }

                    // Filtro 2, cuarta pregunta
                    $noContinuations_p = $m_detalle->_preguntas;

                    foreach ($noContinuations_p as $key => $value) {
                        foreach ($value as $key2 => $value2) {
                            if ($value2 == "Si") {
                                unset($noContinuations_p[$key][$key2]);
                            }
                        }
                    }

                    // Aplicando filtro 2
                    if (isset($noContinuations_p["p4"])) {
                        $noContinuations_p = $noContinuations_p["p4"];
                    
                        foreach ($m_detalle->_preguntas as $key => $value) {
                            if ($key != "p1" && $key != "p2" && $key != "p3" && $key != "p4") {
                                foreach ($noContinuations_p as $key2 => $value2) {
                                    unset($m_detalle->_preguntas[$key][$key2]);
                                }
                            }
                        }
                    }

                    $bitacora = bitacora('crear');

                    if ($bitacora) {
                        
                        /* $nivel_usuario = Yii::$app->user->identity->rol;
                        if ($nivel_usuario == 2) {
                            $model->id_medico = Yii::$app->user->identity->id;
                        } */

                        $model->nombre_empresa = Trabajadores::getCompany($model->id_paciente);
                        

                        $model->id_bitacora = $bitacora->id;

                        $model->id_area = $m_trabajadores->id_area;
                        $model->id_puesto = $m_trabajadores->id_puesto;
                        $model->sexo = $m_trabajadores->sexo;
                        $model->fecha_nacimiento = $m_trabajadores->fecha_nacimiento;
                        $model->edad = $m_trabajadores->edad;

                        $model->id_tipo_cuestionario = 1;
                        $model->fecha_cuestionario = Date('Y-m-d H:i:s');
                        $model->firma_paciente = (isset($_POST['imagen2'])) ? $_POST['imagen2'] : "";
                        $model->status = 1;
                        
                        if ($model->save()) {
                            //(isset($_POST['imagen'])) ? $this->saveImage($_POST['imagen'], $model->id, "firma_medico_".$model->id) : null;
                            (isset($_POST['imagen2'])) ? saveImage($model, $_POST['imagen2'], "firma_trabajador_".$model->id) : null;
                            
                            foreach ($m_detalle->_preguntas as $key_pregunta => $areas) {
                                foreach ($areas as $key_area => $respuesta) {
                                    $respuesta = ($key_pregunta == "p1" && $respuesta == "") ? "No" : $respuesta;
                                    
                                    $resp = insertRespuesta($model->id, (int)substr($key_pregunta, 1), $key_area, $respuesta);

                                    if (!$resp) {
                                        $err = "(4): Las respuestas no fueron guardadas! no se guardo el cuestionario.";
                                        break 2;
                                    }
                                }
                            }


                            //dd($model,$param);
                            define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_paciente.'/Cuestionario/');

                            if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){

                                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_paciente.'/';
                                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_paciente.'/Cuestionario/';
                                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                
                                $this->actionCarpetas( $directorios);
                                $nombre_foto = $this->saveImagen('CONSENTIMIENTO_'.$model->id.'_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                                $model->foto_web = $nombre_foto;
                                $model->save();
                            
                            }
            
                            if($model->fecha_c == null || $model->fecha_c == '' || $model->fecha_c == ' '){
                                $model->fecha_c = date('Y-m-d');
                                $model->hora_c = date('H:i:s');
                            }

                        } else {
                            dd($model);
                            $err = "(3): No se guardo el cuestionario!!";
                        }
                    } else {
                        $err = "(2): No se registro en la bitacora";
                    }
                } catch (\Throwable $th) {
                    $err = str_replace("'", '"',explode("\n", $th->getMessage())[0]);
                }
            } else {
                $err = "(1): No se recibieron los datos necesarios";
            }

            if ($err !== null) {
                $transactions->rollBack();

                return $this->render('create', [
                    'model' => $model,
                    'm_trabajadores' => $m_trabajadores,
                    'm_empresas' => $m_empresas,
                    'm_detalle' => $m_detalle,
                    'm_medicos' => $m_medicos,
                    'message' => "Error " . $err,
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

            //dd($model);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'm_trabajadores' => $m_trabajadores,
            'm_empresas' => $m_empresas,
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

    /**
     * Updates an existing Cuestionario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->getAgeupdated($model,$model->fecha_cuestionario,$model->id_paciente);

        $model->model_ = 'cuestionario';

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
                'SetTitle' => 'CUESTIONARIO NORDICO '.$model->id.'.pdf',
                'SetSubject' => 'CUESTIONARIO NORDICO',
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
     * Deletes an existing Cuestionario model.
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

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Retorna los trabajadores activos de una empresa
     * @return array|bool Retorna los datos en formato json, si no se encuentran datos, retorna false
     */
    public function actionGetWorkers() {

        $puestos = [];
        $areas = [];
        $niveles1 = [];
        $niveles2 = [];
        $niveles3 = [];
        $niveles4 = [];

        $areas_trabajador = [];

        $nombre_empresa = '';

        if (Yii::$app->request->post('empresa')) {
            $id_empresa = Yii::$app->request->post('empresa');
            $empresa = Empresas::findOne($id_empresa);

            if ($empresa) {
                $nombre_empresa = $empresa->comercial;

                $trabajadores = Trabajadores::find()
                                ->select(['id', 'nombre','apellidos'])
                                ->where(["id_empresa" => $empresa->id])
                                ->andWhere(["!=", "status", 5])
                                // ->andWhere(["OR", "status_detallado = :activo", "status_detallado = :cartera"], [":activo" => 'ACTIVO', ":cartera" => "CARTERA"])
                                ->orderBy('nombre')
                                ->all();

                $trabajadores_ = ArrayHelper::map($trabajadores, function($model){
                    $ret = $model->nombre.' '.$model->apellidos;
                    return $ret;
                },'id');

                $puestos =$empresa->puestos;
                $areas = $empresa->areas;


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


                $label_nivel1 = 'Nivel 1';
                $label_nivel2 = 'Nivel 2';
                $label_nivel3 = 'Nivel 3';
                $label_nivel4 = 'Nivel 4';
                
                $show_nivel1 = 'none';
                $show_nivel2 = 'none';
                $show_nivel3 = 'none';
                $show_nivel4 = 'none';
                
                
                $usuario = Yii::$app->user->identity;
                if($usuario->nivel1_all == 1){
                    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
                        $rtlvl1 = '';
                        if($data->pais){
                            $rtlvl1 = $data->pais->pais;
                        }
                        return $rtlvl1;
                    });
                }  else {
                    $array = explode(',', $usuario->nivel1_select);
                    if($array && count($array)>0){

                    } else {
                        $array = [];
                    }
                    
                    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array])->orderBy('id_pais')->all(), 'id', function($data){
                        $rtlvl1 = '';
                        if($data->pais){
                            $rtlvl1 = $data->pais->pais;
                        }
                        return $rtlvl1;
                    });
                }
                
                if($usuario->nivel2_all == 1){
                    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
                        $rtlvl2 = $data['nivelorganizacional2'];
                        return $rtlvl2;
                    });
                }  else {
                    $array = explode(',', $usuario->nivel2_select);
                    if($array && count($array)>0){

                    } else {
                        $array = [];
                    }
                    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
                        $rtlvl2 = $data['nivelorganizacional2'];
                        return $rtlvl2;
                    });
                }
                
                
                if($usuario->nivel3_all == 1){
                    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
                        $rtlvl3 = $data['nivelorganizacional3'];
                        return $rtlvl3;
                    });
                } else {
                    $array = explode(',', $usuario->nivel3_select);
                    if($array && count($array)>0){

                    } else {
                        $array = [];
                    }
                    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
                        $rtlvl3 = $data['nivelorganizacional3'];
                        return $rtlvl3;
                    });
                }
                
                
                if($usuario->nivel4_all == 1){
                    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
                        $rtlvl4 = $data['nivelorganizacional4'];
                        return $rtlvl4;
                    });
                } else {
                    $array = explode(',', $usuario->nivel4_select);
                    if($array && count($array)>0){

                    } else {
                        $array = [];
                    }
                    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
                        $rtlvl4 = $data['nivelorganizacional4'];
                        return $rtlvl4;
                    });
                } 
                
                
                
                if($empresa){
                    if($empresa){
                        $label_nivel1 = $empresa->label_nivel1;
                        $label_nivel2 = $empresa->label_nivel2;
                        $label_nivel3 = $empresa->label_nivel3;
                        $label_nivel4 = $empresa->label_nivel4;
                        
                        if($empresa->cantidad_niveles >= 1){
                            $show_nivel1 = 'block';
                            
                            if($usuario->areas_all == 1){
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->all(), 'id','area');
                            } else {
                                $array = explode(',', $usuario->areas_select);
                                if($array && count($array)>0){

                                } else {
                                    $array = [];
                                }
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all(), 'id','area');
                            }
                        }
                        

                        if($empresa->cantidad_niveles >= 2){
                            $show_nivel2 = 'block';
                            $usuario = Yii::$app->user->identity;
                            if($usuario->areas_all == 1){
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->all(), 'id','area');
                            } else {
                                $array = explode(',', $usuario->areas_select);
                                if($array && count($array)>0){

                                } else {
                                    $array = [];
                                }
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->andWhere(['in','id',$array])->all(), 'id','area');
                            }
                        }
                        
                        
                        if($empresa->cantidad_niveles >= 3){
                            $show_nivel3 = 'block';
                            
                            $usuario = Yii::$app->user->identity;
                            if($usuario->areas_all == 1){
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->all(), 'id','area');
                            } else {
                                $array = explode(',', $usuario->areas_select);
                                if($array && count($array)>0){

                                } else {
                                    $array = [];
                                }
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->andWhere(['in','id',$array])->all(), 'id','area');
                            }
                        }
                        
                        
                        if($empresa->cantidad_niveles >= 4){
                            $show_nivel4 = 'block';
                            $usuario = Yii::$app->user->identity;
                            if($usuario->areas_all == 1){
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->all(), 'id','area');
                            } else {
                                $array = explode(',', $usuario->areas_select);
                                if($array && count($array)>0){

                                } else {
                                    $array = [];
                                }
                                //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->andWhere(['in','id',$array])->all(), 'id','area');
                            }
                        }
                    }
                }

                
                return \yii\helpers\Json::encode(['trabajadores_'=>$trabajadores_,'puestos'=>$puestos,'areas'=>$areas,'paises'=>$paises,'label_nivel1'=>$label_nivel1,'label_nivel2'=>$label_nivel2,'label_nivel3'=>$label_nivel3,'label_nivel4'=>$label_nivel4,'show_nivel1'=>$show_nivel1,'show_nivel2'=>$show_nivel2,'show_nivel3'=>$show_nivel3,'show_nivel4'=>$show_nivel4,'nivel1'=>$nivel1,'nivel2'=>$nivel2,'nivel3'=>$nivel3,'nivel4'=>$nivel4,'areas_trabajador'=>$areas_trabajador,'nombre_empresa'=>$nombre_empresa]);
            }
            
        }

        return false;
    }

    /**
     * Verifica si el trabajador ya aplico cierto cuestionario
     * @return bool False si ya se aplico el cuestionario, True si no sa ha aplicado el cuestionario
     */
    public function actionVerificarCuestionarioTrabajador() {

        if (Yii::$app->request->post('id_trabajador') && Yii::$app->request->post('id_tipo_cuestionario')) {
            $id_trabajador = Yii::$app->request->post('id_trabajador');
            $id_tipo_cuestionario = Yii::$app->request->post('id_tipo_cuestionario');

            // Se habilito la repeticion del cuestionario nordico y musculo esqueleticas
            if ($id_tipo_cuestionario == 1 || $id_tipo_cuestionario == 3) {
                return true;
            }

            $cuestionarios = Cuestionario::findOne(['id_paciente' => $id_trabajador, 'id_tipo_cuestionario' => $id_tipo_cuestionario, "status" => 1]);

            if ($cuestionarios == null) {
                return true;
            } else {
                return false;
            }
        }

        return "0";
    }

    /**
     * Retorna la informacion basica de un trabajador.
     * @return array Retirna en formato json los datos del trabajador, si no existe, retorna un array vacio
     */
    public function actionLoadWorkerData() {
    
        if (Yii::$app->request->post("id_trabajador")) {
            $id_trabajador = Yii::$app->request->post("id_trabajador");

            if ($trabajador = Trabajadores::findOne(["id" => $id_trabajador])) {
                $hcc_ohc = Hccohc::find()->select(['id_trabajador', 'peso', 'talla', 'imc'])->where(['id_trabajador' => $id_trabajador])->orderBy(["id" => SORT_DESC])->one();

                $trabajador = [
                    'nombre' => $trabajador->nombre,
                    'apellidos' => $trabajador->apellidos,
                    'fecha_nacimiento' => $trabajador->fecha_nacimiento,
                    'sexo' => $trabajador->sexo,
                    'num_trabajador' => $trabajador->num_trabajador,
                    'id_puesto' => $trabajador->id_puesto,
                    'id_area' => $trabajador->id_area,
                    'empresa' => $trabajador->id_empresa,
                    'id_nivel1' => $trabajador->id_nivel1,
                    'id_nivel2' => $trabajador->id_nivel2,
                    'id_nivel3' => $trabajador->id_nivel3,
                    'id_nivel4' => $trabajador->id_nivel4,
                    'peso' => ($hcc_ohc) ? $hcc_ohc->peso : null,
                    'talla' => ($hcc_ohc) ? ($hcc_ohc->talla * 100) : null,
                    'imc' => ($hcc_ohc) ? $hcc_ohc->imc : null,
                ];

                return json_encode($trabajador);
            }
        }

        return json_encode([]);
    }

    public function actionFirma(){
        if ( isset($_POST["id_"]) ) {
            $r = "img/";
            $r = "https://www.medicalfil.com/web/images/ohc/";

            $path = UsuariosRh::find()->where(['id' => $_POST["id_"]])->one()->sign;
            $path = ($path != null) ? $r . $path : "";

            return json_encode($path);
        }
        return json_encode("");
    }


    public function actionConsentimientopdf($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Cuestionario::findOne($id);
        $nombre_paciente = '';

        $image = 'resources/images/empty.png';
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        if($model){
            if($model->trabajadorsmo){
                $nombre_paciente = $model->trabajadorsmo->nombre.' '.$model->trabajadorsmo->apellidos;
            }
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('consentimientopdf.php',['model' => $model]),
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
                'SetTitle' => 'CONSENTIMIENTO '.$nombre_paciente.'.pdf',
                'SetSubject' => 'Consentimientos',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="'.$image.'"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
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

    protected function saveImagen($nombre,$imagen,$model) {
        
        $img = $imagen;
        $img = str_replace('data:image/jpeg;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  $nombre . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
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