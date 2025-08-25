<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consultas".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_empresa
 * @property int|null $id_consultorio
 * @property int|null $tipo
 * @property string|null $folio
 * @property string|null $fecha
 * @property int|null $visita
 * @property int|null $solicitante
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property int|null $sexo
 * @property string|null $num_imss
 * @property int|null $area
 * @property int|null $puesto
 * @property string|null $evidencia
 * @property string|null $fc
 * @property string|null $fr
 * @property string|null $temp
 * @property string|null $ta
 * @property string|null $ta_diastolica
 * @property string|null $pulso
 * @property string|null $oxigeno
 * @property float|null $peso
 * @property float|null $talla
 * @property string|null $imc
 * @property string|null $categoria_imc
 * @property string|null $sintomatologia
 * @property string|null $aparatos
 * @property string|null $alergias
 * @property string|null $embarazo
 * @property string|null $diagnosticocie
 * @property string|null $diagnostico
 * @property string|null $estudios
 * @property string|null $manejo
 * @property string|null $seguimiento
 * @property int|null $resultado
 * @property int|null $tipo_padecimiento
 */
class Consultas extends \yii\db\ActiveRecord
{
    public $file_evidencia;
    public $aux_estudios = [];
    public $aux_medicamentos = [];
    public $envia_empresa;
    public $envia_consultorio;
    public $envia_form;

    public $firmatxt;
    public $firmado;
    public $testfirma;


    public $consentimiento;
    public $nombre_empresa;
    public $model_;


    public $file_fotoweb;
    public $txt_base64_foto;
    public $txt_base64_ine;
    public $txt_base64_inereverso;

    public $file_evidencia_consentimiento;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consultas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_evidencia_consentimiento'], 'file','extensions' => 'png, jpg, jpeg, pdf', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['file_evidencia'], 'file','extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['id_trabajador', 'id_empresa','id_pais','id_linea','id_ubicacion', 'id_consultorio', 'tipo', 'visita', 'solicitante', 'sexo', 'area', 'puesto', 'resultado', 'tipo_padecimiento','incapacidad_tipo','incapacidad_dias','incapacidad_ramo','triesgo_tipo','envia_empresa','envia_consultorio','envia_form',
              'ps_diabetes1','ps_diabetes2','ps_diabetes5','ps_diabetes6','ps_diabetes7','ps_diabetes8','ps_diabetes9',
              'ps_hipertension1','ps_hipertension2','ps_hipertension3','ps_hipertension4','ps_hipertension5','ps_hipertension7','ps_hipertension8','ps_hipertension9','ps_hipertension10',
              'ps_maternidad1', 'ps_maternidad2', 'ps_maternidad3', 'ps_maternidad4', 'ps_maternidad5', 'ps_maternidad6', 'ps_maternidad7', 'ps_maternidad8', 'ps_maternidad9', 'ps_maternidad10', 'ps_maternidad11',
              'ps_lactancia2','ps_lactancia5','ps_lactancia6',
              'ps_hiperdiabe1','ps_hiperdiabe2','ps_hiperdiabe4','ps_hiperdiabe5','ps_hiperdiabe6','ps_hiperdiabe7','ps_hiperdiabe8','ps_hiperdiabe9','tasis_diagnostico','tadis_diagnostico','ta_diagnostico','oximetria_diagnostico','fr_diagnostico','edad','id_hccohc','firmado','status','calificacion_osha','id_nivel1','id_nivel2','id_nivel3','id_nivel4','status_baja','tipo_consentimiento','updated'], 'integer'],
            [['fecha', 'hora_inicio', 'hora_fin', 'diagnosticocie','aparatos','accidente_hora','accidente_horareporte','incapacidad_fechainicio','incapacidad_fechafin','aux_estudios','aux_medicamentos','ps_diabetes3','ps_hipertension6','ps_lactancia1','ps_lactancia7','ps_hiperdiabe3','id_programa','firmatxt', 'firma','testfirma','fecha_nacimiento'], 'safe'],
            [['peso', 'talla'], 'number'],
            [['num_trabajador'], 'string', 'max' => 10],
            [['folio', 'categoria_imc'], 'string', 'max' => 50],
            [['num_imss', 'imc', 'incapacidad_folio'], 'string', 'max' => 20],
            [['accidente_zona','empresa','ps_lactancia3','ps_lactancia4','nombre','apellidos'], 'string', 'max' => 100],
            [['accidente_causa', 'firma_ruta'], 'string', 'max' => 250],
            [['accidente_descripcion','evidencia_consentimiento'], 'string', 'max' => 300],
            [['evidencia', 'embarazo'], 'string', 'max' => 500],
            [['fc', 'fr', 'temp', 'ta', 'ta_diastolica', 'pulso', 'oxigeno','ps_diabetes4'], 'string', 'max' => 10],
            [['sintomatologia', 'alergias', 'diagnostico', 'estudios', 'manejo', 'seguimiento'], 'string', 'max' => 1000],
            [['id_empresa'], 'required','on' =>['create','update']],
            [['id_empresa'], 'required','on' =>['incapacidad']],
            [['peso'], 'number', 'min' => 10,'max' => 200],
            [['talla'], 'number', 'min' => 0,'max' => 2],
            [['fc'], 'integer', 'min' => 60,'max' => 100],
            [['fr'], 'integer', 'min' => 15,'max' => 40],
            [['temp'], 'number', 'min' => 30,'max' => 45],
            [['ta'], 'integer', 'min' => 50,'max' => 180],
            [['ta_diastolica'], 'integer', 'min' => 50,'max' => 110],
            [['pulso'], 'integer', 'min' => 60,'max' => 190],
            [['oxigeno'], 'integer', 'min' => 70,'max' => 110],

            [['uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','tipo_identificacion','numero_identificacion','foto_web','base64_foto','fecha','hora','txt_base64_foto','txt_base64_ine','txt_base64_inereverso','nombre_empresa'], 'safe'],

            [['ps_diabetes1','ps_diabetes2','ps_diabetes3','ps_diabetes4','ps_diabetes5','ps_diabetes6','ps_diabetes7','ps_diabetes8','ps_diabetes9'], 'required', 'when' => function($model) {
                return $model->id_programa == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-id_programa').val() == '1';
            }"],
            [['ps_hipertension1','ps_hipertension2','ps_hipertension3','ps_hipertension4','ps_hipertension5','ps_hipertension6','ps_hipertension7','ps_hipertension8','ps_hipertension9','ps_hipertension10'], 'required', 'when' => function($model) {
                return $model->id_programa == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-id_programa').val() == '2';
            }"],
            [['ps_maternidad1','ps_maternidad2','ps_maternidad3','ps_maternidad4','ps_maternidad5','ps_maternidad6','ps_maternidad7','ps_maternidad8','ps_maternidad9','ps_maternidad10','ps_maternidad11'], 'required', 'when' => function($model) {
                return $model->id_programa == '3';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-id_programa').val() == '3';
            }"],
            [['ps_lactancia1','ps_lactancia2','ps_lactancia3','ps_lactancia4','ps_lactancia5','ps_lactancia6','ps_lactancia7'], 'required', 'when' => function($model) {
                return $model->id_programa == '4';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-id_programa').val() == '4';
            }"],
            [['ps_hiperdiabe1','ps_hiperdiabe2','ps_hiperdiabe3','ps_hiperdiabe4','ps_hiperdiabe5','ps_hiperdiabe6','ps_hiperdiabe7','ps_hiperdiabe8','ps_hiperdiabe9'], 'required', 'when' => function($model) {
                return $model->id_programa == '4';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-id_programa').val() == '4';
            }"],
            [['id_empresa','id_consultorio','solicitante','tipo','visita','fecha','hora_inicio','resultado',
              'tipo_padecimiento','status','calificacion_osha'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-envia_form').val() == '1';
            }"],
           /*  [['id_trabajador'], 'required', 'when' => function($model) {
                return $model->solicitante == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-solicitante').val() == '1';
            }"], */
            /* [['nombre','apellidos'], 'required', 'when' => function($model) {
                return $model->solicitante == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-solicitante').val() == '2';
            }"],
            
            [['nombre','apellidos'], 'required', 'when' => function($model) {
                return $model->solicitante == '3';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-solicitante').val() == '3';
            }"], */
            [['accidente_hora','accidente_horareporte','accidente_zona','accidente_causa','accidente_descripcion'], 'required', 'when' => function($model) {
                return $model->tipo == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-tipo').val() == '1';
            }"],
           /*  [['incapacidad_folio','incapacidad_tipo','incapacidad_ramo','incapacidad_fechainicio','incapacidad_dias','incapacidad_fechafin'], 'required', 'when' => function($model) {
                return $model->tipo == '4';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-tipo').val() == '4';
            }"], */
            [['triesgo_tipo'], 'required', 'when' => function($model) {
                return $model->tipo == '6';
            }, 'whenClient' => "function (attribute, value) {
                return $('#consultas-tipo').val() == '6';
            }"],

          /*   [['id_consultorio','solicitante','tipo','visita','fecha','hora_inicio','resultado',
            'tipo_padecimiento'], 'required', 'when' => function($model) {
              return $model->tipo == '4';
          }, 'whenClient' => "function (attribute, value) {
              return $('#consultas-tipo').val() == '4';
          }"], */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_trabajador' => Yii::t('app', 'Trabajador'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'empresa' => Yii::t('app', 'Nombre Empresa'),
            'id_consultorio' => Yii::t('app', 'Consultorio'),
            'tipo' => Yii::t('app', 'Tipo de Consulta'),
            'id_programa' => Yii::t('app', 'Programa de Salud'),
            'folio' => Yii::t('app', 'Folio'),
            'fecha' => Yii::t('app', 'Fecha'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'visita' => Yii::t('app', 'Motivo de Consulta'),
            'solicitante' => Yii::t('app', 'Solicitante'),
            'hora_inicio' => Yii::t('app', 'H. Inicio'),
            'hora_fin' => Yii::t('app', 'H. Fin'),
            'sexo' => Yii::t('app', 'Sexo'),
            'num_imss' => Yii::t('app', 'N° IMSS'),
            'area' => Yii::t('app', 'Área'),
            'puesto' => Yii::t('app', 'Puesto'),
            'file_evidencia' => Yii::t('app', 'Evidencia'),
            'evidencia' => Yii::t('app', 'Evidencia'),
            'fc' => Yii::t('app', 'F.C.'),
            'fr' => Yii::t('app', 'F.R.'),
            'fr_diagnostico' => Yii::t('app', 'F.R. Diagnóstico'),
            'temp' => Yii::t('app', 'Temperatura'),
            'ta' => Yii::t('app', 'T/A'),
            'tasis_diagnostico' => Yii::t('app', 'Sistólica Diagnóstico'),
            'ta_diastolica' => Yii::t('app', 'T/A Diastólica'),
            'tadis_diagnostico' => Yii::t('app', 'Diastólica Diagnóstico'),
            'ta_diagnostico' => Yii::t('app', 'TA Diagnóstico'),
            'pulso' => Yii::t('app', 'Pulso'),
            'oxigeno' => Yii::t('app', 'Oximetría'),
            'oximetria_diagnostico' => Yii::t('app', 'Oximetría Diagnóstico'),
            'peso' => Yii::t('app', 'Peso(Kg)'),
            'talla' => Yii::t('app', 'Talla(Mts)'),
            'imc' => Yii::t('app', 'IMC'),
            'categoria_imc' => Yii::t('app', 'Estado Nutricional'),
            'sintomatologia' => Yii::t('app', 'Sintomatología'),
            'aparatos' => Yii::t('app', 'Aparatos y Sistemas'),
            'alergias' => Yii::t('app', 'Alergias'),
            'embarazo' => Yii::t('app', 'Embarazo'),
            'diagnosticocie' => Yii::t('app', 'Diagnóstico CIE'),
            'diagnostico' => Yii::t('app', 'Diagnóstico'),
            'estudios' => Yii::t('app', 'Estudios Solicitados'),
            'manejo' => Yii::t('app', 'Manejo'),
            'seguimiento' => Yii::t('app', 'Seguimiento'),
            'resultado' => Yii::t('app', 'Resultado'),
            'tipo_padecimiento' => Yii::t('app', 'Tipo de Padecimiento'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Médico'),
            'update_date' => Yii::t('app', 'Fecha Actualiza'),
            'update_user' => Yii::t('app', 'Usuario Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Elimina'),
            'delete_user' => Yii::t('app', 'Fecha Eliminó'),
            'accidente_hora'=>Yii::t('app', 'Hora de Accidente'),
            'accidente_horareporte'=>Yii::t('app', 'Hora de Reporte'),
            'accidente_zona'=>Yii::t('app', 'Zona'),
            'accidente_causa'=>Yii::t('app', 'Causa'),
            'accidente_descripcion'=>Yii::t('app', 'Descripción'),
            'incapacidad_folio'=>Yii::t('app', 'Folio Incapacidad'),
            'incapacidad_tipo'=>Yii::t('app', 'Tipo'),
            'incapacidad_fechainicio'=>Yii::t('app', 'Fecha de inicio'),
            'incapacidad_dias'=>Yii::t('app', 'Días'),
            'incapacidad_fechafin'=>Yii::t('app', 'Fecha Fin'),
            'incapacidad_ramo'=>Yii::t('app', 'Ramo de Seguro'),
            'triesgo_tipo'=>Yii::t('app', 'Tipo Riesgo'),
            'ps_diabetes1'=>Yii::t('app', '¿Ha presentado incremento en la frecuencia de orinar? (Poliuria)'),
            'ps_diabetes2'=>Yii::t('app', '¿Se despierta por las noches para ir a orinar?'),
            'ps_diabetes3'=>Yii::t('app', '¿Presenta  molestias urinarias?'),
            'ps_diabetes4'=>Yii::t('app', '¿Su orina presenta alteraciones? ¿Cuáles?'),
            'ps_diabetes5'=>Yii::t('app', '¿Presenta sed en exceso? (Polidipsia)'),
            'ps_diabetes6'=>Yii::t('app', '¿Presenta incremento en el apetito? (Polifagia)'),
            'ps_diabetes7'=>Yii::t('app', '¿Ha presentado un incremento de peso mayor a 3 kg en el último mes?'),
            'ps_diabetes8'=>Yii::t('app', '¿Lleva su tratamiento (farmacologico / no farmacologico) de manera regular y como está indicado por su médico?'),
            'ps_diabetes9'=>Yii::t('app', '¿Sus cifras de glucosa se han mantenido en parametros normales?'),
            'ps_hipertension1'=>Yii::t('app', '¿Sus cifras de tensión arterial se han mantenido dentro de parámetros normales?'),
            'ps_hipertension2'=>Yii::t('app', '¿Ha presentado dolor de cabeza de manera recurrente?'),
            'ps_hipertension3'=>Yii::t('app', '¿Ha presentado mareo?'),
            'ps_hipertension4'=>Yii::t('app', '¿Presenta alteraciones visuales (ver lucecitas o puntilleos)?'),
            'ps_hipertension5'=>Yii::t('app', '¿Ha presentado dolor torácico?'),
            'ps_hipertension6'=>Yii::t('app', 'Ha presentado cambios en la frecuencia, volumen o aspecto de la orina. ¿Cuales?'),
            'ps_hipertension7'=>Yii::t('app', '¿Ha presentado alteraciones auditivas?'),
            'ps_hipertension8'=>Yii::t('app', '¿Ha presentado alteraciones auditivas?'),
            'ps_hipertension9'=>Yii::t('app', '¿Ha presentado hinchazón en sus manos, pies o cara?'),
            'ps_hipertension10'=>Yii::t('app', '¿En base al interrogatorio y exploración física, existe sospecha de daño a órgano blanco?'),
            'ps_maternidad1'=>Yii::t('app', '¿Presenta dolor de cabeza?'),
            'ps_maternidad2'=>Yii::t('app', '¿Percibe zumbido o pitido de oídos?'),
            'ps_maternidad3'=>Yii::t('app', '¿Presenta alteraciones visuales (ver lucecitas o puntilleos)?'),
            'ps_maternidad4'=>Yii::t('app', '¿Presenta náusea o vómitos recurrentes?'),
            'ps_maternidad5'=>Yii::t('app', '¿Percibe los movimientos de su bebé?'),
            'ps_maternidad6'=>Yii::t('app', '¿Ha notado hinchazón en sus pies, manos o cara?'),
            'ps_maternidad7'=>Yii::t('app', '¿Ha presentado pérdida de liquido o sangre en las últimas 48 hrs?'),
            'ps_maternidad8'=>Yii::t('app', '¿Presenta flujo transvaginal?'),
            'ps_maternidad9'=>Yii::t('app', '¿Presenta dolor abdominal?'),
            'ps_maternidad10'=>Yii::t('app', '¿Ha presentado molestias al orinar?'),
            'ps_maternidad11'=>Yii::t('app', '¿Ha presentado convulsiones o desmayos?'),
            'ps_lactancia1'=>Yii::t('app', 'Fecha de resolución de evento obstetrico'),
            'ps_lactancia2'=>Yii::t('app', 'Tipo de resolución'),
            'ps_lactancia3'=>Yii::t('app', '¿Tuvo complicaciones durante el evento obstétrico?'),
            'ps_lactancia4'=>Yii::t('app', '¿Ha presentado complicaciones durante el puerperio como fiebre, infecciones, etc?'),
            'ps_lactancia5'=>Yii::t('app', '¿Cuenta con método de planificación familiar?'),
            'ps_lactancia6'=>Yii::t('app', '¿Que tipo de lactancia materna esta brindando?'),
            'ps_lactancia7'=>Yii::t('app', 'Respecto a los datos clínicos de mastitis, seleccione los que se encuentren presentes'),
            'ps_hiperdiabe1'=>Yii::t('app', '¿Sus cifras de glucosa y tensión arterial se han mantenido dentro de parámetros normales?'),
            'ps_hiperdiabe2'=>Yii::t('app', '¿Considera que su adherencia al tratamiento para diabetes mellitus e hipertensión es el adecuado?'),
            'ps_hiperdiabe3'=>Yii::t('app', '¿Ha presentado alteraciones urinarias? ¿Cuales?'),
            'ps_hiperdiabe4'=>Yii::t('app', '¿Ha presentado dolor torácico?'),
            'ps_hiperdiabe5'=>Yii::t('app', '¿Ha presentado dolor de cabeza recurrente?'),
            'ps_hiperdiabe6'=>Yii::t('app', '¿Ha presentado alteraciones auditivas? (Zumbido o pitido de oidos)'),
            'ps_hiperdiabe7'=>Yii::t('app', '¿Ha presentado alteraciones visuales? (Ver lucecitas o puntilleo)'),
            'ps_hiperdiabe8'=>Yii::t('app', '¿Ha presentado hinchazón en sus manos, pies o cara?'),
            'ps_hiperdiabe9'=>Yii::t('app', '¿En base al interrogatorio y exploración física, existe sospecha de daño a órgano blanco?'),
            'id_hccohc' => Yii::t('app', 'Historia Clínica'),
            'calificacion_osha' => Yii::t('app', 'Clasificación OSHA'),
            'status' => Yii::t('app', 'Status Consulta'),


            'evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
            'file_evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDempresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

    /**
     * Gets query for [[Consultorio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorio()
    {
        return $this->hasOne(Consultorios::class, ['id' => 'id_consultorio']);
    }


    /**
     * Gets query for [[Puestostrabajo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuestodata()
    {
        return $this->hasOne(Puestostrabajo::class, ['id' => 'puesto']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreadata()
    {
        return $this->hasOne(Areas::class, ['id' => 'area']);
    }


    public function getUCaptura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'create_user']);
    }

    public function getUActualiza()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'update_user']);
    }

    public function getUElimina()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'delete_user']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }

     public function getNivel1()
    {
        return $this->hasOne(NivelOrganizacional1::class, ['id' => 'id_nivel1']);
    }
    public function getNivel2()
    {
        return $this->hasOne(NivelOrganizacional2::class, ['id' => 'id_nivel2']);
    }
    public function getNivel3()
    {
        return $this->hasOne(NivelOrganizacional3::class, ['id' => 'id_nivel3']);
    }
    public function getNivel4()
    {
        return $this->hasOne(NivelOrganizacional4::class, ['id' => 'id_nivel4']);
    }


}