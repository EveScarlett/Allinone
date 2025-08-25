<?php

namespace app\controllers;

use app\models\ExtraccionBd;
use app\models\ExtraccionBd2Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

use app\models\Trabajadores;
use app\models\Empresas;
use app\models\Puestostrabajo;
use app\models\Poes;
use app\models\Servicios;
use app\models\TipoServicios;
use app\models\Poeestudio;
use app\models\Areascuestionario;
use app\models\Cuestionario;
use app\models\DetalleCuestionario;
use app\models\Preguntas;
use app\models\Hccohc;
use app\models\Detallehc;
use app\models\Hccohcestudio;


/**
 * ExtraccionBdController implements the CRUD actions for ExtraccionBd model.
 */
class ExtraccionbdController extends Controller
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
     * Lists all ExtraccionBd models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExtraccionBd2Search();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExtraccionBd model.
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

    public function actionPoes()
    {
        $model = new ExtraccionBd();

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT * FROM poes WHERE empresa=:empresa')
        ->bindValues($params)
        ->queryAll();

        $nuevos_poes = [];
        $errores_poes = [];
        $array_estudios = [];
        $nuevos_puestos = [];

        $nuevas_categorias = [];
        $nuevos_estudios = [];


        foreach($rows as $key=>$bd_poes){
            
            $id_origen = $bd_poes['id'];
            $origen = null;
            $id_empresa = null;
            $id_trabajador = null;
            $nombre = null;
            $apellidos = null;
            $num_trabajador = null;
            $sexo = null;
            $fecha_nacimiento = null;
            $anio = null;
            $num_imss = null;
            $id_puesto = null;
            $id_area = null;
            $id_ubicacion = null;
            $observaciones = null;
            $create_date = null;
            $create_user = null;
            $update_date = null;
            $update_user = null;
            $id_area = null;
            $id_area = null;
            $id_area = null;
            $status = null;
            $cerrar_entrega = null;
            $origen_extraccion = null;
           

            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){

                $nombre = $bd_poes['nombre'];
                $apellidos = $bd_poes['apellidos'];
                $nombre = trim($nombre);
                $apellidos = trim($apellidos);

                try {
                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                } catch (\Throwable $th) {
                    $nombre = mb_convert_encoding($nombre, "UTF-8", "LATIN1");
                    $apellidos = mb_convert_encoding($apellidos, "UTF-8", "LATIN1");

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                }

                $poe = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_origen])->one();

                if($trabajador){
                    if(!$poe){
                        //dd($bd_poes);
                        $origen = 3;
                        $id_empresa = $empresa->id;
                        $id_trabajador = $trabajador->id;
                        $nombre = $trabajador->nombre;
                        $apellidos = $trabajador->apellidos;
                        $num_trabajador = $bd_poes['nempleado'];
                        $sexo =  $trabajador->sexo;
                        $fecha_nacimiento = $trabajador->fecha_nacimiento;
                        $anio = $bd_poes['anio_poes'];
                        $num_imss = $trabajador->num_imss;

                        $puesto_new = $bd_poes['puesto_trabajo'];
                        $puesto_new = trim($puesto_new);

                        try {
                            $puesto = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$puesto_new])->one();
                        } catch (\Throwable $th3) {
                            $puesto_new = mb_convert_encoding($puesto_new, "UTF-8", "LATIN1");
                            $puesto = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$puesto_new])->one();
                        }
                        
                        if(!$puesto){
                            if($puesto_new != null && $puesto_new != '' && $puesto_new != ' '){
                                if(!in_array($puesto_new, $nuevos_puestos)){
                                    array_push($nuevos_puestos, $puesto_new);
                                }
                                $puesto = new Puestostrabajo();
                                $puesto->id_empresa = $empresa->id;
                                $puesto->nombre = $puesto_new;
                                $puesto->status = 0;
                                $puesto->create_date = date('Y-m-d');
                                $puesto->save();
                            }
                        }
                    
                        if($puesto){
                            $id_puesto = $puesto->id;
                        }
                        
                        $id_area = null;
                        $id_ubicacion = null;
                        $observaciones = $bd_poes['comentario'];
                        $create_date = $bd_poes['fecha_capturo'];
                        $create_user = null;
                        $update_date = $bd_poes['fecha_modifico'];
                        $update_user = null;
                        $status = 0;
                        $cerrar_entrega = 1;
                        $origen_extraccion = 1;
                        $id_origen = $id_origen;


                        $poe = new Poes();
                        $poe->origen = $origen;
                        $poe->id_empresa = $id_empresa;
                        $poe->id_trabajador = $id_trabajador;
                        $poe->nombre = $nombre;
                        $poe->apellidos = $apellidos;
                        $poe->num_trabajador = $num_trabajador;
                        $poe->sexo = $sexo;
                        $poe->fecha_nacimiento = $fecha_nacimiento;
                        $poe->anio = $anio;
                        $poe->num_imss = $num_imss;
                        $poe->id_puesto = $id_puesto;
                        $poe->id_area = $id_area;
                        $poe->id_ubicacion = $id_ubicacion;
                        $poe->observaciones = $observaciones;
                        $poe->create_date = $create_date;
                        $poe->create_user = $create_user;
                        $poe->update_date = $update_date;
                        $poe->update_user = $update_user;
                        $poe->status = $status;
                        $poe->cerrar_entrega = $cerrar_entrega;
                        $poe->origen_extraccion = $origen_extraccion;
                        $poe->id_origen = $id_origen;
                        $poe->save();

                        if($poe){
                            array_push($nuevos_poes, $nombre.' '.$apellidos.' | '.$id_origen);
                        } else{
                            array_push($errores_poes, $nombre.' '.$apellidos);
                            if($poe->errors){
                                dd($poe);
                            }
                        }  

                        
                    } else {
                        $array_estudios = explode('|', $bd_poes['estudios']);
                        $array_categorias = explode(',', $bd_poes['categoria_estudios']);
                        $array_condicion = explode(',', $bd_poes['condicion_estudios']);
                        //dd($array_estudios);
                        
                       
                        foreach($array_categorias as $key=>$categoria){
                            $paramscat = [':id' => $categoria];
                            $cat = Yii::$app->db_ohc->createCommand('SELECT id,nombre,apellidos,empresa,nombre_categoria FROM categorias_poes WHERE id=:id')
                            ->bindValues($paramscat)
                            ->queryOne();

                            if($cat){
                                
                                $cate = TipoServicios::find()->where(['like','nombre',$cat['nombre_categoria']])->one();
                                if(!$cate){
                                    $cate = new TipoServicios();
                                    $cate->nombre = $cat['nombre_categoria'];
                                    $cate->status = 2;
                                    $cate->save();   
                                }
                            
                                if($cate){
                                    if(!in_array($cate->nombre, $nuevas_categorias)){
                                        array_push($nuevas_categorias, $cate->nombre);
                                    }
                                }


                                $estudioscategoria = $array_estudios[$key];
                                
                                $array_catestudios = explode(',', $estudioscategoria);
                                foreach($array_catestudios as $key2=>$estudio){
                                    $paramsest = [':id' => $estudio];
                                    $est = Yii::$app->db_ohc->createCommand('SELECT * FROM expediente_estudios_poes WHERE id=:id')
                                    ->bindValues($paramsest)
                                    ->queryOne();

                                    if($est){
                                        $estud = Servicios::find()->where(['like','nombre',$est['nombre_estudio']])->one();
                                        if(!$estud){
                                            $estud = new Servicios();
                                            $estud->nombre = $est['nombre_estudio'];
                                            $estud->status = 2;
                                            $estud->save();   
                                        }
                                    
                                        if($estud){
                                            if(!in_array($estud->nombre, $nuevos_estudios)){
                                                array_push($nuevos_estudios, $estud->nombre);
                                            }


                                            //HAY ESTUDIO Y CATEGORIA, ENTONCES CREAR DETALLEPOE
                                            if($cate && $estud){
                                                //dd($est);
                                                //Condiciones , '1' => 'BIEN', '2' => 'REGULAR', '3' => 'MAL', '4' => 'F INTERPRETACI�0�7N'
                                                $condicion_estudio = 0;
                                                if($est['condicion_estudio']== 1){
                                                    $condicion_estudio = 1;
                                                } else if($est['condicion_estudio']== 2){
                                                    $condicion_estudio = 2;
                                                } else if($est['condicion_estudio']== 3){
                                                    $condicion_estudio = 3;
                                                } else if($est['condicion_estudio']== 4){
                                                    $condicion_estudio = 100;
                                                }

                                                $detallepoe = Poeestudio::find()->where(['id_poe'=>$poe->id])->andWhere(['id_tipo'=>$cate->id])->andWhere(['id_estudio'=>$estud->id])->one();
                                                if(!$detallepoe){
                                                    $detallepoe = new Poeestudio();
                                                }
                                                $detallepoe->id_poe = $poe->id;
                                                $detallepoe->id_tipo = $cate->id;
                                                $detallepoe->id_estudio = $estud->id;
                                                $detallepoe->fecha = $est['fecha_subida'];
                                                $detallepoe->condicion = $condicion_estudio;
                                                $detallepoe->comentario = $est['comentario'];
                                                $detallepoe->evolucion = 4;
                                                $detallepoe->status = 0;
                                                $detallepoe->save();   

                                                if(!$detallepoe || $detallepoe->errors){
                                                    dd($detallepoe);
                                                }
                                                //dd('Categoria: '.$cate->nombre.' | Estudio: '.$estud->nombre);
                                            }
                                            
                                        }
                                    }

                                }

                            }

                            

                            //dd($cat);
                            //dd($categoria);
                        }
                       //dd($poe);
                    }

                } else {
                    array_push($errores_poes, $nombre.' '.$apellidos);
                    //dd('No existe: '.$nombre.' '.$apellidos);
                }
                
            }

        }

        dd($errores_poes);
        dd($nuevos_poes);

    }

    /**
     * Creates a new ExtraccionBd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionTrabajadores()
    {
        $model = new ExtraccionBd();

        //$params = [':empresa' => $_GET['id'], ':status' => 1];
        /* $empresa_get = 'NSK WARNER';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT * FROM listado_trabajadores WHERE empresa=:empresa ORDER BY id ASC')
        ->bindValues($params)
        ->queryAll(); */

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT * FROM listado_trabajadores WHERE empresa=:empresa ORDER BY id ASC')
        ->bindValues($params)
        ->queryAll();

        //dd($rows);

        $nuevos_trabajadores = [];
        $errores_trabajadores = [];
        $nuevos_puestos = [];

        $cantidad_trabajadores = 0;

        //dd($rows);

        foreach($rows as $key=>$bd_trabajador){
            //dd($bd_trabajador);

            $id_origen = $bd_trabajador['id'];
            $nombre = '';
            $apellidos = '';
            $nempleado = '';
            $nomina = '';

            $tipo_registro = null;
            $id_cargamasiva = null;
            $id_empresa = null;
            $tipo_examen = null;
            $foto = null;
            $sexo = null;
            $estado_civil = null;
            $fecha_nacimiento = null;
            $edad = null;
            $turno = null;
            $num_imss = null;
            $curp = null;
            $rfc = null;
            $correo = null;
            $contacto_emergencia = null;
            $direccion = null;
            $colonia = null;
            $pais = null;
            $estado = null;
            $ciudad = null;
            $municipio = null;
            $cp = null;
            $num_trabajador = null;
            $tipo_contratacion = null;
            $fecha_contratacion = null;
            $id_puesto = null;
            $id_area = null;
            $status = null;
            $hidden = null;
            $fecha_baja = date('Y-m-d');
            $motivo_baja = '--';


            $nombre = $bd_trabajador['nombre'];
            $apellidos = $bd_trabajador['apellidos'];
            $nempleado = $bd_trabajador['nempleado'];
            $nomina = $bd_trabajador['nomina'];

            $nombre = trim($nombre);
            $apellidos = trim($apellidos);
            $nempleado = trim($nempleado);
            $nomina = trim($nomina);

            /* $nombre = $this->remove_utf8_bom($nombre);
            $apellidos = $this->remove_utf8_bom($apellidos);
            $nempleado = $this->remove_utf8_bom($nempleado);
            $nomina = $this->remove_utf8_bom($nomina); */


            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){

                //SOLO APLICA ESTO SI ES QUE EN LA BD DE SMO YA HAY REGISTRO DE DICHA EMPRESA (para no repetir los trabajadores), SI NO USAR LA SEGUNDA OPCION
                try {
                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                } catch (\Throwable $th) {
                    $nombre = mb_convert_encoding($nombre, "UTF-8", "LATIN1");
                    $apellidos = mb_convert_encoding($apellidos, "UTF-8", "LATIN1");
                    $nempleado = mb_convert_encoding($nempleado, "UTF-8", "LATIN1");
                    $nomina = mb_convert_encoding($nomina, "UTF-8", "LATIN1");

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                }
               /*  $trabajador = null; */
                

                if($trabajador){//YA HAY TRABAJADOR
                    
                } else {//NO HAY TRABAJADOR
                    
                    $cantidad_trabajadores ++;
                    array_push($nuevos_trabajadores, $nombre.' '.$apellidos);
                

                    $puesto_new = $bd_trabajador['puesto_trabajo'];
                    $puesto_new = trim($puesto_new);

                    $puesto = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$puesto_new])->one();
                    if(!$puesto){
                        if($puesto_new != null && $puesto_new != '' && $puesto_new != ' '){
                            if(!in_array($puesto_new, $nuevos_puestos)){
                                array_push($nuevos_puestos, $puesto_new);
                            }
                            $origen_extraccion = 1;
                            $puesto = new Puestostrabajo();
                            $puesto->id_empresa = $empresa->id;
                            $puesto->nombre = $puesto_new;
                            $puesto->status = 0;
                            $puesto->create_date = date('Y-m-d');
                            $puesto->origen_extraccion = $origen_extraccion;
                            $puesto->save();
                        }
                    }

                    
  
                    $tipo_registro = 2;
                    $id_cargamasiva = null;
                    $id_empresa = $empresa->id;
                    $tipo_examen = null;
                    $foto = null;

                    $retsex = null;
                    if($bd_trabajador['sexo'] == 'MASCULINO'){
                        $retsex = 1;
                    } else if($bd_trabajador['sexo'] == 'MASCULINO'){
                        $retsex = 2;
                    }
                    $sexo = $retsex;

                    $retestadocivil = null;
                    if($bd_trabajador['estado_civil'] == 'SOLTERO(A)'){
                        $retestadocivil = 1;
                    } else if($bd_trabajador['estado_civil'] == 'CASADO(A)'){
                        $retestadocivil = 2;
                    } else if($bd_trabajador['estado_civil'] == 'VIUDO'){
                        $retestadocivil = 3;
                    }
                    $estado_civil = $retestadocivil;

                    $fecha_nacimiento = $bd_trabajador['fecha_nacimiento'];
                    $edad = $bd_trabajador['edad'];
                    $turno = null;
                    $num_imss = $bd_trabajador['imss'];
                    $curp = $bd_trabajador['curp'];
                    $rfc = $bd_trabajador['rfc'];
                    $correo = $bd_trabajador['correo'];
                    $contacto_emergencia = $bd_trabajador['contacto_emergencia'];
                    $direccion = $bd_trabajador['direccion'];
                    $colonia = $bd_trabajador['colonia'];
                    $pais = null;
                    $estado = $bd_trabajador['estado'];
                    $ciudad = $bd_trabajador['ciudad'];
                    $municipio = $bd_trabajador['municipio'];
                    $cp = $bd_trabajador['codigo_postal'];
                    $num_trabajador = $bd_trabajador['nempleado'];

                    $rettipocontrato = null;
                    if($bd_trabajador['tiempo_contrato'] == 'Indefinido'){
                        $rettipocontrato = 0;
                    } else if($bd_trabajador['tiempo_contrato'] == '1 Mes'){
                        $rettipocontrato = 2;
                    } else if($bd_trabajador['tiempo_contrato'] == '2 Meses'){
                        $rettipocontrato = 3;
                    } else if($bd_trabajador['tiempo_contrato'] == '3 Meses'){
                        $rettipocontrato = 4;
                    } else if($bd_trabajador['tiempo_contrato'] == '4 Meses'){
                        $rettipocontrato = 5;
                    } else if($bd_trabajador['tiempo_contrato'] == '5 Meses'){
                        $rettipocontrato = 6;
                    } else if($bd_trabajador['tiempo_contrato'] == '6 Meses'){
                        $rettipocontrato = 7;
                    }
                    $tipo_contratacion = $rettipocontrato;

                    $fecha_contratacion = $bd_trabajador['fecha_inicio'];

                    if($puesto){
                        $id_puesto = $puesto->id;
                    }
                    
                    $id_area = null;
                    $status = 2;
                    $hidden = 1;
                    $origen_extraccion = 1;


                    $trabajador = new Trabajadores();
                    $trabajador->nombre = $nombre;
                    $trabajador->apellidos = $apellidos;
                    $trabajador->tipo_registro = $tipo_registro;
                    $trabajador->id_cargamasiva = $id_cargamasiva;
                    $trabajador->id_empresa = $id_empresa;
                    $trabajador->tipo_examen = $tipo_examen;
                    $trabajador->foto = $foto;
                    $trabajador->sexo = $sexo;
                    $trabajador->estado_civil = $estado_civil;

                    $trabajador->fecha_nacimiento = $fecha_nacimiento;
                    if($bd_trabajador['fecha_nacimiento'] == '0000-00-00'){
                        $trabajador->fecha_nacimiento = '1900-01-01';
                    }

                    $trabajador->edad = $edad;
                    $trabajador->turno = $turno;
                    $trabajador->num_imss = $num_imss;
                    $trabajador->curp = $curp;
                    $trabajador->rfc = $rfc;
                    $trabajador->correo = $correo;
                    $trabajador->contacto_emergencia = $contacto_emergencia;
                    $trabajador->direccion = $direccion;
                    $trabajador->colonia = $colonia;
                    $trabajador->pais = $pais;
                    $trabajador->estado = $estado;
                    $trabajador->ciudad = $ciudad;
                    $trabajador->municipio = $municipio;
                    $trabajador->cp = $cp;
                    $trabajador->num_trabajador = $num_trabajador;
                    $trabajador->tipo_contratacion = $tipo_contratacion;
                    $trabajador->fecha_contratacion = $fecha_contratacion;
                    $trabajador->id_puesto = $id_puesto;
                    $trabajador->id_area = $id_area;
                    $trabajador->status = $status;
                    $trabajador->hidden = $hidden;
                    $trabajador->origen_extraccion = $origen_extraccion;
                    $trabajador->id_origen = $id_origen;

                    $trabajador->fecha_baja = $fecha_baja;
                    if($bd_trabajador['fecha_fin'] != '0000-00-00' && $bd_trabajador['fecha_fin'] != null && $bd_trabajador['fecha_fin'] != '' && $bd_trabajador['fecha_fin'] != ' '){
                        $trabajador->fecha_baja = $bd_trabajador['fecha_fin'];
                    } else {
                        $trabajador->fecha_baja = date('Y-m-d');
                    }

                    $trabajador->motivo_baja = $motivo_baja;
                    try {
                        $trabajador->save();
                    } catch (\Throwable $th) {
                      
                       $trabajador->tipo_registro = mb_convert_encoding($tipo_registro, "UTF-8", "LATIN1");
                       $trabajador->id_cargamasiva = mb_convert_encoding($id_cargamasiva, "UTF-8", "LATIN1");
                       $trabajador->id_empresa = mb_convert_encoding($id_empresa, "UTF-8", "LATIN1");
                       $trabajador->tipo_examen = mb_convert_encoding($tipo_examen, "UTF-8", "LATIN1");
                       $trabajador->foto = null;
                       $trabajador->sexo = mb_convert_encoding($sexo, "UTF-8", "LATIN1");
                       $trabajador->estado_civil =  null;
   
                       $trabajador->fecha_nacimiento = mb_convert_encoding($fecha_nacimiento, "UTF-8", "LATIN1");
                       if($bd_trabajador['fecha_nacimiento'] == '0000-00-00'){
                           $trabajador->fecha_nacimiento = '1900-01-01';
                       }
   
                       $trabajador->edad = mb_convert_encoding($edad, "UTF-8", "LATIN1");
                       $trabajador->turno = null;
                       $trabajador->num_imss = null;
                       $trabajador->curp = null;
                       $trabajador->rfc = null;
                       $trabajador->correo = null;
                       $trabajador->contacto_emergencia = null;
                       $trabajador->direccion = null;
                       $trabajador->colonia = null;
                       $trabajador->pais = null;
                       $trabajador->estado = null;
                       $trabajador->ciudad = null;
                       $trabajador->municipio = null;
                       $trabajador->cp = null;
                       $trabajador->num_trabajador = null;
                       $trabajador->tipo_contratacion = null;
                       $trabajador->fecha_contratacion = null;
                       $trabajador->id_puesto = null;
                       $trabajador->id_area = null;
                       $trabajador->status = mb_convert_encoding($status, "UTF-8", "LATIN1");
                       $trabajador->hidden = mb_convert_encoding($hidden, "UTF-8", "LATIN1");
   
                       $trabajador->fecha_baja = mb_convert_encoding($fecha_baja, "UTF-8", "LATIN1");
                       if($bd_trabajador['fecha_fin'] != '0000-00-00' && $bd_trabajador['fecha_fin'] != null && $bd_trabajador['fecha_fin'] != '' && $bd_trabajador['fecha_fin'] != ' '){
                           $trabajador->fecha_baja = $bd_trabajador['fecha_fin'];
                       } else {
                           $trabajador->fecha_baja = date('Y-m-d');
                       }
   
                       $trabajador->motivo_baja = '--';

                       try {
                           $trabajador->save();
                        } catch (\Throwable $th2) {
                            dd($th2);
                        }
                    }
                    

            
                    if(!$trabajador){
                        array_push($errores_trabajadores, $nombre.' '.$apellidos);
                    } if($trabajador->errors){
                        dd($trabajador);
                        array_push($errores_trabajadores, $nombre.' '.$apellidos);
                    }
                    //dd('CREAR TRABAJADOR | nombre: '.$nombre.' | apellidos: '.$apellidos.' | nempleado: '.$nempleado.' | nomina: '.$nomina);
                }
            }
            
        }

        dd($nuevos_trabajadores);
        
        //dd($errores_trabajadores);
        //dd($nuevos_puestos);
        //dd($nuevos_trabajadores);

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

    public function actionHistorias(){
        $model = new ExtraccionBd();

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT * FROM hcc_ohc WHERE empresa=:empresa')
        ->bindValues($params)
        ->queryAll();
        //dd($rows);

        $nuevos_hccohc = [];
        $errores_hccohc = [];
        $nuevos_puestos = [];

        foreach($rows as $key=>$bd_hccohc){
            //dd($bd_hccohc);

            $id_trabajador = $bd_hccohc['id_trabajador'];
            $id_origen = $bd_hccohc['id'];

            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){
                $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_trabajador])->one();
                
                if(!$trabajador){
                    $nombre = $bd_hccohc['nombre'];
                    $apellidos = $bd_hccohc['apellidos'];
        
                    $nombre = trim($nombre);
                    $apellidos = trim($apellidos);

                    $nombre = str_replace("  "," ",$nombre);
                    $apellidos = str_replace("  "," ",$apellidos);

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();  
                }
                if($trabajador){
                    $origen = null;
                    $id_empresa = null;

                    $hc = Hccohc::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_origen])->one();

                    if(!$hc){
                        $id_trabajador = null;
                        $id_empresa = null;
                        $fecha = null;
                        $examen = null;
                        $nombre = null;
                        $apellidos = null;
                        $sexo = null;
                        $fecha_nacimiento = null;
                        $edad = null;
                        $nivel_lectura = null;
                        $nivel_escritura = null;
                        $retestadocivil = null;
                        $estado_civil = null;
                        $area = null;
                        $puesto = null;
                        $status = null;
                        $examen = null;
                        $origen_extraccion = null;
        

                        /* foreach($atributos as $k=>$at){
                            $retatributo = $this->atributos($k,$at, $bd_hccohc,$trabajador,$empresa->id);
                        } */

                        $puesto_new = $bd_hccohc['puesto'];
                        $puesto_new = trim($puesto_new);

                        try {
                            $puesto = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$puesto_new])->one();
                        } catch (\Throwable $th3) {
                            $puesto_new = mb_convert_encoding($puesto_new, "UTF-8", "LATIN1");
                            $puesto = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$puesto_new])->one();
                        }
                        
                        if(!$puesto){
                            if($puesto_new != null && $puesto_new != '' && $puesto_new != ' '){
                                if(!in_array($puesto_new, $nuevos_puestos)){
                                    array_push($nuevos_puestos, $puesto_new);
                                }
                                $puesto = new Puestostrabajo();
                                $puesto->id_empresa = $empresa->id;
                                $puesto->nombre = $puesto_new;
                                $puesto->status = 0;
                                $puesto->create_date = date('Y-m-d');
                                $puesto->save();
                            }
                        }
                    
                        if($puesto){
                            $id_puesto = $puesto->id;
                        }

                        $retestadocivil = null;
                        if($bd_hccohc['civil'] == 'SOLTERO(A)'){
                            $retestadocivil = 1;
                        } else if($bd_hccohc['civil'] == 'CASADO(A)'){
                            $retestadocivil = 2;
                        } else if($bd_hccohc['civil'] == 'VIUDO'){
                            $retestadocivil = 3;
                        }

                        $id_trabajador = $trabajador->id;
                        $id_empresa = $empresa->id;
                        $fecha = $bd_hccohc['fecha'];
                        $nombre = $trabajador->nombre;
                        $apellidos = $trabajador->apellidos;
                        $sexo = $trabajador->sexo;
                        $fecha_nacimiento = $trabajador->fecha_nacimiento;
                        $edad = $bd_hccohc['edad'];
                        $nivel_lectura = null;
                        $nivel_escritura = null;
                        $estado_civil = $retestadocivil;
                        $area = null;
                        $puesto = $id_puesto;
                        $status = 1;
                        $examen = 4;
                        $origen_extraccion = 1;
                        $id_origen = $id_origen;


                        $hc = new Hccohc();
                        $hc->id_trabajador = $id_trabajador;
                        $hc->id_empresa = $id_empresa;
                        $hc->fecha = $fecha;
                        $hc->examen = $examen;
                        $hc->nombre = $nombre;
                        $hc->apellidos = $apellidos;
                        $hc->sexo = $sexo;
                        $hc->fecha_nacimiento = $fecha_nacimiento;
                        $hc->edad = $edad;
                        $hc->nivel_lectura = $nivel_lectura;
                        $hc->nivel_escritura = $nivel_escritura;
                        $hc->estado_civil = $estado_civil;
                        $hc->area = $area;
                        $hc->puesto = $puesto;
                        $hc->status = $status;
                        $hc->origen_extraccion = $origen_extraccion;
                        $hc->id_origen = $id_origen;
                        $hc->save();
                       

                        if($hc){
                            array_push($nuevos_hccohc, $nombre.' '.$apellidos.' | '.$id_origen);
                        } else{
                            array_push($errores_hccohc, $nombre.' '.$apellidos);
                            if($hc->errors){
                                dd($hc);
                            }
                        } 

                    } else {

                        //dd($hc);
                        //dd('Si hay hc');
                    }

                } else{
                    dd('No existe trabajador: '.$bd_hccohc['nombre_completo'].' | id: '.$id_trabajador);
                }
            }
            
        }

        dd($nuevos_hccohc);
        dd($errores_hccohc);
    }

    public function actionCuestionarios(){
        $model = new ExtraccionBd();
        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_cuestionario->createCommand('SELECT * FROM cuestionario WHERE nombre_empresa=:empresa')
        ->bindValues($params)
        ->queryAll();

        $nuevos_cuestionarios = [];
        $errores_cuestionarios = [];

        //dd($rows);

        foreach($rows as $key=>$bd_cuestionario){
            //dd($bd_cuestionario);

            $id_trabajador = $bd_cuestionario['id_paciente'];
            $id_origen = $bd_cuestionario['id'];

            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){
                $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_trabajador])->one();
                //dd($trabajador);
                if($trabajador){
                    $sw = null;
                    $id_bitacora = null;
                    $id_tipo_cuestionario  = null;
                    $id_empresa = null;
                    $id_paciente = null;
                    $id_medico = null;
                    $nombre_empresa = null;
                    $id_area = null;
                    $id_puesto = null;
                    $num_trabajador = null;
                    $sexo = null;
                    $fecha_nacimiento = null;
                    $edad = null;
                    $fecha_cuestionario = null;
                    $firma_paciente = null;
                    $es_local = null;
                    $status = null;
                    $soft_delete = null;
                    $origen_extraccion = null;

                    $cuestionario = Cuestionario::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_origen])->one();

                    if(!$cuestionario){
                        //dd('No hay cuestionario: ');
                        $sw = $bd_cuestionario['sw'];
                        $id_bitacora = $bd_cuestionario['id_bitacora'];
                        $id_tipo_cuestionario = $bd_cuestionario['id_tipo_cuestionario'];
                        $id_empresa = $empresa->id;
                        $id_paciente = $trabajador->id;
                        $id_medico = $bd_cuestionario['id_medico'];
                        $nombre_empresa = $empresa->comercial;
                        $id_area = $trabajador->id_area;
                        $id_puesto = $trabajador->id_puesto;
                        $num_trabajador = $trabajador->num_trabajador;
                        $sexo = $trabajador->sexo;
                        $fecha_nacimiento = $trabajador->fecha_nacimiento;
                        $edad = $trabajador->edad;
                        $fecha_cuestionario = $bd_cuestionario['fecha_cuestionario'];
                        $firma_paciente = $bd_cuestionario['firma_paciente'];
                        $es_local = 2;
                        $status = $bd_cuestionario['status'];
                        $soft_delete = null;
                        $origen_extraccion = 1;
                        $id_origen = $id_origen;
                       
                    
                        $cuestionario = new Cuestionario();
                        $cuestionario->sw = $sw;
                        $cuestionario->id_bitacora = $id_bitacora;
                        $cuestionario->id_tipo_cuestionario = $id_tipo_cuestionario;
                        $cuestionario->id_empresa = $id_empresa;
                        $cuestionario->id_paciente = $id_paciente;
                        $cuestionario->id_medico = $id_medico;
                        $cuestionario->nombre_empresa = $nombre_empresa;
                        $cuestionario->id_area = $id_area;
                        $cuestionario->id_puesto = $id_puesto;
                        $cuestionario->num_trabajador = $num_trabajador;
                        $cuestionario->sexo = $sexo;
                        $cuestionario->fecha_nacimiento = $fecha_nacimiento;
                        $cuestionario->edad = $edad;
                        $cuestionario->fecha_cuestionario = $fecha_cuestionario;
                        $cuestionario->firma_paciente = $firma_paciente;
                        $cuestionario->es_local = $es_local;
                        $cuestionario->status = $status;
                        $cuestionario->soft_delete = $soft_delete;
                        $cuestionario->origen_extraccion = $origen_extraccion;
                        $cuestionario->id_origen = $id_origen;

                        $cuestionario->save();

                        if($cuestionario){
                            array_push($nuevos_cuestionarios, $id_origen);
                        } else{
                            array_push($errores_cuestionarios, $id_origen);
                            if($cuestionario->errors){
                                dd($cuestionario);
                            }
                        }
                    } else{
                        //dd($cuestionario);
                        $paramsdet = [':id_cuestionario' => $cuestionario->id_origen];
                        $det = Yii::$app->db_cuestionario->createCommand('SELECT * FROM detalle_cuestionario WHERE id_cuestionario=:id_cuestionario')
                        ->bindValues($paramsdet)
                        ->queryAll();

                        foreach($det as $key2=>$det_c){
                            $id_detallecuestionario = $det_c['id'];
                            $detallecuestionario = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_origen'=>$id_detallecuestionario])->one();
                            if(!$detallecuestionario){
                                $detallecuestionario = new DetalleCuestionario();
                            }

                            $id_cuestionario = null;
                            $id_tipo_cuestionario  = null;
                            $id_pregunta  = null;
                            $id_area  = null;
                            $respuesta_1 = null;
                            $respuesta_2 = null;
                            $status = null;
                            $origen_extraccion = null;
                            $id_origen = null;
                            $soft_delete = null;

                            $id_cuestionario = $cuestionario->id;
                            $id_tipo_cuestionario = $det_c['id_tipo_cuestionario'];
                            $id_pregunta = $det_c['id_pregunta'];
                            $id_area = $det_c['id_area'];
                            $respuesta_1 = $det_c['respuesta_1'];
                            $respuesta_2 = $det_c['respuesta_2'];
                            $status = $det_c['status'];
                            $origen_extraccion = 1;
                            $id_origen = $det_c['id'];
                            $soft_delete = null;

                            $detallecuestionario->id_cuestionario = $id_cuestionario;
                            $detallecuestionario->id_tipo_cuestionario = $id_tipo_cuestionario;
                            $detallecuestionario->id_pregunta = $id_pregunta;
                            $detallecuestionario->id_area = $id_area;
                            $detallecuestionario->respuesta_1 = $respuesta_1;
                            $detallecuestionario->respuesta_2 = $respuesta_2;
                            $detallecuestionario->status = $status;
                            $detallecuestionario->origen_extraccion = $origen_extraccion;
                            $detallecuestionario->id_origen = $id_origen;
                            $detallecuestionario->soft_delete = $soft_delete;
                            $detallecuestionario->save();

                            if(!$detallecuestionario || $detallecuestionario->errors){
                                dd($detallecuestionario);
                            }
                        }

                    }
                } else{
                    dd('No existe trabajador: '.$bd_cuestionario['id_paciente']);
                }
            }
            
        }

        dd($nuevos_cuestionarios);
        dd($errores_cuestionarios);
    }

    public function actionEvolucionpoes()
    {
        $model = new ExtraccionBd();

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT DISTINCT id_trab FROM poes WHERE empresa=:empresa')
        ->bindValues($params)
        ->queryAll();

        $nuevos_evolucion = [];
        $errores_evolucion = [];

        foreach($rows as $key=>$bd_poes){
            
            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){

                $id_trabajador = $bd_poes['id_trab'];
               /*  $nombre = $bd_poes['nombre'];
                $apellidos = $bd_poes['apellidos'];
                $nombre = trim($nombre);
                $apellidos = trim($apellidos); */

                try {
                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_trabajador])->one();
                } catch (\Throwable $th) {
                    /* $nombre = mb_convert_encoding($nombre, "UTF-8", "LATIN1");
                    $apellidos = mb_convert_encoding($apellidos, "UTF-8", "LATIN1"); */

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_trabajador])->one();
                }

                if(!$trabajador){
                    $params = [':empresa' => $empresa_get,':id' => $id_trabajador];
                    $bd_trab = Yii::$app->db_ohc->createCommand('SELECT * FROM listado_trabajadores WHERE empresa=:empresa && id=:id ORDER BY id ASC')
                    ->bindValues($params)
                    ->queryOne();

                    if($bd_trab){
                        $nombre = $bd_trab['nombre'];
                        $apellidos = $bd_trab['apellidos'];
            
                        $nombre = trim($nombre);
                        $apellidos = trim($apellidos);
    
                        $nombre = str_replace("  "," ",$nombre);
                        $apellidos = str_replace("  "," ",$apellidos);
    
                        $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();  
                    }
                    
                }

                if($trabajador){
                    $poes = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_trabajador'=>$trabajador->id])->orderBy(['id'=>SORT_DESC])->all();
                    $id_poes = [];

                    if(count($poes)>0){

                        if(count($poes) == 1){//SI SOLO HAY UN POE LO CONTAMOS COMO INICIAL
                            
                            foreach($poes as $key=>$poe){
                                if($poe->estudios){
                                    foreach($poe->estudios as $k=>$estudio){
                                        $estudio->evolucion = 5;
                                        $estudio->save();
                                    }
                                }
                            }

                        } else { // Si hay mas de 1 poe comparamos con anteriores
                            
                            foreach($poes as $key=>$poe){
                                $id_poes[$key] = $poe->id;
                            }
    
                            foreach($poes as $key=>$poe){
                                unset($id_poes[$key]);
    
                                if($poe->estudios){
                                    foreach($poe->estudios as $k=>$estudio){
                                        $estudio_anterior = Poeestudio::find()->where(['in','id_poe',$id_poes])->andWhere(['id_estudio'=>$estudio->id])->orderBy(['id'=>SORT_DESC])->one();
                                        if($estudio_anterior){//Si hay un estudio anterior que comparar
                                            if($estudio_anterior->status == $estudio->status){//Si son iguales los status de ambos estudios
                                                if($estudio_anterior->status == 100){
                                                    //Si el estatus es sin avance colocar evolucion = sin avance
                                                    $estudio->evolucion = 100;
                                                    $estudio->save();
                                                } else if($estudio_anterior->status == 0){
                                                    //Si el estatus es pendiente colocar evolucion = pendiente
                                                    $estudio->evolucion = 0;
                                                    $estudio->save();
                                                } else if($estudio_anterior->status == 1 || $estudio_anterior->status == 2 || $estudio_anterior->status == 3){
                                                    //Si es bien, regular, mal, al tener ambos el mismo status colocamos evolucion = igual
                                                    $estudio->evolucion = 1;
                                                    $estudio->save();
                                                }
                                            } else {
                                                if($estudio->status == 100  && $estudio->status == 0){
                                                    //Si el estudio actual esta pendiente o sin avance, ponerlo pendiente
                                                    $estudio->evolucion = 0;
                                                    $estudio->save();
                                                } else if($estudio->status == 1){
                                                    //Si el estudio actual es bien, (sabemos que el otro no es bien porque no entro en el == status) lo ponemos como mejora
                                                    $estudio->evolucion = 2;
                                                    $estudio->save();
                                                } else if($estudio->status == 2){
                                                    //Si el estudio actual es regular
                                                    if($estudio_anterior->status == 1){//El anterior era bien, empeoro
                                                        $estudio->evolucion = 3;
                                                        $estudio->save();
                                                    } else if($estudio_anterior->status == 3){//El anterior era mal, mejoro
                                                        $estudio->evolucion = 2;
                                                        $estudio->save();
                                                    } else{
                                                        //Es pendiente o sin avance, lo ponemos pendiente
                                                        $estudio->evolucion = 0;
                                                        $estudio->save();
                                                    }
                                                } else if($estudio->status == 3){
                                                    //Si el estatus es mal
                                                    if($estudio_anterior->status == 100 || $estudio_anterior->status == 0 ){//El anterior era pendiente o sin avance, ponerlo pendiente
                                                        $estudio->evolucion = 0;
                                                        $estudio->save();
                                                    } else{
                                                        //Si es otro ponerlo como empeoro, ya que de bien a regular es empeorar, y no aplica el ==
                                                        $estudio->evolucion = 3;
                                                        $estudio->save();
                                                    }

                                                }
                                            }
    
                                        } else {
                                            //Si no hay estudio anterior lo mandamos como inicial
                                            $estudio->evolucion = 5;
                                            $estudio->save();
                                        }
                                    }
                                }
                            }
    

                        }
                        
                    } else {
                        dd('no hay poe: ');
                    }

                } else {
                    //dd('No existe trabajador: ');
                }
                
            }

        }
        dd('Terminó revisión');

    }

    public function actionPoesdocumentos()
    {
        $model = new ExtraccionBd();

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT * FROM poes WHERE empresa=:empresa')
        ->bindValues($params)
        ->queryAll();

        $nuevos_archivos = [];
        $errores_archivos = [];
        $array_estudios = [];
        $nuevos_puestos = [];

        $nuevas_categorias = [];
        $nuevos_estudios = [];


        foreach($rows as $key=>$bd_poes){
            //dd($bd_poes);
            $id_origen = $bd_poes['id'];

            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){

                $nombre = $bd_poes['nombre'];
                $apellidos = $bd_poes['apellidos'];
                $nombre = trim($nombre);
                $apellidos = trim($apellidos);

                try {
                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                } catch (\Throwable $th) {
                    $nombre = mb_convert_encoding($nombre, "UTF-8", "LATIN1");
                    $apellidos = mb_convert_encoding($apellidos, "UTF-8", "LATIN1");

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();
                }

                $poe = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_origen])->one();

                if($trabajador){
                    if($poe) {
                        $k = $poe->id.'|'.$trabajador->nombre.'_'.$trabajador->apellidos;
                        $nuevos_archivos[$k] = '';
                        $errores_archivos[$k] = '';
                        //dd($poe);

                        //CREAR LA CARPETA DEL POE, SI ES QUE NO EXISTE AUN
                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$poe->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$poe->id_empresa.'/Trabajadores/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/';
                        $dir4 = __DIR__ . '/../web/resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                        $this->actionCarpetas( $directorios);

                        $dirdestino = 'C:/laragon/www/nuevoohc/web/resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/';

                        $array_estudios = explode('|', $bd_poes['estudios']);
                        $array_categorias = explode(',', $bd_poes['categoria_estudios']);
                        $array_condicion = explode(',', $bd_poes['condicion_estudios']);
                        //dd($array_estudios);
                        
                       
                        foreach($array_categorias as $key=>$categoria){
                            $paramscat = [':id' => $categoria];
                            $cat = Yii::$app->db_ohc->createCommand('SELECT id,nombre,apellidos,empresa,nombre_categoria FROM categorias_poes WHERE id=:id')
                            ->bindValues($paramscat)
                            ->queryOne();

                            if($cat){
                                
                                $cate = TipoServicios::find()->where(['like','nombre',$cat['nombre_categoria']])->one();
                                if(!$cate){
                                    $cate = new TipoServicios();
                                    $cate->nombre = $cat['nombre_categoria'];
                                    $cate->status = 2;
                                    $cate->save();   
                                }
                            
                                if($cate){
                                    if(!in_array($cate->nombre, $nuevas_categorias)){
                                        array_push($nuevas_categorias, $cate->nombre);
                                    }
                                }


                                $estudioscategoria = $array_estudios[$key];
                                
                                $array_catestudios = explode(',', $estudioscategoria);
                                foreach($array_catestudios as $key2=>$estudio){
                                    $paramsest = [':id' => $estudio];
                                    $est = Yii::$app->db_ohc->createCommand('SELECT * FROM expediente_estudios_poes WHERE id=:id')
                                    ->bindValues($paramsest)
                                    ->queryOne();

                                    //dd($est);

                                    if($est){
                                        $estud = Servicios::find()->where(['like','nombre',$est['nombre_estudio']])->one();
                                        if(!$estud){
                                            $estud = new Servicios();
                                            $estud->nombre = $est['nombre_estudio'];
                                            $estud->status = 2;
                                            $estud->save();   
                                        }
                                    
                                        if($estud){
                                            if(!in_array($estud->nombre, $nuevos_estudios)){
                                                array_push($nuevos_estudios, $estud->nombre);
                                            }


                                            //HAY ESTUDIO Y CATEGORIA, ENTONCES CREAR DETALLEPOE
                                            if($cate && $estud){

                                                $detallepoe = Poeestudio::find()->where(['id_poe'=>$poe->id])->andWhere(['id_tipo'=>$cate->id])->andWhere(['id_estudio'=>$estud->id])->one();
                                                if($detallepoe){
                                                    if($est['ruta_estudio'] != null && $est['ruta_estudio'] != '' && $est['ruta_estudio'] != ' '){
                                                        $url_archivo = $est['ruta_estudio'];
                                                        $base_archivo = 'C:/laragon/www/OHC/web'.'/'.$url_archivo;
                                                        
                                                        $url  = $base_archivo;//URL del archivo

                                                        $extension = 'pdf';

                                                        if(str_contains($url_archivo, '.pdf')){
                                                            $extension = 'pdf';
                                                        } else if(str_contains($url_archivo, '.jpg')){
                                                            $extension = 'jpg';
                                                        } else if(str_contains($url_archivo, '.jpeg')){
                                                            $extension = 'jpeg';
                                                        } else if(str_contains($url_archivo, '.png')){
                                                            $extension = 'png';
                                                        } else if(str_contains($url_archivo, '.docx')){
                                                            $extension = 'docx';
                                                        }

                                                        $destino = $dirdestino;//Path del destino, falta el nombre del archivo
                                                        $nombre_nuevo = $detallepoe->id_estudio .'_EVIDENCIA'.'_'.date('YmdHis'). '.' . $extension;

                                                        $path = $destino.$nombre_nuevo;

                                                        try {
                                                            if($detallepoe->evidencia != null && $detallepoe->evidencia != '' && $detallepoe->evidencia != ' '){
                                                                $existente = $destino.$detallepoe->evidencia;
                                                                if(file_exists($existente)){
                                                                    unlink($existente);
                                                                }
                                                            }
                                                            
                                                            copy($url, $path);//COPIAR EL ORIGINAL EN EL SMO
                                                            $detallepoe->evidencia = $nombre_nuevo;
                                                            $detallepoe->save();

                                                            $nuevos_archivos[$k] = $nuevos_archivos[$k] . ','.$detallepoe->id;
                                                        } catch (\Throwable $th) {
                                                            $errores_archivos[$k] = $errores_archivos[$k] . ','.$detallepoe->id;
                                                        }
                                                        
                                                    }
                                                }
                                                
                                            }
                                            
                                        }
                                    }

                                }

                            }
                        }
                    }

                } else {
                    dd('No existe: '.$nombre.' '.$apellidos);
                }
                
            }

        }

        dd($errores_archivos);
        dd($nuevos_archivos);

    }

    public function actionHistoriasdocumentos(){
        $model = new ExtraccionBd();

        $empresa_get = 'NSK BEARINGS';
        $params = [':empresa' => $empresa_get];
        $rows = Yii::$app->db_ohc->createCommand('SELECT id,id_trabajador,empresa,nombre,apellidos,nombre_completo FROM hcc_ohc WHERE empresa=:empresa')
        ->bindValues($params)
        ->queryAll();
        //dd($rows);

        $nuevos_archivos = [];
        $errores_archivos = [];
       
        foreach($rows as $key=>$bd_hccohc){
            //dd($bd_hccohc);

            $id_trabajador = $bd_hccohc['id_trabajador'];
            $id_origen = $bd_hccohc['id'];

            $empresa = Empresas::find()->where(['or',['comercial'=>$empresa_get],['razon'=>$empresa_get]])->one();
            if($empresa){
                $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_trabajador])->one();
                
                if(!$trabajador){
                    $nombre = $bd_hccohc['nombre'];
                    $apellidos = $bd_hccohc['apellidos'];
        
                    $nombre = trim($nombre);
                    $apellidos = trim($apellidos);

                    $nombre = str_replace("  "," ",$nombre);
                    $apellidos = str_replace("  "," ",$apellidos);

                    $trabajador = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['like','nombre',$nombre])->andWhere(['like','apellidos',$apellidos])->one();  
                }

                if($trabajador){
                    $origen = null;
                    $id_empresa = null;

                    $hc = Hccohc::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_origen'=>$id_origen])->one();

                    if($hc) {
                        $nuevos_archivos[$hc->id] = '';
                        $errores_archivos[$hc->id] = '';


                        $dir0 = __DIR__ . '/../web/resources/Empresas/';
                        $dir1 = __DIR__ . '/../web/resources/Empresas/'.$hc->id_empresa.'/';
                        $dir2 = __DIR__ . '/../web/resources/Empresas/'.$hc->id_empresa.'/Trabajadores/';
                        $dir3 = __DIR__ . '/../web/resources/Empresas/'.$hc->id_empresa.'/Trabajadores/'.$hc->id_trabajador.'/';
                        $dir4 = __DIR__ . '/../web/resources/Empresas/'.$hc->id_empresa.'/Trabajadores/'.$hc->id_trabajador.'/Hccal/';
                        $directorios = ['0'=>$dir0,'1'=>$dir1,'2'=>$dir2,'3'=>$dir3,'4'=>$dir4];
                        $this->actionCarpetas( $directorios);

                        $dirdestino = 'C:/laragon/www/nuevoohc/web/resources/Empresas/'.$hc->id_empresa.'/Trabajadores/'.$hc->id_trabajador.'/Hccal/';

                        //HC CON FIRMA-------------------------------------------------------------------INICIO
                        //Archivo ORIGINAL
                        $nombre_archivo = 'hc_1_'.$id_origen.'.pdf';
                        $url_archivo = $empresa->comercial.'/HC_CAL/'.$nombre_archivo;
                        $base_archivo = 'C:/laragon/www/OHC/web'.'/'.$url_archivo;
                                                      
                        $url  = $base_archivo;//URL del archivo
                        
                        $extension = 'pdf';

                        $destino = $dirdestino;//Path del destino, falta el nombre del archivo
                        $nombre_nuevo = 'hc_1_'.$hc->id.'_EVIDENCIA'.'_'.date('YmdHis'). '.' . $extension;

                        $path = $destino.$nombre_nuevo;


                        try {
                            if($hc->hc_confirma != null && $hc->hc_confirma != '' && $hc->hc_confirma != ' '){
                                $existente = $destino.$hc->hc_confirma;
                                if(file_exists($existente)){
                                    unlink($existente);
                                }
                            }
                            
                            copy($url, $path);//COPIAR EL ORIGINAL EN EL SMO
                            $hc->hc_confirma = $nombre_nuevo;
                            $hc->save(false);

                            $nuevos_archivos[$hc->id] = $nuevos_archivos[$hc->id] . ',hc firmado';
                        } catch (\Throwable $th) {
                            $errores_archivos[$hc->id] = $errores_archivos[$hc->id] . ',hc firmado';
                        }
                        //HC CON FIRMA-------------------------------------------------------------------FIN


                        //HC SIN FIRMA-------------------------------------------------------------------INICIO
                        //Archivo ORIGINAL
                        $nombre_archivo = 'hc_0_'.$id_origen.'.pdf';
                        $url_archivo = $empresa->comercial.'/HC_CAL/'.$nombre_archivo;
                        $base_archivo = 'C:/laragon/www/OHC/web'.'/'.$url_archivo;
                                                      
                        $url  = $base_archivo;//URL del archivo
                        
                        $extension = 'pdf';

                        $destino = $dirdestino;//Path del destino, falta el nombre del archivo
                        $nombre_nuevo = 'hc_0_'.$hc->id.'_EVIDENCIA'.'_'.date('YmdHis'). '.' . $extension;

                        $path = $destino.$nombre_nuevo;


                        try {
                            if($hc->hc_sinfirma != null && $hc->hc_sinfirma != '' && $hc->hc_sinfirma != ' '){
                                $existente = $destino.$hc->hc_sinfirma;
                                if(file_exists($existente)){
                                    unlink($existente);
                                }
                            }
                            
                            copy($url, $path);//COPIAR EL ORIGINAL EN EL SMO
                            $hc->hc_sinfirma = $nombre_nuevo;
                            $hc->save(false);

                            $nuevos_archivos[$hc->id] = $nuevos_archivos[$hc->id] . ',hc sin firma';
                        } catch (\Throwable $th) {
                            $errores_archivos[$hc->id] = $errores_archivos[$hc->id] . ',hc sin firma';
                        }
                        //HC SIN FIRMA-------------------------------------------------------------------FIN
                                                        
                        //CAL CON FIRMA-------------------------------------------------------------------INICIO
                        //Archivo ORIGINAL
                        $nombre_archivo = 'cal_1_'.$id_origen.'.pdf';
                        $url_archivo = $empresa->comercial.'/HC_CAL/'.$nombre_archivo;
                        $base_archivo = 'C:/laragon/www/OHC/web'.'/'.$url_archivo;
                                                      
                        $url  = $base_archivo;//URL del archivo
                        
                        $extension = 'pdf';

                        $destino = $dirdestino;//Path del destino, falta el nombre del archivo
                        $nombre_nuevo = 'cal_1_'.$hc->id.'_EVIDENCIA'.'_'.date('YmdHis'). '.' . $extension;

                        $path = $destino.$nombre_nuevo;


                        try {
                            if($hc->cal_confirma != null && $hc->cal_confirma != '' && $hc->cal_confirma != ' '){
                                $existente = $destino.$hc->cal_confirma;
                                if(file_exists($existente)){
                                    unlink($existente);
                                }
                            }
                            
                            copy($url, $path);//COPIAR EL ORIGINAL EN EL SMO
                            $hc->cal_confirma = $nombre_nuevo;
                            $hc->save(false);

                            $nuevos_archivos[$hc->id] = $nuevos_archivos[$hc->id] . ',cal firmado';
                        } catch (\Throwable $th) {
                            $errores_archivos[$hc->id] = $errores_archivos[$hc->id] . ',cal firmado';
                        }
                        //CAL CON FIRMA-------------------------------------------------------------------FIN                              


                        //CAL SIN FIRMA-------------------------------------------------------------------INICIO
                        //Archivo ORIGINAL
                        $nombre_archivo = 'cal_0_'.$id_origen.'.pdf';
                        $url_archivo = $empresa->comercial.'/HC_CAL/'.$nombre_archivo;
                        $base_archivo = 'C:/laragon/www/OHC/web'.'/'.$url_archivo;
                                                      
                        $url  = $base_archivo;//URL del archivo
                        
                        $extension = 'pdf';

                        $destino = $dirdestino;//Path del destino, falta el nombre del archivo
                        $nombre_nuevo = 'cal_0_'.$hc->id.'_EVIDENCIA'.'_'.date('YmdHis'). '.' . $extension;

                        $path = $destino.$nombre_nuevo;


                        try {
                            if($hc->cal_sinfirma != null && $hc->cal_sinfirma != '' && $hc->cal_sinfirma != ' '){
                                $existente = $destino.$hc->cal_sinfirma;
                                if(file_exists($existente)){
                                    unlink($existente);
                                }
                            }
                            
                            copy($url, $path);//COPIAR EL ORIGINAL EN EL SMO
                            $hc->cal_sinfirma = $nombre_nuevo;
                            $hc->save(false);

                            $nuevos_archivos[$hc->id] = $nuevos_archivos[$hc->id] . ',cal sin firma';
                        } catch (\Throwable $th) {
                            $errores_archivos[$hc->id] = $errores_archivos[$hc->id] . ',cal sin firma';
                        }
                        //CAL SIN FIRMA-------------------------------------------------------------------FIN                              

                        //dd($hc);
                        //dd('Si hay hc');
                    }

                } else{
                    dd('No existe trabajador: '.$bd_hccohc['nombre_completo']);
                }
            }
            
        }

        dd($nuevos_archivos);
        dd($errores_archivos);
    }

    /**
     * Updates an existing ExtraccionBd model.
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
     * Deletes an existing ExtraccionBd model.
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
     * Finds the ExtraccionBd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ExtraccionBd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExtraccionBd::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    function remove_utf8_bom($text){
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
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

    protected function atributos($key,$atributo,$bd_hccohc,$trabajador,$id_empresa){
        $retatributo = $atributo;
        //dd('Key: '.$key.' | Atributo: '.$atributo.' | Retatributo: '.$retatributo);
        dd($bd_hccohc);
        if($key == 'id_trabajador'){
            $retatributo = $trabajador->id;
        } else if($key == 'id_empresa'){
            $retatributo = $id_empresa;
        } else if($key == 'folio'){
            $retatributo = $id_empresa;
        }

        return $retatributo;
    }
}