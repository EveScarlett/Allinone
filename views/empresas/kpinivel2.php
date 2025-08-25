<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use unclead\multipleinput\MultipleInput;
use kartik\date\DatePicker;

use app\models\Paises;
use app\models\Trabajadores;
use app\models\Areas;
use app\models\Kpis;
use app\models\Puestostrabajo;
use app\models\Consultorios;
use app\models\Programaempresa;
use app\models\Turnos;
use app\models\ProgramaTrabajador;
use app\models\Poes;
use app\models\ProgramaSalud;
use app\models\Consultas;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

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
$label = 'Nivel 2';

if($empresa){
    $label = $empresa->label_nivel2;
}
?>

<?php
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
?>

<div class="ubicaciones-form p-3">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'id_nivel2']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'kpi_cumplimiento')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'nivel')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'nivel']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_nivelorganizacional1')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'id_nivel1']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_nivelorganizacional2')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'id_nivel3']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_nivelorganizacional3')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'id_nivel4']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'cumplimiento_dias_sin_accidentes')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
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

        <div class="col-lg-12 title1 color3 mt-3 bgcolor16 rounded-3 p-2 px-3">
            <?php
            echo $model->nivelorganizacional2;
            echo ' | editable: '.$editar_nivel;
            ?>
        </div>
    </div>

    <div class="container">
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
                <div class="title1" id="cumplimiento_kpi">
                    <?php
                    echo(number_format($model->kpi_cumplimiento, 2, '.', ',')).'%';
                ?>
                </div>
            </div>
        </div>

        <div class="row border-top">
            <h1 class="title1 mt-2">
                Días sin Accidentes
                <?= '<img src="resources/images/casco-de-seguridad.png" class="px-2" height="30px" width="auto"/>'; ?>
            </h1>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if($editar_nivel):?>
                        <?= $form->field($model, 'aux_dias_sin_accidentes')->textInput(['class'=>"form-control font30 bgcolor20 px-2 py-3 text-center border-bt3 font-digital",'type'=>'number','min'=>0,'max'=>100000,'maxlength' => true])->label(false) ?>
                        <?php else:?>
                        <div class="form-control font30 bgcolor20 px-2 py-3 text-center border-bt3 font-digital">
                            <?=
                            $model->aux_dias_sin_accidentes.'&nbsp;';
                            ?>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <?= '<img src="resources/images/rojo.png" class="px-2" width="40px" width="auto"/>'; ?>
            </div>
            <div class="col-lg-2 p-2 rounded-3 bgregular">
                <label class="font11">Cumplimiento</label>
                <div class="title1" id="cumplimiento_accidentes">
                    <?php
                    echo(number_format($model->cumplimiento_dias_sin_accidentes, 2, '.', ',')).'%';
                ?>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <?php if($editar_nivel):?>
            <h2 class="title1 mt-2 font20">
                Total de accidentes en el año
            </h2>
            <?php endif;?>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if($editar_nivel):?>
                        <?= $form->field($model, 'accidentes_anio_dias_sin_accidentes')->textInput(['class'=>"form-control font25 bgcolor22 px-2 py-3 text-center border-0 font-digital",'type'=>'number','min'=>0,'max'=>100000,'maxlength' => true, 'onkeyup' => 'cambiaAccidentes("2");'])->label(false) ?>
                        <?php else:?>
                        <div class="form-control font25 bgcolor22 px-2 py-3 text-center border-0 font-digital"
                            style="display:none;">
                            <?=
                            $model->accidentes_anio_dias_sin_accidentes.'&nbsp;';
                            ?>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php if($editar_nivel):?>
            <div class="col-lg-4">
                <span class="color11 font500">QTY Máxima aceptable de Accidentes por Año</span>
            </div>
            <?php endif;?>
            <div class="col-lg-2">
                <?php if($editar_nivel):?>
                <?= $form->field($model, 'objetivo_dias_sin_accidentes')->textInput(['class'=>"form-control",'type'=>'number','min'=>0,'max'=>100000,'maxlength' => true, 'onkeyup' => 'cambiaAccidentes("2");'])->label(false) ?>
                <?php else:?>
                <div class="form-control bgcolor16 border-0" style="display:none;">
                    <?=
                        $model->objetivo_dias_sin_accidentes.'&nbsp;';
                    ?>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-2">
                <?php if($editar_nivel):?>
                <?= $form->field($model, 'aux_fecha_dias_sin_accidentes')->textInput(['class'=>"form-control bgcolor16",'readonly'=>true])->label() ?>
                <?php else:?>
                <label class="control-label"
                    style="display:none;"><?= $model->getAttributeLabel('aux_fecha_dias_sin_accidentes');?></label>
                <div class="form-control bgcolor16 border-0" style="display:none;">
                    <?=
                        $model->aux_fecha_dias_sin_accidentes.'&nbsp;';
                    ?>
                </div>
                <?php endif;?>
            </div>
            <div class="col-lg-2">
                <?php if($editar_nivel):?>
                <?= $form->field($model, 'aux_actualiza_dias_sin_accidentes')->textInput(['class'=>"form-control bgcolor16",'readonly'=>true])->label() ?>
                <?php else:?>
                <label class="control-label"
                    style="display:none;"><?= $model->getAttributeLabel('aux_actualiza_dias_sin_accidentes');?></label>
                <div class="form-control bgcolor16 border-0" style="display:none;">
                    <?=
                        $model->aux_actualiza_dias_sin_accidentes.'&nbsp;';
                    ?>
                </div>
                <?php endif;?>
            </div>
            <div class="col-lg-6">
                <?php if($editar_nivel):?>
                <?= $form->field($model, 'comentario_dias_sin_accidentes')->textArea(['rows'=>'2','maxlength' => true,'onkeyup' => '']); ?>
                <?php else:?>
                <label class="control-label"
                    style="display:none;"><?= $model->getAttributeLabel('comentario_dias_sin_accidentes');?></label>
                <div class="form-control bgcolor16 border-0" style="display:none;">
                    <?=
                        $model->comentario_dias_sin_accidentes.'&nbsp;';
                    ?>
                </div>
                <?php endif;?>
            </div>
            <div class="col-lg-2 d-grid">
                <?php if($editar_nivel):?>
                <?= Html::button('Guardar Días <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'Diasaccidentes','onclick'=>'enviarDias()']) ?>
                <?php endif;?>
            </div>
        </div>

        <div class="row mt-5">
            <?php
            $icon_kpi = '<img src="resources/images/dashboard.png" class="px-2" height="20px" width="auto"/>';
            ?>
            <h1 class="title1 mt-2 border-top">
                KPIs
            </h1>
            <div class="col-lg-12 table-responsive" style="width:1200px !important;">
                <?php if($editar_nivel):?>
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
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'','size' => Select2::SMALL],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id");
                                            
                                            console.log("valor: "+valor+" | id: "+id);
                                            cambiaResultadokpi(id,"2");
                                            //nuevoEstudio(nuevo_id, valor);
                                        }'
                                    ] ,
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                        'dropdownParent' => '#modal-kpiedit'
                                    ],  
                                ], 
                                
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:20%;'
                                ], 
                            ],
                           /*  [
                                'name'  => 'kpi_descripcion',
                                'title' => 'Descripción',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'--'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ], */
                            [
                                'name'  => 'kpi_responsable',
                                'title' => 'Responsable',
                                'type'  => 'textArea',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'--',
                                    'class'=>'form-control form-control-sm'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'kpi_objetivo',
                                'title' => 'Objetivo',
                                'type'  => 'textInput',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'--',
                                    'type'=>'number',
                                    'class'=>'font-control border-complete',
                                    'onchange'=>'cambiaResultadokpi(this.id,"2")',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:8%;'
                                ],          
                            ],
                            [
                                'name'  => 'kpi_real',
                                'title' => 'Real',
                                'type'  => 'textInput',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'--',
                                    'type'=>'number',
                                    'class'=>'font-control border-complete',
                                    'onchange'=>'cambiaResultadokpi(this.id,"2")',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:8%;'
                                ],          
                            ],
                            [
                                'name'  => 'kpi_cumplimiento',
                                'title' => '% Cumpli.',
                                'type'  => 'textInput',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'--',
                                    'type'=>'number',
                                    'readonly'=>true,
                                    'class'=>'form-control bgcolor16 border-complete porcentaje_cumplimiento',
                                    'onchange'=>'cambiaCumplimiento("2")',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],          
                            ],
                            [
                                'name'  => 'kpi_fecha',
                                'title'  => 'Fecha',
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
                                       'size' => 'sm',
                                    'pluginOptions'=>[
                                        'placeholder' => 'YYYY-MM-DD',
                                        'onchange'=>'', 
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:20%;'
                                ],         
                            ],
                            [
                                'name'  => 'kpi_actualiza_aux',
                                'title' => 'Actualizó',
                                'type'  => 'textInput',
                                'options' => [
                                    'style'=>'',
                                    'placeholder'=>'',
                                    'readonly'=>true,
                                    'class'=>'form-control bgcolor16 form-control-sm'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:10%;'
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
                                'name'=>'kpi_actualiza',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
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
                <?php else:?>
                <?php
                    $lbl_superior = '';
                    $cambia_superior = false;
                    if($id_inferiores && (count($id_inferiores)>0)){
                        $niveles_above = null;
                        if($empresa->cantidad_niveles == 3){
                            $niveles_above = NivelOrganizacional3::find()->where(['id_empresa'=>$empresa->id])->andWhere(['in','id',$id_inferiores])->andWhere(['status'=>1])->all();
                        } else if($empresa->cantidad_niveles == 4){
                            $niveles_above = NivelOrganizacional4::find()->where(['id_empresa'=>$empresa->id])->andWhere(['in','id',$id_inferiores])->andWhere(['status'=>1])->all();
                        }
                        
                        if($niveles_above){
                            foreach($niveles_above as $key=>$inferior){

                                $label_inferior = '';
                                if($empresa->cantidad_niveles == 3){
                                    $label_inferior = $inferior->nivelorganizacional3;
                                } else if($empresa->cantidad_niveles == 4){
                                    $label_inferior = $inferior->nivelorganizacional4;
                                    
                                    $super =  NivelOrganizacional3::findOne($inferior->id_nivelorganizacional3);
                                    if($super){
                                        if($lbl_superior != $super->nivelorganizacional3){
                                            $lbl_superior = $super->nivelorganizacional3;
                                            $cambia_superior = true;
                                        } else {
                                            $cambia_superior = false;
                                        }
                                        
                                    }

                                }
                                $ret_nivel = '<table class="table table-hover table-sm table-bordered font11">
                                <thead>';

                                if($cambia_superior){
                                    $ret_nivel .= '<tr>
                                    <th class="bgcolor1 text-light font12 font500" colspan=3>'.$lbl_superior.'</th>
                                    <th class="font12 font500 text-center bgcolor14 p-2" colspan=1>'.number_format($super->kpi_cumplimiento, 2, '.', ',').'%</th>
                                    </tr>';
                                }

                                $ret_nivel .= '<tr>
                                    <th class="bgcolor3 text-light font12 font500" colspan=3>'.$label_inferior.'</th>
                                    <th class="font12 font500 text-center bgcolor14 p-2" colspan=1>'.number_format($inferior->kpi_cumplimiento, 2, '.', ',').'%</th>
                                </tr>
                                <tr>
                                    <th class="font500 color9" width="40%">KPI</th>
                                    <th class="font500 color9 text-center" width="20%">Objetivo</th>
                                    <th class="font500 color9 text-center" width="20%">Real</th>
                                    <th class="font500 color9 text-center" width="20%">Cumplimiento</th>
                                </tr>
                                </thead>
                                <tbody>';

                                $ret_nivel .= '
                                        <tr>
                                            <td width="40%">DIAS SIN ACCIDENTES</td>
                                            <td class="text-center" width="20%">'.$inferior->objetivo_dias_sin_accidentes.'</td>
                                            <td class="text-center" width="20%">'.$inferior->accidentes_anio_dias_sin_accidentes.'</td>
                                            <td class="text-center" width="20%">'.$inferior->cumplimiento_dias_sin_accidentes.'%</td>
                                        </tr>';

                                $retkpis = Kpis::find()->where(['id_superior'=>$inferior->id])->andWhere(['nivel'=>4])->andWhere(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->all();
                                if($retkpis){
                                    foreach($retkpis as $key2=>$kpinivel){
                                        $data_kpi = '';
                                        $data_objetivo = $kpinivel->kpi_objetivo;
                                        $data_real = $kpinivel->kpi_real;
                                        $data_cumplimiento = $kpinivel->kpi_cumplimiento.'%';

                                        if($kpinivel->kpi == 'D'){
                                            $data_kpi = $kpis_mixed[$kpinivel->id_programa];
                                        } else {
                                            $data_kpi = $kpis_mixed[$kpinivel->kpi];
                                        }

                                        $ret_nivel .= '
                                        <tr>
                                            <td width="40%">'.$data_kpi.'</td>
                                            <td class="text-center" width="20%">'.$data_objetivo.'</td>
                                            <td class="text-center" width="20%">'.$data_real.'</td>
                                            <td class="text-center" width="20%">'.$data_cumplimiento.'</td>
                                        </tr>';
                                    }
                                } else {
                                    $ret_nivel .= '<tr><td colspan=4>Ninguno</td></tr>';
                                }
                                    
                                $ret_nivel .= '</tbody>
                                </table>';
                                 echo $ret_nivel;
                            }
                        }
                    }    
                ?>
                <?php endif;?>
            </div>
        </div>
    </div>


    <?php if($editar_nivel):?>
    <div class="row mt-3">
        <div class="col-lg-4 offset-lg-8 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>
    <?php endif;?>

    <?php ActiveForm::end(); ?>

</div>