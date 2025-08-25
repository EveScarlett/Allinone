<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/lab.ico" type="image/x-icon">

</head>

<?php

use yii\helpers\Html;
use Da\QrCode\QrCode;
use app\models\Firmas;
use app\models\Firmaempresa;

use app\models\Empresas;
use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

?>


<?php
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];

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


if($model->diabetess =='NO'){
    $AHF_diabetes = 'NO';
}else{
    $AHF_diabetes = '';
    $array = explode(',', $model->diabetesstxt);

    if(isset($model->diabetesstxt) && $model->diabetesstxt != null && $model->diabetesstxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_diabetes .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_diabetes .= ', ';
            }
        }
    }
}

if($model->hipertension =='NO'){
    $AHF_hipertension = 'NO';
}else{
    $AHF_hipertension = '';
    $array = explode(',', $model->hipertensiontxt);

    if(isset($model->hipertensiontxt) && $model->hipertensiontxt != null && $model->hipertensiontxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_hipertension .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_hipertension .= ', ';
            }
        }
    }
}

if($model->cancer =='NO'){
    $AHF_cancer = 'NO';
}else{
    $array = explode(',', $model->cancertxt);

    if(isset($model->cancertxt) && $model->cancertxt != null && $model->cancertxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_cancer .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_cancer .= ', ';
            }
        }
    }
}

if($model->nefropatias =='NO'){
    $AHF_nefropatias = 'NO';
}else{
    $array = explode(',', $model->nefropatiastxt);

    if(isset($model->nefropatiastxt) && $model->nefropatiastxt != null && $model->nefropatiastxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_nefropatias .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_nefropatias .= ', ';
            }
        }
    }
}

if($model->cardiopatias =='NO'){
    $AHF_cardiopatias = 'NO';
}else{
    $array = explode(',', $model->cardiopatiastxt);

    if(isset($model->cardiopatiastxt) && $model->cardiopatiastxt != null && $model->cardiopatiastxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_cardiopatias .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_cardiopatias .= ', ';
            }
        }
    }
}

if($model->reuma =='NO'){
    $AHF_enfreumaticas = 'NO';
}else{
    $array = explode(',', $model->reumatxt);

    if(isset($model->reumatxt) && $model->reumatxt != null && $model->reumatxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_enfreumaticas .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_enfreumaticas .= ', ';
            }
        }
    }
}

if($model->tuber =='NO'){
    $AHF_tuberculosis = 'NO';
}else{
    $array = explode(',', $model->tubertxt);

    if(isset($model->tubertxt) && $model->tubertxt != null && $model->tubertxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_tuberculosis .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_tuberculosis .= ', ';
            }
        }
    }
}

if($model->hepa =='NO'){
    $AHF_hepaticos = 'NO';
}else{
    $array = explode(',', $model->hepatxt);

    if(isset($model->hepatxt) && $model->hepatxt != null && $model->hepatxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_hepaticos .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_hepaticos .= ', ';
            }
        }
    }
}

if($model->psi =='NO'){
    $AHF_psiquiatricos = 'NO';
}else{
    $array = explode(',', $model->psitxt);

    if(isset($model->psitxt) && $model->psitxt != null && $model->psitxt != ''){
        foreach($array as $key=>$elemento){
            $AHF_psiquiatricos .= $array_familiares[$elemento];  
            if($key < (count($array)-1)){
                $AHF_psiquiatricos .= ', ';
            }
        }
    }
}

if($model->tabaquismo =='SI'){
    $APNP_tabaquismofrec = $array_frecuencia[$model->tabfrec];
    $APNP_tabaquismocant = $array_tabacocant[$model->tabcantidad]; 
}
if($model->alcoholismo =='SI'){
    $APNP_alcoholismofrec = $array_frecuencia[$model->alcofrec];
    $APNP_alcoholismocant = $array_alcoholcant[$model->alcocantidad];
}
if($model->audifonos =='SI'){
    $APNP_audifonosfrec = $array_frecuencia[$model->audiodesde];
    $APNP_audifonoscant = $array_audifonotime[$model->audiocuando];
}

if($model->droga =='NO'){
    $APNP_drogadicciontipo = 'NO';
}else{
    $array = explode(',', $model->drogatxt);

    if(isset($model->drogatxt) && $model->drogatxt != null && $model->drogatxt != ''){
        foreach($array as $key=>$elemento){
            $APNP_drogadicciontipo .= $array_drogas[$elemento];  
            if($key < (count($array)-1)){
                $APNP_drogadicciontipo .= ', ';
            }
        }
    }

    if(isset($model->fecha_droga) && $model->fecha_droga != null && $model->fecha_droga != ''){
        $APNP_drogadiccionfecha = $array_drogasultimo[$model->fecha_droga];
    }
}

if($model->vacunacion_cov =='SI'){
    if(isset($model->nombre_vacunacion) && $model->nombre_vacunacion != ''){
        $APNP_vacunacionnombre = $array_vacuna[$model->nombre_vacunacion];
    }
    if(isset($model->dosis_vacunacion) && $model->dosis_vacunacion != ''){
        $APNP_vacunacionnum = $array_vacunadosis[$model->dosis_vacunacion];
    }
}

if(isset($model->alimentacion) && $model->alimentacion!= '' && array_key_exists($model->alimentacion, $array_alimentacion)){
    $APHD_Alimentacion = $array_alimentacion[$model->alimentacion];
}

if(isset($model->vivienda) && $model->vivienda!= ''){
    $array = explode(',', $model->vivienda);

    if(isset($model->vivienda) && $model->vivienda != null && $model->vivienda != ''){
        foreach($array as $key=>$elemento){
            $APHD_Vivienda .= $array_vivienda[$elemento];  
            if($key < (count($array)-1)){
                $APHD_Vivienda .= ', ';
            }
        }
    }
}

if(isset($model->servicios) && $model->servicios!= ''){
    $array = explode(',', $model->servicios);

    if(isset($model->servicios) && $model->servicios != null && $model->servicios != ''){
        foreach($array as $key=>$elemento){
            $APHD_Servicios .= $array_servicios[$elemento];  
            if($key < (count($array)-1)){
                $APHD_Servicios .= ', ';
            }
        }
    }
}

if(isset($model->wc) && $model->wc!= '' && array_key_exists($model->wc, $array_frecuencia2)){
    $APHD_Banio = $array_frecuencia2[$model->wc];
}

if(isset($model->ropa) && $model->ropa!= '' && array_key_exists($model->ropa, $array_frecuencia2)){
    $APHD_Ropa = $array_frecuencia2[$model->ropa];
}

if(isset($model->bucal) && $model->bucal!= '' && array_key_exists($model->bucal, $array_frecuencia2)){
    $APHD_Bucal = $array_frecuencia2[$model->bucal];
}

if(isset($model->deporte) && $model->deporte!= '' && array_key_exists($model->deporte, $array_frecuencia2)){
    $APHD_Deporte = $array_frecuencia2[$model->deporte];
}

if(isset($model->recreativa) && $model->recreativa!= '' && array_key_exists($model->recreativa, $array_frecuencia2)){
    $APHD_Actividad = $array_frecuencia2[$model->recreativa];
}

if(isset($model->horas) && $model->horas!= '' && array_key_exists($model->horas, $array_horasuenio)){
    $APHD_Suenio = $array_horasuenio[$model->horas];
}

if(isset($model->mpf) && $model->mpf!= '' && array_key_exists($model->mpf, $array_mpf)){
    $AGO_mpf = $array_mpf[$model->mpf];
}

if(isset($model->doc) && $model->doc!= '' && array_key_exists($model->doc, $array_doc)){
    $AGO_doc = $array_doc[$model->doc];
}

if(isset($model->docma) && $model->docma!= '' && array_key_exists($model->docma, $array_doc)){
    $AGO_docma = $array_doc[$model->docma];
}

if(isset($model->mano) && $model->mano!= '' && array_key_exists($model->mano, $array_manos)){
    $APNP_mano = $array_manos[$model->mano];
}


if(isset($model->inspeccion) && $model->inspeccion != ''){
    $array = explode(',', $model->inspeccion);

    if(isset($model->inspeccion) && $model->inspeccion != null && $model->inspeccion != ''){
        foreach($array as $key=>$elemento){
            $EXP_inspecciong .= $array_inspeccion[$elemento];  
            if($key < (count($array)-1)){
                $EXP_inspecciong .= ', ';
            }
        }
    }
}
if($model->inspeccion_otros == 1){
    $EXP_inspecciong .= ', '.$model->txt_inspeccion_otros; 
}


if(isset($model->cabeza) && $model->cabeza != ''){
    $array = explode(',', $model->cabeza);

    if(isset($model->cabeza) && $model->cabeza != null && $model->cabeza != ''){
        foreach($array as $key=>$elemento){
            $EXP_cabeza .= $array_cabeza[$elemento];  
            if($key < (count($array)-1)){
                $EXP_cabeza .= ', ';
            }
        }
    }
}
if($model->cabeza_otros == 1){
    $EXP_cabeza .= ', '.$model->txt_cabeza_otros; 
}


if(isset($model->oidos) && $model->oidos != ''){
    $array = explode(',', $model->oidos);

    if(isset($model->oidos) && $model->oidos != null && $model->oidos != ''){
        foreach($array as $key=>$elemento){
            $EXP_oidos .= $array_oidos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_oidos .= ', ';
            }
        }
    }
}
if($model->oidos_otros == 1){
    $EXP_oidos .= ', '.$model->txt_oidos_otros; 
}


if(isset($model->ojos) && $model->ojos != ''){
    $array = explode(',', $model->ojos);

    if(isset($model->ojos) && $model->ojos != null && $model->ojos != ''){
        foreach($array as $key=>$elemento){
            $EXP_ojoscara .= $array_ojos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_ojoscara .= ', ';
            }
        }
    }
}
if($model->ojos_otros == 1){
    $EXP_ojoscara .= ', '.$model->txt_ojos_otros; 
}


if(isset($model->boca) && $model->boca != ''){
    $array = explode(',', $model->boca);

    if(isset($model->boca) && $model->boca != null && $model->boca != ''){
        foreach($array as $key=>$elemento){
            $EXP_boca .= $array_boca[$elemento];  
            if($key < (count($array)-1)){
                $EXP_boca .= ', ';
            }
        }
    }
}
if($model->boca_otros == 1){
    $EXP_boca .= ', '.$model->txt_boca_otros; 
}


if(isset($model->cuello) && $model->cuello != ''){
    $array = explode(',', $model->cuello);

    if(isset($model->cuello) && $model->cuello != null && $model->cuello != ''){
        foreach($array as $key=>$elemento){
            $EXP_cuello .= $array_cuello[$elemento];  
            if($key < (count($array)-1)){
                $EXP_cuello .= ', ';
            }
        }
    } 
}
if($model->cuello_otros == 1){
    $EXP_cuello .= ', '.$model->txt_cuello_otros; 
}


if(isset($model->torax) && $model->torax != ''){
    $array = explode(',', $model->torax);

    if(isset($model->torax) && $model->torax != null && $model->torax != ''){
        foreach($array as $key=>$elemento){
            $EXP_torax .= $array_torax[$elemento];  
            if($key < (count($array)-1)){
                $EXP_torax .= ', ';
            }
        }
    }
}
if($model->torax_otros == 1){
    $EXP_torax .= ', '.$model->txt_torax_otros; 
}


if(isset($model->abdomen) && $model->abdomen != ''){
    $array = explode(',', $model->abdomen);

    if(isset($model->abdomen) && $model->abdomen != null && $model->abdomen != ''){
        foreach($array as $key=>$elemento){
            $EXP_abdomen .= $array_abdomen[$elemento];  
            if($key < (count($array)-1)){
                $EXP_abdomen .= ', ';
            }
        }
    }
}
if($model->abdomen_otros == 1){
    $EXP_abdomen .= ', '.$model->txt_abdomen_otros; 
}


if(isset($model->superior) && $model->superior != ''){
    $array = explode(',', $model->superior);

    if(isset($model->superior) && $model->superior != null && $model->superior != ''){
        foreach($array as $key=>$elemento){
            $EXP_miembrossup .= $array_miembrossup[$elemento];  
            if($key < (count($array)-1)){
                $EXP_miembrossup .= ', ';
            }
        }
    } 
}
if($model->miembrossup_otros == 1){
    $EXP_miembrossup .= ', '.$model->txt_miembrossup_otros; 
}


if(isset($model->inferior) && $model->inferior != ''){
    $array = explode(',', $model->inferior);

    if(isset($model->inferior) && $model->inferior != null && $model->inferior != ''){
        foreach($array as $key=>$elemento){
            $EXP_miembrosinf .= $array_miembrosinf[$elemento];  
            if($key < (count($array)-1)){
                $EXP_miembrosinf .= ', ';
            }
        }
    }
}
if($model->miembrosinf_otros == 1){
    $EXP_miembrosinf .= ', '.$model->txt_miembrosinf_otros; 
}


if(isset($model->columna) && $model->columna != ''){
    $array = explode(',', $model->columna);

    if(isset($model->columna) && $model->columna != null && $model->columna != ''){
        foreach($array as $key=>$elemento){
            $EXP_columna .= $array_columna[$elemento];  
            if($key < (count($array)-1)){
                $EXP_columna .= ', ';
            }
        }
    }
}
if($model->columna_otros == 1){
    $EXP_columna .= ', '.$model->txt_columna_otros; 
}


if(isset($model->txtneurologicos) && $model->txtneurologicos != ''){
    $array = explode(',', $model->txtneurologicos);

    if(isset($model->txtneurologicos) && $model->txtneurologicos != null && $model->txtneurologicos != ''){
        foreach($array as $key=>$elemento){
            $EXP_neurologicos .= $array_neurologicos[$elemento];  
            if($key < (count($array)-1)){
                $EXP_neurologicos .= ', ';
            }
        }
    }
}
if($model->neurologicos_otros == '1'){
    $EXP_neurologicos .= ', '.$model->txt_neurologicos_otros; 
}


if(isset($model->antlaboral_antiguedad) && $model->antlaboral_antiguedad!= '' && array_key_exists($model->antlaboral_antiguedad, $array_antiguedad)){
    $EXPLAB_antiguedad = $array_antiguedad[$model->antlaboral_antiguedad];
}

if(isset($model->laboral0_tiempoexposicion) && $model->laboral0_tiempoexposicion!= '' && array_key_exists($model->laboral0_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_0tiempoexposicion = $array_antiguedad[$model->laboral0_tiempoexposicion];
}

if(isset($model->laboral1_tiempoexposicion) && $model->laboral1_tiempoexposicion!= '' && array_key_exists($model->laboral1_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_1tiempoexposicion = $array_antiguedad[$model->laboral1_tiempoexposicion];
}

if(isset($model->laboral2_tiempoexposicion) && $model->laboral2_tiempoexposicion!= '' && array_key_exists($model->laboral2_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_2tiempoexposicion = $array_antiguedad[$model->laboral2_tiempoexposicion];
}

if(isset($model->laboral3_tiempoexposicion) && $model->laboral3_tiempoexposicion!= '' && array_key_exists($model->laboral3_tiempoexposicion, $array_antiguedad)){
    $EXPLAB_3tiempoexposicion = $array_antiguedad[$model->laboral3_tiempoexposicion];
}

if(isset($model->laboral0_exposicion) && $model->laboral0_exposicion != ''){
    $array = explode(',', $model->laboral0_exposicion);

    if(isset($model->laboral0_exposicion) && $model->laboral0_exposicion != null && $model->laboral0_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_0exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_0exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral1_exposicion) && $model->laboral1_exposicion != ''){
    $array = explode(',', $model->laboral1_exposicion);

    if(isset($model->laboral1_exposicion) && $model->laboral1_exposicion != null && $model->laboral1_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_1exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_1exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral2_exposicion) && $model->laboral2_exposicion != ''){
    $array = explode(',', $model->laboral2_exposicion);

    if(isset($model->laboral2_exposicion) && $model->laboral2_exposicion != null && $model->laboral2_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_2exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_2exposicion .= ', ';
            }
        }
    }
}

if(isset($model->laboral3_exposicion) && $model->laboral3_exposicion != ''){
    $array = explode(',', $model->laboral3_exposicion);

    if(isset($model->laboral3_exposicion) && $model->laboral3_exposicion != null && $model->laboral3_exposicion != ''){
        foreach($array as $key=>$elemento){
            $EXPLAB_3exposicion .= $array_exposiciones[$elemento];  
            if($key < (count($array)-1)){
                $EXPLAB_3exposicion .= ', ';
            }
        }
    }
}
?>


<?php
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = false;
$show_nivel2 = false;
$show_nivel3 = false;
$show_nivel4 = false;

$nombre_empresa = '';
if(true) {
    $dataempresa = Empresas::findOne(intval($model->id_empresa));
    if($dataempresa){
        
        if($model->empresa->mostrar_nivel_pdf == 1){
            $nombre_empresa = $model->empresa->comercial;
        }
        
        $label_nivel1 = $dataempresa->label_nivel1;
        $label_nivel2 = $dataempresa->label_nivel2;
        $label_nivel3 = $dataempresa->label_nivel3;
        $label_nivel4 = $dataempresa->label_nivel4;

        if($dataempresa->cantidad_niveles >= 1){
            $show_nivel1 = true;
            
            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel1){
                if($model->nivel1->pais){
                    
                    $nombre_empresa = $model->nivel1->pais->pais;
                }
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel2 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel2){
                $nombre_empresa = $model->nivel2->nivelorganizacional2;
            }
        }
        if($dataempresa->cantidad_niveles >= 2){
            $show_nivel3 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel3){
                $nombre_empresa = $model->nivel3->nivelorganizacional3;
            }
        }
        if($dataempresa->cantidad_niveles >= 4){
            $show_nivel4 = true;

            if($model->empresa->mostrar_nivel_pdf == 2 && $model->nivel4){
                $nombre_empresa = $model->nivel4->nivelorganizacional4;
            }
        }
    }
} else {
    
    $show_nivel1 = true;
    $show_nivel2 = true;
    $show_nivel3 = true;
    $show_nivel4 = true;
}
//dd($label_nivel1,$label_nivel2,$label_nivel3,$label_nivel4,$show_nivel1,$show_nivel2,$show_nivel3,$show_nivel4);
?>

<body>
    <div class="container px-5">
        <div style="margin-bottom: 15px;">
            <h1 class="title text-center">HISTORIA CLÍNICA</h1>
        </div>
        <div>

            <table style="line-height: 1.5; font-size:12px;width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                    </tr>

                    <tr>
                        <td colspan="6" class="t5">
                            <b>Folio:</b>
                            <?=$model->folio?>
                        </td>
                        <td colspan="14" class="t5">
                            <b>Fecha:</b>
                            <?=$model->fecha?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <?php
                            
                            ?>
                            <b>Empresa:</b>
                            <?=$nombre_empresa?>
                        </td>
                        <td colspan="10" class="t5">
                            <?php
                            $area = '';
                            if($model->areadata){//aqui estoy
                                $area = $model->areadata->area;
                            }
                            ?>
                            <b>Área:</b>
                            <?=$area?>
                        </td>
                    </tr>


                    <tr>
                        <?php if($show_nivel1 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel1?>;">
                            <b><?=$label_nivel1;?>:</b>
                            <?php
                            if($model->nivel1){
                                if($model->nivel1->pais){
                                    echo $model->nivel1->pais->pais;
                                }
                            }
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($show_nivel2 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel2?>;">
                            <b><?=$label_nivel2;?>:</b>
                            <?php
                            if($model->nivel2){
                                echo $model->nivel2->nivelorganizacional2;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if($show_nivel3 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel3?>;">
                            <b><?=$label_nivel3;?>:</b>
                            <?php
                            if($model->nivel3){
                                echo $model->nivel3->nivelorganizacional3;
                            }
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($show_nivel4 == 'block'):?>
                        <td colspan="10" class="t5" style="display:<?=$show_nivel4?>;">
                            <b><?=$label_nivel4;?>:</b>
                            <?php
                            if($model->nivel4){
                                echo $model->nivel4->nivelorganizacional4;
                            }
                            ?>
                        </td>
                        <?php endif; ?>
                    </tr>


                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <?php
                            $puesto = '';
                            if($model->puestodata){//aqui estoy
                                $puesto = $model->puestodata->nombre;
                            }
                            ?>
                            <b>Puesto:</b>
                            <?=$puesto?>
                        </td>
                        <td colspan="10" class="t5">
                            <?php
                            $examen = '';
                            if($model->examen){//aqui estoy
                                $examen = $tipoexamen[$model->examen];
                            }
                            ?>
                            <b>Tipo de Examen:</b>
                            <?=$examen?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>

                    <tr>
                        <td colspan="20" class="bgbadges">
                            Ficha de Identificación
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="t5">
                            <b>Nombre:</b>
                            <?=$model->nombre?>
                        </td>
                        <td colspan="8" class="t5">
                            <b>Apellidos:</b>
                            <?=$model->apellidos?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Sexo:</b>
                            <?=$array_sexo[$model->sexo]?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <?php
                            $estado = '';
                            if($model->estado_civil){//aqui estoy
                                $estado = $estadocivil[$model->estado_civil];
                            }
                            ?>
                            <b>Estado Civil:</b>
                            <?=$estado?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Fecha de nacimiento:</b>
                            <?=$model->fecha_nacimiento?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Edad:</b>
                            <?=$model->edad?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Antecedentes Heredo-Familiares
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Diabetes:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_diabetes?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Hipertensión:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_hipertension?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Cancer:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_cancer?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Nefropatías:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_nefropatias?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Cardiopatías:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_cardiopatias?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Enf. Reumáticas:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_enfreumaticas?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Hepáticos:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_hepaticos?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Tuberculosis:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_tuberculosis?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Psiquiátricos:</b>
                        </td>
                        <td colspan="16" class="borderall">
                            <?=$AHF_psiquiatricos?>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Antecedentes Personales No Patológicos
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Tabaquismo:</b>
                        </td>
                        <td colspan="1" class="">
                            <?=$model->tabaquismo;?>
                        </td>
                        <?php if($model->tabaquismo == 'SI'):?>
                        <td colspan="5" class="t5">
                            <b>¿Desde Cuándo?:</b>
                            <?=$model->tabdesde.' años'?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Frecuencia:</b>
                            <?=$APNP_tabaquismofrec?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Cantidad:</b>
                            <?=$APNP_tabaquismocant?>
                        </td>
                        <?php else:?>
                        <td colspan="15" class="t5">
                        </td>
                        <?php endif;?>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Alcoholismo:</b>
                        </td>
                        <td colspan="1" class="">
                            <?=$model->alcoholismo?>
                        </td>
                        <?php if($model->alcoholismo == 'SI'):?>
                        <td colspan="5" class="t5">
                            <b>¿Desde Cuándo?:</b>
                            <?=$model->alcodesde.' años'?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Frecuencia:</b>
                            <?=$APNP_alcoholismofrec?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Cantidad:</b>
                            <?=$APNP_alcoholismocant?>
                        </td>
                        <?php else:?>
                        <td colspan="14" class="t5">
                        </td>
                        <?php endif;?>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Cocina con Leña:</b>
                        </td>
                        <td colspan="1" class="">
                            <?=$model->cocina?>
                        </td>
                        <!-- <?php if($model->cocina == 'SI'):?>
                        <td colspan="5" class="t5">
                            <b>¿Desde Cuándo?:</b>
                            <?=$model->cocinadesde?>
                        </td>
                        <?php else:?> -->
                        <td colspan="15" class="t5">
                        </td>
                        <!--  <?php endif;?> -->
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Uso de Audífonos:</b>
                        </td>
                        <td colspan="1" class="">
                            <?=$model->audifonos?>
                        </td>
                        <?php if($model->audifonos == 'SI'):?>
                        <td colspan="7" class="t5">
                            <b>¿Desde Cuándo?:</b>
                            <?=$APNP_audifonosfrec?>
                        </td>
                        <td colspan="7" class="t5">
                            <b>¿Cuánto Tiempo?:</b>
                            <?=$APNP_audifonoscant?>
                        </td>
                        <?php else:?>
                        <td colspan="14" class="t5">
                        </td>
                        <?php endif;?>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Drogadicción:</b>
                        </td>
                        <td colspan="1" class="">
                            <?=$model->droga?>
                        </td>
                        <?php if($model->droga == 'SI'):?>
                        <td colspan="15" class="t5">
                            <b>Tipo:</b>
                            <?=$APNP_drogadicciontipo?>
                        </td>
                        <?php else:?>
                        <td colspan="14" class="t5">
                        </td>
                        <?php endif;?>
                    </tr>
                    <?php if($model->droga == 'SI'):?>
                    <tr>
                        <td colspan="5" class="t5">
                        </td>
                        <td colspan="7" class="t5">
                            <b>Duración de uso:</b>
                            <?="".$model->duracion_droga." años"?>
                        </td>
                        <td colspan="7" class="t5">
                            <b>Fecha último Consumo:</b>
                            <?="".$APNP_drogadiccionfecha?>
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php
                            if($model->dVacunacion){
                                echo '<tr>
                                       
                                        <td colspan="15" class="t5 borderall"><b>Vacunación</b></td>
                                        <td colspan="5" class="t5 borderall"><b>Fecha</b></td>
                                        </tr>';
                                foreach($model->dVacunacion as $key=>$data){

                                    $retvacuna = '';

                                    if($data->vacuna){
                                        $retvacuna = $data->vacuna->vacuna;
                                    }
                                    echo '<tr>
                                       
                                        <td colspan="15" class="t5 borderall">'.$retvacuna.'</td>
                                        <td colspan="5" class="t5 borderall">'.$data->fecha.'</td>
                                        </tr>';
                                }
                            }  else {
                                if($APNP_vacunacionnombre != null && $APNP_vacunacionnombre != '' && $APNP_vacunacionnombre != ' '){
                                    echo '<tr><td colspan="5" class="t5">
                                    <b>Vacuna:</b>
                                    '.$APNP_vacunacionnombre.'
                                    </td>
                                    <td colspan="5" class="t5">
                                    <b>N° Dósis:</b>
                                    '.$APNP_vacunacionnum.'
                                    </td></tr>';
                                } else {
                                    echo '<tr>
                                    <td colspan="4" class="t5">
                                        <b>Vacunación:</b>
                                    </td>
                                    <td colspan="1" class="">
                                        NO
                                    </td>
                                    </tr>';
                                }
                                
                            }  
                    ?>
                  

                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">
                            Antecedentes Personales Patológicos
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                        </td>
                        <td colspan="14" class="t5 borderall text-center">
                            <b>Diagnóstico</b>
                        </td>
                        <td colspan="3" class="t5 borderall text-center">
                            <b>Año</b>
                        </td>
                    </tr>

                    <?php
                     if($model->dAlergias){
                        foreach($model->dAlergias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Alergias:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Alergias:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->asma == 'SI'){
                        echo '<tr>
                        <td colspan="4" class="t5"><b>Asma:</b></td>
                        <td colspan="14" class="t5 borderall">'.$model->asmatxt.'</td>
                        <td colspan="3" class="t5 borderall text-center">'.$model->asma_anio.'</td>
                        </tr>';
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Asma:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dCardiopatias){
                        foreach($model->dCardiopatias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Cardiopatías:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Cardiopatías:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dCirugias){
                        foreach($model->dCirugias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Cirugías:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Cirugías:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dConvulsiones){
                        foreach($model->dConvulsiones as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Convulsiones:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Convulsiones:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dDiabetes){
                        foreach($model->dDiabetes as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Diabetes:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Diabetes:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dHipertension){
                        foreach($model->dHipertension as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Hipertensión:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Hipertensión:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dLumbalgias){
                        foreach($model->dLumbalgias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Lumbalgias:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Lumbalgias:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dNefropatias){
                        foreach($model->dNefropatias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Nefropatías:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Nefropatías:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->polio == 'SI'){
                        echo '<tr>
                        <td colspan="4" class="t5"><b>Poliomelitis:</b></td>
                        <td colspan="17" class="t5 borderall">'.$model->poliomelitis_anio.'</td>
                        </tr>';
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Poliomelitis:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->saram == 'SI'){
                        echo '<tr>
                        <td colspan="4" class="t5"><b>Sarampión:</b></td>
                        <td colspan="17" class="t5 borderall">'.$model->saram_anio.'</td>
                        </tr>';
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Sarampión:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dPulmonares){
                        foreach($model->dPulmonares as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Enf. Pulmonares:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Enf. Pulmonares:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    /* echo '<tr><td style="padding-top:3px;"></td></tr>';
                    if($model->dHematicas){
                        foreach($model->dHematicas as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Enf. Hemáticas:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Enf. Hemáticas:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    } */

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dTraumatismos){
                        foreach($model->dTraumatismos as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Traumatismos:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Traumatismos:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dMedicamentos){
                        foreach($model->dMedicamentos as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Uso de Medicamentos:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Uso de Medicamentos:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dProtesis){
                        foreach($model->dProtesis as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Uso de prótesis:</b></td>
                                <td colspan="16" class="t5 borderall">'.$data->descripcion.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="16" class="t5 borderall">'.$data->descripcion.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Uso de prótesis:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dTransfusiones){
                        foreach($model->dTransfusiones as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Transfusiones</b></td>
                                <td colspan="16" class="t5 borderall">Año '.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="16" class="t5 borderall">Año '.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Transfusiones:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dOcular){
                        foreach($model->dOcular as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Enfermedad Ocular:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Enfermedad Ocular:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';

                    if($model->dAuditiva){
                        foreach($model->dAuditiva as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Enfermedad Auditiva:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Enfermedad Auditiva:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dFractura){
                        foreach($model->dFractura as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Fractura / Luxación:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Fractura / Luxación:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dAmputacion){
                        foreach($model->dAmputacion as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Amputación:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Amputación:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dHernias){
                        foreach($model->dHernias as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Hernias:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Hernias:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dSanguineas){
                        foreach($model->dSanguineas as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="10" class="t5"><b>Enfermedades Sanguíneas/inmunológica: Anemia/VIH:</b></td>
                                <td colspan="7" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="10" class="t5"></td>
                                <td colspan="7" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="10" class="t5"><b>Enfermedades Sanguíneas/inmunológica: Anemia/VIH:</b></td><td colspan="10" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dTumores){
                        foreach($model->dTumores as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="4" class="t5"><b>Tumores/Cáncer:</b></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="4" class="t5"></td>
                                <td colspan="14" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="4" class="t5"><b>Tumores/Cáncer:</b></td><td colspan="16" class="borderall">NO</td></tr>';
                    }

                    echo '<tr><td style="padding-top:3px;"></td></tr>';


                    if($model->dPsicologicas){
                        foreach($model->dPsicologicas as $key=>$data){
                            if($key==0){
                                echo '<tr>
                                <td colspan="8" class="t5"><b>Enfermedades Psicológicas/Psiquiátricas:</b></td>
                                <td colspan="9" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }else{
                                echo '<tr>
                                <td colspan="8" class="t5"></td>
                                <td colspan="9" class="t5 borderall">'.$data->descripcion.'</td>
                                <td colspan="3" class="t5 borderall text-center">'.$data->anio.'</td>
                                </tr>';
                            }
                        }
                    }else{
                        echo '<tr><td colspan="8" class="t5"><b>Enfermedades Psicológicas/Psiquiátricas:</b></td><td colspan="12" class="borderall">NO</td></tr>';
                    }
                    ?>

                    <?php if($model->sexo == '2'):?>

                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">Antecedentes Gineco Obstétricos</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="t5">
                            <b>Menarca:</b>
                            <?=$model->menarca?>
                        </td>
                        <td colspan="7" class="t5">
                            <b>IVSA:</b>
                            <?=$model->ivsa?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>FUM:</b>
                            <?=$model->fum?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="t5">
                            <b>Gestas:</b>
                            <?=$model->gestas?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Partos:</b>
                            <?=$model->partos?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Abortos:</b>
                            <?=$model->abortos?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Cesáreas:</b>
                            <?=$model->cesareas?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="t5">
                            <b>MPF:</b>
                            <?=$AGO_mpf?>
                        </td>
                        <td colspan="7" class="t5">
                            <b>DOC:</b>
                            <?=$AGO_doc?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>DOCMA:</b>
                            <?=$AGO_docma?>
                        </td>
                    </tr>
                    <?php endif;?>


                    <?php if($model->antecedentes_sino == '1'):?>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">Antecedentes Laborales</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="t5">
                            <b>Edad Inicio a Laborar (Años):</b>
                            <?=$model->inicio_laboral?>
                        </td>
                        <td colspan="7" class="t5">
                            <b>Área:</b>
                            <?=$model->antlaboral_area?>
                        </td>
                        <td colspan="6" class="t5">
                            <b>Puesto:</b>
                            <?=$model->antlaboral_puesto?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="t5">
                            <b>Antigüedad en el Puesto:</b>
                            <?=$EXPLAB_antiguedad;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5 borderall">Actividad Actual</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Actividad Actual(E.M.P):</b>
                            <?=$model->laboral0_actividad;?>
                        </td>
                        <td colspan="10" class="t5">
                            <b>Exposición:</b>
                            <?=$EXPLAB_0exposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Tiempo de Exposición:</b>
                            <?=$EXPLAB_0tiempoexposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Equipo de Protección:</b>
                            <?=$model->laboral0_epp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>%IPP Accidente o Enf.Prof:</b>
                            <?=$model->laboral0_ipp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Fecha de periodo laboral:</b>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Desde:</b>
                            <?=$model->laboral0_desde;?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Hasta:</b>
                            <?=$model->laboral0_hasta;?>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="20" class="t5 borderall">Actividad Anterior 1</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Actividad Anterior 1:</b>
                            <?=$model->laboral1_actividad;?>
                        </td>
                        <td colspan="10" class="t5">
                            <b>Exposición:</b>
                            <?=$EXPLAB_1exposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Tiempo de Exposición:</b>
                            <?=$EXPLAB_1tiempoexposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Equipo de Protección:</b>
                            <?=$model->laboral1_epp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>%IPP Accidente o Enf.Prof:</b>
                            <?=$model->laboral1_ipp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Fecha de periodo laboral:</b>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Desde:</b>
                            <?=$model->laboral1_desde;?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Hasta:</b>
                            <?=$model->laboral1_hasta;?>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="20" class="t5 borderall">Actividad Anterior 2</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Actividad Anterior 2:</b>
                            <?=$model->laboral2_actividad;?>
                        </td>
                        <td colspan="10" class="t5">
                            <b>Exposición:</b>
                            <?=$EXPLAB_2exposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Tiempo de Exposición:</b>
                            <?=$EXPLAB_2tiempoexposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Equipo de Protección:</b>
                            <?=$model->laboral2_epp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>%IPP Accidente o Enf.Prof:</b>
                            <?=$model->laboral2_ipp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Fecha de periodo laboral:</b>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Desde:</b>
                            <?=$model->laboral2_desde;?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Hasta:</b>
                            <?=$model->laboral2_hasta;?>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="20" class="t5 borderall">Actividad Anterior 3</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Actividad Anterior 3:</b>
                            <?=$model->laboral3_actividad;?>
                        </td>
                        <td colspan="10" class="t5">
                            <b>Exposición:</b>
                            <?=$EXPLAB_3exposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Tiempo de Exposición:</b>
                            <?=$EXPLAB_3tiempoexposicion;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>Equipo de Protección:</b>
                            <?=$model->laboral3_epp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="t5">
                            <b>%IPP Accidente o Enf.Prof:</b>
                            <?=$model->laboral3_ipp;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Fecha de periodo laboral:</b>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Desde:</b>
                            <?=$model->laboral3_desde;?>
                        </td>
                        <td colspan="4" class="t5">
                            <b>Hasta:</b>
                            <?=$model->laboral3_hasta;?>
                        </td>
                    </tr>
                    <?php endif;?>


                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">Exploración Física</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>Peso:</b>
                            <?=$model->peso.' kg'?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Talla:</b>
                            <?=$model->talla." m"?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>F.C.:</b>
                            <?=$model->fc?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>F.R.:</b>
                            <?=$model->fr?>
                        </td>
                        <td colspan="5" class="t5">
                            <b>Temperatura:</b>
                            <?=$model->temp." ºC"?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>T/A: S.</b>
                            <?=$model->ta?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>D.</b>
                            <?=$model->ta_diastolica?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="t5">
                            <b>IMC:</b>
                            <?=$model->imc?>
                        </td>
                        <td colspan="11" class="t5">
                            <b>E.Nutri:</b>
                            <?=$model->categoria_imc?>
                        </td>
                         <td colspan="6" class="t5">
                            <b>Perímetro Abdominal:</b>
                            <?=$model->perimetro_abdominal.'cm'?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5">
                            <b>Agudeza Visual SL:</b>
                        </td>
                        <td colspan="2" class="t5">
                            <b>OI:20/</b>
                            <?=$model->sLentes?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>OD:20/</b>
                            <?=$model->sLentesD?>
                        </td>
                        <td colspan="1" class="t5">
                        </td>
                        <td colspan="4" class="t5">
                            <b>Agudeza Visual CL:</b>
                        </td>
                        <td colspan="2" class="t5">
                            <b>OI:20/</b>
                            <?=$model->cLentes?>
                        </td>
                        <td colspan="2" class="t5">
                            <b>OD:20/</b>
                            <?=$model->cLentesD?>
                        </td>
                        <td colspan="1" class="t5">
                        </td>
                        <td colspan="2" class="t5">
                            <b>PSO2:</b>
                            <?='%'.$model->pso2?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5">
                            <b>Mano Predominante:</b>
                            <?= $APNP_mano;?>
                        </td>
                       
                        <td colspan="3" class="t5">
                            <b>R. Lentes:</b>
                            <?=$model->Rlentes?>
                        </td>
                        <td colspan="3" class="t5">
                            <b>Usa Lentes:</b>
                            <?=$model->Ulentes?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Inspección General:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_inspecciong;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Cabeza:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_cabeza;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Oidos:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_oidos;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Ojos/Cara:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_ojoscara;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Boca/Faringe:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_boca;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Cuello:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_cuello;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Torax:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_torax;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Abdomen:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_abdomen;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Miembros Superiores:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_miembrossup;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Miembros Inferiores:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_miembrosinf;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Columna:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_columna;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Neurológicos:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $EXP_neurologicos;?></td>
                    </tr>

                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Diagnóstico:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $model->diagnostico;?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Comentarios:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $model->comentarios;?></td>
                    </tr>

                    <tr>
                        <td style="padding-top:3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Recomendaciones:</b></td>
                        <td colspan="17" class="t5 borderall"><?= $model->recomendaciones;?></td>
                    </tr>



                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges"> Estudios Complementarios</td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <?php
                    if($model->testudios){
                        $array_conclusion = ['0'=>'PENDIENTE','1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'];

                        echo '<tr>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CATEGORIA</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>ESTUDIO</b></td>
                        <td colspan="4" class="t5 color3 border-bottom"><b>CONCLUSION</b></td>
                        <td colspan="8" class="t5 border-bottom"><b>COMENTARIOS</b></td>
                        </tr>';
                        foreach($model->testudios as $key=>$estudio){
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

                            echo '<tr>
                                <td colspan="4" class="text-11 border-bottom">'.$est_categoria.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_estudio.'</td>
                                <td colspan="4" class="text-11 border-bottom">'.$est_conclusion.'</td>
                                <td colspan="8" class="text-11 border-bottom">'.$est_comentarios.'</td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="20" class="text-11 border-bottom">NINGUNO</td></tr>';
                    }
                    
                    ?>

                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="bgbadges">Conclusión</td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t5"><b>Conclusión Laboral:</b></td>
                        <td colspan="17" class="t5 borderall"><?php
                        if (isset($model->conclusion) && $model->conclusion != null && $model->conclusion != ''){
                            echo $array_conclusionhc[$model->conclusion];
                        }
                        ?></td>
                    </tr>


                    <tr>
                        <td style="padding-top:15px;"></td>
                    </tr>


                    <tr>
                        <td colspan="9" class="t5 text-center">
                            <h4 class="color4">Firma Médico</h4>
                        </td>
                        <td colspan="2" class="t5"></td>
                        <td colspan="9" class="t5 text-center">
                            <h4 class="color4">Firma Médico Laboral</h4>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" class="t1 text-center">

                            <?php
                            //dd($model->uCaptura);
                            if($model->uMedico && $model->uMedico->firmaa){
                                
                                $ret = '';
                                if($model->firmado == 1 && isset($model->uMedico->firmaa->firma) && $model->uMedico->firmaa->firma != ""){
                              
                                    $filePath =  '/resources/firmas/'.$model->uMedico->firmaa->firma;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">'.$model->uMedico->firmaa->abreviado_titulo.' '.$model->uMedico->firmaa->nombre.'</h4>';
                                echo '<h4 class="text-center">'.$model->uMedico->firmaa->universidad.'</h4>';
                                echo '<h4 class="text-center">Ced. Prof. '.$model->uMedico->firmaa->cedula.'   Reg. S.S.A. '.$model->uMedico->firmaa->registro_sanitario.'</h4>';
                                echo '<h4 class="text-center">'.$model->uMedico->firmaa->titulo.'</h4>';
                            }
                            ?>
                        </td>
                        <td colspan="2" class="t5"></td>
                        <td colspan="9" class="t5 text-center">
                            <?php
                            $id_empresas = [];
                            $id_firmas = [];
                            $firmaempresa = Firmaempresa::find()->where(['id_empresa'=>$model->id_empresa])->all();
                            
                            foreach($firmaempresa as $key=>$fe){
                                array_push($id_empresas, $fe->id_empresa);
                                array_push($id_firmas, $fe->id_firma);
                            }

                            
                            $medicolaboral = Firmas::find()->where(['in','id',$id_firmas])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
                            //$medicolaboral = Firmas::find()->where(['in','id_empresa',$id_empresas])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
                            //$medicolaboral = Firmas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo'=>2])->andWhere(['<=','fecha_inicio', $model->fecha])->andWhere(['>=','fecha_fin', $model->fecha])->one();
                            
                            if(Yii::$app->user->identity->hidden_actions == 1){
                                //dd('$firmaempresa',$firmaempresa,'$id_empresas',$id_empresas,'$id_firmas',$id_firmas,'$medicolaboral',$medicolaboral);
                            }

                            if($medicolaboral){
                                
                                $ret = '';
                                if($model->firmado == 1 && isset($medicolaboral->firma) && $medicolaboral->firma != ""){
                              
                                    $filePath =  '/resources/firmas/'.$medicolaboral->firma;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:150px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">'.$medicolaboral->abreviado_titulo.' '.$medicolaboral->nombre.'</h4>';
                                echo '<h4 class="text-center">'.$medicolaboral->universidad.'</h4>';
                                echo '<h4 class="text-center">Ced. Prof. '.$medicolaboral->cedula.'   Reg. S.S.A. '.$medicolaboral->registro_sanitario.'</h4>';
                                echo '<h4 class="text-center">'.$medicolaboral->titulo.'</h4>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="t5"></td>
                        <td colspan="8" class="t5 text-center">
                            <?php
                            if($model->firma_ruta){
                                //dd($model->firma_ruta);
                                $ret = '';
                                if($model->firmado == 1 && isset($model->firma_ruta) && $model->firma_ruta != ""){
                              
                                    $filePath =  '/resources/Empresas/'.$model->id_empresa.'/HC/'.$model->id.'/'.$model->firma_ruta;
                                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '100px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:100px; width:auto;']);
                                }
                                echo $ret;
                                echo '<h4 class="text-center bordertop">FIRMA DEL PACIENTE</h4>';
                              
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px;"></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="t5 text-center">
                            <?php
                           $base = 'https://nuevoohc.test';
                           $qrCode = (new QrCode( $base.'/index.php?r=hccohc%2Fqr&id='.$model->id))
                           ->setSize(250)
                           ->setMargin(5)
                           ->useForegroundColor(0, 0, 0);

                           echo '<img src="' . $qrCode->writeDataUri() . '" class="img-fluid float-end" style="width:140px;">';
                            ?>

                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>