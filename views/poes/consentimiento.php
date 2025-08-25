<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


use app\models\Empresas;
use app\models\Poeestudio;

$this->title = Yii::t('app', 'Consentimiento POE: {name}', [
    'name' => $model->nombre.' '.$model->apellidos.' - AÑO '.$model->anio,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exámenes Médicos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['consentimiento', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Consentimiento');

$modulo1 = 'Poes';
$modulo2 = 'poes';
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

<div class="consentimiento">

    <?php $form = ActiveForm::begin(['id'=>'formOHC','options' => ['enctype' => 'multipart/form-data'],]); ?>

    <?php
    $displaycam_preregistro = 'flex';
    $display_preregistro = 'block';
    $display_btn = 'none';

    if($model->scenario == 'create'){
        $displaycam_preregistro = 'none';
        $display_preregistro = 'none';
        $display_btn = 'inline';
    }
    ?>


    <?php
        $mostrar_firma = 'none';
        $mostrar_pad = 'block';
        if($model->scenario == 'update' && isset($model->firma)){
           
            $mostrar_firma = 'block';
            $mostrar_pad = 'none';
        }
        $url = Url::to(['firma']);
    ?>

    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'tipo_consentimiento')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>

    <?php
    $show_consentimiento1 = 'none';
    $show_consentimiento2 = 'none';
    $show_guardar = 'none';

    $class_consentimiento1 = 'btnnew2';
    $class_consentimiento2 = 'btnnew2';

    if($model->tipo_consentimiento == 1){
        $show_consentimiento1 = 'block';
        $show_guardar = 'block';
        $class_consentimiento1 = 'btnnew4';
    } else if($model->tipo_consentimiento == 2){
        $show_consentimiento2 = 'block';
        $show_guardar = 'block';
        $class_consentimiento2 = 'btnnew4';
    }
    ?>


    <div class="row">
        <div class="col-lg-4 offset-lg-2 p-2 puntero">
            <div class=" rounded-3 p-4 puntero <?=$class_consentimiento1?>" id="click_consentimiento1">
                <label class='mini-cube2 p-0 puntero'>
                    <?php
                        $filePath =  '/resources/images/consentimiento1.png';
                        echo Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-fluid"]);
                    ?>
                </label>
                <label class="title1 text-light puntero text-center">Llenar Consentimiento</label>
            </div>
        </div>

        <div class="col-lg-4 p-2 puntero">
            <div class=" rounded-3 p-4 puntero <?=$class_consentimiento2?>" id="click_consentimiento2">
                <label class='mini-cube2 p-0 puntero'>
                    <?php
                        $filePath =  '/resources/images/consentimiento2.png';
                        echo Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-fluid"]);
                    ?>
                </label>
                <label class="title1 text-light puntero text-center">Subir Consentimiento</label>
            </div>
        </div>
    </div>


    <div class="container-fluid bg1 p-3 my-3 shadow datos_consentimiento" style="display:<?=$show_consentimiento1;?>;" id="bloque_consentimiento1">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <h5 class="mb-3 bgcolor1 text-light p-2 text-center ">
            CONSENTIMIENTO
        </h5>
        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                    class="mx-2 border-bottom-dot title2 color3 nombre_cliente"
                    id='nombre_cliente'><?php echo $model->nombre.' '.$model->apellidos?></span>, EN PLENO
                USO DE
                MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE SE HIZO
                DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO PARA LA
                REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS ACCIONES
                QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA INTEGRIDAD DE
                NINGUNA PERSONA.
            </div>
        </div>


        <div class="container-fluid my-3 border30 bg-light p-4">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        ESTUDIOS A REALIZAR
                    </label>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="control-label font500" width="5%">#</th>
                        <th class="control-label font500" width="95%">Estudio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $estudios_ordenados = Poeestudio::find()->where(['id_poe'=>$model->id])->orderBy(['orden'=>SORT_ASC])->all();

                    if($estudios_ordenados){
                        foreach($estudios_ordenados as $key=>$estudio){
                                    
                            $est_estudio = '';
                            if($estudio->estudio){
                                $est_estudio = $estudio->estudio->nombre;
                            }
                                    
                            echo '<tr>
                                    <td class="t5 " width="5%">'.($key+1).'</td>
                                    <td class="t5 " width="95%">'.$est_estudio.'</td>
                                </tr>';
                        }
                    }
                    
                ?>
                </tbody>
            </table>
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
                        echo $model->nombre_empresa;
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
                    class="border-bottom-dot title2 color3 nombre_cliente mx-2"><?php echo $model->nombre.' '.$model->apellidos;?></span>
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
        <div class="row my-3" style="display:none;">
            <div class="col-lg-12 text-center">
                <?php if(isset($model->firma_ruta)):?>
                <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Documentos/'.$model->firma_ruta;?>"
                    class="img-fluid img-responsive" width="500px" height="auto" />
                <?php endif;?>
            </div>
            <div class="col-lg-12 text-center">
                <!--  <?= $form->field($model, 'firma')->textInput(['maxlength' => true]) ?> -->
                <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'800px','height'=>'300px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">FIRMA TRABAJADOR </h5>']) ?>
            </div>
        </div>
        <div class="row" style="display:none;">
            <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
            <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
        </div>

    </div>

    <div class="container-fluid bg1 p-3 my-3" style="display:<?=$show_consentimiento1;?>;" id="bloque_consentimiento1_pt2">
        <h5 class="mb-3 bgcolor1 text-light p-2">
            IDENTIFICACIÓN
        </h5>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'tipo_identificacion_p')->widget(Select2::classname(), [
                    'data' => ['INE'=>'INE','PASAPORTE'=>'PASAPORTE','LICENCIA DE CONDUCIR'=>'LICENCIA DE CONDUCIR','GAFETE'=>'GAFETE','OTRO'=>'OTRO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'numero_identificacion_p')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']);?>
            </div>
        </div>


        <div class="row my-3 mt-4 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
            <div class="col-lg-6">
                <div id="live_camera"></div>
                <div class="row mt-3">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Evidencia"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot('poes')">
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
                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->foto_web;?>"
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


    <div class="row my-3" style="display:<?=$show_consentimiento2;?>;"  id="bloque_consentimiento2">
        <div class="col-lg-6  offset-lg-3">
            <?= $form->field($model, 'file_evidencia_consentimiento')->widget(FileInput::classname(), [
                    'options' => ['accept' => '*'],
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
                    ])->label(); ?>
        </div>
        <div class="col-lg-6  offset-lg-3">
            <?php
            if(isset($model->evidencia_consentimiento) && $model->evidencia_consentimiento!= null && $model->evidencia_consentimiento != '' && $model->evidencia_consentimiento != ' '){
                $imageevidencia = '<span class="color15" style="font-size:100px"><i class="bi bi-folder-fill"></i></span><span class="badge bgtransparent2 color11 font12 m-1">Evidencia Consentimiento</span>';
                $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->evidencia_consentimiento;
                $ret = Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia Consentimiento'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                echo $ret;
            }
            ?>
        </div>
    </div>


    <div class="row mt-5"  style="display:<?=$show_guardar;?>;" id="bloque_guardar">
        <div class="col-lg-4 offset-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarconsentimiento','value'=>'poes']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>



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