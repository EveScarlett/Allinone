<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use app\models\Puestostrabajo;
use app\models\Insumos;


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
    
    $puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
    $medidas = [
        '1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS',
        '2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS',
        '3'=>'MEX 26 | US 6 | EUR 32 | INTER S',
        '4'=>'MEX 28 | US 8 | EUR 34 | INTER M',
        '5'=>'MEX 30 | US 10 | EUR 34 | INTER M',
        '6'=>'MEX 32 | US 12 | EUR 36 | INTER L',
        '7'=>'MEX 36 | US 14 | EUR 36 | INTER L',
        '8'=>'MEX 38 | US 16 | EUR 38 | INTER XL',
        '9'=>'MEX 40 | US 18 | EUR 38 | INTER XL',
        '10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL',
        '11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',
    ];
    $medidascabezamano = ['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL'];
    $medidascalzado = ['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13'];
    $colores = [1=>'Negro',2=>'Azul',3=>'Marrón',4=>'Gris',5=>'Verde',6=>'Naranja',7=>'Rosa',8=>'Púrpura',9=>'Rojo',10=>'Blanco',11=>'Amarillo'];
    $tallas = $medidas+$medidascabezamano+$medidascalzado;
    $epps = ArrayHelper::map(Insumos::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['tipo'=>2])->orderBy('nombre_comercial')->all(), 'id', function($model) use ($colores,$tallas){
        $ret = $model['nombre_comercial'];

        if(isset($model['color']) && $model['color'] != null && $model['color'] != '' && $model['color'] != ' '){
            $ret .= ' Color:'.$colores[$model['color']];
        }

        if(isset($model['talla']) && $model['talla'] != null && $model['talla'] != '' && $model['talla'] != ' '){
            $ret .= ' Talla:'.$tallas[$model['talla']];
        }

        return $ret;
    });
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
                        Riesgos del puesto de trabajo
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php
                $ret = '';
                if(isset($model->puesto)){
                    if($model->puesto->riesgos){
                        foreach($model->puesto->riesgos as $key=>$riesgo){
                            $ret.= '<span class="badge rounded-pill bgtransparent2 text-dark mx-1 font14">'.$riesgo->riesgo.'</span>';
                        }
                    }
                    echo $ret;
                }
                ?>
                </div>
            </div>
        </div>

        <div class="container-fluid mb-5 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $icon;?></span>
                        Medidas Corporales
                    </label>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-lg-2">
                    <?= $form->field($model, 'talla_cabeza')->widget(Select2::classname(), [
                    'data' => $medidascabezamano,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'talla_superior')->widget(Select2::classname(), [
                    'data' => $medidas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'talla_inferior')->widget(Select2::classname(), [
                    'data' => $medidas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'talla_manos')->widget(Select2::classname(), [
                    'data' => $medidascabezamano,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'talla_pies')->widget(Select2::classname(), [
                    'data' => $medidascalzado,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
            </div>
        </div>


        <div class="container-fluid mb-5 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><i class="bi bi-rulers"></i></span>
                        Formato EPP requerido por el puesto de trabajo
                    </label>
                </div>
            </div>
            <div class="row">
                <?php
                $ret = '';

                echo '<div class="row mt-1 font500"><div class="col-lg-2">EPP Requerido</div><div class="col-lg-1"></div><div class="col-lg-7">Inventario</div><div class="col-lg-2">Talla</div></div>';
                if(isset($model->puesto)){
                    if($model->puesto->epps){
                        foreach($model->puesto->epps as $key=>$epp){

                            //$filePath =  '/resources/Empresas/'.$epp->id_empresa.'/EPP/'.$epp->id.'/'.$epp->foto;
                            //$foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                            $foto = '';
                            echo '<div class="row mt-1">';
                            echo '<div class="col-lg-2">'.($key+1).') <span class="badge rounded-pill bgcolor14 text-dark mx-1 font14">'.$epp->epp.'</span></div>';
                            echo '<div class="col-lg-1">'.$foto.'</div>';
                            echo '<div class="col-lg-7">';
                            echo $form->field($model, 'aux_epps['.$epp->id.']')->widget(Select2::classname(), [
                                'data' => $epps,
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                                'onchange' => 'cambiaEppitem(this.value,this.id)'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ])->label(false);
                            echo '</div>';


                            echo '<div class="col-lg-2">';
                            echo $form->field($model, 'aux_tallas['.$epp->id.']')->widget(Select2::classname(), [
                                'data' => $tallas,
                                'disabled' =>true,
                                'readonly' =>true,
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ])->label(false);
                            echo '</div>';

                            echo '<div class="col-lg-2" style="display:none;">';
                            echo $form->field($model, 'aux_tallas2['.$epp->id.']')->widget(Select2::classname(), [
                                'data' => $tallas,
                                'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ])->label(false);
                            echo '</div>';

                            echo '</div>';
                        }
                    }
                    echo $ret;
                }
                ?>
            </div>
        </div>

        <div class="container-fluid mb-5 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><i class="bi bi-collection-fill"></i></span>
                        Historial de Prestamos EPP
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 d-grid">
                <?= Html::a('<span class="mx-2 small"><i class="bi bi-plus-lg"></i></span>Solicitar Entrega de EPP', ['movimientos/create','tipo'=>2,'type'=>9,'worker'=>$model->id], ['class' => 'btn btn-primary bgcolor14 btn-block']) ?>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-lg-12">
                    <?php
                    if($model->entregasepp){
                        $ret = ''; 
                        $ret = '<table class="table table-hover table-sm text-little" style="height:100%">';
                        $ret .= '<thead class="font500"><tr><td class="text-center">#</td><td class="text-center" width="20%">Fecha Entrega</td><td class="text-center"  width="60%">Epp Entregado</td><td class="text-center">Motivo Entrega</td></tr></thead><tbody>';
    
                        foreach($model->entregasepp as $key=>$movimiento){
                            $listadoepps = '';
                            if($movimiento->medicamentos){
                                foreach($movimiento->medicamentos as $key=>$medicamento){
                                    $foto = '';
        
                                    $carpeta = 'Medicamentos';
                                    if($medicamento->insumo->tipo == 2){
                                        $carpeta = 'EPP';
                                    }
                                    if(isset($medicamento->insumo->foto) && $medicamento->insumo->foto != ""){
                                        $filePath =  '/resources/Empresas/'.$medicamento->insumo->id_empresa.'/'.$carpeta.'/'.$medicamento->insumo->id.'/'.$medicamento->insumo->foto;
                                        $foto = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "iconphoto img-responsive", 'width' => '35px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                        
                                    }
                                    $listadoepps.= '<span class="mx-1 color3"><i class="bi bi-dash-lg"></i></span><span class="mx-2">'. $foto.'</span>'.$medicamento->insumo->nombre_comercial.'<span class="font600 color3"> | '.$medicamento->cantidad_unidad.' unidades</span><br>';
                                }
                            }
                            $ret .= '<tr><td class="font500 text-center text-uppercase">'.($key+1).'</td><td class="font500 text-center text-uppercase">'.$movimiento->create_date . '</td><td class="">' .$listadoepps. '</td><td class="text-justify">' .$movimiento->motivoentrega. '</td></tr>';
                            /* $fecha_apartir = '--';
    
                            if($estudio->fecha_apartir != null && $estudio->fecha_apartir != ''){
                                $fecha_apartir = $estudio->fecha_apartir;
                            }
                             */
                        }
    
                        $ret .= '</tbody></table>';
                        echo $ret;

                    } else{
                        echo '<span class="color11">NO HAY ENTREGAS REGISTRADAS</span>';
                    }
                    
                    ?>
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