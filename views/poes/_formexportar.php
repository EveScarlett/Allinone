<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Puestostrabajo;
use kartik\select2\Select2;
use kartik\date\DatePicker;

use app\models\Servicios;
use app\models\TipoServicios;
/** @var yii\web\View $this */
/** @var app\models\Vacantes $model */
/** @var yii\widgets\ActiveForm $form */
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
    font-size:10px;
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
    font-size:10px;
}
");
?>

<div class="vacantes-form">

    <?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');

$estudiossearch = ArrayHelper::map(Servicios::find()->orderBy('nombre')->all(), 'id', 'nombre');

$categoria = ArrayHelper::map(TipoServicios::find()->where(['status'=>1])->orderBy('nombre')->all(), 'id', 'nombre');
$entrega = [1=>'ENTREGA COMPLETA',0=>'ENTREGA PENDIENTE'];
$documento = [1=>'CON EVIDENCIA',2=>'SIN EVIDENCIA'];
?>

    <?php $form = ActiveForm::begin(['id'=>'formSMO']); ?>

    <div class="row my-2 boxtitle2 p-2 rounded-4">
        <div class="col-lg-12">
            Seleccione los filtros de busqueda que desea realizar, aplicar filtros ayudará a realizar más rapido la
            carga de información
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'src_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'src_trabajador')->textInput([]) ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_condicion')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'src_area')->textInput([]) ?>
        </div>
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'src_puesto')->textInput([]) ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_anio')->textInput(['type'=>'number']) ?>
        </div>
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'src_fecha')->widget(DatePicker::classname(), [
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
                         ]]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mt-3">
            <?= $form->field($model, 'src_estudio')->widget(Select2::classname(), [
                    'data' =>$estudiossearch,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true
                    ],
                    ]) ?>
        </div>
        <div class="col-lg-4 mt-3">
            <?= $form->field($model, 'src_categoria')->widget(Select2::classname(), [
                    'data' => $categoria,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_diagnostico')->widget(Select2::classname(), [
                    'data' => ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'BIEN','2'=>'REGULAR','3'=>'MAL'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_evaluacion')->widget(Select2::classname(), [
                    'data' => ['100'=>'FALTA','0'=>'PENDIENTE','1'=>'IGUAL','2'=>'MEJOR','3'=>'PEOR','4'=>'N/A'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_entrega')->widget(Select2::classname(), [
                    'data' => $entrega,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2 mt-3">
            <?= $form->field($model, 'src_evidencia')->widget(Select2::classname(), [
                    'data' =>$documento,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Realizar Busqueda <i class="bi bi-search"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'enviarForm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>