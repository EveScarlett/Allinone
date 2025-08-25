<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hcc_ohc".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_empresa
 * @property string|null $folio
 * @property string|null $fecha
 * @property string|null $hora
 * @property string|null $examen
 * @property string|null $empresa
 * @property string|null $area
 * @property string|null $puesto
 * @property string|null $nombre
 * @property string|null $apellidos
 * @property int|null $sexo
 * @property string|null $fecha_nacimiento
 * @property int|null $edad
 * @property int|null $estado_civil
 * @property int|null $nivel_lectura
 * @property int|null $nivel_escritura
 * @property string|null $grupo
 * @property string|null $rh
 * @property string|null $diabetess
 * @property string|null $diabetesstxt
 * @property string|null $hipertension
 * @property string|null $hipertensiontxt
 * @property string|null $cancer
 * @property string|null $cancertxt
 * @property string|null $nefropatias
 * @property string|null $nefropatiastxt
 * @property string|null $cardiopatias
 * @property string|null $cardiopatiastxt
 * @property string|null $reuma
 * @property string|null $reumatxt
 * @property string|null $hepa
 * @property string|null $hepatxt
 * @property string|null $tuber
 * @property string|null $tubertxt
 * @property string|null $psi
 * @property string|null $psitxt
 * @property string|null $tabaquismo
 * @property string|null $tabdesde
 * @property string|null $tabfrec
 * @property string|null $tabcantidad
 * @property string|null $alcoholismo
 * @property string|null $alcodesde
 * @property string|null $alcofrec
 * @property string|null $alcocantidad
 * @property string|null $cocina
 * @property string|null $cocinadesde
 * @property string|null $audifonos
 * @property string|null $audiodesde
 * @property string|null $audiocuando
 * @property string|null $droga
 * @property string|null $drogatxt
 * @property string|null $duracion_droga
 * @property string|null $fecha_droga
 * @property string|null $vacunacion_cov
 * @property string|null $nombre_vacunacion
 * @property int|null $dosis_vacunacion
 * @property string|null $fecha_vacunacion
 * @property string|null $mano
 * @property string|null $alergias
 * @property string|null $alergiastxt
 * @property string|null $asma
 * @property string|null $asmatxt
 * @property string|null $asma_anio
 * @property string|null $cardio
 * @property string|null $cardiotxt
 * @property string|null $cirugias
 * @property string|null $cirugiastxt
 * @property string|null $convulsiones
 * @property string|null $convulsionestxt
 * @property string|null $diabetes
 * @property string|null $diabetestxt
 * @property string|null $hiper
 * @property string|null $hipertxt
 * @property string|null $lumbalgias
 * @property string|null $lumbalgiastxt
 * @property string|null $nefro
 * @property string|null $nefrotxt
 * @property string|null $polio
 * @property string|null $politxt
 * @property string|null $poliomelitis_anio
 * @property string|null $saram
 * @property string|null $saram_anio
 * @property string|null $pulmo
 * @property string|null $pulmotxt
 * @property string|null $hematicos
 * @property string|null $hematicostxt
 * @property string|null $trauma
 * @property string|null $traumatxt
 * @property string|null $medicamentos
 * @property string|null $medicamentostxt
 * @property string|null $protesis
 * @property string|null $protesistxt
 * @property string|null $trans
 * @property string|null $transtxt
 * @property string|null $enf_ocular
 * @property string|null $enf_ocular_txt
 * @property string|null $enf_auditiva
 * @property string|null $enf_auditiva_txt
 * @property string|null $fractura
 * @property string|null $fractura_txt
 * @property string|null $amputacion
 * @property string|null $amputacion_txt
 * @property string|null $hernias
 * @property string|null $hernias_txt
 * @property string|null $enfsanguinea
 * @property string|null $enfsanguinea_txt
 * @property string|null $tumorescancer
 * @property string|null $tumorescancer_txt
 * @property string|null $enfpsico
 * @property string|null $enfpsico_txt
 * @property string|null $gestas
 * @property string|null $partos
 * @property string|null $abortos
 * @property string|null $cesareas
 * @property string|null $menarca
 * @property string|null $ivsa
 * @property string|null $fum
 * @property string|null $mpf
 * @property string|null $doc
 * @property string|null $docma
 * @property int|null $peso
 * @property float|null $talla
 * @property string|null $imc
 * @property string|null $categoria_imc
 * @property string|null $fc
 * @property string|null $fr
 * @property string|null $temp
 * @property string|null $ta
 * @property string|null $ta_diastolica
 * @property string|null $caries_rd
 * @property string|null $inspeccion
 * @property int|null $inspeccion_otros
 * @property string|null $txt_inspeccion_otros
 * @property string|null $cabeza
 * @property int|null $cabeza_otros
 * @property string|null $txt_cabeza_otros
 * @property string|null $oidos
 * @property int|null $oidos_otros
 * @property string|null $txt_oidos_otros
 * @property string|null $ojos
 * @property int|null $ojos_otros
 * @property string|null $txt_ojos_otros
 * @property string|null $sLentes
 * @property string|null $sLentesD
 * @property string|null $cLentes
 * @property string|null $cLentesD
 * @property string|null $Rlentes
 * @property string|null $Ulentes
 * @property string|null $boca
 * @property int|null $boca_otros
 * @property string|null $txt_boca_otros
 * @property string|null $cuello
 * @property int|null $cuello_otros
 * @property string|null $txt_cuello_otros
 * @property string|null $torax
 * @property int|null $torax_otros
 * @property string|null $txt_torax_otros
 * @property string|null $abdomen
 * @property int|null $abdomen_otros
 * @property string|null $txt_abdomen_otros
 * @property string|null $superior
 * @property int|null $miembrossup_otros
 * @property string|null $txt_miembrossup_otros
 * @property string|null $inferior
 * @property int|null $miembrosinf_otros
 * @property string|null $txt_miembrosinf_otros
 * @property string|null $columna
 * @property int|null $columna_otros
 * @property string|null $txt_columna_otros
 * @property string|null $txtneurologicos
 * @property int|null $neurologicos_otros
 * @property string|null $txt_neurologicos_otros
 * @property string|null $diagnostico
 * @property string|null $comentarios
 * @property string|null $conclusion
 * @property string|null $medico
 * @property int|null $status
 * @property int|null $firma_medicolaboral
 */
class Hccohc extends \yii\db\ActiveRecord
{
    public $aux_alergiastxt = [];
    public $aux_cardiotxt;
    public $aux_cirugiastxt;
    public $aux_convulsionestxt;
    public $aux_diabetestxt;
    public $aux_hipertxt;
    public $aux_lumbalgiastxt;
    public $aux_nefrotxt;
    public $aux_pulmotxt;
    public $aux_hematicostxt;
    public $aux_traumatxt;
    public $aux_medicamentostxt;
    public $aux_protesistxt;
    public $aux_transtxt;
    public $aux_enf_ocular_txt;
    public $aux_enf_auditiva_txt;
    public $aux_fractura_txt;
    public $aux_amputacion_txt;
    public $aux_hernias_txt;
    public $aux_enfsanguinea_txt;
    public $aux_tumorescancer_txt;
    public $aux_enfpsico_txt;
    public $aux_vacunacion_txt;


    public $firmatxt;

    public $aux_programas = [];
    public $aux_estudios = [];


    public $guardar_firma;
    

    public $analisis = [];

    public $aux_nuevopuesto;

    public $firmado;

    public $envia_form;

    

    public $show_inspeccion;
    public $show_cabeza;
    public $show_oidos;
    public $show_ojos;
    public $show_boca;
    public $show_cuello;
    public $show_torax;
    public $show_abdomen;
    public $show_superior;
    public $show_inferior;
    public $show_columna;
    public $show_txtneurologicos;


    public $consentimiento;
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
        return 'hcc_ohc';
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
            [['id_trabajador', 'id_empresa','id_pais','id_linea','id_ubicacion', 'sexo', 'edad', 'estado_civil', 'nivel_lectura', 'nivel_escritura', 'dosis_vacunacion', 'inspeccion_otros', 'cabeza_otros', 'oidos_otros', 'ojos_otros', 'boca_otros', 'cuello_otros', 'torax_otros', 'abdomen_otros', 'miembrossup_otros', 'miembrosinf_otros', 'columna_otros', 'neurologicos_otros', 'status', 'firma_medicolaboral', 'examen','pso2','conclusion_cal',
              'inicio_laboral','antlaboral_antiguedad','laboral0_tiempoexposicion','laboral1_tiempoexposicion','laboral2_tiempoexposicion','laboral3_tiempoexposicion','antecedentes_sino','guardar_firma', 'vigencia','envia_form','corrective_user','id_nivel1','id_nivel2','id_nivel3','id_nivel4','tipo_consentimiento','id_poe','id_estudio_poe','tipo_hc_poe','status_backup'], 'integer'],
            [['fecha', 'hora', 'fecha_nacimiento', 'fecha_vacunacion',
              'diabetesstxt', 'hipertensiontxt', 'cancertxt', 'nefropatiastxt', 'cardiopatiastxt', 'reumatxt', 'alergiastxt',
              'asmatxt', 'cardiotxt', 'cirugiastxt', 'convulsionestxt', 'diabetestxt', 'hipertxt',
              'lumbalgiastxt', 'nefrotxt', 'politxt', 'pulmotxt', 'hematicostxt', 'traumatxt', 'medicamentostxt',
              'protesistxt', 'inspeccion', 'cabeza', 'oidos', 'ojos', 'torax', 'abdomen', 'superior', 'inferior', 'columna',
              'aux_alergiastxt','aux_cardiotxt','aux_cirugiastxt','aux_convulsionestxt','aux_diabetestxt',
              'aux_hipertxt','aux_lumbalgiastxt','aux_nefrotxt','aux_pulmotxt','aux_hematicostxt','aux_traumatxt',
              'aux_medicamentostxt','aux_protesistxt','aux_transtxt','aux_enf_ocular_txt','aux_fractura_txt',
              'aux_amputacion_txt','aux_hernias_txt','aux_enfsanguinea_txt','aux_tumorescancer_txt','aux_enfpsico_txt','aux_vacunacion_txt',
              'aux_programas','aux_estudios','boca', 'cuello','torax','abdomen','superior','inferior','columna','txtneurologicos','firma',
              'covidreciente_fecha','laboral0_desde','laboral1_desde','laboral2_desde','laboral3_desde','laboral0_hasta','laboral1_hasta','laboral2_hasta','laboral3_hasta','laboral0_exposicion','laboral1_exposicion','laboral2_exposicion','laboral3_exposicion',
              'corrective_date','start_date','end_date'
            ], 'safe'],

            [['uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','tipo_identificacion','numero_identificacion','foto_web','base64_foto','fecha','hora','txt_base64_foto','txt_base64_ine','txt_base64_inereverso','nombre_empresa','perimetro_abdominal'], 'safe'],

            [['talla','peso'], 'number'],
            [['num_trabajador','laboral0_ipp','laboral1_ipp','laboral2_ipp','laboral3_ipp'], 'string', 'max' => 10],
            [['folio', 'categoria_imc', 'sLentesD', 'cLentesD', 'Rlentes', 'Ulentes'], 'string', 'max' => 50],
            [['nombre', 'apellidos','covidreciente_vacunacion','antlaboral_area','antlaboral_puesto','laboral0_epp','laboral1_epp','laboral2_epp','laboral3_epp','nombre_empresa'], 'string', 'max' => 100],
            [['covidreciente_secuelas','laboral0_actividad','laboral1_actividad','laboral2_actividad','laboral3_actividad'], 'string', 'max' => 200],
            [['area', 'puesto','id_familiar','id_area','status_baja'], 'integer'],
            [['grupo', 'asma_anio', 'poliomelitis_anio', 'saram_anio', 'ta_diastolica'], 'string', 'max' => 5],
            [['rh', 'hepa', 'tuber', 'psi', 'droga', 'fecha_droga', 'trans', 'gestas', 'partos', 'abortos', 'cesareas', 'menarca', 'ivsa', 'fc', 'fr', 'temp', 'ta'], 'string', 'max' => 10],
            [['diabetess', 'hipertension', 'cancer', 'nefropatias', 'cardiopatias', 'reuma', 'tabaquismo', 'alcoholismo', 'cocina', 'audifonos', 'vacunacion_cov', 'alergias', 'asma', 'cardio', 'cirugias', 'convulsiones', 'diabetes', 'hiper', 'lumbalgias', 'nefro', 'polio', 'saram', 'pulmo', 'hematicos', 'trauma', 'medicamentos', 'protesis', 'enf_ocular', 'enf_auditiva', 'fractura', 'amputacion', 'hernias', 'enfsanguinea', 'tumorescancer', 'enfpsico','familiar_empresa','covidreciente'], 'string', 'max' => 2],
            [['hepatxt', 'tubertxt', 'psitxt', 'drogatxt', 'transtxt', 'conclusion','fecha_vigencia'], 'safe'],
            [['tabdesde', 'tabfrec', 'tabcantidad', 'alcodesde', 'alcofrec', 'alcocantidad', 'cocinadesde', 'audiodesde', 'audiocuando'], 'string', 'max' => 30],
            [['duracion_droga', 'nombre_vacunacion', 'mano', 'fum', 'doc', 'docma', 'imc', 'caries_rd', 'sLentes', 'cLentes','numero_emergencia'], 'string', 'max' => 20],
            [['enf_ocular_txt', 'enf_auditiva_txt', 'fractura_txt', 'amputacion_txt', 'hernias_txt', 'enfsanguinea_txt', 'tumorescancer_txt', 'enfpsico_txt'], 'string', 'max' => 150],
            [['mpf'], 'string', 'max' => 25],
            [['txt_inspeccion_otros', 'txt_cabeza_otros', 'txt_oidos_otros', 'txt_ojos_otros', 'txt_boca_otros', 'txt_cuello_otros', 'txt_torax_otros', 'txt_abdomen_otros', 'txt_miembrossup_otros', 'txt_miembrosinf_otros', 'txt_columna_otros', 'txt_neurologicos_otros', 'medico'], 'string', 'max' => 200],
            [['diagnostico'], 'string', 'max' => 500],
            [['firma_ruta','aux_nuevopuesto', 'evidencia_consentimiento'], 'string', 'max' => 300],
            [['comentarios'], 'string', 'max' => 1100],
            [['recomendaciones'], 'string', 'max' => 1000],

            [['pso2'], 'integer', 'min' => 0,'max' => 100],

            [['poe_doc1','poe_doc2','poe_doc3'], 'string', 'min' => 0,'max' => 70],

            [['conclusion_cal'], 'required','on' =>['close']],

            [['id'], 'required','on' =>['correction']],

            [['id_empresa','fecha'], 'required','on' =>['create','update']],
           

            [['id_empresa','folio','fecha','id_trabajador','examen','nombre','apellidos','sexo','fecha_nacimiento','edad',

            'num_trabajador','puesto','nivel_lectura','nivel_escritura','estado_civil','grupo','rh','numero_emergencia','familiar_empresa',

            'diabetess','hipertension','cancer','nefropatias','cardiopatias','reuma','hepa','tuber','psi',
            'tabaquismo','alcoholismo','cocina','audifonos','droga','vacunacion_cov','mano','covidreciente',
            'alergias','asma','cardio','cirugias','convulsiones','diabetes','hiper','lumbalgias','nefro','polio','saram','pulmo','trauma','medicamentos','protesis','trans','enf_ocular','enf_auditiva','fractura','amputacion','hernias','enfsanguinea','tumorescancer','enfpsico','antecedentes_sino',
            'peso','talla','imc','categoria_imc','perimetro_abdominal','fc','fr','temp','ta','ta_diastolica','caries_rd','pso2',
            'show_inspeccion','show_cabeza','show_oidos','show_ojos','show_boca','show_cuello','show_torax','show_abdomen','show_superior','show_inferior','show_columna','show_txtneurologicos',
            'sLentes','sLentesD','Rlentes','Ulentes',
            'diagnostico','comentarios','conclusion','vigencia','recomendaciones','puesto'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-envia_form').val() == '1';
            }"],

            [['id_familiar','id_area'], 'required', 'when' => function($model) {
                return $model->familiar_empresa == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-familiar_empresa').val() == 'SI';
            }"],
            [['diabetesstxt'], 'required', 'when' => function($model) {
                return $model->diabetess == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-diabetess').val() == 'SI';
            }"],
            [['hipertensiontxt'], 'required', 'when' => function($model) {
                return $model->hipertension == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-hipertension').val() == 'SI';
            }"],
            [['cancertxt'], 'required', 'when' => function($model) {
                return $model->cancer == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-cancer').val() == 'SI';
            }"],
            [['nefropatiastxt'], 'required', 'when' => function($model) {
                return $model->nefropatias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-nefropatias').val() == 'SI';
            }"],
            [['cardiopatiastxt'], 'required', 'when' => function($model) {
                return $model->cardiopatias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-cardiopatias').val() == 'SI';
            }"],
            [['reumatxt'], 'required', 'when' => function($model) {
                return $model->reuma == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-reuma').val() == 'SI';
            }"],
            [['hepatxt'], 'required', 'when' => function($model) {
                return $model->hepa == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-hepa').val() == 'SI';
            }"],
            [['tubertxt'], 'required', 'when' => function($model) {
                return $model->tuber == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-tuber').val() == 'SI';
            }"],
            [['psitxt'], 'required', 'when' => function($model) {
                return $model->psi == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-psi').val() == 'SI';
            }"],


            [['tabdesde','tabfrec','tabcantidad'], 'required', 'when' => function($model) {
                return $model->tabaquismo == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-tabaquismo').val() == 'SI';
            }"],
            [['alcodesde','alcofrec','alcocantidad'], 'required', 'when' => function($model) {
                return $model->alcoholismo == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-alcoholismo').val() == 'SI';
            }"],
            [['audiodesde','audiocuando'], 'required', 'when' => function($model) {
                return $model->audifonos == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-audifonos').val() == 'SI';
            }"],
            [['duracion_droga','fecha_droga'], 'required', 'when' => function($model) {
                return $model->droga == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-droga').val() == 'SI';
            }"],
     /*        [['covidreciente_fecha','covidreciente_secuelas','covidreciente_vacunacion'], 'required', 'when' => function($model) {
                return $model->covidreciente == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-covidreciente').val() == 'SI';
            }"], */


            [['aux_alergiastxt'], 'required', 'when' => function($model) {
                return $model->alergias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-alergias').val() == 'SI';
            }"],
            [['asmatxt','asma_anio'], 'required', 'when' => function($model) {
                return $model->asma == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-asma').val() == 'SI';
            }"],
            [['aux_cardiotxt'], 'required', 'when' => function($model) {
                return $model->cardio == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-cardio').val() == 'SI';
            }"],
            [['aux_cirugiastxt'], 'required', 'when' => function($model) {
                return $model->cirugias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-cirugias').val() == 'SI';
            }"],
            [['aux_convulsionestxt'], 'required', 'when' => function($model) {
                return $model->convulsiones == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-convulsiones').val() == 'SI';
            }"],
            [['aux_diabetestxt'], 'required', 'when' => function($model) {
                return $model->diabetes == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-diabetes').val() == 'SI';
            }"],
            [['aux_hipertxt'], 'required', 'when' => function($model) {
                return $model->hiper == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-hiper').val() == 'SI';
            }"],
            [['aux_lumbalgiastxt'], 'required', 'when' => function($model) {
                return $model->lumbalgias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-lumbalgias').val() == 'SI';
            }"],
            [['aux_nefrotxt'], 'required', 'when' => function($model) {
                return $model->nefro == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-nefro').val() == 'SI';
            }"],
            [['poliomelitis_anio'], 'required', 'when' => function($model) {
                return $model->polio == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-polio').val() == 'SI';
            }"],
            [['saram_anio'], 'required', 'when' => function($model) {
                return $model->saram == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-saram').val() == 'SI';
            }"],
            [['aux_pulmotxt'], 'required', 'when' => function($model) {
                return $model->pulmo == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-pulmo').val() == 'SI';
            }"],
            [['aux_traumatxt'], 'required', 'when' => function($model) {
                return $model->trauma == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-trauma').val() == 'SI';
            }"],
            [['aux_medicamentostxt'], 'required', 'when' => function($model) {
                return $model->medicamentos == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-medicamentos').val() == 'SI';
            }"],
            [['aux_protesistxt'], 'required', 'when' => function($model) {
                return $model->protesis == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-protesis').val() == 'SI';
            }"],
            [['aux_transtxt'], 'required', 'when' => function($model) {
                return $model->trans == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-trans').val() == 'SI';
            }"],
            [['aux_enf_ocular_txt'], 'required', 'when' => function($model) {
                return $model->enf_ocular == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-enf_ocular').val() == 'SI';
            }"],
            [['aux_enf_auditiva_txt'], 'required', 'when' => function($model) {
                return $model->enf_auditiva == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-enf_auditiva').val() == 'SI';
            }"],
            [['aux_fractura_txt'], 'required', 'when' => function($model) {
                return $model->fractura == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-fractura').val() == 'SI';
            }"],
            [['aux_amputacion_txt'], 'required', 'when' => function($model) {
                return $model->amputacion == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-amputacion').val() == 'SI';
            }"],
            [['aux_hernias_txt'], 'required', 'when' => function($model) {
                return $model->hernias == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-hernias').val() == 'SI';
            }"],
            [['aux_enfsanguinea_txt'], 'required', 'when' => function($model) {
                return $model->enfsanguinea == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-enfsanguinea').val() == 'SI';
            }"],
            [['aux_tumorescancer_txt'], 'required', 'when' => function($model) {
                return $model->tumorescancer == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-tumorescancer').val() == 'SI';
            }"],
            [['aux_enfpsico_txt'], 'required', 'when' => function($model) {
                return $model->enfpsico == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-enfpsico').val() == 'SI';
            }"],


            [['cLentes','cLentesD'], 'required', 'when' => function($model) {
                return $model->Rlentes == 'SI';
            }, 'whenClient' => "function (attribute, value) {
                return $('#hccohc-Rlentes').val() == 'SI';
            }"],
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
            'folio' => Yii::t('app', 'Folio'),

            'nombre_empresa' => Yii::t('app', 'Empresa'),
            'nombre_medico' => Yii::t('app', 'Médico'),

            'fecha' => Yii::t('app', 'Fecha'),
            'hora' => Yii::t('app', 'Hora'),
            'examen' => Yii::t('app', 'Historia Clínica'),
            'area' => Yii::t('app', 'Área'),
            'puesto' => Yii::t('app', 'Puesto de Trabajo'),
            'nombre' => Yii::t('app', 'Nombre(s)'),
            'apellidos' => Yii::t('app', 'Apellidos'),
            'sexo' => Yii::t('app', 'Sexo'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha de Nacimiento'),
            'edad' => Yii::t('app', 'Edad'),
            'estado_civil' => Yii::t('app', 'Estado Civil'),
            'nivel_lectura' => Yii::t('app', 'Nivel de Lectura'),
            'nivel_escritura' => Yii::t('app', 'Nivel de Escritura'),
            'grupo' => Yii::t('app', 'GPO'),
            'rh' => Yii::t('app', 'Rh'),
            'diabetess' => Yii::t('app', 'Diabetes'),
            'diabetesstxt' => Yii::t('app', 'Diabetes Comentarios'),
            'hipertension' => Yii::t('app', 'Hipertensión'),
            'hipertensiontxt' => Yii::t('app', 'Hipertensión Comentarios'),
            'cancer' => Yii::t('app', 'Cáncer'),
            'cancertxt' => Yii::t('app', 'Cáncer Comentarios'),
            'nefropatias' => Yii::t('app', 'Nefropatías'),
            'nefropatiastxt' => Yii::t('app', 'Nefropatías Comentarios'),
            'cardiopatias' => Yii::t('app', 'Cardiopatías'),
            'cardiopatiastxt' => Yii::t('app', 'Cardiopatías Comentarios'),
            'reuma' => Yii::t('app', 'Enf. Reumáticas'),
            'reumatxt' => Yii::t('app', 'Enf. Reumáticas Comentarios'),
            'hepa' => Yii::t('app', 'Hepáticos'),
            'hepatxt' => Yii::t('app', 'Hepáticos Comentarios'),
            'tuber' => Yii::t('app', 'Tuberculosis'),
            'tubertxt' => Yii::t('app', 'Tuberculosis Comentarios'),
            'psi' => Yii::t('app', 'Psiquiátricos'),
            'psitxt' => Yii::t('app', 'Psiquiátricos Comentarios'),
            'tabaquismo' => Yii::t('app', 'Tabaquismo'),
            'tabdesde' => Yii::t('app', 'Edad Inicio (Años)'),
            'tabfrec' => Yii::t('app', 'Frecuencia'),
            'tabcantidad' => Yii::t('app', 'Cantidad'),
            'alcoholismo' => Yii::t('app', 'Alcoholismo'),
            'alcodesde' => Yii::t('app', 'Edad Inicio (Años)'),
            'alcofrec' => Yii::t('app', 'Frecuencia'),
            'alcocantidad' => Yii::t('app', 'Cantidad'),
            'cocina' => Yii::t('app', 'Cocina con Leña'),
            'cocinadesde' => Yii::t('app', 'Edad Inicio (Años)'),
            'audifonos' => Yii::t('app', 'Audifonos'),
            'audiodesde' => Yii::t('app', 'Frecuencia'),
            'audiocuando' => Yii::t('app', 'Cuanto Tiempo'),
            'droga' => Yii::t('app', 'Droga'),
            'drogatxt' => Yii::t('app', 'Que tipo (Droga)'),
            'duracion_droga' => Yii::t('app', 'Duración uso de Droga (Años)'),
            'fecha_droga' => Yii::t('app', 'Fecha último Consumo'),
            'vacunacion_cov' => Yii::t('app', 'Vacunación'),
            'nombre_vacunacion' => Yii::t('app', 'Nombre Vacuna'),
            'dosis_vacunacion' => Yii::t('app', 'N° Dosis'),
            'fecha_vacunacion' => Yii::t('app', 'Fecha Vacuna'),
            'mano' => Yii::t('app', 'Mano Predominante'),
            'alergias' => Yii::t('app', 'Alergias'),
            'alergiastxt' => Yii::t('app', 'Alergias'),
            'asma' => Yii::t('app', 'Asma'),
            'asmatxt' => Yii::t('app', 'Diagnóstico'),
            'asma_anio' => Yii::t('app', 'Año'),
            'cardio' => Yii::t('app', 'Cardiopatías'),
            'cardiotxt' => Yii::t('app', 'Cardiopatías'),
            'cirugias' => Yii::t('app', 'Cirugías'),
            'cirugiastxt' => Yii::t('app', 'Cirugías'),
            'convulsiones' => Yii::t('app', 'Convulsiones'),
            'convulsionestxt' => Yii::t('app', 'Convulsiones'),
            'diabetes' => Yii::t('app', 'Diabetes'),
            'diabetestxt' => Yii::t('app', 'Diabetes'),
            'hiper' => Yii::t('app', 'Hipertensión'),
            'hipertxt' => Yii::t('app', 'Hipertensión'),
            'lumbalgias' => Yii::t('app', 'Lumbalgias'),
            'lumbalgiastxt' => Yii::t('app', 'Lumbalgias'),
            'nefro' => Yii::t('app', 'Nefropatías'),
            'nefrotxt' => Yii::t('app', 'Nefropatías'),
            'polio' => Yii::t('app', 'Poliomelitis'),
            'politxt' => Yii::t('app', 'Poliomelitis'),
            'poliomelitis_anio' => Yii::t('app', 'Año'),
            'saram' => Yii::t('app', 'Sarampión'),
            'saram_anio' => Yii::t('app', 'Año'),
            'pulmo' => Yii::t('app', 'Enf. Pulmonares'),
            'pulmotxt' => Yii::t('app', 'Enf. Pulmonares'),
            'hematicos' => Yii::t('app', 'Enf. Hemáticas'),
            'hematicostxt' => Yii::t('app', 'Enf. Hemáticas'),
            'trauma' => Yii::t('app', 'Traumatismos'),
            'traumatxt' => Yii::t('app', 'Traumatismos'),
            'medicamentos' => Yii::t('app', 'Uso de Medicamentos'),
            'medicamentostxt' => Yii::t('app', 'Uso de Medicamentos'),
            'protesis' => Yii::t('app', 'Uso de Protesís'),
            'protesistxt' => Yii::t('app', 'Uso de Protesís'),
            'trans' => Yii::t('app', 'Transfusiones'),
            'transtxt' => Yii::t('app', 'Transfusiones'),
            'enf_ocular' => Yii::t('app', 'Enfermedad Ocular'),
            'enf_ocular_txt' => Yii::t('app', 'Enfermedad Ocular'),
            'enf_auditiva' => Yii::t('app', 'Enfermedad Auditiva'),
            'enf_auditiva_txt' => Yii::t('app', 'Enfermedad Auditiva'),
            'fractura' => Yii::t('app', 'Fractura / Luxación'),
            'fractura_txt' => Yii::t('app', 'Fractura / Luxación'),
            'amputacion' => Yii::t('app', 'Amputación'),
            'amputacion_txt' => Yii::t('app', 'Amputación'),
            'hernias' => Yii::t('app', 'Hernias'),
            'hernias_txt' => Yii::t('app', 'Hernias'),
            'enfsanguinea' => Yii::t('app', 'Enfermedades Sanguíneas/inmunológica: Anemia/VIH'),
            'enfsanguinea_txt' => Yii::t('app', 'Enfermedades Sanguíneas/inmunológica: Anemia/VIH'),
            'tumorescancer' => Yii::t('app', 'Tumores/Cáncer'),
            'tumorescancer_txt' => Yii::t('app', 'Tumores/Cáncer'),
            'enfpsico' => Yii::t('app', 'Enfermedades Psicológicas/Psiquiáticas'),
            'enfpsico_txt' => Yii::t('app', 'Enfermedades Psicológicas/Psiquiáticas'),
            'gestas' => Yii::t('app', 'Gestas'),
            'partos' => Yii::t('app', 'Partos'),
            'abortos' => Yii::t('app', 'Abortos'),
            'cesareas' => Yii::t('app', 'Cesareas'),
            'menarca' => Yii::t('app', 'Menarca (Años)'),
            'ivsa' => Yii::t('app', 'IVSA (Años)'),
            'fum' => Yii::t('app', 'FUM'),
            'mpf' => Yii::t('app', 'MPF'),
            'doc' => Yii::t('app', 'DOC'),
            'docma' => Yii::t('app', 'DOCMA'),
            'peso' => Yii::t('app', 'Peso(Kg)'),
            'talla' => Yii::t('app', 'Talla(Mts)'),
            'imc' => Yii::t('app', 'IMC'),
            'categoria_imc' => Yii::t('app', 'Estado Nutricional'),
            'perimetro_abdominal' => Yii::t('app', 'Perímetro Abdominal(cm)'),
            'fc' => Yii::t('app', 'F.C'),
            'fr' => Yii::t('app', 'F.R'),
            'temp' => Yii::t('app', 'Temperatura'),
            'ta' => Yii::t('app', 'T/A'),
            'ta_diastolica' => Yii::t('app', 'T/A Diastólica'),
            'caries_rd' => Yii::t('app', 'Caries'),
            'inspeccion' => Yii::t('app', 'Inspección General'),
            'inspeccion_otros' => Yii::t('app', 'Otros'),
            'txt_inspeccion_otros' => Yii::t('app', 'Describa cuales'),
            'cabeza' => Yii::t('app', 'Cabeza'),
            'cabeza_otros' => Yii::t('app', 'Otros'),
            'txt_cabeza_otros' => Yii::t('app', 'Describa cuales'),
            'oidos' => Yii::t('app', 'Oidos'),
            'oidos_otros' => Yii::t('app', 'Otros'),
            'txt_oidos_otros' => Yii::t('app', 'Describa cuales'),
            'ojos' => Yii::t('app', 'Ojos / Cara'),
            'ojos_otros' => Yii::t('app', 'Otros'),
            'txt_ojos_otros' => Yii::t('app', 'Describa cuales'),
            'sLentes' => Yii::t('app', 'Agudeza Visual sin Lentes'),
            'sLentesD' => Yii::t('app', 'Agudeza Visual sin Lentes'),
            'cLentes' => Yii::t('app', 'Agudeza Visual con Lentes Izquierda'),
            'cLentesD' => Yii::t('app', 'Agudeza Visual con Lentes Derecha'),
            'Rlentes' => Yii::t('app', '¿Requiere Lentes Graduados?'),
            'Ulentes' => Yii::t('app', '¿Cuenta con Lentes Graduados?'),
            'boca' => Yii::t('app', 'Boca/Faringe'),
            'boca_otros' => Yii::t('app', 'Boca/Faringe Otros'),
            'txt_boca_otros' => Yii::t('app', 'Describa cuales'),
            'cuello' => Yii::t('app', 'Cuello'),
            'cuello_otros' => Yii::t('app', 'Cuello Otros'),
            'txt_cuello_otros' => Yii::t('app', 'Describa cuales'),
            'torax' => Yii::t('app', 'Torax'),
            'torax_otros' => Yii::t('app', 'Torax Otros'),
            'txt_torax_otros' => Yii::t('app', 'Describa cuales'),
            'abdomen' => Yii::t('app', 'Abdomen'),
            'abdomen_otros' => Yii::t('app', 'Abdomen Otros'),
            'txt_abdomen_otros' => Yii::t('app', 'Describa cuales'),
            'superior' => Yii::t('app', 'Miembros Superiores'),
            'miembrossup_otros' => Yii::t('app', 'Otros'),
            'txt_miembrossup_otros' => Yii::t('app', 'Describa cuales'),
            'inferior' => Yii::t('app', 'Miembros Inferiores'),
            'miembrosinf_otros' => Yii::t('app', 'Otros'),
            'txt_miembrosinf_otros' => Yii::t('app', 'Describa cuales'),
            'columna' => Yii::t('app', 'Columna'),
            'columna_otros' => Yii::t('app', 'Otros'),
            'txt_columna_otros' => Yii::t('app', 'Describa cuales'),
            'txtneurologicos' => Yii::t('app', 'Neurológicos'),
            'neurologicos_otros' => Yii::t('app', 'Otros'),
            'txt_neurologicos_otros' => Yii::t('app', 'Describa cuales'),
            'diagnostico' => Yii::t('app', 'Diagnóstico'),
            'comentarios' => Yii::t('app', 'Comentarios'),
            'recomendaciones' => Yii::t('app', 'Recomendaciones'),
            'conclusion' => Yii::t('app', 'Conclusión Laboral'),
            'conclusion_cal' => Yii::t('app', 'Conclusión CAL'),
            'medico' => Yii::t('app', 'Médico'),
            'status' => Yii::t('app', 'Status HC'),
            'firma_medicolaboral' => Yii::t('app', 'Firma Medicolaboral'),

            'pso2' => Yii::t('app', 'PSO2'),

            'covidreciente' => Yii::t('app', 'Covid Reciente'),
            'covidreciente_fecha' => Yii::t('app', 'Fecha Contagio'),
            'covidreciente_secuelas' => Yii::t('app', 'Secuelas'),
            'covidreciente_vacunacion' => Yii::t('app', 'Vacunación'),
            
            'numero_emergencia' => Yii::t('app', 'Número de Emergencia'),
            'familiar_empresa' => Yii::t('app', '¿Tiene Algún Familiar en la Empresa?'),
            'id_familiar' => Yii::t('app', 'Nombre'),
            'id_area' => Yii::t('app', 'Área'),

            'vigencia' => Yii::t('app', 'Vigencia del CAL'),
            'fecha_vigencia' => Yii::t('app', 'Fecha Vigencia'),

            'aux_nuevopuesto' => Yii::t('app', 'Nuevo Puesto de Trabajo'),

            'laboral1_desde' => Yii::t('app', 'Desde'),
            'laboral1_hasta' => Yii::t('app', 'Hasta'),
            'laboral2_desde' => Yii::t('app', 'Desde'),
            'laboral2_hasta' => Yii::t('app', 'Hasta'),
            'laboral3_desde' => Yii::t('app', 'Desde'),
            'laboral3_hasta' => Yii::t('app', 'Hasta'),


            'show_inspeccion' => Yii::t('app', 'Inspección General'),
            'show_cabeza' => Yii::t('app', 'Cabeza'),
            'show_oidos' => Yii::t('app', 'Oidos'),
            'show_ojos' => Yii::t('app', 'Ojos/Cara'),
            'show_boca' => Yii::t('app', 'Boca/Faringe'),
            'show_cuello' => Yii::t('app', 'Cuello'),
            'show_torax' => Yii::t('app', 'Torax'),
            'show_abdomen' => Yii::t('app', 'Abdomen'),
            'show_superior' => Yii::t('app', 'Miembros Superiores'),
            'show_inferior' => Yii::t('app', 'Miembros Inferiores'),
            'show_columna' => Yii::t('app', 'Columna'),
            'show_txtneurologicos' => Yii::t('app', 'Neurológicos'),

            'corrective_date' => Yii::t('app', 'Fecha Corrección'),
            'corrective_user' => Yii::t('app', 'Corrigió'),
            'start_date' => Yii::t('app', 'Start'),
            'end_date' => Yii::t('app', 'End'),

            'evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
            'file_evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
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

    public function getDAlergias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'alergias'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDVacunacion()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'vacunacion'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDCardiopatias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'cardiopatias'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDCirugias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'cirugias'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDConvulsiones()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'convulsiones'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDDiabetes()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'diabetes'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDHipertension()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'hipertension'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDLumbalgias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'lumbalgias'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDNefropatias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'nefropatias'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDPulmonares()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'pulmonares'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDHematicas()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'hematicos'])
        ->orderBy(['id' => SORT_ASC]);
    }
 
    public function getDTraumatismos()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'traumatismos'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDMedicamentos()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'medicamentos'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDProtesis()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'protesis'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDTransfusiones()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'transfusiones'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDOcular()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'enfocular'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDAuditiva()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'enfauditiva'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDFractura()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'fractura'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDAmputacion()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'amputacion'])
        ->orderBy(['id' => SORT_ASC]);
    }

    public function getDHernias()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'hernias'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDSanguineas()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'enfsanguineas'])
        ->orderBy(['id' => SORT_ASC]);
    }
 
    public function getDTumores()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'tumores'])
        ->orderBy(['id' => SORT_ASC]);
    }
    
    public function getDPsicologicas()
    {
        return $this->hasMany(Detallehc::className(), ['id_hcc' => 'id'])
        ->where(['seccion'=>'psicologicas'])
        ->orderBy(['id' => SORT_ASC]);
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

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Estudios::className(), ['id' => 'id_estudio'])
        ->viaTable('hccohc_estudio', ['id_hccohc' => 'id']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestudios()
    {
        return $this->hasMany(Hccohcestudio::className(), ['id_hccohc' => 'id']);
    }

    public function getUMedico()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'id_medico']);
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicolaboral()
    {
        return $this->hasOne(Firmas::className(), ['id' => 'firma_medicolaboral']);
    }

    public function getDatavigencia()
    {
        return $this->hasOne(Vigencias::className(), ['id' => 'vigencia']);
    }

    public function getFamiliar()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_familiar']);
    }

    public function getAreafamiliar()
    {
        return $this->hasOne(Areas::class, ['id' => 'id_area']);
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


    public function getPoe()
    {
        return $this->hasOne(Poes::class, ['id' => 'id_poe']);
    }
    public function getPoeestudio()
    {
        return $this->hasOne(Poeestudio::class, ['id' => 'id_estudio_poe']);
    }

}
