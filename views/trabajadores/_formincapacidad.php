<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Almacen;
use kartik\file\FileInput;
use app\models\Diagnosticoscie;
use yii\web\JsExpression;
use kartik\time\TimePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;


use app\models\Riesgos;
use app\models\Puestostrabajo;

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
/** @var app\models\Consultas $model */
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
$empresas[0] = 'OTRA';
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$programas = ArrayHelper::map(Programaempresa::find()->orderBy('id_programa')->where(['id_empresa'=>$model->id_empresa])->all(), 'id_programa', function($model){
    return $model->programasalud['nombre'];
});
$consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('consultorio')->all(), 'id', 'consultorio');
$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PSICOLÓGICA','8'=>'COVID-19'];
$riesgostipos = [
    '1'=>'Trabajos en caliente',
    '2'=>'Espacios confinados',
    '3'=>'Trabajo en componentes energizados',
    '4'=>'Trabajos en alturas',
    '5'=>'Trabajos en solitario y/o áreas remotas',
    '6'=>'Trabajo en sistemas presurizados',
    '7'=>'Trabajos químicos altamente peligrosos',
    '8'=>'Construcción/excavación/demolición/izaje'
];
$hoy = date('Y-m-d');
$medicamentos = ArrayHelper::map(Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere(['>','stock',0])->andWhere(['>=','fecha_caducidad',$hoy])->orderBy(['id_insumo'=>SORT_DESC,'fecha_caducidad'=>SORT_ASC])->all(), 'id', function($model){
    $ret = '';
    if($model->insumo){
        $ret =  $model->insumo['nombre_comercial'].' | '.$model->insumo['nombre_generico'];
    }
    $ret .= ' - [Exp. '.$model->fecha_caducidad.']';
    return $ret;
});

$tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
$oxidiagnosticos = ['1'=>'Normal','2'=>'Hipoxia'];
$frdiagnosticos = ['1'=>'Normal','2'=>'Bradipnea','3'=>'Taquipnea'];
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
$modelo_ = 'consultas';
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


<?php
    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
    <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
    </svg>';

    $iconcura = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bandaid" viewBox="0 0 16 16">
    <path d="M14.121 1.879a3 3 0 0 0-4.242 0L8.733 3.026l4.261 4.26 1.127-1.165a3 3 0 0 0 0-4.242ZM12.293 8 8.027 3.734 3.738 8.031 8 12.293 12.293 8Zm-5.006 4.994L3.03 8.737 1.879 9.88a3 3 0 0 0 4.241 4.24l.006-.006 1.16-1.121ZM2.679 7.676l6.492-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Z"/>
    <path d="M5.56 7.646a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708Zm1.415-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707ZM8.39 4.818a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707Zm0 5.657a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707ZM9.803 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707Zm1.414-1.414a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708ZM6.975 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707ZM8.39 7.646a.5.5 0 1 1-.708.708.5.5 0 0 1 .707-.708Zm1.413-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707Z"/>
  </svg>';

  $iconhospital = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
  <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
  <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
</svg>';
?>


<div class="consultas-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row my-3" style="display:none;">
        <div class="col-lg-5">
            <?= $form->field($model, 'solicitante')->widget(Select2::classname(), [
                    'data' => ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaSolicitante(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <?php 
            $show = 'none';
            if($model->solicitante == '2' || $model->solicitante == '3'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-5 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <?php 
            $show = 'none';
            if($model->id_empresa == '0'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-4 mt-3" id="empresa" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'empresa')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
        </div>
        <!-- <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"consultas")'],
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
                    'onchange' => 'changelinea(this.value,"consultas")'],
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
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_consultorio')->widget(Select2::classname(), [
                    'data' => $consultorios,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3 mt-3">
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
        <div class="col-lg-1">
            <?= $form->field($model, 'hora_inicio')->textInput(['maxlength' => true,'onkeyup' => '','readonly'=>true]) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_consultorio')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'id_hccohc')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>

    <div class="row mt-3">
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
        <div class="col-lg-5" style="display:none;">
            <?php
            $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PSICOLÓGICA','8'=>'COVID-19'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PSICOLÓGICA','8'=>'COVID-19'];
            }
            ?>
            <?= $form->field($model, 'tipo')->widget(Select2::classname(), [
                    'data' => $tipoexamen,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTipoconsulta(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-5 datos_trabajador">
            <?= $form->field($model, 'id_trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTrabajador(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'visita')->widget(Select2::classname(), [
                    'data' => ['1'=>'1A VEZ','2'=>'SUBSECUENTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>


    </div>

    <?php 
        $show = 'none';
        if($model->solicitante == '1'){
            $show = 'block';
        }
    ?>
    <div class="row my-3">

        <div class="col-lg-3" style="display:none;">
            <?= $form->field($model, 'folio')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-lg-3 my-3 border30 boxtitle p-2 text-center datos_trabajador"
            style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Historia Clínica
            </label>
            <div class="text-center" id="historia_clinica"></div>
        </div>
        <div class="col-lg-3 offset-lg-1 my-3 border30 boxtitle p-2 text-center datos_trabajador"
            style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Programas de Salud
            </label>
            <div class="text-center" id="programas_salud"></div>
        </div>

    </div>

    <?php
    $deshabilitados = false;
    if($model->solicitante == 1){
        $deshabilitados = true;
    }
    ?>

    <div class="row my-3 mt-5">
        <div class="col-lg-2">
            <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'disabled'=>$deshabilitados,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false,
                        
                    ],
                    ]);?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'edad')->textInput(['type'=>'number','readonly'=>$deshabilitados, 'min'=>'0', 'step'=>'1', 'maxlength' => true])->label() ?>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'num_imss')->textInput(['maxlength' => true,'readonly'=>$deshabilitados]) ?>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-lg-3 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'disabled'=>$deshabilitados,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-4 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'puesto')->widget(Select2::classname(), [
                    'data' => $puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>
    <?php 
    $show = 'none';
    if($model->tipo == '1'){
        $show = 'block';
    }
   ?>

    <div class="container-fluid my-3 border30 bg-light p-4 datos_trabajador" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-exclamation-circle"></i></span>
                            Alergias
                        </label>
                    </div>
                    <div class="col-lg-12" id='alergias'></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-slash-circle"></i></span>
                            Riesgos de Trabajo
                        </label>
                    </div>
                    <div class="col-lg-12" id='riesgos'></div>
                </div>
            </div>

        </div>
    </div>

    <?php 
    $show = 'none';
    if($model->tipo == '4'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_incapacidad"
        style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconhospital;?></span>
                    Datos de la Incapacidad
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_folio')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'incapacidad_tipo')->widget(Select2::classname(), [
                    'data' => ['1'=>'IMSS','2'=>'INTERNA','3'=>'PARTICULAR'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'incapacidad_ramo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Maternidad','2'=>'Enfermedad General','3'=>'Riesgo del Trabajo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_fechainicio')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'vencimientoIncapacidad()',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'incapacidad_dias')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Dias de Incapacidad','onchange'=>'vencimientoIncapacidad()'])->label() ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_fechafin')->textInput(['maxlength' => true,'readonly'=>true]); ?>
            </div>
        </div>
    </div>


    <div class="row my-3 mt-5">
        <div class="col-lg-12 my-3">
            <?= $form->field($model, 'diagnostico')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-6">
            <?= $form->field($model, 'file_evidencia')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'language' => yii::t('app','es'),
                    'pluginOptions' => [
                    'browseClass' => 'btn btn-block btn-sm btn-dark',
                    'uploadClass' => 'btn btn-block btn-sm btn-info',
                    'removeClass' => 'btn btn-block btn-sm btn-danger',
                    'cancelClass' => 'btn btn-block btn-sm btn-danger',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                    ]
                    ])->label(); ?>
        </div>
        <div class="col-lg-6">
            <?php
            if(isset($model->evidencia) && $model->evidencia!= null && $model->evidencia != '' && $model->evidencia != ' '){
                $imageevidencia = '<span class="color15" style="font-size:100px"><i class="bi bi-folder-fill"></i></span><span class="badge bgtransparent2 color11 font12 m-1">Evidencia</span>';
                $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->evidencia;
                $ret = Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                echo $ret;
            }
            ?>
        </div>
    </div>


    <div class="row my-3 mt-5">
        <div class="col-lg-4">
            <?= $form->field($model, 'resultado')->widget(Select2::classname(), [
                    'data' => ['3'=>'ENVIO IMSS','5'=>'INCAPACIDAD IMSS'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
        <div class="col-lg-4">
            <?php
            $tipospadecimientos = ['1'=>'LABORAL','2'=>'NO LABORAL'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipospadecimientos = ['3'=>'GENERAL'];
            }
            ?>
            <?= $form->field($model, 'tipo_padecimiento')->widget(Select2::classname(), [
                    'data' =>  $tipospadecimientos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
        <div class="col-lg-4">
            <?php
                $tipososha = ['1'=>'ANOTHER RECORDABLE CASES','2'=>'NO  WORK RELEATED','3'=>'NO APLICA'];
                ?>
            <?= $form->field($model, 'calificacion_osha')->widget(Select2::classname(), [
                    'data' =>  $tipososha,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
    </div>

    <?php
        $url = Url::to(['firma']);
    ?>
    <div class="row my-3">
        <div class="col-lg-12 text-center">
            <?php if(isset($model->firma_ruta)):?>
            <img src="<?php  echo '/Consentimientos/'.$model->id.'/'.$model->firma_ruta;?>"
                class="img-fluid img-responsive" width="500px" height="auto" />
            <?php endif;?>
        </div>
        <div class="col-lg-12 text-center">
            <!--  <?= $form->field($model, 'firma')->textInput(['maxlength' => true]) ?> -->
            <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'1000px','height'=>'500px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">Firma Trabajador </h5>']) ?>
        </div>
    </div>
    <div class="row" style="display:none;">
        <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
        <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
    </div>


    <div class="row">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>