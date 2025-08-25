<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use kartik\file\FileInput;

?>
<?php
$foto = '';

if(isset($model->foto) && $model->foto != ""){
    $foto = '<div class="circularbig2 y_centercenter" style="background-image: url(\'/web/resources/Empresas/'.$model->id_empresa."/Trabajadores/".$model->id."/Documentos/".$model->foto.'\');  background-position: center; background-size: cover;"></div>';
}
?>
<div class="trabajadores-view p-0">
    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>
    <div class="container-fluid bg-soft p-0">

        <div class="p-3 rounded-3 bg-soft borderbluetop shadow">
            <div class="row mb-3">
                <div class="col-lg-12 font12 font500 text-center">
                    STATUS DOCUMENTOS
                </div>
                <?php
            $ret = '';
            if($model->status_documentos == 0){
                $ret =  '<div class="col-lg-4 offset-lg-4 p-2 text-center font500 rounded-3 bgpendiente font14 text-light text-uppercase">PENDIENTE</div>';
            }else if( $model->status_documentos == 1){
                $ret =  '<div class="col-lg-4 offset-lg-4 p-2 text-center font500 rounded-3 bgcumple font14 text-light text-uppercase">CUMPLE</div>';
            } else if( $model->status_documentos == 2){
                $ret =  '<div class="col-lg-4 offset-lg-4 p-2 text-center font500 rounded-3 bgnocumple font14 text-light text-uppercase">NO CUMPLE</div>';
            }
            echo $ret;
            ?>
            </div>
            <div class="text-center">
                <div class="circularbig3 text-center"><?php echo $foto;?></div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-4 offset-lg-4">
                    <h1 class="title1">
                        <?php echo $model->nombre.' '.$model->apellidos?>
                    </h1>
                    <h6 class="color6">
                        <?php if(isset($model->puesto)){
                      echo '<i class="bi bi-briefcase-fill"></i> '.$model->puesto->nombre;
                }?>
                    </h6>
                </div>
                <div class="col-lg-4 offset-lg-4">
                    <?php
            if(isset($model->sexo) && $model->sexo != null && $model->sexo != ''){
                $sexos = ['1'=>'Hombre','2'=>'Mujer','3'=>'Otro'];
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$sexos[$model->sexo].'</span>';
            }
            if(isset($model->edad) && $model->edad != null && $model->edad != ''){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$model->edad.' Años</span>';
            }
            
            if(isset($model->turno) && $model->turnoactual ){
                echo '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-calendar3"></i> Turno '.$model->turnoactual->turno.'</span>';
            }
            ?>

                </div>

            </div>
        </div>


        <div class="p-3 rounded-3 bg-soft borderbluetop shadow mt-3"  style="display:none;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 offset-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><i class="bi bi-clipboard"></i></span>
                        QR de Maquinaria
                    </label>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4 offset-lg-4 mb-4" style="display:none;">
                    <?= $form->field($model, 'code_maquina')->textInput(['maxlength' => true,'class'=>"form-control text-center form-controlbig", 'placeholder'=>'CÓDIGO DE MAQUINARIA']) ?>
                </div>
                <div class="col-lg-4 offset-lg-4">

                    <?= $form->field($model, 'file_qrmaquina')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
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
            </div>


        </div>

        <div class="row" style="display:none">
            <div class="col-lg-4 offset-lg-4 d-grid">
                <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12 mt-3">
                <?php
                $retestudios = '';
                if($model->testudios){

                    $retestudios = '<table class="table table-hover font12">
                    <thead>
                      <tr>
                        <td width="5%"></td>
                        <td width="65%" class="font500">Estudio</td>
                        <td width="30%" class="font500">Status</td>
                      </tr>
                    </thead>
                    <tbody>';

                    foreach($model->testudios as $key=>$estudio){

                        $index = $key+1;
                        $nombre_estudio = '';
                        $status_estudio ='';

                        if($estudio->estudio){
                            $nombre_estudio = $estudio->estudio->estudio;
                        }

                        if($estudio->status == 1){
                            $status_estudio = '<span class="badge bgcumple m-1 font14 text-light text-uppercase">Vigente</span>';
                        } else  if($estudio->status == 2){
                            $status_estudio = '<span class="badge bgnocumple m-1 font14 text-light text-uppercase">Vencido</span>';
                        } else if($estudio->status == 0){
                            $status_estudio = '<span class="badge bgpendiente m-1 font14 text-light text-uppercase">Indefinido</span>';
                        }

                        $retestudios .= '<tr>
                        <td>'.$index.'</td>
                        <td>'.$nombre_estudio.'</td>
                        <td>'.$status_estudio.'</td>
                        </tr>';
                       
                    }

                    $retestudios .= '
                    </tbody>
                    </table>';
                }

                echo $retestudios;
                ?>
            </div>
        </div>


    </div>
    <?php ActiveForm::end(); ?>
</div>