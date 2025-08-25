<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
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
?>

<div class="empresas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php

    if($model->turnos){
        foreach($model->turnos as $key=>$turno){
            echo '<div class="row my-3 border30 border30 bg-customlight border-custom p-4"><div class="row m-0 p-0"><div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3"><label class=""><span class="mx-2"><i class="bi bi-calendar3-week"></i></span>Turno '.$turno->turno.'</label></div></div>';
            
            echo '<div class="row">
            <table class="table table-hover table-striped table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-center color1" style="font-weight:600; width:15%;">
                            
                        </th>
                        <th class="text-center" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Lunes</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Martes</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Miercoles</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Jueves</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Viernes</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Sabado</span>
                        </th>
                        <th class="text-center color1" style="font-weight:600;">
                            <span class="badge rounded-pill btnnew">Domingo</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td class="text-center color3 font500">
                            <label class="">H. Inicio <i class="bi bi-alarm-fill"></i></label>
                        </td>
                        <td class="text-center">';
                            echo $form->field($model, 'lunes_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'martes_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'miercoles_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'jueves_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'viernes_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'sabado_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'domingo_inicio['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                        echo '</td>
                    </tr>';

                    echo '<tr>
                        <td class="text-center color3 font500">
                            <label class="">H. Fin <i class="bi bi-alarm-fill"></i></label>
                        </td>
                        <td class="text-center">';
                            echo $form->field($model, 'lunes_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo  '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'martes_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'miercoles_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'jueves_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'viernes_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'sabado_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                        <td class="text-center">';
                            echo $form->field($model, 'domingo_fin['.$turno->id.']')->widget(Select2::classname(), [
                                'data' => $horarios,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => yii::t('app', '--'),
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ])->label(false);
                    echo    '</td>
                    </tr>
               
                </tbody>
            </table>
            </div>';

            $icono = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
            <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/>
            <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.492 1.492 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.72.72 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.72.72 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
            </svg>';

            echo '<div class="row">';

            echo $form->field($model, 'aux_personal['.$turno->id.']')->widget(MultipleInput::className(), [
                'rowOptions' => ['class' => 'border-bottom table-sm'],
                'max' => 44,
                'theme'=>'bs',
                'id'=>'aux_personal['.$turno->id.']',
                'cloneButton' => false,
                'rowOptions' => [
                    'class' => 'border-bottom table-sm',
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
                        'name'  => 'personal',
                        'title' => 'Personal Requerido',
                        'options' => [
                            'placeholder'=>'Nombre Personal Requerido'
                            ,'onkeyup' => 'converToMayus(this);'
                        ],
                        'headerOptions' => [
                            'class' => 'color9 font500',
                            'style' => 'vertical-align: top;'
                        ],          
                    ],  
                    [
                        'name'  => 'cantidad',
                        'title' => 'Cantidad',
                        'options' => [
                            'placeholder'=>'Cantidad'
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
                            'class' => 'text-light align-top text-center text-uppercase small space p-0',
                            'style' => 'vertical-align: top;'
                        ],
                    ],
                ]])->label(false);


            echo '</div>';
            
            echo '</div>';
        }
    }
    ?>
   

    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
