<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\select2\Select2;
use kartik\sortable\Sortable;
use app\models\TipoServicios;

/** @var yii\web\View $this */
/** @var app\models\TipoServicios $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
$listado_categorias = [];

$categorias = TipoServicios::find()->where(['status'=>1])->orderBy(['orden'=>SORT_ASC])->all();
if($categorias){
    foreach($categorias as $key=>$categoria){
        
        $categ['content']=$categoria->nombre;
        array_push($listado_categorias, $categ);
    }
}
//dd($listado_categorias);
?>
<div class="tipo-servicios-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>


    <div class="row my-3">
        <div class="col-lg-8">
            <?php
            if(count($listado_categorias) == 0){
                echo 'SIN ESTUDIOS';
            }
            ?>
            <?= Sortable::widget([
            'itemOptions'=>['class'=>'elemento rounded-2','style'=>'background-color: #362FD94b !important;'],
            'items'=>$listado_categorias
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