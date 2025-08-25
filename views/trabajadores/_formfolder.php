<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use app\models\Areas;
use app\models\Puestostrabajo;
use app\models\Estudios;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
    
    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
    $puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
    $estudios = ArrayHelper::map(Estudios::find()->orderBy('estudio')->all(), 'id', 'estudio');
    $estudios[0] ='OTRO';
    $periodicidad = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
?>

<div class="trabajadores-form">

    <?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';
?>
    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>
    <div class="row">
        <div class="col-lg-2">
            <div class="row">
                <?php
                     $avatar = '/resources/images/'.'av5.jpg';
                     if($model->foto){
                        $avatar = '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->foto;
                     }
                     
                     $filePath =  $avatar;
                     echo '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                     width: 150px;
                     height: 150px;']).'</span>';
                ?>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-10">
                    <label for="" class="control-label">Empresa</label>
                    <div class="form-control">
                        <?php
                        if($model->empresa){
                            echo $model->empresa->comercial;
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 mt-3">
                        <label for="" class="control-label">Nombre</label>
                        <div class="form-control">
                            <?php
                        echo $model->nombre;
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-5 mt-3">
                        <label for="" class="control-label">Apellidos</label>
                        <div class="form-control">
                            <?php
                        echo $model->apellidos;
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-3">
                        <label for="" class="control-label">Sexo</label>
                        <div class="form-control">
                            <?php
                            $array_sexo = ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'];
                            if($model->sexo != null && $model->sexo != '' && $model->sexo != ' '){
                                echo $array_sexo[$model->sexo];
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col-lg-5">
                        <?= $form->field($model, 'id_puesto')->widget(Select2::classname(), [
                            'data' => $puestos,
                            'disabled' =>true,
                            'readonly' =>true,
                            'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                            'onchange' => 'cambiaPuesto(this.value,"trabajadores")'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>



        <div class="container-fluid my-5 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><i class="bi bi-clipboard"></i></span>
                        Requisitos
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $form->field($model, 'aux_estudios')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_estudios',
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
                            'label' => '',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'index color4'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'tipo',
                                'title'  => 'Tipo',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['1'=>'Médico','2'=>'Otro'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var modelo = "requerimientoempresa";
                                            var nuevo_id = id.replace("-tipo!", "-estudio");
                                           /*  console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            console.log("Nuevo id : "+nuevo_id); */
                                            cambiaTipo(nuevo_id, valor,modelo);
                                        }'
                                    ] 
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'estudio',
                                'title'  => 'Estudio', 
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $estudios,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:20%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-estudio!", "-otro");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoEstudio(nuevo_id, valor);
                                        }'
                                    ]   
                                ],  
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                            ],
                            [
                                'name'  => 'otro',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nombre Estudio'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'periodicidad',
                                'title'  => 'Periodicidad',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $periodicidad,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'fecha_documento',
                                'title'  => 'Fecha Documento',
                                'type'  => \yii\widgets\MaskedInput::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'mask' => '9999-99-99', 
                                    'options' => [ 'placeholder'=>'yyyy-mm-dd',
                                    'class'=>'form-control',
                                    'onchange'=>'
                                    
                                    var fecha = $(this).val();
                                    console.log("Estoy calculando la fecha de vencimiento: "+fecha);

                                    var fecha_documento_id = $(this).attr("id")+"!";
                                    var fecha_vencimiento_id = fecha_documento_id.replace("-fecha_documento!", "-fecha_vencimiento");
                                    var periodicidad_id = fecha_documento_id.replace("-fecha_documento!", "-periodicidad");

                                    var periodicidad = $("#"+periodicidad_id).val();
                                    calculaVencimiento(fecha_vencimiento_id,fecha_documento_id, fecha,periodicidad);
                                    '

                                    ]
                                   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:13%;'
                                ],         
                            ],
                            [
                                'name'  => 'fecha_vencimiento',
                                'title'  => 'Fecha Vencimiento',
                                'type'  => 'textInput',
                                'options'=>['readonly'=>'true','class'=>'y_centercenter color3','style'=>'border:none;'],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:10%;'
                                ],         
                            ],
                            [
                                'name'  => 'evidencia',
                                'title'  => 'Evidencia',
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ], 
                                'type'  => FileInput::classname(),
                                'options'=>['class'=>'input-etiqueta text-500 disabled',
                                'pluginOptions' => [
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    'removeClass' => 'btn btn btn-danger',
                                    'browseIcon' => '<i class="bi bi-folder"></i>',
                                    'browseLabel' =>  '',
                                    'removeIcon' => '<i class="bi bi-trash"></i>',
                                    'removeLabel' =>  ''
                                ]
                                ],
                            ],
                            [
                                'name'  => 'doc',
                                'title'  => 'Doc',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'pdf text-center',
                                    'style' =>''
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],   
                                'value'  => function ($model,$indice)
                                { 
                                    $pdf = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                    
                                    if(isset($model['doc']) && $model['doc'] != ''){
                                       
                                        $filePath = $model['doc'];
                                        $pdf = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                            
                                        return $pdf;
                                            
                                    }
                                },
                            ],
                            [
                                'name'  => 'conclusion',
                                'title' => '',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'display:none;',
                                    'placeholder'=>'Conclusión Estudio'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'status_show',
                                'title'  => 'Status',
                                'type'  => 'static',
                                'options' =>[
                                    'class' => 'pdf text-center',
                                    'style' =>''
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],   
                                'value'  => function ($model,$indice)
                                { 
                                    $ret = '';
                                    $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';
                                    
                                    if(isset($model['status_show'])){
                                       
                                        if($model['status_show'] == 1){
                                            $ret = '<span class="badge bgcumple m-1 font14 text-light text-uppercase">Vigente</span>';
                                        } else  if($model['status_show'] == 2){
                                            $ret = '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">Vencido</span>';
                                        } else if($model['status_show'] == 0){
                                            $ret = '<span class="badge bgpendiente m-1 font14 text-light text-uppercase">Indefinido</span>';
                                        }
                                            
                                        return $ret;
                                            
                                    }
                                },
                            ],
                            [
                                'name'=>'fecha_apartir',
                                'title'  => 'Req. Desde',
                                'type'=>'textInput',
                                'options'=>['readonly'=>'true','style'=>'border:none;'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
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
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 d-grid">
                <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>