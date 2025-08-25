<?php

use app\controllers\CuestionarioController;
use app\models\Areascuestionario;
use app\models\Cuestionario;
use app\models\Trabajadores;
use app\models\Pacientes;
use app\models\Preguntas;
use app\models\Usuarios;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

use app\models\Empresas;
use app\models\Puestostrabajo;
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

$usuario = Yii::$app->user->identity;
$modulo1 = 'Cuestionario';
$modulo2 = 'cuestionario';
/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */
/** @var yii\widgets\ActiveForm $form */

$data_empresas = ArrayHelper::map($m_empresas, 'id', 'comercial');

$array_areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$array_puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');

//Carga a los trabajadores
$data_trabajadores = [];
if ($model->id_empresa != null) {
    /* $data_trabajadores = Cuestionario::getWorkersByCompany($model->id_empresa, ["id", function($model){
        return $model['nombre'].' '.$model['apellidos'];
    }]); */
    $data_trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('apellidos')->all(), 'id', function($model){
        return $model['apellidos'].' '.$model['nombre'];
    });
}

$arr_medicos = ArrayHelper::map($m_medicos,'id','name');

//dd($arr_medicos);

$preguntas = Preguntas::find()->where(['status' => 1, 'id_tipo_cuestionario' => 1])->all();
$areas = Areascuestionario::find()->where(['status' => 1, 'id_tipo_cuestionario' => 1])->all();

//dd($areas);

$arr_preguntas = ArrayHelper::map($preguntas, 'id', 'pregunta');
$arr_areas = ArrayHelper::map($areas, 'id', 'nombre');

// Verificar si es medico u otro usuario --
$_usuario = Yii::$app->user->identity->rol;

if ($_usuario === 2) {
    $path_f = 'img/';
    // $path_f = ''; //SERVER

    /* $firma = Usuarios::find()->where(["id" => Yii::$app->user->id])->one();
    $firma = $path_f . $firma->sign;

    if (file_exists($firma)) {
        $this->registerJs("
            document.getElementById('frm_med').src = '".$firma."';
        ");
    }

    $this->registerJs("
        let select_medico = document.getElementById('cuestionario-id_medico');
        select_medico.value = ".Yii::$app->user->identity->id.";
        select_medico.setAttribute('disabled', true);
    "); */
}
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
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
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
$modelo_ = 'cuestionario';
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

<div class="cuestionario-form">

    <?php $form = ActiveForm::begin(
        [
            'options' => [
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
                'labelOptions' => ['class' => 'control-label'],
            ],
        ]
    ); ?>

    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'model_')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'model_']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'id_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'sw')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'id_sw','name'=>'id_sw']) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3" style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::className(),[
                    'data' => $data_empresas,
                    'options' => [
                        'id' => 'e_empresa',
                        'prompt' => "-- Seleccione la empresa --",
                        'onchange' => "getWorkers(this);",
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label("Empresa"); ?>
        </div>
        <!-- <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"cuestionario")'],
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
                    'onchange' => 'changelinea(this.value,"cuestionario")'],
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

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($model, 'id_paciente')->widget(Select2::className(), [
                    'data' => $data_trabajadores,
                    'options' => [
                        'id' => 'e_paciente',
                        'prompt' => "-- Seleccione el trabajador --",
                        'onchange' => "loadWorkerData(this, 1)"
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label("Trabajador"); ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($model, 'id_medico')->widget(Select2::className(),[
                        'data' => $arr_medicos,
                        'options' => [
                            'prompt' => '-- Seleccione el medico --',
                            'onchange' => 'cargarFirma(this)'
                        ]
                ])->label("Medico"); ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
            <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel1">'.$label_nivel1.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
            <?= $form->field($model, 'id_nivel2')->widget(Select2::classname(), [
                    'data' => $nivel2,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel2">'.$label_nivel2.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
            <?= $form->field($model, 'id_nivel3')->widget(Select2::classname(), [
                    'data' => $nivel3,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel3">'.$label_nivel3.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
            <?= $form->field($model, 'id_nivel4')->widget(Select2::classname(), [
                    'data' => $nivel4,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
        </div>
    </div>

    <div class="row" style="display:none;">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-2">
            <?= $form->field($m_trabajadores, 'nombre')->textInput(['class' => "form-control form-control-sm", 'readonly' => true, 'placeholder' => 'Ingrese su nombre(s)'])->label("Nombre del empleado:") ?>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-2">
            <?= $form->field($m_trabajadores, 'apellidos')->textInput(['class' => "form-control form-control-sm", 'readonly' => true, 'placeholder' => 'Ingrese sus apellidos'])->label("Apellidos del empleado:") ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'num_trabajador')->textInput(['readonly' => true,'maxlength' => true]) ?>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'fecha_nacimiento')->textInput(['type' => 'date', 'class' => "form-control form-control-sm", 'readonly' => true, 'onchange' => 'getAge(this)'])->label('Fecha de nacimiento:') ?>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'edad')->textInput(['readonly' => true, 'class' => "form-control form-control-sm"])->label("Edad:") ?>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'sexo')->dropDownList(
                            [
                                '1' => "MASCULINO",
                                '2' => "FEMENINO",
                                '3' => "OTRO"
                            ],
                            [
                                'prompt' => '',//'-- Seleccione --',
                                'class' => "form-select form-select-sm disabled-select",
                            ]
                )->label("Sexo:"); ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'id_puesto')->widget(Select2::classname(), [
                    'data' => $array_puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ])->label("Puesto:"); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-1">
            <?= $form->field($m_trabajadores, 'id_area')->widget(Select2::classname(), [
                    'data' => $array_areas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ])->label("Area:"); ?>
        </div>
    </div>

    <div class="row mb-3 p-3">
        <div class="alert alert-primary" role="alert">
            <i class="bi bi-info-circle-fill"></i> Este cuestionario se basa en el Cuestionario Nórdico de Kuorinka, su
            propósito es detectar la existencia de síntomas iniciales que todavía no se han constituido como una
            enfermedad, ayuda para recopilar información sobre dolor, fatiga o molestias corporales. El cuestionario
            podrá aplicarse a los trabajadores que realizan manejo manual de cargas.
        </div>
    </div>

    <!-- PREGUNTA 1 -->
    <h5 class="mt-4"><span class="badge bg-primary">1</span> <?= $arr_preguntas[1].":" ?></h5>
    <?php 
    //dd($arr_areas);
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col col-lg-4 col-md-4">';
                        echo $form->field($m_detalle, '_preguntas[p0]['.$j.']')->radioList(
                            [
                                'Si' => "Si",
                                'No' => "No"
                            ],
                            [
                                'id' => 'p-0_a-' . $j,
                                'onchange' => 'filtro_uno(this);',
                                'itemOptions' => [
                                    'required' => true
                                ],
                                // 'item' => function ($index, $label, $name, $checked, $value) {
                    
                                //     $return = '<div class="form-check">';
                                //     $return .= '<input type="radio" class="form-check-input"  name="' . $name . '" value="' . $value . '" ' . ($checked ? "checked" : "") . ' required>';
                                //     $return .= ucwords($label);
                                //     $return .= '</div>';
                                //     return $return;
                                // },
                            ]
                        )->label($arr_areas[$j].":", ['for' => 'pre-1_area-' . $j, 'class' => 'form-label']);
                    echo '</div>';

                    echo '<div class="col col-lg-4 col-md-4">';
                        echo $form->field($m_detalle,'_preguntas[p1]['.$j.']')->radioList(
                            [
                                'Izquierdo' => "Izquierdo",
                                'Derecho' => "Derecho",
                                'Izquierdo y derecho' => "Izquierdo y derecho"
                            ],
                            [
                                'id' => 'p-1_a-' . $j,
                                'title' => 'Opción'
                                // 'itemOptions' => [
                                //     'required' => true
                                // ],
                                // 'item' => function ($index, $label, $name, $checked, $value) {
                                //     $return = '<div class="form-check">';
                                //     $return .= '<input type="radio" name="'.$name.'" value="'.$value.'" id="'.$name.'" >';
                                //     $return .= '<label class="form-check-label" for="'.$index.'"> '.' '.$label.'</label>';
                                //     $return .= "</div>";
                                //     return $return;
                                // }
                            ]
                        )->label("Lado:", ['class' => 'form-label']);
                    echo '</div>';
                echo '</div>';
                echo '<hr>';
            }
        ?>

    <!-- PREGUNTA 2 -->
    <h5 class="mt-4"><span class="badge bg-primary">2</span> <?= "¿".$arr_preguntas[2] . "?"?></h5>
    <?php 
            for ($j = 1; $j < count($arr_areas); $j++) { 
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle, '_preguntas[p2]['.$j.']')->textInput(['id' => 'p-2_a-' . $j, 'placeholder' => 'Duración', 'class' => "form-control form-control-sm", 'required' => true])->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 3 -->
    <h5 class="mt-4"><span class="badge bg-primary">3</span> <?= "¿" . $arr_preguntas[3] . "?"?></h5>
    <?php 
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col col-lg-4 col-md-4">';
                        echo $form->field($m_detalle,'_preguntas[p3]['.$j.']')->radioList(
                            [
                                'Si' => "SI",
                                'No' => "NO"
                            ],
                            [
                                'id' => 'p-3_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 4 -->
    <h5 class="mt-4"><span class="badge bg-primary">4</span> <?= "¿" . $arr_preguntas[4] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col col-lg-4 col-md-4">';
                        echo $form->field($m_detalle,'_preguntas[p4]['.$j.']')->radioList(
                            [
                                'Si' => "SI",
                                'No' => "NO"
                            ],
                            [
                                'id' => 'p-4_a-' . $j,
                                'onchange' => 'filtro_dos(this);',
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 5 -->
    <h5 class="mt-4"><span class="badge bg-primary">5</span> <?= "¿" . $arr_preguntas[5] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p5]['.$j.']')->radioList(
                            [
                                '1 - 7 Días' => "1 - 7 Días.",
                                '8 - 30 Días' => "8 - 30 Días.",
                                'Mas de 30 días no continuos' => "Mas de 30 días no continuos.",
                                'Siempre' => "Siempre.",
                            ],
                            [
                                'id' => 'p-5_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 6 -->
    <h5 class="mt-4"><span class="badge bg-primary">6</span> <?= "¿" . $arr_preguntas[6] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p6]['.$j.']')->radioList(
                            [
                                'Menos de 1 hora' => "Menos de 1 hora.",
                                '1 - 24 horas'    => "1 - 24 horas.",
                                '1 a 7 días'      => "1 a 7 días.",
                                '1 a 4 semanas'   => "1 a 4 semanas.",
                                'Más de 1 mes'    => "Más de 1 mes."
                            ],
                            [
                                'id' => 'p-6_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- pregunta 7 -->
    <h5 class="mt-4"><span class="badge bg-primary">7</span> <?= "¿" . $arr_preguntas[7] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p7]['.$j.']')->radioList(
                            [
                                'Nunca'         => "Nunca.",
                                '1 a 7 días'    => "1 a 7 días.",
                                '1 a 4 semanas' => "1 a 4 semanas.",
                                'Mas de 1 mes'  => "Mas de 1 mes."
                            ],
                            [
                                'id' => 'p-7_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 8 -->
    <h5 class="mt-4"><span class="badge bg-primary">8</span> <?= "¿" . $arr_preguntas[8] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p8]['.$j.']')->radioList(
                            [
                                'Si' => "Si",
                                'No' => "No"
                            ],
                            [
                                'id' => 'p-8_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 9 -->
    <h5 class="mt-4"><span class="badge bg-primary">9</span> <?= "¿" . $arr_preguntas[9] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p9]['.$j.']')->radioList(
                            [
                                'Si' => "Si",
                                'No' => "No"
                            ],
                            [
                                'id' => 'p-9_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 10 -->
    <h5 class="mt-4"><span class="badge bg-primary">10</span> <?= "¿" . $arr_preguntas[10] . "?" ?></h5>
    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle,'_preguntas[p10]['.$j.']')->radioList(
                            [
                                '1' => "1",
                                '2' => "2",
                                '3' => "3",
                                '4' => "4",
                                '5' => "5",
                            ],
                            [
                                'id' => 'p-10_a-' . $j,
                                'itemOptions' => [
                                    'required' => true
                                ],
                            ]
                        )->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
        ?>

    <!-- PREGUNTA 11 -->
    <h5 class="mt-4"><span class="badge bg-primary">11</span> <?= "¿" . $arr_preguntas[11] . "?" ?></h5>

    <div class="row mb-1 p-3">
        <div class="alert alert-primary" role="alert">
            <i class="bi bi-info-circle-fill"></i> Se puede agregar cualquier comentario que el trabajador considere
            importante, en relación con sus molestias y/o las actividades que desarrolla.
        </div>
    </div>

    <?php
            for ($j = 1; $j < count($arr_areas); $j++) {
                echo '<div class="row">';
                    echo '<div class="col">';
                        echo $form->field($m_detalle, '_preguntas[p11]['.$j.']')->textInput(['id' => 'p-11_a-' . $j, 'placeholder' => 'Descripción', 'class' => "form-control form-control-sm", "required" => true])->label($arr_areas[$j].":", ['class' => 'form-label mt-3']);
                    echo '</div>';
                echo '</div>';
            }
    ?>



<?php
    $displaycam_preregistro = 'flex';
    $display_preregistro = 'block';
    $display_btn = 'none';
    ?>
     <div class="container-fluid bg1 p-3 my-3 shadow datos_consentimiento" style="display:block;">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'name_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <h5 class="mb-3 bgcolor1 text-light p-2 text-center ">
            CONSENTIMIENTO
        </h5>
        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                    class="mx-2 border-bottom-dot title2 color3 nombre_cliente"
                    id='nombre_cliente'><?php echo $m_trabajadores->nombre.' '.$m_trabajadores->apellidos?></span>, EN PLENO
                USO DE
                MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE SE HIZO
                DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO PARA LA
                REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS ACCIONES
                QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA INTEGRIDAD DE
                NINGUNA PERSONA.
            </div>
        </div>


        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                FINALMENTE, Y CORRESPONDIENDO AL PRINCIPIO DE CONFIDENCIALIDAD, SE ME HA EXPLICADO QUE LA
                INFORMACIÓN QUE DERIVE COMO RESULTADO, RESPECTO A EL/LOS PROCEDIMIENTO(S) PRACTICADO(S),
                SERÁ MANEJADA DE MANERA CONFIDENCIAL Y ESTRICTAMENTE PARA EL USO DE:
            </div>
            <div class="col-lg-6">
                <?=$form->field($model, 'uso_consentimiento')->radioList( [1=>Yii::t('app','MI PERSONA'), 2 => Yii::t('app','ÁREA DE RECURSOS HUMANOS DE LA EMPRESA')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3 font-600','separator' => '<br>', 'itemOptions' => [
                        'class' => 'largerCheckbox'
                       ]] )->label(false);
                ?>
            </div>
            <div class="col-lg-6 shadow">
                <br>
                <span class="border-bottom-dot title2 color11 nombre_empresa mx-2 p-2" id='nombre_empresa'>
                    <?php
                    if($model->uso_consentimiento == 2){
                        echo $model->name_empresa;
                    }
                    ?>
                </span>
            </div>
        </div>
        <div class="row  mt-5 my-4">
            <div class="col-lg-12 text-justify">
                EN MI PRESENCIA HAN SIDO LLENADOS TODOS LOS ESPACIOS EN BLANCO EXISTENTES EN ESTE DOCUMENTO.
                TAMBIÉN ME ENCUENTRO ENTERADO(A) QUE TENGO LA FACULTAD DE RETIRAR ESTE CONSENTIMIENTO SI ASÍ
                LO DESEO (EN EL CASO DE QUE NO SE ME HAYA EXPLICADO EL/LOS PROCEDIMIENTO(S)).
            </div>
            <div class="col-lg-12">
                <?=$form->field($model, 'retirar_consentimiento')->radioList( [1=>Yii::t('app','SI'), 2 => Yii::t('app','NO')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3  font-600','separator' => '<br>', 'itemOptions' => [
                        'class' => 'largerCheckbox'
                       ]] )->label(false);
                ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-12 text-justify">
                YO <span
                    class="border-bottom-dot title2 color3 nombre_cliente mx-2"><?php echo $m_trabajadores->nombre.' '.$m_trabajadores->apellidos;?></span>
            </div>
            <div class="col-lg-12 text-justify">
                DOY MI CONSENTIMIENTO PARA LA REALIZACIÓN DEL PROCEDIMIENTO(S) ANTERIORMENTE SEÑALADO(S).
            </div>
        </div>
        <div class="row my-5 pt-3">
            <div class="col-lg-12 text-justify text-darkgray font-600">

                <?php $aviso= Html::a('Aviso de privacidad', Url::to(['trabajadores/privacy']), $options = ['target'=>'_blank','class'=>"btn boton btn-primary"]);?>
                <?php
                echo $form->field($model, 'acuerdo_confidencialidad')->checkBox([
                    'label' => '<span class="text-uppercase">He leído y aceptado el '.$aviso.' y comprendo que la información proporcionada se usará de acuerdo a los terminos establecidos.</span>',
                    'onChange'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")',
                    'class' => 'largerCheckbox',
                    'options' => [
                        
                        ]
                    ])->label(false);
                ?>

            </div>
        </div>
        

    </div>

    <div class="container-fluid bg1 p-3 my-3">
        <h5 class="mb-3 bgcolor1 text-light p-2">
            IDENTIFICACIÓN
        </h5>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'tipo_identificacion')->widget(Select2::classname(), [
                    'data' => ['INE'=>'INE','PASAPORTE'=>'PASAPORTE','LICENCIA DE CONDUCIR'=>'LICENCIA DE CONDUCIR','GAFETE'=>'GAFETE','OTRO'=>'OTRO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'numero_identificacion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']);?>
            </div>
        </div>


        <div class="row my-3 mt-4 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
            <div class="col-lg-6">
                <div id="live_camera"></div>
                <div class="row mt-3">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Evidencia"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot('cuestionario')">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
                    <div class="col-lg-12 d-grid text-center">
                        <label class="control-label" for="canvasfoto">Evidencia</label>
                        <div id="preview">
                            <?php if(isset($model->foto_web)):?>

                            <?php
                            //define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/');    
                            //dd('/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->foto_web);
                            ?>
                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->foto_web;?>"
                                class="img-fluid img-responsive" width="350px" height="300px" />
                            <?php endif;?>
                        </div>
                        <canvas id="canvasfoto" class="canvasmedia text-center mx-auto" width="350px" height="300px"
                            style="display:none;"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <div class="row" style="display:none;">
            <?= $form->field($model, 'txt_base64_foto')->textArea(['maxlength' => true,'class'=>'form-control image-tag']); ?>
            <?= $form->field($model, 'txt_base64_ine')->textArea(['maxlength' => true]); ?>
            <?= $form->field($model, 'txt_base64_inereverso')->textArea(['maxlength' => true]); ?>
        </div>



        <?php
        $mostrar_firma = 'none';
        $mostrar_pad = 'block';
        if($model->scenario == 'update' && isset($model->firma)){
           
            $mostrar_firma = 'block';
            $mostrar_pad = 'none';
        }
        $url = Url::to(['firma']);?>

    </div>

    <!-- FIRMA MEDICO -->
    <div class="row">
        <!-- <div class="col mt-3">
                <label for="">Firma medico:</label><br>
                <canvas id='canvas' width="400" height="200" style='border: 1px solid #CCC;' required>
                </canvas>
            </div> -->

        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Html::button('Limpiar firma', ['onclick' => 'LimpiarTrazado()', 'class' => 'btn btn-sm btn-danger']) ?>
                <input type='hidden' name='imagen' id='imagen' />
            </div> -->

        <div class="col mt-3">
            <label for="">Firma medico:</label><br>
            <?= Html::img("", ["class" => "", "style" => "width: 400px; height:200px; border:1px solid #ccc;", "alt" => "La firma del medico no se encontro.", "id" => "frm_med"]) ?>
        </div>
    </div>

    <!-- FIRMA TRABAJADOR -->
    <div class="row">
        <div class="col mt-3">
            <label for="">Firma trabajador:</label><br>
            <canvas id='canvas2' width="400" height="200" style='border: 1px solid #CCC;'>
            </canvas>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Html::button('<i class="bi bi-trash-fill"></i> Limpiar firma', ['onclick' => 'LimpiarTrazado2()', 'class' => 'btn btn-sm btn-danger']) ?>
            <input type='hidden' name='imagen2' id='imagen2' />
        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- <?php echo $form->errorSummary($model); ?> -->
        </div>
    </div>

    <div class="row mt-5">
        <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <?= Html::submitButton('<i class="bi bi-check-circle-fill"></i> Guardar', ['class' => 'btn btn-success w-100', 'onclick' => 'saveFirmas()']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- FIRMA MEDICO -->
<!-- <script type="text/javascript">
    /* Variables de Configuracion */
    var idCanvas = 'canvas';
    var idForm = 'w0';//'formCanvas';
    var inputImagen = 'imagen';
    var estiloDelCursor = 'crosshair';
    var colorDelTrazo = '#000'; //'#555';
    var colorDeFondo = '#fff';
    var grosorDelTrazo = 3;

    /* Variables necesarias */
    var contexto = null;
    var valX = 0;
    var valY = 0;
    var flag = false;
    var imagen = document.getElementById(inputImagen); 
    var anchoCanvas = document.getElementById(idCanvas).offsetWidth;
    var altoCanvas = document.getElementById(idCanvas).offsetHeight;
    var pizarraCanvas = document.getElementById(idCanvas);

    /* Esperamos el evento load */
    window.addEventListener('load',IniciarDibujo,false);

    function IniciarDibujo(){
      /* Creamos la pizarra */
      pizarraCanvas.style.cursor = estiloDelCursor;
      contexto=pizarraCanvas.getContext('2d');
      contexto.fillStyle = colorDeFondo;
      contexto.fillRect(0,0,anchoCanvas,altoCanvas);
      contexto.strokeStyle = colorDelTrazo;
      contexto.lineWidth = grosorDelTrazo;
      contexto.lineJoin = 'round';
      contexto.lineCap = 'round';
      /* Capturamos los diferentes eventos */
      pizarraCanvas.addEventListener('mousedown',MouseDown,false);// Click pc
      pizarraCanvas.addEventListener('mouseup',MouseUp,false);// fin click pc
      pizarraCanvas.addEventListener('mousemove',MouseMove,false);// arrastrar pc

      pizarraCanvas.addEventListener('touchstart',TouchStart,false);// tocar pantalla tactil
      pizarraCanvas.addEventListener('touchmove',TouchMove,false);// arrastras pantalla tactil
      pizarraCanvas.addEventListener('touchend',TouchEnd,false);// fin tocar pantalla dentro de la pizarra
      pizarraCanvas.addEventListener('touchleave',TouchEnd,false);// fin tocar pantalla fuera de la pizarra
    }

    function MouseDown(e){
      flag = true;
      contexto.beginPath();
      valX = e.pageX - posicionX(pizarraCanvas);
      valY = e.pageY - posicionY(pizarraCanvas);
      contexto.moveTo(valX, valY);
    }

    function MouseUp(e){
      contexto.closePath();
      flag = false;
    }

    function MouseMove(e){
      if(flag){
        contexto.beginPath();
        contexto.moveTo(valX, valY);
        valX = e.pageX - posicionX(pizarraCanvas);
        valY = e.pageY - posicionY(pizarraCanvas);
        contexto.lineTo(valX,valY);
        contexto.closePath();
        contexto.stroke();
      }
    }

    function TouchMove(e){
      e.preventDefault();
      if (e.targetTouches.length == 1) { 
        var touch = e.targetTouches[0]; 
        MouseMove(touch);
      }
    }

    function TouchStart(e){
      if (e.targetTouches.length == 1) { 
        var touch = e.targetTouches[0]; 
        MouseDown(touch);
      }
    }

    function TouchEnd(e){
      if (e.targetTouches.length == 1) { 
        var touch = e.targetTouches[0]; 
        MouseUp(touch);
      }
    }

    function posicionY(obj) {
      var valor = obj.offsetTop;
      if (obj.offsetParent) valor += posicionY(obj.offsetParent);
      return valor;
    }

    function posicionX(obj) {
      var valor = obj.offsetLeft;
      if (obj.offsetParent) valor += posicionX(obj.offsetParent);
      return valor;
    }

    /* Limpiar pizarra */
    function LimpiarTrazado(){
      contexto = document.getElementById(idCanvas).getContext('2d');
      contexto.fillStyle = colorDeFondo;
      contexto.fillRect(0,0,anchoCanvas,altoCanvas);
    }

    /* Enviar el trazado */
    function GuardarTrazado(){
      imagen.value = document.getElementById(idCanvas).toDataURL('image/png');
      document.forms[idForm].submit();
    }
</script> -->


<!-- FIRMA TRABAJADOR -->
<script type="text/javascript">
/* Variables de Configuracion */
var idCanvas2 = 'canvas2';
var idForm2 = 'w0'; //'formCanvas';
var inputImagen2 = 'imagen2';
var estiloDelCursor2 = 'crosshair';
var colorDelTrazo2 = '#000'; //'#555';
var colorDeFondo2 = '#fff';
var grosorDelTrazo2 = 3;

/* Variables necesarias */
var contexto2 = null;
var valX2 = 0;
var valY2 = 0;
var flag2 = false;
var imagen2 = document.getElementById(inputImagen2);
var anchoCanvas2 = document.getElementById(idCanvas2).offsetWidth;
var altoCanvas2 = document.getElementById(idCanvas2).offsetHeight;
var pizarraCanvas2 = document.getElementById(idCanvas2);

/* Esperamos el evento load */
window.addEventListener('load', IniciarDibujo2, false);

function IniciarDibujo2() {
    /* Creamos la pizarra */
    pizarraCanvas2.style.cursor = estiloDelCursor2;
    contexto2 = pizarraCanvas2.getContext('2d');
    contexto2.fillStyle = colorDeFondo2;
    contexto2.fillRect(0, 0, anchoCanvas2, altoCanvas2);
    contexto2.strokeStyle = colorDelTrazo2;
    contexto2.lineWidth = grosorDelTrazo2;
    contexto2.lineJoin = 'round';
    contexto2.lineCap = 'round';
    /* Capturamos los diferentes eventos */
    pizarraCanvas2.addEventListener('mousedown', MouseDown2, false); // Click pc
    pizarraCanvas2.addEventListener('mouseup', MouseUp2, false); // fin click pc
    pizarraCanvas2.addEventListener('mousemove', MouseMove2, false); // arrastrar pc

    pizarraCanvas2.addEventListener('touchstart', TouchStart2, false); // tocar pantalla tactil
    pizarraCanvas2.addEventListener('touchmove', TouchMove2, false); // arrastras pantalla tactil
    pizarraCanvas2.addEventListener('touchend', TouchEnd2, false); // fin tocar pantalla dentro de la pizarra
    pizarraCanvas2.addEventListener('touchleave', TouchEnd2, false); // fin tocar pantalla fuera de la pizarra
}

function MouseDown2(e) {
    flag2 = true;
    contexto2.beginPath();
    valX2 = e.pageX - posicionX2(pizarraCanvas2);
    valY2 = e.pageY - posicionY2(pizarraCanvas2);
    contexto2.moveTo(valX2, valY2);
}

function MouseUp2(e) {
    contexto2.closePath();
    flag2 = false;
}

function MouseMove2(e) {
    if (flag2) {
        contexto2.beginPath();
        contexto2.moveTo(valX2, valY2);
        valX2 = e.pageX - posicionX2(pizarraCanvas2);
        valY2 = e.pageY - posicionY2(pizarraCanvas2);
        contexto2.lineTo(valX2, valY2);
        contexto2.closePath();
        contexto2.stroke();
    }
}

function TouchMove2(e) {
    e.preventDefault();
    if (e.targetTouches.length == 1) {
        var touch = e.targetTouches[0];
        MouseMove2(touch);
    }
}

function TouchStart2(e) {
    if (e.targetTouches.length == 1) {
        var touch = e.targetTouches[0];
        MouseDown2(touch);
    }
}

function TouchEnd2(e) {
    if (e.targetTouches.length == 1) {
        var touch = e.targetTouches[0];
        MouseUp2(touch);
    }
}

function posicionY2(obj) {
    var valor = obj.offsetTop;
    if (obj.offsetParent) valor += posicionY2(obj.offsetParent);
    return valor;
}

function posicionX2(obj) {
    var valor = obj.offsetLeft;
    if (obj.offsetParent) valor += posicionX2(obj.offsetParent);
    return valor;
}

/* Limpiar pizarra */
function LimpiarTrazado2() {
    contexto2 = document.getElementById(idCanvas2).getContext('2d');
    contexto2.fillStyle = colorDeFondo2;
    contexto2.fillRect(0, 0, anchoCanvas2, altoCanvas2);
}

/* Enviar el trazado */
function GuardarTrazado2() {
    imagen2.value = document.getElementById(idCanvas2).toDataURL('image/png');
    document.forms[idForm2].submit();
}


function saveFirmas() {
    //imagen.value = document.getElementById(idCanvas).toDataURL('image/png');
    imagen2.value = document.getElementById(idCanvas2).toDataURL('image/png');

    var model_ = $('#model_').val();
    console.log('model_: ' + model_);

    if (model_ == 'cuestionario') {
        var foto = $("#" + model_ + "-txt_base64_foto").val();
        console.log('FOTO------------------------------------------------');
        console.log(foto);
    }

    // GuardarTrazado();
    // GuardarTrazado2();
}
</script>

<?php
$script = <<< JS
$(document).ready(function(){
    console.log('ESTOY EN DOCUMENT READY');
   
});

Webcam.set({
    width: 350,
    height: 300,
    image_format: 'jpeg',
    jpeg_quality: 90
});

Webcam.attach('#live_camera');


JS;
$this->registerJS($script)
?>