<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\select2\Select2;
use kartik\sortable\Sortable;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
$listado_estudios = [];

if($model->serviciosactivos){
    foreach($model->serviciosactivos as $key=>$servicio){
        
        $estudio['content']=$servicio->nombre;
        array_push($listado_estudios, $estudio);
    }
    //dd($listado_estudios);
}

?>
<div class="tipo-servicios-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>


    <div class="row my-3" style="display:none;">
        <div class="col-lg-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-lg-8">
            <?php
            if(count($listado_estudios) == 0){
                echo 'SIN ESTUDIOS';
            }
            ?>
            <?= Sortable::widget([
            'itemOptions'=>['class'=>'elemento rounded-2','style'=>'background-color: '.$model->logo.'4b !important;'],
            'items'=>$listado_estudios
            ]); ?>
        </div>

    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarEstudios']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>