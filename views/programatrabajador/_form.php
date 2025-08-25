<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

use app\models\ProgramaSalud;

/** @var yii\web\View $this */
/** @var app\models\Trabajadores $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
  <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/>
  <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z"/>
</svg>';


$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', 'nombre');
?>

<div class="trabajadores-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-3">
            <?php
            if($model->scenario == 'update'){
                $worker = $model;
                $retworker = '';

                $foto = '';

                $status_trabajador = '';

                if(isset($worker->foto) && $worker->foto != ""){
                    $avatar = '/resources/images/'.'av5.jpg';
                    if($worker->foto){
                        $avatar = '/resources/Empresas/'.$worker->id_empresa.'/Trabajadores/'.$worker->id.'/Documentos/'.$worker->foto;
                    }
                     
                    $filePath =  $avatar;
                    $foto = '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                    width: 150px;
                    height: 150px;']).'</span>';
                }

                $empresa = '';
                if($worker->empresa){
                    $empresa = $worker->empresa->comercial;

                    if($worker->nivel1){
                        $empresa .= ' / '.$worker->nivel1->pais->pais;
                    }

                    if($worker->nivel2){
                        $empresa .= ' / '.$worker->nivel2->nivelorganizacional2;
                    }

                    if($worker->nivel3){
                        $empresa .= ' / '.$worker->nivel3->nivelorganizacional3;
                    }

                    if($worker->nivel4){
                        $empresa .= ' / '.$worker->nivel4->nivelorganizacional4;
                    }
                }


                if($worker->status == 1){
                    $status_trabajador = '<span class="mx-1 color7"><i class="bi bi-circle-fill"></i></span>Status Activo';
                } else if($worker->status == 3){
                    $status_trabajador = '<span class="mx-1 color14"><i class="bi bi-circle-fill"></i></span>Status NI';
                } else if($worker->status == 5){
                    $status_trabajador = '<span class="mx-1 color11"><i class="bi bi-circle-fill"></i></span>Status Baja';
                }


                $retworker .= '<div class="row mt-3 bordercolor3">
                <div class="col-lg-12 p-2 card text-center">
                    <div class="row">
                        '.$foto.'
                    </div>
                    <div class=" p-3">
                        <h2 class="title1">'.$worker->nombre.' '.$worker->apellidos.'</h2>
                        <h3 class="color3 font500">'.$empresa.'</h3>
                        <h5>'.$status_trabajador.'</h5>
                        <h5 class="my-2" style="display:none;">Cumplimiento: <span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font500 text-end">'.$worker->porcentaje_cumplimiento.' %</span></h5>
                        <h6 class="color6">';

                if(isset($worker->puesto)){
                    $retworker .= '<i class="bi bi-briefcase-fill"></i> Puesto: '.$worker->puesto->nombre;
                }
                if(isset($worker->puesto_contable)){
                    $retworker .= '<br><span class=""><i class="bi bi-briefcase-fill"></i> Puesto Contable: '.$worker->puesto_contable.'</span>';
                }


                $retworker .= '<div class="col-lg-12">';
                if(isset($worker->sexo) && $worker->sexo != null && $worker->sexo != ''){
                    $sexos = ['1'=>'Hombre','2'=>'Mujer','3'=>'Otro'];
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$sexos[$worker->sexo].'</span>';
                }
                if(isset($worker->edad) && $worker->edad != null && $worker->edad != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$worker->edad.' Años</span>';
                }
                if(isset($worker->celular) && $worker->celular != null && $worker->celular != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-telephone mx-2"></i>'.$worker->celular.'</span>';
                }
                if(isset($worker->correo) && $worker->correo != null && $worker->correo != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-envelope mx-2"></i>'.$worker->correo.'</span>';
                }
                if(isset($worker->ruta) && $worker->ruta != null && $worker->ruta != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-geo-alt-fill mx-2"></i> Ruta '.$worker->ruta.'</span>';
                }
                if(isset($worker->turno) && $worker->turnoactual ){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-calendar3"></i> Turno '.$worker->turnoactual->turno.'</span>';
                }
                if(isset($worker->antiguedad) && $worker->antiguedad ){
                    $retworker .= '<br><span class=""><i class="bi bi-hourglass-bottom"></i> Antigüedad: '.$worker->antiguedad.'</span>';
                }

                
                if(Yii::$app->user->identity->hidden_actions == 1){
                    $retworker .= '<br><span class="badge bg-dark text-light rounded-pill font500 m-1">ID: '.$worker->id.'</span>';
                }

                $retworker .= '</div>';

                $retworker .= '</h6>';

                $retworker .= '</div>
                </div></div>';

                echo $retworker;
            }
            ?>
        </div>
        <div class="col-lg-9">
            <div class="row m-0 p-0">
                <div class="col-lg-12 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo $iconheart;?></span>
                        Programas de Salud del Trabajador
                    </label>
                </div>
            </div>
            <div class="my-3">
                <div class="" style="">
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
                                'name'  => 'id_programa',
                                'title'  => 'Programa de Salud',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $programas,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:30%;'],
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
                            /* [
                                'name'  => 'fecha_inicio',
                                'title'  => 'Fecha Inicio',
                                'type'  => kartik\date\DatePicker::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                                    'pluginOptions'=>[
                                        'placeholder' => 'YYYY-MM-DD',
                                        'onchange'=>'', 
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:15%;'
                                ],         
                            ],
                            [
                                'name'  => 'fecha_fin',
                                'title'  => 'Fecha Término',
                                'type'  => kartik\date\DatePicker::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                                    'pluginOptions'=>[
                                        'placeholder' => 'YYYY-MM-DD',
                                        'onchange'=>'', 
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:15%;'
                                ],         
                            ], */
                            [
                                'name'  => 'fecha_inicio',
                                'title'  => 'Fecha Inicio',
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
                                    'onchange'=>''
                                    ]
                                   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:13%;'
                                ],         
                            ],
                            [
                                'name'  => 'fecha_fin',
                                'title'  => 'Fecha Fin',
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
                                    'onchange'=>''
                                    ]
                                   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:13%;'
                                ],         
                            ],
                            [
                                'name'  => 'conclusion',
                                'title'  => 'Condición',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => [1=>'Estable',2=>'En Mejoría',3=>'En Deterioro',4=>'Crítico',5=>'Concluido'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top; width:15%;'
                                    ],          
                            ],
                            [
                                'name'  => 'status',
                                'title'  => 'Status',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => ['1'=>'ACTIVO','2'=>'BAJA'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]  
                                    ],
                                    'headerOptions' => [
                                        'class' => 'color9 font500',
                                        'style' => 'vertical-align: top; width:10%;'
                                    ],          
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>

                </div>
            </div>
        </div>
    </div>



    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>