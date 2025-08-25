<?php

use app\models\Areascuestionario;
use app\models\Cuestionario;
use app\models\Pacientes;
use app\models\Preguntas;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Trabajadores;
use app\models\Puestostrabajo;

use app\models\Empresas;
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
/** @var app\models\Cuestionario $model */
/** @var yii\widgets\ActiveForm $form */

$data_empresas = ArrayHelper::map($m_empresas, 'id', 'comercial');

//Carga a los trabajadores
$data_trabajadores = [];

$array_areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
$array_puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');


if ($model->id_empresa != null) {
    /* $data_trabajadores = Cuestionario::getWorkersByCompany($model->id_empresa, ["id", function($model){
        return $model['nombre'].' '.$model['apellidos'];
    }]); */
    $data_trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('apellidos')->all(), 'id', function($model){
        return $model['apellidos'].' '.$model['nombre'];
    });
}

$arr_medicos = ArrayHelper::map($m_medicos,'id','name');

$arr_preguntas = ArrayHelper::map($m_preguntas, 'id', 'pregunta');

$img = [
    'Postura_parado' => "antroparado.png",
    'Postura_sentado' => "antrosentado.png"
];

// Verificar si es medico u otro usuario --
$_usuario = Yii::$app->user->identity->rol;

if ($_usuario === 2) {
    $this->registerJs("
        let select_medico = document.getElementById('cuestionario-id_medico');
        select_medico.value = ".Yii::$app->user->identity->id.";
        select_medico.setAttribute('disabled', true);
    ");
}

$id_preguntas_generales = Preguntas::find()->where(['pregunta' => "_general"])->one()->id;

$cont = 1;
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
        ]
    ); ?>

    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'id_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
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
                        'onchange' => "loadWorkerData(this, 4)"
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label("Trabajador"); ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  mt-3">
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
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'nombre')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese su nombre(s)', 'readonly' => true])->label("Nombre del empleado:") ?>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'apellidos')->textInput(['class' => "form-control form-control-sm", 'placeholder' => 'Ingrese sus apellidos', 'readonly' => true])->label("Apellidos del empleado:") ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'fecha_nacimiento')->textInput(['type' => 'date', 'class' => "form-control form-control-sm", 'onchange' => 'getAge(this)', 'readonly' => true])->label('Fecha de nacimiento') ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'edad')->textInput(['readonly' => true, 'class' => "form-control form-control-sm", 'readonly' => true])->label("Edad") ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'sexo')->dropDownList(
                            [
                                '1' => "MASCULINO",
                                '2' => "FEMENINO",
                                '3' => "OTRO"
                            ],
                            [
                                'prompt' => '',//'-- Seleccione --',
                                'class' => "form-select form-select-sm disabled-select",
                                'readonly' => true
                            ]
                )->label("Sexo"); ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'id_puesto')->widget(Select2::classname(), [
                    'data' => $array_puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ])->label("Puesto:"); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-3">
            <?= $form->field($m_trabajadores, 'id_area')->widget(Select2::classname(), [
                    'data' => $array_areas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
            ])->label("Area:"); ?>
        </div>
    </div>

    <div class="row mt-3 p-2">
        <div class="alert alert-primary" role="alert">
            <i class="bi bi-info-circle-fill"></i> Instrucciones: Registra la medición antropométrica realizada con
            cinta métrica.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-2">
            <?= $form->field($m_detalle, '_preguntas['.$id_preguntas_generales.'][179]')->textInput (
                    [
                        'id' => 'inp_peso',
                        'type' => 'number',
                        'step' => "any",
                        'class' => 'form-control form-control-sm',
                        'required' => true,
                        'onchange' => 'imc()',
                    ]
                )->label("Peso (kg)") ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-2">
            <?= $form->field($m_detalle, '_preguntas['.$id_preguntas_generales.'][180]')->textInput (
                    [
                        'id' => 'inp_talla',
                        'type' => 'number',
                        'step' => "any",
                        'class' => 'form-control form-control-sm',
                        'required' => true,
                        'onkeypress' => "return valideKey(event);",
                        'onchange' => 'imc()',
                    ]
                )->label("Altura (cm)") ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-2">
            <?= $form->field($m_detalle, '_preguntas['.$id_preguntas_generales.'][181]')->textInput (
                    [
                        'id' => 'inp_imc',
                        'type' => 'number',
                        'step' => "any",
                        'class' => 'form-control form-control-sm',
                        'required' => true,
                        'readonly' => true,
                        'onkeypress' => "return valideKey(event);"
                    ]
                )->label("IMC") ?>
        </div>
    </div>

    <?php foreach ($arr_preguntas as $key => $pregunta) {
            if ($pregunta != '_general') {
                $arr_areas = Areascuestionario::find()->where(["id_pregunta" => $key, 'status' => 1])->all();
                $arr_areas = ArrayHelper::map($arr_areas, "id", "nombre");

                $img_name = str_replace(" ", "_", $pregunta);
                $img_name = (isset($img[$img_name])) ? $img[$img_name] : null;

        ?>
    <div class="row my-3">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h5 class="mt-1 bg-gray px-1 rounded-1"><?= $pregunta ?></h5>
        </div>
        <div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <?php foreach ($arr_areas as $key_area => $area) { ?>
                    <?php
                                $id_e = str_replace(" ", "-", $area);
                            ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
                        <?= $form->field($m_detalle, '_preguntas['.$key.']['.$key_area.']')->textInput (
                                    [
                                        'type' => 'number',
                                        'step' => "any",
                                        'onkeypress' => "return valideKey(event);",
                                        'class' => 'form-control form-control-sm',
                                        'required' => true
                                    ]
                                )->label( "$area (cm)") ?>
                    </div>
                    <?php }
                            /* $cont = 1; */
                        ?>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                    <?= Html::img("./img/". $img_name,['class'=>'img-fluid']) ?>
                </div>
            </div>
            <?php
            } 
        } ?>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?= $form->field($m_detalle, '_preguntas['.$id_preguntas_generales.'][182]')->textarea (
                    [
                        'class' => 'form-control form-control-sm',
                        'required' => true,
                    ]
                )->label("Comentarios") ?>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <?= Html::submitButton('<i class="bi bi-check-circle-fill"></i> Guardar', ['class' => 'btn btn-success btn-sm w-100']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>