<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Presentaciones;
use app\models\Unidades;
use app\models\Viasadministracion;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Insumos;
use unclead\multipleinput\MultipleInput;
use app\models\Almacen;
use app\models\Trabajadores;


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
/** @var app\models\Movimientos $model */
/** @var yii\widgets\ActiveForm $form */
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
    //dd($tipo);
    $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
    if(Yii::$app->user->identity->empresa_all != 1) {
        $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
    } else{
        $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
    }
    $presentaciones = ArrayHelper::map(Presentaciones::find()->orderBy('presentacion')->all(), 'id', 'presentacion');
    $presentaciones[0] = 'OTRO';
    $unidades = ArrayHelper::map(Unidades::find()->orderBy('unidad')->all(), 'id', 'unidad');
    $unidades[0] = 'OTRO';
    $vias = ArrayHelper::map(Viasadministracion::find()->orderBy('via_administracion')->all(), 'id', 'via_administracion');
    $vias[0] = 'OTRO';
    $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('consultorio')->all(), 'id', 'consultorio');
    $consultorios[0]='Almacén';

    $colores = [1=>'Negro',2=>'Azul',3=>'Marrón',4=>'Gris',5=>'Verde',6=>'Naranja',7=>'Rosa',8=>'Púrpura',9=>'Rojo',10=>'Blanco',11=>'Amarillo'];
    $medidas = [
        '1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS',
        '2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS',
        '3'=>'MEX 26 | US 6 | EUR 32 | INTER S',
        '4'=>'MEX 28 | US 8 | EUR 34 | INTER M',
        '5'=>'MEX 30 | US 10 | EUR 34 | INTER M',
        '6'=>'MEX 32 | US 12 | EUR 36 | INTER L',
        '7'=>'MEX 36 | US 14 | EUR 36 | INTER L',
        '8'=>'MEX 38 | US 16 | EUR 38 | INTER XL',
        '9'=>'MEX 40 | US 18 | EUR 38 | INTER XL',
        '10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL',
        '11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',
    ];
    $medidascabezamano = ['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL'];
    $medidascalzado = ['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13'];
    $tallas = $medidas+$medidascabezamano+$medidascalzado;
    $sexo = [1=>'Masculino',2=>'Femenino',3=>'Unisex'];
    
    $tipos = [];
    if($model->e_s == '1'){
        if($model->scenario == 'update'){
            $tipos = ['1'=>'Ingreso','2'=>'Traspaso','3'=>'Ajustes','4'=>'Inventario Inicial'];
        } else {
            $tipos = ['1'=>'Ingreso','3'=>'Ajustes','4'=>'Inventario Inicial'];
        }
        
    } else if($model->e_s == '2'){
        $tipos = ['5'=>'Traspaso','7'=>'Caducidad','8'=>'Devolucion','9'=>'Entrega EPP'];
    }
    //dd($tipo);
    $medicamentos = [];
    if($model->e_s == 1){
        
        if($tipo == 2){
            $medicamentos = ArrayHelper::map(Insumos::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo'=>$tipo])->orderBy('nombre_comercial')->all(), 'id', function($model) use ($colores,$tallas){
                $ret = $model['nombre_comercial'];

                if(isset($model['color']) && $model['color'] != null && $model['color'] != '' && $model['color'] != ' '){
                    $ret .= ' Color:'.$colores[$model['color']];
                }

                if(isset($model['talla']) && $model['talla'] != null && $model['talla'] != '' && $model['talla'] != ' '){
                    $ret .= ' Talla:'.$tallas[$model['talla']];
                }

                return $ret;
            });
        } else{
            $medicamentos = ArrayHelper::map(Insumos::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo'=>$tipo])->orderBy('nombre_comercial')->all(), 'id', function($model){
                return $model['nombre_comercial'].' | '.$model['nombre_generico'];
            });
        }
    } else if($model->e_s == 2){
        //$hoy = date('Y-m-d');
        $medicamentos = ArrayHelper::map(Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo_insumo'=>$tipo])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['>','stock',0])->orderBy(['id_insumo'=>SORT_DESC,'fecha_caducidad'=>SORT_ASC])->all(), 'id', function($model){
            $ret = '';
            if($model->insumo){
                $ret =  $model->insumo['nombre_comercial'].' | '.$model->insumo['nombre_generico'];
            }
            $ret .= ' - [Exp. '.$model->fecha_caducidad.']';
            return $ret;
        });
        
    }

    if($model->tipo == 2 || $model->tipo == 5){
        $medicamentos = ArrayHelper::map(Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo_insumo'=>$tipo])->andWhere(['id_consultorio'=>$model->id_consultorio2])->andWhere(['>','stock',0])->orderBy(['id_insumo'=>SORT_DESC,'fecha_caducidad'=>SORT_ASC])->all(), 'id', function($model){
            $ret = '';
            if($model->insumo){
                $ret =  $model->insumo['nombre_comercial'].' | '.$model->insumo['nombre_generico'];
            }
            $ret .= ' - [Exp. '.$model->fecha_caducidad.']';
            return $ret;
        });
    }

    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-capsule-pill" viewBox="0 0 16 16">
    <path d="M11.02 5.364a3 3 0 0 0-4.242-4.243L1.121 6.778a3 3 0 1 0 4.243 4.243l5.657-5.657Zm-6.413-.657 2.878-2.879a2 2 0 1 1 2.829 2.829L7.435 7.536 4.607 4.707ZM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm-.5 1.042a3 3 0 0 0 0 5.917V9.042Zm1 5.917a3 3 0 0 0 0-5.917v5.917Z"/>
  </svg>';

    $trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
     return $data['nombre'].' '.$data['apellidos'];
    });
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


<?php
$modelo_ = 'movimientos';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$usuario = Yii::$app->user->identity;
if($usuario->nivel1_all == 1){
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



if($usuario->nivel2_all == 1){
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}  else {
    $array = explode(',', $usuario->nivel2_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}



if($usuario->nivel3_all == 1){
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}  else {
    $array = explode(',', $usuario->nivel3_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}




if($usuario->nivel4_all == 1){
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}  else {
    $array = explode(',', $usuario->nivel4_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
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
?>

<div class="movimientos-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row my-3">
        <div class="col-lg-6 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"movimientos")'],
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
                    'onchange' => 'changepais(this.value,"movimientos")'],
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
                    'onchange' => 'changelinea(this.value,"movimientos")'],
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
        <div class="col-lg-4" style="display:<?php echo $showempresa?>;">

        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'create_date')->textInput(['maxlength' => true,'readonly'=>true,'class'=>'form-control bg-disabled']) ?>
        </div>
        <div class="col-lg-2" style="display:none;">
            <?= $form->field($model, 'folio')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_consultorio')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'tipo_insumo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Medicamento','2'=>'EPP'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>


        <div class="row mt-3">
            <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
                <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel1(this.value,"'.$modelo_.'")'],
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
                    'onchange' => 'changeNivel2(this.value,"'.$modelo_.'")'],
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
                    'onchange' => 'changeNivel3(this.value,"'.$modelo_.'")'],
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
                    'onchange' => 'changeNivel4(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
            </div>
        </div>


        <div class="row my-3">
            <div class="col-lg-4 mt-3">
                <?= $form->field($model, 'e_s')->widget(Select2::classname(), [
                    'data' => ['1'=>'Entrada','2'=>'Salida'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaES(this.value,"movimientos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
            </div>
            <div class="col-lg-4 mt-3">
                <?= $form->field($model, 'tipo')->widget(Select2::classname(), [
                    'data' => $tipos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTipomov(this.value,"movimientos","id_consultorio2")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
            </div>
            <?php 
            $show = 'none';
            if($model->tipo == '5'){
                $show = 'block';
            }
        ?>
            <div class="col-lg-4 mt-3" id="consultorio2" style="display:<?php echo $show;?>;">
                <?= $form->field($model, 'id_consultorio2')->widget(Select2::classname(), [
                    'data' => $consultorios,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaConsultorio(this.value,"movimientos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-4 mt-3">
                <?= $form->field($model, 'id_consultorio')->widget(Select2::classname(), [
                    'data' => $consultorios,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaConsultorio(this.value,"movimientos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>


        <?php 
        $show = 'none';
        if($model->tipo ==9){
            $show = 'flex';
        }
        ?>

        <div class="row my-3" id='bloque_entregaepp' style="display:<?php echo $show;?>;">
            <div class="col-lg-4 mt-3">
                <?= $form->field($model, 'id_trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTrabajadorepp(this.value,"movimientos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
            </div>
            <div class="col-lg-3 mt-3">
                <?= $form->field($model, 'fechaultentrega')->widget(DatePicker::classname(), [ 
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
            <div class="col-lg-5 mt-3">
                <?= $form->field($model, 'motivoentrega')->textArea(['rows'=>'3','maxlength' => true, 'class'=>"form-control",'onkeyup' => 'converToMayus(this);']) ?>
            </div>
        </div>

        <?php if($tipo == 1):?>
        <?php 
        $show = 'none';
        if($model->id_consultorio != null && $model->id_consultorio != '' && $model->id_consultorio != ' '){
            if($model->e_s == '1'){
                if($model->tipo != 2 && $model->tipo != 5){
                    $show = 'block';
                }  
            }
        }
        ?>


        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2" id='movimiento_entrada'
            style="display:<?php echo $show;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconclip;?></span>
                        Medicamentos
                    </label>
                </div>
            </div>
            <?php echo $form->field($model, 'aux_medicamentos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_medicamentos',
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
                            'label' => '<span class="color10 p-3"><i class="bi bi-trash2"></i></span>',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return 1;
                                },
                                'options' =>[
                                    'class' => 'index'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'foto',
                                'title'  => '',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return Html::img('@web/resources/images/'.'medicamento.jpg', ['alt'=>'sin imagen', 'class' => "img-fluid", 'width' => '30px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'id_insumo',
                                'title'  => 'Medicamento (Nombre Comercial | Genérico)',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $medicamentos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var presentacion_id = id.replace("-id_insumo!", "-presentacion");
                                            var unidad_id = id.replace("-id_insumo!", "-unidad");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoInsumo(presentacion_id, unidad_id, valor,"movimientos"); 
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'  => 'presentacion',
                                'title' => '' ,
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'color3',
                                    'style'=>'border:none;display:none;'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'unidad',
                                'title' => 'Unidades x Caja' ,
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'color6 text-end',
                                    'style'=>'border:none;'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'fecha_caducidad',
                                'title'  => 'Fecha Caducidad',
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
                                    'class' => 'color9 font500 text-center text-center',
                                    'style' => 'vertical-align: top; width:23%;'
                                ],         
                            ],
                            [
                                'name'  => 'cantidad',
                                'title' => 'Cantidad '.$model->titulo ,
                                'options' =>[
                                    "onchange" => '
                                        console.log("Estoy cambiando esta wea");
                                        var cantidad = $(this).val();
                                        var id = $(this).attr("id")+"!";
                                        var unidad_id = id.replace("-cantidad!", "-unidad");
                                        var stockunidades_id = id.replace("-cantidad!", "-cantidad_unidades");
                                        var cantidad_unidad = $("#"+unidad_id).val();
                                        console.log("Unidades x caja: "+cantidad_unidad+" | Cantidad: "+cantidad);
                                        
                                        cambiaTotal(cantidad,cantidad_unidad,stockunidades_id);
                                    ',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad_unidades',
                                'title' => '',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                    'style'=>'display:none;'
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

        <?php 
        $show = 'none';
        if($model->id_consultorio != null && $model->id_consultorio != '' && $model->id_consultorio != ' '){
            if($model->e_s == '2'){
                $show = 'block';
            }
        }

        if($model->id_consultorio2 != null && $model->id_consultorio2 != '' && $model->id_consultorio2 != ' '){
            if($model->tipo == 2 || $model->tipo == 5){
                $show = 'block';
            }
        }
        ?>
        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2" id="movimiento_salida"
            style="display:<?php echo $show;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconclip;?></span>
                        Medicamentos
                    </label>
                </div>
            </div>
            <?php echo $form->field($model, 'aux_medicamentossalida')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_medicamentossalida',
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
                            'label' => '<span class="color10 p-3"><i class="bi bi-trash2"></i></span>',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return 1;
                                },
                                'options' =>[
                                    'class' => 'index'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'foto',
                                'title'  => '',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return Html::img('@web/resources/images/'.'medicamento.jpg', ['alt'=>'sin imagen', 'class' => "img-fluid", 'width' => '30px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'id_insumo',
                                'title'  => 'Medicamento (Nombre Comercial | Genérico | [Caducidad])',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $medicamentos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:45%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";

                                            var stock_id = id.replace("-id_insumo!", "-stock");
                                            var unidad_id = id.replace("-id_insumo!", "-unidad");
                                            var cantidad_id = id.replace("-id_insumo!", "-cantidad");
                                            var fecha_id = id.replace("-id_insumo!", "-fecha_caducidad");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoMedicamento(stock_id, unidad_id, cantidad_id,fecha_id, valor,"consultas"); 
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'  => 'unidad',
                                'title' => 'Unidades x Caja' ,
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'color6 text-end',
                                    'style'=>'border:none;'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],          
                            ],
                            [
                                'name'=>'fecha_caducidad',
                                'title' => 'Caducidad' ,
                                'type'=>'textInput',
                                'options'=>[ 'readonly' =>'true','style'=>'','class'=>'bg-disabled text-end rounded-3'],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],   
                            ],
                            [
                                'name'  => 'stock',
                                'title' => 'En Stock',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad',
                                'title' => 'Cantidad',
                                /* 'type'  => kartik\number\NumberControl::className(), */
                                'options' =>[
                                    /* 'maskedInputOptions' => [
                                        'allowMinus' => false,
                                        'digits' => 0,
                                        'min' => 1,
                                        'max' => 1000
                                    ], */
                                    /* 'pluginEvents' => [
                                        
                                    ] */
                                    "change" => '
                                           
                                        var valor = $(this).val();
                                        var id = $(this).attr("id")+"!";

                                        var stock_id = id.replace("-cantidad!", "-stock");

                                        var stock =  $("#"+stock_id).val();
                                        console.log("La cantidad que quire salir es: "+valor);
                                        console.log("El stock disponible es: "+stock);

                                        if(valor>stock){
                                            $("#"+$(this).attr("id")).val(stock);
                                        } else if(valor<1){
                                            $("#"+$(this).attr("id")).val(1);
                                        }
                                    '
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad_unidades',
                                'title' => '',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                    'style'=>'display:none;'
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


        <?php else:?>

        <?php 
        $show = 'none';
        if($model->id_consultorio != null && $model->id_consultorio != '' && $model->id_consultorio != ' '){
            if($model->e_s == '1'){
                if($model->tipo != 2 && $model->tipo != 5){
                    $show = 'block';
                }  
            }
        }

        $iconperson = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
        <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
        <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.492 1.492 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.72.72 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.72.72 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
        </svg>';
       ?>
        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2" id="eppmovimiento_salida"
            style="display:<?php echo $show;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconperson;?></span>
                        Equipo de Protección
                    </label>
                </div>
            </div>
            <?php echo $form->field($model, 'aux_epp')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_epp',
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
                            'label' => '<span class="color10 p-3"><i class="bi bi-trash2"></i></span>',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return 1;
                                },
                                'options' =>[
                                    'class' => 'index'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'foto',
                                'title'  => '',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return Html::img('@web/resources/images/'.'medicamento.jpg', ['alt'=>'sin imagen', 'class' => "img-fluid", 'width' => '30px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'id_insumo',
                                'title'  => 'Nombre Artículo',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $medicamentos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:45%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'  => 'cantidad',
                                'title' => 'Cantidad',
                                /* 'type'  => kartik\number\NumberControl::className(), */
                                'options' =>[
                                    /* 'maskedInputOptions' => [
                                        'allowMinus' => false,
                                        'digits' => 0,
                                        'min' => 1,
                                        'max' => 1000
                                    ], */
                                    /* 'pluginEvents' => [
                                        
                                    ] */
                                    "change" => '
                                    '
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad_unidades',
                                'title' => '',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                    'style'=>'display:none;'
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

        <?php 
        $show = 'none';
        if($model->id_consultorio != null && $model->id_consultorio != '' && $model->id_consultorio != ' '){
            if($model->e_s == '2'){
                $show = 'block';
            }
        }

        if($model->id_consultorio2 != null && $model->id_consultorio2 != '' && $model->id_consultorio2 != ' '){
            if($model->tipo == 2 || $model->tipo == 5){
                $show = 'block';
            }
        }
        ?>

        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2" id="movimiento_salida"
            style="display:<?php echo $show;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconperson;?></span>
                        Equipo de Protección
                    </label>
                </div>
            </div>
            <?php echo $form->field($model, 'aux_eppsalida')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_eppsalida',
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
                            'label' => '<span class="color10 p-3"><i class="bi bi-trash2"></i></span>',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return 1;
                                },
                                'options' =>[
                                    'class' => 'index'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'foto',
                                'title'  => '',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return Html::img('@web/resources/images/'.'medicamento.jpg', ['alt'=>'sin imagen', 'class' => "img-fluid", 'width' => '30px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'id_insumo',
                                'title'  => 'Nombre Artículo',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $medicamentos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:45%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";

                                            var stock_id = id.replace("-id_insumo!", "-stock");
                                            var unidad_id = id.replace("-id_insumo!", "-unidad");
                                            var cantidad_id = id.replace("-id_insumo!", "-cantidad");
                                            var fecha_id = id.replace("-id_insumo!", "-fecha_caducidad");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoMedicamento(stock_id, unidad_id, cantidad_id,fecha_id, valor,"consultas"); 
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'  => 'unidad',
                                'title' => '' ,
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'color6 text-end',
                                    'style'=>'display:none;'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],          
                            ],
                            [
                                'name'  => 'stock',
                                'title' => 'En Stock',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad',
                                'title' => 'Cantidad',
                                /* 'type'  => kartik\number\NumberControl::className(), */
                                'options' =>[
                                    /* 'maskedInputOptions' => [
                                        'allowMinus' => false,
                                        'digits' => 0,
                                        'min' => 1,
                                        'max' => 1000
                                    ], */
                                    /* 'pluginEvents' => [
                                        
                                    ] */
                                    "change" => '
                                           
                                        var valor = $(this).val();
                                        var id = $(this).attr("id")+"!";

                                        var stock_id = id.replace("-cantidad!", "-stock");

                                        var stock =  $("#"+stock_id).val();
                                        console.log("La cantidad que quire salir es: "+valor);
                                        console.log("El stock disponible es: "+stock);

                                        if(valor>stock){
                                            $("#"+$(this).attr("id")).val(stock);
                                        } else if(valor<1){
                                            $("#"+$(this).attr("id")).val(1);
                                        }
                                    '
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad_unidades',
                                'title' => '',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                    'style'=>'display:none;'
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


        <?php endif;?>

        <div class="row my-3 mt-5">
            <div class="col-lg-6 my-3">
                <?= $form->field($model, 'observaciones')->textArea(['rows'=>'3','maxlength' => true, 'class'=>"form-control",'onkeyup' => 'converToMayus(this);']) ?>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4 d-grid">
                <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>