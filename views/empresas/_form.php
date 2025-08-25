<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use app\models\ProgramaSalud;
use yii\helpers\ArrayHelper;


use app\models\Paises;
use app\models\Lineas;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', 'nombre');
$programas[0] ='OTRO';
$horarios = [
    '1'=> '12:00 am',
    '2'=> '1:00 am',
    '3'=> '2:00 am',
    '4'=> '3:00 am',
    '5'=> '4:00 am',
    '6'=> '5:00 am',
    '7'=> '6:00 am',
    '8'=> '7:00 am',
    '9'=> '8:00 am',
    '10'=> '9:00 am',
    '11'=> '10:00 am',
    '12'=> '11:00 am',
    '13'=> '12:00 pm',
    '14'=> '1:00 pm',
    '15'=> '2:00 pm',
    '16'=> '3:00 pm',
    '17'=> '4:00 pm',
    '18'=> '5:00 pm',
    '19'=> '6:00 pm',
    '20'=> '7:00 pm',
    '21'=> '8:00 pm',
    '22'=> '9:00 pm',
    '23'=> '10:00 pm',
    '24'=> '11:00 pm',
];

$tipomails = ['1'=>'Inicio Operación Maquinaria','2'=>'Fin Operación Maquinaria','3'=>'Inicio & Fin Operación Maquinaria'];
$paises = ArrayHelper::map(Paises::find()->orderBy('pais')->all(), 'id', 'pais');

$iconpais = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
            <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z"/>
            </svg>';

$iconniveles = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
</svg>';
?>

<div class="empresas-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nivel1')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nivel2')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nivel3')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nivel4')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>

    <div class="row border30">
        <div class="col-lg-2">
            <div class="row">
                <?php
                     $avatar = '/resources/images/'.'company.jpg';
                     if($model->logo){
                        $avatar = '/resources/Empresas/'.$model->id.'/'.$model->logo;
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
                    ])->label('Logo'); ?>
            </div>

        </div>
        <div class="col-lg-10">
            <div class="row my-3">
                <div class="col-lg-6">
                    <?= $form->field($model, 'razon')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'comercial')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-lg-3">
                    <?= $form->field($model, 'abreviacion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3" style="display:none;">
                    <?= $form->field($model, 'rfc')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'pais')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'estado')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
            </div>
            <div class="row  mt-5 my-3">
                <div class="col-lg-4">
                    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row mt-5 my-3">
                <div class="col-lg-8">
                    <?= $form->field($model, 'direccion')->textArea(['rows'=>'2','maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
                 <div class="col-lg-4">
                    <?= $form->field($model, 'correo_privacidad')->textInput(['maxlength' => true]) ?>
                </div>
            </div>


            <div class="row mt-5">
                 <div class="col-lg-8">
                    <?= $form->field($model, 'actividad')->textArea(['rows'=>'2','maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
                </div>
            </div>

        </div>
    </div>


    <div class="row my-3 border30 border30 bg-customlight border-custom p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 bgcolor6 p-1 rounded-3 text-light my-3">
                <label class="">
                    <span class="mx-2"><?php echo $iconniveles;?></span>
                    Estructura Organizacional
                </label>
            </div>
        </div>
        <div class="row my-2 boxtitle2 p-2 rounded-4">
            <div class="col-lg-12">
                Ingrese cuantos niveles hacia abajo desea establecer en la estructura organizacional y proporcione el
                <span class="font500">nombre o etiqueta para cada nivel</span>.<br><br>
                <span class="font500">Los trabajadores, asi como las consultas y otra información médica estarán
                    enlazados a la estructura que defina.</span>
            </div>
        </div>

        <div class="row my-2">
            <dic class="col-lg-4">
                <div class="row">
                    <div class="col-lg-10">
                        <?= $form->field($model, 'cantidad_niveles')->widget(Select2::classname(), [ 
                        'data' => [1=>'1 NIVEL',2=>'2 NIVELES',3=>'3 NIVELES',4=>'4 NIVELES'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off',
                        'onchange'=>'changeCantidad(this.value)'],
                        'pluginOptions' => [
                        
                         ],]) ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-10">
                        <?= $form->field($model, 'mostrar_nivel_pdf')->widget(Select2::classname(), [ 
                        'data' => [1=>'Nombre Comercial Empresa',2=>'Niveles Inferiores'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Seleccione ...', 'multiple' => false, 'autocomplete' => 'off',
                        'onchange'=>''],
                        'pluginOptions' => [
                        
                         ],]) ?>
                    </div>
                </div>
            </dic>

            <?php
            $showetiqueta1 = 'none';
            $showetiqueta2 = 'none';
            $showetiqueta3 = 'none';
            $showetiqueta4 = 'none';

            if($model->cantidad_niveles >= 1){
                 $showetiqueta1 = 'block';
            }
            if($model->cantidad_niveles >= 2){
                 $showetiqueta2 = 'block';
            }
            if($model->cantidad_niveles >= 3){
                 $showetiqueta3 = 'block';
            }
            if($model->cantidad_niveles >= 4){
                 $showetiqueta4 = 'block';
            }
            ?>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12 mt-3" id="showetiqueta1" style="display:<?=$showetiqueta1?>;">
                        <?= $form->field($model, 'label_nivel1')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','readonly'=>true,'class'=>'form-control bg-disabled'])->label($model->getAttributeLabel('label_nivel1')); ?>
                    </div>
                    <div class="col-lg-12 mt-3" id="showetiqueta2" style="display:<?=$showetiqueta2?>;">
                        <?= $form->field($model, 'label_nivel2')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label($model->getAttributeLabel('label_nivel2')); ?>
                    </div>
                    <div class="col-lg-12 mt-3" id="showetiqueta3" style="display:<?=$showetiqueta3?>;">
                        <?= $form->field($model, 'label_nivel3')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label($model->getAttributeLabel('label_nivel3')); ?>
                    </div>
                    <div class="col-lg-12 mt-3" id="showetiqueta4" style="display:<?=$showetiqueta4?>;">
                        <?= $form->field($model, 'label_nivel4')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label($model->getAttributeLabel('label_nivel4')); ?>
                    </div>
                </div>
            </div>


            <div class="col-lg-4">
                <div class="container rounded-2 shadow p-3 font12">
                    <h3 class="font14 font500">Ejemplo</h3>
                    <p class="color11">
                        Cantidad de Niveles: 3
                    </p>
                    <p class="color11 mt-2">
                        Nivel 1: <span class="text-dark">Paises</span>
                    </p>
                    <p class="color11 mt-2">
                        Nivel 2: <span class="text-dark">Estados o Regiones</span>
                    </p>
                    <p class="color11 mt-2">
                        Nivel 3: <span class="text-dark">Ciudades</span>
                    </p>
                </div>
            </div>

        </div>


    </div>



    <div class="row my-3 border30 border30 bg-customlight border-custom p-4">
        <div class="col-lg-6 my-3">
            <?php echo $form->field($model, 'aux_nivel1')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_nivel1',
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
                                'name'  => 'id_pais',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2">'.$iconpais.'</span>Nivel 1: '.$model->label_nivel1.'</h1>',   
                                'headerOptions' => [
                                    'class' => '',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $paises,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;', 'disabled' =>false,
                                    'readonly' =>true],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ] 
                                ],
                                
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
        </div>

        <div class="col-lg-4 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-layers-half"></i></span>Lineas</h1>
            <div class="row">
                <?php
                $ret = '';
                $total = count($model->lineas);
                $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                if($model->lineas){
                    foreach($model->lineas as $key=>$data){
                        $ret .= '<span class="badge rounded-pill font10 bgcolor9"><i class="bi bi-dot"></i>'.$data->linea.'</span>';
                    }
                }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_lineas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_lineas',
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
                                'name'  => 'linea',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-layers-half"></i></span>Lineas</h1>',   
                                'headerOptions' => [
                                    'class' => '',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>

        </div>

        <div class="col-lg-4 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-geo-alt-fill"></i></span>Ubicaciones</h1>
            <div class="row">
                <?php
                $ret = '';
                $total = count($model->ubicaciones);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->ubicaciones){
                        foreach($model->ubicaciones as $key=>$ubicacion){
                            $ret .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-geo-alt-fill"></i>'.$ubicacion->ubicacion.'</span>';
                        }
                    }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_ubicaciones')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_ubicaciones',
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
                                'name'  => 'ubicacion',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-geo-alt-fill"></i></span>Ubicaciones</h1>',   
                                'headerOptions' => [
                                    'class' => '',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>
        </div>

        <div class="col-lg-4 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-columns-gap"></i></span>Áreas</h1>
            <div class="row">
                <?php
                $ret = '';
                $total = count($model->areas);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->areas){
                        foreach($model->areas as $key=>$area){
                            $ret .= '<span class="badge rounded-pill bg-light color6 font10">'.$area->area.'</span>';
                        }
                    }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_areas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_areas',
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
                                'name'  => 'area',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-columns-gap"></i></span>Áreas</h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],   
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>
        </div>

        <div class="col-lg-4 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-star-fill"></i></span>Consultorios</h1>
            <div class="row">
                <?php
                $ret = '';
                 $total = count($model->consultorios);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->consultorios){
                        foreach($model->consultorios as $key=>$consultorio){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$consultorio->consultorio.'</span>';
                        }
                    }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_consultorios')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_consultorios',
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
                                'name'  => 'consultorio',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-star-fill"></i></span>Consultorios</h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],    
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>
        </div>

        <div class="col-lg-6 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-alarm"></i></span>Turnos</h1>
            <div class="row">
                <?php
                $ret = '';
                $total = count($model->turnos);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->turnos){
                        foreach($model->turnos as $key=>$turno){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$turno->turno.'</span>';
                        }
                    }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_turnos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_turnos',
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
                                'name'  => 'turno',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-alarm"></i></span>Turnos</h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'orden',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-sort-numeric-down"></i></span>Orden</h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:30%;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'#','onkeyup' => ''],
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>
        </div>

        <div class="col-lg-6 my-3" style="display:none;">
            <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i
                        class="bi bi-heart"></i></span>Programas de Salud</h1>
            <div class="row">
                <?php
                $ret = '';
                $total = count($model->programas);
                    $ret .= '<div class="color5 font10">Registros: <span class="color3">'.$total.'</span></div>';
                    if($model->programas){
                        foreach($model->programas as $key=>$programa){
                            $ret.= '<span class="badge rounded-pill bg-light text-dark font10">'.$programa->nombre.'</span>';
                        }
                    }
                echo $ret;
                ?>
            </div>
            <div style="display:none;">
                <?php echo $form->field($model, 'aux_programas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_programas',
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
                                'name'  => 'programa',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-heart"></i></span>Programas de Salud</h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $programas,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:50%;'],
                                    
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-programa!", "-otroprograma");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoEstudio(nuevo_id, valor);
                                        }'
                                    ]   
                                ],
                            ],
                            [
                                'name'  => 'otroprograma',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nuevo Programa'
                                    ,'onkeyup' => 'converToMayus(this);'
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
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
            </div>
        </div>

    </div>



    <div class="row my-3 border30 border30 bg-customlight border-custom p-4" style="display:none;">

        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo '<i class="bi bi-calendar3-week"></i>';?></span>
                    Horario de Atención Empresa
                </label>
            </div>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'horario')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>

    </div>


    <div class="row my-3 border30 border30 bg-customlight border-custom p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo '<i class="bi bi-envelope-fill"></i>';?></span>
                    Mails Notificaciones
                </label>
            </div>
        </div>
        <div class="col-lg-12 my-3">
            <?php echo $form->field($model, 'aux_mails')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 20,
                        'theme'=>'bs',
                        'id'=>'aux_mails',
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
                                'name'  => 'mail',
                                'title'  => 'Ingrese Mail',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:65%;'
                                ], 
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>' --'],
                            ],
                            [
                                'name'  => 'tipo_mail',
                                'title'  => 'Tipo de Buzón',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $tipomails,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:35%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                           
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
        </div>

    </div>

    <div class="row my-4">
        <div class="col-lg-4">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['0'=>'Baja','1'=>'Activo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>