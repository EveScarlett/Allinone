<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Puestostrabajo;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\TipoServicios;
use app\models\Servicios;

use app\models\Vigencias;
use app\models\Vacunacion;
use app\models\Programasaludempresa;

use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Ubicaciones;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;
/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */
/** @var yii\widgets\ActiveForm $form */
$usuario = Yii::$app->user->identity;
$modulo1 = 'Hccohc';
$modulo2 = 'hccohc';
?>

<?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}
?>

<?php
//AHF -> ANTECEDENTES HEREDO FAMILIARES
$AHF_diabetes = '';
$AHF_hipertension = '';
$AHF_cancer = '';
$AHF_nefropatias = '';
$AHF_cardiopatias = '';
$AHF_enfreumaticas = '';
$AHF_hepaticos = '';
$AHF_tuberculosis = '';
$AHF_psiquiatricos = '';

//APNP -> ANTECEDENTES PERSONALES NO PATOLOGICOS
$APNP_tabaquismofrec = '';
$APNP_tabaquismocant = '';
$APNP_alcoholismofrec = '';
$APNP_alcoholismocant = '';
$APNP_audifonosfrec = '';
$APNP_audifonoscant = '';
$APNP_drogadicciontipo = '';
$APNP_drogadiccionfecha = '';
$APNP_vacunacionnombre = '';
$APNP_vacunacionnum = '';
$APNP_mano = '';

//AGO -> ANTECEDENTES GINECO OBSTETRICOS
$AGO_mpf = '';
$AGO_doc = '';
$AGO_docma = '';

//EXP -> EXPLORACION FISICA
$EXP_inspecciong = '';
$EXP_cabeza = '';
$EXP_oidos = '';
$EXP_ojoscara = '';
$EXP_boca = '';
$EXP_cuello = '';
$EXP_torax = '';
$EXP_abdomen = '';
$EXP_miembrossup = '';
$EXP_miembrosinf = '';
$EXP_columna = '';
$EXP_neurologicos = '';

//EXPLAB -> EXPERIENCIA LABORAL
$EXPLAB_antiguedad = '';
$EXPLAB_0tiempoexposicion = '';
$EXPLAB_0exposicion = '';
$EXPLAB_1tiempoexposicion = '';
$EXPLAB_1exposicion = '';
$EXPLAB_2tiempoexposicion = '';
$EXPLAB_2exposicion = '';
$EXPLAB_3tiempoexposicion = '';
$EXPLAB_3exposicion = '';

$enf_ocular = '';
$enf_auditiva = '';
$fractura ='';
$amputacion ='';
$hernias ='';
$enf_sanguinea ='';
$tumores ='';
$enf_psicolog ='';
$fecha_laborala ='';
$fecha_laborala_2 ='';

$enf_ocular = $model->enf_ocular_txt;
$enf_auditiva = $model->enf_auditiva_txt;
$fractura = $model->fractura_txt;
$amputacion = $model->amputacion_txt;
$hernias = $model->hernias_txt;
$enf_sanguinea = $model->enfsanguinea_txt;
$tumores = $model->tumorescancer_txt;
$enf_psicolog = $model->enfpsico_txt;
$fecha_laboral1 = '';
$fecha_laboral1_2 = '';

$array_conclusion = ['0'=>'PENDIENTE','1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'];
$array_grupo = ['1'=>'A','2'=>'B','3'=>'AB','4'=>'O','5'=>'PENDIENTE'];
$array_rh = ['1'=>'POSITIVO (+)','2'=>'NEGATIVO (-)','3'=>'PENDIENTE'];
$array_lectura = ['1'=>'Bueno','2'=>'Regular','3'=>'Malo'];
$array_antlaboral =  [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'];
$array_familiares = ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'];
$array_frecuencia = ['1'=>'DIARIO','2'=>'SEMANAL','3'=>'QUINCENAL','4'=>'MENSUAL','5'=>'OCASIONAL'];
$array_tabacocant = ['1'=>'1 CIGARRO','2'=>'2 A 4 CIGARROS','3'=>'MÁS DE 5 CIGARROS'];
$array_alcoholcant = ['1'=>'1 COPA','2'=>'2 - 5 COPAS','3'=>'MÁS DE 5 COPAS'];
$array_audifonotime = ['1'=>'30 MINUTOS','2'=>'1 HORA','3'=>'2 HORAS','4'=>'3 HORAS','5'=>'4 HORAS','6'=>'5 HORAS','7'=>'6 HORAS','8'=>'7 HORAS','9'=>'8 HORAS','10'=>'MÁS DE 8 HORAS'];
$array_drogas = ['1'=>'ANFETAMINAS','2'=>'META-ANFETAMINAS','3'=>'MARIHUANA','4'=>'OPIACEOS','5'=>'BENZODIACEPINAS','6'=>'BARBITURICOS','7'=>'COCAINA','8'=>'MORFINA','9'=>'BENCICLIDINA'];
$array_drogasultimo = ['1'=>'HOY','2'=>'MENOR A UNA SEMANA','3'=>'MENOR A UN MES','4'=>'MENOR A 6 MESES','5'=>'MAYOR A 6 MESES'];
$array_vacuna = ['1'=>'PFIZER/BIONTECH','2'=>'MODERNA','3'=>'ASTRAZENECA','4'=>'JANSSEN','5'=>'SINOPHARM','6'=>'SINOVAC','7'=>'BHARAT','8'=>'CANSINO','9'=>'VALNEVA','10'=>'NOVAVAX','11'=>'SPUTNIK V','12'=>'OTRA'];
$array_vacunadosis = ['1' => 'PRIMERA', '2' => 'SEGUNDA', '3' => 'TERCERA', '4'=>'UNICA', '5'=>'REFUERZO'];
$array_alimentacion = ['1' => 'BUENA EN CALIDAD Y CANTIDAD', '2' => 'REGULAR EN CALIDAD Y CANTIDAD', '3' => 'MALA EN CALIDAD Y CANTIDAD'];
$array_vivienda = ['1'=>'CEMENTO','2'=>'LADRILLO','3'=>'TEJA','4'=>'LAMINA','5'=>'VITROPISO','6'=>'TIERRA'];
$array_servicios = ['1'=>'AGUA','2'=>'LUZ','3'=>'DRENAJE','4'=>'FOSA SÉPTICA','5'=>'PORTÁTIL'];
$array_frecuencia2 = ['1'=>'DIARIO','2'=>'CADA 2 DÍAS','3'=>'2 VECES POR SEMANA','4'=>'UNA VEZ POR SEMANA','5'=>'NO SE REALIZA'];
$array_horasuenio =  ['1'=>'MENOS DE 6 HORAS DIARIAS','2'=>'ENTRE 6 Y 8 HORAS DIARIAS','3'=>'MÁS DE 8 HORAS DIARIAS'];
$array_mpf = ['0'=>'CONDÓN FEMENINO','1'=>'CONDÓN MASCULINO','2'=>'PASTILLAS ANTICONCEPTIVAS','3'=>'PARCHES ANTICONCEPTIVOS','4'=>'IMPLANTE SUDÉRMICO','5'=>'INYECCIONES ANTICONCEPTIVAS','6'=>'PASTILLA ANTICONCEPCIÓN DE EMERGENCIA','7'=>'DISPOSITIVO INTRAUTERINO','8'=>'ANILLO VAGINAL','9'=>'MÉTODOS PERMANENTES','10'=>'SISTEMA INTRAUTERINO (SIU)','11'=>'NINGUNO'];
$array_doc = ['1'=>'NUNCA','2'=>'MENOS DE 1 AÑO','3'=>'MENOS DE 2 AÑOS','4'=>'MÁS DE 2 AÑOS'];
$array_inspeccion = ['1'=>'ORIENTADO','2'=>'DESORIENTADO','3'=>'HIDRATADO','4'=>'DESHIDRATADO','5'=>'BUENA COLORACIÓN','6'=>'PALIDEZ','7'=>'ICTERICIA','8'=>'MARCHA ANORMAL','9'=>'MARCHA NORMAL','10'=>'SIN DATOS PATOLÓGICOS'];
$array_cabeza = ['1' => 'NORMOCÉFALO', '2' => 'ALOPECIA', '3' => 'CABELLO BIEN IMPLANTADO', '4' => 'SIN DATOS PATOLÓGICOS'];
$array_oidos = ['1' => 'INTEGRA', '2' => 'SIMÉTRICOS', '3' => 'SIN DOLOR A LA PALPACIÓN', '4' => 'MEMBRANA TIMPÁNICA SIN ALTERACIONES', '5' => 'MALFORMACIÓN CONGÉNITA', '6' => 'MEMBRANA TIMPÁNICA ANORMAL', '7' => 'ADENOPATÍA PREARICULAR PALPABLE', '8' => 'SIN DATOS PATOLÓGICOS'];
$array_ojos = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'PUPILAS ISOCÓRICAS','4'=>'PUPILAS ANISOCÓRICAS','5'=>'FOSAS PERMEABLES','6'=>'FOSAS OBSTRUIDAS','7'=>'ADENOMEGALIAS RETROAURICULARES','8'=>'ADENOMEGALIAS SUBMANDIBULARES','9'=>'NO PALPABLES','10'=>'SIN DATOS PATOLÓGICOS'];
$array_boca = ['1'=>'NORMAL','2'=>'HIPEREMIA','3'=>'AMÍGDALAS NORMALES','4'=>'AMÍGDALAS HIPERTRÓFICAS','5'=>'SIN DATOS PATOLÓGICOS','6'=>'EXUDADO PURULENTO'];
$array_cuello = ['1'=>'TRAQUEA CENTRAL','2'=>'CILÍNDRICO','3'=>'CRECIMIENTO TIROIDEO','4'=>'ADENOMEGALIAS RETROAURICULARES','5'=>'ADENOMEGALIAS SUBMANDIBULARES','7'=>'SIN DATOS PATOLÓGICOS','6'=>'NO PALPABLES'];
$array_torax = ['1'=>'CAMPOS PULMONARES VENTILADOS','2'=>'ESTERTORES','3'=>'SIBILANCIAS','4'=>'RUIDOS CARDIACOS RÍTMICOS','5'=>'ARRITMIA','6'=>'ADENOMEGALIAS AXILARES','7'=>'NORMOLÍNEO','8'=>'SIN DATOS PATOLÓGICOS'];
$array_abdomen = ['1'=>'GLOBOSO','2'=>'PLANO','3'=>'BLANDO Y DEPRESIBLE','4'=>'ABDOMEN EN MADERA','5'=>'DOLOR A LA PALPACIÓN','6'=>'VISCEROMEGALIAS','7'=>'RESISTENCIA','8'=>'HEPATOMEGALIA','9'=>'ESPLENOMEGALIA','10'=>'PERISTALSIS ALTERADA','11'=>'SIN DATOS PATOLÓGICOS'];
$array_miembrossup = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'SIMÉTRICOS','4'=>'PULSOS PALPABLES','5'=>'ALTERACIONES FUNCIONALES','6'=>'ALTERACIONES ESTRUCTURALES','7'=>'ALTERACIONES VASCULARES','8'=>'SIN DATOS PATOLÓGICOS'];
$array_miembrosinf = ['1'=>'ÍNTEGROS','2'=>'PRÓTESIS','3'=>'SIMÉTRICOS','4'=>'PULSOS PALPABLES','5'=>'ALTERACIONES FUNCIONALES','6'=>'ALTERACIONES ESTRUCTURALES','7'=>'ALTERACIONES VASCULARES','8'=>'SIN DATOS PATOLÓGICOS'];
$array_columna = ['1'=>'INTEGRA','2'=>'SIN LIMITACIONES FUNCIONALES','3'=>'MOVIMIENTOS MUSCULOESQUELÉTICOS LIMITADOS','4'=>'SIN DATOS PATOLÓGICOS'];
$array_neurologicos = ['1'=>'RESPUESTA VERBAL ALTERADA','2'=>'RESPUESTA MOTORA ALTERADA','3'=>'ALTERACIONES DE LA MEMORIA','4'=>'DESORIENTADO','5'=>'SIN DATOS PATOLÓGICOS'];

$array_manos = ['1' => 'IZQUIERDA', '2' => 'DERECHA', '3' => 'AMBIDIESTRO'];

$array_sexo = ['1' => 'MASCULINO', '2' => 'FEMENINO'];

$array_antiguedad = ['1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'];
$array_exposiciones = ['1' => 'Ruido', '2' => 'Polvos/Humos/Vap', '3' => 'Mov. Repetidos', '4' => 'Temperatura', '5' => 'Químicos', '6' => 'Vibraciones', '7' => 'Biológicos', '8' => 'Iluminación', '9' => 'Levantar Peso'];

$estadocivil = ['1'=>'Soltero','2'=>'Casado','3'=>'Viudo','4'=>'Unión Libre'];

$array_conclusionhc = [
    '1'=>'SANO Y APTO',
    '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
    '3'=>'APTO TEMPORAL',
    '4'=>'PENDIENTE',
    '5'=>'NO APTO',
];
?>


<?php
$showprevia = 'none';

if($hc_anterior){
    $showprevia = 'block';

    if($hc_anterior->diabetess =='NO'){
        $AHF_diabetes = 'NO';
    }else{
        $AHF_diabetes = '';
        $array = explode(',', $hc_anterior->diabetesstxt);
    
        if(isset($hc_anterior->diabetesstxt) && $hc_anterior->diabetesstxt != null && $hc_anterior->diabetesstxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_diabetes .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_diabetes .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->hipertension =='NO'){
        $AHF_hipertension = 'NO';
    }else{
        $AHF_hipertension = '';
        $array = explode(',', $hc_anterior->hipertensiontxt);
    
        if(isset($hc_anterior->hipertensiontxt) && $hc_anterior->hipertensiontxt != null && $hc_anterior->hipertensiontxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_hipertension .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_hipertension .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->cancer =='NO'){
        $AHF_cancer = 'NO';
    }else{
        $AHF_cancer = '';
        $array = explode(',', $hc_anterior->cancertxt);
    
        if(isset($hc_anterior->cancertxt) && $hc_anterior->cancertxt != null && $hc_anterior->cancertxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_cancer .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_cancer .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->nefropatias =='NO'){
        $AHF_nefropatias = 'NO';
    }else{
        $AHF_nefropatias = '';
        $array = explode(',', $hc_anterior->nefropatiastxt);
    
        if(isset($hc_anterior->nefropatiastxt) && $hc_anterior->nefropatiastxt != null && $hc_anterior->nefropatiastxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_nefropatias .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_nefropatias .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->cardiopatias =='NO'){
        $AHF_cardiopatias = 'NO';
    }else{
        $AHF_cardiopatias = '';
        $array = explode(',', $hc_anterior->cardiopatiastxt);
    
        if(isset($hc_anterior->cardiopatiastxt) && $hc_anterior->cardiopatiastxt != null && $hc_anterior->cardiopatiastxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_cardiopatias .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_cardiopatias .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->reuma =='NO'){
        $AHF_enfreumaticas = 'NO';
    }else{
        $AHF_enfreumaticas = '';
        $array = explode(',', $hc_anterior->reumatxt);
    
        if(isset($hc_anterior->reumatxt) && $hc_anterior->reumatxt != null && $hc_anterior->reumatxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_enfreumaticas .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_enfreumaticas .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->tuber =='NO'){
        $AHF_tuberculosis = 'NO';
    }else{
        $AHF_tuberculosis = '';
        $array = explode(',', $hc_anterior->tubertxt);
    
        if(isset($hc_anterior->tubertxt) && $hc_anterior->tubertxt != null && $hc_anterior->tubertxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_tuberculosis .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_tuberculosis .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->hepa =='NO'){
        $AHF_hepaticos = 'NO';
    }else{
        $AHF_hepaticos = '';
        $array = explode(',', $hc_anterior->hepatxt);
    
        if(isset($hc_anterior->hepatxt) && $hc_anterior->hepatxt != null && $hc_anterior->hepatxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_hepaticos .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_hepaticos .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->psi =='NO'){
        $AHF_psiquiatricos = 'NO';
    }else{
        $AHF_psiquiatricos = '';
        $array = explode(',', $hc_anterior->psitxt);
    
        if(isset($hc_anterior->psitxt) && $hc_anterior->psitxt != null && $hc_anterior->psitxt != ''){
            foreach($array as $key=>$elemento){
                $AHF_psiquiatricos .= $array_familiares[$elemento];  
                if($key < (count($array)-1)){
                    $AHF_psiquiatricos .= ', ';
                }
            }
        }
    }
    
    if($hc_anterior->tabaquismo =='SI'){
        $APNP_tabaquismofrec = $array_frecuencia[$hc_anterior->tabfrec];
        $APNP_tabaquismocant = $array_tabacocant[$hc_anterior->tabcantidad]; 
    }
    if($hc_anterior->alcoholismo =='SI'){
        $APNP_alcoholismofrec = $array_frecuencia[$hc_anterior->alcofrec];
        $APNP_alcoholismocant = $array_alcoholcant[$hc_anterior->alcocantidad];
    }
    if($hc_anterior->audifonos =='SI'){
        $APNP_audifonosfrec = $array_frecuencia[$hc_anterior->audiodesde];
        $APNP_audifonoscant = $array_audifonotime[$hc_anterior->audiocuando];
    }
    
    if($hc_anterior->droga =='NO'){
        $APNP_drogadicciontipo = 'NO';
    }else{
        $array = explode(',', $hc_anterior->drogatxt);
    
        if(isset($hc_anterior->drogatxt) && $hc_anterior->drogatxt != null && $hc_anterior->drogatxt != ''){
            foreach($array as $key=>$elemento){
                $APNP_drogadicciontipo .= $array_drogas[$elemento];  
                if($key < (count($array)-1)){
                    $APNP_drogadicciontipo .= ', ';
                }
            }
        }
    
        if(isset($hc_anterior->fecha_droga) && $hc_anterior->fecha_droga != null && $hc_anterior->fecha_droga != ''){
            $APNP_drogadiccionfecha = $array_drogasultimo[$hc_anterior->fecha_droga];
        }
    }
    
    if($hc_anterior->vacunacion_cov =='SI'){
        if(isset($hc_anterior->nombre_vacunacion) && $hc_anterior->nombre_vacunacion != ''){
            $APNP_vacunacionnombre = $array_vacuna[$hc_anterior->nombre_vacunacion];
        }
        if(isset($hc_anterior->dosis_vacunacion) && $hc_anterior->dosis_vacunacion != ''){
            $APNP_vacunacionnum = $array_vacunadosis[$hc_anterior->dosis_vacunacion];
        }
    }
    
    if(isset($hc_anterior->alimentacion) && $hc_anterior->alimentacion!= '' && array_key_exists($hc_anterior->alimentacion, $array_alimentacion)){
        $APHD_Alimentacion = $array_alimentacion[$hc_anterior->alimentacion];
    }
    
    if(isset($hc_anterior->vivienda) && $hc_anterior->vivienda!= ''){
        $array = explode(',', $hc_anterior->vivienda);
    
        if(isset($hc_anterior->vivienda) && $hc_anterior->vivienda != null && $hc_anterior->vivienda != ''){
            foreach($array as $key=>$elemento){
                $APHD_Vivienda .= $array_vivienda[$elemento];  
                if($key < (count($array)-1)){
                    $APHD_Vivienda .= ', ';
                }
            }
        }
    }
    
    if(isset($hc_anterior->servicios) && $hc_anterior->servicios!= ''){
        $array = explode(',', $hc_anterior->servicios);
    
        if(isset($hc_anterior->servicios) && $hc_anterior->servicios != null && $hc_anterior->servicios != ''){
            foreach($array as $key=>$elemento){
                $APHD_Servicios .= $array_servicios[$elemento];  
                if($key < (count($array)-1)){
                    $APHD_Servicios .= ', ';
                }
            }
        }
    }
    
    if(isset($hc_anterior->wc) && $hc_anterior->wc!= '' && array_key_exists($hc_anterior->wc, $array_frecuencia2)){
        $APHD_Banio = $array_frecuencia2[$hc_anterior->wc];
    }
    
    if(isset($hc_anterior->ropa) && $hc_anterior->ropa!= '' && array_key_exists($hc_anterior->ropa, $array_frecuencia2)){
        $APHD_Ropa = $array_frecuencia2[$hc_anterior->ropa];
    }
    
    if(isset($hc_anterior->bucal) && $hc_anterior->bucal!= '' && array_key_exists($hc_anterior->bucal, $array_frecuencia2)){
        $APHD_Bucal = $array_frecuencia2[$hc_anterior->bucal];
    }
    
    if(isset($hc_anterior->deporte) && $hc_anterior->deporte!= '' && array_key_exists($hc_anterior->deporte, $array_frecuencia2)){
        $APHD_Deporte = $array_frecuencia2[$hc_anterior->deporte];
    }
    
    if(isset($hc_anterior->recreativa) && $hc_anterior->recreativa!= '' && array_key_exists($hc_anterior->recreativa, $array_frecuencia2)){
        $APHD_Actividad = $array_frecuencia2[$hc_anterior->recreativa];
    }
    
    if(isset($hc_anterior->horas) && $hc_anterior->horas!= '' && array_key_exists($hc_anterior->horas, $array_horasuenio)){
        $APHD_Suenio = $array_horasuenio[$hc_anterior->horas];
    }
    
    if(isset($hc_anterior->mpf) && $hc_anterior->mpf!= '' && array_key_exists($hc_anterior->mpf, $array_mpf)){
        $AGO_mpf = $array_mpf[$hc_anterior->mpf];
    }
    
    if(isset($hc_anterior->doc) && $hc_anterior->doc!= '' && array_key_exists($hc_anterior->doc, $array_doc)){
        $AGO_doc = $array_doc[$hc_anterior->doc];
    }
    
    if(isset($hc_anterior->docma) && $hc_anterior->docma!= '' && array_key_exists($hc_anterior->docma, $array_doc)){
        $AGO_docma = $array_doc[$hc_anterior->docma];
    }
    
    if(isset($hc_anterior->mano) && $hc_anterior->mano!= '' && array_key_exists($hc_anterior->mano, $array_manos)){
        $APNP_mano = $array_manos[$hc_anterior->mano];
    }
    
    
    if(isset($hc_anterior->inspeccion) && $hc_anterior->inspeccion != ''){
        $array = explode(',', $hc_anterior->inspeccion);
    
        if(isset($hc_anterior->inspeccion) && $hc_anterior->inspeccion != null && $hc_anterior->inspeccion != ''){
            foreach($array as $key=>$elemento){
                $EXP_inspecciong .= $array_inspeccion[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_inspecciong .= ', ';
                }
            }
        }
    }
    if($hc_anterior->inspeccion_otros == 1){
        $EXP_inspecciong .= ', '.$hc_anterior->txt_inspeccion_otros; 
    }
    
    
    if(isset($hc_anterior->cabeza) && $hc_anterior->cabeza != ''){
        $array = explode(',', $hc_anterior->cabeza);
    
        if(isset($hc_anterior->cabeza) && $hc_anterior->cabeza != null && $hc_anterior->cabeza != ''){
            foreach($array as $key=>$elemento){
                $EXP_cabeza .= $array_cabeza[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_cabeza .= ', ';
                }
            }
        }
    }
    if($hc_anterior->cabeza_otros == 1){
        $EXP_cabeza .= ', '.$hc_anterior->txt_cabeza_otros; 
    }
    
    
    if(isset($hc_anterior->oidos) && $hc_anterior->oidos != ''){
        $array = explode(',', $hc_anterior->oidos);
    
        if(isset($hc_anterior->oidos) && $hc_anterior->oidos != null && $hc_anterior->oidos != ''){
            foreach($array as $key=>$elemento){
                $EXP_oidos .= $array_oidos[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_oidos .= ', ';
                }
            }
        }
    }
    if($hc_anterior->oidos_otros == 1){
        $EXP_oidos .= ', '.$hc_anterior->txt_oidos_otros; 
    }
    
    
    if(isset($hc_anterior->ojos) && $hc_anterior->ojos != ''){
        $array = explode(',', $hc_anterior->ojos);
    
        if(isset($hc_anterior->ojos) && $hc_anterior->ojos != null && $hc_anterior->ojos != ''){
            foreach($array as $key=>$elemento){
                $EXP_ojoscara .= $array_ojos[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_ojoscara .= ', ';
                }
            }
        }
    }
    if($hc_anterior->ojos_otros == 1){
        $EXP_ojoscara .= ', '.$hc_anterior->txt_ojos_otros; 
    }
    
    
    if(isset($hc_anterior->boca) && $hc_anterior->boca != ''){
        $array = explode(',', $hc_anterior->boca);
    
        if(isset($hc_anterior->boca) && $hc_anterior->boca != null && $hc_anterior->boca != ''){
            foreach($array as $key=>$elemento){
                $EXP_boca .= $array_boca[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_boca .= ', ';
                }
            }
        }
    }
    if($hc_anterior->boca_otros == 1){
        $EXP_boca .= ', '.$hc_anterior->txt_boca_otros; 
    }
    
    
    if(isset($hc_anterior->cuello) && $hc_anterior->cuello != ''){
        $array = explode(',', $hc_anterior->cuello);
    
        if(isset($hc_anterior->cuello) && $hc_anterior->cuello != null && $hc_anterior->cuello != ''){
            foreach($array as $key=>$elemento){
                $EXP_cuello .= $array_cuello[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_cuello .= ', ';
                }
            }
        } 
    }
    if($hc_anterior->cuello_otros == 1){
        $EXP_cuello .= ', '.$hc_anterior->txt_cuello_otros; 
    }
    
    
    if(isset($hc_anterior->torax) && $hc_anterior->torax != ''){
        $array = explode(',', $hc_anterior->torax);
    
        if(isset($hc_anterior->torax) && $hc_anterior->torax != null && $hc_anterior->torax != ''){
            foreach($array as $key=>$elemento){
                $EXP_torax .= $array_torax[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_torax .= ', ';
                }
            }
        }
    }
    if($hc_anterior->torax_otros == 1){
        $EXP_torax .= ', '.$hc_anterior->txt_torax_otros; 
    }
    
    
    if(isset($hc_anterior->abdomen) && $hc_anterior->abdomen != ''){
        $array = explode(',', $hc_anterior->abdomen);
    
        if(isset($hc_anterior->abdomen) && $hc_anterior->abdomen != null && $hc_anterior->abdomen != ''){
            foreach($array as $key=>$elemento){
                $EXP_abdomen .= $array_abdomen[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_abdomen .= ', ';
                }
            }
        }
    }
    if($hc_anterior->abdomen_otros == 1){
        $EXP_abdomen .= ', '.$hc_anterior->txt_abdomen_otros; 
    }
    
    
    if(isset($hc_anterior->superior) && $hc_anterior->superior != ''){
        $array = explode(',', $hc_anterior->superior);
    
        if(isset($hc_anterior->superior) && $hc_anterior->superior != null && $hc_anterior->superior != ''){
            foreach($array as $key=>$elemento){
                $EXP_miembrossup .= $array_miembrossup[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_miembrossup .= ', ';
                }
            }
        } 
    }
    if($hc_anterior->miembrossup_otros == 1){
        $EXP_miembrossup .= ', '.$hc_anterior->txt_miembrossup_otros; 
    }
    
    
    if(isset($hc_anterior->inferior) && $hc_anterior->inferior != ''){
        $array = explode(',', $hc_anterior->inferior);
    
        if(isset($hc_anterior->inferior) && $hc_anterior->inferior != null && $hc_anterior->inferior != ''){
            foreach($array as $key=>$elemento){
                $EXP_miembrosinf .= $array_miembrosinf[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_miembrosinf .= ', ';
                }
            }
        }
    }
    if($hc_anterior->miembrosinf_otros == 1){
        $EXP_miembrosinf .= ', '.$hc_anterior->txt_miembrosinf_otros; 
    }
    
    
    if(isset($hc_anterior->columna) && $hc_anterior->columna != ''){
        $array = explode(',', $hc_anterior->columna);
    
        if(isset($hc_anterior->columna) && $hc_anterior->columna != null && $hc_anterior->columna != ''){
            foreach($array as $key=>$elemento){
                $EXP_columna .= $array_columna[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_columna .= ', ';
                }
            }
        }
    }
    if($hc_anterior->columna_otros == 1){
        $EXP_columna .= ', '.$hc_anterior->txt_columna_otros; 
    }
    
    
    if(isset($hc_anterior->txtneurologicos) && $hc_anterior->txtneurologicos != ''){
        $array = explode(',', $hc_anterior->txtneurologicos);
    
        if(isset($hc_anterior->txtneurologicos) && $hc_anterior->txtneurologicos != null && $hc_anterior->txtneurologicos != ''){
            foreach($array as $key=>$elemento){
                $EXP_neurologicos .= $array_neurologicos[$elemento];  
                if($key < (count($array)-1)){
                    $EXP_neurologicos .= ', ';
                }
            }
        }
    }
    if($hc_anterior->neurologicos_otros == '1'){
        $EXP_neurologicos .= ', '.$hc_anterior->txt_neurologicos_otros; 
    }
    
    
    if(isset($hc_anterior->antlaboral_antiguedad) && $hc_anterior->antlaboral_antiguedad!= '' && array_key_exists($hc_anterior->antlaboral_antiguedad, $array_antiguedad)){
        $EXPLAB_antiguedad = $array_antiguedad[$hc_anterior->antlaboral_antiguedad];
    }
    
    if(isset($hc_anterior->laboral0_tiempoexposicion) && $hc_anterior->laboral0_tiempoexposicion!= '' && array_key_exists($hc_anterior->laboral0_tiempoexposicion, $array_antiguedad)){
        $EXPLAB_0tiempoexposicion = $array_antiguedad[$hc_anterior->laboral0_tiempoexposicion];
    }
    
    if(isset($hc_anterior->laboral1_tiempoexposicion) && $hc_anterior->laboral1_tiempoexposicion!= '' && array_key_exists($hc_anterior->laboral1_tiempoexposicion, $array_antiguedad)){
        $EXPLAB_1tiempoexposicion = $array_antiguedad[$hc_anterior->laboral1_tiempoexposicion];
    }
    
    if(isset($hc_anterior->laboral2_tiempoexposicion) && $hc_anterior->laboral2_tiempoexposicion!= '' && array_key_exists($hc_anterior->laboral2_tiempoexposicion, $array_antiguedad)){
        $EXPLAB_2tiempoexposicion = $array_antiguedad[$hc_anterior->laboral2_tiempoexposicion];
    }
    
    if(isset($hc_anterior->laboral3_tiempoexposicion) && $hc_anterior->laboral3_tiempoexposicion!= '' && array_key_exists($hc_anterior->laboral3_tiempoexposicion, $array_antiguedad)){
        $EXPLAB_3tiempoexposicion = $array_antiguedad[$hc_anterior->laboral3_tiempoexposicion];
    }
    
    if(isset($hc_anterior->laboral0_exposicion) && $hc_anterior->laboral0_exposicion != ''){
        $array = explode(',', $hc_anterior->laboral0_exposicion);
    
        if(isset($hc_anterior->laboral0_exposicion) && $hc_anterior->laboral0_exposicion != null && $hc_anterior->laboral0_exposicion != ''){
            foreach($array as $key=>$elemento){
                $EXPLAB_0exposicion .= $array_exposiciones[$elemento];  
                if($key < (count($array)-1)){
                    $EXPLAB_0exposicion .= ', ';
                }
            }
        }
    }
    
    if(isset($hc_anterior->laboral1_exposicion) && $hc_anterior->laboral1_exposicion != ''){
        $array = explode(',', $hc_anterior->laboral1_exposicion);
    
        if(isset($hc_anterior->laboral1_exposicion) && $hc_anterior->laboral1_exposicion != null && $hc_anterior->laboral1_exposicion != ''){
            foreach($array as $key=>$elemento){
                $EXPLAB_1exposicion .= $array_exposiciones[$elemento];  
                if($key < (count($array)-1)){
                    $EXPLAB_1exposicion .= ', ';
                }
            }
        }
    }
    
    if(isset($hc_anterior->laboral2_exposicion) && $hc_anterior->laboral2_exposicion != ''){
        $array = explode(',', $hc_anterior->laboral2_exposicion);
    
        if(isset($hc_anterior->laboral2_exposicion) && $hc_anterior->laboral2_exposicion != null && $hc_anterior->laboral2_exposicion != ''){
            foreach($array as $key=>$elemento){
                $EXPLAB_2exposicion .= $array_exposiciones[$elemento];  
                if($key < (count($array)-1)){
                    $EXPLAB_2exposicion .= ', ';
                }
            }
        }
    }
    
    if(isset($hc_anterior->laboral3_exposicion) && $hc_anterior->laboral3_exposicion != ''){
        $array = explode(',', $hc_anterior->laboral3_exposicion);
    
        if(isset($hc_anterior->laboral3_exposicion) && $hc_anterior->laboral3_exposicion != null && $hc_anterior->laboral3_exposicion != ''){
            foreach($array as $key=>$elemento){
                $EXPLAB_3exposicion .= $array_exposiciones[$elemento];  
                if($key < (count($array)-1)){
                    $EXPLAB_3exposicion .= ', ';
                }
            }
        }
    }
}
?>


<?php
$modelo_ = 'hccohc';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$usuario = Yii::$app->user->identity;
if($usuario->nivel1_all == 1){//$usuario->nivel1_all == 1
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
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

    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array])->orderBy('id_pais')->all(), 'id', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
}



if($usuario->nivel2_all == 1){//$usuario->nivel2_all == 1
    //->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}  else {
    $array = explode(',', $usuario->nivel2_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }
    //->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}



if($usuario->nivel3_all == 1){//$usuario->nivel3_all == 1
    //->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}  else {
    $array = explode(',', $usuario->nivel3_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    //->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}




if($usuario->nivel4_all == 1){//$usuario->nivel4_all == 1
    //->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}  else {
    $array = explode(',', $usuario->nivel4_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    //->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}



if($model->id_empresa != null && $model->id_empresa != '' && $model->id_empresa != ' '){
    $empresa = Empresas::findOne($model->id_empresa);

    if($empresa){
        $label_nivel1 = $empresa->label_nivel1;
        $label_nivel2 = $empresa->label_nivel2;
        $label_nivel3 = $empresa->label_nivel3;
        $label_nivel4 = $empresa->label_nivel4;

        if($empresa->cantidad_niveles >= 1){
            $show_nivel1 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 2){
            $show_nivel2 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 3){
            $show_nivel3 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 4){
            $show_nivel4 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->andWhere(['in','id',$array])->all(), 'id','area');
            }

        }
    }
}
//dd($model);
?>

<?php
$this->registerCss(".select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
    color: black;
    background: #636AF25a;
    border: 1px solid #636AF2;
    border-radius: 10px 10px 10px 10px;
    cursor: default;
    float: left;
    margin: 5px 0 0 6px;
    padding: 0 6px;
    font-size:17px;
}

.select2-container--bootstrap .select2-selection{
    background-color:transparent;
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
}

.select2-container--bootstrap .select2-selection {
    border: none;
    border-radius:0px;
    border-bottom: 1px solid #0d6efd;
    font-size:12px;
}
");
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$puestos[0] = 'NUEVO PUESTO';
$programas = [];

if(isset($model->id_empresa)){
    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();

    //dd($empresa);
    if($empresa){
        $p_e = Programasaludempresa::find()->where(['id_empresa'=>$model->id_empresa])->all();
        foreach($p_e as $key=>$pe){
            if($pe->programa){
                $programas[$pe->programa->id] = $pe->programa->nombre;
            }
        }
        //dd($programas);
        //$programas = ArrayHelper::map($empresa->programas, 'id', 'nombre');
    }
}

$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('apellidos')->all(), 'id', function($data){
    $counthc = '[ '.($data->historiasactivas).' HC ]';
    return $data['apellidos'].' '.$data['nombre']. ' '.$counthc;
});

$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];
if($model->scenario == 'create'){
    //$tipoexamen = ['3'=>'PERIODICO'];
} else if($model->scenario == 'correction'){
    //$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];
} else if($model->scenario == 'update'){
    if($model->status == 0){
        if($model->conclusion == null || $model->conclusion == ''|| $model->conclusion == ' '){
            //$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ'];
        }
    }
}


$tipos = ArrayHelper::map(TipoServicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$estudios = ArrayHelper::map(Servicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$estudios[0] ='NUEVO ESTUDIO';

$vigencias = ArrayHelper::map(Vigencias::find()->orderBy('orden')->all(), 'id', 'vigencia');

$array_vacunas = ArrayHelper::map(Vacunacion::find()->orderBy('vacuna')->all(), 'id', 'vacuna');
$array_vacunas[0] ='NUEVA VACUNA';
?>


<?php
$id_paises = [];
$id_lineas = [];
$id_ubicaciones = [];

if(1==2){
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->select(['id_pais'])->distinct()->all(); 
} else {
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id_pais',$id_paises])->select(['id_pais'])->distinct()->all(); 
}

$id_paises = [];
foreach($paisempresa as $key=>$pais){
    array_push($id_paises, $pais->id_pais);
}
   
$paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');



if(1==2){
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
} else {
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->andWhere(['in','id',$id_lineas])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
}

$id_lineas = [];
foreach($lineas as $key=>$linea){
    array_push($id_lineas, $key);
}

if(1==2){
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
} else {
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->andWhere(['in','id',$id_ubicaciones])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
}

?>

<div class="hcc-ohc-form">

    <div class="row">
        <div class="col-lg-12 sticky">
            <div class="btn-group btn-group-lg">
                <button type="button" class="btn btn-primary btn-block" id="btn_hcactual">HC ACTUAL</button>
                <button type="button" class="btn btn-primary btn-block" id="btn_hcanterior">HC PREVIA</button>
            </div>
        </div>
        <div class="col-lg-12" id="actual_hc" style="display:block;">

            <?php $form = ActiveForm::begin(['id'=>'formOHC','options' => ['enctype' => 'multipart/form-data'],]); ?>

            <div class="row">
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'id_poe')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'id_estudio_poe')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
            </div>


            <?php
            //changeempresa(this.value,"hccohc")
            ?>
            <div class="row my-3">
                <div class="col-lg-6 mt-3" style="display:<?php echo $showempresa?>;">
                    <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"hccohc")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <!-- <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"hccohc")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_linea')->widget(Select2::classname(), [
                    'data' => $lineas,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changelinea(this.value,"hccohc")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_ubicacion')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div> -->
                <div class="col-lg-3">
                    <?= $form->field($model, 'folio')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]) ?>
                </div>
            </div>


            <div class="row my-3">
                <div class="col-lg-6">
                    <?= $form->field($model, 'id_trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'examen')->widget(Select2::classname(), [
                    'data' => $tipoexamen,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
                    <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel1">'.$label_nivel1.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
                    <?= $form->field($model, 'id_nivel2')->widget(Select2::classname(), [
                    'data' => $nivel2,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel2">'.$label_nivel2.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
                    <?= $form->field($model, 'id_nivel3')->widget(Select2::classname(), [
                    'data' => $nivel3,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel3">'.$label_nivel3.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
                    <?= $form->field($model, 'id_nivel4')->widget(Select2::classname(), [
                    'data' => $nivel4,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
                </div>
            </div>


            <div class="row my-3">
                <div class="col-lg-3">
                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaSexo(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>
                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'fecha_nacimiento')->textInput(['maxlength' => true,'onchange'=>'calculoEdad2(this);']);?>

                </div>
                <div class="col-lg-1">
                    <?= $form->field($model, 'edad')->textInput(['maxlength' => true,'onkeyup' => '','readonly'=>true]) ?>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-2">
                    <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'puesto')->widget(Select2::classname(), [
                    'data' => $puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaPuestoHc(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <?php
                $display_puesto = 'none';
                if($model->puesto == 0){
                    $display_puesto = 'block';
                }
                ?>
                <div class="col-lg-4" id="bloquenuevo_puesto" style="display:<?=$display_puesto;?>;">
                    <?= $form->field($model, 'aux_nuevopuesto')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','onchange'=>'cambiaNuevopuesto(this.value);']) ?>
                </div>
            </div>

            <div class="row my-3 mt-5">
                <div class="col-lg-2">
                    <?= $form->field($model, 'nivel_lectura')->widget(Select2::classname(), [
                    'data' => ['1'=>'Bueno','2'=>'Regular','3'=>'Malo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'nivel_escritura')->widget(Select2::classname(), [
                    'data' => ['1'=>'Bueno','2'=>'Regular','3'=>'Malo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'estado_civil')->widget(Select2::classname(), [
                    'data' => ['1'=>'Soltero','2'=>'Casado','3'=>'Viudo','4'=>'Unión Libre'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>

                <div class="col-lg-2 offset-lg-1">
                    <?= $form->field($model, 'grupo')->widget(Select2::classname(), [
                    'data' => ['1'=>'A','2'=>'B','3'=>'AB','4'=>'O','5'=>'PENDIENTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'rh')->widget(Select2::classname(), [
                    'data' => ['1'=>'POSITIVO (+)','2'=>'NEGATIVO (-)','3'=>'PENDIENTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
            </div>


            <div class="row my-3 mt-5">
                <div class="col-lg-2">
                    <?= $form->field($model, 'numero_emergencia')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'familiar_empresa')->radioList(['SI' => 'SI', 'NO' => 'NO'], [ 'separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label() ?>
                </div>
                <?php 
                $show = 'none';
                if($model->familiar_empresa == 'SI'){
                    $show = 'block';
                }
                ?>
                <div class="col-lg-4" id="familiar1" style="display:<?php echo $show;?>;">
                    <?= $form->field($model, 'id_familiar')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3" id="familiar2" style="display:<?php echo $show;?>;">
                    <?= $form->field($model, 'id_area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>

                </div>
            </div>

            <?php
            $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
            <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
            <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
            </svg>';
            ?>


            <?php if($model->poe_doc1 != null || $model->poe_doc2 != null || $model->poe_doc3 != null):?>
            <div class="container-fluid my-3 border30 bg-customlight border-custom p-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>Evidencia HC
                        </label>
                    </div>
                </div>
                <div class="row my-3 border-bottom py-2">
                    <?php if($model->poe_doc1 != null && $model->poe_doc1 != '' && $model->poe_doc1 != ' '):?>
                    <div class="col-lg-4">
                        <?php
                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';
                        $retdoc1 = '';
                            if($model->poe){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc1;
                                $retdoc1 .= Html::a('<div class="rounded-3 p-4 puntero btnnew text-light title1"><span style="font-size:30px;" class="text-light mx-2">'.$image.'</span>Documento 1
                                 </div>', $filePath, $options = ['target'=>'_blank']);
                            }
                        echo $retdoc1;
                        ?>
                    </div>
                    <?php endif;?>
                    <?php if($model->poe_doc2 != null && $model->poe_doc2 != '' && $model->poe_doc2 != ' '):?>
                    <div class="col-lg-4">
                        <?php
                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';
                        $retdoc2 = '';
                            if($model->poe){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc2;
                                $retdoc2 .= Html::a('<div class="rounded-3 p-4 puntero btnnew2 text-light title1"><span style="font-size:30px;" class="text-light mx-2">'.$image.'</span>Documento 2
                                 </div>', $filePath, $options = ['target'=>'_blank']);
                            }
                        echo $retdoc2;
                        ?>
                    </div>
                    <?php endif;?>
                    <?php if($model->poe_doc3 != null && $model->poe_doc3 != '' && $model->poe_doc3 != ' '):?>
                    <div class="col-lg-4">
                        <?php
                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';
                        $retdoc3 = '';
                            if($model->poe){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc3;
                                $retdoc3 .= Html::a('<div class="rounded-3 p-4 puntero btnnew6 text-light title1"><span style="font-size:30px;" class="text-light mx-2">'.$image.'</span>Documento 3
                                 </div>', $filePath, $options = ['target'=>'_blank']);
                            }
                        echo $retdoc3;
                        ?>
                    </div>
                    <?php endif;?>
                </div>
                <?php endif;?>


                <!-- <?= $form->field($model, 'hora')->textInput() ?> -->

                <!-- <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]) ?> -->



                <div class="container-fluid my-3 border30 bg-customlight border-custom p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>Antecedentes Heredo Familiares
                            </label>
                        </div>
                    </div>

                    <!-- DIABETES -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Diabetes' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'diabetess')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->diabetess == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-6" id="diabetesstxt" style="display:<?php echo $show;?>;">
                            <?php
                        echo $form->field($model, 'diabetesstxt')->widget(Select2::classname(), [ 
                            'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                            'pluginOptions' => [
                        ],])->label(false); 
                        ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('diabetess');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        echo $AHF_diabetes;
                        ?>
                        </div>
                    </div>

                    <!-- HIPERTENSIÓN -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hipertensión' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'hipertension')->radioList(['SI' => 'SI', 'NO' => 'NO'], [ 'separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->hipertension == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="hipertensiontxt" style="display:<?php echo $show;?>;">
                            <?php
                        echo $form->field($model, 'hipertensiontxt')->widget(Select2::classname(), [  
                            'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        ],])->label(false); 
                        ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('hipertension');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_hipertension;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- CANCER -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cancer' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'cancer')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->cancer == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="cancertxt" style="display:<?php echo $show;?>;">
                            <?php
                        echo $form->field($model, 'cancertxt')->widget(Select2::classname(), [ 
                            'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                            'pluginOptions' => [
                        ],])->label(false); 
                        ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('cancer');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_cancer;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- NEFROPATÍAS -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Nefropatias' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'nefropatias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->nefropatias == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="nefropatiastxt" style="display:<?php echo $show;?>;">
                            <?php
                        echo $form->field($model, 'nefropatiastxt')->widget(Select2::classname(), [ 
                        'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        ],])->label(false); 
                        ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('nefropatias');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_nefropatias;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- CARDIOPATÍAS -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cardiopatias' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'cardiopatias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->cardiopatias == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="cardiopatiastxt" style="display:<?php echo $show;?>;">
                            <?php
                    echo $form->field($model, 'cardiopatiastxt')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        
                    ],])->label(false); 
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('cardiopatias');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_cardiopatias;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- ENFERMEDADES REUMATICAS -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedades Reumaticas' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'reuma')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->reuma == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="reumatxt" style="display:<?php echo $show;?>;">
                            <?php
                    echo $form->field($model, 'reumatxt')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                    ],])->label(false); 
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('reuma');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_enfreumaticas;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- HEPATICOS   -->
                    <div class="row border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hepáticos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'hepa')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->hepa == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="hepatxt" style="display:<?php echo $show;?>;">
                            <?php
                    echo $form->field($model, 'hepatxt')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                    ],])->label(false); 
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('hepa');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_hepaticos;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- TUBERCULOSIS   -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tuberculosis' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'tuber')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->tuber == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="tubertxt" style="display:<?php echo $show;?>;">
                            <?php
                    echo $form->field($model, 'tubertxt')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                    ],])->label(false); 
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('tuber');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_tuberculosis;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- PSIQUIATRICOS   -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Psiquiátricos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'psi')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->psi == 'SI'){
                        $show = 'block';
                    }?>
                        <div class="col-lg-6" id="psitxt" style="display:<?php echo $show;?>;">
                            <?php
                    echo $form->field($model, 'psitxt')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'PADRE','2'=>'MADRE','3'=>'ABUELOS','4'=>'HERMANOS'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [  
                    ],])->label(false); 
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('psi');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $AHF_psiquiatricos;
                        }
                        ?>
                        </div>
                    </div>
                </div>


                <div class="container-fluid my-3 border30 bg-light p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>Antecedentes Personales no
                                Patológicos
                            </label>
                        </div>
                    </div>

                    <!-- TABAQUISMO -->
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tabaquismo' ?></p>
                            <label class="form-check-label"></label>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'tabaquismo')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                            <label class="form-check-label"></label>
                        </div>
                        <?php  
                    $show = 'none';
                    if($model->tabaquismo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="tabaquismodata" style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'tabdesde')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'tabaqdesde', 'maxlength' => true]) ?>
                                    <label class="form-check-label"></label>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'tabfrec')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'DIARIO','2'=>'SEMANAL','3'=>'QUINCENAL','4'=>'MENSUAL','5'=>'OCASIONAL'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        
                         ],])->label();
                        ?>
                                    <label class="form-check-label"></label>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'tabcantidad')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 CIGARRO','2'=>'2 A 4 CIGARROS','3'=>'MÁS DE 5 CIGARROS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                       
                        ],])->label(); 
                        ?>
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('tabaquismo');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->tabaquismo;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('tabdesde');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->tabdesde;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('tabfrec');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_tabaquismofrec;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('tabcantidad');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_tabaquismocant;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ALCOHOLISMO -->
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Alcoholismo' ?></p>
                            <label class="form-check-label"></label>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'alcoholismo')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                            <label class="form-check-label"></label>
                        </div>
                        <?php  
                    $show = 'none';
                    if($model->alcoholismo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="alcoholismodata" style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'alcodesde')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'alcodesde', 'maxlength' => true]) ?>
                                    <label class="form-check-label"></label>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'alcofrec')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'DIARIO','2'=>'SEMANAL','3'=>'QUINCENAL','4'=>'MENSUAL','5'=>'OCASIONAL'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        
                        ],])->label('Frecuencia'); 
                        ?>
                                    <label class="form-check-label"></label>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'alcocantidad')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 COPA','2'=>'2 - 5 COPAS','3'=>'MÁS DE 5 COPAS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                       
                        ],])->label('Cantidad'); 
                        ?>
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('alcoholismo');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->alcoholismo;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('alcodesde');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->tabdesde;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('alcofrec');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_alcoholismofrec;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('alcocantidad');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_alcoholismocant;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- COCINA CON LEÑA -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cocina con leña' ?></p>
                            <label class="form-check-label"></label>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'cocina')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                            <label class="form-check-label"></label>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->cocina == 'SI'){
                        $show = 'none';
                    }
                    ?>
                        <div class="col-lg-3" id="cocinadata" style="display:<?php echo $show;?>;">
                            <?= $form->field($model, 'cocinadesde')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'cocinadesde', 'maxlength' => true]) ?>
                            <label class="form-check-label"></label>
                        </div>
                        <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cocina');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                            if($hc_anterior){
                                echo $hc_anterior->cocina;
                            }
                        ?>
                        </div>
                    </div>

                    <!-- USO DE AUDIFONOS -->
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Audifonos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'audifonos')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->audifonos == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="audifonosdata" style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'audiodesde')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'DIARIO','2'=>'SEMANAL','3'=>'QUINCENAL','4'=>'MENSUAL','5'=>'OCASIONAL'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        ],]); 
                        ?>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'audiocuando')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'30 Minutos','2'=>'1 Hora','3'=>'2 Horas','4'=>'3 Horas','5'=>'4 Horas','6'=>'5 Horas','7'=>'6 Horas','8'=>'7 Horas','9'=>'8 Horas','10'=>'Más de 8 Horas'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        ],]); 
                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('audifonos');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->audifonos;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('audiodesde');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_audifonosfrec;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('audiocuando');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_audifonoscant;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DROGADICCION -->
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Drogadicción' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'droga')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->droga == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="drogadata" style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <?php
                                echo $form->field($model, 'drogatxt')->widget(Select2::classname(), [ 
                                'data' => ['1'=>'Anfetamínas','2'=>'Meta-anfetamínas','3'=>'Marihuana','4'=>'Opiáceos','5'=>'Benzodiacepinas','6'=>'Barbitúricos','7'=>'Cocaina','8'=>'Morfina','9'=>'Fenciclidina'],
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                                'pluginOptions' => [
                       
                                ],])->label(); 
                                ?>
                                </div>
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'duracion_droga')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'duracion_droga', 'maxlength' => true]) ?>
                                </div>
                                <?php
                            $ultimoconsumo = ['1'=>'Hoy','2'=>'Menor a una semana','3'=>'Menor a un mes','4'=>'Menor a 6 meses','5'=>'Mayor a 6 meses']
                            ?>
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'fecha_droga')->widget(Select2::classname(), [ 
                            'data' => $ultimoconsumo,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                            'pluginOptions' => [
                           
                            ],]); 
                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('droga');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->droga;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('drogatxt');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_drogadicciontipo;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('duracion_droga');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->duracion_droga;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('fecha_droga');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $APNP_drogadiccionfecha;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- VACUNACION -->
                    <div class="row">

                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Vacunación' ?></p>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'vacunacion_cov')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>

                        <?php 
                    $show = 'none';
                    if($model->vacunacion_cov == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="vacunaciondata" style="display:<?php echo $show;?>;">
                            <div class="row">

                                <?php echo $form->field($model, 'aux_vacunacion_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 15,
                        'theme'=>'bs',
                        'id'=>'aux_vacunacion_txt',
                        'cloneButton' => false,
                        'rowOptions' => [
                            'class' => 'border-bottom table-sm',
                            'id' => 'row{multiple_index}'
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation'      => false,
                            'enableClientValidation'    => false,
                            'validateOnChange'          => false,
                            'validateOnSubmit'          => true,
                            'validateOnBlur'            => false,
                        ],
                
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                           
                            [
                                'name'  => 'vacuna',
                                'title'  => 'Vacuna',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $array_vacunas,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-vacuna!", "-otravacuna");
                                           
                                            nuevaVacuna(nuevo_id, valor, id); 
                                        }'
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'otravacuna',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nueva Vacuna'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'fecha',
                                'title'  => 'Fecha',
                                'type'  => kartik\date\DatePicker::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                                    'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                                    'pluginOptions'=>[
                                        'placeholder' => 'YYYY-MM-DD',
                                        'onchange'=>'', 
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:30%;'
                                ],         
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>

                                <div class="col-lg-4" style="display:none;">
                                    <?php
                    echo $form->field($model, 'nombre_vacunacion')->widget(Select2::classname(), [ 
                    'data' => ['1'=>'Pfizer/BioNTech','2'=>'Moderna','3'=>'AstraZeneca','4'=>'Janssen','5'=>'Sinopharm','6'=>'Sinovac','7'=>'Bharat','8'=>'CanSino','9'=>'Valneva','10'=>'Novavax','11'=>'Sputnik V','12'=>'Otra'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                       
                    ],])->label(); 
                    ?>
                                </div>
                                <div class="col-lg-4" style="display:none;">
                                    <?= $form->field($model, 'dosis_vacunacion')->dropDownList(['1' => 'PRIMERA', '2' => 'SEGUNDA', '3' => 'TERCERA', '4'=>'UNICA', '5'=>'REFUERZO'], ['prompt' => 'Seleccione--', 'id' => 'dosis_vacunacion'], ['style' => 'width:100px']) ?>
                                </div>
                                <div class="col-lg-4" style="display:none;">
                                    <?= $form->field($model, 'fecha_vacunacion')->widget(DatePicker::classname(), [
                     'id' => 'fecha_vacunacion',
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                         'id' => 'fecha_vacunacion',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('vacunacion_cov');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->vacunacion_cov;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Vacuna</th>
                                            <th class="control-label font500" width="40%">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dVacunacion as $key=>$data){
                                            $retvacuna = ''; 
                                            
                                            if($data->vacuna){
                                                $retvacuna = $data->vacuna->vacuna;
                                            }
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$retvacuna.'</td>
                                                <td class="t5 color3" width="40%">'.$data->fecha.'</td>
                                                </tr>';
                                            }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- MANO PREDOMINANTE -->
                    <div class="row my-3 border-bottom py-2">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Mano Predominante' ?></p>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'mano')->dropDownList(['1' => 'Izquierda', '2' => 'Derecha', '3' => 'Ambidiestro'], ['prompt' => 'Seleccione--'], ['style' => 'width:100px'])->label(false)
                    ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('mano');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $APNP_mano;
                        }
                        ?>
                        </div>
                    </div>

                    <!-- RECIENTEMENTE COVID -->
                    <div class="row">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Recientemente Covid' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'covidreciente')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->covidreciente == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id="covidrecientedata" style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <?php
                        echo $form->field($model, 'covidreciente_fecha')->widget(\yii\widgets\MaskedInput::class, ['mask' => '9999-99-99', 'options' => [ 'placeholder'=>'yyyy-mm-dd', 'onchange'=>'']])->label($model->getAttributeLabel('covidreciente_fecha'));
                    /* echo $form->field($model, 'covidreciente_fecha')->widget(DatePicker::classname(), [
                        'id' => 'fecha_vacunacion',
                        'options' => [
                            'placeholder' => 'YYYY-MM-DD',
                            'onchange'=>'',
                            'id' => 'fecha_vacunacion',
                           ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                        'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                            ]]);  */
                    ?>
                                </div>
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'covidreciente_secuelas')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                                </div>
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'covidreciente_vacunacion')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('covidreciente');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->covidreciente;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('covidreciente_fecha');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->covidreciente_fecha;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('covidreciente_secuelas');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->covidreciente_secuelas;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('covidreciente_vacunacion');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->covidreciente_vacunacion;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container-fluid my-3 border30 bg-light p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Antecedentes Personales Patológicos
                            </label>
                        </div>
                    </div>

                    <!-- ALERGIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Alergias' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'alergias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->alergias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='alergiasdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_alergiastxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'alergiastxt',
                        'cloneButton' => false,
                        'rowOptions' => [
                            'class' => 'border-bottom table-sm',
                            'id' => 'row{multiple_index}'
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation'      => false,
                            'enableClientValidation'    => false,
                            'validateOnChange'          => false,
                            'validateOnSubmit'          => true,
                            'validateOnBlur'            => false,
                        ],
                
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('alergias');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->alergias;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dAlergias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- ASMA -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Asma' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'asma')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->asma == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-8" id='asmadata' style="display:<?php echo $show;?>;">
                            <div class="row">
                                <div class="col-lg-4 px-0">
                                    <?= $form->field($model, 'asmatxt')->textInput(['onkeyup' => 'converToMayus(this);', 'id' => 'asma', 'maxlength' => true])->label('Diagnóstico') ?>
                                </div>
                                <div class="col-lg-4 px-0">
                                    <?= $form->field($model, 'asma_anio')->textInput(['type'=>'number','onkeyup' => 'converToMayus(this);', 'id' => 'asmaanio', 'maxlength' => true])->label('Año') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('asma');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->asma;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('asmatxt');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->asmatxt;
                                }
                                ?>
                                </div>
                                <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('asma_anio');
                            ?>
                                <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                    data-bs-original-title="<?=$tituloprevio?>">
                                    <?php
                                if($hc_anterior){
                                    echo $hc_anterior->asma_anio;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- CARDIOPATIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cardiopatías' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'cardio')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->cardio == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='cardiodata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_cardiotxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'cardiotxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --', 'onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-lightalign-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('cardio');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->cardio;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dCardiopatias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- CIRUGIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Cirugías' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'cirugias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->cirugias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='cirugiasdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_cirugiastxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'cirugiastxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('cirugias');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->cirugias;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dCirugias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- CONVULSIONES -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Convulsiones' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'convulsiones')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->convulsiones == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='convulsionesdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_convulsionestxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'convulsionestxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>

                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('convulsiones');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->convulsiones;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dConvulsiones as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- DIABETES -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Diabetes' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'diabetes')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->diabetes == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='diabetesdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_diabetestxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'diabetestxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('diabetes');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->diabetes;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dDiabetes as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- HIPERTENSION -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hipertensión' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'hiper')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->hiper == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='hiperdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_hipertxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'hipertxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('hiper');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->hiper;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dHipertension as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- LUMBALGIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Lumbalgias' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'lumbalgias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->lumbalgias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='lumbalgiasdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_lumbalgiastxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'lumbalgiastxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('lumbalgias');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->lumbalgias;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dLumbalgias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- NEFROPATIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Nefropatías' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'nefro')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->nefro == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='nefrodata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_nefrotxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'nefrotxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('nefro');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->nefro;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dNefropatias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- POLIOMELITIS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Poliomelitis' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'polio')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->polio == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-3 px-0" id='poliodata' style="display:<?php echo $show;?>;">
                            <?= $form->field($model, 'poliomelitis_anio')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'poliomelitis_anio', 'maxlength' => true])->label('Año') ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('polio');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->polio;

                            if($hc_anterior->polio == 'SI'){
                                echo ' | Año: '.$hc_anterior->poliomelitis_anio;
                            }
                        }
                        ?>
                        </div>
                    </div>


                    <!-- SARAMPION -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Sarampión' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'saram')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->saram == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-3 px-0" id='saramdata' style="display:<?php echo $show;?>;">
                            <?= $form->field($model, 'saram_anio')->textInput(['type'=>'number','readonly' => 'true','onkeyup' => 'converToMayus(this);', 'id' => 'sarampion_anio', 'maxlength' => true])->label('Año') ?>
                        </div>
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('saram');
                    ?>
                        <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->saram;

                            if($hc_anterior->saram == 'SI'){
                                echo ' | Año: '.$hc_anterior->saram_anio;
                            }
                        }
                        ?>
                        </div>
                    </div>


                    <!-- PROBLEMAS PULMONARES -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enf. Pulmonares' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'pulmo')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->pulmo == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='pulmodata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_pulmotxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'pulmotxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('pulmo');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->pulmo;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dPulmonares as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- TRASTORNOS HEMATICOS -->
                    <!-- <div class="row py-3 borderbuttonb">
               <div class="col-lg-2">
               <p class="color9 font-600"><?= 'Enf. Hemáticas' ?></p>
                </div>
                <div class="col-lg-2">
            <?= $form->field($model, 'hematicos')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
            </div>
            <?php 
                $show = 'none';
                if($model->hematicos == 'SI'){
                    $show = 'block';
                }
            ?>
            <div class="col-lg-7 px-0" id='hematicosdata' style="display:<?php echo $show;?>;">
            <?php echo $form->field($model, 'aux_hematicostxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'hematicostxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'style'=>'width:70%',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'style'=>'width:30%',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                 </div>
                </div> -->

                    <!-- TRAUMATISMOS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Traumatismos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'trauma')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->trauma == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='traumadata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_traumatxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'traumatxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('trauma');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->trauma;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dTraumatismos as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- USO DE MEDICAMENTOS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Uso de Medicamentos' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'medicamentos')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->medicamentos == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='medicamentosdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_medicamentostxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'medicamentostxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'medicamento',
                                'title'  => 'Medicamento',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%;vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año de Inicio',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%;vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('medicamentos');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->medicamentos;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dMedicamentos as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                  
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- USO DE PROTESIS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Uso de prótesis' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'protesis')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->protesis == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='protesisdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_protesistxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'protesistxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'tipo',
                                'title'  => 'Tipo',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('protesis');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->protesis;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dProtesis as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- TRANSFUSIONES -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Transfusiones' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'trans')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->trans == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='transdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_transtxt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'transtxt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('trans');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->trans;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dTransfusiones as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- ENFERMEDAD OCULAR -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedad Ocular' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'enf_ocular')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->enf_ocular == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='enf_oculardata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_enf_ocular_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'enf_ocular_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('enf_ocular');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->enf_ocular;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dOcular as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- ENFERMEDAD AUDITIVA -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Enfermedad Auditiva' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'enf_auditiva')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->enf_auditiva == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='enf_auditivadata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_enf_auditiva_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'enf_auditiva_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('enf_auditiva');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->enf_auditiva;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dAuditiva as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- FRACTURA LUXACIÓN -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Fractura / Luxación' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'fractura')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->fractura == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='fracturadata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_fractura_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'fractura_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('enf_auditiva');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->enf_auditiva;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dAuditiva as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- AMPUTACIÓN -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Amputación' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'amputacion')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->amputacion == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='amputaciondata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_amputacion_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'amputacion_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('amputacion');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->amputacion;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dAmputacion as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- HERNIAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Hernias' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'hernias')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->hernias == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='herniasdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_hernias_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'hernias_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('hernias');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->hernias;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dHernias as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                   
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- ENFERMEDADES SANGUÍNEAS / INMUNOLÓGICA: ANEMIA/VIH -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1">
                                <?= 'Enfermedades Sanguíneas/inmunológica: Anemia/VIH' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'enfsanguinea')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->enfsanguinea == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='enfsanguineadata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_enfsanguinea_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'enfsanguinea_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('enfsanguinea');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->enfsanguinea;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dSanguineas as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- TUMORES/CANCER -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Tumores/Cáncer' ?></p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'tumorescancer')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->tumorescancer == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='tumorescancerdata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_tumorescancer_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'tumorescancer_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('tumorescancer');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->tumorescancer;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dTumores as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- ENFERMEDADES PSICOLÓGICAS/PSIQUIÁTRICAS -->
                    <div class="row py-3 borderbuttonb">
                        <div class="col-lg-2">
                            <p class="p-1 bgtransparent1 text-dark font14 m-1">
                                <?= 'Enfermedades Psicológicas/Psiquiátricas' ?>
                            </p>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'enfpsico')->radioList(['SI' => 'SI', 'NO' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);','required'=>'required'])->label(false) ?>
                        </div>
                        <?php 
                    $show = 'none';
                    if($model->enfpsico == 'SI'){
                        $show = 'block';
                    }
                    ?>
                        <div class="col-lg-7 px-0" id='enfpsicodata' style="display:<?php echo $show;?>;">
                            <?php echo $form->field($model, 'aux_enfpsico_txt')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        'theme'=>'bs',
                        'id'=>'enfpsico_txt',
                        'cloneButton'=>false,
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:70%; vertical-align: top;',
                                ],
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'anio',
                                'title'  => 'Año',
                                'type'  => 'textInput',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style'=>'width:30%; vertical-align: top;',
                                ],
                                'options'=>['type'=>'number','min'=>'1900','max'=>Date('Y'),'class'=>'input-etiqueta text-500','placeholder'=>'yyyy'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom py-2">
                        <?php
                    $tituloprevio = '  '.$model->getAttributeLabel('enfpsico');
                    ?>
                        <div class="col-lg-2 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <?php
                        if($hc_anterior){
                            echo $hc_anterior->enfpsico;
                        }
                        ?>
                        </div>
                        <div class="col-lg-8 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                            data-bs-original-title="<?=$tituloprevio?>">
                            <div class="row">

                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="55%">Diagnóstico</th>
                                            <th class="control-label font500" width="40%">Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->dPsicologicas as $key=>$data){
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="55%">'.$data->descripcion.'</td>
                                                <td class="t5 color3" width="40%">'.$data->anio.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <?php 
            $show = 'none';
            if($model->sexo == '2'){
                $show = 'block';
            }
            ?>

                <div class="container-fluid my-3 border30 bg-light p-4" id="antecedentesgineco"
                    style="display:<?php echo $show;?>;">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Antecedentes Gineco Obstétricos
                            </label>
                        </div>
                    </div>

                    <div class="from-group" id="gineco_obstetrico">
                        <div class="row">
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'Gestas' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'gestas')->textInput(['type'=>'number','min'=>0,'max'=>20,'maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'Partos' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'partos')->textInput(['type'=>'number','min'=>0,'max'=>20,'maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'Abortos' ?></p>
                            </div>

                            <div class="col-lg-2">
                                <?= $form->field($model, 'abortos')->textInput(['type'=>'number','min'=>0,'max'=>20,'maxlength' => true])->label(false) ?>
                            </div>

                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'Cesareas' ?></p>
                            </div>

                            <div class="col-lg-2">
                                <?= $form->field($model, 'cesareas')->textInput(['type'=>'number','min'=>0,'max'=>20,'maxlength' => true])->label(false) ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('gestas');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->gestas;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('partos');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->partos;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('abortos');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->abortos;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cesareas');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->cesareas;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row my-3">
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'Menarca'.' (Años)' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'menarca')->textInput(['type'=>'number','maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'IVSA'.' (Años)' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'ivsa')->textInput(['type'=>'number','maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'FUM' ?></p>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'fum')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label(false); ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('menarca');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->menarca;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('ivsa');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->ivsa;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('fum');
                        ?>
                            <div class="col-lg-4 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->fum;
                            }
                            ?>
                            </div>
                        </div>



                        <div class="row my-3">
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'MPF' ?></p>
                            </div>
                            <div class="col-lg-5">
                                <?php
                        $metodospf = [''=>'Seleccione','0'=>'CONDÓN FEMENINO','1'=>'CONDÓN MASCULINO','2'=>'PASTILLAS ANTICONCEPTIVAS','3'=>'PARCHES ANTICONCEPTIVOS','4'=>'IMPLANTE SUDÉRMICO','5'=>'INYECCIONES ANTICONCEPTIVAS','6'=>'PASTILLA ANTICONCEPCIÓN DE EMERGENCIA','7'=>'DISPOSITIVO INTRAUTERINO','8'=>'ANILLO VAGINAL','9'=>'MÉTODOS PERMANENTES','10'=>'SISTEMA INTRAUTERINO (SIU)','11'=>'NINGUNO'];
                        echo $form->field($model, 'mpf')->widget(Select2::classname(), [ 
                            'data' => $metodospf,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                            'pluginOptions' => [   
                            ],])->label(false); 
                        ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"> <?= 'DOC' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?php
                        echo $form->field($model, 'doc')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'NUNCA','2'=>'MENOS DE 1 AÑO','3'=>'MENOS DE 2 AÑOS','4'=>'MÁS DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        
                        ],])->label(false); 
                        ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600"><?= 'DOCMA' ?></p>
                            </div>

                            <div class="col-lg-2">
                                <?php
                        echo $form->field($model, 'docma')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'NUNCA','2'=>'MENOS DE 1 AÑO','3'=>'MENOS DE 2 AÑOS','4'=>'MÁS DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        
                        ],])->label(false); 
                        ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('mpf');
                        ?>
                            <div class="col-lg-5 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $AGO_mpf;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('doc');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $AGO_doc;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('docma');
                        ?>
                            <div class="col-lg-2 offset-lg-1 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $AGO_docma;
                            }
                            ?>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="container-fluid my-3 border30 bg-light p-4">
                    <div class="row m-0 p-0">
                        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                            <label class="">
                                <span class="mx-2"><?php echo  $iconclip;?></span>
                                Antecedentes Laborales
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-">
                            <?= $form->field($model, 'antecedentes_sino')->radioList(['1' => 'SI', '2' => 'NO'], ['separator' => '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'onClick' => 'radioLists(this.value);'])->label(false) ?>
                        </div>
                    </div>

                    <?php 
                $show = 'none';
                if($model->antecedentes_sino == 1){
                    $show = 'block';
                }
                ?>
                    <div class="from-group mt-3" id="antecedentes_laborales" style="display:<?php echo $show;?>;">
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Edad Inicio a Laborar (Años)' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'inicio_laboral')->textInput(['type'=>'number','min'=>1,'max'=>100,'maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600 text-end"><?= 'Área' ?></p>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'antlaboral_area')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(false) ?>
                            </div>
                            <div class="col-lg-1">
                                <p class="color9 font-600 text-end"><?= 'Puesto' ?></p>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'antlaboral_puesto')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(false) ?>
                            </div>
                            <div class="col-lg-2 mt-2">
                                <p class="color9 font-600"><?= 'Antigüedad en el Puesto' ?></p>
                            </div>
                            <div class="col-lg-2 mt-2">
                                <?= $form->field($model, 'antlaboral_antiguedad')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off'],
                        'pluginOptions' => [
                        
                        ],])->label(false) ?>
                            </div>
                        </div>

                        <?php
                    $exposiciones = ['1' => 'Ruido', '2' => 'Polvos/Humos/Vap', '3' => 'Mov. Repetidos', '4' => 'Temperatura', '5' => 'Químicos', '6' => 'Vibraciones', '7' => 'Biológicos', '8' => 'Iluminación', '9' => 'Levantar Peso'];
                    ?>
                        <div class="row py-3 border-bottom border-primary" id="antecedente_0">
                            <div class="col-lg-3 px-0">
                                <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Actual' ?></p>
                            </div>
                            <div class="col-lg-9"></div>
                            <div class="col-lg-6 mt-2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Actividad Actual(E.M.P)' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral0_actividad')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral0_tiempoexposicion')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off','placeholder'=>'SELECCIONE--'],
                        'pluginOptions' => [
                        
                        ],])->label(false); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral0_epp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral0_ipp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral0_desde')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Desde'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral0_hasta')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Hasta'); ?>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mt-2">
                                <div class="col-lg-12">
                                    <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                                </div>
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'laboral0_exposicion')->checkboxList($exposiciones,  ['separator' => '',
                    'itemOptions' => [
                        'class' => 'check px-3',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6 largerCheckbox','style'=>'font-weight:500;']
                        ]
                    ])->label(false) ?>
                                </div>
                            </div>

                            <div class="col-lg-12 text-end">
                                <div class="btn-group" id="add_0">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color4"
                                        onclick="addAntecedentelab('antecedente_1','0')"><i
                                            class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                        </div>

                        <?php
            $show_antecedentes1 = 'none';
            $show_antecedentes2 = 'none';
            $show_antecedentes3 = 'none';


            if($model->laboral1_actividad != null && $model->laboral1_actividad != '' && $model->laboral1_actividad != ' '){
                $show_antecedentes1 = 'block';
            }
            if($model->laboral2_actividad != null && $model->laboral2_actividad != '' && $model->laboral2_actividad != ' '){
                $show_antecedentes2 = 'block';
            }
            if($model->laboral3_actividad != null && $model->laboral3_actividad != '' && $model->laboral3_actividad != ' '){
                $show_antecedentes3 = 'block';
            }
            ?>


                        <div class="row py-3 border-bottom border-primary" id="antecedente_1"
                            style="display:<?php echo $show_antecedentes1;?>;">
                            <div class="col-lg-3 px-0">
                                <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 1' ?></p>
                            </div>
                            <div class="col-lg-9"></div>
                            <div class="col-lg-6 mt-2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Actividad Anterior 1' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral1_actividad')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral1_tiempoexposicion')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off','placeholder'=>'SELECCIONE--'],
                        'pluginOptions' => [
                        
                        ],])->label(false); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral1_epp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral1_ipp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral1_desde')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Desde'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral1_hasta')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Hasta'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <div class="col-lg-12">
                                    <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                                </div>
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'laboral1_exposicion')->checkboxList($exposiciones,  ['separator' => '',
                    'itemOptions' => [
                        'class' => 'check px-3',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6 largerCheckbox','style'=>'font-weight:500;']
                        ]
                    ])->label(false) ?>
                                </div>
                            </div>


                            <div class="col-lg-12 text-end">
                                <div class="btn-group" id="delete_1">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color11"
                                        onclick="deleteAntecedentelab('antecedente_1','1','0')"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                                <div class="btn-group" id="add_1">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color4"
                                        onclick="addAntecedentelab('antecedente_2','1')"><i
                                            class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                        </div>


                        <div class="row py-3 border-bottom border-primary" id="antecedente_2"
                            style="display:<?php echo $show_antecedentes2;?>;">
                            <div class="col-lg-3 px-0">
                                <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 2' ?></p>
                            </div>
                            <div class="col-lg-9"></div>
                            <div class="col-lg-6 mt-2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Actividad Anterior 2' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral2_actividad')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral2_tiempoexposicion')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off','placeholder'=>'SELECCIONE--'],
                        'pluginOptions' => [
                        
                        ],])->label(false); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral2_epp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral2_ipp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral2_desde')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Desde'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral2_hasta')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Hasta'); ?>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mt-2">
                                <div class="col-lg-12">
                                    <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                                </div>
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'laboral2_exposicion')->checkboxList($exposiciones,  ['separator' => '',
                    'itemOptions' => [
                        'class' => 'check px-3',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6 largerCheckbox','style'=>'font-weight:500;']
                        ]
                    ])->label(false) ?>
                                </div>
                            </div>

                            <div class="col-lg-12 text-end">
                                <div class="btn-group" id="delete_2">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color11"
                                        onclick="deleteAntecedentelab('antecedente_2','2','1')"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                                <div class="btn-group" id="add_2">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color4"
                                        onclick="addAntecedentelab('antecedente_3','2')"><i
                                            class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                        </div>


                        <div class="row py-3 border-bottom border-primary" id="antecedente_3"
                            style="display:<?php echo $show_antecedentes3;?>;">
                            <div class="col-lg-3 px-0">
                                <p class="p-1 bgtransparent1 text-dark font14 m-1"><?= 'Actividad Anterior 3' ?></p>
                            </div>
                            <div class="col-lg-9"></div>
                            <div class="col-lg-6 mt-2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Actividad Anterior 3' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral3_actividad')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Tiempo de Exposición' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral3_tiempoexposicion')->widget(Select2::classname(), [ 
                        'data' => [''=>'Seleccione','1'=>'1 Mes','2'=>'2 Meses','3'=>'3 Meses','4'=>'4 Meses','5'=>'5 Meses','6'=>'6 Meses','7'=>'7 Meses','8'=>'8 Meses','9'=>'9 Meses','10'=>'10 Meses','11'=>'11 Meses','12'=>'1 AÑO','13'=>'1 AÑO Y MEDIO','14'=>'2 AÑOS','15'=>'+ DE 2 AÑOS'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off','placeholder'=>'SELECCIONE--'],
                        'pluginOptions' => [
                        
                        ],])->label(false); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Equipo de Protección' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral3_epp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= '%IPP Accidente o Enf.Prof' ?></p>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($model, 'laboral3_ipp')->textInput(['maxlength' => true,'placeholder'=>'INGRESE--','onkeyup' => 'converToMayus(this);'])->label(false) ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="color9 font-600"><?= 'Fecha de periodo laboral' ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral3_desde')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Desde'); ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <?= $form->field($model, 'laboral3_hasta')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label('Hasta'); ?>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mt-2">
                                <div class="col-lg-12">
                                    <p class="color9 font-600 text-center"><?= 'Exposición' ?></p>
                                </div>
                                <div class="col-lg-12">
                                    <?= $form->field($model, 'laboral3_exposicion')->checkboxList($exposiciones,  ['separator' => '',
                    'itemOptions' => [
                        'class' => 'check px-3',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6 largerCheckbox','style'=>'font-weight:500;']
                        ]
                    ])->label(false) ?>
                                </div>
                            </div>

                            <div class="col-lg-12 text-end">
                                <div class="btn-group" id="delete_3">
                                    <button type="button" class="btn btn-sm text-center shadow-sm color11"
                                        onclick="deleteAntecedentelab('antecedente_3','3','2')"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="container-fluid my-3 border30 bg-light p-4">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Exploración Física
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <?= $form->field($model, 'peso')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'0.01','id' => 'peso', 'maxlength' => true, 'onchange' => 'calculoIMC(this.value);'])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'talla')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'0.01','id' => 'talla', 'maxlength' => true, 'onchange' => 'calculoIMC(this.value);'])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'imc')->textInput(['id' => 'imcHcc', 'readonly' => true])->label() ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'categoria_imc')->textInput(['id' => 'imccat', 'readonly' => true])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'perimetro_abdominal')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'0.01','id' => 'perimetro_abdominal', 'maxlength' => true, 'onchange' => ''])->label() ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('peso');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->peso;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('talla');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->talla;
                            }
                            ?>
                            </div>
                            <?php
                            $tituloprevio = '  '.$model->getAttributeLabel('imc');
                            ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->imc;
                            }
                            ?>
                            </div>
                            <?php
                                $tituloprevio = '  '.$model->getAttributeLabel('categoria_imc');
                            ?>
                            <div class="col-lg-4 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->categoria_imc;
                            }
                            ?>
                            </div>
                             <?php
                                $tituloprevio = '  '.$model->getAttributeLabel('perimetro_abdominal');
                            ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->perimetro_abdominal;
                            }
                            ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2">
                                <?= $form->field($model, 'fc')->textInput(['type'=>'number','min'=>60,'max'=>100,'maxlength' => true])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'fr')->textInput(['type'=>'number','maxlength' => true])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'temp')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'ta')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Sistólica'])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'ta_diastolica')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Diastólica'])->label() ?>
                            </div>
                            <div class="col-lg-2">
                                <?= $form->field($model, 'caries_rd')->dropDownList(['SI' => 'SI', 'NO' => 'NO'], ['prompt' => '¿Tiene Caries?'])->label() ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('fc');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->fc;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('fr');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->fr;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('temp');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->temp;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('ta');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->ta;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('ta_diastolica');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->ta_diastolica;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('caries_rd');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->caries_rd;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <?= $form->field($model, 'pso2')->textInput(['class'=>"icon-percentage form-control",'type'=>'number','min'=>1,'max'=>100,'maxlength' => true])->label() ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('pso2');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->pso2;
                            }
                            ?>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Inspección General' ?></p>
                                <?= $form->field($model, 'show_inspeccion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php $inspeccion = ['1'=>'Orientado','2'=>'Desorientado','3'=>'Hidratado','4'=>'Deshidratado','5'=>'Buena Coloración','6'=>'Palidez','7'=>'Ictericia','8'=>'Marcha Anormal','9'=>'Marcha Normal','10'=>'Sin Datos Patológicos'];?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'inspeccion')->checkboxList($inspeccion, 
                            ['separator' => '',
                            'itemOptions' => [
                            'class' => 'check px-3 show_inspeccion',
                            'style'=>' margin: 5px 15px 0 0;',
                            'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                            ]
                            ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'inspeccion_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_inspeccion','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"inspeccion_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                                $show = 'none';
                                if($model->inspeccion_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                    <div class="col-lg-12" id="txt_inspeccion_otros"
                                        style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_inspeccion_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('inspeccion');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_inspecciong;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Cabeza' ?></p>
                                <?= $form->field($model, 'show_cabeza')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $cabeza = ['1' => 'NORMOCÉFALO', '2' => 'ALOPECIA', '3' => 'CABELLO BIEN IMPLANTADO', '4' => 'SIN DATOS PATOLÓGICOS'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'cabeza')->checkboxList($cabeza,  ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_cabeza',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'cabeza_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_cabeza','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"cabeza_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->cabeza_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_cabeza_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_cabeza_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cabeza');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_cabeza;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Oidos' ?></p>
                                <?= $form->field($model, 'show_oidos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $oidos = ['1' => 'Integra', '2' => 'Simétricos', '3' => 'Sin dolor a la palpación', '4' => 'Membrana timpánica sin alteraciones', '5' => 'Malformación congénita', '6' => 'Membrana timpánica anormal', '7' => 'Adenopatía prearicular palpable', '8' => 'Sin datos patológicos'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'oidos')->checkboxList($oidos,  ['separator' => '',
                            'itemOptions' => [
                            'class' => 'check px-3 show_oidos',
                            'style'=>' margin: 5px 15px 0 0;',
                            'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                            ]
                            ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'oidos_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_oidos','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"oidos_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->oidos_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_oidos_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_oidos_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('oidos');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_oidos;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Ojos/Cara' ?></p>
                                <?= $form->field($model, 'show_ojos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $ojoscara = ['1'=>'Íntegros','2'=>'Prótesis','3'=>'Pupilas Isocóricas','4'=>'Pupilas Anisocóricas','5'=>'Fosas Permeables','6'=>'Fosas Obstruidas','7'=>'Adenomegalias Retroauriculares','8'=>'Adenomegalias Submandibulares','10'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'ojos')->checkboxList($ojoscara, ['separator' => '',
                            'itemOptions' => [
                            'class' => 'check px-3 show_ojos',
                            'style'=>' margin: 5px 15px 0 0;',
                            'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                            ]
                            ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'ojos_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_ojos','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"ojos_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                                $show = 'none';
                                if($model->ojos_otros == 1){
                                    $show = 'block';
                                }
                                ?>
                                    <div class="col-lg-12" id="txt_ojos_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_ojos_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('ojos');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_ojoscara;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col-lg-3">
                                <p class="color9 font-600"><?= 'Agudeza Visual sin Lentes' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?=
                            $form->field($model, 'sLentes')->dropDownList(['' => 'Seleccione--', '10' => '20/10',
                            '13' => '20/13',
                            '15' => '20/15',
                            '20' => '20/20',
                            '25' => '20/25',
                            '30' => '20/30',
                            '40' => '20/40',
                            '50' => '20/50',
                            '70' => '20/70',
                            '100' => '20/100'
                            ])->label(false)
                            ?>
                            </div>
                            <div class="col-lg-2">
                                <?=
                    $form->field($model, 'sLentesD')->dropDownList(['' => 'Seleccione--', '10' => '20/10',
                        '13' => '20/13',
                        '15' => '20/15',
                        '20' => '20/20',
                        '25' => '20/25',
                        '30' => '20/30',
                        '40' => '20/40',
                        '50' => '20/50',
                        '70' => '20/70',
                        '100' => '20/100'
                    ])->label(false)
                    ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('sLentes');
                        ?>
                            <div class="col-lg-2 offset-lg-3 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if($hc_anterior->sLentes != null && $hc_anterior->sLentes != '' && $hc_anterior->sLentes!= ' '){
                                    echo '20/'.$hc_anterior->sLentes;
                                }
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('sLentesD');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if($hc_anterior->sLentesD != null && $hc_anterior->sLentesD != '' && $hc_anterior->sLentesD!= ' '){
                                    echo '20/'.$hc_anterior->sLentesD;
                                }
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-3">
                                <p class="color9 font-600"><?= 'Agudeza Visual con Lentes' ?></p>
                            </div>
                            <div class="col-lg-2">
                                <?=
                            $form->field($model, 'cLentes')->dropDownList(['' => 'Seleccione--', '10' => '20/10',
                            '13' => '20/13',
                            '15' => '20/15',
                            '20' => '20/20',
                            '25' => '20/25',
                            '30' => '20/30',
                            '40' => '20/40',
                            '50' => '20/50',
                            '70' => '20/70',
                            '100' => '20/100'
                            ])->label(false)
                            ?>
                            </div>
                            <div class="col-lg-2">
                                <?=
                        $form->field($model, 'cLentesD')->dropDownList(['' => 'Seleccione--', '10' => '20/10',
                        '13' => '20/13',
                        '15' => '20/15',
                        '20' => '20/20',
                        '25' => '20/25',
                        '30' => '20/30',
                        '40' => '20/40',
                        '50' => '20/50',
                        '70' => '20/70',
                        '100' => '20/100'
                        ])->label(false)
                         ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cLentes');
                        ?>
                            <div class="col-lg-2 offset-lg-3 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if($hc_anterior->cLentes != null && $hc_anterior->cLentes != '' && $hc_anterior->cLentes!= ' '){
                                    echo '20/'.$hc_anterior->cLentes;
                                }
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cLentesD');
                        ?>
                            <div class="col-lg-2 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if($hc_anterior->cLentesD != null && $hc_anterior->cLentesD != '' && $hc_anterior->cLentesD!= ' '){
                                    echo '20/'.$hc_anterior->cLentesD;
                                }
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= '¿Requiere Lentes Graduados?' ?></p>
                            </div>
                            <div class="col-lg-1">
                                <?= $form->field($model, 'Rlentes')->radioList(['SI' => 'SI', 'NO' => 'NO'])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <p class="color9 font-600"><?= '¿Cuenta con Lentes Graduados?' ?></p>
                            </div>
                            <div class="col-lg-1">
                                <?= $form->field($model, 'Ulentes')->radioList(['SI' => 'SI', 'NO' => 'NO'])->label(false) ?>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom border-primary py-2">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('Rlentes');
                        ?>
                            <div class="col-lg-1 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->Rlentes;
                            }
                            ?>
                            </div>
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('Ulentes');
                        ?>
                            <div class="col-lg-1 offset-lg-3 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->Ulentes;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Boca/Faringe' ?></p>
                                <?= $form->field($model, 'show_boca')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $bocafaringe = ['1'=>'Normal','2'=>'Hiperemia','3'=>'Amígdalas Normales','4'=>'Amígdalas Hipertróficas','5'=>'Sin Datos Patológicos','6'=>'Exudado Purulento'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'boca')->checkboxlist($bocafaringe, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_boca',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'boca_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_boca','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"boca_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->boca_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_boca_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_boca_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('boca');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_boca;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Cuello' ?></p>
                                <?= $form->field($model, 'show_cuello')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $cuello = ['1'=>'Traquea central','2'=>'Cilíndrico','3'=>'Crecimiento Tiroideo','4'=>'Adenomegalias Retroauriculares','5'=>'Adenomegalias Submandibulares','7'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'cuello')->checkboxlist($cuello, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_cuello',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'cuello_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_cuello','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"cuello_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->cuello_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_cuello_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_cuello_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('cuello');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_cuello;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Torax' ?></p>
                                <?= $form->field($model, 'show_torax')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $torax = ['1'=>'Campos Pulmonares Ventilados','2'=>'Estertores','3'=>'Sibilancias','4'=>'Ruidos Cardiacos Rítmicos','5'=>'Arritmia','6'=>'Adenomegalias Axilares','7'=>'Normolíneo','8'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'torax')->checkboxList($torax, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_torax',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'torax_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_torax','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"torax_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->torax_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_torax_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_torax_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('torax');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_torax;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Abdomen' ?></p>
                                <?= $form->field($model, 'show_abdomen')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $abdomen = ['1'=>'Globoso','2'=>'Plano','3'=>'Blando y Depresible','4'=>'Abdomen En Madera','5'=>'Dolor A La Palpación','6'=>'Visceromegalias','7'=>'Resistencia','8'=>'Hepatomegalia','9'=>'Esplenomegalia','10'=>'Peristalsis Alterada','11'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-7">
                                <?= $form->field($model, 'abdomen')->checkboxlist($abdomen, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_abdomen',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'abdomen_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_abdomen','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"abdomen_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->abdomen_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_abdomen_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_abdomen_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('abdomen');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_abdomen;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Miembros Superiores' ?></p>
                                <?= $form->field($model, 'show_superior')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $miembrossup = ['1'=>'Íntegros','2'=>'Prótesis','3'=>'Simétricos','4'=>'Pulsos Palpables','5'=>'Alteraciones Funcionales','6'=>'Alteraciones Estructurales','7'=>'Alteraciones Vasculares','8'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'superior')->checkboxList($miembrossup, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_superior',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-4','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'miembrossup_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_superior','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"miembrossup_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->miembrossup_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_miembrossup_otros"
                                        style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_miembrossup_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('superior');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_miembrossup;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Miembros Inferiores' ?></p>
                                <?= $form->field($model, 'show_inferior')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $miembrosinf = ['1'=>'Íntegros','2'=>'Prótesis','3'=>'Simétricos','4'=>'Pulsos Palpables','5'=>'Alteraciones Funcionales','6'=>'Alteraciones Estructurales','7'=>'Alteraciones Vasculares','8'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'inferior')->checkboxList($miembrosinf, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_inferior',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-4','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'miembrosinf_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_inferior','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"miembrosinf_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->miembrosinf_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_miembrosinf_otros"
                                        style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_miembrosinf_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('inferior');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_miembrosinf;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Columna' ?></p>
                                <?= $form->field($model, 'show_columna')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $columna = ['1'=>'Integra','2'=>'Sin Limitaciones Funcionales','3'=>'Movimientos Musculoesqueléticos Limitados','4'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'columna')->checkboxlist($columna, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_columna',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'columna_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_columna','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"columna_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->columna_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_columna_otros" style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_columna_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('columna');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_columna;
                            }
                            ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-2">
                                <p class="color9 font-600"><?= 'Neurológicos' ?></p>
                                <?= $form->field($model, 'show_txtneurologicos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','style'=>'display:none;'])->label(false); ?>
                            </div>
                            <?php
                        $neurologicos = ['1'=>'Respuesta Verbal Alterada','2'=>'Respuesta Motora Alterada','3'=>'Alteraciones De La Memoria','4'=>'Desorientado','5'=>'Sin Datos Patológicos'];
                        ?>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'txtneurologicos')->checkboxList($neurologicos, ['separator' => '',
                        'itemOptions' => [
                        'class' => 'check px-3 show_txtneurologicos',
                        'style'=>' margin: 5px 15px 0 0;',
                        'labelOptions' => ['class' => 'col-lg-6','style'=>'font-weight:500;']
                        ]
                        ])->label(false) ?>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'neurologicos_otros')->checkbox(['label' => 'Otros','class' => 'check px-3 show_txtneurologicos','style'=>' margin: 5px 15px 0 0;','onchange' => 'cambiaCheck(this.value,this.id,"neurologicos_otros")'])->label(false) ?>
                                    </div>
                                    <?php
                        $show = 'none';
                        if($model->neurologicos_otros == 1){
                            $show = 'block';
                        }
                        ?>
                                    <div class="col-lg-12" id="txt_neurologicos_otros"
                                        style="display:<?php echo $show;?>;">
                                        <?= $form->field($model, 'txt_neurologicos_otros')->textArea(['rows'=>2,'onkeyup' => 'converToMayus(this);', 'maxlength' => true,'placeholder'=>'Describa Cual'])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('txtneurologicos');
                        ?>
                            <div class="col-lg-10 offset-lg-2 previo" style="display:<?=$showprevia?>;"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $EXP_neurologicos;
                            }
                            ?>
                            </div>
                        </div>
                    </div>


                    <div class="container-fluid my-3 border30 bg-light p-4">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Estudios Complementarios
                                </label>
                            </div>
                        </div>
                        <?php echo $form->field($model, 'aux_estudios')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_estudios',
                        'cloneButton' => false,
                        'rowOptions' => [
                            'class' => 'border-bottom table-sm',
                            'id' => 'row{multiple_index}'
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation'      => false,
                            'enableClientValidation'    => false,
                            'validateOnChange'          => false,
                            'validateOnSubmit'          => true,
                            'validateOnBlur'            => false,
                        ],
                
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'estudio',
                                'title'  => 'Estudio',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $estudios,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-estudio!", "-otroestudio");
                                            var categoria_id = id.replace("-estudio!", "-categoria");
                                            var showcategoria_id = id.replace("-estudio!", "-showcategoria");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoEstudio(nuevo_id, valor, categoria_id, showcategoria_id); 
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],         
                            ],
                            [
                                'name'  => 'otroestudio',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nombre Estudio'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'categoria',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_categoria'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],         
                            ],
                            [
                                'name'  => 'showcategoria',
                                'title'  => 'Categoria',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $tipos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;', 'disabled' =>true,
                                    'readonly' =>true],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            //var valor = $(this).val();
                                            //var id = $(this).attr("id")+"!";
                                            //var nuevo_id = id.replace("-categoria!", "-otracategoria");
                                            //var estudio_id = id.replace("-categoria!", "-estudio");
                                            //console.log("Valor que está cambiando: "+valor);
                                            //console.log("Id que está cambiando: "+id);
                                            //nuevoEstudio2(nuevo_id, valor,estudio_id);
                                        }'
                                    ] 
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'otracategoria',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nombre Categoria'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'evidencia',
                                'title'  => 'Evidencia',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'type'  => FileInput::classname(),
                                'options'=>['class'=>'input-etiqueta text-500 disabled',
                                'pluginOptions' => [
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    'removeClass' => 'btn btn btn-danger',
                                    'browseIcon' => '<i class="bi bi-folder"></i>',
                                    'browseLabel' =>  '',
                                    'removeIcon' => '<i class="bi bi-trash"></i>',
                                    'removeLabel' =>  ''
                                ]
                                ],
                            ],
                            [
                                'name'  => 'doc',
                                'title'  => 'Doc',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'pdf text-center',
                                    'style' =>''
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],   
                                'value'  => function ($model,$indice)
                                { 
                                    $pdf = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                    
                                    if(isset($model['doc']) && $model['doc'] != ''){
                                       
                                        $filePath = $model['doc'];
                                        $pdf = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                            
                                        return $pdf;
                                            
                                    }
                                },
                            ],
                            [
                                'name'  => 'conclusion',
                                'title'  => 'Conclusión',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['0'=>'PENDIENTE','1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'comentarios',
                                'title' => 'Comentarios Clínicos',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'Comentario Estudio'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                        <div class="row mb-3 py-3 border-bottom">
                            <?php
                        $tituloprevio = '  '.'Estudios Complementarios';
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <table class="table table-hover table-sm font10">
                                    <thead>
                                        <tr>
                                            <th class="control-label font500" width="5%">#</th>
                                            <th class="control-label font500" width="30%">Estudio</th>
                                            <th class="control-label font500" width="15%">Categoria</th>
                                            <th class="control-label font500" width="5%">Doc.</th>
                                            <th class="control-label font500" width="25%">Conclusión</th>
                                            <th class="control-label font500" width="20%">Comentarios Clínicos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if($hc_anterior){
                                        foreach($hc_anterior->testudios as $key=>$estudio){
                                            $est_categoria = '';
                                            $est_estudio = '';
                                            $est_conclusion = '';
                                            $est_comentarios = $estudio->comentario;
                
                                            if (array_key_exists($estudio->conclusion, $array_conclusion)) {
                                                $est_conclusion = $array_conclusion[$estudio->conclusion];
                                            }
                
                                            if($estudio->categoria){
                                                $est_categoria = $estudio->categoria->nombre;
                                            }
                
                                            if($estudio->estudio){
                                                $est_estudio = $estudio->estudio->nombre;
                                            }
    
                                            $pdf = '';
                                            $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                            if($estudio->evidencia != '' && $estudio->evidencia != null){
                                                $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Documentos/'.$estudio->evidencia;
                                                $pdf = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                            }
                                        
                                            echo '<tr>
                                                <td class="t5 " width="5%">'.($key+1).'</td>
                                                <td class="t5 " width="30%">'.$est_estudio.'</td>
                                                <td class="t5 " width="15%">'.$est_categoria.'</td>
                                                <td class="t5 " width="5%">'.$pdf.'</td>
                                                <td class="t5 " width="25%">'.$est_conclusion.'</td>
                                                <td class="t5 color3" width="20%">'.$est_comentarios.'</td>
                                                </tr>';
                                            }
                                    }
                                    
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="container-fluid my-3 border30 bg-light p-4">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Conclusión
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <?= $form->field($model, 'diagnostico')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                        </div>
                        <div class="col-lg-12 mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('diagnostico');
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->diagnostico;
                            }
                            ?>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <?= $form->field($model, 'comentarios')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                        </div>
                        <div class="col-lg-12 mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('comentarios');
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->comentarios;
                            }
                            ?>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <p class="p-2 bgtransparent1 text-dark font14 m-1">Puesto de Trabajo:
                                <span id="datapuesto" class="font600">
                                    <?php
                                if($model->puestodata){
                                    echo $model->puestodata->nombre;
                                }
                                ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <?= $form->field($model, 'conclusion')->widget(Select2::classname(), [
                       'data' => [
                        '1'=>'SANO Y APTO',
                        '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                        '3'=>'APTO TEMPORAL',
                        '4'=>'PENDIENTE',
                        '5'=>'NO APTO',
                        ],
                        'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                        'pluginOptions' => [
                        'allowClear' => false
                        ],
                        ]); ?>
                        </div>
                        <div class="col-lg-12 mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('conclusion');
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if (isset($hc_anterior->conclusion) && $hc_anterior->conclusion != null && $hc_anterior->conclusion != ''){
                                    echo $array_conclusionhc[$hc_anterior->conclusion];
                                }
                            }
                            ?>
                            </div>
                        </div>


                        <div class="col-lg-4 my-5">
                            <?= $form->field($model, 'vigencia')->widget(Select2::classname(), [
                        'data' => $vigencias,
                        'size' => Select2::LARGE,
                        'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                        'pluginOptions' => [
                        'allowClear' => false
                        ],
                        ]); ?>
                        </div>
                        <div class="col-lg-12 mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('vigencia');
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                if ($hc_anterior->datavigencia){
                                    echo $hc_anterior->datavigencia->vigencia;
                                }
                            }
                            ?>
                            </div>
                        </div>


                        <div class="col-lg-12 my-3">
                            <?= $form->field($model, 'recomendaciones')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                        </div>
                        <div class="col-lg-12 mb-3 py-3 border-bottom border-primary">
                            <?php
                        $tituloprevio = '  '.$model->getAttributeLabel('recomendaciones');
                        ?>
                            <div class="col-lg-12 previo" style="display:<?=$showprevia?>;" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="<?=$tituloprevio?>"
                                data-bs-original-title="<?=$tituloprevio?>">
                                <?php
                            if($hc_anterior){
                                echo $hc_anterior->recomendaciones;
                            }
                            ?>
                            </div>
                        </div>
                    </div>



                    <?php
                    $displaycam_preregistro = 'flex';
                    $display_preregistro = 'block';
                    $display_btn = 'none';
                ?>


                    <?php
                    $mostrar_firma = 'none';
                    $mostrar_pad = 'block';
                    if($model->scenario == 'update' && isset($model->firma)){
                        $mostrar_firma = 'block';
                        $mostrar_pad = 'none';
                    }
                    $url = Url::to(['firma']);
                ?>

                    <div class="container-fluid bg1 p-3 my-3 shadow datos_consentimiento" style="display:block;">
                        <div class="col-lg-4" style="display:none;">
                            <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                        </div>
                        <h5 class="mb-3 bgcolor1 text-light p-2 text-center ">
                            CONSENTIMIENTO
                        </h5>
                        <div class="row my-4">
                            <div class="col-lg-12 text-justify">
                                POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                                    class="mx-2 border-bottom-dot title2 color3 nombre_cliente"
                                    id='nombre_cliente'><?php echo $model->nombre.' '.$model->apellidos?></span>, EN
                                PLENO
                                USO DE
                                MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                                PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE
                                SE HIZO
                                DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO
                                PARA LA
                                REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS
                                ACCIONES
                                QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA
                                INTEGRIDAD DE
                                NINGUNA PERSONA.
                            </div>
                        </div>


                        <div class="row my-4">
                            <div class="col-lg-12 text-justify">
                                FINALMENTE, Y CORRESPONDIENDO AL PRINCIPIO DE CONFIDENCIALIDAD, SE ME HA EXPLICADO
                                QUE LA
                                INFORMACIÓN QUE DERIVE COMO RESULTADO, RESPECTO A EL/LOS PROCEDIMIENTO(S)
                                PRACTICADO(S),
                                SERÁ MANEJADA DE MANERA CONFIDENCIAL Y ESTRICTAMENTE PARA EL USO DE:
                            </div>
                            <div class="col-lg-6">
                                <?=$form->field($model, 'uso_consentimiento')->radioList( [1=>Yii::t('app','MI PERSONA'), 2 => Yii::t('app','ÁREA DE RECURSOS HUMANOS DE LA EMPRESA')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3 font-600','separator' => '<br>', 'itemOptions' => [
                                'class' => 'largerCheckbox'
                                ]] )->label(false);
                            ?>
                            </div>
                            <div class="col-lg-6 shadow">
                                <br>
                                <span class="border-bottom-dot title2 color11 nombre_empresa mx-2 p-2"
                                    id='nombre_empresa'>
                                    <?php
                                if($model->uso_consentimiento == 2){
                                    echo $model->nombre_empresa;
                                }
                                ?>
                                </span>
                            </div>
                        </div>
                        <div class="row  mt-5 my-4">
                            <div class="col-lg-12 text-justify">
                                EN MI PRESENCIA HAN SIDO LLENADOS TODOS LOS ESPACIOS EN BLANCO EXISTENTES EN ESTE
                                DOCUMENTO.
                                TAMBIÉN ME ENCUENTRO ENTERADO(A) QUE TENGO LA FACULTAD DE RETIRAR ESTE
                                CONSENTIMIENTO SI ASÍ
                                LO DESEO (EN EL CASO DE QUE NO SE ME HAYA EXPLICADO EL/LOS PROCEDIMIENTO(S)).
                            </div>
                            <div class="col-lg-12">
                                <?=$form->field($model, 'retirar_consentimiento')->radioList( [1=>Yii::t('app','SI'), 2 => Yii::t('app','NO')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3  font-600','separator' => '<br>', 'itemOptions' => [
                                'class' => 'largerCheckbox'
                                ]] )->label(false);
                            ?>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-12 text-justify">
                                YO <span
                                    class="border-bottom-dot title2 color3 nombre_cliente mx-2"><?php echo $model->nombre.' '.$model->apellidos;?></span>
                            </div>
                            <div class="col-lg-12 text-justify">
                                DOY MI CONSENTIMIENTO PARA LA REALIZACIÓN DEL PROCEDIMIENTO(S) ANTERIORMENTE
                                SEÑALADO(S).
                            </div>
                        </div>
                        <div class="row my-5 pt-3">
                            <div class="col-lg-12 text-justify text-darkgray font-600">

                                <?php $aviso= Html::a('Aviso de privacidad', Url::to(['trabajadores/privacy']), $options = ['target'=>'_blank','class'=>"btn boton btn-primary"]);?>
                                <?php
                            echo $form->field($model, 'acuerdo_confidencialidad')->checkBox([
                                'label' => '<span class="text-uppercase">He leído y aceptado el '.$aviso.' y comprendo que la información proporcionada se usará de acuerdo a los terminos establecidos.</span>',
                                'onChange'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")',
                                'class' => 'largerCheckbox',
                                'options' => [
                        
                                ]
                                ])->label(false);
                            ?>
                            </div>
                        </div>
                    </div>





                    <div class="container-fluid bg1 p-3 my-3">
                        <h5 class="mb-3 bgcolor1 text-light p-2">
                            IDENTIFICACIÓN
                        </h5>
                        <div class="row my-3">
                            <div class="col-lg-3">
                                <?= $form->field($model, 'tipo_identificacion')->widget(Select2::classname(), [
                    'data' => ['INE'=>'INE','PASAPORTE'=>'PASAPORTE','LICENCIA DE CONDUCIR'=>'LICENCIA DE CONDUCIR','GAFETE'=>'GAFETE','OTRO'=>'OTRO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'numero_identificacion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']);?>
                            </div>
                        </div>


                        <div class="row my-3 mt-4 datos_consentimientocamara"
                            style="display:<?php echo $displaycam_preregistro;?>;">
                            <div class="col-lg-6">
                                <div id="live_camera"></div>
                                <div class="row mt-3">
                                    <div class="col-lg-8 d-grid text-center">
                                        <input type=button value="Capturar Evidencia"
                                            class="btn btn-dark border30 btn-lg btn-block my-2"
                                            onClick="capture_web_snapshot('hccohc')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3 datos_consentimientocamara"
                                    style="display:<?php echo $displaycam_preregistro;?>;">
                                    <div class="col-lg-12 d-grid text-center">
                                        <label class="control-label" for="canvasfoto">Evidencia</label>
                                        <div id="preview">
                                            <?php if(isset($model->foto_web)):?>

                                            <?php
                            //define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/');    
                            //dd('/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->foto_web);
                            ?>
                                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/HC/'.$model->foto_web;?>"
                                                class="img-fluid img-responsive" width="350px" height="300px" />
                                            <?php endif;?>
                                        </div>
                                        <canvas id="canvasfoto" class="canvasmedia text-center mx-auto" width="350px"
                                            height="300px" style="display:none;"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="display:none;">
                            <?= $form->field($model, 'txt_base64_foto')->textArea(['maxlength' => true,'class'=>'form-control image-tag']); ?>
                            <?= $form->field($model, 'txt_base64_ine')->textArea(['maxlength' => true]); ?>
                            <?= $form->field($model, 'txt_base64_inereverso')->textArea(['maxlength' => true]); ?>
                        </div>



                        <?php
                    $mostrar_firma = 'none';
                    $mostrar_pad = 'block';
                    if($model->scenario == 'update' && isset($model->firma)){
                        $mostrar_firma = 'block';
                        $mostrar_pad = 'none';
                    }
                    $url = Url::to(['firma']);?>

                    </div>




                    <?php
                $url = Url::to(['firma']);
                ?>

                    <div class="row my-3">
                        <div class="col-lg-3 offset-lg-3">
                            <?php
                            echo $form->field($model, 'guardar_firma')->checkBox([
                            'label' => '<span class="control-label">¿Reemplazar Firma Actual?</span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                        </div>
                        <div class="col-lg-4">
                            <?php if(isset($model->firma_ruta)):?>
                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/'.$model->firma_ruta;?>"
                                class="img-fluid img-responsive" width="150px" height="auto"
                                style='height:100px; width:auto;opacity: 0.6;' />
                            <?php endif;?>
                            <label class="control-label" for="">Firma Actual</label>
                        </div>
                        <div class="col-lg-12 text-center">
                            <!--  <?= $form->field($model, 'firma')->textInput(['maxlength' => true]) ?> -->
                            <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'800px','height'=>'300px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">Firma Trabajador </h5>']) ?>
                        </div>
                        <div class="col-lg-12 text-center">
                            <div for="" class="border-top">Firma Trabajador</div>
                        </div>
                    </div>
                    <div class="row" style="display:none;">
                        <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
                        <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
                    </div>



                    <div class="row mt-5">
                        <div class="col-lg-4 d-grid">
                            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'enviarHC']) ?>
                        </div>
                    </div>



                    <?php
                $ver_programassalud = 'none';
                if(Yii::$app->user->can('programasalud_hc')){
                    $ver_programassalud = 'block';
                }
                ?>

                    <div class="container-fluid my-3 bg-light p-4 mt-5 borderlcolor3 border-top"
                        style="display:<?php echo $ver_programassalud?>;">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Programas de Salud
                                </label>
                            </div>
                        </div>
                        <?php echo $form->field($model, 'aux_programas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_programas',
                        'cloneButton' => false,
                        'rowOptions' => [
                            'class' => 'border-bottom table-sm',
                            'id' => 'row{multiple_index}'
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation'      => false,
                            'enableClientValidation'    => false,
                            'validateOnChange'          => false,
                            'validateOnSubmit'          => true,
                            'validateOnBlur'            => false,
                        ],
                
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<button type="button" class="btn text-danger shadow"><i class="bi bi-trash"></i></button>',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'programa',
                                'title'  => 'Programa',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $programas,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],         
                            ],
                            [
                                'name'  => 'conclusion',
                                'title'  => 'Conclusión',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['1'=>'CONTROLADO','2'=>'DESCONTROLADO'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
            <div class="col-lg-12" id="anterior_hc" style="display:none;">
                <?php 
                if(isset($hc_anterior) && $hc_anterior != null && $hc_anterior != '' && $hc_anterior != ' '){
                    echo $this->render('viewclean', ['model' => $hc_anterior]);
                } else {
                    echo '<div class="container bg-light p-5 rounded-5 mt-3"><h5 class="color11 text-center"><span class="mx-1"><i class="bi bi-info-circle"></i></span>Sin Historia Clínica previa</h5></div>';
                }
            ?>
            </div>
        </div>

        <?php 
if (isset($msj)!=NULL){
    echo Html::script('Swal.fire({  icon: "error", title: "Revise el Formulario", html: "'.$msj.'"});', ['defer' => true]);
}
?>

        <?php
$script = <<< JS
$(document).ready(function(){
    console.log('ESTOY EN DOCUMENT READY');
   
});

Webcam.set({
    width: 350,
    height: 300,
    image_format: 'jpeg',
    jpeg_quality: 90
});

Webcam.attach('#live_camera');


JS;
$this->registerJS($script)
?>