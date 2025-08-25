<?php

namespace app\controllers;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Carbon\Carbon;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use chillerlan\QRCode\{QRCode, QROptions};

use app\models\Estudios; 
use app\models\Empresas;
use app\models\Historialdocumentos;
use app\models\Puestostrabajo;
use app\models\Trabajadorestudio;
use app\models\Puestotrabajador;
use app\models\Trabajadores;
use app\models\TrabajadoresSearch;
use app\models\Areas;
use app\models\ConsultasSearch;
use app\models\Trabajadorepp;
use app\models\Medidas;
use app\models\Parametros;
use app\models\Trabajadorparametro;
use app\models\DetalleCuestionario;
use app\models\Puestoparametro;
use app\models\Trabajadormaquina;
use app\models\Trashhistory;


use app\models\Consultas;
use app\models\Hccohc;
use app\models\Poes;
use app\models\Cuestionario;

use app\models\NivelOrganizacional1;
use app\models\Puestotrabajohriesgo;
use app\models\Usuariotrabajador;
use app\models\ProgramaTrabajador;

use Yii;

require_once __DIR__ . '/../web/phpqrcode/qrlib.php';
require_once __DIR__.'/../vendor/autoload.php';


/**
 * TrabajadoresController implements the CRUD actions for Trabajadores model.
 */
class TrabajadoresController extends Controller
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
    public function actionIndex($id_empresa = null, $page=null)
    {
        $searchModel = new TrabajadoresSearch();

        if($id_empresa != null){
            $searchModel->id_empresa = $id_empresa;
        }

        $dataProvider = $searchModel->search($this->request->queryParams);

        $this->getHistorialpuesto();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all Trabajadores models.
     *
     * @return string
     */
    public function actionIncapacidades()
    {
        $searchModel = new ConsultasSearch();
        $searchModel->tipo = 4;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexincapacidad', [
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
        //date_default_timezone_set('America/Mazatlan');
        $model->status = 1;
        $model->envia_puesto = 0;
        $model->envia_form = 0;
        $model->scenario = 'create';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }

        for ($i = 0; $i <= 1; $i++) {
            $model->aux_estudios[$i]['estudio'] = '';
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
            
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Trabajadores");

                $model->envia_puesto = $param['envia_puesto'];
                $model->envia_form = $param['envia_form']; 
                $model->edad = $param['edad'];
                $model->firma = $param['firma'];
                $model->txt_base64_foto = $param['txt_base64_foto'];
                $model->txt_base64_ine = $param['txt_base64_ine'];
                $model->txt_base64_inereverso = $param['txt_base64_inereverso'];
                
               
                if($model->envia_form == '1'){

                    $puesto_sueldo = str_replace("$", "",$model->puesto_sueldo);
                    $puesto_sueldo = str_replace(",", "",$puesto_sueldo);
                    $puesto_sueldo = str_replace("  ", " ",$puesto_sueldo);
                    $puesto_sueldo = str_replace(" ", "",$puesto_sueldo);
                    $puesto_sueldo = floatval($puesto_sueldo);

                    $model->puesto_sueldo = $puesto_sueldo;
                    ///dd('Entra a guardar');

                    $this->guardarMedidas($model);
                    
                    $model->save();

                    if($model){
                        $hc = new Hccohc();
                        $hc->id_trabajador = $model->id;
                        $hc->id_empresa = $model->id_empresa;
                        $hc->fecha = date('Y-m-d');
                        $hc->examen = 1;
                        $hc->nombre = $model->nombre;
                        $hc->apellidos = $model->apellidos;
                        $hc->sexo = $model->sexo;
                        $hc->fecha_nacimiento = $model->fecha_nacimiento;
                        $hc->edad = $model->edad;
                        $hc->nivel_lectura = $model->nivel_lectura;
                        $hc->nivel_escritura = $model->nivel_escritura;
                        $hc->estado_civil = $model->estado_civil;
                        $hc->area = $model->id_area;
                        $hc->puesto = $model->id_puesto;
                        $hc->id_nivel1 = $model->id_nivel1;
                        $hc->id_nivel2 = $model->id_nivel2;
                        $hc->id_nivel3 = $model->id_nivel3;
                        $hc->id_nivel4 = $model->id_nivel4;
                        $hc->status = 0;
                        $hc->save();
                        //dd($hc);
                    }
                    $archivo = UploadedFile::getInstance($model,'file_foto');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/';
                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                    
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                        $model->foto = $nombre_archivo; 
                        $model->save();
                    } 
    
                    $model->create_user = Yii::$app->user->identity->id;
                    $model->create_date = date('Y-m-d H:i:s');
                    $model->save();
                
                    
                    $this->saveMultiple($model,$param);

                    if(isset($model->fecha_contratacion) && $model->fecha_contratacion != null && $model->fecha_contratacion != ''){
                        $resultado_antiguedad = $this->actionCalculateantig($model->fecha_contratacion,$model);
                        
                        $model->antiguedad = $resultado_antiguedad[0];
                        $model->antiguedad_anios = $resultado_antiguedad[1];
                        $model->antiguedad_meses = $resultado_antiguedad[2];
                        $model->antiguedad_dias = $resultado_antiguedad[3];
                        $model->save();
                    }

                    $this->guardarParametros($model);

                    $this->actionCrearqr($model);

                    if($model->validate()){
                        $model->save();
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/');

                        //Guardar la firma del trabajador---------------------------------
                        if(isset($model->firma) && $model->firma != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_firma = $this->saveFirma($model->firma,$model);
                            $model->firma_ruta = $nombre_firma;
                            $model->save();
                        }

                        //Guardar la foto del trabajador
                        if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_foto = $this->saveImagen('FOTOCAMARA_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                            $model->foto_web = $nombre_foto;
                            $model->save();
                        }

                        //Guardar la ine del trabajador
                        if(isset($model->txt_base64_ine) && $model->txt_base64_ine != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_ife = $this->saveImagen('INE_'.date('y-m-d_h-i-s'),$model->txt_base64_ine,$model);
                            $model->ife = $nombre_ife;
                            $model->save();
                        }

                        //Guardar la ine reverso del trabajador
                        if(isset($model->txt_base64_inereverso) && $model->txt_base64_inereverso != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_ifereverso = $this->saveImagen('INEREVERSO_'.date('y-m-d_h-i-s'),$model->txt_base64_inereverso,$model);
                            $model->ife_reverso = $nombre_ifereverso;
                            $model->save();
                        }
                    }

                    $this->checarDocs($model);

                    if($model->id_puesto != null && $model->id_puesto != '' && $model->id_puesto != ' '){
                        $this->guardarPuesto($model,$model->id_puesto);
                    } else {
                            $puestotrabajo = Puestotrabajador::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_puesto'=>$model->id_puesto])->one();
                            if($puestotrabajo){
                                $puestotrabajo->id_trabajador = $model->id;
                                $puestotrabajo->id_puesto = $model->id_puesto;
                                $puestotrabajo->area = ''.$model->id_area;
                                $puestotrabajo->fecha_inicio = $model->fecha_iniciop;
                                $puestotrabajo->fecha_fin = $model->fecha_finp;
                                $puestotrabajo->teamleader = $model->teamleader;
                                $puestotrabajo->save();

                                $resultado_antiguedad = $this->actionCalculateantigpuesto($puestotrabajo);
                                $puestotrabajo->antiguedad = $resultado_antiguedad[0];
                                $puestotrabajo->save();
                            }
                        }

                    $this->cumplimientosTrabajador($model->id);

                    return $this->redirect(['index']);
                } else{
                    if($model->envia_puesto == '1'){
                   
                        $puesto = Puestostrabajo::findOne($model->id_puesto);
                    
                        if($puesto){
                            //Hacer aqui la wea de los estudios generales por empresa

                            $index = 0;
                            if(isset($model->id_empresa)){
                                $empresa = Empresas::findOne($model->id_empresa);

                                if(isset($empresa)){
                                    if($empresa->requisitosactivos){
                                        foreach($empresa->requisitosactivos as $key=>$estudio){
                                            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
                                            $model->aux_estudios[$key]['periodicidad'] = $estudio->id_periodicidad;
                                            $model->aux_estudios[$key]['fecha_apartir'] = $estudio->fecha_apartir;
                                            $model->aux_estudios[$key]['tipo'] = $estudio->id_tipo;
                                            /* $model->aux_estudios[$key]['id'] = $estudio->id; */
                                            $index ++;
                                        }
                                    }
                                }
                            }
                            //dd($index);

                            if($puesto->pestudiosactivos){
                                foreach($puesto->pestudiosactivos as $key=>$estudio){
                                    $model->aux_estudios[$key+$index]['estudio'] = $estudio->id_estudio;
                                    $model->aux_estudios[$key+$index]['periodicidad'] = $estudio->periodicidad;
                                    $model->aux_estudios[$key+$index]['fecha_apartir'] = $estudio->fecha_apartir;
                                    $model->aux_estudios[$key+$index]['tipo'] = $estudio->id_tipo;
                                   /*  $model->aux_estudios[$key+$index]['id'] = $estudio->id; */
                                }
                            }
    
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    } 
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

    private function guardarMedidas($model){
        if($model->talla_cabeza == 0){
            if(isset($model->talla_cabezaotro) && $model->talla_cabezaotro != null && $model->talla_cabezaotro != ''){
                $medida = new Medidas();
                $medida->id_empresa = $model->id_empresa;
                $medida->parte_corporal = 1;
                $medida->medida = $model->talla_cabezaotro;
                $medida->status = 1;
                $medida->save();

                if($medida){
                    $model->talla_cabeza = $medida->id;
                }
            } else{
                $model->talla_cabeza = null;
            }
        }
        if($model->talla_superior == 0){
            if(isset($model->talla_superiorotro) && $model->talla_superiorotro != null && $model->talla_superiorotro != ''){
                $medida = new Medidas();
                $medida->id_empresa = $model->id_empresa;
                $medida->parte_corporal = 2;
                $medida->medida = $model->talla_superiorotro;
                $medida->status = 1;
                $medida->save();

                if($medida){
                    $model->talla_superior = $medida->id;
                }
            } else{
                $model->talla_superior = null;
            }
        }
        if($model->talla_inferior == 0){
            if(isset($model->talla_inferiorotro) && $model->talla_inferiorotro != null && $model->talla_inferiorotro != ''){
                $medida = new Medidas();
                $medida->id_empresa = $model->id_empresa;
                $medida->parte_corporal = 3;
                $medida->medida = $model->talla_inferiorotro;
                $medida->status = 1;
                $medida->save();

                if($medida){
                    $model->talla_inferior = $medida->id;
                }
            } else{
                $model->talla_inferior = null;
            }
        }
        if($model->talla_manos == 0){
            if(isset($model->talla_manosotro) && $model->talla_manosotro != null && $model->talla_manosotro != ''){
                $medida = new Medidas();
                $medida->id_empresa = $model->id_empresa;
                $medida->parte_corporal = 4;
                $medida->medida = $model->talla_manosotro;
                $medida->status = 1;
                $medida->save();

                if($medida){
                    $model->talla_manos = $medida->id;
                }
            } else{
                $model->talla_manos = null;
            }
        }
        if($model->talla_pies == 0){
            if(isset($model->talla_piesotro) && $model->talla_piesotro != null && $model->talla_piesotro != ''){
                $medida = new Medidas();
                $medida->id_empresa = $model->id_empresa;
                $medida->parte_corporal = 5;
                $medida->medida = $model->talla_piesotro;
                $medida->status = 1;
                $medida->save();

                if($medida){
                    $model->talla_pies = $medida->id;
                }
            } else{
                $model->talla_pies = null;
            }
        }

    }

    public function actionCalculateantig($fecha,$model){
        
        //dd('Fecha: '.$fecha);
        $resultados = ['','','',''];
        $date = Carbon::parse($fecha.' 00:00:00');//Convertir a Carbon la fecha de contratación

        $now = Carbon::now();
        if($model->status == 2 && (isset($model->fecha_baja) && $model->fecha_baja != null  && $model->fecha_baja != '')){
            $now = Carbon::parse($model->fecha_baja.' 00:00:00');
        }
        
        $dias = $date->diffInDays($now);
        $meses = $date->diffInMonths($now);
        $anios = $date->diffInYears($now);
        
        $ret_anios = $anios;//Los años ya van en automatico
        $ret_meses = $meses;//los meses luego los calcularemos bien (restando es mes x año)
        $ret_dias = $dias; //Los dias luego los calcularemos bien

        $ret_antiguedad = '';

        if($ret_anios > 0){ //Si hay años
            /* $anios_dias = $ret_anios*365;
            $ret_dias = $dias - $anios_dias; */
            if($meses > 0){
                $ret_meses = $meses - ($ret_anios * 12);
            }
        }

        //Aumentar años y meses a la fecha inicial, ahora solo sacar los dias de diferencia y listo xd
        $new_date = $date->addYears($ret_anios);
        $new_date = $new_date->addMonths($ret_meses);
        $ret_dias = $new_date->diffInDays($now);
        //dd($ret_dias);

        //dd('FECHA CONTRATACIÓN: '.$fecha.' | Años: '.$anios.' | Meses: '.$meses.' | Días: '.$dias.' ------ Ret Años: '.$ret_anios.' | Ret Meses: '.$ret_meses.' | Ret Días: '.$ret_dias);

        /* if($ret_dias > 30){ //Si hay meses, calcular los dias sobrantes
            $ret_meses = intval($ret_dias/31);
            $ret_dias = $dias - ($ret_meses * 31);
        } */


        if($ret_anios > 0){
            $label = ' años';
            if($ret_anios == 1){
                $label = ' año';
            }
            $ret_antiguedad .= $ret_anios.$label;
        }
        if($ret_meses > 0){
            $label = ' meses';
            if($ret_meses == 1){
                $label = ' mes';
            }
            $ret_antiguedad .= ' '.$ret_meses.$label;
        }
        if($ret_dias > 0){
            $label = ' dias';
            if($ret_dias == 1){
                $label = ' dia';
            }
            $ret_antiguedad .= ' '.$ret_dias.$label;
        }

        $resultados[0] = trim($ret_antiguedad);
        $resultados[1] = $ret_anios;
        $resultados[2] = $ret_meses;
        $resultados[3] = $ret_dias;

        //dd('dias o: '.$dias.' | años o: '.$anios.' |||||| años: '.$ret_anios.' | meses: '.$ret_meses.' | dias: '.$ret_dias);

        return $resultados;
    }


    public function actionCalculateantigpuesto($puesto){
        $resultados = ['','','',''];

        if($puesto->fecha_inicio != null && $puesto->fecha_inicio != '' && $puesto->fecha_inicio != ' '){

            //dd('Fecha: '.$fecha);
            $date = Carbon::parse($puesto->fecha_inicio.' 00:00:00');//Convertir a Carbon la fecha de contratación

            $now = Carbon::now();
            if((isset($model->fecha_baja) && $model->fecha_baja != null  && $model->fecha_baja != '')){
                $now = Carbon::parse($model->fecha_baja.' 00:00:00');
            }
        
            $dias = $date->diffInDays($now);
            $meses = $date->diffInMonths($now);
            $anios = $date->diffInYears($now);
        
            $ret_anios = $anios;//Los años ya van en automatico
            $ret_meses = $meses;//los meses luego los calcularemos bien (restando es mes x año)
            $ret_dias = $dias; //Los dias luego los calcularemos bien

            $ret_antiguedad = '';

            if($ret_anios > 0){ //Si hay años
                if($meses > 0){
                    $ret_meses = $meses - ($ret_anios * 12);
                }
            }

            //Aumentar años y meses a la fecha inicial, ahora solo sacar los dias de diferencia y listo xd
            $new_date = $date->addYears($ret_anios);
            $new_date = $new_date->addMonths($ret_meses);
            $ret_dias = $new_date->diffInDays($now);


            if($ret_anios > 0){
                $label = ' años';
                if($ret_anios == 1){
                    $label = ' año';
                }
                $ret_antiguedad .= $ret_anios.$label;
            }
            if($ret_meses > 0){
                $label = ' meses';
                if($ret_meses == 1){
                    $label = ' mes';
                }
                $ret_antiguedad .= ' '.$ret_meses.$label;
            }
            if($ret_dias > 0){
                $label = ' dias';
                if($ret_dias == 1){
                    $label = ' dia';
                }
                $ret_antiguedad .= ' '.$ret_dias.$label;
            }

            $resultados[0] = trim($ret_antiguedad);
            $resultados[1] = $ret_anios;
            $resultados[2] = $ret_meses;
            $resultados[3] = $ret_dias;

        }

        return $resultados;
    }




    /**
     * Updates an existing Trabajadores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFolder($id)
    {
        //date_default_timezone_set('America/Mazatlan');
        $model = $this->findModel($id);
        $model->scenario = 'folder';

        $this->checarDocs($model);

        if($model->testudios){
            foreach($model->testudios as $key=>$estudio){
                $model->aux_estudios[$key]['index'] = ($key+1);
                $model->aux_estudios[$key]['tipo'] = $estudio->id_tipo;
                $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
                $model->aux_estudios[$key]['periodicidad'] = $estudio->id_periodicidad;
                $model->aux_estudios[$key]['fecha_apartir'] = $estudio->fecha_apartir;
                $model->aux_estudios[$key]['fecha_documento'] = $estudio->fecha_documento;
                $model->aux_estudios[$key]['status_show'] = $estudio->status;
    
                if($estudio->id_periodicidad == '0'){
                    $model->aux_estudios[$key]['fecha_vencimiento'] = 'INDEFINIDO';
                }else{
                    $model->aux_estudios[$key]['fecha_vencimiento'] = $estudio->fecha_vencimiento;
                }
    
                if($estudio->evidencia != '' && $estudio->evidencia != null){
                    $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$estudio->evidencia;
                }else{
                    $model->aux_estudios[$key]['doc'] = null;
                }
                $model->aux_estudios[$key]['conclusion'] = $estudio->conclusion;
                $model->aux_estudios[$key]['id'] = $estudio->id;
            }
        } else{
            for ($i = 0; $i <= 1; $i++) {
                $model->aux_estudios[$i]['estudio'] = '';
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Trabajadores");

            $this->saveMultiple($model,$param);
            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('folder', [
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
    public function actionEpp($id)
    {
        //date_default_timezone_set('America/Mazatlan');
        $model = $this->findModel($id);
        $model->scenario = 'epp';

        if(isset($model->puesto)){
            if($model->puesto->epps){
                foreach($model->puesto->epps as $key=>$epp){
                    $trabajadorepp = Trabajadorepp::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_puestoepp'=>$epp->id])->andWhere(['status'=>1])->one();
                    if($trabajadorepp){
                        $model->aux_epps[$epp->id] = $trabajadorepp->id_epp;
                        $model->aux_tallas[$epp->id] = $trabajadorepp->talla;
                        $model->aux_tallas2[$epp->id] = $trabajadorepp->talla;
                    }
                }
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Trabajadores");

            foreach($model->aux_epps as $epp=>$insumo){
                $trabajadorepp = Trabajadorepp::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_epp'=>$insumo])->andWhere(['status'=>1])->one();
                if(!$trabajadorepp){
                    $trabajadorepp = new Trabajadorepp();
                    $trabajadorepp->id_trabajador = $model->id;
                    $trabajadorepp->id_epp = $insumo;
                    $trabajadorepp->save();
                }
                $trabajadorepp->id_puestoepp = $epp;
                $trabajadorepp->id_puesto = $model->id_puesto;
                $trabajadorepp->talla = $model->aux_tallas2[$epp];
                $trabajadorepp->status = 1;
                $trabajadorepp->save();
            }

            return $this->redirect(['index']);
        }

        return $this->render('epp', [
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
        //date_default_timezone_set('America/Mazatlan');
        $model = $this->findModel($id);
        $model->envia_puesto = 0;
        $model->envia_form = 0;
        $model->scenario = 'update';

        if($model->historiaclinica){
            $hc = $model->historiaclinica;

            if($hc->id_nivel1 == null || $hc->id_nivel1 == '' || $hc->id_nivel1 == ' '){
                $hc->id_nivel1 = $model->id_nivel1;
            }
            if($hc->id_nivel2 == null || $hc->id_nivel2 == '' || $hc->id_nivel2 == ' '){
                $hc->id_nivel2 = $model->id_nivel2;
            }
            if($hc->id_nivel3 == null || $hc->id_nivel3 == '' || $hc->id_nivel3 == ' '){
                $hc->id_nivel3 = $model->id_nivel3;
            }
            if($hc->id_nivel4 == null || $hc->id_nivel4 == '' || $hc->id_nivel4 == ' '){
                $hc->id_nivel4 = $model->id_nivel4;
            }
            $hc->save(false);
             
        }

        //dd($model);

        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }

        $array_maquinas = [];
        if($model->maquinas){
            foreach($model->maquinas as $key=>$maquina){
                array_push($array_maquinas, $maquina->id_maquina);
            }
        }
        if($array_maquinas && count($array_maquinas)>0){
            $model->maquina_select = $array_maquinas;
        }

        $this->cumplimientosTrabajador($model->id);

        $this->checarDocs($model);

        if($model->id_link == null || $model->id_link == '' || $model->id_link == ' '){
            //dd('entra');
            $this->actionCrearqr($model);
        }

        if(isset($model->fecha_contratacion) && $model->fecha_contratacion != null && $model->fecha_contratacion != ''){
            $resultado_antiguedad = $this->actionCalculateantig($model->fecha_contratacion,$model);
            
            $model->antiguedad = $resultado_antiguedad[0];
            $model->antiguedad_anios = $resultado_antiguedad[1];
            $model->antiguedad_meses = $resultado_antiguedad[2];
            $model->antiguedad_dias = $resultado_antiguedad[3];
            $model->save();
        }


        if($model->testudios){
            foreach($model->testudios as $key=>$estudio){
                $model->aux_estudios[$key]['index'] = ($key+1);
                $model->aux_estudios[$key]['tipo'] = $estudio->id_tipo;
                $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
                $model->aux_estudios[$key]['periodicidad'] = $estudio->id_periodicidad;
                $model->aux_estudios[$key]['fecha_apartir'] = $estudio->fecha_apartir;
                $model->aux_estudios[$key]['fecha_documento'] = $estudio->fecha_documento;
                $model->aux_estudios[$key]['status_show'] = $estudio->status;
    
                if($estudio->id_periodicidad == '0'){
                    $model->aux_estudios[$key]['fecha_vencimiento'] = 'INDEFINIDO';
                }else{
                    $model->aux_estudios[$key]['fecha_vencimiento'] = $estudio->fecha_vencimiento;
                }
    
                if($estudio->evidencia != '' && $estudio->evidencia != null){
                    $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$estudio->evidencia;
                }else{
                    $model->aux_estudios[$key]['doc'] = null;
                }
                $model->aux_estudios[$key]['conclusion'] = $estudio->conclusion;
                $model->aux_estudios[$key]['id'] = $estudio->id;
            }
        } else{
            for ($i = 0; $i <= 1; $i++) {
                $model->aux_estudios[$i]['estudio'] = '';
            }
        }

        foreach($model->parametros as $key=>$parametro){
            $model->aux_psicologico[$key]['parametro'] = $parametro->id_parametro;
            $model->aux_psicologico[$key]['valor'] = $parametro->valor;
            $model->aux_psicologico[$key]['id'] = $parametro->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            
           
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Trabajadores");

            $model->envia_puesto = $param['envia_puesto'];
            $model->envia_form = $param['envia_form']; 
            $model->edad = $param['edad'];
            $model->firma = $param['firma'];
            $model->txt_base64_foto = $param['txt_base64_foto'];
            $model->txt_base64_ine = $param['txt_base64_ine'];
            $model->txt_base64_inereverso = $param['txt_base64_inereverso'];

            if($model->envia_form == '1'){

                $puesto_sueldo = str_replace("$", "",$model->puesto_sueldo);
                $puesto_sueldo = str_replace(",", "",$puesto_sueldo);
                $puesto_sueldo = str_replace("  ", " ",$puesto_sueldo);
                $puesto_sueldo = str_replace(" ", "",$puesto_sueldo);
                $puesto_sueldo = floatval($puesto_sueldo);

                $model->puesto_sueldo = $puesto_sueldo;

                    if($model->id_puesto != null && $model->id_puesto != '' && $model->id_puesto != ' '){
                        $puesto_anterior = intval($model->getOldAttribute('id_puesto'));
                        $puesto_nuevo = intval($model->id_puesto);
                    
                        if($puesto_anterior != $puesto_nuevo){
                            $this->guardarPuesto($model,$puesto_nuevo);
                        } else {
                            $puestotrabajo = Puestotrabajador::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_puesto'=>$model->id_puesto])->one();
                            if($puestotrabajo){
                                $puestotrabajo->id_trabajador = $model->id;
                                $puestotrabajo->id_puesto = $model->id_puesto;
                                $puestotrabajo->area = ''.$model->id_area;
                                $puestotrabajo->fecha_inicio = $model->fecha_iniciop;
                                $puestotrabajo->fecha_fin = $model->fecha_finp;
                                $puestotrabajo->teamleader = $model->teamleader;
                                $puestotrabajo->save();

                                $resultado_antiguedad = $this->actionCalculateantigpuesto($puestotrabajo);
                                $puestotrabajo->antiguedad = $resultado_antiguedad[0];
                                $puestotrabajo->save();
                            }
                        }
                    }

                    $this->guardarMedidas($model);
                    
                    ///dd('Entra a guardar');
                    $model->save();
                    $archivo = UploadedFile::getInstance($model,'file_foto');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/';
                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                    
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                        $model->foto = $nombre_archivo; 
                        $model->save();
                    } 
    
                    $model->update_date = date('Y-m-d');
                    $model->update_user = Yii::$app->user->identity->id;
                    $model->save();
                
                    
                    $this->saveMultiple($model,$param);

                    if(isset($model->fecha_contratacion) && $model->fecha_contratacion != null && $model->fecha_contratacion != ''){
                        $resultado_antiguedad = $this->actionCalculateantig($model->fecha_contratacion,$model);
                        
                        $model->antiguedad = $resultado_antiguedad[0];
                        $model->antiguedad_anios = $resultado_antiguedad[1];
                        $model->antiguedad_meses = $resultado_antiguedad[2];
                        $model->antiguedad_dias = $resultado_antiguedad[3];
                        $model->save();
                    }
                    $this->guardarParametros($model);


                    if($model->status == 5){
                        $this->actionBajatrabajador($model);
                    }

                    if($model->validate()){
                        //dd('entra',$model);
                        $model->save();
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/');

                        //Guardar la firma del trabajador---------------------------------
                        if(isset($model->firma) && $model->firma != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_firma = $this->saveFirma($model->firma,$model);
                            $model->firma_ruta = $nombre_firma;
                            $model->save();
                        }

                        //Guardar la foto del trabajador
                        if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_foto = $this->saveImagen('FOTOCAMARA_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                            $model->foto_web = $nombre_foto;
                            $model->save();
                        }

                        //Guardar la ine del trabajador
                        if(isset($model->txt_base64_ine) && $model->txt_base64_ine != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_ife = $this->saveImagen('INE_'.date('y-m-d_h-i-s'),$model->txt_base64_ine,$model);
                            $model->ife = $nombre_ife;
                            $model->save();
                        }

                        //Guardar la ine reverso del trabajador
                        if(isset($model->txt_base64_inereverso) && $model->txt_base64_inereverso != 'data:,'){
                            $dir_ticket = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                            $directorios = ['0'=>$dir_ticket];
                            $this->actionCarpetas( $directorios);
                            $nombre_ifereverso = $this->saveImagen('INEREVERSO_'.date('y-m-d_h-i-s'),$model->txt_base64_inereverso,$model);
                            $model->ife_reverso = $nombre_ifereverso;
                            $model->save();
                        }
                    }

                    $this->cumplimientosTrabajador($model->id);
                    

                    return $this->redirect(['index']);
                } else{
                    if($model->envia_puesto == '1'){
                   
                        $puesto = Puestostrabajo::findOne($model->id_puesto);
                    
                        if($puesto){
                            if($puesto->pestudios){
                                foreach($puesto->pestudios as $key=>$estudio){
                                    $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
                                    $model->aux_estudios[$key]['periodicidad'] = $estudio->periodicidad;
                                    $model->aux_estudios[$key]['fecha_apartir'] = $estudio->fecha_apartir;
                                    $model->aux_estudios[$key]['tipo'] = $estudio->id_tipo;
                                }
                            }
    
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    } 
                }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveMultiple($model,$param){
        //date_default_timezone_set('America/Monterrey');

        if(Yii::$app->user->identity->activo_eliminar != 2 || $model->scenario == 'create'){

            $id_estudios = [];

        //dd($model->aux_estudios);

        if(isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
            foreach($model->aux_estudios as $key => $estudio){

                
                if(isset($estudio['id']) && $estudio['id'] != null && $estudio['id'] != ''){
                    $t_e = Trabajadorestudio::find()->where(['id'=> $estudio['id']])->one();
                } else {
                    $t_e = new Trabajadorestudio();
                }

                //dd($t_e );
                //dd($estudio);

                if($estudio['tipo'] != null && $estudio['tipo'] != '' && $estudio['tipo'] != ' '){

                    if(isset($estudio['estudio']) || isset($estudio['otro'])){
                        
                        $t_e->id_trabajador = $model->id;
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
    
                        if(isset($nestudio)){
                            
                            $t_e->id_tipo = $estudio['tipo'];
                            $t_e->id_estudio = $nestudio->id;
                            $t_e->id_periodicidad = $estudio['periodicidad'];
                            $t_e->fecha_apartir = $estudio['fecha_apartir'];
                            $t_e->fecha_documento = $estudio['fecha_documento'];
    
                            if($estudio['fecha_vencimiento'] == 'INDEFINIDO' || $estudio['periodicidad'] == '0'){
                                $t_e->fecha_vencimiento = null;

                                if($estudio['fecha_apartir'] != null && $estudio['fecha_apartir'] != ''){
                                    //dd('hay fecha a partir: '.$estudio['fecha_apartir']);

                                    if($estudio['fecha_documento'] != null && $estudio['fecha_documento'] != '' && $estudio['fecha_documento'] != ' '){
                                        $t_e->status = 1; 
                                    } else{
                                        $date_hoy = date('Y-m-d');
                                        if($date_hoy > $estudio['fecha_apartir']){
                                            //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : YA VENCIO');
                                            $t_e->status = 2;
                                        } else{
                                            //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : SIGUE VIGENTE');
                                            $t_e->status = 1;
                                        }
                                    }
                                } else{
                                    $t_e->status = 0; 
                                }
                        
                            } else {
    
                                //STATUS 0 = INDEFINIDO, 1 = VIGENTE, 2 = VENCIDO
                                if($estudio['fecha_vencimiento'] == '' || $estudio['fecha_vencimiento'] == null){

                                   
                                    if($estudio['fecha_apartir'] != null && $estudio['fecha_apartir'] != ''){
                                        //dd('hay fecha a partir: '.$estudio['fecha_apartir']);
                                        
                                        $date_hoy = date('Y-m-d');
                                        if($date_hoy > $estudio['fecha_apartir']){
                                            //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : YA VENCIO');
                                            $t_e->status = 2;
                                        } else{
                                            //dd('Hoy: '.$date_hoy.' | Fecha a partir: '.$estudio['fecha_apartir'].' - : SIGUE VIGENTE');
                                            $t_e->status = 0;
                                        }
                                    } else{
                                        $t_e->status = 0; 
                                    }
                                } else{
                                    $t_e->fecha_vencimiento = $estudio['fecha_vencimiento'];
                                    $date_hoy = date('Y-m-d');
                                    $date_vencimiento = $estudio['fecha_vencimiento'];
        
                                    if($date_hoy > $date_vencimiento){
                                        $t_e->status = 2;
                                    } else {
                                        $t_e->status = 1;
                                    }
                                }
                            }
                            $t_e->conclusion = $estudio['conclusion'];
                            
                            $t_e->save();
                            //dd($t_e);
                            if($t_e){
                                array_push($id_estudios, $t_e->id);
                            }
    
                            if('aux_estudios' . '[' . $key . '][' . 'evidencia' . ']' != ""){
                   
                                $archivo = 'aux_estudios' . '[' . $key . '][' . 'evidencia' . ']';
                                $save_archivo = UploadedFile::getInstance($model, $archivo);
                                if (!is_null($save_archivo)) {
        
                                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/';
                                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                                    $this->actionCarpetas( $directorios);
        
                                    $ruta_link='resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/';
                                    /* if($t_e->evidencia != null || $t_e->evidencia != ''){
                                        $link = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$t_e->evidencia;
                                        if(file_exists($link)){
                                            unlink('resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$t_e->evidencia);
                                        }
                                    } */
        
                                    $ruta_archivo = $t_e->id_estudio .'_'.date('YmdHis'). '.' . $save_archivo->extension;
                                    $save_archivo->saveAs($directorios[4] . '/' . $ruta_archivo);
                                    $t_e->evidencia= $ruta_archivo;



                                    //Agregar al historial solo si hay evidencia nueva
                                    $historial = new Historialdocumentos();
                                    $historial->id_empresa = $model->id_empresa;
                                    $historial->id_trabajador = $model->id;
                                    $historial->id_tipo = $t_e->id_tipo;
                                    $historial->id_estudio = $t_e->id_estudio;
                                    $historial->id_periodicidad =  $t_e->id_periodicidad;
                                    $historial->fecha_documento = $t_e->fecha_documento;
                                    $historial->fecha_vencimiento = $t_e->fecha_vencimiento;
                                    $historial->evidencia = $ruta_link.$t_e->evidencia;

                                    $historial->update_date = date('Y-m-d');
                                    $historial->update_user = Yii::$app->user->identity->id;
                                    $historial->save();
                                    
                                }
                                $t_e->save();


                                $t_e->date_update = date('Y-m-d');
                                $t_e->hour_update = date('H:i');
                                $t_e->save();
                            }
    
                            if ( ($t_e->evidencia == null || $t_e->evidencia == '') && $t_e->status != 2){
                                $t_e->status = 0;
                                $t_e->save();
                            }
                        }
                        
                    }

                }

            }
        }

        $deletes = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }


        //STATUS 0 => PENDIENTE, 1 = CUMPLE, 2 = NO CUMPLE
        $status_documentos = 0;
        $total_estudios = count($model->testudios);
        $cumplen = 0;
        $nocumplen = 0;
        $pendientes = 0;

        $estudioscheck = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['status'=>1])->all();

        //dd($estudioscheck);

        //dd($total_estudios);
        if($estudioscheck){
            foreach($estudioscheck as $key=>$estudio){
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

        $model->status_documentos = $status_documentos;
        $model->save();

        }


        $id_maquinas = [];
        if(isset($model->maquina_select) && $model->maquina_select != null && $model->maquina_select != ''){
            foreach($model->maquina_select as $key => $maquina){

                $maq = Trabajadormaquina::find()->where(['id_trabajador'=> $model->id])->andWhere(['id_maquina'=> $maquina])->one();
                if($maq){
                    
                } else {
                    $maq = new Trabajadormaquina();
                }
                $maq->id_trabajador = $model->id;
                $maq->id_maquina = $maquina;
                $maq->status = 1;
               
                $maq->create_date = date('Y-m-d H:i:s');
                $maq->create_user = Yii::$app->user->identity->id;
                $maq->save();

                if($maq){
                    array_push($id_maquinas, $maq->id);
                }

            }
        }
        $deletes = Trabajadormaquina::find()->where(['id_trabajador'=>$model->id])->andWhere(['not in','id',$id_maquinas])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

        //dd('Total estudios: '.$total_estudios.' | Cumplen: '.$cumplen.' | No Cumplen: '.$nocumplen.' | Pendientes: '.$pendientes.' | STATUS DOCUMENTOS: '.$status_documentos);
    }

    /**
     * Deletes an existing Trabajadores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id,$company=null,$page=null)
    {
        $model = $this->findModel($id);

        if($model){
            $model->status_backup = $model->status;
            $model->status = 5;
            $model->status_baja = 1;
            $model->save(false);
            //dd($model);

            $this->saveTrash($model,'Trabajadores');

            $this->actionBajatrabajador($model);
        }

        if($page != null && $page != '' && $page != ' '){
            $page = $page +1;
        }

        $url = Url::to(['index', 'id_empresa' => $company,'page'=>$page]);
        return $this->redirect($url);

        //return $this->redirect(['index']);
    }


    public function saveTrash($model,$tipo_modelo){
        $modelos = [
            '0' => 'AlergiaTrabajador',
            '1' => 'Almacen',
            '2' => 'Areas',
            '3' => 'Areascuestionario',
            '4' => 'Bitacora',
            '5' => 'Cargasmasivas',
            '6' => 'Configconsentimientos',
            '7' => 'Configuracion',
            '8' => 'Consentimientos',
            '9' => 'Consultas',
            '10' => 'Consultorios',
            '11' => 'ContactForm',
            '12' => 'Cuestionario',
            '13' => 'DetalleCuestionario',
            '14' => 'Detallehc',
            '15' => 'Detallemovimiento',
            '16' => 'Detalleordenpoe',
            '17' => 'Diagnosticoscie',
            '18' => 'Documentacion',
            '19' => 'DocumentoTrabajador',
            '20' => 'Empresamails',
            '21' => 'Empresas',
            '22' => 'Epps',
            '23' => 'Estudios',
            '24' => 'ExtraccionBd',
            '25' => 'Firmaempresa',
            '26' => 'Firmas',
            '27' => 'Hccohc',
            '28' => 'Hccohcestudio',
            '29' => 'Historialdocumentos',
            '30' => 'Historicoes',
            '31' => 'Insumos',
            '32' => 'Insumostockmin',
            '33' => 'Lineas',
            '34' => 'LoginForm',
            '35' => 'Lotes',
            '36' => 'Mantenimientoactividad',
            '37' => 'Mantenimientocomponentes',
            '38' => 'Mantenimientos',
            '39' => 'Maquinaria',
            '40' => 'Maquinariesgo',
            '41' => 'Medidas',
            '42' => 'Movimientos',
            '43' => 'NivelOrganizacional1',
            '44' => 'NivelOrganizacional2',
            '45' => 'NivelOrganizacional3',
            '46' => 'NivelOrganizacional4',
            '47' => 'Notification',
            '48' => 'Ordenespoes',
            '49' => 'Ordenpoetrabajador',
            '50' => 'Paisempresa',
            '51' => 'Paises',
            '52' => 'Parametros',
            '53' => 'Poeestudio',
            '54' => 'Poes',
            '55' => 'Preguntas',
            '56' => 'Presentaciones',
            '57' => 'Programaempresa',
            '58' => 'ProgramaSalud',
            '59' => 'Programasaludempresa',
            '60' => 'ProgramaTrabajador',
            '61' => 'PuestoEpp',
            '62' => 'PuestoEstudio',
            '63' => 'Puestoparametro',
            '64' => 'PuestoRiesgo',
            '65' => 'Puestostrabajo',
            '66' => 'Puestotrabajador',
            '67' => 'Requerimientoempresa',
            '68' => 'Riesgos',
            '69' => 'Roles',
            '70' => 'Rolpermiso',
            '71' => 'Secciones',
            '72' => 'Servicios',
            '73' => 'SolicitudesDelete',
            '74' => 'TipoCuestionario',
            '75' => 'TipoServicios',
            '76' => 'Trabajadorepp',
            '77' => 'Trabajadores',
            '78' => 'Trabajadorestudio',
            '79' => 'Trabajadormaquina',
            '80' => 'Trabajadorparametro',
            '81' => 'Trashhistory',
            '82' => 'Turnopersonal',
            '83' => 'Turnos',
            '84' => 'Ubicaciones',
            '84' => 'Unidades',
            '85' => 'Usuarios',
            '86' => 'Vacantes',
            '87' => 'Vacunacion',
            '88' => 'Vacantetrabajador',
            '89' => 'Vacunacion',
            '90' => 'Viasadministracion',
            '91' => 'Vigencias'
          
        ];

        $modelos2 = [
            'AlergiaTrabajador' => 'AlergiaTrabajador',
            'Almacen' => 'Almacen',
            'Areas' => 'Areas',
            'Areascuestionario' => 'Areascuestionario',
            'Bitacora' => 'Bitacora',
            'Cargasmasivas' => 'Cargasmasivas',
            'Configconsentimientos' => 'Configconsentimientos',
            'Configuracion' => 'Configuracion',
            'Consentimientos' => 'Consentimientos',
            'Consultas' => 'Consultas',
            'Consultorios' => 'Consultorios',
            'ContactForm' => 'ContactForm',
            'Cuestionario' => 'Cuestionario',
            'DetalleCuestionario' => 'DetalleCuestionario',
            'Detallehc' => 'Detallehc',
            'Detallemovimiento' => 'Detallemovimiento',
            'Detalleordenpoe' => 'Detalleordenpoe',
            'Diagnosticoscie' => 'Diagnosticoscie',
            'Documentacion' => 'Documentacion',
            'DocumentoTrabajador' => 'DocumentoTrabajador',
            'Empresamails' => 'Empresamails',
            'Empresas' => 'Empresas',
            'Epps' => 'Epps',
            'Estudios' => 'Estudios',
            'ExtraccionBd' => 'ExtraccionBd',
            'Firmaempresa' => 'Firmaempresa',
            'Firmas' => 'Firmas',
            'Hccohc' => 'Hccohc',
            'Hccohcestudio' => 'Hccohcestudio',
            'Historialdocumentos' => 'Historialdocumentos',
            'Historicoes' => 'Historicoes',
            'Insumos' => 'Insumos',
            'Insumostockmin' => 'Insumostockmin',
            'Lineas' => 'Lineas',
            'LoginForm' => 'LoginForm',
            'Lotes' => 'Lotes',
            'Mantenimientoactividad' => 'Mantenimientoactividad',
            'Mantenimientocomponentes' => 'Mantenimientocomponentes',
            'Mantenimientos' => 'Mantenimientos',
            'Maquinaria' => 'Maquinaria',
            'Maquinariesgo' => 'Maquinariesgo',
            'Medidas' => 'Medidas',
            'Movimientos' => 'Movimientos',
            'NivelOrganizacional1' => 'NivelOrganizacional1',
            'NivelOrganizacional2' => 'NivelOrganizacional2',
            'NivelOrganizacional3' => 'NivelOrganizacional3',
            'NivelOrganizacional4' => 'NivelOrganizacional4',
            'Notification' => 'Notification',
            'Ordenespoes' => 'Ordenespoes',
            'Ordenpoetrabajador' => 'Ordenpoetrabajador',
            'Paisempresa' => 'Paisempresa',
            'Paises' => 'Paises',
            'Parametros' => 'Parametros',
            'Poeestudio' => 'Poeestudio',
            'Poes' => 'Poes',
            'Preguntas' => 'Preguntas',
            'Presentaciones' => 'Presentaciones',
            'Programaempresa' => 'Programaempresa',
            'ProgramaSalud' => 'ProgramaSalud',
            'Programasaludempresa' => 'Programasaludempresa',
            'ProgramaTrabajador' => 'ProgramaTrabajador',
            'PuestoEpp' => 'PuestoEpp',
            'PuestoEstudio' => 'PuestoEstudio',
            'Puestoparametro' => 'Puestoparametro',
            'PuestoRiesgo' => 'PuestoRiesgo',
            'Puestostrabajo' => 'Puestostrabajo',
            'Puestotrabajador' => 'Puestotrabajador',
            'Requerimientoempresa' => 'Requerimientoempresa',
            'Riesgos' => 'Riesgos',
            'Roles' => 'Roles',
            'Rolpermiso' => 'Rolpermiso',
            'Secciones' => 'Secciones',
            'Servicios' => 'Servicios',
            'SolicitudesDelete' => 'SolicitudesDelete',
            'TipoCuestionario' => 'TipoCuestionario',
            'TipoServicios' => 'TipoServicios',
            'Trabajadorepp' => 'Trabajadorepp',
            'Trabajadores' => 'Trabajadores',
            'Trabajadorestudio' => 'Trabajadorestudio',
            'Trabajadormaquina' => 'Trabajadormaquina',
            'Trabajadorparametro' => 'Trabajadorparametro',
            'Trashhistory' => 'Trashhistory',
            'Turnopersonal' => 'Turnopersonal',
            'Turnos' => 'Turnos',
            'Ubicaciones' => 'Ubicaciones',
            'Unidades' => 'Unidades',
            'Usuarios' => 'Usuarios',
            'Vacantes' => 'Vacantes',
            'Vacunacion' => 'Vacunacion',
            'Vacantetrabajador' => 'Vacantetrabajador',
            'Vacunacion' => 'Vacunacion',
            'Viasadministracion' => 'Viasadministracion',
            'Vigencias' => 'Vigencias'
          
        ];

        date_default_timezone_set('America/Costa_Rica');
        $trash = new Trashhistory();
        $trash->model = $tipo_modelo;
        $trash->id_model = $model->id;
        $trash->id_empresa = $model->id_empresa;
        $trash->deleted_date = date('Y-m-d H:i:s');
        $trash->deleted_user = Yii::$app->user->identity->id;
        $trash->save();

        if($model){
            $trash->contenido = $model->nombre.' '.$model->apellidos;
            $trash->save();
        }
        //dd($trash);

    }


    public function actionRefresh()
    {
        $trabajadores = Trabajadores::find()->all();

        foreach($trabajadores as $key =>$model){
            $fecha = $model->fecha_nacimiento;

            if($fecha != null && $fecha != '' && $fecha != ' '){
                try {
                    $edad= $this->actionCalculateedad($fecha);
                    $model->edad = $edad;
                    $model->save(false);
                    //dd('Fecha nacimiento: '.$fecha,'Edad: '.$model->edad,'Edad calculada: '.$edad);
                } catch (\Throwable $th) {
                    dd($model);
                }
                
            }

            if(isset($model->fecha_contratacion) && $model->fecha_contratacion != null && $model->fecha_contratacion != ''){
                
                try {
                    $resultado_antiguedad = $this->actionCalculateantig($model->fecha_contratacion,$model);
                
                    $model->antiguedad = $resultado_antiguedad[0];
                    $model->antiguedad_anios = $resultado_antiguedad[1];
                    $model->antiguedad_meses = $resultado_antiguedad[2];
                    $model->antiguedad_dias = $resultado_antiguedad[3];
                    $model->save(false);
                } catch (\Throwable $th) {
                    dd($model);
                }
                
            }
            
            
        }

        return $this->redirect(['index']);
    }


    public function actionRefreshpoes()
    {
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->empresa_all != 1) {
            $trabajadores = ArrayHelper::map(Trabajadores::find()->where(['in', 'id_empresa', $empresas])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id'])->all(), 'id', 'id');
        } else {
            $trabajadores = ArrayHelper::map(Trabajadores::find()->where(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id'])->all(), 'id', 'id');
        }

        if($trabajadores){
            foreach($trabajadores as $key=>$id_trabajador){
                //'1'=>'SI','2'=>'NO','3'=>'SIN REGISTROS'
                $poes_trabajador = Poes::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['<>','status',2])->andWhere(['IS', 'status_entrega', new \yii\db\Expression('NULL')])->all();
                $estudios_pendientes = null;
                if($poes_trabajador){
                        $estudios_pendientes = 1;
                } else {
                    $poes_trabajador = Poes::find()->where(['id_trabajador'=>$id_trabajador])->andWhere(['<>','status',2])->all();
                    if($poes_trabajador){
                        $estudios_pendientes = 2;
                    } else {
                        $estudios_pendientes = 3;
                    }
                        
                }
                $trabajador = Trabajadores::findOne($id_trabajador);
                if($trabajador){
                    $trabajador->estudios_pendientes = $estudios_pendientes;
                    $trabajador->save();
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionBajatrabajador($model)
    {
        //dd('entra');
        $historias = Hccohc::find()->where(['id_trabajador'=>$model->id])->all();
        //dd($historias);

        $borrar = true;
        if($historias){
            foreach($historias as $key=>$historia){
                if($historia->status != 0){
                    $borrar = false;
                }
            }

            if($borrar == true){
                foreach($historias as $key=>$historia){
                    $historia->status_baja = 2;
                    $historia->save();
                }
            }
        }


        $usuarios = Usuariotrabajador::find()->where(['id_trabajador'=>$model->id])->all();
        if($usuarios){
            foreach($usuarios as $key=>$usuario){
                if($usuario->usuario){
                    $baja = $usuario->usuario;
                    $baja->status = 2;
                    $baja->save(false);
                }
                $usuario->delete_date = date('Y-m-d');
                $usuario->status = 2;
                $usuario->save(false);
            }
        }

    }

    /**
     * Creates a new Trabajadores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCarga()
    {
        $model = new Trabajadores();
        //date_default_timezone_set('America/Mazatlan');
        $model->scenario = 'carga';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $request = Yii::$app->request;

                $archivo = UploadedFile::getInstance($model,'file_excel');
                //dd($archivo);
                $dir0 = __DIR__ . '/../web/cargas/';
                $directorios = ['0'=>$dir0];
                
                $this->actionCarpetas( $directorios);
                
                if($archivo){
                    $nombre_archivo = 'CARGAMASIVA_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                } 

                
                if($archivo){
                   
                    define('UPLOAD_DIR', '../web/cargas/');
                    $convertir = UPLOAD_DIR . $nombre_archivo;
                    $fn = fopen($convertir,"r");
                    
                    while(! feof($fn))  {
                    
                        $result = fgets($fn);
                        $result = trim($result);
                        $result = str_replace("  "," ",$result);
                    
                        $final = "";
                    
                        $palabras = explode(",", $result);
                        //dd($palabras);
                        
                        //ATRIBUTOS TRABAJADOR
                        //obligatorios 4 atributos
                        $nombre = '';
                        $apellidos = '';
                        $sexo = '';
                        $fecha_nacimiento = '';
                        $edad = '';
                        
                        $num_trabajador = '';
                        $num_imss = '';
                        $celular = '';
                        $contacto_emergencia = '';
                        $direccion = '';
                        $colonia = '';
                        $ciudad = '';
                        $municipio = '';
                        $estado = '';
                        $pais = '';
                        $cp = '';
                        $fecha_contratacion = '';
                        $estado_civil = '';
                        $nivel_lectura = '';
                        $nivel_escritura = '';
                        $escolaridad = '';
                        $ruta = '';
                        $parada = '';
                        $area = '';
                        $puesto = '';
                        $teamleader = '';
                        $fecha_inicio = '';
                        //dd($palabras);
                        
                        foreach ($palabras as $key => $palabra) {
                            $check = trim($palabra);
                            $check = str_replace("  "," ",$check);
                            $check = utf8_encode($check);
                            
                        
                            if($key == 0){
                                $nombre = trim($check);
                                $nombre = $this->remove_utf8_bom($nombre);
                                $nombre = trim($nombre);
                                //$nombre = strtoupper($nombre);
                            } else if($key == 1){
                                $apellidos = trim($check);
                                //$apellidos = strtoupper($apellidos);
                            } else if($key == 2){
                                $sexo = trim($check);
                                //$sexo = strtoupper($sexo);
            
                                if($sexo == 'MASCULINO'){
                                    $sexo = 1;
                                } else if($sexo == 'FEMENICO'){
                                    $sexo = 2;
                                } else if($sexo == 'OTRO'){
                                    $sexo = 3;
                                }
                            } else if($key == 3){
                                $edad = '';
                                $dateOfBirth = trim($palabra);
                                $fecha_nacimiento = $dateOfBirth;
                                $today = date("Y-m-d");
                                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                                $edad = $diff->format('%y');
                                $edad = intval($edad);
                            } else if($key > 3){
                                $index = $key-3;
                                $nombre_extra = 'extra'.$index;

                                $extra = trim($check);
                                $extra = $this->remove_utf8_bom($extra);
                                $extra = trim($extra);

                                //dd($extra);

                                //Solo empezar a checar si es que seleccionaron columna extra, si no ignorar
                                if(isset($model->$nombre_extra)){
                                    if($model->$nombre_extra == 1){//N° Trabajador
                                        $num_trabajador = ''.$extra;
                                    } else if($model->$nombre_extra == 2){//N° IMSS
                                        $num_imss = ''.$extra;
                                    } else if($model->$nombre_extra == 3){//Célular
                                        $celular = ''.$extra;
                                    } else if($model->$nombre_extra == 4){//Contacto Emergencia
                                        $contacto_emergencia = ''.$extra;
                                    } else if($model->$nombre_extra == 5){//Dirección
                                        $direccion = ''.$extra;
                                    } else if($model->$nombre_extra == 6){//Colonia
                                        $colonia = ''.$extra;
                                    } else if($model->$nombre_extra == 7){//Ciudad
                                        $ciudad = ''.$extra;
                                    } else if($model->$nombre_extra == 8){//Municipio
                                        $municipio = ''.$extra;
                                    } else if($model->$nombre_extra == 9){//Estado
                                        $estado = ''.$extra;
                                    } else if($model->$nombre_extra == 10){//País
                                        $pais = ''.$extra;
                                    } else if($model->$nombre_extra == 11){//CP.
                                        $cp = ''.$extra;
                                    } else if($model->$nombre_extra == 12){//Fecha Contratacion
                                        $fecha_contratacion = $extra;
                                    } else if($model->$nombre_extra == 13){//Estado Civil
                                        if($extra == 'SOLTERO'){
                                            $estado_civil = 1;
                                        } else if($extra == 'CASADO'){
                                            $estado_civil = 2;
                                        } else if($extra == 'VIUDO'){
                                            $estado_civil = 3;
                                        }
                                    } else if($model->$nombre_extra == 14){//Nivel Lectura
                                        if($extra == 'BUENO'){
                                            $nivel_lectura = 1;
                                        } else if($extra == 'REGULAR'){
                                            $nivel_lectura = 2;
                                        } else if($extra == 'MALO'){
                                            $nivel_lectura = 3;
                                        }
                                    } else if($model->$nombre_extra == 15){//Nivel Escritura
                                        if($extra == 'BUENO'){
                                            $nivel_escritura = 1;
                                        } else if($extra == 'REGULAR'){
                                            $nivel_escritura = 2;
                                        } else if($extra == 'MALO'){
                                            $nivel_escritura = 3;
                                        }
                                    } else if($model->$nombre_extra == 16){//Escolaridad
                                        if($extra == 'PRIMARIA'){
                                            $escolaridad = 1;
                                        } else if($extra == 'SECUNDARIA'){
                                            $escolaridad = 2;
                                        } else if($extra == 'PREPARATORIA'){
                                            $escolaridad = 3;
                                        } else if($extra == 'CARRERA TÉCNICA'){
                                            $escolaridad = 4;
                                        } else if($extra == 'LICENCIATURA'){
                                            $escolaridad = 5;
                                        } else if($extra == 'MAESTRIA'){
                                            $escolaridad = 6;
                                        } else if($extra == 'DOCTORADO'){
                                            $escolaridad = 7;
                                        }
                                    } else if($model->$nombre_extra == 17){//Ruta
                                        $ruta = ''.$extra;
                                    } else if($model->$nombre_extra == 18){//Parada
                                        $parada = ''.$extra;
                                    } else if($model->$nombre_extra == 19){//Área
                                        $areast = Areas::find()->where(['like','area',$extra])->one();
                                        if($puestot){
                                            $area =$areast->id;
                                        }
                                    } else if($model->$nombre_extra == 20){//Puesto
                                        $puestot = Puestostrabajo::find()->where(['like','nombre',$extra])->one();
                                        if($puestot){
                                            $puesto =$puestot->id;
                                        }
                                    } else if($model->$nombre_extra == 21){//Teamleader
                                        $teamleader = ''.$extra;
                                    } else if($model->$nombre_extra == 22){//Fecha Inicio
                                        $fecha_inicio = $extra;
                                    }
                                }

                            }
                        }
                        //dd('Nombre:'.$nombre.' | Apellidos:'.$apellidos.' | Sexo:'.$sexo.' | Fecha Nacimiento:'.$fecha_nacimiento.' | Edad:'.$edad);
                        if(isset($nombre) && $nombre != '' && isset($apellidos) && $apellidos != '' && isset($sexo) && $sexo != '' && isset($fecha_nacimiento) && $fecha_nacimiento != '' && isset($edad) && $edad != ''){
                            $trabajador = new Trabajadores();
                            $trabajador->id_empresa = $model->id_empresa;
                            $trabajador->nombre = $nombre;
                            $trabajador->apellidos = $apellidos;
                            $trabajador->sexo = $sexo;
                            $trabajador->fecha_nacimiento = $fecha_nacimiento;
                            $trabajador->edad = $edad;
                            //date_default_timezone_set('America/Mazatlan');
                            $trabajador->status = 1;
                            $trabajador->save();

                            //CAMPOS EXTRA NO OBLIGATORIOS-------------------------
                            $trabajador->num_trabajador = $num_trabajador;
                            $trabajador->num_imss  = $num_imss;
                            $trabajador->celular = $celular;
                            $trabajador->contacto_emergencia = $contacto_emergencia;
                            $trabajador->direccion = $direccion;
                            $trabajador->colonia = $colonia;
                            $trabajador->ciudad = $ciudad;
                            $trabajador->municipio = $municipio;
                            $trabajador->estado = $estado;
                            $trabajador->pais = $pais;
                            $trabajador->cp = $cp;
                            $trabajador->fecha_contratacion = $fecha_contratacion;
                            $trabajador->estado_civil = $estado_civil;
                            $trabajador->nivel_lectura  = $nivel_lectura;
                            $trabajador->nivel_escritura = $nivel_escritura;
                            $trabajador->escolaridad  = $escolaridad;
                            $trabajador->ruta = $ruta;
                            $trabajador->parada = $parada;
                            $trabajador->id_area = $area;
                            $trabajador->id_puesto = $puesto;
                            $trabajador->teamleader = $teamleader;
                            $trabajador->fecha_iniciop = $fecha_inicio;

                            try {
                                $trabajador->save();
                            } catch (\Throwable $th) {
                                dd($th);
                            }
                            
                            //CAMPOS EXTRA NO OBLIGATORIOS-------------------------

                            if($trabajador){
                                $hc = new Hccohc();
                                $hc->id_trabajador = $trabajador->id;
                                $hc->id_empresa = $trabajador->id_empresa;
                                $hc->fecha = date('Y-m-d');
                                $hc->examen = 1;
                                $hc->nombre = $trabajador->nombre;
                                $hc->apellidos = $trabajador->apellidos;
                                $hc->sexo = $trabajador->sexo;
                                $hc->fecha_nacimiento = $trabajador->fecha_nacimiento;
                                $hc->edad = $trabajador->edad;
                                $hc->nivel_lectura = $trabajador->nivel_lectura;
                                $hc->nivel_escritura = $trabajador->nivel_escritura;
                                $hc->estado_civil = $trabajador->estado_civil;
                                $hc->area = $trabajador->id_area;
                                $hc->puesto = $trabajador->id_puesto;
                                $hc->status = 0;
                                $hc->save();
                            }
                        }
                    }
                }
                //$param = $request->getBodyParam("Trabajadores");
                return $this->redirect(['trabajadores/index']);
                
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('carga', [
            'model' => $model,
        ]);
    }

    function remove_utf8_bom($text){
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    function utf8_for_xml($string)
    {
        return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u',
                        ' ', $string);
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


    /**
     * Creates a new Consultas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreateincapacidad()
    {
        $model = new Consultas();
        //date_default_timezone_set('America/Monterrey');
        $model->fecha = date('Y-m-d');
        $model->hora_inicio = date('H:i');
        $model->scenario = 'incapacidad';
        $model->status = 1;
        $model->solicitante = 1;
        $model->tipo = 4;
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);


        if ($this->request->isPost) {

            //dd('hello');
            if ($model->load($this->request->post())) {
                
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Consultas");
                $model->id_empresa = $param['id_empresa'];
                $model->solicitante = $param['solicitante'];
                $model->fecha = $param['fecha'];
                $model->hora_inicio = $param['hora_inicio'];
                $model->envia_empresa = $param['envia_empresa'];
                $model->tipo = $param['tipo'];
                $model->id_trabajador = $param['id_trabajador'];
                $model->nombre = $param['nombre'];
                $model->apellidos = $param['apellidos'];
                $model->empresa = $param['empresa'];
                $model->id_consultorio = $param['id_consultorio'];
                $model->envia_form = $param['envia_form'];
                $model->visita = $param['visita'];
                $model->folio = $param['folio'];
                $model->edad = $param['edad'];
                $model->num_imss = $param['num_imss'];
                $model->num_trabajador = $param['num_trabajador'];
                $model->puesto = $param['puesto'];
                $model->incapacidad_folio = $param['incapacidad_folio'];
                $model->incapacidad_tipo = $param['incapacidad_tipo'];
                $model->incapacidad_ramo = $param['incapacidad_ramo'];
                $model->incapacidad_fechainicio = $param['incapacidad_fechainicio'];
                $model->incapacidad_dias = $param['incapacidad_dias'];
                $model->incapacidad_fechafin = $param['incapacidad_fechafin'];
                $model->diagnostico = $param['diagnostico'];
                $model->file_evidencia = $param['file_evidencia'];
                $model->resultado = $param['resultado'];
                $model->tipo_padecimiento = $param['tipo_padecimiento'];
                $model->firma = $param['firma'];
                $model->firmatxt = $param['firmatxt'];
                //dd($request);
                
                if($model->envia_form == '1'){
                    $model->create_date = date('Y-m-d');
                    $model->create_user = Yii::$app->user->identity->id;
                    $model->testfirma = $model->firma;
                    $model->firma = null;
                    //dd($model);

                    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                    if($empresa){
                        $model->id_empresa =  $empresa->id;
                        $model->empresa =  $empresa->comercial;
                    }
                    
    
                    $archivo = UploadedFile::getInstance($model,'file_evidencia');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador;
                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'CONSULTA_'.$model->id_trabajador.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                        $model->evidencia = $nombre_archivo; 
                        $model->save();
                    }
    
                    $model->hora_fin = date('H:i');
                    $model->save();
                    

                    //Guardar la firma del trabajador---------------------------------
                    if(isset($model->testfirma) && $model->testfirma != 'data:,'){
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/');
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                        $this->actionCarpetas($directorios);
                        $nombre_firma = $this->saveFirma($model->testfirma,$model);
                        $model->firma_ruta = $nombre_firma;
                        $model->save();
                    }
    
                    return $this->redirect(['incapacidades']);
                } else{
                    if($model->envia_empresa == '1'){

                        //dd('Entra aqui');
                        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                        $fecha = date('Ymd');

                        if($empresa){
                            $model->folio = $this->createClave('CM'.$empresa->abreviacion.$fecha,'app\models\Consultas');
                        
                            return $this->render('incapacidad', [
                                'model' => $model,
                            ]);
                        }
                    }
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('incapacidad', [
            'model' => $model,
        ]);
    }


        /**
     * Creates a new Consultas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionUpdateincapacidad($id)
    {
        $model = Consultas::find()->where(['id'=>$id])->one();
        //date_default_timezone_set('America/Monterrey');
        //dd($model);

        if(!isset($model->fecha) || $model->fecha == null || $model->fecha == ''){
            $model->fecha = date('Y-m-d');
        }
        if(!isset($model->fecha) || $model->fecha == null || $model->fecha == ''){
            $model->hora_inicio = date('H:i'); 
        }
       
        $model->scenario = 'incapacidad';
        $model->status = 1;
        $model->solicitante = 1;
        $model->tipo = 4;

        if ($this->request->isPost) {

            //dd('hello');
            if ($model->load($this->request->post())) {
                
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Consultas");
                $model->id_empresa = $param['id_empresa'];
                $model->solicitante = $param['solicitante'];
                $model->fecha = $param['fecha'];
                $model->hora_inicio = $param['hora_inicio'];
                $model->envia_empresa = $param['envia_empresa'];
                $model->tipo = $param['tipo'];
                $model->id_trabajador = $param['id_trabajador'];
                $model->nombre = $param['nombre'];
                $model->apellidos = $param['apellidos'];
                $model->empresa = $param['empresa'];
                $model->id_consultorio = $param['id_consultorio'];
                $model->envia_form = $param['envia_form'];
                $model->visita = $param['visita'];
                $model->folio = $param['folio'];
                $model->edad = $param['edad'];
                $model->num_imss = $param['num_imss'];
                $model->num_trabajador = $param['num_trabajador'];
                $model->puesto = $param['puesto'];
                $model->incapacidad_folio = $param['incapacidad_folio'];
                $model->incapacidad_tipo = $param['incapacidad_tipo'];
                $model->incapacidad_ramo = $param['incapacidad_ramo'];
                $model->incapacidad_fechainicio = $param['incapacidad_fechainicio'];
                $model->incapacidad_dias = $param['incapacidad_dias'];
                $model->incapacidad_fechafin = $param['incapacidad_fechafin'];
                $model->diagnostico = $param['diagnostico'];
                $model->file_evidencia = $param['file_evidencia'];
                $model->resultado = $param['resultado'];
                $model->tipo_padecimiento = $param['tipo_padecimiento'];
                $model->firma = $param['firma'];
                $model->firmatxt = $param['firmatxt'];
                //dd($request);
                
                if($model->envia_form == '1'){

                    $status_anterior = intval($model->getOldAttribute('status'));
                    $status_actual = intval($model->status);
                    if($status_actual == 2){
                        if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){

                        } else {
                        $model->status = $status_anterior;
                        }
                    }
            
                    $model->update_date = date('Y-m-d');
                    $model->update_user = Yii::$app->user->identity->id;
                    $model->testfirma = $model->firma;
                    $model->firma = null;
                    //dd($model);

                    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                    if($empresa){
                        $model->id_empresa =  $empresa->id;
                        $model->empresa =  $empresa->comercial;
                    }
                    
    
                    $archivo = UploadedFile::getInstance($model,'file_evidencia');
                    $dir0 = __DIR__ . '/../web/resources/Empresas/';
                    $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                    $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                    $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador;
                    $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'CONSULTA_'.$model->id_trabajador.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[4].'/'. $nombre_archivo);
                        $model->evidencia = $nombre_archivo; 
                        $model->save();
                    }
    
                    //$model->hora_fin = date('H:i');
                    $model->save();

                    //Guardar la firma del trabajador---------------------------------
                    if(isset($model->testfirma) && $model->testfirma != 'data:,'){
                        define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/');
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                        $this->actionCarpetas($directorios);
                        $nombre_firma = $this->saveFirma($model->testfirma,$model);
                        $model->firma_ruta = $nombre_firma;
                        $model->save();
                    }

    
                    return $this->redirect(['incapacidades']);
                } else{
                    if($model->envia_empresa == '1'){

                        //dd('Entra aqui');
                        $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
                        $fecha = date('Ymd');

                        if($empresa){
                            $model->folio = $this->createClave('CM'.$empresa->abreviacion.$fecha,'app\models\Consultas');
                        
                            return $this->render('incapacidad', [
                                'model' => $model,
                            ]);
                        }
                    }
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('incapacidad', [
            'model' => $model,
        ]);
    }


    public function createClave($indicio,$modelo){
        $ultimo_guardado = $modelo::find()->orderBy(['id'=>SORT_DESC])->one();
       
        if($ultimo_guardado){
            $clave = $ultimo_guardado->folio;
            $parte_numerica =intval(str_replace ( $indicio,'',$clave));
            $parte_numerica++;
            $clave_devuelta = $indicio.str_pad($parte_numerica, 6, "0", STR_PAD_LEFT);
        }else{
            $clave_devuelta = $indicio.'000001';
        }                      
        return $clave_devuelta;
    }

    public function actionCalculateedad($fecha){
        //dd('Fecha: '.$fecha);
        $resultado = '';

        $date = Carbon::parse($fecha.' 00:00:00');//Convertir a Carbon la fecha de contratación
        $now = Carbon::now();//Extraer la fecha actual, para calcular la diferencia
        
        $resultado = $date->diffInYears($now);
        
        return $resultado;
    }

    protected function saveFirma($firma,$model) {
        
        $img = $firma;
        $img = str_replace('data:image/png;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  uniqid() . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
    }

    public function actionCard($id)
    {
        //date_default_timezone_set('America/Mazatlan');
        $model = $this->findModel($id);

        $hay_medidas = false;
        $hay_perfil = false;
        $cuestionario = null;

        if($model->antropometrica){//OBTENER MEDIDAS ANTROPOMETRICAS MAS RECIENTES DEL TRABAJADOR
            $hay_medidas = true;
            $cuestionario = $model->antropometrica;
        }

        $perfil_worker = [];
        if($model->parametros){//OBTENER PARAMETROS DE PERFIL PSICOLOGICO DEL TRABAJADOR
            $hay_perfil = true;

            foreach($model->parametros as $key=>$param){
                $perfil_worker[$param->id_parametro]= $param->valor;
            }
            
        }

        $porcentaje_medidas = 0;//50% del nivel evaluarlo con las medidas antropométricas
        $porcentaje_psicometrico = 0;//50% del nivel evaluarlo con evaluacion psicometrica

        $medidas_trabajador = [];
        $psicologico_trabajador = [];

        $medidas_porcentaje = [];
        $psicologico_porcentaje = [];
        $puesto_porcentaje = [];


        foreach ($model->empresa->puestos as $key=>$puesto){
            $medidas_trabajador[$puesto->id] = 0;
            $medidas_porcentaje[$puesto->id] = 0;
            
            if($hay_medidas){
                //OBTENER RESULTADO DE LAS MEDIDAS ANTROPOMETRICAS---------------------------------------INICIO
                //dd($puesto);
                $medidas = [];
                $medidas[0]= null;
                $medidas[1]= null;
                $medidas[2]= null;
    
                if(isset($puesto->medida1) && $puesto->medida1 != null && $puesto->medida1 != '' && $puesto->medida1 != ' '){
    
                    if(isset($puesto->rango1desde) && $puesto->rango1desde != null && $puesto->rango1desde != '' && $puesto->rango1hasta != ' ' && isset($puesto->rango1hasta) && $puesto->rango1hasta != null && $puesto->rango1hasta != '' && $puesto->rango1hasta != ' '){
                        $desde = $puesto->rango1desde;
                        $hasta = $puesto->rango1hasta;
                        $intermedio = ($puesto->rango1hasta-$puesto->rango1desde)/2;
                        $intermedio = $desde + $intermedio;
                        $valor = null;
    
                        //Obtener la medida del trabajador
                        $c_medida1 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida1])->one();
                        
                        if($c_medida1){
                            $resultado = 0;
                            $valor = $c_medida1->respuesta_1;
    
                            if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                $resultado = 100;
                            } else{
                                if($valor > $hasta){
                                    $resultado = ($valor*100)/$intermedio;
                                    $resultado = $resultado - 100;
                                    $resultado = 100 - $resultado;
                                } else if($valor < $desde){
                                    $resultado = ($valor*100)/$intermedio;
                                }
                            }
                            $resultado = round($resultado, 2);
                            //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                            $medidas[0]= $resultado;
                        }
                    }
                    
                }
    
                if(isset($puesto->medida2) && $puesto->medida2 != null && $puesto->medida2 != '' && $puesto->medida2 != ' '){
    
                    if(isset($puesto->rango2desde) && $puesto->rango2desde != null && $puesto->rango2desde != '' && $puesto->rango2hasta != ' ' && isset($puesto->rango2hasta) && $puesto->rango2hasta != null && $puesto->rango2hasta != '' && $puesto->rango2hasta != ' '){
                        $desde = $puesto->rango2desde;
                        $hasta = $puesto->rango2hasta;
                        $intermedio = ($puesto->rango2hasta-$puesto->rango2desde)/2;
                        $intermedio = $desde + $intermedio;
                        $valor = null;
    
                        //Obtener la medida del trabajador
                        $c_medida2 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida2])->one();
                        //dd($c_medida2);
                        if($c_medida2){
                            $resultado = 0;
                            $valor = $c_medida2->respuesta_1;
    
                            if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                $resultado = 100;
                            } else{
                                if($valor > $hasta){
                                    $resultado = ($valor*100)/$intermedio;
                                    $resultado = $resultado - 100;
                                    $resultado = 100 - $resultado;
                                } else if($valor < $desde){
                                    $resultado = ($valor*100)/$intermedio;
                                }
                            }
                            $resultado = round($resultado, 2);
                            //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                            $medidas[1]= $resultado;
                        }
                    }
                    
                }
    
                if(isset($puesto->medida3) && $puesto->medida3 != null && $puesto->medida3 != '' && $puesto->medida3 != ' '){
    
                    if(isset($puesto->rango3desde) && $puesto->rango3desde != null && $puesto->rango3desde != '' && $puesto->rango3hasta != ' ' && isset($puesto->rango3hasta) && $puesto->rango3hasta != null && $puesto->rango3hasta != '' && $puesto->rango3hasta != ' '){
                        $desde = $puesto->rango3desde;
                        $hasta = $puesto->rango3hasta;
                        $intermedio = ($puesto->rango3hasta-$puesto->rango3desde)/2;
                        $intermedio = $desde + $intermedio;
                        $valor = null;
    
                        //Obtener la medida del trabajador
                        $c_medida3 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida3])->one();
                        //dd($c_medida2);
                        if($c_medida3){
                            $resultado = 0;
                            $valor = $c_medida3->respuesta_1;
    
                            if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                $resultado = 100;
                            } else{
                                if($valor > $hasta){
                                    $resultado = ($valor*100)/$intermedio;
                                    $resultado = $resultado - 100;
                                    $resultado = 100 - $resultado;
                                } else if($valor < $desde){
                                    $resultado = ($valor*100)/$intermedio;
                                }
                            }
    
                            $resultado = round($resultado, 2);
                            //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                            $medidas[2]= $resultado;
                        }
                    }
                    
                }
    
                $medidas_trabajador[$puesto->id] = $medidas;
                $percenfinal = 0;
    
                foreach($medidas as $key=>$medida){
                    $porcentajeindividual = ($medida * 33.33)/100;
                    $percenfinal += $porcentajeindividual;
                }
    
                $percenfinal = round($percenfinal, 2);
                $medidas_porcentaje[$puesto->id] = $percenfinal;
                //OBTENER RESULTADO DE LAS MEDIDAS ANTROPOMETRICAS---------------------------------------FIN
            
            }

            $psicologico_trabajador[$puesto->id] = 0;
            $psicologico_porcentaje[$puesto->id] = 0;
            if($hay_perfil){
                //OBTENER RESULTADO DEL PERFIL PSICOLÓGICO---------------------------------------INICIO
                $valor_maximo = 10;
                $sumatoria = 0;
                $parametros = [];

                
                foreach($puesto->parametros as $key=>$parametro){
                    $sumatoria += $parametro->valor;
                }
                
                if($puesto->parametros){
                    $parametromax = Puestoparametro::find()->where(['id_puesto'=>$puesto->id])->orderBy(['valor'=>SORT_DESC])->one();
                    if($parametromax){
                        $valor_maximo = $parametromax->valor;
                    }
                    foreach($puesto->parametros as $key=>$parametro){
                        $parametros[$key] = 0;
                        $resultado = 0;
                        $valor = 0;
                        $parametrovalor = $parametro->valor;
                        $porcentaje_parametro = ($parametrovalor*100)/$sumatoria;
                        $porcentaje_parametro = round($porcentaje_parametro, 2);

                        
                        $paramworker = Trabajadorparametro::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_parametro'=>$parametro->id_parametro])->one();
                        
                        if ($paramworker) {
                            $valor = $paramworker->valor;
                        }

                        if($valor>$parametrovalor){
                            $resultado = $porcentaje_parametro;
                        } else{
                            $resultado = ($valor*$porcentaje_parametro)/$parametrovalor;
                            $resultado = round($resultado, 2);
                        }

                        $parametros[$key]= $resultado;
                        //dd('Si existe el parametro: '.$parametro->id_parametro.'-'.$parametro->parametro->nombre.' | Valor Puesto: '.$parametrovalor.' | Valor Trabajador: '.$valor.' | Porcentaje Parámetro: '.$porcentaje_parametro);
                    }
                }

                $psicologico_trabajador[$puesto->id] = $parametros;
                $percenfinal = 0;
    
                foreach($parametros as $key=>$parametro){
                    $percenfinal += $parametro;
                }
    
                $percenfinal = round($percenfinal, 2);
                $psicologico_porcentaje[$puesto->id] = $percenfinal;
                 //OBTENER RESULTADO DEL PERFIL PSICOLÓGICO---------------------------------------FIN
                
            }
        }
        
        foreach($medidas_porcentaje as $key=>$porcentaje){
            $medidaantropo = $porcentaje;
            $perfilpsico = $psicologico_porcentaje[$key];
            $resultadofinal = ($medidaantropo+$perfilpsico)/2;
            $resultadofinal = round($resultadofinal, 2);
            $puesto_porcentaje[$key] = $resultadofinal;

            //dd('PUESTO: '.$key.' | MEDIDA: '.$medidaantropo.'%'.' | PSICOLOGICO: '.$perfilpsico.'% | RESULTADO: '.$resultadofinal);
        }

        

        arsort($puesto_porcentaje);


        return $this->renderAjax('card', [
            'model' => $model,
            'puestosordenados'=>$puesto_porcentaje,
            'medidas_porcentaje'=>$medidas_porcentaje,
            'psicologico_porcentaje'=>$psicologico_porcentaje,
        ]);
    }

    private function guardarParametros($model){
        try {
            if($model){
                $id_parametros = [];
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
                            $nuevotp = new Trabajadorparametro();  
                            if($psicologico['id'] == ""){
                                $nuevotp = new Trabajadorparametro();  
                            } else {
                                $nuevotp =  Trabajadorparametro::findOne($parametro['id']); 
                            }
                            //dd($nuevotp);
                            $nuevotp->id_trabajador = $model->id;
                            $nuevotp->id_parametro = $parametro->id;
                            $nuevotp->valor = $psicologico['valor'];
                            $nuevotp->save();
        
                            if($nuevotp){
                                array_push($id_parametros, $nuevotp->id);
                            }
                        }
                         
                    }
                    
                }
        
                //Borramos todos aquellos parametros que ya no estén activos
                $deletes = Trabajadorparametro::find()->where(['id_trabajador'=>$model->id])->andWhere(['not in','id',$id_parametros])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        

        return null;
    }


    public function actionCrearqr($model){
        date_default_timezone_set('America/Costa_Rica');
        $id_unique = ''.$model->id.$model->id_empresa.''.uniqid();
        $model->id_link = $id_unique;
        $model->save();

        //dd($id_unique);

        $dir_qr = __DIR__ . '/../web/qrs';
        $dir_qr2 = __DIR__ . '/../web/qrs/'.$model->id;
        $directorios = ['0'=>$dir_qr,'1'=>$dir_qr2];
        $this->actionCarpetas( $directorios);
        
        $dominio = 'https://www.dmm-smo.com';
        $dirqr = __DIR__ . '/../web/qrs/' . $model->id;

        if (!(file_exists( $dirqr . '/' . $model->id . '.png'))) {
            //dd('entra');
            $contenido = $dominio.'/web/index.php?r=trabajadores%2Fqr&id=' . $model->id_link;
            $ECClevel = 'M';
            $pixelSize = 10;
            $frameSize = 4;
            \QRcode::png($contenido, $dirqr . '/' . $model->id . 'qr.png', $ECClevel, $pixelSize, $frameSize);
        }

        return null;
                
    }

    public function actionLoadqr($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = Trabajadores::findOne($id);

        return $this->renderAjax('viewqr', [
            'model' => $model,
        ]);
    }

    public function actionQr($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = Trabajadores::find()->where(['id_link'=>$id])->one();
        $this->checarDocs($model);

        $session = Yii::$app->session;
        $session->set('trabajador', $model->id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($session['trabajador']);
                dd($model);
                
                return $this->redirect(['index']);
            }
        }

        return $this->render('qr', [
            'model'=>$model
        ]);
    }


    public function checarDocs($model){
        date_default_timezone_set('America/Costa_Rica');

        $index = 0;
        $id_estudiosactivos = [];
        if($model->empresa && $model->empresa->requisitosactivos){
            foreach($model->empresa->requisitosactivos as $key=>$requisito){
                $trabestudio = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_estudio'=>$requisito->id_estudio])->one();
                if($trabestudio){
                    $index ++;
                    $trabestudio->orden = $index;
                    $trabestudio->status_baja = null;
                    $trabestudio->save();
                    if($trabestudio){
                        if(!in_array($trabestudio->id, $id_estudiosactivos)){
                            array_push($id_estudiosactivos, $trabestudio->id);
                        }
                    }
                }
            }
        }

        //dd($model->puesto->pestudiosactivos);
        if($model->puesto && $model->puesto->pestudiosactivos){
            //dd($model->puesto->pestudiosactivos);
            foreach($model->puesto->pestudiosactivos as $key=>$requisito){
                $trabestudio = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_estudio'=>$requisito->id_estudio])->one();
                if($trabestudio){
                    $index ++;
                    $trabestudio->orden = $index;
                    $trabestudio->status_baja = null;
                    $trabestudio->save();
                    if($trabestudio){
                        if(!in_array($trabestudio->id, $id_estudiosactivos)){
                            array_push($id_estudiosactivos, $trabestudio->id);
                        }
                    }
                } else {
                    $trabestudio = new Trabajadorestudio();
                    $trabestudio->id_trabajador = $model->id;
                    $trabestudio->id_tipo = $requisito->id_tipo;
                    $trabestudio->id_estudio = $requisito->id_estudio;
                    $trabestudio->id_periodicidad = $requisito->periodicidad;
                    $trabestudio->fecha_apartir = $requisito->fecha_apartir;
                    $trabestudio->status = 0;
                    $index ++;
                    $trabestudio->orden = $index;
                    $trabestudio->status_baja = null;
                    $trabestudio->save();
                    if($trabestudio){
                        if(!in_array($trabestudio->id, $id_estudiosactivos)){
                            array_push($id_estudiosactivos, $trabestudio->id);
                        }
                    }
                }
            }
        }

        $estudiosdelete = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['not in','id',$id_estudiosactivos])->all();
        if($estudiosdelete){
            foreach($estudiosdelete as $key=>$estudio){
                $estudio->status_baja = 2;
                $estudio->save(false);
            }
        }
        
        $estudios = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['orden'=>SORT_ASC])->all();
        //dd($estudiosdelete,$estudios);
        foreach($estudios as $key => $estudio){

            if($estudio->fecha_vencimiento == 'INDEFINIDO' || $estudio->id_periodicidad == '0'){
                $estudio->fecha_vencimiento = null;

                if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){

                    if($estudio->fecha_documento != null && $estudio->fecha_documento != '' && $estudio->fecha_documento != ' '){
                        $estudio->status = 1; 
                    } else{
                        $date_hoy = date('Y-m-d');
                        if($date_hoy > $estudio->fecha_apartir){
                            if($estudio->evidencia == null || $estudio->evidencia == '' || $estudio->evidencia == ' '){
                                $estudio->status = 3;
                            } else {
                                $estudio->status = 2;
                            }
                            
                        } else{
                            $estudio->status = 1;
                        }
                    }
                
                } else {
                    $estudio->status = 0; 
                }
                        
            } else {
    
                //STATUS 0 = INDEFINIDO, 1 = VIGENTE, 2 = VENCIDO, 3 = SIN ARCHIVO
                if($estudio->fecha_vencimiento == '' || $estudio->fecha_vencimiento == null){
                    if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){
                                        
                        $date_hoy = date('Y-m-d');
                        if($date_hoy > $estudio->fecha_apartir){
                            
                            if($estudio->evidencia == null || $estudio->evidencia == '' || $estudio->evidencia == ' '){
                                $estudio->status = 3;
                            } else {
                                $estudio->status = 2;
                            }
                            
                        } else{
                            $estudio->status = 0;
                        }
                    } else{
                        $estudio->status = 0; 
                    }
                } else  {
                    $estudio->fecha_vencimiento = $estudio->fecha_vencimiento;
                    $date_hoy = date('Y-m-d');
                    $date_vencimiento = $estudio->fecha_vencimiento;
        
                    if($date_hoy > $date_vencimiento){
                        if($estudio->evidencia == null || $estudio->evidencia == '' || $estudio->evidencia == ' '){
                            $estudio->status = 3;
                        } else {
                            $estudio->status = 2;
                        }
                        
                    } else {
                        $estudio->status = 1;
                    }
                }
            }
            
            if($estudio->evidencia != null && $estudio->evidencia != '' && $estudio->evidencia != ' '){
                //Cuando hay evidencia 

            } else {
                $estudio->status = 3;
                $estudio->save(false);
            }

            $estudio->save(false);
        }

        //STATUS 0 => PENDIENTE, 1 = CUMPLE, 2 = NO CUMPLE
        $status_documentos = 0;
        $total_estudios = count($estudios);
        $cumplen = 0;
        $nocumplen = 0;
        $pendientes = 0;

        $estudioscheck = Trabajadorestudio::find()->where(['id_trabajador'=>$model->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

        //dd($estudioscheck);

        //dd($total_estudios);
        if($estudioscheck){
            foreach($estudioscheck as $key=>$estudio){
                if($estudio->status == 0){
                    $pendientes ++;
                } else if($estudio->status == 1){
                    $cumplen ++;
                } else if($estudio->status == 3){
                    $pendientes ++;
                } else if($estudio->status == 2){
                    $nocumplen ++;
                }
            }
        }

        //dd('$total_estudios: '.$total_estudios,'$pendientes: '.$pendientes,'$cumplen: '.$cumplen,'$nocumplen: '.$nocumplen);
        if ($total_estudios == $pendientes){
            $status_documentos = 0;
        } else if ($total_estudios == $cumplen){
            $status_documentos = 1;
        } else{
            $status_documentos = 2;
        }

        $model->status_documentos = $status_documentos;
        $model->save();
    }

    public function actionPrivacy()
    {
        return $this->render('aviso_privacidad', [
        ]);
    }


    public function actionConsentimiento($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Trabajadores::findOne($id);

        $image = 'resources/images/empty.png';
        
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
                'SetTitle' => 'CONSENTIMIENTO '.$model->nombre.' '.$model->apellidos.'.pdf',
                'SetSubject' => 'Consentimientos',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="'.$image.'"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
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


    public function actionGetnivelestrabajador($id)
    {
        $model = $this->findModel($id);
        $id_empresa = null;

        if($model){
            $id_empresa = $model->id_empresa;
            $historias = Hccohc::find()->where(['id_trabajador'=>$model->id])->all();
            if($historias){
                foreach($historias as $key=>$historia){
                    $historia->id_nivel1 = $model->id_nivel1;
                    $historia->id_nivel2 = $model->id_nivel2;
                    $historia->id_nivel3 = $model->id_nivel3;
                    $historia->id_nivel4 = $model->id_nivel4;
                    $historia->save(false);
                }
            }
            //dd($model);
        }

        return $this->redirect(['index','id_empresa'=>$id_empresa]);
    }


    public function getHistorialpuesto(){
        return null;
        $trabajadores_puesto = Trabajadores::find()->all();
        foreach($trabajadores_puesto as $key=>$trabajador){
            //dd($trabajador);

            if($trabajador->id_puesto != null && $trabajador->id_puesto != '' && $trabajador->id_puesto != ' '){
                $puestotrabajo = Puestotrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_puesto'=>$trabajador->id_puesto])->one();
                
                
                if(!$puestotrabajo){
                    
                    $puestotrabajo = new Puestotrabajador();
                    $puestotrabajo->id_trabajador = $trabajador->id;
                    $puestotrabajo->id_puesto = $trabajador->id_puesto;
                    $puestotrabajo->area = ''.$trabajador->id_area;
                    $puestotrabajo->fecha_inicio = $trabajador->fecha_iniciop;
                    $puestotrabajo->fecha_fin = $trabajador->fecha_finp;
                    $puestotrabajo->teamleader = $trabajador->teamleader;
                    $puestotrabajo->status = 1;
                    $puestotrabajo->save();


                    $puestooriginal = Puestostrabajo::findOne($trabajador->id_puesto);
                    if($puestooriginal){
                        $riesgopuesto = '';

                        $id_riesgos = [];

                        if($puestooriginal->riesgos){
                            foreach($puestooriginal->riesgos as $key2=>$riesgop){
                                $riesgopuesto .= $riesgop->id;
                                if($key2 < (count($puestooriginal->riesgos)-1)){
                                    $riesgopuesto .= ',';
                                }


                                $pr = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['id_riesgo'=>$riesgop->id])->one();
                                if(!$pr){
                                    $pr = new Puestotrabajohriesgo();
                                    $pr->id_puestotrabajador = $puestotrabajo->id;
                                    $pr->id_trabajador = $trabajador->id;
                                    $pr->id_puesto = $trabajador->id_puesto;
                                    $pr->id_riesgo = $riesgop->id;
                                    $pr->fecha_inicio = $trabajador->fecha_iniciop;
                                    $pr->create_date = date('Y-m-d');
                                    $pr->save();
                                } else {
                                    $pr->id_trabajador = $trabajador->id;
                                    $pr->fecha_inicio = $trabajador->fecha_iniciop;
                                    if($pr->create_date == null || $pr->create_date == '' || $pr->create_date == ' '){
                                        $pr->create_date = date('Y-m-d');
                                    }
                                    $pr->save();
                                }

                                if($pr){
                                    array_push($id_riesgos, $pr->id);
                                }
                            }
                            $puestotrabajo->riesgos = $riesgopuesto;
                            $puestotrabajo->save();
                        }

                        //Borramos todos aquellos riesgos que ya no estén activos
                        $deletes = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['not in','id',$id_riesgos])->all();
                        foreach($deletes as $delete){//Eliminar los que se hayan quitado
                            $delete->delete();
                        }
                    }
                } else {
                    $puestooriginal = Puestostrabajo::findOne($trabajador->id_puesto);
                    if($puestooriginal){
                        $riesgopuesto = '';

                        $id_riesgos = [];
                        
                        if($puestooriginal->riesgos){
                            foreach($puestooriginal->riesgos as $key2=>$riesgop){
                                $riesgopuesto .= $riesgop->id;
                                if($key2 < (count($puestooriginal->riesgos)-1)){
                                    $riesgopuesto .= ',';
                                }

                                $pr = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['id_riesgo'=>$riesgop->id])->one();
                                if(!$pr){
                                    $pr = new Puestotrabajohriesgo();
                                    $pr->id_puestotrabajador = $puestotrabajo->id;
                                    $pr->id_trabajador = $trabajador->id;
                                    $pr->id_puesto = $trabajador->id_puesto;
                                    $pr->id_riesgo = $riesgop->id;
                                    $pr->fecha_inicio = $trabajador->fecha_iniciop;
                                    $pr->create_date = date('Y-m-d');
                                    $pr->save();
                                } else {
                                    $pr->id_trabajador = $trabajador->id;
                                    $pr->fecha_inicio = $trabajador->fecha_iniciop;
                                    if($pr->create_date == null || $pr->create_date == '' || $pr->create_date == ' '){
                                        $pr->create_date = date('Y-m-d');
                                    }
                                    $pr->save();
                                }

                                if($pr){
                                    array_push($id_riesgos, $pr->id);
                                }
                            }
                            $puestotrabajo->riesgos = $riesgopuesto;
                            $puestotrabajo->save();

                            //Borramos todos aquellos riesgos que ya no estén activos
                            $deletes = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['not in','id',$id_riesgos])->all();
                            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                                $delete->delete();
                            }
                        }
                    }
                }
            }
        }

        dd('terminó');
    }


    public function guardarPuesto($trabajador,$id_puesto){
        $puestotrabajo = Puestotrabajador::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_puesto'=>$id_puesto])->one();
                      
        if(!$puestotrabajo){
                    
            $puestotrabajo = new Puestotrabajador();
            $puestotrabajo->id_trabajador = $trabajador->id;
            $puestotrabajo->id_puesto = $id_puesto;
            $puestotrabajo->area = ''.$trabajador->id_area;
            $puestotrabajo->fecha_inicio = $trabajador->fecha_iniciop;
            $puestotrabajo->fecha_fin = $trabajador->fecha_finp;
            $puestotrabajo->teamleader = $trabajador->teamleader;
            $puestotrabajo->status = 1;
            $puestotrabajo->save();


            $puestooriginal = Puestostrabajo::findOne($id_puesto);
            if($puestooriginal){
                $riesgopuesto = '';

                $id_riesgos = [];

                if($puestooriginal->riesgos){
                    foreach($puestooriginal->riesgos as $key2=>$riesgop){
                        $riesgopuesto .= $riesgop->id;
                        if($key2 < (count($puestooriginal->riesgos)-1)){
                            $riesgopuesto .= ',';
                        }


                        $pr = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['id_riesgo'=>$riesgop->id])->one();
                        if(!$pr){
                            $pr = new Puestotrabajohriesgo();
                            $pr->id_puestotrabajador = $puestotrabajo->id;
                            $pr->id_trabajador = $trabajador->id;
                            $pr->id_puesto = $id_puesto;
                            $pr->id_riesgo = $riesgop->id;
                            $pr->fecha_inicio = $trabajador->fecha_iniciop;
                            $pr->create_date = date('Y-m-d');
                            $pr->save();
                        } else {
                            $pr->id_trabajador = $trabajador->id;
                            $pr->fecha_inicio = $trabajador->fecha_iniciop;
                            if($pr->create_date == null || $pr->create_date == '' || $pr->create_date == ' '){
                                $pr->create_date = date('Y-m-d');
                            }
                            $pr->save();
                        }

                        if($pr){
                            array_push($id_riesgos, $pr->id);
                        }
                    }
                    $puestotrabajo->riesgos = $riesgopuesto;
                    $puestotrabajo->save();
                }

                //Borramos todos aquellos riesgos que ya no estén activos
                $deletes = Puestotrabajohriesgo::find()->where(['id_puestotrabajador'=>$puestotrabajo->id])->andWhere(['not in','id',$id_riesgos])->all();
                foreach($deletes as $delete){//Eliminar los que se hayan quitado
                    $delete->delete();
                }
            }
        }  else {
            $puestotrabajo->id_trabajador = $trabajador->id;
            $puestotrabajo->id_puesto = $id_puesto;
            $puestotrabajo->area = ''.$trabajador->id_area;
            $puestotrabajo->fecha_inicio = $trabajador->fecha_iniciop;
            $puestotrabajo->fecha_fin = $trabajador->fecha_finp;
            $puestotrabajo->teamleader = $trabajador->teamleader;
            $puestotrabajo->save();
        }


        if($puestotrabajo){
            $resultado_antiguedad = $this->actionCalculateantigpuesto($puestotrabajo);
            $puestotrabajo->antiguedad = $resultado_antiguedad[0];
            $puestotrabajo->save();
        }

    }


    public function actionRefreshtrabhc()
    {
        $empresas = [60];
        
        $trabajadores = Trabajadores::find()->where(['in', 'id_empresa', $empresas])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
        //dd($empresas,$trabajadores);
        if($trabajadores){
            foreach($trabajadores as $key=>$trabajadpr){
                
                $hc_trabajador = Hccohc::find()->where(['id_trabajador'=>$trabajadpr->id])->all();
                if($hc_trabajador){
                    foreach($hc_trabajador as $key2=>$hc){
                        $hc->fecha_nacimiento = $trabajadpr->fecha_nacimiento;
                        $hc->edad= $trabajadpr->edad;
                        $hc->save(false);
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }


    public function actionRefreshprogramas()
    {
        $trabajadores = Trabajadores::find()->where(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
        
        if($trabajadores){
            foreach($trabajadores as $key=>$trabajador){
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
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionRefreshcumplimientos()
    {
        $trabajadores = ArrayHelper::map(Trabajadores::find()->where(['IS', 'refreshupdated_5', new \yii\db\Expression('NULL')])->select(['id'])->limit(1000)->all(), 'id','id');
        //dd($trabajadores);
        if($trabajadores){
            foreach($trabajadores as $key=>$id_trabajador){
                $this->cumplimientosTrabajador($id_trabajador);
            }
        }

        if(Yii::$app->user->identity->hidden_actions == 1){
            dd('Terminó Refresh Cumplimientos');
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
}