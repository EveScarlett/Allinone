<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Areas;
use app\models\Puestostrabajo;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use app\models\ProgramaSalud;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\TipoServicios;
use app\models\Servicios;

/** @var yii\web\View $this */
/** @var app\models\HccOhc $model */
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
    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
    <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
  </svg>';
    ?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$programas = [];

if(isset($model->id_empresa)){
    $empresa = Empresas::find()->where(['id'=>$model->id_empresa])->one();
    if($empresa->programas){
        $programas = ArrayHelper::map($empresa->programas, 'id', 'nombre');
    }
}

$trabajadores = ArrayHelper::map(Trabajadores::find()->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
$tipoexamen = ['1'=>'NUEVO INGRESO','2'=>'PRIMERA VEZ','3'=>'PERIODICO','4'=>'PREEXISTENTE','5'=>'SALIDA'];

$tipos = ArrayHelper::map(TipoServicios::find()->orderBy('nombre')->all(), 'id', 'nombre');
$estudios = ArrayHelper::map(Servicios::find()->orderBy('nombre')->all(), 'id', 'nombre');
$estudios[0] ='OTRO';
?>

<div class="hcc-ohc-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>


    <div class="container-fluid my-3 border30 bg-light p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Estudios Complementarios
                </label>
            </div>
        </div>
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
                                'name'  => 'categoria',
                                'title'  => 'Categoria',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $tipos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-categoria!", "-otracategoria");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoEstudio2(nuevo_id, valor);
                                        }'
                                    ] 
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top;'
                                    ],          
                            ],
                            [
                                'name'  => 'otracategoria',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none',
                                    'placeholder'=>'Nombre Categoria'
                                    ,'onkeyup' => 'converToMayus(this);'
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
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-estudio!", "-otroestudio");
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
                                'name'  => 'otroestudio',
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
                                'title'  => 'Conclusión',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['1'=>'BUENO','2'=>'REGULAR','3'=>'MALO'],
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
                                'name'  => 'comentarios',
                                'title' => 'Comentarios Clínicos',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'Comentario Estudio'
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


    
    <div class="container-fluid my-3 border30 bg-light p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Conclusión
                </label>
            </div>
        </div>
        <div class="col-lg-12">
            <?php
            $conclusion_hc = [
                '1'=>'SANO Y APTO',
                '2'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                '3'=>'APTO TEMPORAL',
                '4'=>'PENDIENTE',
                '5'=>'NO APTO',
            ];
            ?>
            <label for="" class="control-label">Conclusión Historia Clínica</label>
            <label for="" class="form-control">
                <?php 
                if($model->conclusion != null && $model->conclusion != '' && $model->conclusion != ' '){
                    echo $conclusion_hc[$model->conclusion];
                }
                ?>
            </label>
        </div>
        <div class="col-lg-12 mt-3">
            <?= $form->field($model, 'conclusion_cal')->widget(Select2::classname(), [
                    'data' => [
                        '1'=>'SANO Y APTO PARA EL PUESTO',
                        '2'=>'NO APTO',
                        '3'=>'REQUIERE MEJORAR SALUD, APTO PARA EL PUESTO',
                        ],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
        <div class="col-lg-12">
            <label for="" class="color11">Una vez guardado se cerrará la Historia Clínica y no podrá ser editada</label>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>