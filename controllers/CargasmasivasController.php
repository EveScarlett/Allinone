<?php

namespace app\controllers;

use app\models\Cargasmasivas;
use app\models\CargasmasivasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Carbon\Carbon;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\Poes;
use app\models\Poeestudio;
use Yii;

use app\models\Trabajadores;
use app\models\Puestostrabajo;
use app\models\Hccohc;
use app\models\Areas;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;


/**
 * CargasmasivasController implements the CRUD actions for Cargasmasivas model.
 */
class CargasmasivasController extends Controller
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
     * Lists all Cargasmasivas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CargasmasivasSearch();
        $searchModel->tipo = 1;
        if(Yii::$app->user->identity->empresa_all == 0){
            $searchModel->id_empresa = Yii::$app->user->identity->id_empresa;
        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

        /**
     * Lists all Cargasmasivas models.
     *
     * @return string
     */
    public function actionIndexpoe()
    {
        $searchModel = new CargasmasivasSearch();
        $searchModel->tipo = 2;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('indexpoe', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cargasmasivas model.
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

    public function actionViewpoe($id)
    {
        return $this->render('viewpoe', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Cargasmasivas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cargasmasivas();

        //date_default_timezone_set('America/Mazatlan');
        $model->scenario = 'carga';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);
               
                $request = Yii::$app->request;
                

                $archivo = UploadedFile::getInstance($model,'file_excel');

                    $model->create_date = date('Y-m-d');
                    $model->create_user = Yii::$app->user->identity->id;
                    $model->file_excel = 'aa';
                    $model->tipo = 1;
                    $model->save();
                
                    $dir0 = __DIR__ . '/../web/cargas/';
                    $directorios = ['0'=>$dir0];
                
                    $this->actionCarpetas( $directorios);
                
                    if($archivo){
                        $nombre_archivo = 'CARGAMASIVA_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                        $model->archivo = $nombre_archivo;
                        $model->save();
                    } 

                
                if($archivo){
                    define('UPLOAD_DIR', '../web/cargas/');
                    $convertir = UPLOAD_DIR . $nombre_archivo;
                    $fn = fopen($convertir,"r");
                    $cantidad_lineas = 0;
                    $cantidad_success = 0;
                    $cantidad_error = 0;
                    
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
                        $curp = '';
                        $rfc = '';
                        $correo = '';

                        $extra1 = '';
                        $extra2 = '';
                        $extra3 = '';
                        $extra4 = '';
                        $extra5 = '';
                        $extra6 = '';
                        $extra7 = '';
                        $extra8 = '';
                        $extra9 = '';
                        $extra10 = '';

                        $nivel1 = '';
                        $nivel2 = '';
                        $nivel3 = '';
                        $nivel4 = '';

                        $puesto_contable = '';
                        $salario_puesto = '';

                        //dd($palabras);

                        $cantidad_lineas ++;
                        
                        foreach ($palabras as $key => $palabra) {
                            $check = trim($palabra);
                            $check = str_replace("  "," ",$check);
                            $check = $this->remove_utf8_bom($check);
                            //$check = utf8_encode($check);
                            
                        
                            if($key == 0){
                                $nombre = trim($check);
                                $nombre = $this->remove_utf8_bom($nombre);
                                $nombre = trim($nombre);
                                $nombre = strtoupper($nombre);
                            } else if($key == 1){
                                $apellidos = trim($check);
                                $apellidos = $this->remove_utf8_bom($apellidos);
                                $apellidos = trim($apellidos);
                                $apellidos = strtoupper($apellidos);
                            } else if($key == 2){
                                $sexo = trim($check);
                                //$sexo = strtoupper($sexo);
            
                                if($sexo == 'MASCULINO' || $sexo == 'masculino' || $sexo == 'M' || $sexo == 'm'){
                                    $sexo = 1;
                                } else if($sexo == 'FEMENINO' || $sexo == 'femenino' || $sexo == 'F' || $sexo == 'f'){
                                    $sexo = 2;
                                } else if($sexo == 'OTRO' || $sexo == 'otro' || $sexo == 'O' || $sexo == 'o'){
                                    $sexo = 3;
                                } else {
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

                                

                                //Solo empezar a checar si es que seleccionaron columna extra, si no ignorar
                                if(isset($model->$nombre_extra)){
                                    //dd('SI NOMBRE EXTRA: '.$nombre_extra.' valor: '.$model->$nombre_extra);
                                    
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
                                        $pais = ' '.$extra;
                                    } else if($model->$nombre_extra == 11){//CP.
                                        $cp = ''.$extra;
                                    } else if($model->$nombre_extra == 12){//Fecha Contratacion
                                        $fecha_contratacion = $extra;
                                    } else if($model->$nombre_extra == 13){//Estado Civil
                                        if($extra == 'SOLTERO' || $extra == 'Soltero' || $extra == 'soltero'){
                                            $estado_civil = 1;
                                        } else if($extra == 'CASADO' || $extra == 'Casado' || $extra == 'casado'){
                                            $estado_civil = 2;
                                        } else if($extra == 'VIUDO' || $extra == 'Viudo' || $extra == 'viudo'){
                                            $estado_civil = 3;
                                        } else if($extra == 'UNIÓN LIBRE' || $extra == 'Unión Libre' || $extra == 'Unión libre' || $extra == 'unión libre' || $extra == 'UNION LIBRE' || $extra == 'Union Libre' || $extra == 'Union libre' || $extra == 'union libre'){
                                            $estado_civil = 4;
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
                                        $rt= $this->stringreplace($extra);

                                        $areast = Areas::find()->where(['like','area',$rt])->andWhere(['id_empresa'=>$model->id_empresa])->one();
                                        if($areast){
                                            $area =$areast->id;
                                        } else {
                                            $areast = new Areas();
                                            $areast->id_empresa = $model->id_empresa;
                                            $areast->area = $rt;
                                            $areast->status = 1;
                                            $areast->save();

                                            if($areast){
                                                $area =$areast->id;
                                            }
                                        }
                                    } else if($model->$nombre_extra == 20){//Puesto
                                        $rt= $this->stringreplace($extra);

                                        $puestot = Puestostrabajo::find()->where(['like','nombre',$rt])->andWhere(['id_empresa'=>$model->id_empresa])->one();
                                        if($puestot){
                                            $puesto =$puestot->id;
                                        } else{
                                            $puestot = new Puestostrabajo();
                                            $puestot->id_empresa = $model->id_empresa;
                                            $puestot->nombre = $rt;
                                            $puestot->status = 1;
                                            $puestot->save();

                                            if($puestot){
                                                $puesto =$puestot->id;
                                            }
                                        }
                                    } else if($model->$nombre_extra == 21){//Teamleader
                                        $teamleader = ''.$extra;
                                    } else if($model->$nombre_extra == 22){//Fecha Inicio
                                        $fecha_inicio = $extra;
                                    } else if($model->$nombre_extra == 23){//Curp
                                        $curp = $extra;
                                    } else if($model->$nombre_extra == 24){//Rfc
                                        $rfc = $extra;
                                    } else if($model->$nombre_extra == 25){//Correo
                                        $correo = $extra;
                                    } else if($model->$nombre_extra == 26){//Extra 1 - Ubicación
                                        $rt= $this->stringreplace($extra);

                                        $ubicaciont = Ubicaciones::find()->where(['like','ubicacion',$rt])->andWhere(['id_empresa'=>$model->id_empresa])->one();
                                        
                                        if($ubicaciont){
                                            $extra1 =$ubicaciont->id;
                                        } else {
                                            $ubicaciont = new Ubicaciones();
                                            $ubicaciont->id_empresa = $model->id_empresa;
                                            $ubicaciont->ubicacion = $rt;
                                            $ubicaciont->status = 1;
                                            $ubicaciont->save();

                                            if($ubicaciont){
                                                $extra1 =$ubicaciont->id;
                                            }
                                        }

                                    } else if($model->$nombre_extra == 27){//Extra 2 - País
                                        
                                        $rt= $this->stringreplace($extra);

                                        $paist = Paises::find()->where(['like','pais',$rt])->one();
                                        if($paist){
                                            $extra2 =$paist->id;

                                            $paisempresa = Paisempresa::find()->where(['id_empresa' => $model->id_empresa])->andWhere(['id_pais' => $paist->id])->one();
                                            if(!$paisempresa){
                                                $paisempresa = new Paisempresa();  
                                                $paisempresa->id_empresa = $model->id_empresa;
                                                $paisempresa->id_pais = $paist->id;
                                                $paisempresa->status = 1;
                                                $paisempresa->save();
                                            }
                                           
                                        }

                                        //dd($rt,$ubicaciont,$extra1,$rt,$paist,$extra2);
                                    } else if($model->$nombre_extra == 28){//Extra 3
                                        $extra3 = $extra;
                                    } else if($model->$nombre_extra == 29){//Extra 4
                                        $extra4 = $extra;
                                    } else if($model->$nombre_extra == 30){//Extra 5
                                        $extra5 = $extra;
                                    } else if($model->$nombre_extra == 31){//Extra 6
                                        $extra6 = $extra;
                                    } else if($model->$nombre_extra == 32){//Extra 7
                                        $extra7 = $extra;
                                    } else if($model->$nombre_extra == 33){//Extra 8
                                        $extra8 = $extra;
                                    } else if($model->$nombre_extra == 34){//Extra 9
                                        $extra9 = $extra;
                                    } else if($model->$nombre_extra == 35){//Extra 10
                                        $extra10 = $extra;
                                    } else if($model->$nombre_extra == 36){//Nivel 1 
                                        
                                        $rt= $this->stringreplace($extra);

                                        $pais_t = Paises::find()->where(['like','pais',$rt])->one();
                                        if($pais_t){
                                            $data_nivel1 = NivelOrganizacional1::find()->where(['id_pais'=>$pais_t->id])->andWhere(['id_empresa' => $model->id_empresa])->andWhere(['status'=>1])->one();
                                            if($data_nivel1){
                                                $nivel1 = $data_nivel1->id;
                                            }
                                            //dd($data_nivel1);
                                        }
                                        //dd($pais);
                                        //dd('entra a 36');
                                    } else if($model->$nombre_extra == 37){//Nivel 2 
                                        
                                        $rt= $this->stringreplace($extra);

                                        $data_nivel2 = NivelOrganizacional2::find()->where(['like','nivelorganizacional2',$rt])->andWhere(['id_empresa' => $model->id_empresa])->andWhere(['status'=>1])->one();
                                        if($data_nivel2){
                                            $nivel2 = $data_nivel2->id;
                                        }
                                    } else if($model->$nombre_extra == 38){//Nivel 2 
                                        
                                        $rt= $this->stringreplace($extra);

                                        $data_nivel3 = NivelOrganizacional3::find()->where(['like','nivelorganizacional3',$rt])->andWhere(['id_empresa' => $model->id_empresa])->andWhere(['status'=>1])->one();
                                        if($data_nivel3){
                                            $nivel3 = $data_nivel3->id;
                                        }
                                    } else if($model->$nombre_extra == 39){//Nivel 2 
                                        
                                        $rt= $this->stringreplace($extra);

                                        $data_nivel4 = NivelOrganizacional4::find()->where(['like','nivelorganizacional4',$rt])->andWhere(['id_empresa' => $model->id_empresa])->andWhere(['status'=>1])->one();
                                        if($data_nivel4){
                                            $nivel4 = $data_nivel4->id;
                                        }
                                    } else if($model->$nombre_extra == 40){//Puesto Contable
                                        $puesto_sueldo = ''.$extra;
                                    } else if($model->$nombre_extra == 41){//Sueldo Puesto
                                        if($extra != null && $extra != '' && $extra != ' '){
                                            $salario_puesto = floatval($extra);
                                        }
                                        
                                    }
                                } else {
                                    dd('NO NOMBRE EXTRA: '.$nombre_extra);
                                }

                            }
                        }
                        //dd('Nombre:'.$nombre.' | Apellidos:'.$apellidos.' | Sexo:'.$sexo.' | Fecha Nacimiento:'.$fecha_nacimiento.' | Edad:'.$edad.'num_trabajador: '.$num_trabajador.' | num_imss: '.$num_imss.' | celular: '.$celular.' | contacto_emergencia: '.$contacto_emergencia.' | direccion: '.$direccion.' | colonia: '.$colonia.' | ciudad: '.$ciudad.' | municipio: '.$municipio.' | estado: '.$estado.' | pais: '.$pais.' | cp: '.$cp.' | fecha_contratacion: '.$fecha_contratacion.' | estado_civil: '.$estado_civil.' | nivel_lectura: '.$nivel_lectura.' | nivel_escritura: '.$nivel_escritura.' | escolaridad: '.$escolaridad.' | escolaridad: '.$escolaridad.' | ruta: '.$ruta.' | parada: '.$parada.' | areast: '.$area.' | puestot: '.$puesto.' | teamleader: '.$teamleader.' | fecha_inicio: '.$fecha_inicio);
                                    
                        //dd('Nombre:'.$nombre.' | Apellidos:'.$apellidos.' | Sexo:'.$sexo.' | Fecha Nacimiento:'.$fecha_nacimiento.' | Edad:'.$edad);
                        //&& isset($apellidos) && $apellidos != ''
                        if(isset($nombre) && $nombre != '' && isset($fecha_nacimiento) && $fecha_nacimiento != '' && isset($edad) && $edad != ''){
                            //&& isset($sexo) && $sexo != ''
                            //dd('Entra aqui');
                            $trabajador = new Trabajadores();
                            $trabajador->tipo_registro = 2;
                            $trabajador->id_cargamasiva = $model->id;
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

                            $trabajador->puesto_contable = $puesto_sueldo;
                            $trabajador->puesto_sueldo = $salario_puesto;

                            $trabajador->teamleader = $teamleader;
                            $trabajador->fecha_iniciop = $fecha_inicio;
                            $trabajador->curp = $curp;
                            $trabajador->rfc = $rfc;
                            $trabajador->correo = $correo;

                            $trabajador->dato_extra1 = ''.$extra1;
                            $trabajador->dato_extra2 = ''.$extra2;
                            $trabajador->dato_extra3 = $extra3;
                            $trabajador->dato_extra4 = $extra4;
                            $trabajador->dato_extra5 = $extra5;
                            $trabajador->dato_extra6 = $extra6;
                            $trabajador->dato_extra7 = $extra7;
                            $trabajador->dato_extra8 = $extra8;
                            $trabajador->dato_extra9 = $extra9;
                            $trabajador->dato_extra10 = $extra10;

                            $trabajador->id_nivel1 = $nivel1;
                            $trabajador->id_nivel2 = $nivel2;
                            $trabajador->id_nivel3 = $nivel3;
                            $trabajador->id_nivel4 = $nivel4;

                            $trabajador->create_user = Yii::$app->user->identity->id;
                            $trabajador->create_date = date('Y-m-d H:i:s');

                            //dd($nivel1,$nivel2,$nivel3,$nivel4);
                            try {
                                $trabajador->save();
                                
                                //dd($trabajador);
                            } catch (\Throwable $th) {
                                dd($th);
                            }
                            
                            //dd($trabajador);
                            //CAMPOS EXTRA NO OBLIGATORIOS-------------------------

                            if($trabajador){
                                $cantidad_success ++;
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

                                $hc->id_nivel1 = $trabajador->id_nivel1;
                                $hc->id_nivel2 = $trabajador->id_nivel2;
                                $hc->id_nivel3 = $trabajador->id_nivel3;
                                $hc->id_nivel4 = $trabajador->id_nivel4;

                                $hc->save();
                            } else{
                                $cantidad_error ++;
                            }
                        } else{
                            $cantidad_error ++;
                            //dd('Nombre:'.$nombre.' | Apellidos:'.$apellidos.' | Sexo:'.$sexo.' | Fecha Nacimiento:'.$fecha_nacimiento.' | Edad:'.$edad);
                        }
                    }

                    $model->total_trabajadores = $cantidad_lineas-1;
                    $model->total_success = $cantidad_success;
                    $model->total_error = $cantidad_error-1;
                    $model->save();
                    }
                
                    return $this->redirect(['index']);
                
            }
        } else {
            //$model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Cargasmasivas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreatepoe()
    {
        $model = new Cargasmasivas();

        //date_default_timezone_set('America/Mazatlan');
        $model->scenario = 'cargapoe';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //dd($model);
                $request = Yii::$app->request;

                $archivo = UploadedFile::getInstance($model,'file_excel');

                $model->create_date = date('Y-m-d');
                $model->create_user = Yii::$app->user->identity->id;
                $model->file_excel = 'aa';
                $model->tipo = 2;
                $model->save();
                //dd($model);

                //dd($archivo);
                $dir0 = __DIR__ . '/../web/cargas/';
                $directorios = ['0'=>$dir0];
                
                $this->actionCarpetas( $directorios);
                
                if($archivo){
                    $nombre_archivo = 'CARGAMASIVA_POE_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[0].'/'. $nombre_archivo);
                    $model->archivo = $nombre_archivo;
                    $model->save();
                } 

                
                if($archivo){
                    define('UPLOAD_DIR', '../web/cargas/');
                    $convertir = UPLOAD_DIR . $nombre_archivo;
                    $fn = fopen($convertir,"r");
                    $cantidad_lineas = 0;
                    $cantidad_success = 0;
                    $cantidad_error = 0;
                    
                    while(! feof($fn))  {
                    
                        $result = fgets($fn);
                        $result = trim($result);
                        $result = str_replace("  "," ",$result);
                    
                        $estudios = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        $tipo_poe = null;
                    
                        $palabras = explode(",", $result);
                    

                        $cantidad_lineas ++;
                        //dd($palabras);
                        foreach ($palabras as $key => $palabra) {
                            
                            $check = trim($palabra);
                            $check = str_replace("  "," ",$check);
                            //$check = utf8_encode($check);
                        
                            if($key == 0){
                                $dato1 = trim($check);
                                $dato1 = $this->remove_utf8_bom($dato1);
                                $dato1 = trim($dato1);
                                $trabajador = null;
                                //De acuerdo al identificador seleccionado buscar el trabajador
                                if($model->id_trabajador == 1){
                                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id'=>$dato1])->one();
                                    //dd('Se seleccionó Busqueda por ID: '.$dato1);
                                } else if($model->id_trabajador == 2){
                                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['num_trabajador'=>$dato1])->one();
                                    //dd('Se seleccionó Busqueda por Número de Trabajador: '.$dato1);
                                } else if($model->id_trabajador == 3){
                                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['like', 'Concat(nombre," ", apellidos)', $dato1])->one();
                                    //dd('Se seleccionó Busqueda por Nombre de Trabajador: '.$dato1);
                                }
                                
                                
                            } else if($key > 0){
                                $datoextra = $model['extra'.$key];
                                $index = $key-1;
                                //dd('$key',$key,'$check',$check,'$model',$model,'$datoextra',$datoextra);
                                
                                if($datoextra != 10000){//si no es el tipo de poe, es decir si son estudios
                                    

                                    $estudio = trim($check);
                                    $estudio = $this->remove_utf8_bom($estudio);
                                    $estudio = trim($estudio);

                                    //Solo empezar a checar si es que seleccionaron columna extra, si no ignorar
                                    if(isset($estudio)){
                                        if($estudio == 'SI' || $estudio == 'Si' || $estudio == 'Sí' || $estudio == 'si' || $estudio == '1'){//Estudio
                                            $estudios[$index] = 1;
                                        }
                                    }
                                } else {
                                    $tipo_poe = null;

                                    if($check == 'NUEVO INGRESO' || $check == 'Nuevo Ingreso' || $check == 'nuevo ingreso' || $check == '1'){
                                        $tipo_poe = 1;
                                    } else if($check == 'POES PERIODICOS' || $check == 'Poes Periodicos' || $check == 'poes periodicos' || $check == '2'){
                                        $tipo_poe = 2;
                                    } else if($check == 'SALIDA' || $check == 'Salida' || $check == 'salida' || $check == '3'){
                                        $tipo_poe = 3;
                                    }
                                }

                            }
                        }
                        //dd($trabajador,$tipo_poe,$estudios,$model);
                        $hayestudio = in_array(1, $estudios);

                        //dd('Id trabajador: '.$trabajador->id.' | Hay estudios?: '. $hayestudio);
                        if(isset($trabajador) && $hayestudio){
                            //dd( $estudios);
                            $poe = new Poes();
                            $poe->origen = 3;

                            if($tipo_poe != null && $tipo_poe != '' && $tipo_poe != ' '){
                                $poe->tipo_poe = $tipo_poe;
                            } else {
                                $poe->tipo_poe = 2;
                            }
                            
                            $poe->id_ordenpoetrabajador = $model->id;
                            $poe->id_empresa = $model->id_empresa;
                            $poe->id_trabajador = $trabajador->id;
                            $poe->anio = $model->anio;
                            $poe->create_date = $model->create_date;
                            $poe->create_user = $model->create_user;
                            $poe->status = 0;
            
                            if($trabajador){//Guardar solo si existe el trabajador
                                $poe->nombre = $trabajador->nombre;
                                $poe->apellidos = $trabajador->apellidos;
                                $poe->sexo = $trabajador->sexo;
                                $poe->fecha_nacimiento = $trabajador->fecha_nacimiento;
                                $poe->num_imss = $trabajador->num_imss;
                                $poe->num_trabajador = $trabajador->num_trabajador;
                                $poe->id_puesto = $trabajador->id_puesto;
                                $poe->id_area = $trabajador->id_area;

                                $poe->id_nivel1 = $trabajador->id_nivel1;
                                $poe->id_nivel2 = $trabajador->id_nivel2;
                                $poe->id_nivel3 = $trabajador->id_nivel3;
                                $poe->id_nivel4 = $trabajador->id_nivel4;
                                $poe->save();
            
                                if($poe){
                                    $cantidad_success ++;

                                    foreach($estudios as $key2=>$estudio){

                                        if($estudio == 1){
                                            $index = $key2+1;
                                            $nombre_extra = 'extra'.$index;

                                            $poeestudio = Servicios::find()->where(['id'=>$model->$nombre_extra])->one();

                                            if($poeestudio){
                                                $pe = new Poeestudio();
                                                $pe->id_poe = $poe->id;
        
                                                //Aqui revisar cual fue el estudio que se registró, y si puso SI o NO
                                                $pe->id_tipo = $poeestudio->id_tiposervicio;
                                                $pe->id_estudio = $poeestudio->id;
                                                //------------------------------------------------------------------
                                                $pe->condicion = 100;
                                                $pe->evolucion = 100;
                                                $pe->status = 0;
                                                $pe->save();
                                            }

                                        }
                                    }
                                } else{
                                    //dd($trabajador);
                                    $cantidad_error ++;
                                }
                            } else{
                                //dd($trabajador);
                                $cantidad_error ++;
                            }
                
                        } else{
                            //dd($trabajador);
                            $cantidad_error ++;
                        }
                    }

                    $model->total_trabajadores = $cantidad_lineas-1;
                    $model->total_success = $cantidad_success;
                    $model->total_error = $cantidad_error-1;
                    $model->save();
                }
                //$param = $request->getBodyParam("Trabajadores");
                return $this->redirect(['indexpoe']);
                
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('createpoe', [
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
     * Updates an existing Cargasmasivas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cargasmasivas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //dd('model');
        $model = $this->findModel($id);

        //dd($model);

        if($model->tipo == 1){
            if($model->trabajadores){
                foreach($model->trabajadores as $key=>$trabajador){
                    
                    if($trabajador){
                    
                    //Borrar las HC del trabajador si es que tiene
                    if($trabajador->historiasclinicas){
                        foreach($trabajador->historiasclinicas as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar las Alergias del trabajador si es que tiene
                    if($trabajador->alergias){
                        foreach($trabajador->alergias as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar las Consultas del trabajador si es que tiene
                    if($trabajador->cuestionarios){
                        foreach($trabajador->cuestionarios as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar los Documentos Trabajadores del trabajador si es que tiene
                    if($trabajador->documentostrabajadores){
                        foreach($trabajador->documentostrabajadores as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar El Historial Documentos del trabajador si es que tiene
                    if($trabajador->historialdocumentos){
                        foreach($trabajador->historialdocumentos as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar las Ordenes Poes del trabajador si es que tiene
                    if($trabajador->ordenespoes){
                        foreach($trabajador->ordenespoes as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar los Poes del trabajador si es que tiene
                    if($trabajador->poes){
                        foreach($trabajador->poes as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar los Programas del trabajador si es que tiene
                    if($trabajador->programas){
                        foreach($trabajador->programas as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar los Puestos del trabajador si es que tiene
                    if($trabajador->puestos){
                        foreach($trabajador->puestos as $key=>$dato){
                            $dato->delete();
                        }
                    }
    
                    //Borrar los Estudios del trabajador si es que tiene
                    if($trabajador->estudios){
                        foreach($trabajador->estudios as $key=>$dato){
                            $dato->delete();
                        }
                    }
                
                }

                
                $trabajador->delete();
                }
            }

        } else{
            if($model->poes){
                foreach($model->poes as $key=>$poe){
                    //Borrar los Estudios del trabajador si es que tiene
                    if($poe->estudios){
                        foreach($poe->estudios as $key=>$dato){
                            $dato->delete();
                        }
                    }
                    $poe->delete();
                }
            }
        }

        $model->delete();

        if($model->tipo == 1){
            return $this->redirect(['index']);
        } else{
            return $this->redirect(['indexpoe']);
        }
        
    }

    public function actionTutorial()
    {
        return $this->renderAjax('tutorial', [
        ]);
    }

    public function actionExcel()
    {
        return $this->renderAjax('excel', [
        ]);
    }

    /**
     * Finds the Cargasmasivas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cargasmasivas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cargasmasivas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    function remove_utf8_bom($text){
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    function stringreplace($extra){
        $extra = str_replace("á", "a", $extra);
        $extra = str_replace("é", "e", $extra);
        $extra = str_replace("í", "i", $extra);
        $extra = str_replace("ó", "o", $extra);
        $extra = str_replace("ú", "u", $extra);

        $extra = strtoupper($extra);
       
        return $extra;
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

}