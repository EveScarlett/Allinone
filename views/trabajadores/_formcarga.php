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
$columnas = [
1=>'N° Trabajador',
2=>'N° IMSS',
3=>'Célular',
4=>'Contacto Emergencia',
5=>'Dirección',
6=>'Colonia',
7=>'Ciudad',
8=>'Municipio',
9=>'Estado',
10=>'País',
11=>'CP.',
12=>'Fecha Contratacion',
13=>'Estado Civil',
14=>'Nivel Lectura',
15=>'Nivel Escritura',
16=>'Escolaridad',
17=>'Ruta',
18=>'Parada',
19=>'Área',
20=>'Puesto',
21=>'Teamleader',
22=>'Fecha Inicio',
23=>'Curp',
24=>'Rfc',
25=>'Correo Electrónico'
];


$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
<path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
<path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
</svg>';

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
$asteriscomini = '<span class="px-2 color11 font8"><i class="bi bi-asterisk"></i></span>';
?>
    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row">
        <div class="col-lg-8">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label($model->getAttributeLabel('id_empresa').$asterisco); ?>
        </div>
    </div>
    <div class="row mt-2 px-3">
        <div class="col-lg-6 my-2 bgnocumple p-2 rounded-4">
            Suba el listado de trabajadores en formato .CSV
        </div>
    </div>
    <div class="row mt-3">

        <div class="col-lg-4">
            <?= $form->field($model, 'file_excel')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'excel','id'=>'upload'],
                    'language' => yii::t('app','es'),
                    'pluginOptions' => [
                    'browseClass' => 'btn btn-block btn-sm btn-dark',
                    'uploadClass' => 'btn btn-block btn-sm btn-info',
                    'removeClass' => 'btn btn-block btn-sm btn-danger',
                    'cancelClass' => 'btn btn-block btn-sm btn-danger',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                    ]
                    ])->label(false); ?>
        </div>
        <div class="col-lg-8">
            El archivo .CSV debe tener el siguiente orden de columnas<br><span class="color11">No colocar símbolos o
                carácteres especiales.<br>Los datos deben estar en <b>MAYÚSCULAS</b> <br>Las columnas <b>NOMBRE, APELLIDOS, SEXO, FECHA DE NACIMIENTO</b> son obligatorias</span><br>Las
            columnas extra no son obligatorias, y debe seleccionarse el atributo del trabajador que corresponda
            (direccion, numero de trabajador, etc.)
            <div class="container mt-3">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th class="font500">Nombre <?php echo $asteriscomini?></th>
                            <th class="font500">Apellidos <?php echo $asteriscomini?></th>
                            <th class="font500">Sexo <?php echo $asteriscomini?></th>
                            <th class="font500">Fecha de Nacimiento <?php echo $asteriscomini?></th>
                            <th class="font500">Columna Extra 1</th>
                            <th class="font500">Columna Extra 2</th>
                            <th class="font500">Columna Extra ...</th>
                        </tr>
                        <tr>
                            <td>John</td>
                            <td>Doe</td>
                            <td>MASCULINO</td>
                            <td>1992-05-20</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra1')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra2')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra3')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra4')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra5')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra6')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra7')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra8')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra9')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra10')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra11')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra12')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra13')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra14')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra15')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra16')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra17')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra18')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra19')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra20')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra21')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra22')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra23')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra24')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
                </div>
                <div class="col-lg-3 mt-2">
                    <?= $form->field($model, 'extra25')->widget(Select2::classname(), [
                    'data' => $columnas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
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