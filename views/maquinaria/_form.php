<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\file\FileInput;

use app\models\Empresas;
use app\models\Ubicaciones;

use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;


/** @var yii\web\View $this */
/** @var app\models\Maquinaria $model */
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
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('ubicacion')->all(), 'id', 'ubicacion');
?>
<?php
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
    <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
    </svg>';

    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
?>


<?php
$id_paises =[];
$id_lineas = [];
$id_ubicaciones =[];

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
$modelo_ = 'maquinaria';
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
<div class="maquinaria-form">

    <?php $form = ActiveForm::begin(['id'=>'formSMO']); ?>
    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>
    <div class="row border30">
        <div class="col-lg-2">
            <div class="row">
                <?php
                     $avatar = '/resources/images/'.'indus1.png';
                     if($model->foto){
                        $avatar = '/resources/Empresas/'.$model->id.'/'.$model->foto;
                     }
                     
                     $filePath =  $avatar;
                     echo '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                     width: 150px;
                     height: 150px;']).'</span>';
                ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'file_logo')->widget(FileInput::classname(), [
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
                    ])->label('Foto'); ?>
            </div>

        </div>
        <div class="col-lg-10">
            <div class="row my-3">
                <div class="col-lg-12 mt-3" style="display:<?php echo $showempresa?>;">
                    <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa(this.value,"maquinaria")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <!--  <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"maquinaria")'],
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
                    'onchange' => 'changelinea(this.value,"maquinaria")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_ubicacionl')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div> -->
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
                <div class="col-lg-6">
                    <?= $form->field($model, 'maquina')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'clave')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3">

                    <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['0'=>'Baja','1'=>'Activo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
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
                    <span class="mx-2"><span class="mx-2"><i class="bi bi-clipboard"></i></span></span>
                    Ficha Técnica
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'fabricante')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <?= $form->field($model, 'modelo')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <?= $form->field($model, 'marca')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <?= $form->field($model, 'combustible')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                    </div>
                </div>
            </div>
            <div class="col lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'id_area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                    </div>
                    <div class="col-lg-12">
                        <?= $form->field($model, 'id_ubicacion')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><span class="mx-2"><i class="bi bi-clipboard"></i></span></span>
                    Características Generales
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'peso')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'altura')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'ancho')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'largo')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'alto')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-lg-6">
                <?= $form->field($model, 'detalles_tecnicos')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'funcion')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
        </div>
    </div>


    <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><span class="mx-2"><i class="bi bi-exclamation-triangle-fill"></i></span></span>
                    Riesgos
                </label>
            </div>
        </div>
        <div class="row by-3">
            <div class="col-lg-6">
                <?php echo $form->field($model, 'aux_riesgos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
                        'theme'=>'bs',
                        'id'=>'aux_riesgos',
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
                                'name'  => 'riesgo',
                                'title'  => 'Riesgo',
                                'type'  => 'textInput',
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
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
        </div>
    </div>



    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'enviarFormvalue']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>