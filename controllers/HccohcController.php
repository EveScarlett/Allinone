<?php

namespace app\controllers;

use app\models\Hccohc;
use app\models\HccohcSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Carbon\Carbon;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use Yii;

use app\models\AlergiaTrabajador;
use app\models\Turnos;
use app\models\Puestostrabajo;
use app\models\Puestotrabajador;
use app\models\Vigencias;
use app\models\Vacunacion;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Consultas;
use app\models\Detallehc;
use app\models\Hccohcestudio;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\ProgramaTrabajador;
use app\models\Paises;
use app\models\Paisempresa;
use app\models\Areas;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Poeestudio;
use app\models\Poes;
use app\models\Usuarios;
use app\models\Cuestionario;

use app\models\Trashhistory;


/**
 * HccOhcController implements the CRUD actions for HccOhc model.
 */
class HccohcController extends Controller
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
     * Lists all HccOhc models.
     *
     * @return string
     */
    public function actionIndex($id_empresa = null, $page=null)
    {
        $searchModel = new HccohcSearch();

        if($id_empresa != null && $id_empresa != '' && $id_empresa != ' '){
            $searchModel->id_empresa = $id_empresa;
        }

        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HccOhc model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionViewclean($id)
    {
        return $this->render('viewclean', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single HccOhc model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);

        $model = $this->cargarDatoshc($model);
        //dd($model);
        $model->scenario = 'close';
        //date_default_timezone_set('America/Mazatlan');

        //dd($model->testudios);

        foreach($model->testudios as $key=>$estudio){
            $model->aux_estudios[$key]['categoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_estudios[$key]['conclusion'] = $estudio->conclusion;
            $model->aux_estudios[$key]['comentarios'] = $estudio->comentario;

            if($estudio->evidencia != '' && $estudio->evidencia != null){
                $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$estudio->evidencia;
            }else{
                $model->aux_estudios[$key]['doc'] = null;
            }
            
            $model->aux_estudios[$key]['id'] = $estudio->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Hccohc");

            $model = $this->limpiarDatoshc($model);
            $model->save(false);
            //dd($model);
            if(true){
                $this->saveMultipleestudio($model);
                $model->status = $this->checarEstudios($model);

                /* if($model->status == 2){
                    $model->conclusion_cal == null;
                } */
                if($model->conclusion_cal != null){
                    $model->status = 2;
                }
                $model->save(false);

                return $this->redirect(['index']);
            } else {
                dd($model);
            }
        }

        return $this->render('close', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new HccOhc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($poe = null,$estudio=null)
    {
        date_default_timezone_set('America/Costa_Rica');
        $msj = null;
        $model = new Hccohc();
        $model->fecha = date('Y-m-d');
        $model->hora = date('H:i:s');
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);
        
        $model->scenario = 'create';


        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }

        if($poe != null && $estudio != null){
            $model->id_poe = $poe;
            $model->id_estudio_poe = $estudio;

            $poe = Poes::findOne($poe);
            if($poe){
                $model->id_empresa =  $poe->id_empresa;


                //['1'=>'NUEVO INGRESO','2'=>'POES PERIODICOS','3'=>'SALIDA'] POE
                //['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'] HC
                if($poe->tipo_poe != null && $poe->tipo_poe != '' && $poe->tipo_poe != ' '){
                    if($poe->tipo_poe == 1){
                        $model->examen = 1;
                    } else if($poe->tipo_poe == 2){
                        $model->examen = 3;
                    } else if($poe->tipo_poe == 3){
                        $model->examen = 5;
                    }
                }
                
                if($poe->trabajador){
                    //dd($poe->trabajador);
                    $model->id_trabajador = $poe->trabajador->id;
                    $model->id_nivel1 = $poe->trabajador->id_nivel1;
                    $model->id_nivel2 = $poe->trabajador->id_nivel2;
                    $model->id_nivel3 = $poe->trabajador->id_nivel3;
                    $model->id_nivel4 = $poe->trabajador->id_nivel4;

                    $model->nombre = $poe->trabajador->nombre;
                    $model->apellidos = $poe->trabajador->apellidos;
                    $model->sexo = $poe->trabajador->sexo;
                    $model->fecha_nacimiento = $poe->trabajador->fecha_nacimiento;
                    $model->edad = $poe->trabajador->edad;

                    $model->num_trabajador = $poe->trabajador->num_trabajador;
                    $model->area = $poe->trabajador->id_area;
                    $model->puesto = $poe->trabajador->id_puesto;

                    $model->nivel_lectura = $poe->trabajador->nivel_lectura;
                    $model->nivel_escritura = $poe->trabajador->nivel_escritura;
                    $model->estado_civil = $poe->trabajador->estado_civil;
                    $model->grupo = $poe->trabajador->grupo;
                    $model->rh = $poe->trabajador->rh;
                    
                    //dd($poe->trabajador);
                }
            }
            //dd('$poe: '.$poe.' | $estudio: '.$estudio);
        }

        $hc_anterior = null;

        $this->loadHccreate($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $request = Yii::$app->request;
                $param = $request->getBodyParam("Hccohc");


                if($model->envia_form == 1){                
                    
                    $vista = 'create';
                    $this->saveHc($model,$param,$vista,$hc_anterior);
                    
                } else {
                    //dd('entro aqui');
                    $empresa = Empresas::findOne($model->id_empresa);
                    if($empresa){
                        $model->nombre_empresa = $empresa->comercial;
                    }

                    $trabajador = Trabajadores::findOne($model->id_trabajador);
                    
                    if($trabajador){
                        $model->nombre = $trabajador->nombre;
                        $model->apellidos = $trabajador->apellidos;
                        $model->fecha_nacimiento = $trabajador->fecha_nacimiento;
                        $model->edad = $this->actionCalculateedad($trabajador->fecha_nacimiento);
                        $model->sexo = $trabajador->sexo;
                        $model->num_trabajador = $trabajador->num_trabajador;
                        $model->nivel_lectura = $trabajador->nivel_lectura;
                        $model->nivel_escritura = $trabajador->nivel_escritura;
                        $model->estado_civil = $trabajador->estado_civil;
                        $model->grupo = $trabajador->grupo;
                        $model->rh = $trabajador->rh;
                        $model->area = $trabajador->id_area;
                        $model->puesto = $trabajador->id_puesto;

                        $model->id_nivel1 = $trabajador->id_nivel1;
                        $model->id_nivel2 = $trabajador->id_nivel2;
                        $model->id_nivel3 = $trabajador->id_nivel3;
                        $model->id_nivel4 = $trabajador->id_nivel4;
    
                        if($trabajador->programas){
                            foreach($trabajador->programas as $key=>$programa){
                                $model->aux_programas[$key]['programa'] = $programa->id_programa;
                                $model->aux_programas[$key]['conclusion'] = null;
                                $model->aux_programas[$key]['id'] = null;
                            }
                        }
                    }

                    $hc_anterior = $this->anteriorHc($model);

                    //dd($hc_anterior);
    
                    return $this->render('create', [
                        'model' => $model,
                        'msj'=> $msj,
                        'hc_anterior'=>$hc_anterior
                    ]);

                
                }

               
            } else{
                $errors = $model->errors;

                foreach($errors as $key=>$infoerror){
                    $msj .= '<br>';
                    foreach($infoerror as $key2=>$error){
                        $msj .= ($key2+1).')'.$error;
                    }
                }
                
                return $this->render('create', [
                    'model' => $model,
                    'msj'=> $msj,
                    'hc_anterior'=>$hc_anterior
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'msj'=> $msj,
            'hc_anterior'=>$hc_anterior
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


    protected function Arraytocomas($valor) {
        //dd($valor);
        $result = null;
        try {
            foreach($valor as $key=>$data){
                $result .= $data;
                if($key < (count($valor)-1)){
                    $result .= ',';
                }
            }
        } catch (\Throwable $th) {
            //dd($valor);
        }
        
        return $result;
    }


    protected function saveMultiple($model,$modelpt2){
        //ALERGIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->alergias == 'SI'){
            if($model->aux_alergiastxt != null && $model->aux_alergiastxt != ''&& $model->aux_alergiastxt != ' '){
                foreach($model->aux_alergiastxt as $key=>$dato){
                    //dd($model->aux_alergiastxt);
    
                    if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                        
                        $alergia = AlergiaTrabajador::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['alergia'=>$dato['diagnostico']])->one();
    
                        if(!$alergia){
                            $alergia = new AlergiaTrabajador();
                        }
                        
                        $alergia = new AlergiaTrabajador();
                        $alergia->id_trabajador = $model->id_trabajador;
                        $alergia->alergia = $dato['diagnostico'];
                        $alergia->save();
        
                    }
                    
    
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
    
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'alergias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
    
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'alergias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        }else if($model->alergias == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'alergias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ASMA-----------------------------------------------------------
        if($model->asma == 'SI'){
            
        }else if($model->asma == 'NO'){
            $model->asmatxt = null;
            $model->asma_anio = null;
        }



        //VACUNACION-----------------------------------------------------------
        $arr_save = [];
        if($model->vacunacion_cov == 'SI'){
            if($model->aux_vacunacion_txt != null && $model->aux_vacunacion_txt != ''&& $model->aux_vacunacion_txt != ' '){
                foreach($model->aux_vacunacion_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                    } else{//Si no existe entonces crear nuevo
                        $detalle = new Detallehc();
                    }
    
                    if(isset($dato['vacuna']) && $dato['vacuna'] != null && $dato['vacuna'] != "" && $dato['vacuna'] != " "){
                        //dd($dato);
    
                        if($dato['vacuna'] == '0'){
                            if($dato['otravacuna'] != null && $dato['otravacuna'] != "" && $dato['otravacuna'] != " "){
                                $vacuna = new Vacunacion();
                                $vacuna->vacuna = $dato['otravacuna'];
                                $vacuna->status = 1;
                                $vacuna->save();
                                //dd($vacuna);
                            }
                        }else{
                            $vacuna = Vacunacion::findOne($dato['vacuna']);
                        }
    
                        if($vacuna){
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'vacunacion';
                            $detalle->descripcion = "".$vacuna->id;
                            $detalle->fecha = $dato['fecha'];
                            $detalle->save();
    
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
    
                        
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'vacunacion'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }

        } else if($model->vacunacion_cov == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'vacunacion'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //CARDIOPATIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->cardio == 'SI'){
            if($model->aux_cardiotxt != null && $model->aux_cardiotxt != ''&& $model->aux_cardiotxt != ' '){
                foreach($model->aux_cardiotxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'cardiopatias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'cardiopatias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        }else if($model->cardio == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'cardiopatias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //CIRUGIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->cirugias == 'SI'){
            if($model->aux_cirugiastxt != null && $model->aux_cirugiastxt != ''&& $model->aux_cirugiastxt != ' '){
                foreach($model->aux_cirugiastxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'cirugias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'cirugias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        }else if($model->cirugias == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'cirugias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //CONVULSIONES-----------------------------------------------------------
        $arr_save = [];
        if($model->convulsiones == 'SI'){
            if($model->aux_convulsionestxt != null && $model->aux_convulsionestxt != ''&& $model->aux_convulsionestxt != ' '){
                foreach($model->aux_convulsionestxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'convulsiones';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'convulsiones'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        }else if($model->convulsiones == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'convulsiones'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //DIABETES-----------------------------------------------------------
        $arr_save = [];
        if($model->diabetes == 'SI'){
            if($model->aux_diabetestxt != null && $model->aux_diabetestxt != ''&& $model->aux_diabetestxt != ' '){
                foreach($model->aux_diabetestxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'diabetes';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'diabetes'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->diabetes == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'diabetes'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //HIPERTENSION-----------------------------------------------------------
        $arr_save = [];
        if($model->hiper == 'SI'){
            if($model->aux_hipertxt != null && $model->aux_hipertxt != ''&& $model->aux_hipertxt != ' '){
                foreach($model->aux_hipertxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'hipertension';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hipertension'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->diabetes == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hipertension'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //LUMBALGIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->lumbalgias == 'SI'){
            if($model->aux_lumbalgiastxt != null && $model->aux_lumbalgiastxt != ''&& $model->aux_lumbalgiastxt != ' '){
                foreach($model->aux_lumbalgiastxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'lumbalgias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            } 

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'lumbalgias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->lumbalgias == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'lumbalgias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //NEFROPATIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->nefro == 'SI'){
            if($model->aux_nefrotxt != null && $model->aux_nefrotxt != ''&& $model->aux_nefrotxt != ' '){
                foreach($model->aux_nefrotxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'nefropatias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            } 

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'nefropatias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->nefro == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'nefropatias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //POLIOMELITIS-----------------------------------------------------------
        if($model->polio == 'SI'){
            
        }else if($model->polio == 'NO'){
            $model->poliomelitis_anio = null;
        }


        //SARAMPION-----------------------------------------------------------
        if($model->saram == 'SI'){
            
        }else if($model->saram == 'NO'){
            $model->saram_anio = null;
        }


        //ENFERMEDADES PULMONARES-----------------------------------------------------------
        $arr_save = [];
        if($model->pulmo == 'SI'){
            if($model->aux_pulmotxt != null && $model->aux_pulmotxt != ''&& $model->aux_pulmotxt != ' '){
                foreach($model->aux_pulmotxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'pulmonares';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'pulmonares'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->pulmo == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'pulmonares'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ENFERMEDADES HEMATICAS-----------------------------------------------------------
        $arr_save = [];
        if($model->hematicos == 'SI'){
            if($model->aux_hematicostxt != null && $model->aux_hematicostxt != ''&& $model->aux_hematicostxt != ' '){
                foreach($model->aux_hematicostxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'hematicos';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hematicos'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->hematicos == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hematicos'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //TRAUMATISMOS-----------------------------------------------------------
        $arr_save = [];
        if($model->trauma == 'SI'){
            if($model->aux_traumatxt != null && $model->aux_traumatxt != ''&& $model->aux_traumatxt != ' '){
                foreach($model->aux_traumatxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'traumatismos';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'traumatismos'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->trauma == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'traumatismos'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //USO DE MEDICAMENTOS-----------------------------------------------------------
        $arr_save = [];
        if($model->medicamentos == 'SI'){
            if($model->aux_medicamentostxt != null && $model->aux_medicamentostxt != ''&& $model->aux_medicamentostxt != ' '){
                foreach($model->aux_medicamentostxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['medicamento']) && $dato['medicamento'] != "" && $dato['medicamento'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['medicamento'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['medicamento']) && $dato['medicamento'] != "" && $dato['medicamento'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'medicamentos';
                            $detalle->descripcion = $dato['medicamento'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'medicamentos'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->medicamentos == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'medicamentos'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //USO DE PROTESIS-----------------------------------------------------------
        $arr_save = []; 
        if($model->protesis == 'SI'){
            if($model->aux_protesistxt != null && $model->aux_protesistxt != ''&& $model->aux_protesistxt != ' '){
                foreach($model->aux_protesistxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['tipo']) && $dato['tipo'] != "" && $dato['tipo'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['tipo'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['tipo']) && $dato['tipo'] != "" && $dato['tipo'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'protesis';
                            $detalle->descripcion = $dato['tipo'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'protesis'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->protesis == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'protesis'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //TRANSFUSIONES-----------------------------------------------------------
        $arr_save = [];
        //dd($model->aux_transtxt);
        if($model->trans == 'SI'){
            if($model->aux_transtxt != null && $model->aux_transtxt != ''&& $model->aux_transtxt != ' '){
                foreach($model->aux_transtxt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['anio']) && $dato['anio'] != "" && $dato['anio'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['anio']) && $dato['anio'] != "" && $dato['anio'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'transfusiones';
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'transfusiones'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->trans == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'transfusiones'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ENFERMEDAD OCULAR-----------------------------------------------------------
        $arr_save = [];
        if($model->enf_ocular == 'SI'){
            if($model->aux_enf_ocular_txt != null && $model->aux_enf_ocular_txt != ''&& $model->aux_enf_ocular_txt != ' '){
                foreach($model->aux_enf_ocular_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'enfocular';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfocular'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->enf_ocular == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfocular'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ENFERMEDAD AUDITIVA-----------------------------------------------------------
        $arr_save = [];
        if($model->enf_auditiva == 'SI'){
            if($model->aux_enf_auditiva_txt != null && $model->aux_enf_auditiva_txt != ''&& $model->aux_enf_auditiva_txt != ' '){
                foreach($model->aux_enf_auditiva_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'enfauditiva';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfauditiva'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->enf_auditiva == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfauditiva'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //FRACTURA LUXACION-----------------------------------------------------------
        $arr_save = [];
        if($model->fractura == 'SI'){
            if($model->aux_fractura_txt != null && $model->aux_fractura_txt != ''&& $model->aux_fractura_txt != ' '){
                foreach($model->aux_fractura_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'fractura';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'fractura'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->fractura == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'fractura'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //AMPUTACION-----------------------------------------------------------
        $arr_save = [];
        if($model->amputacion == 'SI'){
            if($model->aux_amputacion_txt != null && $model->aux_amputacion_txt != ''&& $model->aux_amputacion_txt != ' '){
                foreach($model->aux_amputacion_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'amputacion';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'amputacion'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->amputacion == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'amputacion'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //HERNIAS-----------------------------------------------------------
        $arr_save = [];
        if($model->hernias == 'SI'){
            if($model->aux_hernias_txt != null && $model->aux_hernias_txt != ''&& $model->aux_hernias_txt != ' '){
                foreach($model->aux_hernias_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'hernias';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hernias'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->hernias == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'hernias'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ENFERMEDADES SANGUINEAS / INMUNOLOGICAS-----------------------------------------------------------
        $arr_save = [];
        if($model->enfsanguinea == 'SI'){
            if($model->aux_enfsanguinea_txt != null && $model->aux_enfsanguinea_txt != ''&& $model->aux_enfsanguinea_txt != ' '){
                foreach($model->aux_enfsanguinea_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'enfsanguineas';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfsanguineas'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->enfsanguinea == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'enfsanguineas'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //TUMORES CANCER-----------------------------------------------------------
        $arr_save = [];
        if($model->tumorescancer == 'SI'){
            if($model->aux_tumorescancer_txt != null && $model->aux_tumorescancer_txt != ''&& $model->aux_tumorescancer_txt != ' '){
                foreach($model->aux_tumorescancer_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'tumores';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'tumores'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->tumorescancer == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'tumores'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }


        //ENFERMEDADES PSICOLOGICAS-----------------------------------------------------------
        $arr_save = [];
        if($model->enfpsico == 'SI'){
            if($model->aux_enfpsico_txt != null && $model->aux_enfpsico_txt != ''&& $model->aux_enfpsico_txt != ' '){
                foreach($model->aux_enfpsico_txt as $key=>$dato){
                    if(isset($dato['id']) && $dato['id'] != "" && $dato['id'] != null){//Si ya existe solo actualizar
                        
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = Detallehc::find()->where(['id'=>$dato['id']])->one();
                            if($detalle){
                                $detalle->descripcion = $dato['diagnostico'];
                                $detalle->anio = $dato['anio'];
                                $detalle->save(); 
                                array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                            }
                        }
                    } else{//Si no existe entonces crear nuevo
    
                        if(isset($dato['diagnostico']) && $dato['diagnostico'] != "" && $dato['diagnostico'] != null){
                            $detalle = new Detallehc();
                            $detalle->id_hcc = $model->id;
                            $detalle->seccion = 'psicologicas';
                            $detalle->descripcion = $dato['diagnostico'];
                            $detalle->anio = $dato['anio'];
                            $detalle->save();
                            array_push($arr_save, $detalle->id);//Guardar los ids de los que ya existian, para borrar los que hayan quitado
                        }
                    }
                }
            }

            $deletes = Detallehc::find()->where(['not in','id',$arr_save])->andWhere(['id_hcc'=>$model->id])->andWhere(['seccion'=>'psicologicas'])->all();
        
            foreach($deletes as $key=>$del){
                $del->delete();
            }
        } else if($model->enfpsico == 'NO'){
            $datos = Detallehc::find()->where(['id_hcc'=>$model->id])->andWhere(['seccion'=>'psicologicas'])->all();
            foreach($datos as $key=>$dato){
                $dato->delete();
            }
        }

    }


    /**
     * Updates an existing HccOhc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $msj = '';
        $model = $this->findModel($id);
        $model->scenario = 'update';


        //dd('model',$model,'fecha_doc',$model->fecha,'id_trabajador',$model->id_trabajador);
        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);


        $empresa = Empresas::findOne($model->id_empresa);
        if($empresa){
            $model->nombre_empresa = $empresa->comercial;
        }
        

        $hc_anterior = null;
        $hc_anterior = $this->anteriorHc($model);

        $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,'nombre_empresa','nombre_medico');

        try {
            $this->loadHc($model);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        
        //Cargando lo de la nueva ohc fin---------------------------------------------

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Hccohc");

            if($model->envia_form == 1){              
                $vista = 'update';
                $this->saveHc($model,$param,$vista,$hc_anterior);

            } else {

                $empresa = Empresas::findOne($model->id_empresa);
                if($empresa){
                    $model->nombre_empresa = $empresa->comercial;
                }

                $trabajador = Trabajadores::findOne($model->id_trabajador);

                if($trabajador){
                    $model->nombre = $trabajador->nombre;
                    $model->apellidos = $trabajador->apellidos;
                    $model->fecha_nacimiento = $trabajador->fecha_nacimiento;
                    $model->edad = $this->actionCalculateedad($trabajador->fecha_nacimiento);
                    $model->sexo = $trabajador->sexo;
                    $model->num_trabajador = $trabajador->num_trabajador;
                    $model->nivel_lectura = $trabajador->nivel_lectura;
                    $model->nivel_escritura = $trabajador->nivel_escritura;
                    $model->estado_civil = $trabajador->estado_civil;
                    $model->grupo = $trabajador->grupo;
                    $model->rh = $trabajador->rh;
                    $model->area = $trabajador->id_area;
                    $model->puesto = $trabajador->id_puesto;

                    $model->id_nivel1 = $trabajador->id_nivel1;
                    $model->id_nivel2 = $trabajador->id_nivel2;
                    $model->id_nivel3 = $trabajador->id_nivel3;
                    $model->id_nivel4 = $trabajador->id_nivel4;

                    if($trabajador->programas){
                        foreach($trabajador->programas as $key=>$programa){
                            $model->aux_programas[$key]['programa'] = $programa->id_programa;
                            $model->aux_programas[$key]['conclusion'] = null;
                            $model->aux_programas[$key]['id'] = null;
                        }
                    }
                }

                $hc_anterior = $this->anteriorHc($model);

                return $this->render('update', [
                    'model' => $model,
                    'msj'=> $msj,
                    'hc_anterior'=>$hc_anterior
                ]);
            }

        } else {

            $errors = $model->errors;
            foreach($errors as $key=>$infoerror){
                $msj .= '<br>';
                foreach($infoerror as $key2=>$error){
                    $msj .= ($key2+1).')'.$error;
                }
            }
            
            return $this->render('update', [
                'model' => $model,
                'msj'=> $msj,
                'hc_anterior'=>$hc_anterior
            ]);
   
        }

        return $this->render('update', [
            'model' => $model,
            'msj'=> $msj,
            'hc_anterior'=>$hc_anterior
        ]);
    }


    /**
     * Updates an existing HccOhc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCorrection($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $msj = '';
        $model = $this->findModel($id);
        $model->scenario = 'correction';

        //$this->loadHc($model);
        
        //Cargando lo de la nueva ohc fin---------------------------------------------

        if ($this->request->isPost && $model->load($this->request->post())) {
            $request = Yii::$app->request;
            $param = $request->getBodyParam("Hccohc");

            if($model->envia_form == 1){ 
                $fecha = $model->fecha;
                $examen = $model->examen;  
                $id_trabajador = $model->id_trabajador;
                
                $corrected_model = $this->findModel($id);
                $corrected_model->fecha = $fecha;
                $corrected_model->examen = $examen;
                $corrected_model->id_trabajador = $id_trabajador;
                $corrected_model->save(false);
                return $this->redirect(['view', 'id' => $corrected_model->id]);     
                //$vista = 'correction';
                //$this->saveHc($model,$param,$vista,$hc_anterior);

            } else {
                $trabajador = Trabajadores::findOne($model->id_trabajador);

                if($trabajador){
                    $model->nombre = $trabajador->nombre;
                    $model->apellidos = $trabajador->apellidos;
                    $model->fecha_nacimiento = $trabajador->fecha_nacimiento;
                    $model->edad = $this->actionCalculateedad($trabajador->fecha_nacimiento);
                    $model->sexo = $trabajador->sexo;
                    $model->num_trabajador = $trabajador->num_trabajador;
                    $model->nivel_lectura = $trabajador->nivel_lectura;
                    $model->nivel_escritura = $trabajador->nivel_escritura;
                    $model->estado_civil = $trabajador->estado_civil;
                    $model->grupo = $trabajador->grupo;
                    $model->rh = $trabajador->rh;
                    $model->area = $trabajador->id_area;
                    $model->puesto = $trabajador->id_puesto;

                    if($trabajador->programas){
                        foreach($trabajador->programas as $key=>$programa){
                            $model->aux_programas[$key]['programa'] = $programa->id_programa;
                            $model->aux_programas[$key]['conclusion'] = null;
                            $model->aux_programas[$key]['id'] = null;
                        }
                    }
                }

                return $this->render('correction', [
                    'model' => $model,
                    'msj'=> $msj
                ]);
            }

        } else {

            $errors = $model->errors;
            foreach($errors as $key=>$infoerror){
                $msj .= '<br>';
                foreach($infoerror as $key2=>$error){
                    $msj .= ($key2+1).')'.$error;
                }
            }
            
            return $this->render('correction', [
                'model' => $model,
                'msj'=> $msj
            ]);
   
        }

        return $this->render('correction', [
            'model' => $model,
            'msj'=> $msj
        ]);
    }

    private function loadHccreate($model){
        $fecha = date('Y-m-d');


        if($model->id_empresa && ($model->folio == null || $model->folio == null || $model->folio == '' || $model->folio == ' ')){
            $empresa = Empresas::findOne($model->id_empresa);
            
            if($empresa){
                $fecha = date('Ymd');
                $lasthistoria = $this->createClave('HC'.$empresa->abreviacion.$fecha,'app\models\Hccohc');
                $model->folio = $lasthistoria;
            }
           
        }

        for($i = 0; $i <= 4; $i++){
            $model->aux_alergiastxt[$i]['diagnostico'] = null;
            $model->aux_alergiastxt[$i]['anio'] = null;
            $model->aux_alergiastxt[$i]['id'] = null;

            $model->aux_cardiotxt[$i]['diagnostico'] = null;
            $model->aux_cardiotxt[$i]['anio'] = null;
            $model->aux_cardiotxt[$i]['id'] = null;

            $model->aux_cirugiastxt[$i]['diagnostico'] = null;
            $model->aux_cirugiastxt[$i]['anio'] = null;
            $model->aux_cirugiastxt[$i]['id'] = null;

            $model->aux_convulsionestxt[$i]['diagnostico'] = null;
            $model->aux_convulsionestxt[$i]['anio'] = null;
            $model->aux_convulsionestxt[$i]['id'] = null;

            $model->aux_diabetestxt[$i]['diagnostico'] = null;
            $model->aux_diabetestxt[$i]['anio'] = null;
            $model->aux_diabetestxt[$i]['id'] = null;

            $model->aux_hipertxt[$i]['diagnostico'] = null;
            $model->aux_hipertxt[$i]['anio'] = null;
            $model->aux_hipertxt[$i]['id'] = null;

            $model->aux_lumbalgiastxt[$i]['diagnostico'] = null;
            $model->aux_lumbalgiastxt[$i]['anio'] = null;
            $model->aux_lumbalgiastxt[$i]['id'] = null;

            $model->aux_nefrotxt[$i]['diagnostico'] = null;
            $model->aux_nefrotxt[$i]['anio'] = null;
            $model->aux_nefrotxt[$i]['id'] = null;

            $model->aux_pulmotxt[$i]['diagnostico'] = null;
            $model->aux_pulmotxt[$i]['anio'] = null;
            $model->aux_pulmotxt[$i]['id'] = null;

            $model->aux_hematicostxt[$i]['diagnostico'] = null;
            $model->aux_hematicostxt[$i]['anio'] = null;
            $model->aux_hematicostxt[$i]['id'] = null;

            $model->aux_traumatxt[$i]['diagnostico'] = null;
            $model->aux_traumatxt[$i]['anio'] = null;
            $model->aux_traumatxt[$i]['id'] = null;

            $model->aux_medicamentostxt[$i]['diagnostico'] = null;
            $model->aux_medicamentostxt[$i]['anio'] = null;
            $model->aux_medicamentostxt[$i]['id'] = null;

            $model->aux_protesistxt[$i]['diagnostico'] = null;
            $model->aux_protesistxt[$i]['anio'] = null;
            $model->aux_protesistxt[$i]['id'] = null;

            $model->aux_transtxt[$i]['diagnostico'] = null;
            $model->aux_transtxt[$i]['anio'] = null;
            $model->aux_transtxt[$i]['id'] = null;

            $model->aux_enf_ocular_txt[$i]['diagnostico'] = null;
            $model->aux_enf_ocular_txt[$i]['anio'] = null;
            $model->aux_enf_ocular_txt[$i]['id'] = null;

            $model->aux_enf_auditiva_txt[$i]['diagnostico'] = null;
            $model->aux_enf_auditiva_txt[$i]['anio'] = null;
            $model->aux_enf_auditiva_txt[$i]['id'] = null;

            $model->aux_fractura_txt[$i]['diagnostico'] = null;
            $model->aux_fractura_txt[$i]['anio'] = null;
            $model->aux_fractura_txt[$i]['id'] = null;

            $model->aux_amputacion_txt[$i]['diagnostico'] = null;
            $model->aux_amputacion_txt[$i]['anio'] = null;
            $model->aux_amputacion_txt[$i]['id'] = null;

            $model->aux_hernias_txt[$i]['diagnostico'] = null;
            $model->aux_hernias_txt[$i]['anio'] = null;
            $model->aux_hernias_txt[$i]['id'] = null;

            $model->aux_enfsanguinea_txt[$i]['diagnostico'] = null;
            $model->aux_enfsanguinea_txt[$i]['anio'] = null;
            $model->aux_enfsanguinea_txt[$i]['id'] = null;

            $model->aux_tumorescancer_txt[$i]['diagnostico'] = null;
            $model->aux_tumorescancer_txt[$i]['anio'] = null;
            $model->aux_tumorescancer_txt[$i]['id'] = null;

            $model->aux_enfpsico_txt[$i]['diagnostico'] = null;
            $model->aux_enfpsico_txt[$i]['anio'] = null;
            $model->aux_enfpsico_txt[$i]['id'] = null;

            $model->aux_programas[$i]['programa'] = null;
            $model->aux_programas[$i]['conclusion'] = null;
            $model->aux_programas[$i]['id'] = null;
        }

        return null;
    }

    public function anteriorHc($model){
        $hc_anterior = null;

        if($model->id_trabajador != null && $model->id_trabajador != '' && $model->id_trabajador != ' '){
            if($model->id == null || $model->id == '' && $model->id == ' '){
                $historia = Hccohc::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['id'=>SORT_DESC])->one();
            } else {
                $historia = Hccohc::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['<','id',$model->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['id'=>SORT_DESC])->one();
                //dd($model->id,$historia->id);
            }

            if($historia){
                $hc_anterior = $historia;
            }
            
        }

        if($hc_anterior){
            $model->grupo = $hc_anterior->grupo;
            $model->rh = $hc_anterior->rh;


            $model->antecedentes_sino = $hc_anterior->antecedentes_sino;


            $model->inicio_laboral = $hc_anterior->inicio_laboral;
            $model->antlaboral_area = $hc_anterior->antlaboral_area;
            $model->antlaboral_puesto = $hc_anterior->antlaboral_puesto;
            $model->antlaboral_antiguedad = $hc_anterior->antlaboral_antiguedad;


            $model->laboral0_actividad = $hc_anterior->laboral0_actividad;
            $model->laboral0_tiempoexposicion = $hc_anterior->laboral0_tiempoexposicion;
            $model->laboral0_epp = $hc_anterior->laboral0_epp;
            $model->laboral0_ipp = $hc_anterior->laboral0_ipp;
            $model->laboral0_desde = $hc_anterior->laboral0_desde;
            $model->laboral0_hasta = $hc_anterior->laboral0_hasta;

            $array = $hc_anterior->laboral0_exposicion;
            if(!is_array($hc_anterior->laboral0_exposicion)){
                $array = explode(',', $hc_anterior->laboral0_exposicion);
            }
            if($array && count($array)>0){
                $model->laboral0_exposicion = $array;
            }


            $model->laboral1_actividad = $hc_anterior->laboral1_actividad;
            $model->laboral1_tiempoexposicion = $hc_anterior->laboral1_tiempoexposicion;
            $model->laboral1_epp = $hc_anterior->laboral1_epp;
            $model->laboral1_ipp = $hc_anterior->laboral1_ipp;
            $model->laboral1_desde = $hc_anterior->laboral1_desde;
            $model->laboral1_hasta = $hc_anterior->laboral1_hasta;

            $array = $hc_anterior->laboral1_exposicion;
            if(!is_array($hc_anterior->laboral1_exposicion)){
                $array = explode(',', $hc_anterior->laboral1_exposicion);
            }
            if($array && count($array)>0){
                $model->laboral1_exposicion = $array;
            }
                    

            $model->laboral2_actividad = $hc_anterior->laboral2_actividad;
            $model->laboral2_tiempoexposicion = $hc_anterior->laboral2_tiempoexposicion;
            $model->laboral2_epp = $hc_anterior->laboral2_epp;
            $model->laboral2_ipp = $hc_anterior->laboral2_ipp;
            $model->laboral2_desde = $hc_anterior->laboral2_desde;
            $model->laboral2_hasta = $hc_anterior->laboral2_hasta;

            $array = $hc_anterior->laboral2_exposicion;
            if(!is_array($hc_anterior->laboral2_exposicion)){
                $array = explode(',', $hc_anterior->laboral2_exposicion);
            }
            if($array && count($array)>0){
                $model->laboral2_exposicion = $array;
            }
                    

            $model->laboral3_actividad = $hc_anterior->laboral3_actividad;
            $model->laboral3_tiempoexposicion = $hc_anterior->laboral3_tiempoexposicion;
            $model->laboral3_epp = $hc_anterior->laboral3_epp;
            $model->laboral3_ipp = $hc_anterior->laboral3_ipp;
            $model->laboral3_desde = $hc_anterior->laboral3_desde;
            $model->laboral3_hasta = $hc_anterior->laboral2_hasta;

            $array = $hc_anterior->laboral3_exposicion;
            if(!is_array($hc_anterior->laboral3_exposicion)){
                $array = explode(',', $hc_anterior->laboral3_exposicion);
            }
            if($array && count($array)>0){
                $model->laboral3_exposicion = $array;
            }
        }

        return $hc_anterior;
    }


    private function loadHc($model){
        if($model->status == 0){
            if($model->conclusion == null || $model->conclusion == ''|| $model->conclusion == ' '){
                $model->fecha = date('Y-m-d');
                $model->save(false);
            }
        }
        
        $this->calculaVigencia($model);

        foreach($model->testudios as $key=>$estudio){
            $model->aux_estudios[$key]['categoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['showcategoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_estudios[$key]['conclusion'] = $estudio->conclusion;
            $model->aux_estudios[$key]['comentarios'] = $estudio->comentario;

            if($estudio->evidencia != '' && $estudio->evidencia != null){
                $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$estudio->evidencia;
            }else{
                $model->aux_estudios[$key]['doc'] = null;
            }
            
            $model->aux_estudios[$key]['id'] = $estudio->id;
        }


        //Cargando lo de la nueva HC---------------------------------------------------------INICIO
        $array = $model->diabetesstxt;
        if(!is_array($model->diabetesstxt)){
            $array = explode(',', $model->diabetesstxt);
        }
        if($array && count($array)>0){
            $model->diabetesstxt = $array;
        }

        $array = $model->hipertensiontxt;
        if(!is_array($model->hipertensiontxt)){
            $array = explode(',', $model->hipertensiontxt);
        }
        if($array && count($array)>0){
            $model->hipertensiontxt = $array;
        }

        $array = $model->cancertxt;
        if(!is_array($model->cancertxt)){
            $array = explode(',', $model->cancertxt);
        }
        if($array && count($array)>0){
            $model->cancertxt = $array;
        }

        $array = $model->nefropatiastxt;
        if(!is_array($model->nefropatiastxt)){
            $array = explode(',', $model->nefropatiastxt);
        }
        if($array && count($array)>0){
            $model->nefropatiastxt = $array;
        }

        $array = $model->cardiopatiastxt;
        if(!is_array($model->cardiopatiastxt)){
            $array = explode(',', $model->cardiopatiastxt);
        }
        if($array && count($array)>0){
            $model->cardiopatiastxt = $array;
        }

        $array = $model->reumatxt;
        if(!is_array($model->reumatxt)){
            $array = explode(',', $model->reumatxt);
        }
        if($array && count($array)>0){
            $model->reumatxt = $array;
        }

        $array = $model->hepatxt;
        if(!is_array($model->hepatxt)){
            $array = explode(',', $model->hepatxt);
        }
        if($array && count($array)>0){
            $model->hepatxt = $array;
        }

        $array = $model->tubertxt;
        if(!is_array($model->tubertxt)){
            $array = explode(',', $model->tubertxt);
        }
        if($array && count($array)>0){
            $model->tubertxt = $array;
        }

        $array = $model->psitxt;
        if(!is_array($model->psitxt)){
            $array = explode(',', $model->psitxt);
        }
        if($array && count($array)>0){
            $model->psitxt = $array;
        }

        $array = $model->drogatxt;
        if(!is_array($model->drogatxt)){
            $array = explode(',', $model->drogatxt);
        }
        if($array && count($array)>0){
            $model->drogatxt = $array;
        }

        $array = $model->inspeccion;
        if(!is_array($model->inspeccion)){
            $array = explode(',', $model->inspeccion);
        }
        if($array && count($array)>0){
            $model->show_inspeccion = $model->inspeccion;

            $model->inspeccion = $array;
        }

        $array = $model->cabeza;
        if(!is_array($model->cabeza)){
            $array = explode(',', $model->cabeza);
        }
        if($array && count($array)>0){
            $model->show_cabeza = $model->cabeza;

            $model->cabeza = $array;
        }
    
        $array = $model->ojos;
        if(!is_array($model->ojos)){
            $array = explode(',', $model->ojos);
        }
        if($array && count($array)>0){
            $model->show_ojos = $model->ojos;

            $model->ojos = $array;
        }

        $array = $model->oidos;
        if(!is_array($model->oidos)){
            $array = explode(',', $model->oidos);
        }
        if($array && count($array)>0){
            $model->show_oidos = $model->oidos;

            $model->oidos = $array;
        }

        $array = $model->boca;
        if(!is_array($model->boca)){
            $array = explode(',', $model->boca);
        }
        if($array && count($array)>0){
            $model->show_boca = $model->boca;

            $model->boca = $array;
        }

        $array = $model->cuello;
        if(!is_array($model->cuello)){
            $array = explode(',', $model->cuello);
        }
        if($array && count($array)>0){
            $model->show_cuello = $model->cuello;

            $model->cuello = $array;
        }

        $array = $model->torax;
        if(!is_array($model->torax)){
            $array = explode(',', $model->torax);
        }
        if($array && count($array)>0){
            $model->show_torax = $model->torax;

            $model->torax = $array;
        }

        $array = $model->abdomen;
        if(!is_array($model->abdomen)){
            $array = explode(',', $model->abdomen);
        }
        if($array && count($array)>0){
            $model->show_abdomen = $model->abdomen;

            $model->abdomen = $array;
        }

        $array = $model->superior;
        if(!is_array($model->superior)){
            $array = explode(',', $model->superior);
        }
        if($array && count($array)>0){
            $model->show_superior = $model->superior;

            $model->superior = $array;
        }

        $array = $model->inferior;
        if(!is_array($model->inferior)){
            $array = explode(',', $model->inferior);
        }
        if($array && count($array)>0){
            $model->show_inferior = $model->inferior;

            $model->inferior = $array;
        }

        $array = $model->columna;
        if(!is_array($model->columna)){
            $array = explode(',', $model->columna);
        }
        if($array && count($array)>0){
            $model->show_columna = $model->columna;

            $model->columna = $array;
        }

        $array = $model->txtneurologicos;
        if(!is_array($model->txtneurologicos)){
            $array = explode(',', $model->txtneurologicos);
        }
        if($array && count($array)>0){
            $model->show_txtneurologicos = $model->txtneurologicos;

            $model->txtneurologicos = $array;
        }

        $array = $model->laboral0_exposicion;
        if(!is_array($model->laboral0_exposicion)){
            $array = explode(',', $model->laboral0_exposicion);
        }
        if($array && count($array)>0){
            $model->laboral0_exposicion = $array;
        }

        $array = $model->laboral1_exposicion;
        if(!is_array($model->laboral1_exposicion)){
            $array = explode(',', $model->laboral1_exposicion);
        }
        if($array && count($array)>0){
            $model->laboral1_exposicion = $array;
        }

        $array = $model->laboral2_exposicion;
        if(!is_array($model->laboral2_exposicion)){
            $array = explode(',', $model->laboral2_exposicion);
        }
        if($array && count($array)>0){
            $model->laboral2_exposicion = $array;
        }

        $array = $model->laboral3_exposicion;
        if(!is_array($model->laboral3_exposicion)){
            $array = explode(',', $model->laboral3_exposicion);
        }
        if($array && count($array)>0){
            $model->laboral3_exposicion = $array;
        }


        for($i = 0; $i <= 4; $i++){
            $model->aux_alergiastxt[$i]['diagnostico'] = null;
            $model->aux_alergiastxt[$i]['anio'] = null;
            $model->aux_alergiastxt[$i]['id'] = null;

            $model->aux_cardiotxt[$i]['diagnostico'] = null;
            $model->aux_cardiotxt[$i]['anio'] = null;
            $model->aux_cardiotxt[$i]['id'] = null;

            $model->aux_cirugiastxt[$i]['diagnostico'] = null;
            $model->aux_cirugiastxt[$i]['anio'] = null;
            $model->aux_cirugiastxt[$i]['id'] = null;

            $model->aux_convulsionestxt[$i]['diagnostico'] = null;
            $model->aux_convulsionestxt[$i]['anio'] = null;
            $model->aux_convulsionestxt[$i]['id'] = null;

            $model->aux_diabetestxt[$i]['diagnostico'] = null;
            $model->aux_diabetestxt[$i]['anio'] = null;
            $model->aux_diabetestxt[$i]['id'] = null;

            $model->aux_hipertxt[$i]['diagnostico'] = null;
            $model->aux_hipertxt[$i]['anio'] = null;
            $model->aux_hipertxt[$i]['id'] = null;

            $model->aux_lumbalgiastxt[$i]['diagnostico'] = null;
            $model->aux_lumbalgiastxt[$i]['anio'] = null;
            $model->aux_lumbalgiastxt[$i]['id'] = null;

            $model->aux_nefrotxt[$i]['diagnostico'] = null;
            $model->aux_nefrotxt[$i]['anio'] = null;
            $model->aux_nefrotxt[$i]['id'] = null;

            $model->aux_pulmotxt[$i]['diagnostico'] = null;
            $model->aux_pulmotxt[$i]['anio'] = null;
            $model->aux_pulmotxt[$i]['id'] = null;

            $model->aux_hematicostxt[$i]['diagnostico'] = null;
            $model->aux_hematicostxt[$i]['anio'] = null;
            $model->aux_hematicostxt[$i]['id'] = null;

            $model->aux_traumatxt[$i]['diagnostico'] = null;
            $model->aux_traumatxt[$i]['anio'] = null;
            $model->aux_traumatxt[$i]['id'] = null;

            $model->aux_medicamentostxt[$i]['diagnostico'] = null;
            $model->aux_medicamentostxt[$i]['anio'] = null;
            $model->aux_medicamentostxt[$i]['id'] = null;

            $model->aux_protesistxt[$i]['diagnostico'] = null;
            $model->aux_protesistxt[$i]['anio'] = null;
            $model->aux_protesistxt[$i]['id'] = null;

            $model->aux_transtxt[$i]['diagnostico'] = null;
            $model->aux_transtxt[$i]['anio'] = null;
            $model->aux_transtxt[$i]['id'] = null;

            $model->aux_enf_ocular_txt[$i]['diagnostico'] = null;
            $model->aux_enf_ocular_txt[$i]['anio'] = null;
            $model->aux_enf_ocular_txt[$i]['id'] = null;

            $model->aux_enf_auditiva_txt[$i]['diagnostico'] = null;
            $model->aux_enf_auditiva_txt[$i]['anio'] = null;
            $model->aux_enf_auditiva_txt[$i]['id'] = null;

            $model->aux_fractura_txt[$i]['diagnostico'] = null;
            $model->aux_fractura_txt[$i]['anio'] = null;
            $model->aux_fractura_txt[$i]['id'] = null;

            $model->aux_amputacion_txt[$i]['diagnostico'] = null;
            $model->aux_amputacion_txt[$i]['anio'] = null;
            $model->aux_amputacion_txt[$i]['id'] = null;

            $model->aux_hernias_txt[$i]['diagnostico'] = null;
            $model->aux_hernias_txt[$i]['anio'] = null;
            $model->aux_hernias_txt[$i]['id'] = null;

            $model->aux_enfsanguinea_txt[$i]['diagnostico'] = null;
            $model->aux_enfsanguinea_txt[$i]['anio'] = null;
            $model->aux_enfsanguinea_txt[$i]['id'] = null;

            $model->aux_tumorescancer_txt[$i]['diagnostico'] = null;
            $model->aux_tumorescancer_txt[$i]['anio'] = null;
            $model->aux_tumorescancer_txt[$i]['id'] = null;

            $model->aux_enfpsico_txt[$i]['diagnostico'] = null;
            $model->aux_enfpsico_txt[$i]['anio'] = null;
            $model->aux_enfpsico_txt[$i]['id'] = null;

            $model->aux_programas[$i]['programa'] = null;
            $model->aux_programas[$i]['conclusion'] = null;
            $model->aux_programas[$i]['id'] = null;
        }

        


        if($model->dAlergias){
            foreach($model->dAlergias as $key=>$detalle){
                $model->aux_alergiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_alergiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_alergiastxt[$key]['id'] = $detalle->id;
            }
        }

        if($model->dVacunacion){
            foreach($model->dVacunacion as $key=>$detalle){
                $model->aux_vacunacion_txt[$key]['vacuna'] = $detalle->descripcion;
                $model->aux_vacunacion_txt[$key]['fecha'] = $detalle->fecha;
                $model->aux_vacunacion_txt[$key]['id'] = $detalle->id;
            }
        }

        if($model->dCardiopatias){
            foreach($model->dCardiopatias as $key=>$detalle){
                $model->aux_cardiotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_cardiotxt[$key]['anio'] = $detalle->anio;
                $model->aux_cardiotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dCirugias){
            foreach($model->dCirugias as $key=>$detalle){
                $model->aux_cirugiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_cirugiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_cirugiastxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dConvulsiones){
            foreach($model->dConvulsiones as $key=>$detalle){
                $model->aux_convulsionestxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_convulsionestxt[$key]['anio'] = $detalle->anio;
                $model->aux_convulsionestxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dDiabetes){
            foreach($model->dDiabetes as $key=>$detalle){
                $model->aux_diabetestxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_diabetestxt[$key]['anio'] = $detalle->anio;
                $model->aux_diabetestxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHipertension){
            foreach($model->dHipertension as $key=>$detalle){
                $model->aux_hipertxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hipertxt[$key]['anio'] = $detalle->anio;
                $model->aux_hipertxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dLumbalgias){
            foreach($model->dLumbalgias as $key=>$detalle){
                $model->aux_lumbalgiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_lumbalgiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_lumbalgiastxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dNefropatias){
            foreach($model->dNefropatias as $key=>$detalle){
                $model->aux_nefrotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_nefrotxt[$key]['anio'] = $detalle->anio;
                $model->aux_nefrotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dPulmonares){
            foreach($model->dPulmonares as $key=>$detalle){
                $model->aux_pulmotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_pulmotxt[$key]['anio'] = $detalle->anio;
                $model->aux_pulmotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHematicas){
            foreach($model->dHematicas as $key=>$detalle){
                $model->aux_hematicostxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hematicostxt[$key]['anio'] = $detalle->anio;
                $model->aux_hematicostxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTraumatismos){
            foreach($model->dTraumatismos as $key=>$detalle){
                $model->aux_traumatxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_traumatxt[$key]['anio'] = $detalle->anio;
                $model->aux_traumatxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dMedicamentos){
            foreach($model->dMedicamentos as $key=>$detalle){
                $model->aux_medicamentostxt[$key]['medicamento'] = $detalle->descripcion;
                $model->aux_medicamentostxt[$key]['anio'] = $detalle->anio;
                $model->aux_medicamentostxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dProtesis){
            foreach($model->dProtesis as $key=>$detalle){
                $model->aux_protesistxt[$key]['tipo'] = $detalle->descripcion;
                $model->aux_protesistxt[$key]['anio'] = $detalle->anio;
                $model->aux_protesistxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTransfusiones){
            foreach($model->dTransfusiones as $key=>$detalle){
                $model->aux_transtxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_transtxt[$key]['anio'] = $detalle->anio;
                $model->aux_transtxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dOcular){
            foreach($model->dOcular as $key=>$detalle){
                $model->aux_enf_ocular_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enf_ocular_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enf_ocular_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dAuditiva){
            foreach($model->dAuditiva as $key=>$detalle){
                $model->aux_enf_auditiva_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enf_auditiva_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enf_auditiva_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dFractura){
            foreach($model->dFractura as $key=>$detalle){
                $model->aux_fractura_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_fractura_txt[$key]['anio'] = $detalle->anio;
                $model->aux_fractura_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dAmputacion){
            foreach($model->dAmputacion as $key=>$detalle){
                $model->aux_amputacion_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_amputacion_txt[$key]['anio'] = $detalle->anio;
                $model->aux_amputacion_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHernias){
            foreach($model->dHernias as $key=>$detalle){
                $model->aux_hernias_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hernias_txt[$key]['anio'] = $detalle->anio;
                $model->aux_hernias_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dSanguineas){
            foreach($model->dSanguineas as $key=>$detalle){
                $model->aux_enfsanguinea_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enfsanguinea_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enfsanguinea_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTumores){
            foreach($model->dTumores as $key=>$detalle){
                $model->aux_tumorescancer_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_tumorescancer_txt[$key]['anio'] = $detalle->anio;
                $model->aux_tumorescancer_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dPsicologicas){
            foreach($model->dPsicologicas as $key=>$detalle){
                $model->aux_enfpsico_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enfpsico_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enfpsico_txt[$key]['id'] = $detalle->id;
            }
        }

        //CARGAR PROGRAMAS DE SALUD
        if($model->trabajador){

            if($model->trabajador->programas){
                foreach($model->trabajador->programas as $key=>$programa){
                    $model->aux_programas[$key]['programa'] = $programa->id_programa;
                    $model->aux_programas[$key]['conclusion'] = $programa->conclusion;
                    $model->aux_programas[$key]['id'] = $programa->id;
                }
            }
        }

        if($model->empresa && ($model->folio == null || $model->folio == null || $model->folio == '' || $model->folio == ' ')){
            $fecha = date('Ymd');
            $lasthistoria = $this->createClave('HC'.$model->empresa->abreviacion.$fecha,'app\models\Hccohc');
            $model->folio = $lasthistoria;
        }

        return null;
    }

    private function saveHc($model,$param,$vista,$hc_anterior){
        $this->calculaVigencia($model);
        $model->firma = $param['firma'];
    
        $id_programas = [];
        if(isset($model->aux_programas) &&  $model->aux_programas != null && $model->aux_programas != ''){
            foreach($model->aux_programas as $key=>$programa){
                if($programa['programa'] != null && $programa['programa'] != '' && $programa['programa'] != ' '){
                    $programatrab = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['id_programa'=>$programa['programa']])->andWhere(['status'=>1])->one();
                    if(!$programatrab){
                        $programatrab = new ProgramaTrabajador();
                        $programatrab->id_trabajador = $model->id_trabajador;
                        $programatrab->id_programa = $programa['programa'];
                        $programatrab->conclusion = $programa['conclusion'];
                        $programatrab->status = 1;
                        $programatrab->save();
                    }

                    if($programatrab){
                        array_push($id_programas, $programatrab->id);
                    }
                }
            }            
        }
        $deletes = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['not in','id',$id_programas])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }
        

        //ANTECEDENTES HEREDO FAMILIARES INICIO*************************************
            //DIABETES-----------------------------------------------------------
            if($model->diabetesstxt == 'NO'){
                $model->diabetesstxt = null;
            }else if($model->diabetesstxt != '' && $model->diabetesstxt != null){
                $test = $this->Arraytocomas($model->diabetesstxt);
                $model->diabetesstxt = $test;
            }

            //HIPERTENSION-----------------------------------------------------------
            if($model->hipertensiontxt == 'NO'){
                $model->hipertensiontxt = null;
            }else if($model->hipertensiontxt != '' && $model->hipertensiontxt != null){
                $test = $this->Arraytocomas($model->hipertensiontxt);
                $model->hipertensiontxt = $test;
            }

            //CANCER-----------------------------------------------------------
            if($model->cancertxt == 'NO'){
                $model->cancertxt = null;
            }else if($model->cancertxt != '' && $model->cancertxt != null){
                $test = $this->Arraytocomas($model->cancertxt);
                $model->cancertxt = $test;
            }

            //NEFROPATIAS-----------------------------------------------------------
            if($model->nefropatiastxt == 'NO'){
                $model->nefropatiastxt = null;
            }else if($model->nefropatiastxt != '' && $model->nefropatiastxt != null){
                $test = $this->Arraytocomas($model->nefropatiastxt);
                $model->nefropatiastxt = $test;
            }

            //CARDIOPATIAS-----------------------------------------------------------
            if($model->cardiopatiastxt == 'NO'){
                $model->cardiopatiastxt = null;
            }else if($model->cardiopatiastxt != '' && $model->cardiopatiastxt != null){
                $test = $this->Arraytocomas($model->cardiopatiastxt);
                $model->cardiopatiastxt = $test;
            }

            //ENFERMEDADES REUMATICAS-----------------------------------------------------------
            if($model->reumatxt == 'NO'){
                $model->reumatxt = null;
            }else if($model->reumatxt != '' && $model->reumatxt != null){
                $test = $this->Arraytocomas($model->reumatxt);
                $model->reumatxt = $test;
            }

            //HEPATICOS-----------------------------------------------------------
            if($model->hepatxt == 'NO'){
                $model->hepatxt = null;
            }else if($model->hepatxt != '' && $model->hepatxt != null){
                $test = $this->Arraytocomas($model->hepatxt);
                $model->hepatxt = $test;
            }

            //TUBERCULOSIS-----------------------------------------------------------
            if($model->tubertxt == 'NO'){
                $model->tubertxt = null;
            }else if($model->tubertxt != '' && $model->tubertxt != null){
                $test = $this->Arraytocomas($model->tubertxt);
                $model->tubertxt = $test;
            }

            //PSIQUIATRICOS-----------------------------------------------------------
            if($model->psitxt == 'NO'){
                $model->psitxt = null;
            }else if($model->psitxt != '' && $model->psitxt != null){
                $test = $this->Arraytocomas($model->psitxt);
                $model->psitxt = $test;
            }
            //ANTECEDENTES HEREDO FAMILIARES FIN*************************************


            //DROGA-----------------------------------------------------
            if(isset($model->drogatxt) && $model->drogatxt != 'NO'){
                try {
                    if(count($model->drogatxt)>0){
                        $model->drogatxt = $test;
                    }else{
                        $model->drogatxt = null;
                    }
                } catch (\Throwable $th) {
                    $model->drogatxt = null;
                }
            }else{
                $model->drogatxt = null;
            }


             //EXPLORACION FÍSICA INICIO*********************************************
            //INSPECCION GENERAL-----------------------------------------------------
            if(isset($model->inspeccion) && $model->inspeccion != '' && count($model->inspeccion)>0){
                $test = $this->Arraytocomas($model->inspeccion);
                $model->inspeccion = $test;
            }else{
                $model->inspeccion = null;
            }

            //CABEZA-----------------------------------------------------
            if(isset($model->cabeza) && $model->cabeza != '' && count($model->cabeza)>0){
                $test = $this->Arraytocomas($model->cabeza);
                $model->cabeza = $test;
            }else{
                $model->cabeza = null;
            }

            //OIDOS-----------------------------------------------------
            if(isset($model->oidos) && $model->oidos != '' && count($model->oidos)>0){
                $test = $this->Arraytocomas($model->oidos);
                $model->oidos = $test;
            }else{
                $model->oidos = null;
            }

            //OJOS-----------------------------------------------------
            if(isset($model->ojos) && $model->ojos != '' && count($model->ojos)>0){
                $test = $this->Arraytocomas($model->ojos);
                $model->ojos = $test;
            }else{
                $model->ojos = null;
            }

            //BOCA/FARINGE-----------------------------------------------------
            if(isset($model->boca) && $model->boca != '' && count($model->boca)>0){
                $test = $this->Arraytocomas($model->boca);
                $model->boca = $test;
            }else{
                $model->boca = null;
            }

            //CUELLO-----------------------------------------------------
            if(isset($model->cuello) && $model->cuello != '' && count($model->cuello)>0){
                $test = $this->Arraytocomas($model->cuello);
                $model->cuello = $test;
            }else{
                $model->cuello = null;
            }

            //TORAX-----------------------------------------------------
            if(isset($model->torax) && $model->torax != '' && count($model->torax)>0){
                $test = $this->Arraytocomas($model->torax);
                $model->torax = $test;
            }else{
                $model->torax = null;
            }
            
            //ABDOMEN-----------------------------------------------------
            if(isset($model->abdomen) && $model->abdomen != '' && count($model->abdomen)>0){
                $test = $this->Arraytocomas($model->abdomen);
                $model->abdomen = $test;
            }else{
                $model->abdomen = null;
            }

            //MIEMBROS SUPERIORES-----------------------------------------------------
            if(isset($model->superior) && $model->superior != '' && count($model->superior)>0){
                $test = $this->Arraytocomas($model->superior);
                $model->superior = $test;
            }else{
                $model->superior = null;
            }

            //MIEMBROS INFERIORES-----------------------------------------------------
            if(isset($model->inferior) && $model->inferior != '' && count($model->inferior)>0){
                $test = $this->Arraytocomas($model->inferior);
                $model->inferior = $test;
            }else{
                $model->inferior = null;
            }

            //COLUMNA-----------------------------------------------------
            if(isset($model->columna) && $model->columna != '' && count($model->columna)>0){
                $test = $this->Arraytocomas($model->columna);
                $model->columna = $test;
            }else{
                $model->columna = null;
            }

            //NEUROLOGICOS-----------------------------------------------------
            if(isset($model->txtneurologicos) && $model->txtneurologicos != '' && count($model->txtneurologicos)>0){
                $test = $this->Arraytocomas($model->txtneurologicos);
                $model->txtneurologicos = $test;
            }else{
                $model->txtneurologicos = null;
            }
            //EXPLORACION FÍSICA FIN*********************************************


            //ANTECEDENTES LABORALES----------------------------------------------
            if(isset($model->laboral0_exposicion) && $model->laboral0_exposicion != '' && count($model->laboral0_exposicion)>0){
                $test = $this->Arraytocomas($model->laboral0_exposicion);
                $model->laboral0_exposicion = $test;
            }else{
                $model->laboral0_exposicion = null;
            }

            if(isset($model->laboral1_exposicion) && $model->laboral1_exposicion != '' && count($model->laboral1_exposicion)>0){
                $test = $this->Arraytocomas($model->laboral1_exposicion);
                $model->laboral1_exposicion = $test;
            }else{
                $model->laboral1_exposicion = null;
            }

            if(isset($model->laboral2_exposicion) && $model->laboral2_exposicion != '' && count($model->laboral2_exposicion)>0){
                $test = $this->Arraytocomas($model->laboral2_exposicion);
                $model->laboral2_exposicion = $test;
            }else{
                $model->laboral2_exposicion = null;
            }

            if(isset($model->laboral3_exposicion) && $model->laboral3_exposicion != '' && count($model->laboral3_exposicion)>0){
                $test = $this->Arraytocomas($model->laboral3_exposicion);
                $model->laboral3_exposicion = $test;
            }else{
                $model->laboral3_exposicion = null;
            }

            try {
                if($model->save()){
                    if($model->conclusion != null && $model->conclusion != '' && $model->conclusion != ' '){
                        if($model->status == null || $model->status != '' && $model->status != ' '){
                            $model->status = 1;
                            $model->save();
                        }
                    }

                    if($model->id_medico == null || $model->id_medico != '' && $model->id_medico != ' '){
                        $model->id_medico = Yii::$app->user->identity->id;
                        $model->save();
                    }

                    if($model->firma_medicolaboral == null || $model->firma_medicolaboral != '' && $model->firma_medicolaboral != ' '){
                        $empresa = $model->empresa;
                        if($empresa){
                            $model->firma_medicolaboral = $empresa->medico_laboral;
                            $model->save();
                        }
                    }

                    //Guardar la firma del trabajador---------------------------------
                    if($model->guardar_firma == 1 || ($model->firma_ruta == null || $model->firma_ruta == '' || $model->firma_ruta == ' ') ){
                        if(isset($model->firma) && $model->firma != 'data:,'){
                            define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/');
                            $dir0 = __DIR__ . '/../web/resources/Empresas/';
                            $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                            $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/HC/';
                            $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/';
                            $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                            $this->actionCarpetas($directorios);
                            $nombre_firma = $this->saveFirma($model->firma,$model);
                            $model->firma_ruta = $nombre_firma;
                            $model->save();
                        } 
                    }
                    
                    $this->saveMultiple($model,$model);
                    $this->saveMultipleestudio($model);

                    if($model->puesto == 0){
                        
                        if($model->aux_nuevopuesto != null && $model->aux_nuevopuesto != '' && $model->aux_nuevopuesto != ' '){
                            $nuevopuesto = new Puestostrabajo();
                            $nuevopuesto->nombre = $model->aux_nuevopuesto;
                            $nuevopuesto->status = 1;
                            $nuevopuesto->id_empresa = $model->id_empresa;
                            $nuevopuesto->create_date = date('Y-m-d');
                            $nuevopuesto->save();

                            if($nuevopuesto){
                                $model->puesto = $nuevopuesto->id;
                                $model->save();
                            }

                        } else {
                            $model->puesto = null;
                            $model->save();
                        }
                    }


                    //define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/');
                
                    if(isset($model->txt_base64_foto) && $model->txt_base64_foto != 'data:,'){

                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/HC/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/';
                        
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3];
                
                        $this->actionCarpetas( $directorios);
                        $nombre_foto = $this->saveImagen('CONSENTIMIENTO_'.$model->id.'_'.date('y-m-d_h-i-s'),$model->txt_base64_foto,$model);
                        $model->foto_web = $nombre_foto;
                        $model->save();
                            
                    }
            
                    if($model->fecha_c == null || $model->fecha_c == '' || $model->fecha_c == ' '){
                        $model->fecha_c = date('Y-m-d');
                        $model->hora_c = date('H:i:s');
                        $model->save(false);
                    }
                    
                } else{
                    dd($model);
                }
            } catch (\Throwable $th) {
                
                $msj = 'Ha ocurrido un error, verifique los campos llenados';
                $errors = $model->errors;
                foreach($errors as $key=>$infoerror){
                    $msj .= '<br>';
                    foreach($infoerror as $key2=>$error){
                        $msj .= ($key2+1).')'.$error;
                    }
                }
                
                //dd('entro aqui');
                //dd($th);
                return $this->render($vista, [
                    'model' => $model,
                    'msj'=> $msj,
                    'hc_anterior' =>$hc_anterior
                ]);
            }
        
            if($model->id_poe != null && $model->id_poe != '' && $model->id_poe != ' ' && $model->id_estudio_poe != null && $model->id_estudio_poe != '' && $model->id_estudio_poe != ' '){
                $estudio = Poeestudio::find()->where(['id'=>$model->id_estudio_poe])->andWhere(['id_poe'=>$model->id_poe])->one();

                if($estudio){
                    $estudio->id_hc = $model->id;
                    $estudio->save(false);
                }
            }
       
        $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,'nombre_empresa','nombre_medico');
        $this->statusPSTrabajador($model->id_trabajador);
        try {
            $this->cumplimientosTrabajador($model->id_trabajador);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->redirect(['view', 'id' => $model->id]);
    
                
        return null;
    }


    public function actionPdf($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Hccohc::findOne($id);
        $this->calculaVigencia($model);
        $model->firmado = $firmado;


        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);
        
        
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
                'SetTitle' => 'HISTORIA CLINICA '.$model->folio.'.pdf',
                'SetSubject' => 'HISTORIA CLINICA',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }


    public function actionPdfcal($id,$firmado) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Hccohc::findOne($id);
        $this->calculaVigencia($model);
        $model->firmado = $firmado;

        $this->getAgeupdated($model,$model->fecha,$model->id_trabajador);

        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('pdfcal.php',['model' => $model]),
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
                'SetTitle' => 'APTITUD MEDICA LABORAL '.$model->folio.'.pdf',
                'SetSubject' => 'APTITUD MEDICA LABORA',
                'SetHTMLHeader' =>'<div style="width:20%; position: absolute;top: 25px;left: 30px;"><img src="resources/images/medicalfil2022.png"></div><div style="width:20%; position: absolute;top: 25px;left: 690px;">Pag {PAGENO}/{nbpg}</div>',
                'SetAuthor' => 'Red Medica Alfil',
                'SetCreator' => 'Red Medica Alfil',
                'SetKeywords' => 'consentimiento',
            ]
        ]);

        
        return $pdf->render();
        
    }


    /**
     * Deletes an existing HccOhc model.
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
            $model->status_baja = 1;
            $model->save(false);

            $this->saveTrash($model,'Hccohc');
        }

        if($page != null && $page != '' && $page != ' '){
            $page = $page +1;
        }

        $url = Url::to(['index', 'id_empresa' => $company,'page'=>$page]);
        return $this->redirect($url);
        
        //return $this->redirect(['index']);
    }

    /**
     * Finds the HccOhc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return HccOhc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hccohc::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function saveMultipleestudio($model){
        
        $id_estudios = [];
        if(isset($model->aux_estudios) && $model->aux_estudios != null && $model->aux_estudios != ''){
            foreach($model->aux_estudios as $key => $estudio){

                if(isset($estudio['id']) && $estudio['id'] != null && $estudio['id'] != ''){
                    $pe = Hccohcestudio::find()->where(['id'=> $estudio['id']])->one();
                } else {
                    $pe = new Hccohcestudio();
                    $pe->id_hccohc = $model->id;
                }

                if(isset($estudio['categoria']) || isset($estudio['otracategoria'])){
                   
                    if($estudio['categoria'] == '5'){
                        if($estudio['otracategoria'] != "" && $estudio['otracategoria'] != null){
                            $tipo = new TipoServicios();
                            $tipo->nombre = $estudio['otracategoria'];
                            $tipo->save();
                            //dd($tipo);
                        }
                    }else{
                        $tipo = TipoServicios::find()->where(['id'=>$estudio['categoria']])->one();
                    }

                    if(isset($tipo)){
                        if($estudio['estudio'] == 0){
                            if($estudio['otroestudio'] != "" && $estudio['otroestudio'] != null){
                                $nestudio = new Servicios();
                                $nestudio->nombre = $estudio['otroestudio'];
                                $nestudio->id_tiposervicio = $tipo->id;
                                $nestudio->save();
                            }
                        }else{
                            $nestudio = Servicios::find()->where(['id'=>$estudio['estudio']])->one();
                        }
                    }

                    if(isset($tipo) && isset($nestudio)){
                        $pe->id_estudio = $nestudio->id;
                        $pe->id_tipo = $tipo->id;
                        $pe->conclusion = $estudio['conclusion'];
                        $pe->comentario = $estudio['comentarios'];
                        $pe->save();

                        if('aux_estudios' . '[' . $key . '][' . 'evidencia' . ']' != ""){
               
                            $archivo = 'aux_estudios' . '[' . $key . '][' . 'evidencia' . ']';
                            $save_archivo = UploadedFile::getInstance($model, $archivo);
                            if (!is_null($save_archivo)) {
    
                                $dir0 = __DIR__ . '/../web/resources/Empresas/';
                                $dir1 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/';
                                $dir2 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/';
                                $dir3 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/';
                                $dir4 = __DIR__ . '/../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/';
                                $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                                $this->actionCarpetas( $directorios);
    
                                if($pe->evidencia != null || $pe->evidencia != ''){
                                    unlink('resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$pe->evidencia);
                                }
    
                                $ruta_archivo = 'DOC_HC_'.$pe->id_estudio .'_'.date('YmdHis'). '.' . $save_archivo->extension;
                                $save_archivo->saveAs($directorios[4] . '/' . $ruta_archivo);
                                $pe->evidencia= $ruta_archivo;
                                
                            }
                            $pe->save();
                        }

                        if($pe){
                            array_push($id_estudios, $pe->id);
                        }
                    }
                    
                }
            }
        }

        $deletes = Hccohcestudio::find()->where(['id_hccohc'=>$model->id])->andWhere(['not in','id',$id_estudios])->all();
        foreach($deletes as $delete){//Eliminar los que se hayan quitado
            $delete->delete();
        }

    }

    public function actionInfoempresa(){
        $id = Yii::$app->request->post('id');
        $empresa = Empresas::find()->where(['id'=>$id])->one();
        //date_default_timezone_set('America/Mazatlan');
        $fecha = date('Ymd');

        $lastconsulta = null;
        $lasthistoria = null;
        $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
        $tipos = ['1'=>'Médico','2'=>'Otro'];
        $status = ['1'=>'Activo','0'=>'Baja'];

        $trabajadores =[];
        $ubicaciones =[];
        $areas =[];
        $consultorios =[];
        $turnos =[];
        $programas =[];
        $puestos =[];
        $turnos =[];

        $requisitos = [];

        $niveles1 = [];
        $niveles2 = [];
        $niveles3 = [];
        $niveles4 = [];


        $label_nivel1 = 'Nivel 1';
        $label_nivel2 = 'Nivel 2';
        $label_nivel3 = 'Nivel 3';
        $label_nivel4 = 'Nivel 4';

        $cantidad_niveles = 0;

        if($empresa){
            $lastconsulta = $this->createClave('CM'.$empresa->abreviacion.$fecha,'app\models\Consultas');
            $lasthistoria = $this->createClave('HC'.$empresa->abreviacion.$fecha,'app\models\Hccohc');
           
            $hctrabajadores = $empresa->trabajadores;
            $trabajadores = $empresa->trabajadores;

            foreach($hctrabajadores as $key=>$trabajador){
                $counthc = '[ '.($trabajador->historiasactivas).' HC ]';
                $trabajador['apellidos'] = $trabajador['apellidos'].$counthc;
            }

            $ubicaciones = $empresa->ubicaciones;
            $areas = $empresa->areas;
            $consultorios = $empresa->consultorios;
            $turnos = $empresa->turnos;
            $programas = $empresa->programas;
            $puestos =$empresa->puestos;
            $requisitos = $empresa->requisitos;
            $turnos = $empresa->turnos;


            foreach($empresa->requisitos as $key=>$req){
                $requisitos[$key]['id_estudio'] = $req->estudio->estudio;
                $requisitos[$key]['id_periodicidad'] = $periodicidades[$req->id_periodicidad];
                $requisitos[$key]['id_status'] = $req->id_status;
                $requisitos[$key]['id_tipo'] = $req->id_tipo-1;
               /*  $requisitos[$key]['id_status'] = $status[$req->id_status];
                $requisitos[$key]['id_tipo'] = $tipos[$req->id_tipo]; */
            }


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

            $usuario = Yii::$app->user->identity;
            if($usuario->nivel1_all == 1){
                $niveles1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
                    $rtlvl1 = '';
                    if($data->pais){
                        $rtlvl1 = $data->pais->pais;
                    }
                    return $rtlvl1;
                });
            } else {
                $array = explode(',', $usuario->nivel1_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }

                $niveles1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('id_pais')->all(), 'id', function($data){
                    $rtlvl1 = '';
                    if($data->pais){
                        $rtlvl1 = $data->pais->pais;
                    }
                    return $rtlvl1;
                });
            }
            //dd($requisitos);


            if($empresa->cantidad_niveles != null && $empresa->cantidad_niveles != '' && $empresa->cantidad_niveles != ' '){
            
                $label_nivel1 = $empresa->label_nivel1;
                $label_nivel2 = $empresa->label_nivel2;
                $label_nivel3 = $empresa->label_nivel3;
                $label_nivel4 = $empresa->label_nivel4;

                $cantidad_niveles = $empresa->cantidad_niveles;
            }
        }
        
        //return \yii\helpers\Json::encode(['empresa' => $empresa,'trabajadores'=>$trabajadores]);
        return \yii\helpers\Json::encode(['empresa' => $empresa,'trabajadores'=>$trabajadores,'fecha'=>$fecha,'ubicaciones'=>$ubicaciones,'areas'=>$areas,'consultorios'=>$consultorios,'turnos'=>$turnos,'programas'=>$programas,'consultas'=>$lastconsulta,'hccohc'=>$lasthistoria,'puestos'=>$puestos,'requisitos'=>$requisitos,'turnos'=>$turnos,'hctrabajadores'=>$hctrabajadores,'paises'=>$paises,'niveles1'=>$niveles1,'niveles2'=>$niveles2,'niveles3'=>$niveles3,'niveles4'=>$niveles4,'cantidad_niveles'=>$cantidad_niveles,'label_nivel1'=>$label_nivel1,'label_nivel2'=>$label_nivel2,'label_nivel3'=>$label_nivel3,'label_nivel4'=>$label_nivel4]);
    }


    public function actionInfonivel1(){
        $id = Yii::$app->request->post('id_nivel');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $nivel1 = NivelOrganizacional1::findOne($id);
        $niveles2 = [];

        $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();

        $cambia_area = false;
        $areas = [];

        if($nivel1){
            
            $usuario = Yii::$app->user->identity;
            if($usuario->nivel2_all == 1){
                $niveles2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id','nivelorganizacional2');
            } else {
                $array = explode(',', $usuario->nivel2_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }

                $niveles2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id','nivelorganizacional2');
            }
            

            if($empresa){
                if($empresa->cantidad_niveles == 1){
                    $cambia_area = true;

                    $usuario = Yii::$app->user->identity;
                    if($usuario->areas_all == 1){
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel1->id])->andWhere(['nivel'=>1])->all(), 'id','area');
                    } else {
                        $array = explode(',', $usuario->areas_select);
                        if($array && count($array)>0){
                        } else {
                            $array = [];
                        }
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel1->id])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all(), 'id','area');
                    }
                }
            }
        }

        return \yii\helpers\Json::encode(['id'=>$id,'nivel1' => $nivel1,'niveles2'=>$niveles2,'empresa'=>$empresa,'cambia_area'=>$cambia_area,'areas'=>$areas]);
    }


    public function actionInfonivel2(){
        $id = Yii::$app->request->post('id_nivel');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $nivel2 = NivelOrganizacional2::findOne($id);
        $niveles3 = [];

        $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();

        $cambia_area = false;
        $areas = [];

        if($nivel2){
            $usuario = Yii::$app->user->identity;
           
            if($usuario->nivel3_all == 1){
                $niveles3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id','nivelorganizacional3');
            } else {
                $array = explode(',', $usuario->nivel3_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $niveles3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id','nivelorganizacional3');
            }

            if($empresa){
                if($empresa->cantidad_niveles == 2){
                    $cambia_area = true;

                    $usuario = Yii::$app->user->identity;
                    if($usuario->areas_all == 1){
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel2->id])->andWhere(['nivel'=>2])->all(), 'id','area');
                    } else {
                        $array = explode(',', $usuario->areas_select);
                        if($array && count($array)>0){
                        } else {
                            $array = [];
                        }
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel2->id])->andWhere(['nivel'=>2])->andWhere(['in','id',$array])->all(), 'id','area');
                    }
                    
                }
            }
        }

        return \yii\helpers\Json::encode(['nivel2' => $nivel2,'niveles3'=>$niveles3,'empresa'=>$empresa,'cambia_area'=>$cambia_area,'areas'=>$areas]);
    }


    public function actionInfonivel3(){
        $id = Yii::$app->request->post('id_nivel');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $nivel3 = NivelOrganizacional3::findOne($id);
        $niveles4 = [];

        $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();

        $cambia_area = false;
        $areas = [];

        if($nivel3){
            $usuario = Yii::$app->user->identity;
            if($usuario->nivel4_all == 1){
                $niveles4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id','nivelorganizacional4');
            } else {
                $array = explode(',', $usuario->nivel4_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $niveles4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id','nivelorganizacional4');
            }
            

            if($empresa){
                if($empresa->cantidad_niveles == 3){
                    $cambia_area = true;

                    $usuario = Yii::$app->user->identity;
                    if($usuario->areas_all == 1){
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel3->id])->andWhere(['nivel'=>3])->all(), 'id','area');
                    } else {
                        $array = explode(',', $usuario->areas_select);
                        if($array && count($array)>0){
                        } else {
                            $array = [];
                        }
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel3->id])->andWhere(['nivel'=>3])->andWhere(['in','id',$array])->all(), 'id','area');
                    }
                    
                }
            }
        }

        return \yii\helpers\Json::encode(['nivel3' => $nivel3,'niveles4'=>$niveles4,'empresa'=>$empresa,'cambia_area'=>$cambia_area,'areas'=>$areas]);
    }


    public function actionInfonivel4(){
        $id = Yii::$app->request->post('id_nivel');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $nivel4 = NivelOrganizacional4::findOne($id);
        
        $empresa = Empresas::find()->where(['id'=>$id_empresa])->one();

        $cambia_area = false;
        $areas = [];

        if($nivel4){
            if($empresa){
                if($empresa->cantidad_niveles == 4){
                    $cambia_area = true;

                    $usuario = Yii::$app->user->identity;
                    if($usuario->areas_all == 1){
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel4->id])->andWhere(['nivel'=>4])->all(), 'id','area');
                    } else {
                        $array = explode(',', $usuario->areas_select);
                        if($array && count($array)>0){
                        } else {
                            $array = [];
                        }
                        $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$nivel4->id])->andWhere(['nivel'=>4])->andWhere(['in','id',$array])->all(), 'id','area');
                    }

                }
            }
        }

        return \yii\helpers\Json::encode(['nivel4' => $nivel4,'empresa'=>$empresa,'cambia_area'=>$cambia_area,'areas'=>$areas]);
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

    protected function saveFirma($firma,$model) {
        //dd($firma);
        $img = $firma;
        $img = str_replace('data:image/png;base64,', '', $img);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
        $nombre_archivo =  uniqid() . '.png';
	    $file = UPLOAD_DIR . $nombre_archivo;
	    $success = file_put_contents($file, $data);

        return  $nombre_archivo;
    }


    public function actionInfotrabajador(){
        
        $id = Yii::$app->request->post('id');
        $trabajador = Trabajadores::find()->where(['id'=>$id])->one();
        $puesto = null;
        $alergias =[];
        $riesgos =[];
        $poe = null;
        $estudios = null;

        $hc = null;
        $programas = null;

        $condiciones = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];
        $evoluciones = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];

        if($trabajador){
            if(isset($trabajador->fecha_nacimiento) && $trabajador->fecha_nacimiento != null && $trabajador->fecha_nacimiento != ''){
                $edad = $this->actionCalculateedad($trabajador->fecha_nacimiento);
                $trabajador->edad = $edad;
                $trabajador->save();
            }

            if($trabajador->tipo_registro == 2){
               
                $puestot = Puestostrabajo::find()->where(['id'=>$trabajador->id_puesto])->one();
               
                if($puestot){
                    $puestonew = Puestostrabajo::find()->where(['like','nombre',$puestot->nombre])->andWhere(['id_empresa'=>$trabajador->id_empresa])->one();
                    //dd($puesto);
                    if(!$puestonew){
                        $puestonew = new Puestostrabajo(); 
                        $puestonew->id_empresa = $trabajador->id_empresa;
                        $puestonew->nombre = $puestot->nombre;
                        $puestonew->status = 1;
                        $puestonew->create_date = date('Y-m-d');
                        $puestonew->save();
                    }
                    if($puestonew){
                        $trabajador->id_puesto = $puestonew->id;
                        $trabajador->save(false);

                        $puesto = $puestonew;
                    }
                }
            } else{
                if($trabajador->puestoactivo){
                    $puesto = $trabajador->puestoactivo->puesto;
                }
            }
            

            $alergias = $trabajador->alergias;
            if($puesto){
                $riesgos = $puesto->riesgos; 
            }

            if($trabajador->historiaclinica){
                $hc = $trabajador->historiaclinica;
            }

            if($trabajador->programas){
                $programas = $trabajador->programas;

                foreach($trabajador->programas as $key=>$program){
                    if($program->programa){
                        $programas[$key]['id_programa'] = $program->programa->nombre;
                    } else {
                        $programas[$key]['id_programa'] = null;
                    }
                    
                }
            }

            if($trabajador->lastpoe){
                $poe  = $trabajador->lastpoe;
                if($poe->puesto){
                    $poe['id_puesto'] = $poe->puesto->nombre;
                }
                if($poe->area){
                    $poe['id_area'] = $poe->area->area;
                }
                
                if($trabajador->lastpoe->estudios){
                    $estudios = $trabajador->lastpoe->estudios;

                    foreach($trabajador->lastpoe->estudios as $key=>$est){
                        $estudios[$key]['id_tipo'] = $est->tipo->nombre;
                        $estudios[$key]['id_estudio'] = $est->estudio->nombre;

                        if(isset($est->condicion) && $est->condicion != null && $est->condicion != ''){
                            
                            $estudios[$key]['condicion'] = $condiciones[$est->condicion];
                        } else{
                            $estudios[$key]['condicion'] = null;
                        }

                        if(isset($est->evolucion) && $est->evolucion != null && $est->evolucion != ''){
                            $estudios[$key]['evolucion'] = $evoluciones[$est->evolucion];
                        } else {
                            $estudios[$key]['evolucion'] = null;
                        }

                        if($est->evidencia != null && $est->evidencia != ''){
                            $estudios[$key]['evidencia'] = 'resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/'.$est->evidencia;
                        }else{
                            $estudios[$key]['evidencia'] = null;
                        }
                        
                    }
                }
            }
        }

        return \yii\helpers\Json::encode(['trabajador' => $trabajador,'puesto'=>$puesto,'alergias'=>$alergias,'riesgos'=>$riesgos,'poe'=>$poe,'estudios'=>$estudios,'hc'=>$hc, 'programas'=>$programas]);
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


    public function actionCalculateedad($fecha){
        //dd('Fecha: '.$fecha);
        $resultado = '';

        $date = Carbon::parse($fecha.' 00:00:00');//Convertir a Carbon la fecha de contratación
        $now = Carbon::now();//Extraer la fecha actual, para calcular la diferencia
        
        $resultado = $date->diffInYears($now);
        
        return $resultado;
    }


    private function cargarDatoshc($model){
        foreach($model->testudios as $key=>$estudio){
            $model->aux_estudios[$key]['categoria'] = $estudio->id_tipo;
            $model->aux_estudios[$key]['estudio'] = $estudio->id_estudio;
            $model->aux_estudios[$key]['conclusion'] = $estudio->conclusion;
            $model->aux_estudios[$key]['comentarios'] = $estudio->comentario;

            if($estudio->evidencia != '' && $estudio->evidencia != null){
                $model->aux_estudios[$key]['doc'] = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$estudio->evidencia;
            }else{
                $model->aux_estudios[$key]['doc'] = null;
            }
            
            $model->aux_estudios[$key]['id'] = $estudio->id;
        }


        //Cargando lo de la nueva HC---------------------------------------------------------INICIO

        $array = explode(',', $model->diabetesstxt);
        if($array && count($array)>0){
            $model->diabetesstxt = $array;
        }

        $array = explode(',', $model->hipertensiontxt);
        if($array && count($array)>0){
            $model->hipertensiontxt = $array;
        }

        $array = explode(',', $model->cancertxt);
        if($array && count($array)>0){
            $model->cancertxt = $array;
        }

        $array = explode(',', $model->nefropatiastxt);
        if($array && count($array)>0){
            $model->nefropatiastxt = $array;
        }

        $array = explode(',', $model->cardiopatiastxt);
        if($array && count($array)>0){
            $model->cardiopatiastxt = $array;
        }

        $array = explode(',', $model->reumatxt);
        if($array && count($array)>0){
            $model->reumatxt = $array;
        }

        $array = explode(',', $model->hepatxt);
        if($array && count($array)>0){
            $model->hepatxt = $array;
        }

        $array = explode(',', $model->tubertxt);
        if($array && count($array)>0){
            $model->tubertxt = $array;
        }

        $array = explode(',', $model->psitxt);
        if($array && count($array)>0){
            $model->psitxt = $array;
        }

        $array = explode(',', $model->drogatxt);
        if($array && count($array)>0){
            $model->drogatxt = $array;
        }

        $array = explode(',', $model->inspeccion);
        if($array && count($array)>0){
            $model->inspeccion = $array;
        }

        $array = explode(',', $model->cabeza);
        if($array && count($array)>0){
            $model->cabeza = $array;
        }

        $array = explode(',', $model->ojos);
        if($array && count($array)>0){
            $model->ojos = $array;
        }

        $array = explode(',', $model->oidos);
        if($array && count($array)>0){
            $model->oidos = $array;
        }

        $array = explode(',', $model->boca);
        if($array && count($array)>0){
            $model->boca = $array;
        }

        $array = explode(',', $model->cuello);
        if($array && count($array)>0){
            $model->cuello = $array;
        }

        $array = explode(',', $model->torax);
        if($array && count($array)>0){
            $model->torax = $array;
        }

        $array = explode(',', $model->abdomen);
        if($array && count($array)>0){
            $model->abdomen = $array;
        }

        $array = explode(',', $model->superior);
        if($array && count($array)>0){
            $model->superior = $array;
        }

        $array = explode(',', $model->inferior);
        if($array && count($array)>0){
            $model->inferior = $array;
        }

        $array = explode(',', $model->columna);
        if($array && count($array)>0){
            $model->columna = $array;
        }

        $array = explode(',', $model->txtneurologicos);
        if($array && count($array)>0){
            $model->txtneurologicos = $array;
        }


        for($i = 0; $i <= 4; $i++){
            $model->aux_alergiastxt[$i]['diagnostico'] = null;
            $model->aux_alergiastxt[$i]['anio'] = null;
            $model->aux_alergiastxt[$i]['id'] = null;

            $model->aux_cardiotxt[$i]['diagnostico'] = null;
            $model->aux_cardiotxt[$i]['anio'] = null;
            $model->aux_cardiotxt[$i]['id'] = null;

            $model->aux_cirugiastxt[$i]['diagnostico'] = null;
            $model->aux_cirugiastxt[$i]['anio'] = null;
            $model->aux_cirugiastxt[$i]['id'] = null;

            $model->aux_convulsionestxt[$i]['diagnostico'] = null;
            $model->aux_convulsionestxt[$i]['anio'] = null;
            $model->aux_convulsionestxt[$i]['id'] = null;

            $model->aux_diabetestxt[$i]['diagnostico'] = null;
            $model->aux_diabetestxt[$i]['anio'] = null;
            $model->aux_diabetestxt[$i]['id'] = null;

            $model->aux_hipertxt[$i]['diagnostico'] = null;
            $model->aux_hipertxt[$i]['anio'] = null;
            $model->aux_hipertxt[$i]['id'] = null;

            $model->aux_lumbalgiastxt[$i]['diagnostico'] = null;
            $model->aux_lumbalgiastxt[$i]['anio'] = null;
            $model->aux_lumbalgiastxt[$i]['id'] = null;

            $model->aux_nefrotxt[$i]['diagnostico'] = null;
            $model->aux_nefrotxt[$i]['anio'] = null;
            $model->aux_nefrotxt[$i]['id'] = null;

            $model->aux_pulmotxt[$i]['diagnostico'] = null;
            $model->aux_pulmotxt[$i]['anio'] = null;
            $model->aux_pulmotxt[$i]['id'] = null;

            $model->aux_hematicostxt[$i]['diagnostico'] = null;
            $model->aux_hematicostxt[$i]['anio'] = null;
            $model->aux_hematicostxt[$i]['id'] = null;

            $model->aux_traumatxt[$i]['diagnostico'] = null;
            $model->aux_traumatxt[$i]['anio'] = null;
            $model->aux_traumatxt[$i]['id'] = null;

            $model->aux_medicamentostxt[$i]['diagnostico'] = null;
            $model->aux_medicamentostxt[$i]['anio'] = null;
            $model->aux_medicamentostxt[$i]['id'] = null;

            $model->aux_protesistxt[$i]['diagnostico'] = null;
            $model->aux_protesistxt[$i]['anio'] = null;
            $model->aux_protesistxt[$i]['id'] = null;

            $model->aux_transtxt[$i]['diagnostico'] = null;
            $model->aux_transtxt[$i]['anio'] = null;
            $model->aux_transtxt[$i]['id'] = null;

            $model->aux_enf_ocular_txt[$i]['diagnostico'] = null;
            $model->aux_enf_ocular_txt[$i]['anio'] = null;
            $model->aux_enf_ocular_txt[$i]['id'] = null;

            $model->aux_enf_auditiva_txt[$i]['diagnostico'] = null;
            $model->aux_enf_auditiva_txt[$i]['anio'] = null;
            $model->aux_enf_auditiva_txt[$i]['id'] = null;

            $model->aux_fractura_txt[$i]['diagnostico'] = null;
            $model->aux_fractura_txt[$i]['anio'] = null;
            $model->aux_fractura_txt[$i]['id'] = null;

            $model->aux_amputacion_txt[$i]['diagnostico'] = null;
            $model->aux_amputacion_txt[$i]['anio'] = null;
            $model->aux_amputacion_txt[$i]['id'] = null;

            $model->aux_hernias_txt[$i]['diagnostico'] = null;
            $model->aux_hernias_txt[$i]['anio'] = null;
            $model->aux_hernias_txt[$i]['id'] = null;

            $model->aux_enfsanguinea_txt[$i]['diagnostico'] = null;
            $model->aux_enfsanguinea_txt[$i]['anio'] = null;
            $model->aux_enfsanguinea_txt[$i]['id'] = null;

            $model->aux_tumorescancer_txt[$i]['diagnostico'] = null;
            $model->aux_tumorescancer_txt[$i]['anio'] = null;
            $model->aux_tumorescancer_txt[$i]['id'] = null;

            $model->aux_enfpsico_txt[$i]['diagnostico'] = null;
            $model->aux_enfpsico_txt[$i]['anio'] = null;
            $model->aux_enfpsico_txt[$i]['id'] = null;
        }

        


        if($model->dAlergias){
            foreach($model->dAlergias as $key=>$detalle){
                $model->aux_alergiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_alergiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_alergiastxt[$key]['id'] = $detalle->id;
            }
        }

        if($model->dCardiopatias){
            foreach($model->dCardiopatias as $key=>$detalle){
                $model->aux_cardiotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_cardiotxt[$key]['anio'] = $detalle->anio;
                $model->aux_cardiotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dCirugias){
            foreach($model->dCirugias as $key=>$detalle){
                $model->aux_cirugiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_cirugiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_cirugiastxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dConvulsiones){
            foreach($model->dConvulsiones as $key=>$detalle){
                $model->aux_convulsionestxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_convulsionestxt[$key]['anio'] = $detalle->anio;
                $model->aux_convulsionestxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dDiabetes){
            foreach($model->dDiabetes as $key=>$detalle){
                $model->aux_diabetestxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_diabetestxt[$key]['anio'] = $detalle->anio;
                $model->aux_diabetestxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHipertension){
            foreach($model->dHipertension as $key=>$detalle){
                $model->aux_hipertxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hipertxt[$key]['anio'] = $detalle->anio;
                $model->aux_hipertxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dLumbalgias){
            foreach($model->dLumbalgias as $key=>$detalle){
                $model->aux_lumbalgiastxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_lumbalgiastxt[$key]['anio'] = $detalle->anio;
                $model->aux_lumbalgiastxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dNefropatias){
            foreach($model->dNefropatias as $key=>$detalle){
                $model->aux_nefrotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_nefrotxt[$key]['anio'] = $detalle->anio;
                $model->aux_nefrotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dPulmonares){
            foreach($model->dPulmonares as $key=>$detalle){
                $model->aux_pulmotxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_pulmotxt[$key]['anio'] = $detalle->anio;
                $model->aux_pulmotxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHematicas){
            foreach($model->dHematicas as $key=>$detalle){
                $model->aux_hematicostxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hematicostxt[$key]['anio'] = $detalle->anio;
                $model->aux_hematicostxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTraumatismos){
            foreach($model->dTraumatismos as $key=>$detalle){
                $model->aux_traumatxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_traumatxt[$key]['anio'] = $detalle->anio;
                $model->aux_traumatxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dMedicamentos){
            foreach($model->dMedicamentos as $key=>$detalle){
                $model->aux_medicamentostxt[$key]['medicamento'] = $detalle->descripcion;
                $model->aux_medicamentostxt[$key]['anio'] = $detalle->anio;
                $model->aux_medicamentostxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dProtesis){
            foreach($model->dProtesis as $key=>$detalle){
                $model->aux_protesistxt[$key]['tipo'] = $detalle->descripcion;
                $model->aux_protesistxt[$key]['anio'] = $detalle->anio;
                $model->aux_protesistxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTransfusiones){
            foreach($model->dTransfusiones as $key=>$detalle){
                $model->aux_transtxt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_transtxt[$key]['anio'] = $detalle->anio;
                $model->aux_transtxt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dOcular){
            foreach($model->dOcular as $key=>$detalle){
                $model->aux_enf_ocular_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enf_ocular_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enf_ocular_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dAuditiva){
            foreach($model->dAuditiva as $key=>$detalle){
                $model->aux_enf_auditiva_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enf_auditiva_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enf_auditiva_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dFractura){
            foreach($model->dFractura as $key=>$detalle){
                $model->aux_fractura_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_fractura_txt[$key]['anio'] = $detalle->anio;
                $model->aux_fractura_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dAmputacion){
            foreach($model->dAmputacion as $key=>$detalle){
                $model->aux_amputacion_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_amputacion_txt[$key]['anio'] = $detalle->anio;
                $model->aux_amputacion_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dHernias){
            foreach($model->dHernias as $key=>$detalle){
                $model->aux_hernias_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_hernias_txt[$key]['anio'] = $detalle->anio;
                $model->aux_hernias_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dSanguineas){
            foreach($model->dSanguineas as $key=>$detalle){
                $model->aux_enfsanguinea_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enfsanguinea_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enfsanguinea_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dTumores){
            foreach($model->dTumores as $key=>$detalle){
                $model->aux_tumorescancer_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_tumorescancer_txt[$key]['anio'] = $detalle->anio;
                $model->aux_tumorescancer_txt[$key]['id'] = $detalle->id;
            }
        }


        if($model->dPsicologicas){
            foreach($model->dPsicologicas as $key=>$detalle){
                $model->aux_enfpsico_txt[$key]['diagnostico'] = $detalle->descripcion;
                $model->aux_enfpsico_txt[$key]['anio'] = $detalle->anio;
                $model->aux_enfpsico_txt[$key]['id'] = $detalle->id;
            }
        }
        return $model;
    }

    private function limpiarDatoshc($model){
        if(isset($model->aux_programas) &&  $model->aux_programas != null && $model->aux_programas != ''){
            //dd($model->aux_programas);
            foreach($model->aux_programas as $key=>$programa){

               
                $programatrab = ProgramaTrabajador::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['id_programa'=>$programa['programa']])->andWhere(['status'=>1])->one();
                if(!$programatrab){
                    $programatrab = new ProgramaTrabajador();
                    $programatrab->id_trabajador = $model->id_trabajador;
                    $programatrab->id_programa = $programa['programa'];
                    $programatrab->conclusion = $programa['conclusion'];
                    $programatrab->status = 1;
                    $programatrab->save();

                    //dd($programatrab);
                }
            }            
        }

        //ANTECEDENTES HEREDO FAMILIARES INICIO*************************************
            //DIABETES-----------------------------------------------------------
            if($model->diabetesstxt == 'NO'){
                $model->diabetesstxt = null;
            }else if($model->diabetesstxt != '' && $model->diabetesstxt != null){
                $test = $this->Arraytocomas($model->diabetesstxt);
                $model->diabetesstxt = $test;
            }

            //HIPERTENSION-----------------------------------------------------------
            if($model->hipertensiontxt == 'NO'){
                $model->hipertensiontxt = null;
            }else if($model->hipertensiontxt != '' && $model->hipertensiontxt != null){
                $test = $this->Arraytocomas($model->hipertensiontxt);
                $model->hipertensiontxt = $test;
            }

            //CANCER-----------------------------------------------------------
            if($model->cancertxt == 'NO'){
                $model->cancertxt = null;
            }else if($model->cancertxt != '' && $model->cancertxt != null){
                $test = $this->Arraytocomas($model->cancertxt);
                $model->cancertxt = $test;
            }

            //NEFROPATIAS-----------------------------------------------------------
            if($model->nefropatiastxt == 'NO'){
                $model->nefropatiastxt = null;
            }else if($model->nefropatiastxt != '' && $model->nefropatiastxt != null){
                $test = $this->Arraytocomas($model->nefropatiastxt);
                $model->nefropatiastxt = $test;
            }

            //CARDIOPATIAS-----------------------------------------------------------
            if($model->cardiopatiastxt == 'NO'){
                $model->cardiopatiastxt = null;
            }else if($model->cardiopatiastxt != '' && $model->cardiopatiastxt != null){
                $test = $this->Arraytocomas($model->cardiopatiastxt);
                $model->cardiopatiastxt = $test;
            }

            //ENFERMEDADES REUMATICAS-----------------------------------------------------------
            if($model->reumatxt == 'NO'){
                $model->reumatxt = null;
            }else if($model->reumatxt != '' && $model->reumatxt != null){
                $test = $this->Arraytocomas($model->reumatxt);
                $model->reumatxt = $test;
            }

            //HEPATICOS-----------------------------------------------------------
            if($model->hepatxt == 'NO'){
                $model->hepatxt = null;
            }else if($model->hepatxt != '' && $model->hepatxt != null){
                $test = $this->Arraytocomas($model->hepatxt);
                $model->hepatxt = $test;
            }

            //TUBERCULOSIS-----------------------------------------------------------
            if($model->tubertxt == 'NO'){
                $model->tubertxt = null;
            }else if($model->tubertxt != '' && $model->tubertxt != null){
                $test = $this->Arraytocomas($model->tubertxt);
                $model->tubertxt = $test;
            }

            //PSIQUIATRICOS-----------------------------------------------------------
            if($model->psitxt == 'NO'){
                $model->psitxt = null;
            }else if($model->psitxt != '' && $model->psitxt != null){
                $test = $this->Arraytocomas($model->psitxt);
                $model->psitxt = $test;
            }
            //ANTECEDENTES HEREDO FAMILIARES FIN*************************************


            //DROGA-----------------------------------------------------
            if(isset($model->drogatxt) && $model->drogatxt != 'NO'){
                try {
                    if(count($model->drogatxt)>0){
                        $model->drogatxt = $test;
                    }else{
                        $model->drogatxt = null;
                    }
                } catch (\Throwable $th) {
                    $model->drogatxt = null;
                }
            }else{
                $model->drogatxt = null;
            }


             //EXPLORACION FÍSICA INICIO*********************************************
            //INSPECCION GENERAL-----------------------------------------------------
            if(isset($model->inspeccion) && $model->inspeccion != '' && count($model->inspeccion)>0){
                $test = $this->Arraytocomas($model->inspeccion);
                $model->inspeccion = $test;
            }else{
                $model->inspeccion = null;
            }

            //CABEZA-----------------------------------------------------
            if(isset($model->cabeza) && $model->cabeza != '' && count($model->cabeza)>0){
                $test = $this->Arraytocomas($model->cabeza);
                $model->cabeza = $test;
            }else{
                $model->cabeza = null;
            }

            //OIDOS-----------------------------------------------------
            if(isset($model->oidos) && $model->oidos != '' && count($model->oidos)>0){
                $test = $this->Arraytocomas($model->oidos);
                $model->oidos = $test;
            }else{
                $model->oidos = null;
            }

            //OJOS-----------------------------------------------------
            if(isset($model->ojos) && $model->ojos != '' && count($model->ojos)>0){
                $test = $this->Arraytocomas($model->ojos);
                $model->ojos = $test;
            }else{
                $model->ojos = null;
            }

            //BOCA/FARINGE-----------------------------------------------------
            if(isset($model->boca) && $model->boca != '' && count($model->boca)>0){
                $test = $this->Arraytocomas($model->boca);
                $model->boca = $test;
            }else{
                $model->boca = null;
            }

            //CUELLO-----------------------------------------------------
            if(isset($model->cuello) && $model->cuello != '' && count($model->cuello)>0){
                $test = $this->Arraytocomas($model->cuello);
                $model->cuello = $test;
            }else{
                $model->cuello = null;
            }

            //TORAX-----------------------------------------------------
            if(isset($model->torax) && $model->torax != '' && count($model->torax)>0){
                $test = $this->Arraytocomas($model->torax);
                $model->torax = $test;
            }else{
                $model->torax = null;
            }
            
            //ABDOMEN-----------------------------------------------------
            if(isset($model->abdomen) && $model->abdomen != '' && count($model->abdomen)>0){
                $test = $this->Arraytocomas($model->abdomen);
                $model->abdomen = $test;
            }else{
                $model->abdomen = null;
            }

            //MIEMBROS SUPERIORES-----------------------------------------------------
            if(isset($model->superior) && $model->superior != '' && count($model->superior)>0){
                $test = $this->Arraytocomas($model->superior);
                $model->superior = $test;
            }else{
                $model->superior = null;
            }

            //MIEMBROS INFERIORES-----------------------------------------------------
            if(isset($model->inferior) && $model->inferior != '' && count($model->inferior)>0){
                $test = $this->Arraytocomas($model->inferior);
                $model->inferior = $test;
            }else{
                $model->inferior = null;
            }

            //COLUMNA-----------------------------------------------------
            if(isset($model->columna) && $model->columna != '' && count($model->columna)>0){
                $test = $this->Arraytocomas($model->columna);
                $model->columna = $test;
            }else{
                $model->columna = null;
            }

            //NEUROLOGICOS-----------------------------------------------------
            if(isset($model->txtneurologicos) && $model->txtneurologicos != '' && count($model->txtneurologicos)>0){
                $test = $this->Arraytocomas($model->txtneurologicos);
                $model->txtneurologicos = $test;
            }else{
                $model->txtneurologicos = null;
            }
            return $model;

    }

    private function checarEstudios($model){
        $status = 1;

        $estudios = Hccohcestudio::find()->where(['id_hccohc'=>$model->id])->all();

        $total_estudios = count($estudios);
        $total_conclusiones = 0;
        $total_documentos = 0;

        //dd($model->testudios);

        if($estudios){
            foreach ($estudios as $key=>$estudio){
                if(isset($estudio->conclusion) && $estudio->conclusion != null && $estudio->conclusion != ''){
                    $total_conclusiones ++;
                }
                if(isset($estudio->evidencia) && $estudio->evidencia != null && $estudio->evidencia != ''){
                    $total_documentos ++;
                }
            }

            if($total_conclusiones == $total_estudios && $total_documentos == $total_estudios){
                $status = 2; 
            }

        } else{
            $status = 2;
        }
        //dd('Total de estudios: '.$total_estudios.' | Conclusiones: '.$total_conclusiones.' | Documentos: '.$total_documentos);
        
        return $status;
    }

    public function actionInfopuesto(){
        $id = Yii::$app->request->post('id');
        $puesto = Puestostrabajo::findOne(['id'=>$id]);
        
        return \yii\helpers\Json::encode(['puesto' => $puesto]);
    }


    public function actionConsentimientopdf($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $model = Hccohc::findOne($id);

        $image = 'resources/images/empty.png';
        
        $css ='.text-indigo {
            color: #6d2efc;
        }
        body{font-family:Arial, Helvetica, sans-serif;}';

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

    private function calculaVigencia($model){
        date_default_timezone_set('America/Costa_Rica');
        $vigencia = Vigencias::findOne($model->vigencia);
        $fecha = $model->fecha;
        $vigenciafinal =  null;
       
        if(true){
            if($vigencia){
                $vigenciafinal = $model->fecha;
                

                if($vigencia->cantidad_dias > 0 && $vigencia->cantidad_dias != null && $vigencia->cantidad_dias != '' && $vigencia->cantidad_dias != ' '){
                    $vigenciafinal = date('Y-m-d', strtotime($vigenciafinal. ' + '.$vigencia->cantidad_dias.' days'));
                }
                if($vigencia->cantidad_meses > 0 && $vigencia->cantidad_meses != null && $vigencia->cantidad_meses != '' && $vigencia->cantidad_meses != ' '){
                    $vigenciafinal = date('Y-m-d', strtotime($vigenciafinal. ' + '.$vigencia->cantidad_meses.' months'));
                }
                if($vigencia->cantidad_anios > 0 && $vigencia->cantidad_anios != null && $vigencia->cantidad_anios != '' && $vigencia->cantidad_anios != ' '){
                    $vigenciafinal = date('Y-m-d', strtotime($vigenciafinal. ' + '.$vigencia->cantidad_anios.' year'));
                }
                //dd($fecha,$vigenciafinal,$vigencia);
                $model->fecha_vigencia = $vigenciafinal;
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


    protected function getNombreinfo($model,$id_empresa,$id_medico,$atributo_empresa,$atributo_medico){
        if($model){
            $get_empresa = Empresas::findOne($id_empresa);
            $get_medico = Usuarios::findOne($id_medico);

            if($get_empresa){
                $model[$atributo_empresa] = $get_empresa->comercial;
            }

            if($get_medico){
                $model[$atributo_medico] = $get_medico->name;
            }

            try {
                $model->save(false);
            } catch (\Throwable $th) {
                //dd($model);
            }
            
            
        }
    }


    public function actionRefreshnames()
    {
        $hcs = Hccohc::find()->all();
        ///dd($hcs);
        if($hcs){
            foreach($hcs as $key=>$model){
                $this->getNombreinfo($model,$model->id_empresa,$model->id_medico,'nombre_empresa','nombre_medico');
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

        if($model->trabajador){
            $trash->contenido = $model->trabajador->nombre.' '.$model->trabajador->apellidos;
            $trash->save();
        }
        //dd($trash);

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

    

}