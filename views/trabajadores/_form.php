<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;
use kartik\money\MaskMoney;

use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\Estudios;
use app\models\Medidas;
use app\models\Parametros;
use dosamigos\chartjs\ChartJs;
use app\models\Turnos;
use app\models\Maquinaria;


use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Ubicaciones;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
/** @var yii\widgets\ActiveForm $form */

?>

<?php
$areas_trabajador = [];

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
$url = Url::to(['firma']);
?>

<?php
$this->registerCss("
.kv-scorebar-border{
    border:1px solid transparent;
    background: none repeat scroll 0 0 transparent;
}
.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
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

    $maquinas = ArrayHelper::map(Maquinaria::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('maquina')->all(), 'id', function($model){
        return $model['maquina'].' '.$model['clave'];
    });
    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
    $puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
    $turnos = ArrayHelper::map(Turnos::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('orden')->all(), 'id', 'turno');
    $estudios = ArrayHelper::map(Estudios::find()->orderBy('estudio')->all(), 'id', 'estudio');
    $estudios[0] ='OTRO';
    $periodicidad = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
    $medidas1 = ArrayHelper::map(Medidas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['parte_corporal'=>1])->orderBy('medida')->all(), 'id', 'medida');
    $medidas2 = ArrayHelper::map(Medidas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['parte_corporal'=>2])->orderBy('medida')->all(), 'id', 'medida');
    $medidas3 = ArrayHelper::map(Medidas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['parte_corporal'=>3])->orderBy('medida')->all(), 'id', 'medida');
    $medidas4 = ArrayHelper::map(Medidas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['parte_corporal'=>4])->orderBy('medida')->all(), 'id', 'medida');
    $medidas5 = ArrayHelper::map(Medidas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['parte_corporal'=>5])->orderBy('medida')->all(), 'id', 'medida');
    $medidas1[0] ='OTRO';
    $medidas2[0] ='OTRO';
    $medidas3[0] ='OTRO';
    $medidas4[0] ='OTRO';
    $medidas5[0] ='OTRO';

    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('ubicacion')->all(), 'id', 'ubicacion');
    
    //dd($paises);
    $parametros = ArrayHelper::map(Parametros::find()->orderBy('nombre')->all(), 'id', 'nombre');

$parametros[0] = 'NUEVO PARÁMETRO';

$valores = [
    1=>'1',
    2=>'2',
    3=>'3',
    4=>'4',
    5=>'5',
    6=>'6',
    7=>'7',
    8=>'8',
    9=>'9',
    10=>'10'
];

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

<div class="trabajadores-form">

    <?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
?>
    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row">
        <div class="col-lg-2">
            <div class="row">
                <?php
                     $avatar = '/resources/images/'.'av5.jpg';
                     if($model->foto){
                        $avatar = '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->foto;
                     }
                     
                     $filePath =  $avatar;
                     echo '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                     width: 150px;
                     height: 150px;']).'</span>';
                ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'file_foto')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*','id'=>'upload'],
                    'language' => yii::t('app','es'),
                    'pluginOptions' => [
                    'browseClass' => 'btn btn-block btn-sm btn-dark',
                    'uploadClass' => 'btn btn-block btn-sm btn-info',
                    'removeClass' => 'btn btn-block btn-sm btn-danger',
                    'cancelClass' => 'btn btn-block btn-sm btn-danger',
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                    ]
                    ])->label(false); ?>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
            </div>
            <div class="row my-2 boxtitle2 p-2 rounded-4">
                <div class="col-lg-12">
                    Los campos marcados con <span class="px-2 color11"><i class="bi bi-asterisk"></i></span> son
                    obligatorios
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mt-3">
                    <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','3'=>'NI','5'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaStatus(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label($model->getAttributeLabel('status').$asterisco); ?>
                </div>

                <div class="col-lg-8 mt-3" style="display:<?php echo $showempresa?>;">
                    <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label($model->getAttributeLabel('id_empresa').$asterisco); ?>
                </div>
                <div class="col-lg-3 mt-3" style="display:none;">
                    <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3" style="display:none;">
                    <?= $form->field($model, 'id_linea')->widget(Select2::classname(), [
                    'data' => $lineas,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changelinea(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3" style="display:none;">
                    <?= $form->field($model, 'id_ubicacion')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
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
                    'onchange' => 'changeNivel1(this.value,"trabajadores")'],
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
                    'onchange' => 'changeNivel2(this.value,"trabajadores")'],
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
                    'onchange' => 'changeNivel3(this.value,"trabajadores")'],
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
                    'onchange' => 'changeNivel4(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
                </div>
            </div>

            <?php
            $showbaja = 'none';
            if ($model->status == 2) {
                $showbaja = 'flex';
            }
            ?>

            <div class="row my-3" style="display:<?php echo $showbaja;?>;" id="bloquestatus">
                <div class="col-lg-4">
                    <?= $form->field($model, 'fecha_baja')->widget(DatePicker::classname(), [ 
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
                <div class="col-lg-6">
                    <?= $form->field($model, 'motivo_baja')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_puesto')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-lg-5">
                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);', 'onchange'=>'completaConsentimiento(this);'])->label($model->getAttributeLabel('nombre').$asterisco); ?>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);', 'onchange'=>'completaConsentimiento(this);'])->label($model->getAttributeLabel('apellidos').$asterisco); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label($model->getAttributeLabel('sexo').$asterisco);; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-2">
            <?= $form->field($model, 'estado_civil')->widget(Select2::classname(), [
                    'data' => ['1'=>'Soltero','2'=>'Casado','3'=>'Viudo','4'=>'Unión Libre'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2">

            <?= $form->field($model, 'fecha_nacimiento')->widget(\yii\widgets\MaskedInput::class, ['mask' => '9999-99-99', 'options' => [ 'placeholder'=>'yyyy-mm-dd', 'onchange'=>'calculoEdad2(this);']])->label($model->getAttributeLabel('fecha_nacimiento').$asterisco);; ?>

        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'edad')->textInput(['type'=>'number','readonly'=>true]) ?>
        </div>
        <div class="col-lg-3 offset-lg-1" style="display:none;">
            <?= $form->field($model, 'dato_extra1')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]) ?>
        </div>
        <div class="col-lg-3" style="display:none;">
            <?= $form->field($model, 'dato_extra2')->widget(Select2::classname(), [
                    'data' => $paises,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]) ?>
        </div>
        <!-- <div class="col-lg-2 offset-lg-1">
            <?= $form->field($model, 'grupo')->widget(Select2::classname(), [
                    'data' => ['1'=>'A','2'=>'B','3'=>'AB','4'=>'O'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'rh')->widget(Select2::classname(), [
                    'data' => ['1'=>'POSITIVO (+)','2'=>'NEGATIVO (-)'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div> -->
    </div>


    <div class="row mt-5 mb-3">
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
            <?= $form->field($model, 'escolaridad')->widget(Select2::classname(), [
                    'data' => ['1'=>'PRIMARIA','2'=>'SECUNDARIA','3'=>'PREPARATORIA','4'=>'CARRERA TÉCNICA','5'=>'LICENCIATURA','6'=>'MAESTRIA','7'=>'DOCTORADO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-4 offset-lg-1">
            <?= $form->field($model, 'num_imss')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>

    </div>

    <div class="row my-3">
        <div class="col-lg-3">
            <?= $form->field($model, 'curp')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'rfc')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'celular')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="row  mt-5 my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'colonia')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'municipio')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'estado')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'pais')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'cp')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <div class="row  mt-5 my-3">
        <div class="col-lg-2">
            <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'tipo_contratacion')->widget(Select2::classname(), [
                    'data' => ['1'=>'1 MES','2'=>'2 MESES','3'=>'3 MESES','4'=>'4 MESES','5'=>'5 MESES','6'=>'6 MESES','7'=>'INDEFINIDO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'fecha_contratacion')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'calculaAntiguedad(this.value);',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'antiguedad')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row  my-5">
        <div class="col-lg-4">
            <?= $form->field($model, 'ruta')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'parada')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'contacto_emergencia')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>


    <div class="row  my-5">

        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra3')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra4')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra5')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra6')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra7')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra8')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra9')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dato_extra10')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <?php
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
    <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
    </svg>';
    ?>
    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $icon;?></span>
                    Medidas Corporales
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'talla_cabeza')->widget(Select2::classname(), [
                    'data' => $medidas1,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTalla1(this.value,"1")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($model, 'talla_superior')->widget(Select2::classname(), [
                    'data' => $medidas2,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTalla2(this.value,"2")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'talla_inferior')->widget(Select2::classname(), [
                    'data' => $medidas3,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTalla3(this.value,"3")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'talla_manos')->widget(Select2::classname(), [
                    'data' => $medidas4,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTalla4(this.value,"4")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'talla_pies')->widget(Select2::classname(), [
                    'data' => $medidas5,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTalla5(this.value,"5")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?php
                $displaymedida = 'none';
                if($model->talla_cabezaotro == 0){
                    $displaymedida = 'block';
                }
                ?>
                <div id="bloque_cabezaotro" style="display:<?=$displaymedida;?>;">
                    <?= $form->field($model, 'talla_cabezaotro')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>
            <div class="col-lg-2">
                <?php
                $displaymedida = 'none';
                if($model->talla_cabezaotro == 0){
                    $displaymedida = 'block';
                }
                ?>
                <div id="bloque_superiorotro" style="display:<?=$displaymedida;?>;">
                    <?= $form->field($model, 'talla_superiorotro')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>
            <div class="col-lg-2">
                <?php
                $displaymedida = 'none';
                if($model->talla_cabezaotro == 0){
                    $displaymedida = 'block';
                }
                ?>
                <div id="bloque_inferiorotro" style="display:<?=$displaymedida;?>;">
                    <?= $form->field($model, 'talla_inferiorotro')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>
            <div class="col-lg-2">
                <?php
                $displaymedida = 'none';
                if($model->talla_cabezaotro == 0){
                    $displaymedida = 'block';
                }
                ?>
                <div id="bloque_manosotro" style="display:<?=$displaymedida;?>;">
                    <?= $form->field($model, 'talla_manosotro')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>
            <div class="col-lg-2">
                <?php
                $displaymedida = 'none';
                if($model->talla_cabezaotro == 0){
                    $displaymedida = 'block';
                }
                ?>
                <div id="bloque_piesotro" style="display:<?=$displaymedida;?>;">
                    <?= $form->field($model, 'talla_piesotro')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2" style="display:none;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span><img src="resources/images/psicologico.png" class="" height="30px" width="auto" /></span>
                    Perfil Psicológico
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'personalidad')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
            <div class="col-lg-12">
                <div class="row my-2 boxtitle2 p-2 rounded-4">
                    <div class="col-lg-12">
                        <span class="font600">Ingrese de 1 a 10 parámetros</span> de personalidad que sean
                        requeridos para el puesto de trabajo.<br><span class="font600">Agregue un valor de 1 al
                            10</span>, siendo 10 el valor mas alto y 1 el mas bajo, segun sea la importancia de este
                        parámetro en el puesto.
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <?php echo $form->field($model, 'aux_psicologico')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        
                        /* 'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
                        'layoutConfig' => [
                            'offsetClass' => 'col-md-offset-2',
                            'labelClass' => 'col-md-2',
                            'wrapperClass' => 'col-md-6',
                            'errorClass' => 'col-md-offset-2 col-md-6',
                            'buttonActionClass' => 'col-md-offset-1 col-md-2',
                        ], */
                        'theme'=>'bs',
                        'id'=>'aux_psicologico',
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
                                'name'  => 'parametro',
                                'title'  => 'Parámetros',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $parametros,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>''],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-parametro!", "-otroparametro");
                                            
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoParametro(nuevo_id, valor);
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:40%;'
                                ],         
                            ],
                            [
                                'name'  => 'otroparametro',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none;',
                                    'placeholder'=>'Nombre Parámetro'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:40%;'
                                ],          
                            ],
                            [
                                'name'  => 'valor',
                                'title'  => 'Valor',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $valores,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>''],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:30%;'
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
            <div class="col-lg-6">
                <div class="card-body bg-soft">
                    <?php
                        $labels = [];
                        $datas = [];
                        $datasmax = [];
                        foreach($model->parametros as $key=>$parametro){
                            array_push($labels, $parametro->parametro->nombre);
                            array_push($datas, $parametro->valor);
                            array_push($datasmax, 10);
                        }
                        ?>
                    <?= ChartJs::widget([
                                    'type' => 'radar',  
                                    'options' => [
                                       
                                    ],
                                    'data' => [
                                        'labels' => $labels, // Your labels
                                        'datasets' => [
                                            [
                                                'label'=> 'Valor Máximo',
                                                'data'=> $datasmax,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(65, 73, 217, 0.2)',
                                                'borderColor'=> 'rgb(65, 73, 217)',
                                                'pointBackgroundColor'=> 'rgb(65, 73, 217)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(65, 73, 217)'
                                            ],
                                            [
                                                'label'=> 'Valor Parámetro',
                                                'data'=> $datas,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(255, 99, 132, 0.2)',
                                                'borderColor'=> 'rgb(255, 99, 132)',
                                                'pointBackgroundColor'=> 'rgb(255, 99, 132)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(255, 99, 132)'
                                            ],
                                        ]
                                    ],
                                    ]);?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Puesto de Trabajo
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'id_area')->widget(Select2::classname(), [
                    'data' => $areas_trabajador,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <?php
            //dd($model->id_empresa);
            ?>
            <div class="col-lg-4">
                <?= $form->field($model, 'id_puesto')->widget(Select2::classname(), [
                    'data' => $puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaPuesto(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'teamleader')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
            <div class="col-lg-4 mt-3">
                <?= $form->field($model, 'turno')->widget(Select2::classname(), [
                    'data' => $turnos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'fecha_iniciop')->widget(DatePicker::classname(), [ 
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
            <div class="col-lg-3">
                <?= $form->field($model, 'fecha_finp')->widget(DatePicker::classname(), [ 
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

            <div class="col-lg-4">
                <?= $form->field($model, 'puesto_contable')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'puesto_sueldo')->widget(MaskMoney::classname(), [
                            'options' => [
                                'onchange'=>''
                            ],
                            'pluginOptions' => [
                                'prefix' => '$ ',
                                'suffix' => '',
                                'allowNegative' => false,
                            ]
                        ]); ?>
            </div>
        </div>


        <?php
         $show = 'none';
         if($model->scenario == 'update'){
             $show = 'block';
         }
        ?>
        <div class="row mt-5 m-0 p-0" style="display:<?php echo $show;?>;">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-clock-history"></i></span>
                    Historial Puestos de Trabajo
                </label>
            </div>
        </div>
        <div class="row" style="display:<?php echo $show;?>;">
            <div class="col-lg-12">
                <?php echo $form->field($model, 'aux_puestos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_puestos',
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
                                'name'  => 'area',
                                'title'  => 'Área', 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $areas,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:20%;'],
                                    
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
                                'name'  => 'puesto',
                                'title'  => 'Puesto', 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $puestos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:20%;'],
                                    
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
                                'name'  => 'finicio',
                                'title'  => 'Fecha Inicio',  
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
                                    'style' => 'vertical-align: top;width:20%;'
                                ],   
                            ],
                            [
                                'name'  => 'ffin',
                                'title'  => 'Fecha Fin', 
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
                                    'style' => 'vertical-align: top;width:20%;'
                                ],   
                            ],
                            [
                                'name'  => 'teamleader',
                                'title'  => 'Teamleader',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],    
                            ],
                            [
                                'name'  => 'status',
                                'title'  => 'Status',  
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['0'=>'Baja','1'=>'Alta'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
                                    
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]   
                                ],   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
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
        </div>
    </div>


    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle2 p-1 rounded-3 color11 mt-3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-clipboard"></i></span>
                    Requisitos Nuevo Ingreso
                </label>
            </div>
            <div class="row my-2 ">
            <div class="col-lg-12 boxtitle2 p-2 rounded-4 font12">
                <span class="px-2 color11"><i class="bi bi-asterisk"></i></span>Los requisitos marcados en este apartado
                <span class="font500 text-uppercase">no son obligatorios</span>, se registran unicamente con fines informativos al momento del reclutamiento
                / nuevo ingreso.
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                $ret = '';
                $ret .= '<table class="table table-hover table-sm text-little" style="height:100%">';
                $ret .= '<tbody>';

                if($model->puesto){

                    $periodicidadesni = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                    if($model->puesto->requisitosniactivos){
                        
                        $ret.= '<tr class="font500"><td>#</td><td>Requisito</td><td>Periodicidad</td><td>Requerido Desde el Día</td></tr>';
                        foreach($model->puesto->requisitosniactivos as $key=>$estudio){
                            $nombreestudio = '';
                            $per = '';

                            if($estudio->tipo_doc_examen == 1){
                                if($estudio->estudio){
                                    $nombreestudio = $estudio->estudio->estudio;
                                }
                            } else if($estudio->tipo_doc_examen == 2){
                                if($estudio->examenmedico){
                                    $nombreestudio = $estudio->examenmedico->nombre;
                                }
                            }

                            if($estudio->periodicidad != null && $estudio->periodicidad != '' && $estudio->periodicidad != ' '){
                                $per = $periodicidadesni[$estudio->periodicidad];
                            }
                            $ret.= '<tr><td>'.($key+1).'</td><td>'.$nombreestudio.'</td><td>'.$per.'</td><td>'.$estudio->fecha_inicio.'</td></tr>';
                            
                        }
                    } else {
                        $ret.= '<tr><td colspan=4 class="text-center">SIN REQUISITOS</td></tr>';
                    }

                } else {
                    $ret.= '<tr><td colspan=4 class="text-center">SIN REQUISITOS</td></tr>';
                }

                $ret .= '</tbody></table>';

                echo $ret;
                ?>
            </div>
        </div>
    </div>

    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-clipboard"></i></span>
                    Requisitos
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
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
                            'label' => '',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '',
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
                                'options' =>[
                                    'class' => 'index color4'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'tipo',
                                'title'  => 'Tipo',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['1'=>'Médico','2'=>'Otro'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var modelo = "requerimientoempresa";
                                            var nuevo_id = id.replace("-tipo!", "-estudio");
                                           /*  console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            console.log("Nuevo id : "+nuevo_id); */
                                            cambiaTipo(nuevo_id, valor,modelo);
                                        }'
                                    ] 
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
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
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:20%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-estudio!", "-otro");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoEstudio(nuevo_id, valor);
                                        }'
                                    ]   
                                ],  
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                            [
                                'name'  => 'otro',
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
                                'name'  => 'periodicidad',
                                'title'  => 'Periodicidad',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $periodicidad,
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
                                'name'  => 'fecha_documento',
                                'title'  => 'Fecha Documento',
                                'type'  => \yii\widgets\MaskedInput::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'mask' => '9999-99-99', 
                                    'options' => [ 'placeholder'=>'yyyy-mm-dd',
                                    'class'=>'form-control',
                                    'onchange'=>'
                                    
                                    var fecha = $(this).val();
                                    console.log("Estoy calculando la fecha de vencimiento: "+fecha);

                                    var fecha_documento_id = $(this).attr("id")+"!";
                                    var fecha_vencimiento_id = fecha_documento_id.replace("-fecha_documento!", "-fecha_vencimiento");
                                    var periodicidad_id = fecha_documento_id.replace("-fecha_documento!", "-periodicidad");

                                    var periodicidad = $("#"+periodicidad_id).val();
                                    calculaVencimiento(fecha_vencimiento_id,fecha_documento_id, fecha,periodicidad);
                                    '

                                    ]
                                   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:13%;'
                                ],         
                            ],
                            [
                                'name'  => 'fecha_vencimiento',
                                'title'  => 'Fecha Vencimiento',
                                'type'  => 'textInput',
                                'options'=>['readonly'=>'true','class'=>'y_centercenter color3','style'=>'border:none;'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:10%;'
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
                                'title' => '',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'display:none;',
                                    'placeholder'=>'Conclusión Estudio'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'status_show',
                                'title'  => 'Status',
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
                                    $ret = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                    
                                    if(isset($model['status_show'])){
                                       
                                        if($model['status_show'] == 1){
                                            $ret = '<span class="badge bgcumple m-1 font14 text-light text-uppercase">Vigente</span>';
                                        } else  if($model['status_show'] == 2){
                                            $ret = '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">Vencido</span>';
                                        } else if($model['status_show'] == 0){
                                            $ret = '<span class="badge bgpendiente m-1 font14 text-light text-uppercase">Indefinido</span>';
                                        } else if($model['status_show'] == 3){
                                            $ret = '<span class="badge bgcolor14 m-1 font14 text-dark text-uppercase">Sin Archivo</span>';
                                        }
                                            
                                        return $ret;
                                            
                                    }
                                },
                            ],
                            [
                                'name'=>'fecha_apartir',
                                'title'  => 'Req. Desde',
                                'type'=>'textInput',
                                'options'=>['readonly'=>'true','style'=>'border:none;'],
                                'headerOptions' => [
                                    'class' => 'color11 align-top text-center text-uppercase small space p-0',
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
        </div>
    </div>

    <?php
    $ver_maquinaria = 'none';
    if(Yii::$app->user->identity->empresa && Yii::$app->user->identity->empresa->configuracion->verseccion_maquina == 1){
        $ver_maquinaria = 'block';
    }
    ?>
    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2" style="display:<?=$ver_maquinaria?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-hammer"></i></span>
                    Maquinaria Autorizada
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $form->field($model, 'maquina_select')->widget(Select2::classname(), [ 
                    'data' => $maquinas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [    
                    ],])->label(); 
                ?>
            </div>
        </div>
    </div>





    <?php
    $displaycam_preregistro = 'flex';
    $display_preregistro = 'block';
    $display_btn = 'none';

    if($model->scenario == 'create'){
        $displaycam_preregistro = 'none';
        $display_preregistro = 'none';
        $display_btn = 'inline';
    }
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
    <div class="container-fluid bg1 p-3 my-3 shadow datos_consentimiento" style="display:none;">
        <h5 class="mb-3 bgcolor1 text-light p-2 text-center ">
            CONSENTIMIENTO
        </h5>
        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                    class="mx-2 border-bottom-dot title2 color3 nombre_cliente"
                    id='nombre_cliente'><?php echo $model->nombre.' '.$model->apellidos?></span>, EN PLENO
                USO DE
                MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE SE HIZO
                DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO PARA LA
                REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS ACCIONES
                QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA INTEGRIDAD DE
                NINGUNA PERSONA.
            </div>
        </div>
        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                FINALMENTE, Y CORRESPONDIENDO AL PRINCIPIO DE CONFIDENCIALIDAD, SE ME HA EXPLICADO QUE LA
                INFORMACIÓN QUE DERIVE COMO RESULTADO, RESPECTO A EL/LOS PROCEDIMIENTO(S) PRACTICADO(S),
                SERÁ MANEJADA DE MANERA CONFIDENCIAL Y ESTRICTAMENTE PARA EL USO DE:
            </div>
            <div class="col-lg-6">
                <?=$form->field($model, 'uso_consentimiento')->radioList( [1=>Yii::t('app','MI PERSONA'), 2 => Yii::t('app','ÁREA DE RECURSOS HUMANOS DE LA EMPRESA')],['onClick'=>'aceptaTerminos("Trabajadores","trabajadores")', 'class'=>'mb-0 title2 color3 font-600','separator' => '<br>', 'itemOptions' => [
                        'class' => 'largerCheckbox'
                       ]] )->label(false);
                ?>
            </div>
            <div class="col-lg-6 shadow">
                <br>
                <span class="border-bottom-dot title2 color11 nombre_empresa mx-2 p-2" id='nombre_empresa'>
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
                EN MI PRESENCIA HAN SIDO LLENADOS TODOS LOS ESPACIOS EN BLANCO EXISTENTES EN ESTE DOCUMENTO.
                TAMBIÉN ME ENCUENTRO ENTERADO(A) QUE TENGO LA FACULTAD DE RETIRAR ESTE CONSENTIMIENTO SI ASÍ
                LO DESEO (EN EL CASO DE QUE NO SE ME HAYA EXPLICADO EL/LOS PROCEDIMIENTO(S)).
            </div>
            <div class="col-lg-12">
                <?=$form->field($model, 'retirar_consentimiento')->radioList( [1=>Yii::t('app','SI'), 2 => Yii::t('app','NO')],['onClick'=>'aceptaTerminos()', 'class'=>'mb-0 title2 color3  font-600','separator' => '<br>', 'itemOptions' => [
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
                DOY MI CONSENTIMIENTO PARA LA REALIZACIÓN DEL PROCEDIMIENTO(S) ANTERIORMENTE SEÑALADO(S).
            </div>
        </div>
        <div class="row my-5 pt-3">
            <div class="col-lg-12 text-justify text-darkgray font-600">

                <?php $aviso= Html::a('Aviso de privacidad', Url::to(['trabajadores/privacy']), $options = ['target'=>'_blank','class'=>"btn boton btn-primary"]);?>
                <?php
                echo $form->field($model, 'acuerdo_confidencialidad')->checkBox([
                    'label' => '<span class="text-uppercase">He leído y aceptado el '.$aviso.' y comprendo que la información proporcionada se usará de acuerdo a los terminos establecidos.</span>',
                    'onChange'=>'aceptaTerminos()',
                    'class' => 'largerCheckbox',
                    'options' => [
                        
                        ]
                    ])->label(false);
                ?>

            </div>
        </div>
        <div class="row my-3" style="display:none;">
            <div class="col-lg-12 text-center">
                <?php if(isset($model->firma_ruta)):?>
                <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->firma_ruta;?>"
                    class="img-fluid img-responsive" width="500px" height="auto" />
                <?php endif;?>
            </div>
            <div class="col-lg-12 text-center">
                <!--  <?= $form->field($model, 'firma')->textInput(['maxlength' => true]) ?> -->
                <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'800px','height'=>'300px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">FIRMA TRABAJADOR </h5>']) ?>
            </div>
        </div>
        <div class="row" style="display:none;">
            <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
            <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
        </div>

    </div>

    <div class="container-fluid bg1 p-3 my-3" style="display:none;">
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


        <div class="row my-3 mt-4 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
            <div class="col-lg-6">
                <div id="live_camera"></div>
                <div class="row mt-3">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Evidencia"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot()">
                    </div>
                </div>
                <div class="row my-3" style="display:none;">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Identificación Frontal"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot2()">
                    </div>
                </div>
                <div class="row my-3" style="display:none;">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Identificación Reverso"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot3()">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
                    <div class="col-lg-4 d-grid text-center">
                        <label class="control-label" for="canvasfoto">Evidencia</label>
                        <div id="preview">
                            <?php if(isset($model->foto)):?>
                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->foto_web;?>"
                                class="img-fluid img-responsive" width="350px" height="300px" />
                            <?php endif;?>
                        </div>
                        <canvas id="canvasfoto" class="canvasmedia text-center mx-auto" width="350px" height="300px"
                            style="display:none;"></canvas>
                    </div>
                    <!--  <div class="col-lg-4 d-grid text-center" style="display:none;">
                <label class="control-label" for="canvasine">Identificación Frontal</label>
                <div id="preview2">
                    <?php if(isset($model->ife)):?>
                    <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->ife;?>"
                        class="img-fluid img-responsive" width="350px" height="300px" />
                    <?php endif;?>
                </div>
                <canvas id="canvasine" class="canvasmedia text-center mx-auto" width="350px" height="300px"
                    style="display:none;"></canvas>
            </div>
            <div class="col-lg-4 d-grid text-center" style="display:none;">
                <label class="control-label" for="canvasinereverso">Identificación Reverso</label>
                <div id="preview3">
                    <?php if(isset($model->ife_reverso)):?>
                    <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->ife_reverso;?>"
                        class="img-fluid img-responsive" width="350px" height="300px" />
                    <?php endif;?>
                </div>
                <canvas id="canvasinereverso" class="canvasmedia text-center mx-auto" width="350px" height="300px"
                    style="display:none;"></canvas>
            </div> -->
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


    <div class="row">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardartrabajador']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>


