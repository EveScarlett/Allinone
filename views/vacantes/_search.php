<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Trabajadores;

/** @var yii\web\View $this */
/** @var app\models\VacantesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
      
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$ids_empresas = [];

foreach($empresas as $key=>$item){
    if(!in_array($key, $ids_empresas)){
        array_push($ids_empresas, $key);
    }
}
$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
?>

<div class="vacantes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-lg-7">
            <?= $form->field($model, 'trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => yii::t('app', 'BUSCAR TRABAJADOR--'),
                    'multiple' => false,
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    ])->label('Afinidad Trabajador'); ?>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Buscar').'<span class="px-2"><i class="bi bi-search"></i></span>', ['class' => 'btn btn-primary','id'=>'buscarsearch']) ?>
                <!-- <?= Html::resetButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
</svg>', ['class' => 'btn btn-outline-secondary','id'=>'limpiarsearch']) ?> -->
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>