<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use app\models\Riesgos;
use app\models\Empresas;
use app\models\Epps;
use app\models\Areascuestionario;
use app\models\Preguntas;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Parametros;
use dosamigos\chartjs\ChartJs;


use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Ubicaciones;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

/** @var yii\web\View $this */
/** @var app\models\PuestosTrabajo $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}
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
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$epps = ArrayHelper::map(Epps::find()->orderBy('epp')->all(), 'id', 'epp');

$preguntas = ArrayHelper::map(Preguntas::find()->where(['in','id_tipo_cuestionario',[4]])->orderBy('pregunta')->all(), 'id', 'pregunta');

$factores = ArrayHelper::map(Areascuestionario::find()->where(['in','id_pregunta',[30,31]])->orderBy('nombre')->all(), 'id', function($model) use ($preguntas ){
    $ret = $model['nombre'];
    if($model['id_pregunta'] != '32'){
        $ret .=' - '.$preguntas[$model['id_pregunta']].' (cm)';
    }
    return $ret;
});

$parametros = ArrayHelper::map(Parametros::find()->orderBy('nombre')->all(), 'id', 'nombre');

$parametros[0] = 'NUEVO PARÁMETRO';

$valores = [
    1=>'1',
    2=>'2',
    3=>'3',
    4=>'4',
    5=>'5',
    6=>'6',
    7=>'7',
    8=>'8',
    9=>'9',
    10=>'10'
];


/* $index = 1;
foreach($factores as $key=>$factor) {
    $factores[$key] = $index.') '.$factor;
    $index++;
} */

?>


<?php
$id_paises = [];
$id_lineas = [];
$id_ubicaciones = [];

if(1==2){
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->select(['id_pais'])->distinct()->all(); 
} else {
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id_pais',$id_paises])->select(['id_pais'])->distinct()->all(); 
}

$id_paises = [];
foreach($paisempresa as $key=>$pais){
    array_push($id_paises, $pais->id_pais);
}
   
$paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');



if(1==2){
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
} else {
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->andWhere(['in','id',$id_lineas])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
}

$id_lineas = [];
foreach($lineas as $key=>$linea){
    array_push($id_lineas, $key);
}

if(1==2){
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
} else {
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->andWhere(['in','id',$id_ubicaciones])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
}

?>


<?php
$modelo_ = 'puestostrabajo';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$usuario = Yii::$app->user->identity;
if($usuario->nivel1_all == 1){
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
}  else {
    $array = explode(',', $usuario->nivel1_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array])->orderBy('id_pais')->all(), 'id', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
}



if($usuario->nivel2_all == 1){
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}  else {
    $array = explode(',', $usuario->nivel2_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}



if($usuario->nivel3_all == 1){
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}  else {
    $array = explode(',', $usuario->nivel3_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}




if($usuario->nivel4_all == 1){
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}  else {
    $array = explode(',', $usuario->nivel4_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}



if($model->id_empresa != null && $model->id_empresa != '' && $model->id_empresa != ' '){
    $empresa = Empresas::findOne($model->id_empresa);

    if($empresa){
        $label_nivel1 = $empresa->label_nivel1;
        $label_nivel2 = $empresa->label_nivel2;
        $label_nivel3 = $empresa->label_nivel3;
        $label_nivel4 = $empresa->label_nivel4;

        if($empresa->cantidad_niveles >= 1){
            $show_nivel1 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 2){
            $show_nivel2 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 3){
            $show_nivel3 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 4){
            $show_nivel4 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->andWhere(['in','id',$array])->all(), 'id','area');
            }

        }
    }
}
?>


<div class="puestos-trabajo-form">

    <?php $form = ActiveForm::begin(['id'=>'formSMO']); ?>

    <div class="row my-3">
        <div class="col-lg-8 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa(this.value,"puestostrabajo")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <!--   <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"puestostrabajo")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_linea')->widget(Select2::classname(), [
                    'data' => $lineas,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changelinea(this.value,"puestostrabajo")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_ubicacion')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div> -->
    </div>

    <div class="row mt-3">
        <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
            <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel1(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel1">'.$label_nivel1.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
            <?= $form->field($model, 'id_nivel2')->widget(Select2::classname(), [
                    'data' => $nivel2,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel2(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel2">'.$label_nivel2.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
            <?= $form->field($model, 'id_nivel3')->widget(Select2::classname(), [
                    'data' => $nivel3,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel3(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel3">'.$label_nivel3.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
            <?= $form->field($model, 'id_nivel4')->widget(Select2::classname(), [
                    'data' => $nivel4,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel4(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
        </div>
    </div>


    <div class="row my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'descripcion')->textArea(['rows'=>3,'maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2">

            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['0'=>'Baja','1'=>'Activo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-6  my-3 border30 bg-light p-4">
            <div class="row">
                <?php
        $icon2 = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-radioactive" viewBox="0 0 16 16">
        <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1ZM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8Z"/>
        <path d="M9.653 5.496A2.986 2.986 0 0 0 8 5c-.61 0-1.179.183-1.653.496L4.694 2.992A5.972 5.972 0 0 1 8 2c1.222 0 2.358.365 3.306.992L9.653 5.496Zm1.342 2.324a2.986 2.986 0 0 1-.884 2.312 3.01 3.01 0 0 1-.769.552l1.342 2.683c.57-.286 1.09-.66 1.538-1.103a5.986 5.986 0 0 0 1.767-4.624l-2.994.18Zm-5.679 5.548 1.342-2.684A3 3 0 0 1 5.005 7.82l-2.994-.18a6 6 0 0 0 3.306 5.728ZM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
      </svg>';
        ?>
                <div class="col-lg-8 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo '<i class="bi bi-exclamation-triangle-fill"></i>';?></span>Riesgos
                        del puesto
                    </label>
                </div>


                <div class="col-lg-12 mb-3">
                    <?= $form->field($model, 'aux_riesgos')->widget(Select2::classname(), [ 
                    'data' => $riesgos,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        
                    ],])->label(false); ?>
                </div>
                <div class="col-lg-12">
                    <?php echo $form->field($model, 'aux_otrosriesgos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm','style'=>'background-color:transparent;'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_otrosriesgos',
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
                                'name'  => 'riesgo',
                                'title'  => 'Agregar Nuevo Riesgo',
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'placeholder'=>'--'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],         
                            ],
                        ]])->label(false);?>
                </div>

            </div>
        </div>
        <div class="col-lg-6  my-3 border30 bg-light p-4">
            <?php
        $icon2 = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access" viewBox="0 0 16 16">
        <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM6 5.5l-4.535-.442A.531.531 0 0 1 1.531 4H14.47a.531.531 0 0 1 .066 1.058L10 5.5V9l.452 6.42a.535.535 0 0 1-1.053.174L8.243 9.97c-.064-.252-.422-.252-.486 0l-1.156 5.624a.535.535 0 0 1-1.053-.174L6 9V5.5Z"/>
      </svg>';
        ?>
            <div class="col-lg-8 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo $icon2;?></span>Equipo de Protección Requerido
                </label>
            </div>

            <div class="col-lg-12 mb-3">
                <?= $form->field($model, 'aux_epps')->widget(Select2::classname(), [ 
                    'data' => $epps,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        
                    ],])->label(false); ?>
            </div>
            <div class="col-lg-12">
                <?php echo $form->field($model, 'aux_otrosepps')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm','style'=>'background-color:transparent;'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_otrosepps',
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
                                'name'  => 'epp',
                                'title'  => 'Agregar Nuevo Epp',
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'placeholder'=>'--'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],         
                            ],
                        ]])->label(false);?>
            </div>

        </div>

    </div>


    <div class="row my-5 border30 bg-light p-4">
        <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color11 mt-3">
            <label class="">
                <span class="mx-2"><i class="bi bi-clipboard"></i></span>Requisitos Nuevo Ingreso
            </label>
        </div>
        <div class="row my-2 boxtitle2 p-2 rounded-4 font12">
            <div class="col-lg-12">
                <span class="px-2 color11"><i class="bi bi-asterisk"></i></span>Los requisitos marcados en este apartado
                <span class="font500 text-uppercase">no serán obligatorios en el expediente del trabajador ni en sus
                    examenes médicos</span>, se registran unicamente con fines informativos al momento del reclutamiento
                / nuevo ingreso.
            </div>
        </div>
        <div class="col-lg-12 px-0">
            <?php
            $periodicidad = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
            $data_documentos = ArrayHelper::map(app\models\Estudios::find()->orderBy('id')->all(), function($data){
                return '1_'.$data['id'];
            }, 'estudio');
            $data_estudios = ArrayHelper::map(app\models\Servicios::find()->orderBy('id')->all(), function($data){
                return '2_'.$data['id'];
            }, 'nombre');

            $array_completo = $data_documentos + $data_estudios;

            ?>
            <?php echo $form->field($model, 'aux_ni')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_ni',
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
                                'name'  => 'id_requisito',
                                'title'  => 'Requisito',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $array_completo,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:40%;'],
                                    
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
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;'],
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
                                'name'  => 'fecha_inicio',
                                'title'  => 'Requerido Desde el Día',
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
                                    ],
                                    'pluginEvents' => [
                                        "changeDate" => 'function(e){
                                        }'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:25%;'
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
                                    'data' => ['1'=>'Activo','0'=>'Baja'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
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
                                'name'=>'tipo_doc_examen',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'tipo_doc_examen'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
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

    <div class="row my-3 border30 bg-light p-4">
        <div class="col-lg-6 title2 boxtitle3 text-light p-1 rounded-3 my-3">
            <label class="">
                <span class="mx-2"><i class="bi bi-journal-text"></i></span>Requisitos mínimos para el puesto de trabajo
            </label>
        </div>
        <div class="col-lg-12 px-0" id='requisitosobligatorios'>
            <?php
            if($model->scenario == 'update'){
                if($model->empresa->requisitos){

                    $tipos = ['1'=>'Médico','2'=>'Otro'];
                    $ret = '';
                    $ret = '<table class="table table-dark table-hover table-sm text-little" style="height:100%">';
                    $ret .= '<thead class="table-dark font600"><tr><td class="text-center">#</td><td class="text-center">Tipo</td><td class="text-center">Estudio</td><td class="text-center">Periodicidad</td><td class="text-center">Requerido desde Día</td><td class="text-center">Status</td></tr></thead><tbody>';
                    $iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-heart" viewBox="0 0 16 16">
                    <path d="M10.058.501a.501.501 0 0 0-.5-.501h-2.98c-.276 0-.5.225-.5.501A.499.499 0 0 1 5.582 1a.497.497 0 0 0-.497.497V2a.5.5 0 0 0 .5.5h4.968a.5.5 0 0 0 .5-.5v-.503A.497.497 0 0 0 10.555 1a.499.499 0 0 1-.497-.499Z"/>
                    <path d="M3.605 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5h-.5a.5.5 0 0 1 0-1h.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5h.5a.5.5 0 0 1 0 1h-.5Z"/>
                    <path d="M8.068 6.482c1.656-1.673 5.795 1.254 0 5.018-5.795-3.764-1.656-6.69 0-5.018Z"/>
                    </svg>';
                    $iconsquare = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file" viewBox="0 0 16 16">
                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                    </svg>';
                    $arr_iconos = ['1'=>$iconheart,'2'=>$iconsquare];
                    $arr_colors = ['1'=>'color10','2'=>'color5'];
                    $status = ['1'=>'<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>','0'=>'<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>'];
                    $periodicidades = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
                    $tipos = ['1'=>'Médico','2'=>'Otro'];

                    foreach($model->empresa->requisitos as $key=>$estudio){
                        $fecha_apartir = '--';

                        if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){
                            $fecha_apartir = $estudio->fecha_apartir;
                        }
                        $ret .= '<tr><td class="font500 text-center text-uppercase">'.($key+1).'</td><td class="font500 text-center text-uppercase ' .$arr_colors[$estudio->id_tipo] . '"> <span class="mx-2">' .$arr_iconos[$estudio->id_tipo] . '</span>' . $tipos[$estudio->id_tipo] . '</td><td class="font600">' . $estudio->estudio->estudio . '</td><td class="font500 color6 text-center">' .$periodicidades[$estudio->id_periodicidad]. '</td><td class="font500 text-center">' .$fecha_apartir. '</td><td class="font500 text-center">' .$status[$estudio->id_status]. '</td></tr>';
                    }

                    $ret .= '</tbody></table>';
                    echo $ret;
                }
            }
            ?>
        </div>
    </div>


    <div class="row my-3 border30 bg-light p-4">
        <div class="col-lg-6 title2 boxtitle p-1 rounded-3 color3 mt-3">
            <label class="">
                <span class="mx-2"><i class="bi bi-clipboard"></i></span>Requisitos del Puesto
            </label>
        </div>
        <div class="col-lg-12 px-0">
            <?php
            $periodicidad = ['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS'];
            $estudios = ArrayHelper::map(app\models\Estudios::find()->orderBy('id')->all(), 'id', 'estudio');
            $estudios[0]= 'NUEVO ESTUDIO';
            ?>
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
                                'title'  => 'Documento',
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
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:15%;'],
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
                                'name'  => 'fecha_apartir',
                                'title'  => 'Requerido Desde el Día',
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
                                    ],
                                    'pluginEvents' => [
                                        "changeDate" => 'function(e){
                                        }'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:17%;'
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
                                    'data' => ['1'=>'Activo','0'=>'Baja'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:10%;'],
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


    <?php
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-arms-up" viewBox="0 0 16 16">
    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
    <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
    </svg>';
    ?>
    <div class="row my-3 border30 bg-light p-4 card bg-light shadow-sm">
        <div class="col-lg-6 title2 boxtitle p-1 rounded-3 color3 mt-3">
            <label class="">
                <span class="mx-2"><?php echo $icon;?></span>Avatar
            </label>
        </div>
        <div class="col-lg-12">
            <div class="row my-2 boxtitle2 p-2 rounded-4">
                <div class="col-lg-12">
                    Ingrese datos y rangos de médidas requeridos para realizar el puesto de trabajo <span
                        class="px-2 color11"><i class="bi bi-rulers"></i></span>
                </div>
            </div>
        </div>
        <?php
        $bloque1 = 'bgnocumple';
        $bloque2 = 'bgnocumple';
        $bloque3 = 'bgnocumple';
        $bloque4 = 'bgnocumple';
        $bloque5 = 'bgnocumple';
        $bloque6 = 'bgnocumple';
        $bloque7 = 'bgnocumple';
        
        if($model->cargamaxima == 5){
            $bloque5 = 'bgcumple';
        } else if($model->cargamaxima == 4){
            $bloque4 = 'bgcumple';
            $bloque5 = 'bgcumple';
            $bloque7 = 'bgcumple';
        } else if($model->cargamaxima == 3){
            $bloque4 = 'bgcumple';
            $bloque5 = 'bgcumple';
            $bloque6 = 'bgcumple';
            $bloque7 = 'bgcumple';
        } else if($model->cargamaxima == 2){
            $bloque3 = 'bgcumple';
            $bloque4 = 'bgcumple';
            $bloque5 = 'bgcumple';
            $bloque6 = 'bgcumple';
            $bloque7 = 'bgcumple';
        } else if($model->cargamaxima == 1){
            $bloque1 = 'bgcumple';
            $bloque2 = 'bgcumple';
            $bloque3 = 'bgcumple';
            $bloque4 = 'bgcumple';
            $bloque5 = 'bgcumple';
            $bloque6 = 'bgcumple';
            $bloque7 = 'bgcumple';
        } else if (!isset($model->cargamaxima) || $model->cargamaxima == null || $model->cargamaxima == ''){
            $bloque1 = '';
            $bloque2 = '';
            $bloque3 = '';
            $bloque4 = '';
            $bloque5 = '';
            $bloque6 = '';
            $bloque7 = '';
        }
        ?>
        <div class="col-lg-12 mt-3">
            <div class="row p-0">
                <div class="col-lg-12 p-0">
                    <div class="row">
                        <div class="col-lg-5">
                            <?= $form->field($model, 'carga')->textInput(['type'=>'number','maxlength' => true,'onchange' => 'cambiaCargapuesto(this.value)']) ?>
                        </div>
                        <div class="col-lg-2" style="display:none;">
                            <?= $form->field($model, 'cargamaxima')->widget(Select2::classname(), [
                                'data' => ['1'=>'7 (kg)','2'=>'10 (kg)','3'=>'15 (kg)','4'=>'20 (kg)','5'=>'25 (kg)'],
                                'theme' => Select2::THEME_BOOTSTRAP, 
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'multiple' => false,
                                'onchange' => 'cambiaCarga(this.value,"puestostrabajo")'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]); ?>
                        </div>
                        <div class="col-lg-7">
                            <table class="table table-hover table-bordered table-sm font11 text-little"
                                style="height:100%">
                                <thead class="font500">
                                    <tr>
                                        <td class="text-center" width="40%">Género</td>
                                        <td class="text-center" width="30%">Edad</td>
                                        <td class="text-center" width="30%">Carga Máxima</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='g1'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='e1'>Menores de 18</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque1;?>"
                                            id='c1'>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='g2'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='e2'>Menores de 18</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque2;?>"
                                            id='c2'>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='g3'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='e3'>Embarazadas</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque3;?>"
                                            id='c3'>10 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='g4'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='e4'>Entre 18 y 45</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque4;?>"
                                            id='c4'>20</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='g5'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='e5'>Entre 18 y 45</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque5;?>"
                                            id='c5'>25</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='g6'>Femenino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='e6'>Mayores de 45*</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque6;?>"
                                            id='c6'>15</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='g7'>Masculino</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='e7'>Mayores de 45*</td>
                                        <td class="text-center text-uppercase y_centercenter <?php echo $bloque7;?>"
                                            id='c7'>20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="display:none;">
                        <div class="col-lg-3">
                            <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                                'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Masculino & Femenino'],
                                'theme' => Select2::THEME_BOOTSTRAP, 
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'multiple' => false,
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                                ]); ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'edaddesde')->textInput(['type'=>'number','maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-2">
                            <?= $form->field($model, 'edadhasta')->textInput(['type'=>'number','maxlength' => true]) ?>
                        </div>
                    </div>
                </div>

            </div>

            <?php
            $iconnumber1 = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-1-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
            </svg>';

            $iconnumber2 = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
            </svg>';

            $iconnumber3 = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
            </svg>';
            ?>
            <div class="row p-0 mt-4">
                <div class="col-lg-5 p-0">
                    <div class="row borderlcolor3">
                        <div class="col-lg-12 mt-2">
                            <?= $form->field($model, 'medida1')->widget(Select2::classname(), [
                                'data' => $factores,
                                'theme' => Select2::THEME_BOOTSTRAP, 
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'multiple' => false,
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                                ])->label('<span class="color3 mx-2">'.$iconnumber1.'</span><span class="color3 font500">'.$model->getAttributeLabel('medida1').'</span>'); ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango1desde')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango1hasta')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row borderlcolor4 mt-2">
                        <div class="col-lg-12 mt-4">
                            <?= $form->field($model, 'medida2')->widget(Select2::classname(), [
                                'data' => $factores,
                                'theme' => Select2::THEME_BOOTSTRAP, 
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'multiple' => false,
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                                ])->label('<span class="color4 mx-2">'.$iconnumber2.'</span><span class="color4 font500">'.$model->getAttributeLabel('medida2').'</span>'); ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango2desde')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango2hasta')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row borderlcolor14 mt-2">
                        <div class="col-lg-12 mt-4">
                            <?= $form->field($model, 'medida3')->widget(Select2::classname(), [
                                'data' => $factores,
                                'theme' => Select2::THEME_BOOTSTRAP, 
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'multiple' => false,
                                'onchange' => ''],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                                ])->label('<span class="color14 mx-2">'.$iconnumber3.'</span><span class="color14 font500">'.$model->getAttributeLabel('medida3').'</span>'); ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango3desde')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <?= $form->field($model, 'rango3hasta')->textInput(['type'=>'number', 'min'=>'0','step'=>'0.01','maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 p-0">
                    <img src="img/antroparado.png" class="img-fluid" height="auto" width="auto" />
                    <img src="img/antrosentado.png" class="img-fluid" height="auto" width="auto" />
                </div>
            </div>

            <div class="row my-4" style="display:none;">
                <h5 class="color3 font500">
                    Perfil Psicológico <span><img src="resources/images/psicologico.png" class="px-2" height="30px"
                            width="auto" /></span>
                </h5>

                <div class="col-lg-12">
                    <div class="row my-2 boxtitle2 p-2 rounded-4">
                        <div class="col-lg-12">
                            <span class="font600">Ingrese de 1 a 10 parámetros</span> de personalidad que sean
                            requeridos para el puesto de trabajo.<br><span class="font600">Agregue un valor de 1 al
                                10</span>, siendo 10 el valor mas alto y 1 el mas bajo, segun sea la importancia de este
                            parámetro en el puesto.
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <?php echo $form->field($model, 'aux_psicologico')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 10,
                        
                        /* 'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
                        'layoutConfig' => [
                            'offsetClass' => 'col-md-offset-2',
                            'labelClass' => 'col-md-2',
                            'wrapperClass' => 'col-md-6',
                            'errorClass' => 'col-md-offset-2 col-md-6',
                            'buttonActionClass' => 'col-md-offset-1 col-md-2',
                        ], */
                        'theme'=>'bs',
                        'id'=>'aux_psicologico',
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
                                'name'  => 'parametro',
                                'title'  => 'Parámetros',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $parametros,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>''],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";
                                            var nuevo_id = id.replace("-parametro!", "-otroparametro");
                                            
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoParametro(nuevo_id, valor);
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:40%;'
                                ],         
                            ],
                            [
                                'name'  => 'otroparametro',
                                'title' => '',
                                'options' => [
                                    'style'=>'display:none;',
                                    'placeholder'=>'Nombre Parámetro'
                                    ,'onkeyup' => 'converToMayus(this);'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:40%;'
                                ],          
                            ],
                            [
                                'name'  => 'valor',
                                'title'  => 'Valor',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $valores,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>''],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:30%;'
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
                <div class="col-lg-6">
                    <div class="card-body bg-soft">
                        <?php
                        $labels = [];
                        $datas = [];
                        $datasmax = [];
                        foreach($model->parametros as $key=>$parametro){
                            array_push($labels, $parametro->parametro->nombre);
                            array_push($datas, $parametro->valor);
                            array_push($datasmax, 10);
                        }
                        ?>
                        <?= ChartJs::widget([
                                    'type' => 'radar',  
                                    'options' => [
                                       
                                    ],
                                    'data' => [
                                        'labels' => $labels, // Your labels
                                        'datasets' => [
                                            [
                                                'label'=> 'Valor Máximo',
                                                'data'=> $datasmax,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(65, 73, 217, 0.2)',
                                                'borderColor'=> 'rgb(65, 73, 217)',
                                                'pointBackgroundColor'=> 'rgb(65, 73, 217)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(65, 73, 217)'
                                            ],
                                            [
                                                'label'=> 'Valor Parámetro',
                                                'data'=> $datas,
                                                'fill'=> true,
                                                'backgroundColor'=> 'rgba(255, 99, 132, 0.2)',
                                                'borderColor'=> 'rgb(255, 99, 132)',
                                                'pointBackgroundColor'=> 'rgb(255, 99, 132)',
                                                'pointBorderColor'=> '#fff',
                                                'pointHoverBackgroundColor'=> '#fff',
                                                'pointHoverBorderColor'=> 'rgb(255, 99, 132)'
                                            ],
                                        ]
                                    ],
                                    ]);?>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'enviarForm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>