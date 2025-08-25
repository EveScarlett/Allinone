<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Puestostrabajo;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Trabajadores;
use unclead\multipleinput\MultipleInput;

/** @var yii\web\View $this */
/** @var app\models\Vacantes $model */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vacantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
$remoto = ['1' => 'SI', '2' => 'NO', '3' => 'PARCIAL'];
$status = ['0'=>'Baja','1'=>'Activo','2'=>'Pospuesto'];
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
$emoji ='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
<path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
<path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
</svg>';
?>
<div class="vacantes-view">

    <?php $form = ActiveForm::begin(); ?>
    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <div class="row mt-5">
            <div class="col-lg-4 p-3 card bg-light shadow rounded-4">
                <div class="row m-0 p-0">
                    <div class="col-lg-12 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?= $emoji?></span>
                            Trabajadores Seleccionados
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <?php echo $form->field($model, 'aux_trabajadores')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm','style'=>'background-color:transparent;'],
                        'max' => $model->cantidad_vacantes,
                        'min' => $model->cantidad_vacantes,
                        'theme'=>'bs',
                        'id'=>'aux_trabajadores',
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
                                'name'  => 'trabajador',
                                'title'  => '',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $trabajadores,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:100%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            
                                        }'
                                    ]   
                                ],
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
                    <div class="col-lg-12 d-grid">
                        <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('id_empresa');?></label>
                        <div class="form-control"><?php echo $model->empresa->comercial?></div>
                    </div>
                    <div class="col-lg-6">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('id_puesto');?></label>
                        <div class="form-control"><?php echo $model->puesto->nombre?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-12 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('titulo');?></label>
                        <div class="form-control"><?php echo $model->titulo?></div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('descripcion');?></label>
                        <div class="form-control"><?php echo $model->descripcion?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('ubicacion');?></label>
                        <div class="form-control"><?php echo $model->ubicacion?></div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('pais');?></label>
                        <div class="form-control"><?php echo $model->pais?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('salario');?></label>
                        <div class="form-control"><?php echo $model->salario?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-12 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('remoto');?></label>
                        <div class="form-control"><?php echo $remoto[$model->remoto]?></div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-6 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('fecha_limite');?></label>
                        <div class="form-control"><?php echo $model->fecha_limite?></div>
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('status');?></label>
                        <div class="form-control"><?php echo $status[$model->status]?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12  mt-3">
                        <label for="" class="control-label"><?=$model->getAttributeLabel('cantidad_vacantes');?></label>
                        <div class="form-control"><?php echo $model->cantidad_vacantes?></div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>