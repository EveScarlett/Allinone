<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Empresas;
use app\models\Trabajadores;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use app\models\Puestostrabajo;
use app\models\Ubicaciones;
use app\models\TipoServicios;
use app\models\Servicios;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

use app\models\Poes;
use app\models\Poeestudio;
use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Hccohc;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */
/** @var yii\widgets\ActiveForm $form */
$usuario = Yii::$app->user->identity;
?>

<?php 
      Modal::begin([
        'id' =>'modal-enlace',
        'options' => [
            'id' => 'modal-enlace',
            'tabindex' => false
        ],
        'title' => '<h5 class="text-uppercase text-purple">
                        Enlazar Estudio
                    </h5>',
        'size' => 'modal-md',
        'headerOptions' =>[
            'class' => 'text-light bg-mymodal btnnew'
        ],
        ]);
        echo '<div id="body-enlace"></div>';
        Modal::end();
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
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('apellidos')->all(), 'id', function($data){
    $status_trabajador = '';

    if($data['status'] == 1){
        $status_trabajador = ' [Activo]';
    } else if($data['status'] == 3){
        $status_trabajador = ' [NI]';
    } else if($data['status'] == 5){
        $status_trabajador = ' [Baja]';
    }
   
    return $data['apellidos'].' '.$data['nombre'].$status_trabajador;

});
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('ubicacion')->all(), 'id', 'ubicacion');


if($usuario->areas_all == 1){
    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
} else {
    $array = explode(',', $usuario->areas_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id',$array])->orderBy('area')->all(), 'id', 'area');
}


$tipos = ArrayHelper::map(TipoServicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$estudios = ArrayHelper::map(Servicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');

$diagnosticos = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'];
$evoluciones = ['100'=>'SIN AVANCE','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'];
$colordiag = ['100'=>'bg-light','0'=>'color6','1'=>'color7','2'=>'color12','3'=>'color11'];
$colores = ['100'=>'bg-light','0'=>'bgpendiente','1'=>'bgcumple','2'=>'bgregular','3'=>'bgnocumple'];
?>

<?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
<path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
<path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
</svg>';
?>

<?php
 $image = '<span class="" style="font-size:20px"><i class="bi bi-file-pdf-fill"></i></span>';
 $image2 = '<span class="" style="font-size:20px"><i class="bi bi-file-pdf-fill"></i></span>';
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
$modelo_ = 'poes';
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



<div class="poes-form">

    <?php $form = ActiveForm::begin([
        'id'=>'form_poes',
        'options' => ['enctype' => 'multipart/form-data'],
       ]); ?>

    <div class="row my-3">
        <div class="col-lg-8 mt-3">
            <?= $form->field($model, 'tipo_poe')->widget(Select2::classname(), [
                    'data' => ['1'=>'NUEVO INGRESO','2'=>'POES PERIODICOS','3'=>'SALIDA'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'model_')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'model_']) ?>
        </div>
        <div class="col-lg-8 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"poes")'],
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
                    'onchange' => 'changepais(this.value,"poes")'],
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
                    'onchange' => 'changelinea(this.value,"poes")'],
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
        <div class="col-lg-2" style="display:<?php echo $showempresa?>;">

        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'anio')->textInput(['type'=>'number']) ?>
        </div>
    </div>


    <div class="row my-3">
        <div class="col-lg-8">
            <?= $form->field($model, 'id_trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTrabajador(this.value,"poes")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>

    <?php
    //changeNivel1(this.value,"'.$modelo_.'")
    //changeNivel2(this.value,"'.$modelo_.'")
    //changeNivel3(this.value,"'.$modelo_.'")
    //changeNivel4(this.value,"'.$modelo_.'")
    ?>
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



    <div class="container-fluid my-3 border30 bg-light p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $icon;?></span>
                    Datos del Trabajador
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'disabled' =>true,
                    'readonly' =>true,
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::classname(), [
                    'disabled' =>true,
                    'readonly' =>true, 
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
                         ]]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'id_area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'disabled' =>true,
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'id_puesto')->widget(Select2::classname(), [
                    'data' => $puestos,
                    'disabled' =>true,
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'num_imss')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>
        </div>

    </div>

    <?php
    $fondos = ['bgcolor1','bgcolor2','bgcolor3','bgcolor4','bgcolor5'];
    $bordes = ['bordercolor1','bordercolor2','bordercolor3','bordercolor4','bordercolor5'];
    $poesprevios = Poes::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['<','id',$model->id])->andWhere(['<>','status',2])->orderBy(['id'=>SORT_DESC])->all();
    if($poesprevios){
        $ret = '<div class="container-fluid mt-3 border30 bg-customlight border-custom p-2">
        <div id="accordion">';

        $ret .= '<div class="row m-0 p-0">
        <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
            <label class="">
                <span class="mx-2"><i class="bi bi-folder"></i></span>
               HISTORIAL DE POES
            </label>
        </div>
        </div>';

        foreach($poesprevios as $key=>$poeanterior){
            $show = '';
            $numero = rand(0, 4);

            $puestoanterior = '';
            if($poeanterior->puesto){
                $puestoanterior = $poeanterior->puesto->nombre;
            }

            $areaanterior = '';
            if($poeanterior->area){
                $areaanterior = $poeanterior->area->area;
            }

            $n_imss = '';
            if($poeanterior->trabajador){
                $n_imss = $poeanterior->trabajador->num_imss;
            }

            if($key == 0){
                $show = 'show'; 
            }
            $ret .= '
            <div class="card '.$bordes[$numero].'">
                    <div class="card-header '.$fondos[$numero].' title2 ">
                        <a class="btn" data-bs-toggle="collapse" href="#collapse'.$poeanterior->id.'">
                            POE '.$poeanterior->anio.' | '.$poeanterior->nombre.' '.$poeanterior->apellidos.'
                        </a>
                    </div>
                    <div id="collapse'.$poeanterior->id.'" class="collapse '.$show.'" data-bs-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label class="control-label">Año</label>
                                    <div class="form-control">
                                        '.$poeanterior->anio.'
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="control-label">Puesto de Trabajo</label>
                                    <div class="form-control">
                                        '.$puestoanterior.'
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="control-label">Área</label>
                                    <div class="form-control">
                                    &nbsp;'.$areaanterior.'
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">N° IMSS</label>
                                    <div class="form-control">
                                         '.$n_imss.'
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <table class="table table-sm font12">
                                    <thead class="table-dark">
                                        <tr>
                                        <th>#</th><th>Categoria</th><th>Estudio</th><th>Fecha</th><th>Evidencia</th><th>Diagnóstico</th><th>Evolución</th><th>Comentarios</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                    if($poeanterior->estudios){
                                        $poesanteriores = Poeestudio::find()->where(['id_poe'=>$poeanterior->id])->orderBy(['orden'=>SORT_ASC])->all();
                                        foreach ($poesanteriores as $key2=>$estudio2){
                                            //dd($estudio2);

                                            $est_categoria = '';
                                            $est_estudio = '';
                                            $est_fecha = '';
                                            $est_evidencia = '';
                                            $est_diagnostico = '';
                                            $est_evolucion = '';
                                            $est_comentario = '';

                                            if($estudio2->tipo){
                                                $est_categoria = $estudio2->tipo->nombre; 
                                            } 
                                            
                                            if($estudio2->estudio){
                                                $est_estudio = $estudio2->estudio->nombre; 
                                            }

                                            $est_fecha = $estudio2->fecha;

                                            if(isset($estudio2->evidencia)){
                                                $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio2->evidencia;
                                                $pdf = Html::a('<span style="font-size:15px;" class="color3 mx-1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                                $est_evidencia .= $pdf;
                                            }
                
                                            if(isset($estudio2->evidencia2)){
                                                $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio2->evidencia2;
                                                $pdf2 = Html::a('<span style="font-size:15px;" class="color4 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                                $est_evidencia .= $pdf2;
                                            }
                
                                            if(isset($estudio2->evidencia3)){
                                                $filePath = 'resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$estudio2->evidencia3;
                                                $pdf3 = Html::a('<span style="font-size:15px;" class="color7 mx-1">'.$image2.'</span>', $filePath, $options = ['target'=>'_blank']);
                                                $est_evidencia .= $pdf3;
                                            }

                                            if (array_key_exists($estudio2->condicion, $diagnosticos)) {
                                                $est_diagnostico = $diagnosticos[$estudio2->condicion];
                                                $color = $colordiag[$estudio2->condicion];
                                                $colorbg = $colores[$estudio2->condicion];
                                            }

                                            if (array_key_exists($estudio2->evolucion, $evoluciones)) {
                                                $est_evolucion = $evoluciones[$estudio2->evolucion];
                                            }

                                            $est_comentario = $estudio2->comentario;
                                            $ret .= 
                                            '<tr>
                                            <td class="font-11">'.($key2+1).'</td>
                                            <td class="font-11">'.$est_categoria.'</td>
                                            <td>'.$est_estudio.'</td>
                                            <td>'.$est_fecha.'</td>
                                            <td>'.$est_evidencia.'</td>
                                            <td class="'.$colorbg.'">'.$est_diagnostico.'</td>
                                            <td>'.$est_evolucion.'</td>
                                            <td>'.$est_comentario.'</td>
                                            </tr>';
                                        }
                                        
                                    }
                                        
                            $ret .= '</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            ';
        }

        $ret .= '</div>
        </div>';

        echo $ret;
    }
    ?>





    <div class="container-fluid my-3 border30 bgcolor2 border-custom p-2" id='poeprevio' style="display:none;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    POE Previo
                </label>
            </div>
        </div>
        <div class="row my-3 px-3">
            <div class="col-lg-2">
                <label class="control-label">Año</label>
                <div class="form-control" id="poe_anio_anterior">
                </div>
            </div>
            <div class="col-lg-4">
                <label class="control-label">Puesto de Trabajo</label>
                <div class="form-control" id="poe_puesto_anterior">
                </div>
            </div>
            <div class="col-lg-4">
                <label class="control-label">
                </label>
                <div class="form-control" id="poe_area_anterior">
                </div>
            </div>
            <div class="col-lg-2">
                <label class="control-label">N° IMSS</label>
                <div class="form-control" id="poe_numimss_anterior">
                </div>
            </div>
        </div>
        <div class="row my-3 px-3" id="poe_estudios_anterior">
        </div>
    </div>

    <div class="container-fluid table-responsive my-3 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    POE Actual
                </label>
            </div>
        </div>
        <div class="my-3">
            <div class="" style="width:1900px !important;">
                <?php echo $form->field($model, 'aux_estudios')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        
                        /* 'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
                        'layoutConfig' => [
                            'offsetClass' => 'col-md-offset-2',
                            'labelClass' => 'col-md-2',
                            'wrapperClass' => 'col-md-6',
                            'errorClass' => 'col-md-offset-2 col-md-6',
                            'buttonActionClass' => 'col-md-offset-1 col-md-2',
                        ], */
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
                                'name' => 'obligatorio',
                                'title'  => 'Obligatorio',
                                'type'  => 'checkbox',
                                 'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500 titulos text-center','style'=>'transform: scale(2.5);',
                                'onChange'=>'',],
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
                                    'style' => 'vertical-align: top; width:17%;'
                                ],         
                            ],
                            [
                                'name'  => 'editar',
                                'title'  => 'Realizar Estudio',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'pdf text-center',
                                    'style' =>''
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],   
                                'value'  => function ($model,$indice)
                                { 
                                    $pdf = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-pen-fill"></i></span>';
                                    
                                    if(isset($model['estudio']) && $model['estudio'] == 1){

                                        
                                        $hc = Hccohc::findOne($model['id_hc']);
                                        if($hc){
                                            $pdf = Html::a($image, Url::to(['hccohc/update','id' => $model['id_hc']]), [
                                            'title' => Yii::t('app', 'Realizar Estudio'),
                                            'data-toggle'=>"tooltip",
                                            'data-placement'=>"top",
                                            'class'=>'btn btn-sm text-center shadow-sm',
                                            'target'=>'_blank'
                                            ]);
                                        } else {
                                            if($model['id'] != null && $model['id'] != '' && $model['id'] != ' '){

                                                $pdf = Html::a($image, Url::to(['hccohc/create','poe' => $model['id_poe'],'estudio' => $model['id']]), [
                                                'title' => Yii::t('app', 'Realizar Estudio'),
                                                'data-toggle'=>"tooltip",
                                                'data-placement'=>"top",
                                                'class'=>'btn btn-sm text-center shadow-sm',
                                                'target'=>'_blank'
                                                ]);

                                            }
                                        }
                                        
                                    }

                                    return $pdf;
                                },
                            ],
                            [
                                'name'  => 'enlazar',
                                'title'  => 'Enlazar Estudio',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'text-center',
                                    'style' =>''
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],   
                                'value'  => function ($model,$indice)
                                { 
                                    $pdf = '';
                                    $image = '<span class="" style="font-size:30px"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                                              <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/>
                                              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                                              </svg></span>';
                                    
                                    if(isset($model['estudio']) && $model['estudio'] == 1){

                                        $hc = Hccohc::findOne($model['id_hc']);
                                        if($model['id'] != null && $model['id'] != '' && $model['id'] != ' '){

                                            $pdf = Html::button($image, ['class' => 'btn btn-sm text-center shadow-sm color3', 'onclick'=>'enlazarHC('.$model['id_poe'].','.$model['id'].',"'.Url::to(['poes/enlazar','id_poe'=>$model['id_poe'],'id_estudio'=>$model['id']]).'")']);

                                        }
                                        
                                    }

                                    return $pdf;
                                },
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
                                'name'  => 'evidencia2',
                                'title'  => '',
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
                                'name'  => 'evidencia3',
                                'title'  => '',
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
                                'name'  => 'diagnostico',
                                'title'  => 'Diagnóstico',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['100'=>'SIN AVANCE','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top; width:15%;'
                                    ],          
                            ],
                            [
                                'name'  => 'evolucion',
                                'title'  => 'Evolución',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['100'=>'SIN AVANCE','0'=>'PENDIENTE','5'=>'INICIAL','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top; width:10%;'
                                    ],          
                            ],
                            [
                                'name'  => 'comentarios',
                                'title' => 'Comentarios',
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
                                'name'  => 'doc',
                                'title'  => 'Docs.',
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
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';
                                    
                                    if(Yii::$app->user->can('poes_documento')){
                                        if(isset($model['doc']) && $model['doc'] != ''){
                                            $filePath = $model['doc'];
                                            $pdf = Html::a('<span style="font-size:30px;" class="color3">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        }

                                        if(isset($model['doc2']) && $model['doc2'] != ''){
                                            $filePath = $model['doc2'];
                                            $pdf .= Html::a('<span style="font-size:30px;" class="color4">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        }

                                        if(isset($model['doc3']) && $model['doc3'] != ''){
                                            $filePath = $model['doc3'];
                                            $pdf .= Html::a('<span style="font-size:30px;" class="color7">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                        }
                                        
                                        if(!isset($model['doc']) && !isset($model['doc2']) && !isset($model['doc3'])){
                                            if($model['estudio'] == 1 && $model['id_hc'] != null){
                                                $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                                $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                                $pdf .= Html::a($image, Url::to(['hccohc/pdf','id' => $model['id_hc'],'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                                //$pdf .= Html::a($image2, Url::to(['hccohc/pdf','id' => $model['id_hc'],'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                            }
                                        }
                                    }

                                    return $pdf;
                                },
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
                            [
                                'name'=>'id_hc',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                            [
                                'name'=>'id_poe',
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
    </div>


    <div class="row my-3">
        <div class="col-lg-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>