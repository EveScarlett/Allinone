<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Empresas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Insumos;
use app\models\Consultorios;

/** @var yii\web\View $this */
/** @var app\models\Insumostockmin $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$id_empresas = [];
foreach($empresas as $key=>$item){
    if(!in_array($key, $id_empresas)){
        array_push($id_empresas, $key);
    }
}
     
$insumos = ArrayHelper::map(Insumos::find()->where(['in','id_empresa',$id_empresas])->andWhere(['tipo'=>$tipo])->orderBy('nombre_comercial')->all(), 'id', function($model){
    return $model['nombre_comercial'].' '.$model->nombre_generico;
});

$consultorios = ArrayHelper::map(Consultorios::find()->where(['in','id_empresa',$id_empresas])->orderBy('consultorio')->all(), 'id','consultorio');
            
?>

<div class="insumostockmin-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-3 mt-5">
        <div class="col-lg-8">
            <?= $form->field($model, 'id_insumo')->widget(Select2::classname(), [
                    'data' => $insumos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaInsumo(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'id_consultorio')->widget(Select2::classname(), [
                    'data' => $consultorios,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>
    <div class="row my-3 mt-5">
        <div class="col-lg-4">
            <?= $form->field($model, 'stock')->textInput(['type'=>'number','onChange'=>'cambiaStockmin(this.value)','maxlength' => true,'placeholder'=>'Ingrese Stock']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'stock_unidad')->textInput(['type'=>'number','readonly'=>true,'maxlength' => true,'placeholder'=>'Ingrese Stock Mínimo']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'cantidad_caja')->textInput(['type'=>'number','readonly'=>true,'maxlength' => true,'placeholder'=>'Ingrese Stock Mínimo']) ?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>