<?php

use app\models\HccOhc;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\field\FieldRange;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;

/** @var yii\web\View $this */
/** @var app\models\HccOhcSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$name_empresa = '';
if(Yii::$app->user->identity->empresa){
    $name_empresa = ' | '.Yii::$app->user->identity->empresa->comercial;

    try {
        $empresa_usuario = Yii::$app->user->identity->empresa;

        if($empresa_usuario){
            if($empresa_usuario->cantidad_niveles >= 1){
                if(Yii::$app->user->identity->nivel1_all != 1) {
                    $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
                    if(count($array_niveles_1) == 1){
                        $pais_n1= Paises::findOne($array_niveles_1[0]);
                        if($pais_n1){
                            $name_empresa .= ' / '.$pais_n1->pais;
                        }
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 2){
                $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(Yii::$app->user->identity->nivel2_all != 1 && (count($niveles_2) == 1)) {
                    $nivel_n2 = NivelOrganizacional2::findOne($niveles_2[0]);
                    if($nivel_n2){
                        $name_empresa .= ' / '.$nivel_n2->nivelorganizacional2;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 3){
                $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(Yii::$app->user->identity->nivel3_all != 1 && (count($niveles_3) == 1)) {
                    $nivel_n3 = NivelOrganizacional3::findOne($niveles_3[0]);
                    if($nivel_n3){
                        $name_empresa .= ' / '.$nivel_n3->nivelorganizacional3;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 4){
                $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(Yii::$app->user->identity->nivel4_all != 1 && (count($niveles_4) == 1)) {
                    $nivel_n4 = NivelOrganizacional4::findOne($niveles_4[0]);
                    if($nivel_n4){
                        $name_empresa .= ' / '.$nivel_n4->nivelorganizacional4;
                    }
                }
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    
}
$this->title = 'Historias Clínicas'.$name_empresa;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$mostrar_nivel1 = true;
$mostrar_nivel2 = true;
$mostrar_nivel3 = true;
$mostrar_nivel4 = true;


if(Yii::$app->user->identity->nivel1_all != 1){
    $array_nivel1 = explode(',', Yii::$app->user->identity->nivel1_select);
    if(count($array_nivel1) == 1){
        $mostrar_nivel1 = false;
    }
}
if(Yii::$app->user->identity->nivel2_all != 1){
    $array_nivel2 = explode(',', Yii::$app->user->identity->nivel2_select);
    if(count($array_nivel2) == 1){
        $mostrar_nivel2 = false;
    }
}
if(Yii::$app->user->identity->nivel3_all != 1){
    $array_nivel3 = explode(',', Yii::$app->user->identity->nivel3_select);
    if(count($array_nivel3) == 1){
        $mostrar_nivel3 = false;
    }
}
if(Yii::$app->user->identity->nivel4_all != 1){
    $array_nivel4 = explode(',', Yii::$app->user->identity->nivel4_select);
    if(count($array_nivel4) == 1){
        $mostrar_nivel4 = false;
    }
}
//dd($mostrar_nivel1,$mostrar_nivel2,$mostrar_nivel3,$mostrar_nivel4);
?>

<?php
$showempresa = true;
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = false;
    }
}
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE'];
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
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = false;
$show_nivel2 = false;
$show_nivel3 = false;
$show_nivel4 = false;


if(Yii::$app->user->identity->empresa_all != 1) {
    $array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
    if(count($array_empresas) == 1){
        $dataempresa = Empresas::findOne(intval(Yii::$app->user->identity->empresas_select));
        if($dataempresa){
            $label_nivel1 = $dataempresa->label_nivel1;
            $label_nivel2 = $dataempresa->label_nivel2;
            $label_nivel3 = $dataempresa->label_nivel3;
            $label_nivel4 = $dataempresa->label_nivel4;

            if($dataempresa->cantidad_niveles >= 1){
                if($mostrar_nivel1){
                    $show_nivel1 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel2){
                    $show_nivel2 = true; 
                }
            }
            if($dataempresa->cantidad_niveles >= 2){
                if($mostrar_nivel3){
                    $show_nivel3 = true;
                }
            }
            if($dataempresa->cantidad_niveles >= 4){
                if($mostrar_nivel4){
                    $show_nivel4 = true;
                }
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

<?php
if(Yii::$app->user->identity->empresa_all != 1) {
    if($array_empresas == null && $array_empresas == '' && $array_empresas == ' '){
        $array_empresas = [];
    }
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$array_empresas])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id_pais', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
} else {
    $nivel1 = ArrayHelper::map(Paises::find()->orderBy('pais')->all(), 'id', 'pais');
}
?>
<div class="hcc-ohc-index">

    <h1 class="title1"><?= Html::encode($this->title) ?></h1>

    <?php 
       $fullExportMenu ='';
      if(Yii::$app->user->can('historias_exportar')){
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' =>'id_trabajador',
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            $ret = $model->trabajador->nombre.' '.$model->trabajador->apellidos;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'num_trabajador',
                    'label'=>'N° Trab.',
                    'value'=>function($model){
                        $ret = '';
                        if($model->num_trabajador){
                            $ret = $model->num_trabajador;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'condicion',
                    'label'=>'Condición',
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            if($model->trabajador->status == 5){
                                $ret =  'Baja';
                            }else if( $model->trabajador->status == 1){
                                $ret =  'Activo';
                            }else if( $model->trabajador->status == 3){
                                $ret =  'NI';
                            }
                        }
                        return $ret;
                      },
                   
                ],
                [
                    'attribute' =>'id_empresa',
                    'visible' => $showempresa,
                    'value'=>function($model){
                        $ret = '';
                        if($model->empresa){
                            $ret = $model->empresa->comercial;
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1){
                            $ret .= $model->nivel1->pais->pais;
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel2',
                    'format'=>'raw',
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2){
                            $ret .= $model->nivel2->nivelorganizacional2;
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel3',
                    'format'=>'raw',
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3){
                            $ret .= $model->nivel3->nivelorganizacional3;
                        }

                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_nivel4',
                    'format'=>'raw',
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4){
                            $ret .= $model->nivel4->nivelorganizacional4;
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'folio',
                ],
                [
                    'attribute' =>'fecha',
                ],
                [
                    'attribute' =>'hora',
                ],
                [
                    'attribute' =>'examen',
                    'value'=>function($model){
                        $ret = '';
                        $tipoexamen = ['1'=>'NI','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];
                        if($model->examen){
                            $ret = $tipoexamen[$model->examen];
                        }
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'area',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->areadata)){
                            $ret = $model->areadata->area;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'puesto',
                    'value'=>function($model){
                        $ret = '';
                        if(isset($model->puestodata)){
                            $ret = $model->puestodata->nombre;
                        }
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'fecha_nacimiento',
                    'label'=>'Fecha de nacimiento',
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            if(isset($model->trabajador->fecha_nacimiento) && $model->trabajador->fecha_nacimiento != ''){
                                $ret = $model->trabajador->fecha_nacimiento;
                            }
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'edad',
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            if(isset($model->trabajador->edad) && $model->trabajador->edad != ''){
                                $ret = $model->trabajador->edad;
                            }
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'edad_actual',
                    'label'=>'Edad Realtime',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->trabajador){
                            $ret = $model->trabajador->edad;
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'fecha_contratacion',
                    'label'=>'Fecha Contratación',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->trabajador){
                            $ret = $model->trabajador->fecha_contratacion;
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'antiguedad',
                    'label'=>'Antigüedad Realtime',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->trabajador){
                            $ret = $model->trabajador->antiguedad;
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'sexo',
                    'label'=>'Sexo',
                    'value'=>function($model){
                       $ret = '';
            
                       if($model->trabajador){
                           if($model->trabajador->sexo == 1){
                               $ret = '<span class="mx-1 color1"><i class="bi bi-gender-male"></i></span>M';
                            } else if($model->trabajador->sexo == 2){
                                $ret = '<span class="mx-1 color8"><i class="bi bi-gender-female"></i></span>F';
                            } else if($model->trabajador->sexo == 3){
                                $ret = '<span class="mx-1 color4"><i class="bi bi-gender-ambiguous"></i></span>Otro';
                            }
                       }
                       
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'estado_civil',
                    'value'=>function($model){
                       $ret = '';
                       if($model->estado_civil == 1){
                           $ret = 'SOLTERO';
                       } else if($model->estado_civil == 2){
                           $ret = 'CASADO';
                       } else if($model->estado_civil == 3){
                        $ret = 'VIUDO';
                       } else if($model->estado_civil == 4){
                        $ret = 'UNIÓN LIBRE';
                       }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'nivel_lectura',
                    'value'=>function($model){
                       $ret = '';
                       if($model->nivel_lectura == 1){
                           $ret = 'BUENO';
                       } else if($model->nivel_lectura == 2){
                           $ret = 'REGULAR';
                       } else if($model->nivel_lectura == 3){
                        $ret = 'MALO';
                    }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'nivel_escritura',
                    'value'=>function($model){
                       $ret = '';
                       if($model->nivel_escritura == 1){
                           $ret = 'BUENO';
                       } else if($model->nivel_escritura == 2){
                           $ret = 'REGULAR';
                       } else if($model->nivel_escritura == 3){
                        $ret = 'MALO';
                    }
                       return $ret;
                     },
                ],
                [
                    'attribute' =>'grupo'
                ],
                [
                    'attribute' =>'rh'
                ],
                [
                    'attribute' =>'numero_emergencia',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->numero_emergencia){
                            $ret = $model->numero_emergencia;
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'familiar_empresa',
                    'label'=>'Familiar en Empresa',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->familiar_empresa =='SI'){
                            $ret = 'SI';
                        } else if($model->familiar_empresa =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_familiar',
                    'label'=>'Nombre Familiar',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->familiar_empresa =='SI'){
                            if($model->familiar){
                                $ret = $model->familiar->nombre.' '.$model->familiar->apellidos;
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'id_area',
                    'label'=>'Área del Familiar',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->familiar_empresa =='SI'){
                            if($model->areafamiliar){
                                $ret = $model->areafamiliar->area;
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'diabetess',
                    'label'=>'AHF Diabetes',
                    'value'=>function($model){
                       $ret = $model->diabetess;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'txtdiabetes',
                    'label'=>'AHF Det. Diabetes',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->diabetess =='SI'){
                            $ret = '';
                            $array = explode(',', $model->diabetesstxt);
                    
                            if(isset($model->diabetesstxt) && $model->diabetesstxt != null && $model->diabetesstxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'hipertension',
                    'label'=>'AHF Hipertensión',
                    'value'=>function($model){
                       $ret = $model->hipertension;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'hipertensiontxt',
                    'label'=>'AHF Det. Hipertensión',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->hipertension =='SI'){
                            $ret = '';
                            $array = explode(',', $model->hipertensiontxt);
                    
                            if(isset($model->hipertensiontxt) && $model->hipertensiontxt != null && $model->hipertensiontxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cancer',
                    'label'=>'AHF Cáncer',
                    'value'=>function($model){
                       $ret = $model->cancer;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'cancertxt',
                    'label'=>'AHF Det. Cáncer',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->cancer =='SI'){
                            $ret = '';
                            $array = explode(',', $model->cancertxt);
                    
                            if(isset($model->cancertxt) && $model->cancertxt != null && $model->cancertxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nefropatias',
                    'label'=>'AHF Nefropatias',
                    'value'=>function($model){
                       $ret = $model->nefropatias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'nefropatiastxt',
                    'label'=>'AHF Det. Nefropatias',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->nefropatias =='SI'){
                            $ret = '';
                            $array = explode(',', $model->nefropatiastxt);
                    
                            if(isset($model->nefropatiastxt) && $model->nefropatiastxt != null && $model->nefropatiastxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cardiopatias',
                    'label'=>'AHF Cardiopatias',
                    'value'=>function($model){
                       $ret = $model->cardiopatias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'cardiopatiastxt',
                    'label'=>'AHF Det. Cardiopatias',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->cardiopatias =='SI'){
                            $ret = '';
                            $array = explode(',', $model->cardiopatiastxt);
                    
                            if(isset($model->cardiopatiastxt) && $model->cardiopatiastxt != null && $model->cardiopatiastxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'reuma',
                    'label'=>'AHF Enfermedades Reumaticas',
                    'value'=>function($model){
                       $ret = $model->reuma;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'reumatxt',
                    'label'=>'AHF Det. Enfermedades Reumaticas',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->reuma =='SI'){
                            $ret = '';
                            $array = explode(',', $model->reumatxt);
                    
                            if(isset($model->reumatxt) && $model->reumatxt != null && $model->reumatxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'hepa',
                    'label'=>'AHF Hepáticos',
                    'value'=>function($model){
                       $ret = $model->hepa;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'hepatxt',
                    'label'=>'AHF Det. Hepáticos',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->hepa =='SI'){
                            $ret = '';
                            $array = explode(',', $model->hepatxt);
                    
                            if(isset($model->hepatxt) && $model->hepatxt != null && $model->hepatxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tuber',
                    'label'=>'AHF Tuberculosis',
                    'value'=>function($model){
                       $ret = $model->tuber;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'tubertxt',
                    'label'=>'AHF Det. Tuberculosis',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->tuber =='SI'){
                            $ret = '';
                            $array = explode(',', $model->tubertxt);
                    
                            if(isset($model->tubertxt) && $model->tubertxt != null && $model->tubertxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'psi',
                    'label'=>'AHF Psiquiátricos',
                    'value'=>function($model){
                       $ret = $model->psi;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'psitxt',
                    'label'=>'AHF Det. Psiquiátricos',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->psi =='SI'){
                            $ret = '';
                            $array = explode(',', $model->psitxt);
                    
                            if(isset($model->psitxt) && $model->psitxt != null && $model->psitxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_familiares[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tabaquismo',
                    'label'=>'APNP Tabaquismo',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->tabaquismo =='SI'){
                            $ret = 'SI';
                        } else if($model->tabaquismo =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tabdesde',
                    'label'=>'APNP Edad Inicio (Años)',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->tabaquismo =='SI'){
                            if($model->tabdesde){
                                $ret = $model->tabdesde;
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tabfrec',
                    'label'=>'APNP Frecuencia',
                    'value'=>function($model) use ($array_frecuencia){
                        $ret = '';
    
                        if($model->tabaquismo =='SI'){
                            if (array_key_exists($model->tabfrec, $array_frecuencia)) {
                                $ret = $array_frecuencia[$model->tabfrec];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tabcantidad',
                    'label'=>'APNP Cantidad',
                    'value'=>function($model) use ($array_tabacocant){
                        $ret = '';
    
                        if($model->tabaquismo =='SI'){
                            if (array_key_exists($model->tabcantidad, $array_tabacocant)) {
                                $ret = $array_tabacocant[$model->tabcantidad];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'alcoholismo',
                    'label'=>'APNP Alcoholismo',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->alcoholismo =='SI'){
                            $ret = 'SI';
                        } else if($model->alcoholismo =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'alcodesde',
                    'label'=>'APNP Edad Inicio (Años)',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->alcoholismo =='SI'){
                            if($model->alcodesde){
                                $ret = $model->alcodesde;
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'alcofrec',
                    'label'=>'APNP Frecuencia',
                    'value'=>function($model) use ($array_frecuencia){
                        $ret = '';
    
                        if($model->alcoholismo =='SI'){
                            if (array_key_exists($model->alcofrec, $array_frecuencia)) {
                                $ret = $array_frecuencia[$model->alcofrec];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'alcocantidad',
                    'label'=>'APNP Cantidad',
                    'value'=>function($model) use ($array_alcoholcant){
                        $ret = '';
    
                        if($model->tabaquismo =='SI'){
                            if (array_key_exists($model->alcocantidad, $array_alcoholcant)) {
                                $ret = $array_alcoholcant[$model->alcocantidad];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cocina',
                    'label'=>'APNP Cocina con leña',
                    'value'=>function($model) use ($array_tabacocant){
                        $ret = $model->cocina;
    
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'audifonos',
                    'label'=>'APNP Audifonos',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->audifonos =='SI'){
                            $ret = 'SI';
                        } else if($model->audifonos =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'audiodesde',
                    'label'=>'APNP Frecuencia',
                    'value'=>function($model) use ($array_frecuencia){
                        $ret = '';
    
                        if($model->audifonos =='SI'){
                            if (array_key_exists($model->audiodesde, $array_frecuencia)) {
                                $ret = $array_frecuencia[$model->audiodesde];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'audiocuando',
                    'label'=>'APNP Cuanto Tiempo',
                    'value'=>function($model) use ($array_audifonotime){
                        $ret = '';
    
                        if($model->audifonos =='SI'){
                            if (array_key_exists($model->audiocuando, $array_audifonotime)) {
                                $ret = $array_audifonotime[$model->audiocuando];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'droga',
                    'label'=>'APNP Drogadicción',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->droga =='SI'){
                            $ret = 'SI';
                        } else if($model->droga =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'drogatxt',
                    'label'=>'APNP Que tipo (Droga)',
                    'value'=>function($model) use ($array_drogas){
                        $ret = '';
    
                        if($model->droga =='SI'){
                            $array = explode(',', $model->drogatxt);

                            if(isset($model->drogatxt) && $model->drogatxt != null && $model->drogatxt != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_drogas[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                            
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'duracion_droga',
                    'label'=>'APNP Duración uso de Droga (Años)',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->droga =='SI'){
                            if($model->duracion_droga){
                                $ret = $model->duracion_droga." años";
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'fecha_droga',
                    'label'=>'APNP Fecha último Consumo',
                    'value'=>function($model) use ($array_drogasultimo){
                        $ret = '';
    
                        if($model->droga =='SI'){
                            if (array_key_exists($model->fecha_droga, $array_drogasultimo)) {
                                $ret = $array_drogasultimo[$model->fecha_droga];
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'vacunacion_cov',
                    'label'=>'APNP Vacunación',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->vacunacion_cov =='SI'){
                            $ret = 'SI';
                        } else if($model->vacunacion_cov =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_vacunacion_txt',
                    'label'=>'APNP Det. Vacunación',
                    'value'=>function($model) use ($array_drogas){
                        $ret = '';
    
                        if($model->vacunacion_cov =='SI'){
                            foreach($model->dVacunacion as $key=>$data){
                                if($data->vacuna){
                                    $ret = $data->vacuna->vacuna.'['.$data->fecha.']';
                                }
                                 
                                if($key < (count($model->dVacunacion)-1)){
                                    $ret .= ', ';
                                }
                            }
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'mano',
                    'label'=>'APNP Mano Predominante',
                    'value'=>function($model) use ($array_manos){
                        $ret = '';
    
                        if (array_key_exists($model->mano, $array_manos)) {
                            $ret = $array_manos[$model->mano];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'covidreciente',
                    'label'=>'APNP Recientemente Covid',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->covidreciente =='SI'){
                            $ret = 'SI';
                        } else if($model->covidreciente =='NO'){
                            $ret = 'NO';
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'covidreciente_fecha',
                    'label'=>'APNP Fecha Contagio',
                    'value'=>function($model){
                        $ret = $model->covidreciente_fecha;
    
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'covidreciente_secuelas',
                    'label'=>'APNP Secuelas',
                    'value'=>function($model){
                        $ret = $model->covidreciente_secuelas;
    
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'covidreciente_vacunacion',
                    'label'=>'APNP Vacunación',
                    'value'=>function($model){
                        $ret = $model->covidreciente_vacunacion;
    
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'alergias',
                    'label'=>'APP Alergias',
                    'value'=>function($model){
                       $ret = $model->alergias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_alergiastxt',
                    'label'=>'APP Det. Alergias',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->alergias =='SI'){
                            if($model->dAlergias){
                                foreach($model->dAlergias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'asma',
                    'label'=>'APP Asma',
                    'value'=>function($model){
                       $ret = $model->asma;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'asmatxt',
                    'label'=>'APP Diagnóstico',
                    'value'=>function($model) {
                        $ret = $model->asmatxt;
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'asma_anio',
                    'label'=>'APP Año',
                    'value'=>function($model) {
                        $ret = $model->asma_anio;
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cardio',
                    'label'=>'APP Cardiopatías',
                    'value'=>function($model){
                       $ret = $model->cardio;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_cardiotxt',
                    'label'=>'APP Det. Cardiopatías',
                    'value'=>function($model){
                        $ret = '';

                        if($model->cardio =='SI'){
                            if($model->dCardiopatias){
                                foreach($model->dCardiopatias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cirugias',
                    'label'=>'APP Cirugías',
                    'value'=>function($model){
                       $ret = $model->cirugias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_cirugiastxt',
                    'label'=>'APP Det. Cirugías',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->cirugias =='SI'){
                            if($model->dCirugias){
                                foreach($model->dCirugias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'convulsiones',
                    'label'=>'APP Convulsiones',
                    'value'=>function($model){
                       $ret = $model->convulsiones;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_convulsionestxt',
                    'label'=>'APP Det. Convulsiones',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->convulsiones =='SI'){
                            if($model->dConvulsiones){
                                foreach($model->dConvulsiones as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'diabetes',
                    'label'=>'APP Diabetes',
                    'value'=>function($model){
                       $ret = $model->diabetes;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'txtappdiabetes',
                    'label'=>'APP Det. Diabetes',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->diabetes =='SI'){
                            if($model->dDiabetes){
                                foreach($model->dDiabetes as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'hiper',
                    'label'=>'APP Hipertensión',
                    'value'=>function($model){
                       $ret = $model->hiper;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'txtapphipertension',
                    'label'=>'APP Det. Hipertensión',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->hiper =='SI'){
                            if($model->dHipertension){
                                foreach($model->dHipertension as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'lumbalgias',
                    'label'=>'APP Lumbalgias',
                    'value'=>function($model){
                       $ret = $model->lumbalgias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_lumbalgiastxt',
                    'label'=>'APP Det. Lumbalgias',
                    'value'=>function($model) use ($array_familiares){
                        $ret = '';

                        if($model->lumbalgias =='SI'){
                            if($model->dLumbalgias){
                                foreach($model->dLumbalgias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'nefro',
                    'label'=>'APP Nefropatías',
                    'value'=>function($model){
                       $ret = $model->nefro;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_nefrotxt',
                    'label'=>'APP Det. Nefropatías',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->nefro =='SI'){
                            if($model->dNefropatias){
                                foreach($model->dNefropatias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'polio',
                    'label'=>'APP Poliomelitis',
                    'value'=>function($model){
                       $ret = $model->polio;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'poliomelitis_anio',
                    'label'=>'APP Año',
                    'value'=>function($model) {
                        $ret = '';

                        $ret = $model->poliomelitis_anio;
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'saram',
                    'label'=>'APP Sarampión',
                    'value'=>function($model){
                       $ret = $model->saram;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'saram_anio',
                    'label'=>'APP Año',
                    'value'=>function($model) {
                        $ret = '';

                        $ret = $model->saram_anio;
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'pulmo',
                    'label'=>'APP Enf. Pulmonares',
                    'value'=>function($model){
                       $ret = $model->pulmo;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_pulmotxt',
                    'label'=>'APP Det. Enf. Pulmonares',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->pulmo =='SI'){
                            if($model->dPulmonares){
                                foreach($model->dPulmonares as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'trauma',
                    'label'=>'APP Traumatismos',
                    'value'=>function($model){
                       $ret = $model->trauma;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_traumatxt',
                    'label'=>'APP Det. Traumatismos',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->trauma =='SI'){
                            if($model->dTraumatismos){
                                foreach($model->dTraumatismos as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'medicamentos',
                    'label'=>'APP Uso de Medicamentos',
                    'value'=>function($model){
                       $ret = $model->medicamentos;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_medicamentostxt',
                    'label'=>'APP Det. Año de Inicio',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->medicamentos =='SI'){
                            if($model->dMedicamentos){
                                foreach($model->dMedicamentos as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'protesis',
                    'label'=>'APP Uso de prótesis',
                    'value'=>function($model){
                       $ret = $model->protesis;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_protesistxt',
                    'label'=>'APP Det. Uso de prótesis',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->protesis =='SI'){
                            if($model->dProtesis){
                                foreach($model->dProtesis as $key=>$data){
                                    $ret .= $data->descripcion.','; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'trans',
                    'label'=>'APP Transfusiones',
                    'value'=>function($model){
                       $ret = $model->trans;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_transtxt',
                    'label'=>'APP Det. Transfusiones',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->trans =='SI'){
                            if($model->dTransfusiones){
                                foreach($model->dTransfusiones as $key=>$data){
                                    $ret .= $data->anio.','; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'enf_ocular',
                    'label'=>'APP Enfermedad Ocular',
                    'value'=>function($model){
                       $ret = $model->enf_ocular;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_enf_ocular_txt',
                    'label'=>'APP Det. Enfermedad Ocular',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->enf_ocular =='SI'){
                            if($model->dOcular){
                                foreach($model->dOcular as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'enf_auditiva',
                    'label'=>'APP Enfermedad Auditiva',
                    'value'=>function($model){
                       $ret = $model->enf_auditiva;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_enf_auditiva_txt',
                    'label'=>'APP Det. Enfermedad Auditiva',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->enf_auditiva =='SI'){
                            if($model->dAuditiva){
                                foreach($model->dAuditiva as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'fractura',
                    'label'=>'APP Fractura / Luxación',
                    'value'=>function($model){
                       $ret = $model->fractura;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_fractura_txt',
                    'label'=>'APP Det. Fractura / Luxación',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->fractura =='SI'){
                            if($model->dFractura){
                                foreach($model->dFractura as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'amputacion',
                    'label'=>'APP Amputación',
                    'value'=>function($model){
                       $ret = $model->amputacion;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_amputacion_txt',
                    'label'=>'APP Det. Amputación',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->amputacion =='SI'){
                            if($model->dAmputacion){
                                foreach($model->dAmputacion as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'hernias',
                    'label'=>'APP Hernias',
                    'value'=>function($model){
                       $ret = $model->hernias;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_hernias_txt',
                    'label'=>'APP Det. Hernias',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->hernias =='SI'){
                            if($model->dHernias){
                                foreach($model->dHernias as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'enfsanguinea',
                    'label'=>'APP Enfermedades Sanguíneas/inmunológica: Anemia/VIH',
                    'value'=>function($model){
                       $ret = $model->enfsanguinea;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_enfsanguinea_txt',
                    'label'=>'APP Det. Enfermedades Sanguíneas/inmunológica: Anemia/VIH',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->enfsanguinea =='SI'){
                            if($model->dSanguineas){
                                foreach($model->dSanguineas as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'tumorescancer',
                    'label'=>'APP Tumores/Cáncer',
                    'value'=>function($model){
                       $ret = $model->tumorescancer;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_enfsanguinea_txt',
                    'label'=>'APP Det. Tumores/Cáncer',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->tumorescancer =='SI'){
                            if($model->dTumores){
                                foreach($model->dTumores as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'enfpsico',
                    'label'=>'APP Enfermedades Psicológicas/Psiquiátricas ',
                    'value'=>function($model){
                       $ret = $model->enfpsico;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'aux_enfpsico_txt',
                    'label'=>'APP Det. Enfermedades Psicológicas/Psiquiátricas ',
                    'value'=>function($model) {
                        $ret = '';

                        if($model->enfpsico =='SI'){
                            if($model->dPsicologicas){
                                foreach($model->dPsicologicas as $key=>$data){
                                    $ret .= $data->descripcion.' ('.$data->anio.'), '; 
                                }
                            }
                        }
                       
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'gestas',
                    'label'=>'AGO Gestas',
                    'value'=>function($model){
                       $ret = $model->gestas;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'partos',
                    'label'=>'AGO Partos',
                    'value'=>function($model){
                       $ret = $model->partos;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'abortos',
                    'label'=>'AGO Abortos',
                    'value'=>function($model){
                       $ret = $model->abortos;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'cesareas',
                    'label'=>'AGO Cesareas',
                    'value'=>function($model){
                       $ret = $model->cesareas;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'menarca',
                    'label'=>'AGO Menarca (Años)',
                    'value'=>function($model){
                       $ret = $model->menarca.' años';
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'ivsa',
                    'label'=>'AGO IVSA (Años)',
                    'value'=>function($model){
                       $ret = $model->ivsa.' años';
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'fum',
                    'label'=>'AGO FUM',
                    'value'=>function($model){
                       $ret = $model->fum;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'mpf',
                    'label'=>'AGO MPF',
                    'value'=>function($model) use ($array_mpf){
                        $ret = '';
    
                        if (array_key_exists($model->mpf, $array_mpf)) {
                            $ret = $array_mpf[$model->mpf];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'doc',
                    'label'=>'AGO DOC',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        if (array_key_exists($model->doc, $array_doc)) {
                            $ret = $array_doc[$model->doc];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'docma',
                    'label'=>'AGO DOCMA',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        if (array_key_exists($model->docma, $array_doc)) {
                            $ret = $array_doc[$model->docma];
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'antecedentes_sino',
                    'label'=>'AHF Antecedentes Laborales',
                    'value'=>function($model){
                       $ret = $model->antecedentes_sino;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'peso',
                    'label'=>'EF Peso(Kg)',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->peso;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'talla',
                    'label'=>'EF Talla(Mts)',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->talla;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'imc',
                    'label'=>'EF IMC',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->imc;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'categoria_imc',
                    'label'=>'EF Estado Nutricional',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->categoria_imc;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'perimetro_abdominal',
                    'label'=>'EF Perímetro Abdominal',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->perimetro_abdominal;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'fc',
                    'label'=>'EF F.C',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->fc;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'fr',
                    'label'=>'EF F.R',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->fr;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'temp',
                    'label'=>'EF Temperatura',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->temp;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'ta',
                    'label'=>'EF T/A',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->ta;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'ta_diastolica',
                    'label'=>'EF T/A Diastólica',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->ta_diastolica;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'caries_rd',
                    'label'=>'EF Caries',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->caries_rd;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'pso2',
                    'label'=>'EF PSO2',
                    'value'=>function($model){
                        $ret = '';
    
                        $ret = $model->pso2;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_inspeccion',
                    'label'=>'EF Inspección General',
                    'value'=>function($model) use ($array_inspeccion){
                        $ret = '';
    
                        if(isset($model->inspeccion) && $model->inspeccion != ''){
                            $array = explode(',', $model->inspeccion);
                        
                            if(isset($model->inspeccion) && $model->inspeccion != null && $model->inspeccion != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_inspeccion[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->inspeccion_otros == 1){
                            $ret .= ', '.$model->txt_inspeccion_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_cabeza',
                    'label'=>'EF Cabeza',
                    'value'=>function($model) use ($array_cabeza){
                        $ret = '';
    
                        if(isset($model->cabeza) && $model->cabeza != ''){
                            $array = explode(',', $model->cabeza);
                        
                            if(isset($model->cabeza) && $model->cabeza != null && $model->cabeza != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_cabeza[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->cabeza_otros == 1){
                            $ret .= ', '.$model->txt_cabeza_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_oidos',
                    'label'=>'EF Oidos',
                    'value'=>function($model) use ($array_oidos){
                        $ret = '';
    
                        if(isset($model->oidos) && $model->oidos != ''){
                            $array = explode(',', $model->oidos);
                        
                            if(isset($model->oidos) && $model->oidos != null && $model->oidos != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_oidos[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->oidos_otros == 1){
                            $ret .= ', '.$model->txt_oidos_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_ojos',
                    'label'=>'EF Ojos/Cara',
                    'value'=>function($model) use ($array_ojos){
                        $ret = '';
    
                        if(isset($model->ojos) && $model->ojos != ''){
                            $array = explode(',', $model->ojos);
                        
                            if(isset($model->ojos) && $model->ojos != null && $model->ojos != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_ojos[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->ojos_otros == 1){
                            $ret .= ', '.$model->txt_ojos_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'sLentes',
                    'label'=>'AGO Agudeza Visual sin Lentes Izq.',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        $ret = 'OI:20/'.$model->sLentes;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'sLentesD',
                    'label'=>'AGO Agudeza Visual sin Lentes Der.',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        $ret = 'OD:20/'.$model->sLentesD;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cLentes',
                    'label'=>'AGO Agudeza Visual con Lentes Izq.',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        $ret = 'OI:20/'.$model->cLentes;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'cLentesD',
                    'label'=>'AGO Agudeza Visual con Lentes Der.',
                    'value'=>function($model) use ($array_doc){
                        $ret = '';
    
                        $ret = 'OD:20/'.$model->cLentesD;
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'Rlentes',
                    'label'=>'APP ¿Requiere Lentes Graduados?',
                    'value'=>function($model){
                       $ret = $model->Rlentes;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'Ulentes',
                    'label'=>'APP ¿Cuenta con Lentes Graduados?',
                    'value'=>function($model){
                       $ret = $model->Ulentes;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'show_boca',
                    'label'=>'EF Boca/Faringe',
                    'value'=>function($model) use ($array_boca){
                        $ret = '';
    
                        if(isset($model->boca) && $model->boca != ''){
                            $array = explode(',', $model->boca);
                        
                            if(isset($model->boca) && $model->boca != null && $model->boca != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_boca[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->boca_otros == 1){
                            $ret .= ', '.$model->txt_boca_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_cuello',
                    'label'=>'EF Cuello',
                    'value'=>function($model) use ($array_cuello){
                        $ret = '';
    
                        if(isset($model->cuello) && $model->cuello != ''){
                            $array = explode(',', $model->cuello);
                        
                            if(isset($model->cuello) && $model->cuello != null && $model->cuello != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_cuello[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            } 
                        }
                        if($model->cuello_otros == 1){
                            $ret .= ', '.$model->txt_cuello_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_torax',
                    'label'=>'EF Torax',
                    'value'=>function($model) use ($array_torax){
                        $ret = '';
    
                        if(isset($model->torax) && $model->torax != ''){
                            $array = explode(',', $model->torax);
                        
                            if(isset($model->torax) && $model->torax != null && $model->torax != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_torax[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->torax_otros == 1){
                            $ret .= ', '.$model->txt_torax_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_abdomen',
                    'label'=>'EF Abdomen',
                    'value'=>function($model) use ($array_abdomen){
                        $ret = '';
    
                        if(isset($model->abdomen) && $model->abdomen != ''){
                            $array = explode(',', $model->abdomen);
                        
                            if(isset($model->abdomen) && $model->abdomen != null && $model->abdomen != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_abdomen[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->abdomen_otros == 1){
                            $ret .= ', '.$model->txt_abdomen_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_superior',
                    'label'=>'EF Miembros Superiores',
                    'value'=>function($model) use ($array_miembrossup){
                        $ret = '';
    
                        if(isset($model->superior) && $model->superior != ''){
                            $array = explode(',', $model->superior);
                        
                            if(isset($model->superior) && $model->superior != null && $model->superior != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_miembrossup[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            } 
                        }
                        if($model->miembrossup_otros == 1){
                            $ret .= ', '.$model->txt_miembrossup_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_inferior',
                    'label'=>'EF Miembros Inferiores',
                    'value'=>function($model) use ($array_miembrosinf){
                        $ret = '';
    
                        if(isset($model->inferior) && $model->inferior != ''){
                            $array = explode(',', $model->inferior);
                        
                            if(isset($model->inferior) && $model->inferior != null && $model->inferior != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_miembrosinf[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->miembrosinf_otros == 1){
                            $ret .= ', '.$model->txt_miembrosinf_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_columna',
                    'label'=>'EF Columna',
                    'value'=>function($model) use ($array_columna){
                        $ret = '';
    
                        if(isset($model->columna) && $model->columna != ''){
                            $array = explode(',', $model->columna);
                        
                            if(isset($model->columna) && $model->columna != null && $model->columna != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_columna[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->columna_otros == 1){
                            $ret .= ', '.$model->txt_columna_otros; 
                        }
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'show_txtneurologicos',
                    'label'=>'EF Neurológicos',
                    'value'=>function($model) use ($array_neurologicos){
                        $ret = '';
    
                        if(isset($model->txtneurologicos) && $model->txtneurologicos != ''){
                            $array = explode(',', $model->txtneurologicos);
                        
                            if(isset($model->txtneurologicos) && $model->txtneurologicos != null && $model->txtneurologicos != ''){
                                foreach($array as $key=>$elemento){
                                    $ret .= $array_neurologicos[$elemento];  
                                    if($key < (count($array)-1)){
                                        $ret .= ', ';
                                    }
                                }
                            }
                        }
                        if($model->neurologicos_otros == '1'){
                            $ret .= ', '.$model->txt_neurologicos_otros; 
                        }
                        return $ret;
                    },
                ],
             
                [
                    'attribute' =>'diagnostico',
                    'value'=>function($model){
                       $ret = $model->diagnostico;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'comentarios',
                    'value'=>function($model){
                       $ret = $model->comentarios;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'conclusion',
                    'contentOptions' => ['class' => "y_centercenter y_centercenter color3",'style'=>''],
                    'value'=>function($model){
                        $ret = '';
                        $conclusiones = [
                            '1'=>'SANO Y APTO',
                            '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                            '3'=>'APTO TEMPORAL',
                            '4'=>'PENDIENTE',
                            '5'=>'NO APTO'
                        ];
    
                        if($model->conclusion){
                            $ret = $conclusiones[$model->conclusion];
                        }
                        
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'vigencia',
                    'contentOptions' => ['class' => "y_centercenter y_centercenter color3",'style'=>''],
                    'value'=>function($model){
                        $ret = '';
                        $text_vigencia = '';
                        $vigencia = '';
                        if($model->datavigencia){
                            $text_vigencia = $model->datavigencia->vigencia;
            
                            if($model->fecha_vigencia != null && $model->fecha_vigencia != '' && $model->fecha_vigencia != ' '){
                                $vigencia = 'hasta el ';
                                $vigencia .= date('d/m/Y', strtotime($model->fecha_vigencia));
                            }
                        }

                        $ret = $text_vigencia.' '.$vigencia;
                        
                        return $ret;
                      },
                ],
                [
                    'attribute' =>'recomendaciones',
                    'value'=>function($model){
                       $ret = $model->recomendaciones;
                       
                       return $ret;
                    },
                ],
                [
                    'attribute' =>'prog_salud',
                    'label'=>'Prog. Salud',
                    'format'=>'raw',
                    'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'value'=>function($model){
                        $ret = '';
                        if($model->trabajador){
                            if($model->trabajador->programas){
                                foreach($model->trabajador->programas as $key=>$program){
                                    if($program->programa){
                                        $ret .= ''.$program->programa->nombre.',';
                                    } 
                                }
                            }
                        }
                       
                        return $ret;
                     },
                ],
                [
                    'attribute' =>'cal',
                    'label'=>'CAL',
                    'contentOptions' => ['class' => "y_centercenter text-center"],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'format'=>'raw',
                    'visible' =>true,
                    'value'=>function($model){
                        $ret = 'NO';
                       
                        if($model->status == 2){
                            $ret = 'SI';
                        }
                        
                        return $ret;
                    },
                ],
                [
                    'attribute' =>'status',
                    'value'=>function($model){
                        $ret = '';
    
                        if($model->status == 1){
                            $ret =  'Abierta';
                        }else if( $model->status == 2){
                            $ret =  'Cerrada';
                        } else if($model->status == 0){
                            $ret =  'Pendiente';
                        }else if( $model->status == 3){
                            $ret =  'Cancelada';
                        }
    
                        return $ret;
                        
                    },
                ],
            ],
            'hiddenColumns'=>[0],
            'clearBuffers' => true,
            'showConfirmAlert' =>false,
            'columnBatchToggleSettings'=>[
                'label'=>'Seleccionar Todo',
                'class' =>'px-2'
            ], 
            'filename'=> $this->title.'_reportdate_'.date('Y-m-d'),
            'columnSelectorOptions'=>[
                'label' => 'Columnas <img src="resources/images/excel.png" class="px-2" height="20px" width="auto"/>',
                'class' => 'btn btn-success text-light colorexcel'
            ],
              'dropdownOptions' => [
              'label' => 'Exportar a',
              'class' => 'btn btn-success text-light colorexcel'
             ],
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                //ExportMenu::FORMAT_EXCEL_X => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_PDF => false,
            ]   
        ]);
    }else{
        $fullExportMenu = '';
    }
?>

    <div class="row mb-3">
        <div class="col-lg-3 d-grid">
            <?php if(Yii::$app->user->can('historias_crear')):?>
            <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>HC Subsecuente', ['create'], ['class' => 'btn btn-primary btnnew btn-block']) ?>
            <?php endif;?>
        </div>
        <div class="col-lg-2 text-center">
            <?php
            if(Yii::$app->user->identity->hidden_actions == 1){
                echo Html::a('<span class=""><i class="bi bi-arrow-clockwise"></i><span>Refresh Nombre Empresas y Médicos', Url::to(['hccohc/refreshnames']), [
                    'title' => Yii::t('app', 'Refresh Nombre Empresas y Médicos'),
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'class'=>'btn btn-primary btnnew5 btn-block'
                ]);
            }
            ?>
        </div>

        <div class="col-lg-7 text-end">
            <?php
            echo $fullExportMenu;
            ?>
        </div>
    </div>

    <?php
    $template = '';
    if(Yii::$app->user->can('historias_ver')){
        $template .='{view}';
    }
    if(Yii::$app->user->can('historias_actualizar')){
        $template .='{update}';
    }
    if(Yii::$app->user->can('historias_delete')){
        $template .='{delete}';
    }
    if(Yii::$app->user->can('historias_corregir')){
        $template .='{correction}';
    }
    ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-label shadow-sm text-uppercase control-label border-0 small'],
        'tableOptions' => ['class' => 'table table-hover table-sm small','style'=>'width:2000px !important'],
        'rowOptions' => ['class' => 'font-12 text-600 bg-white shadow-sm small'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'num_trabajador',
                'label'=>'N° Trab.',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->num_trabajador){
                        $ret = $model->num_trabajador;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_trabajador',
                'contentOptions' => ['class' => "y_centercenter color3 font600",'style'=>'width:7%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        $ret = $model->trabajador->apellidos.' '.$model->trabajador->nombre;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'edad',
                'label'=>'Edad Histórica',
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>'width:5%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>FieldRange::widget([
                    'model' => $searchModel,
                    'attribute1' => 'edad_inicio',
                    'attribute2' => 'edad_fin',
                    'separator' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows" viewBox="0 0 16 16">
                    <path d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z"/>
                  </svg>',
                    'type' => FieldRange::INPUT_TEXT,
                ]),
                'value'=>function($model){
                    $ret = '';

                    if(isset($model->edad) && $model->edad != ''){
                        $ret = $model->edad;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'edad_actual',
                'label'=>'Edad Actual',
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'contentOptions' => ['class' => "y_centercenter text-center",'style'=>'width:5%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>FieldRange::widget([
                    'model' => $searchModel,
                    'attribute1' => 'edadactual_inicio',
                    'attribute2' => 'edadactual_fin',
                    'separator' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows" viewBox="0 0 16 16">
                    <path d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z"/>
                  </svg>',
                    'type' => FieldRange::INPUT_TEXT,
                ]),
                'value'=>function($model){
                    $ret = '';

                    if($model->trabajador){
                        $ret = $model->trabajador->edad;
                    }
                    return $ret;
                },
            ],
            [
                'attribute' =>'id_empresa',
                'visible' => $showempresa,
                'contentOptions' => ['class' => "y_centercenter color6 font600",'style'=>'width:8%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_empresa',
                    'data' =>$empresas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    if($model->empresa){
                        $ret = $model->empresa->comercial;
                    }
                    return $ret;
                  },
            ],
             [
                    'attribute' =>'id_nivel1',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:8%'],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_nivel1',
                    'data' =>$nivel1,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    ]),
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel1,
                    'label'=> $label_nivel1,
                    'value'=>function($model){
                        $ret = '';

                        if($model->nivel1){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel1->pais->pais.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel2',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:8%'],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel2,
                    'label'=> $label_nivel2,
                    'value'=>function($model){
                        $ret = ''; 

                        if($model->nivel2){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel2->nivelorganizacional2.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel3',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:8%'],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel3,
                    'label'=> $label_nivel3,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel3){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel3->nivelorganizacional3.'</span>';
                        }

                        return $ret;
                    },
            ],
            [
                    'attribute' =>'id_nivel4',
                    'format'=>'raw',
                    'contentOptions' => ['class' => " color3 font600",'style'=>'width:8%'],
                    'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                    'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                    text-decoration:none;
                    font-weight: 500;
                    font-size: 11px;'],
                    'visible'=>$show_nivel4,
                    'label'=> $label_nivel4,
                    'value'=>function($model){
                        $ret = '';
                        
                        if($model->nivel4){
                            $ret .= '<span class="badge rounded-pill font10 btnnew2" style="">'.'<i class="bi bi-dash"></i>'.$model->nivel4->nivelorganizacional4.'</span>';
                        }
                        
                        return $ret;
                    },
            ],
            [
                'attribute' =>'id_pais',
                'contentOptions' => ['class' => "",'style'=>'width:7%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->pais){
                        $ret .='<span class="badge rounded-pill font10 btnnew2 ">'.$model->pais->pais.'</span>';;
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_linea',
                'contentOptions' => ['class' => "",'style'=>'width:7%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->linea){
                        $ret .='<span class="badge rounded-pill font10 bgcolor9 ">'.$model->linea->linea.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_ubicacion',
                'contentOptions' => ['class' => "",'style'=>'width:7%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($model){
                    $ret = '';
                    if($model->ubicacion){
                        $ret .='<span class="badge rounded-pill font10 bg-light text-dark">'.$model->ubicacion->ubicacion.'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'id_medico',
                'label'=>'Médico',
                'contentOptions' => ['class' => "y_centercenter y_centercenter color4",'style'=>'width:8%'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'value'=>function($model){
                    $ret = '';
                    if($model->uMedico && $model->uMedico->firmaa){
                        $ret = $model->uMedico->firmaa->nombre;
                    }
                    
                    return $ret;
                  },
            ],
            [
                'attribute' =>'fecha',
                'label'=>'Fecha',
                'contentOptions' => ['class' => "y_centercenter color6",'style'=>'width:7%;'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter' => \kartik\daterange\DateRangePicker::widget(
                    [
                        'model' => $searchModel, 
                        'attribute' => 'fecha', 
                        'convertFormat' => true,
                        'presetDropdown' => true, 
                        'hideInput' => true, 
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 1,
                            'timePicker24Hour' => true,
                            'locale' => [
                                'format' => ('Y-m-d'),
                            ],
                        ]
                ])
            ],
           
            [
                'attribute' =>'condicion',
                'label'=>'Condición',
                'format'=>'raw',
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'condicion',
                    'data' =>['1'=>'Activo','5'=>'Baja','3'=>'NI'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        if($model->trabajador->status == 5){
                            $ret =  '<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>';
                        }else if( $model->trabajador->status == 1){
                            $ret =  '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>';
                        }else if( $model->trabajador->status == 3){
                            $ret =  '<span class="badge bgcolor12 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>NI</span>';
                        }
                    }
                    return $ret;
                  },
               
            ],
            [
                'attribute' =>'prog_salud',
                'label'=>'Prog. Salud',
                'format'=>'raw',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'value'=>function($model){
                    $ret = '';
                    if($model->trabajador){
                        if($model->trabajador->programas){
                            foreach($model->trabajador->programas as $key=>$program){
                                if($program->programa){
                                    $ret .= '<span class="badge rounded-pill bg-light color6 font10">'.$program->programa->nombre.'</span>';
                                } 
                            }
                        }
                    }
                   
                    return $ret;
                 },
            ],
            [
                'attribute' =>'examen',
                'contentOptions' => ['class' => "y_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'examen',
                    'data' =>$tipoexamen,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $tipoexamen = ['1'=>'NI','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];
                    if($model->examen){
                        $ret = '<span class="badge bgtransparent1 text-dark font12 m-1">'.$tipoexamen[$model->examen].'</span>';
                    }
                    return $ret;
                  },
            ],
            [
                'attribute' =>'status',
                'contentOptions' => ['class' => " text-centery_centercenter",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' =>['1'=>'Abierta','2'=>'Cerrada','0'=>'Pendiente','3'=>'Cancelada'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format'=>'raw',
                'value'=>function($model){
                    $ret = '';
                    $label = '';

                    if($model->status == 1){
                        $label =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color5 font14" style="font-size:25px"><i class="bi bi-folder2-open"></i></span> Abierta</div>';
                    }else if( $model->status == 2){
                        $label =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color11 font14" style="font-size:25px"><i class="bi bi-folder"></i></span> Cerrada</div>';
                    } else if($model->status == 0){
                        $label =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color9 font14" style="font-size:25px"><i class="bi bi-folder2-open"></i></span> Pendiente</div>';
                    }else if( $model->status == 3){
                        $label =  '<div class="badge rounded-pill bg-light text-dark font10 y_centercenter font14"><span class="color4 font14" style="font-size:25px"><i class="bi bi-folder"></i></span> Cancelada</div>';
                    }

                    if($model->status == 1 || $model->status == 0){
                        $ret = Html::a($label, Url::to(['hccohc/update','id' => $model->id]), [
                                'title' => Yii::t('app', 'Actualizar'),
                                'data-toggle'=>"tooltip",
                                'data-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm'
                        ]);

                        if($model->status == 1){
                            $image = '<span class="color11" style="font-size:10px"><i class="bi bi-file-pdf-fill"></i></span>';
                            $ret .= Html::a('Generar CAL'.$image, Url::to(['hccohc/close','id' => $model->id]), [
                                'title' => Yii::t('app', 'Actualizar'),
                                'data-toggle'=>"tooltip",
                                'data-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm font10 bordercolor6 text-light'
                            ]);
                        }
                    } else{
                        return $label;
                    }
                    
                    return $ret;
                },
            ],
            [
                'attribute' =>'conclusion',
                'contentOptions' => ['class' => "y_centercenter y_centercenter color3",'style'=>''],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'conclusion',
                    'data' =>[
                        '1'=>'SANO Y APTO',
                        '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                        '3'=>'APTO TEMPORAL',
                        '4'=>'PENDIENTE',
                        '5'=>'NO APTO',
                        ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value'=>function($model){
                    $ret = '';
                    $conclusiones = [
                        '1'=>'SANO Y APTO',
                        '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                        '3'=>'APTO TEMPORAL',
                        '4'=>'PENDIENTE',
                        '5'=>'NO APTO'
                    ];

                    if($model->conclusion){
                        $ret = $conclusiones[$model->conclusion];
                    }
                    
                    return $ret;
                  },
            ],
           [
                'attribute' =>'consentimiento',
                'label'=>'Consentimiento',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'filter'=>Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'consentimiento',
                    'data' =>['1'=>'Con Consentimiento','2'=>'Sin Consentimiento'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Buscar...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false
                    ],
                ]),
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                   
                    if($model->uso_consentimiento != null && $model->uso_consentimiento != '' && $model->uso_consentimiento != ' ' && $model->retirar_consentimiento != null && $model->retirar_consentimiento != '' && $model->retirar_consentimiento != ' ' && $model->acuerdo_confidencialidad != null && $model->acuerdo_confidencialidad != '' && $model->acuerdo_confidencialidad != ' '){
                        $ret .= '<div class="border-bottom color3 font11">CON CONSENTIMIENTO</div>';
                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $ret .= Html::a($image, Url::to(['consentimientopdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    } else {
                        $ret .= '<div class="border-bottom color11 font11">SIN CONSENTIMIENTO</div>';
                    }
                    
                    return $ret;
                },
            ],
            [
                'attribute' =>'hc',
                'label'=>'HC',
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'format'=>'raw',
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';

                    if($model->tipo_hc_poe == 2){
                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';

                        if($model->poe){
                            if($model->poe_doc1 != null && $model->poe_doc1 != '' && $model->poe_doc1 != ' '){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc1;
                                $ret .= Html::a('<span style="font-size:30px;" class="color3">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                            }
                            if($model->poe_doc2 != null && $model->poe_doc2 != '' && $model->poe_doc2 != ' '){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc2;
                                $ret .= Html::a('<span style="font-size:30px;" class="color4">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                            }
                            if($model->poe_doc3 != null && $model->poe_doc3 != '' && $model->poe_doc3 != ' '){
                                $filePath = 'resources/Empresas/'.$model->poe->id_empresa.'/Trabajadores/'.$model->poe->id_trabajador.'/Poes/'.$model->poe_doc3;
                                $ret .= Html::a('<span style="font-size:30px;" class="color7">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                            }
                        }

                    } else {
                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $ret = Html::a($image, Url::to(['hccohc/pdf','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        $ret .= Html::a($image2, Url::to(['hccohc/pdf','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    }
                   
                    return $ret;
                },
            ],
            [
                'attribute' =>'cal',
                'label'=>'CAL',
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'contentOptions' => ['class' => "y_centercenter text-center"],
                'filterInputOptions' => ['class' => 'icon-find2 form-control'],
                'format'=>'raw',
                'visible' =>true,
                'value'=>function($model){
                    $ret = '';
                   
                    $image = '<span class="color11" style="font-size:25px"><i class="bi bi-file-pdf-fill"></i></span>';
                    $image2 = '<span class="color10" style="font-size:25px"><i class="bi bi-file-pdf-fill"></i></span>';
                    
                    if($model->status == 2){
                        $ret = Html::a($image, Url::to(['hccohc/pdfcal','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        $ret .= Html::a($image2, Url::to(['hccohc/pdfcal','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    }
                    
                    return $ret;
                },
            ],
            //'hora',
            //'examen',
            //'empresa',
            //'area',
            //'puesto',
            //'nombre',
            //'apellidos',
            //'sexo',
            //'fecha_nacimiento',
            //'edad',
            //'estado_civil',
            //'nivel_lectura',
            //'nivel_escritura',
            //'grupo',
            //'rh',
            //'diabetess',
            //'diabetesstxt',
            //'hipertension',
            //'hipertensiontxt',
            //'cancer',
            //'cancertxt',
            //'nefropatias',
            //'nefropatiastxt',
            //'cardiopatias',
            //'cardiopatiastxt',
            //'reuma',
            //'reumatxt',
            //'hepa',
            //'hepatxt',
            //'tuber',
            //'tubertxt',
            //'psi',
            //'psitxt',
            //'tabaquismo',
            //'tabdesde',
            //'tabfrec',
            //'tabcantidad',
            //'alcoholismo',
            //'alcodesde',
            //'alcofrec',
            //'alcocantidad',
            //'cocina',
            //'cocinadesde',
            //'audifonos',
            //'audiodesde',
            //'audiocuando',
            //'droga',
            //'drogatxt',
            //'duracion_droga',
            //'fecha_droga',
            //'vacunacion_cov',
            //'nombre_vacunacion',
            //'dosis_vacunacion',
            //'fecha_vacunacion',
            //'mano',
            //'alergias',
            //'alergiastxt',
            //'asma',
            //'asmatxt',
            //'asma_anio',
            //'cardio',
            //'cardiotxt',
            //'cirugias',
            //'cirugiastxt',
            //'convulsiones',
            //'convulsionestxt',
            //'diabetes',
            //'diabetestxt',
            //'hiper',
            //'hipertxt',
            //'lumbalgias',
            //'lumbalgiastxt',
            //'nefro',
            //'nefrotxt',
            //'polio',
            //'politxt',
            //'poliomelitis_anio',
            //'saram',
            //'saram_anio',
            //'pulmo',
            //'pulmotxt',
            //'hematicos',
            //'hematicostxt',
            //'trauma',
            //'traumatxt',
            //'medicamentos',
            //'medicamentostxt',
            //'protesis',
            //'protesistxt',
            //'trans',
            //'transtxt',
            //'enf_ocular',
            //'enf_ocular_txt',
            //'enf_auditiva',
            //'enf_auditiva_txt',
            //'fractura',
            //'fractura_txt',
            //'amputacion',
            //'amputacion_txt',
            //'hernias',
            //'hernias_txt',
            //'enfsanguinea',
            //'enfsanguinea_txt',
            //'tumorescancer',
            //'tumorescancer_txt',
            //'enfpsico',
            //'enfpsico_txt',
            //'gestas',
            //'partos',
            //'abortos',
            //'cesareas',
            //'menarca',
            //'ivsa',
            //'fum',
            //'mpf',
            //'doc',
            //'docma',
            //'peso',
            //'talla',
            //'imc',
            //'categoria_imc',
            //'fc',
            //'fr',
            //'temp',
            //'ta',
            //'ta_diastolica',
            //'caries_rd',
            //'inspeccion',
            //'inspeccion_otros',
            //'txt_inspeccion_otros',
            //'cabeza',
            //'cabeza_otros',
            //'txt_cabeza_otros',
            //'oidos',
            //'oidos_otros',
            //'txt_oidos_otros',
            //'ojos',
            //'ojos_otros',
            //'txt_ojos_otros',
            //'sLentes',
            //'sLentesD',
            //'cLentes',
            //'cLentesD',
            //'Rlentes',
            //'Ulentes',
            //'boca',
            //'boca_otros',
            //'txt_boca_otros',
            //'cuello',
            //'cuello_otros',
            //'txt_cuello_otros',
            //'torax',
            //'torax_otros',
            //'txt_torax_otros',
            //'abdomen',
            //'abdomen_otros',
            //'txt_abdomen_otros',
            //'superior',
            //'miembrossup_otros',
            //'txt_miembrossup_otros',
            //'inferior',
            //'miembrosinf_otros',
            //'txt_miembrosinf_otros',
            //'columna',
            //'columna_otros',
            //'txt_columna_otros',
            //'txtneurologicos',
            //'neurologicos_otros',
            //'txt_neurologicos_otros',
            //'diagnostico',
            //'comentarios',
            //'conclusion',
            //'medico',
            //'status',
            //'firma_medicolaboral',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template,
                'header'=>"Accion",
                'headerOptions' => ['class' => "text-center", 'style'=>'vertical-align: top;'],
                'headerOptions' => ['class' => "y_centercenter",'style'=>'color: #716F81;
                text-decoration:none;
                font-weight: 500;
                font-size: 11px;'],
                'contentOptions' => ['class' => "text-center"],
                'buttons' => [
                    'view' =>  function($url,$model) {
                        return Html::a('<span class="color2"><i class="bi bi-eye-fill"></i><span>', Url::to(['hccohc/view','id' => $model->id]), [
                            'title' => Yii::t('app', 'Ver'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'update' =>  function($url,$model) {
                        //return($model->conclusion_cal);
                        if(1==2){//$model->status != 2
                            if($model->id_poe == null || $model->id_poe == '' && $model->id_poe == ' '){
                                return Html::a('<span class=""><i class="bi bi-pen-fill"></i><span>', Url::to(['hccohc/update','id' => $model->id]), [
                                'title' => Yii::t('app', 'Actualizar'),
                                'data-toggle'=>"tooltip",
                                'data-placement'=>"top",
                                'class'=>'btn btn-sm text-center shadow-sm'
                                ]);
                            } else {
                                return '';
                            }
                        }
                        
                    },
                    'delete' =>  function($url,$model) use ($dataProvider,$searchModel) {
                        $iconcancel = '<i class="bi bi-trash"></i>';

                        $page = $dataProvider->pagination->page;
                        $company = $searchModel->id_empresa;
                        return  Html::a('<span class="color11">'.$iconcancel.'<span>', ['delete', 'id' => $model->id,'company'=>$company,'page'=>$page], [
                            'data' => [
                                'confirm' => Yii::t('app', '¿Seguro que desea eliminar Historia Clínica?'),
                                'method' => 'post',
                            ],
                            'title' => Yii::t('app', 'Eliminar'),
                            'data-bs-toggle'=>"tooltip",
                            'data-bs-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                    'correction' =>  function($url,$model) {
                        return Html::a('<span class="color14"><i class="bi bi-unlock-fill"></i><i class="bi bi-pen-fill"></i><span>', Url::to(['hccohc/correction','id' => $model->id]), [
                            'title' => Yii::t('app', 'Correción HC'),
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'class'=>'btn btn-sm text-center shadow-sm'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>