<?php
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
?>