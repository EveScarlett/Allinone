<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Hccohc;

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

?>

<div class="ubicaciones-form p-4">

    <?php $form = ActiveForm::begin(['id'=>'formenlace']); ?>

    <div class="row my-2 boxtitle2 p-2 rounded-4">
        <div class="col-lg-12">
            Marque la <span class="font500">Historia Clínica</span> existente del trabajador. Si no existe realicela
            desde el botón "Realizar Estudio"
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="font12" style="width:5%;"></th>
                        <th style="width:30%;">HC</th>
                        <th style="width:30%;">Fecha</th>
                        <th style="width:25%;">Seleccione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $image = '<span class="color1" style="font-size:40px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    $image2 = '<span class="color2" style="font-size:40px"><i class="bi bi-file-earmark-text-fill"></i></span>';

                    $ret_hc = '';
                    $hcs = Hccohc::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['<>','status',3])->orderBy(['id'=>SORT_DESC])->all();
                    
                    foreach($hcs as $key=>$hc){
                        $index = $key + 1;
                        $doc_hc = '';
                        $fecha = '';
                        $select = '';

                        $doc_hc = Html::a($image, Url::to(['hccohc/pdf','id' => $hc->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        //$doc_hc .= Html::a($image2, Url::to(['hccohc/pdf','id' => $hc->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);

                        $fecha = $hc->fecha;

                        $select =  $form->field($estudio, 'hc_enlazar['.$hc->id.']')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-link-45deg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    

                        $ret_hc .= ' <tr>
                        <td style="width:5%;">'.$index.'</td>
                        <td style="width:30%;">'.$doc_hc.'</td>
                        <td style="width:30%;">'.$fecha.'</td>
                        <td style="width:25%;">'.$select.'</td>
                        </tr>';

                    }

                    echo $ret_hc;
                    ?>
                   
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-12 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>