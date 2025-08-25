<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use unclead\multipleinput\MultipleInput;


use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Turnos;
use app\models\Riesgos;


/** @var yii\web\View $this */
/** @var app\models\Ubicaciones $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
?>
<?php
$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');

$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', function($data){
        $ret = 'PS - '.$data['nombre'];
        
        return $ret;
});

$kpis = [
    'A'=>'ACCIDENTES',
    'B'=>'NUEVOS INGRESOS',
    'C'=>'INCAPACIDADES',
    'E'=>'POES'
];

$kpis_mixed = $kpis + $programas;

//dd($model,$kpis_mixed);

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
//dd($model);
?>

<div class="ubicaciones-form p-3">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
        <?php if($empresa): ?>
        <span class="font12 color6 font600">
            <?php
            if($empresa){
                echo $empresa->comercial;
            }
            ?>
        </span>
        <?php endif; ?>
        <?php if($nivel_1): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_1){
                echo $nivel_1->pais->pais;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_2): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_2){
                echo $nivel_2->nivelorganizacional2;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_3): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_3){
                echo $nivel_3->nivelorganizacional3;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_4): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_4){
                echo $nivel_4->nivelorganizacional4;
            }
            ?>
        </span>
        <?php endif; ?>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-10">
            <h1 class="title1">
                QTY Trabajadores Activos:
                <span class="p-2 bgcolor2 rounded-3">
                    <?php
                echo $model->qty_trabajadores;
                ?>
                </span>
            </h1>
        </div>
        <div class="col-lg-2 p-2 rounded-3 bgcolor14">
            <label class="font11">Cumplimiento</label>
            <div class="title1">
            <?php
            echo(number_format($model->kpi_cumplimiento, 2, '.', ',')).'%';
            ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->field($model, 'aux_areas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
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
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-columns-gap"></i></span>Áreas<span class="font11 mx-2 text-dark">Items: '.$qty_areas.'</span></h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:90%;'
                                ],   
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'=>'qty_trabajadores',
                                'title'  => 'QTY T.',
                                'type'  => 'static',
                                'value'  => function ($model,$indice)
                                { 
                                    $qty = '';
                                    
                                    if(isset($model['qty_trabajadores']) && $model['qty_trabajadores'] != '' && $model['qty_trabajadores'] != null){
                                        
                                    }
                                    $qty = '<span class="p-2 rounded-3 bgcolor2">'.$model['qty_trabajadores'].'</span>';

                                    return $qty;
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
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


        <div class="col-lg-12">
            <?php echo $form->field($model, 'aux_puestos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
                        'theme'=>'bs',
                        'id'=>'aux_puestos',
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
                                'name'  => 'puesto',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-columns-gap"></i></span>Puesto de Trabajo<span class="font11 mx-2 text-dark">Items: '.$qty_puestos.'</span></h1>',   
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:50%;'
                                ],   
                                'options'=>['class'=>'input-etiqueta text-500','placeholder'=>'Describa Cual --','onkeyup' => 'converToMayus(this);'],
                            ],
                            [
                                'name'  => 'riesgos',
                                'title'  => 'Riesgos', 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $riesgos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:30%;', 'multiple' => true],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                           
                                        }'
                                    ] ,
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'dropdownParent' => '#modal-contenido'
                                    ],  
                                ], 
                                
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:30%;'
                                ], 
                            ],
                            [
                                'name'=>'qty_trabajadores',
                                'title'  => 'QTY T.',
                                'type'  => 'static',
                                'value'  => function ($model,$indice)
                                { 
                                    $qty = '';
                                    
                                    if(isset($model['qty_trabajadores']) && $model['qty_trabajadores'] != '' && $model['qty_trabajadores'] != null){
                                        
                                    }
                                    
                                    $qty = '<span class="p-2 rounded-3 bgcolor2">'.$model['qty_trabajadores'].'</span>';

                                    return $qty;
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
                                ], 
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>''],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                        ]])->label(false);?>
        </div>

        <?php
        $icon_kpi = '<img src="resources/images/dashboard.png" class="px-2" height="20px" width="auto"/>';
        ?>

        <div class="col-lg-12">
            <?php echo $form->field($model, 'aux_kpis')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
                        'theme'=>'bs',
                        'id'=>'aux_kpis',
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
                                'name'  => 'kpi',
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-1">'.$icon_kpi.'</span>KPIs<span class="font11 mx-2 text-dark">Items: '.$qty_kpis.'</span></h1>', 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $kpis_mixed,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>''],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            
                                            console.log("valor: "+valor+" | id: "+id);
                                            //nuevoEstudio(nuevo_id, valor);
                                        }'
                                    ] ,
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                        'dropdownParent' => '#modal-contenido'
                                    ],  
                                ], 
                                
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:90%;'
                                ], 
                            ],
                            [
                                'name'=>'qty_trabajadores',
                                'title'  => 'QTY T.',
                                'type'  => 'static',
                                'value'  => function ($model,$indice)
                                { 
                                    $qty = '';
                                    
                                    if(isset($model['qty_trabajadores']) && $model['qty_trabajadores'] != '' && $model['qty_trabajadores'] != null){
                                        
                                    }
                                    
                                    $qty = '<span class="p-2 rounded-3 bgcolor2">'.$model['qty_trabajadores'].'</span>';

                                    return $qty;
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
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

        <div class="col-lg-12" style="display:none;">
            <?php echo $form->field($model, 'aux_consultorios')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
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

        <div class="col-lg-12" style="display:none;">
            <?php echo $form->field($model, 'aux_programas')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
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
                                'title'  => '<h1 class="title2 boxtitle p-1 rounded-3 color3 my-3"><span class="mx-2"><i class="bi bi-bar-chart-fill"></i></span>KPI</h1>',   
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
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                        'dropdownParent' => '#modal-contenido'
                                    ],
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

        <div class="col-lg-12" style="display:none;">
            <?php echo $form->field($model, 'aux_turnos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 50,
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


    <div class="row mt-5">
        <div class="col-lg-12 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>