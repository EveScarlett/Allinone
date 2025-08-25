<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use kartik\select2\Select2;


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
/** @var app\models\Configconsentimientos $model */
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
$modelo_ = 'configconsentimientos';
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

<div class="configconsentimientos-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeempresa(this.value,"configconsentimientos")'],
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
                    'onchange' => 'changepais(this.value,"configconsentimientos")'],
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
                    'onchange' => 'changelinea(this.value,"configconsentimientos")'],
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
            <?= $form->field($model, 'id_tipoconsentimiento')->widget(Select2::classname(), [
                    'data' => ['1'=>'Historias Clínicas','2'=>'Consultas Clínicas','3'=>'POES'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
        </div>
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
    <div class="row mt-5">
        <div class="col-lg-4 bg-dark p-3 text-light">
            <div class="row">
                <div class="col-lg-12 d-grid text-center">
                    <h5>Herramientas del Formato</h5>
                </div>
                <div class="col-lg-10 p-1 mt-1 d-grid">
                    <?= $form->field($model, 'titulo')->textInput(['class'=>'text-primary form-control','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-2 p-1 mt-1">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-arrow-return-right"></i></span>', ['class' => 'agregaformato btn btn-sm btn-primary btnnew3 rounded-pill','id'=>'1']) ?>
                </div>
                <div class="col-lg-10 p-1 mt-1 d-grid">
                    <?= $form->field($model, 'parrafo')->textArea(['class'=>'text-primary form-control','rows'=>3,'maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-2 p-1 mt-1">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-arrow-return-right"></i></span>', ['class' => 'agregaformato btn btn-sm btn-primary btnnew3 rounded-pill','id'=>'2']) ?>
                </div>
                <div class="col-lg-12 p-1 mt-1 d-grid">
                    <?= Html::button('Salto de Línea <span class="mx-2"><i class="bi bi-arrow-return-right"></i></span>', ['class' => 'agregaformato btn btn-block btn-sm btn-primary btnnew5 rounded-pill','id'=>'0']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-calendar-date"></i></span>Fecha', ['class' => 'agregaformato btn btn-sm btn-primary btnnew3 btn-block rounded-pill','id'=>'3']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-alarm"></i></span>Hora', ['class' => 'agregaformato btn btn-sm btn-primary btnnew3 btn-block rounded-pill','id'=>'4']) ?>
                </div>


                <div class="col-lg-12 mt-3 d-grid text-center border-top">
                    <h5>Datos de la empresa</h5>
                </div>
                <div class="col-lg-12 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-buildings"></i></span>Nombre Comercial', ['class' => 'agregaformato btn btn-sm btn-primary btnnew2 btn-block rounded-pill','id'=>'5']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-buildings"></i></span>Razón Social', ['class' => 'agregaformato btn btn-sm btn-primary btnnew2 btn-block rounded-pill','id'=>'6']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-buildings"></i></span>RFC', ['class' => 'agregaformato btn btn-sm btn-primary btnnew2 btn-block rounded-pill','id'=>'7']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-telephone"></i></span>Teléfono', ['class' => 'agregaformato btn btn-sm btn-primary btnnew2 btn-block rounded-pill','id'=>'8']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-envelope"></i></span>Correo', ['class' => 'agregaformato btn btn-sm btn-primary btnnew2 btn-block rounded-pill','id'=>'9']) ?>
                </div>

                <div class="col-lg-12 mt-3 d-grid text-center border-top">
                    <h5>Datos del trabajador</h5>
                </div>
                <div class="col-lg-12 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-person"></i></span>Nombre Completo', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'10']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-columns-gap"></i></span>Área', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'11']) ?>
                </div>
                <div class="col-lg-6 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-person-square"></i></span>Puesto', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'12']) ?>
                </div>
                <div class="col-lg-8 p-1 mt-1 d-grid pr-1">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-calendar-date"></i></span>Fecha de Nacimiento', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'13']) ?>
                </div>
                <div class="col-lg-4 p-1 mt-1 d-grid pl-1">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-cake"></i></span>Edad', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'14']) ?>
                </div>
                <div class="col-lg-4 p-1 mt-1 d-grid">
                    <?= Html::button('<span class="mx-2"><i class="bi bi-gender-ambiguous"></i></span>Sexo', ['class' => 'agregaformato btn btn-sm btn-primary btnnew4 btn-block rounded-pill','id'=>'15']) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8 p-3">

            <div class="row text-center color9">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-6">
                    <h5>Vista Previa</h5>
                </div>
                <div class="col-lg-3">
                    <?= Html::button('Borrar Todo <span class="mx-2"><i class="bi bi-trash"></i></span>', ['class' => 'agregaformato btn btn-sm btn-danger bgcolor6 rounded-pill','id'=>'clean']) ?>
                </div>
            </div>
            <div class="row my-3 p-5 border30 bg-customlight border-custom p-2 shadow text-justify">
                <div class="text-dark text-justify" id='textoprevio'><?php echo $model->texto_consentimiento;?></div>
            </div>
            <div class="row" style="display:none;">
                <?= $form->field($model, 'texto_consentimiento')->textarea(['rows' => 20,'placeholder'=>'Escriba el texto del consentimiento...'])->label(false); ?>
            </div>
            <div class="row my-2 boxtitle2 p-2 rounded-4 mt-4">
                <div class="col-lg-12 small">
                    <i>Los datos marcados entre llaves <span class="px-2 color11">{ }</span> serán reemplazados por la
                        información indicada al momento de llenar el consentimiento.</i>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-lg-4 offset-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>