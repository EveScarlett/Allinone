<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Poes $model */

\yii\web\YiiAsset::register($this);
?>
<div class="poes-view">

    <?php $form = ActiveForm::begin([
        'id'=>'formOHC',
        'options' => ['enctype' => 'multipart/form-data'],
       ]); ?>

    <div class="container">


        <div class="row mt-5">
            <div class="col-lg-3">
                <?= $form->field($model, 'fecha_entrega')->widget(DatePicker::classname(), [
                    'disabled' =>true,
                    'readonly' =>true, 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'hora_entrega')->textInput(['maxlength' => true,'onkeyup' => '','readonly'=>true]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 text-justify">
                POR MEDIO DE LA PRESENTE SIENDO EL DÍA <span
                    class="mx-2 border-bottom color3 font500"><?php echo $model->fecha_entrega?></span> A LAS
                <span class="mx-2 border-bottom color3 font500"><?php echo $model->hora_entrega?></span>, QUIEN SUSCRIBE
                C. <span
                    class="mx-2 border-bottom color3 font500"><?php echo $model->nombre.' '.$model->apellidos;?></span>,

                CONFIRMO QUE ME HAN SIDO ENTREGADOS EN FORMATO IMPRESO O DIGITAL LOS ANÁLISIS CLÍNICOS Y/Ó ESTUDIOS
                MOSTRADOS A CONTINUACIÓN:
            </div>
        </div>

        <div class="row my-2 mt-4 bgnocumple text-light p-2 rounded-4">
            <div class="col-lg-12">
                <span class="px-2 color11"><i class="bi bi-asterisk"></i></span>Marque unicamente los estudios que
                fueron entregados
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <table class="table table-hover table-sm text-little">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th width="3%">#</th>
                            <th width="67%">Estudio</th>
                            <th width="30%">Entregado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($model->estudios){
                            foreach($model->estudios as $key=>$estudio){
                                //dd($estudio->estudio->nombre);
                                $check =  $form->field($model, 'aux_entregados['.$estudio->id.']')->checkBox([
                                    'label' => '',
                                    'onChange'=>'',
                                    'class' => 'largerCheckbox25',
                                    'options' => [
                                    ]])->label(false);
                                
                                echo '<tr>
                                <td colspan="1" class="">'.($key+1).'</td>
                                <td colspan="1" class="btnnew text-light">'.$estudio->estudio->nombre.'</td>
                                <td colspan="1" class="text-center py-0">'.$check.'</td>
                                </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 text-justify">
                HABIENDO PRESENTADO COMO IDENTIFICACIÓN OFICIAL PARA LA RECEPCIÓN DE MIS DOCUMENTOS:

            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'tipo_identificacion')->widget(Select2::classname(), [
                    'data' => ['1'=>'INE','2'=>'PASAPORTE','3'=>'LICENCIA DE CONDUCIR','4'=>'GAFETE','5'=>'OTRO'],
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

        <div class="row mt-3">
            <?php
            $url = Url::to(['firma']);
            ?>
            <div class="row my-3">
                <div class="col-lg-12 text-center">
                    <?php if(isset($model->firma_ruta)):?>
                    <img src="<?php  echo '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->firma_ruta;?>"
                        class="img-fluid img-responsive" width="300px" height="auto" />
                    <?php endif;?>
                </div>
                <div class="col-lg-12 text-center">
                    <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'800px','height'=>'300px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">Firma Trabajador </h5>']) ?>
                </div>
            </div>
            <div class="row" style="display:none;">
                <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
                <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
            </div>
        </div>
    </div>

    <div class="row mt-3 border-top pt-3">
        <div class="col-lg-4 offset-lg-4 text-center">
            <?=
            $form->field($model, 'cerrar_entrega')->checkBox([
                'label' => 'CERRAR ENTREGA DE RESULTADOS',
                'onChange'=>'cambiaStatusentrega()',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);
            ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4 offset-lg-4 text-center">
            <span class="color10">Una vez cerrado y guardado ya no podrá editarse.</span>
        </div>
    </div>
    <div class="row mt-5" style="display:none;" id="enviarform">
        <div class="col-lg-4 d-grid offset-lg-4">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>