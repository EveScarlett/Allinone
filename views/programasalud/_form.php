<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Empresas;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\ProgramaSalud $model */
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

<div class="programa-salud-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','placeholder'=>'Ingrese Nombre del Programa'])->label(); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 3,'maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
        </div>
        <div class="col-lg-2">
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

    <div class="row-my-3">
        <div class="col-lg-12" style="display:<?php echo $showempresa?>;">
            <?php
                echo $form->field($model, 'empresas_select2')->widget(Select2::classname(), [ 
                    'data' => $empresas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [    
                    ],])->label(); 
                ?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>