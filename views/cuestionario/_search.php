<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Areascuestionario;
use app\models\Preguntas;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\field\FieldRange;

/** @var yii\web\View $this */
/** @var app\models\CuestionarioSearch $model */
/** @var yii\widgets\ActiveForm $form */
$preguntas = ArrayHelper::map(Preguntas::find()->where(['in','id_tipo_cuestionario',[4]])->orderBy('pregunta')->all(), 'id', 'pregunta');

$factores = ArrayHelper::map(Areascuestionario::find()->where(['in','id_pregunta',[30,31,32]])->orderBy('nombre')->all(), 'id', function($model) use ($preguntas ){
    $ret = $model['nombre'];
    if($model['id_pregunta'] != '32'){
        $ret .=' - '.$preguntas[$model['id_pregunta']].' (cm)';
    }
    return $ret;
});

/* $index = 1;
foreach($factores as $key=>$factor) {
    $factores[$key] = $index.') '.$factor;
    $index++;
} */
//dd($factores);
?>

<div class="cuestionario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index','tipo'=>$tipo],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-lg-2">
            <?= $form->field($model, 'filtro1')->widget(Select2::classname(), [
                    'data' => $factores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'multiple' => false,
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-1 px-1">
            <?= $form->field($model, 'rango1desde')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-1 px-0">
            <?= $form->field($model, 'rango1hasta')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'filtro2')->widget(Select2::classname(), [
                    'data' => $factores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'multiple' => false,
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-1 px-1">
            <?= $form->field($model, 'rango2desde')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-1 px-0">
            <?= $form->field($model, 'rango2hasta')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'filtro3')->widget(Select2::classname(), [
                    'data' => $factores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'multiple' => false,
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-1 px-1">
            <?= $form->field($model, 'rango3desde')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-1 px-0">
            <?= $form->field($model, 'rango3hasta')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'rango4')->widget(Select2::classname(), [
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-1 px-1">
            <?= $form->field($model, 'rango5desde')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-1 px-0 y_centercenter">
            <?= $form->field($model, 'rango5hasta')->textInput(['type'=>'number','maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Buscar').'<span class="px-2"><i class="bi bi-search"></i></span>', ['class' => 'btn btn-primary','id'=>'buscarsearch']) ?>
                <?= Html::resetButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
</svg>', ['class' => 'btn btn-outline-secondary','id'=>'limpiarsearch']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>