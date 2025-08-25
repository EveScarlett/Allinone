<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use kartik\file\FileInput;

?>
<?php
    //dd($model,$maquina,$relacion);    
?>
<div class="trabajadores-view p-3">
    <div class="container-fluid bg-soft p-0">
        <?php $form = ActiveForm::begin(['id'=>'formSMO',
    'options' => ['enctype' => 'multipart/form-data'],]); ?>

        <?php if(isset($model) && isset($maquina)):?>
        <div class="row m-0 p-0">
            <div class="col-lg-4" style="display:none;">
                <?= $form->field($maquina, 'id_trabajador')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
            </div>
            <div class="col-lg-4" style="display:none;">
                <?= $form->field($maquina, 'id_maquina')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
            </div>
            <div class="col-lg-4" style="display:none;">
                <?= $form->field($maquina, 'status_trabajo')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-lg-12 font12 font500 text-center">
                AUTORIZADO A OPERAR
            </div>
            <?php
            $ret = '';
            if( $autorizado == 1){
                $ret =  '<div class="title1 col-lg-4 offset-lg-4 p-2 text-center font500 rounded-3 bgcumple font14 text-light text-uppercase"><h2 class="mb-0">AUTORIZADO</h2></div>';
            } else if( $autorizado == 0){
                $ret =  '<div class="title1 col-lg-4 offset-lg-4 p-2 text-center font500 rounded-3 bgnocumple font14 text-light text-uppercase"><h2 class="mb-0">NO AUTORIZADO</h2></div>';
            }
            echo $ret;
            ?>
        </div>
        <?php endif;?>


        <?php if(isset($relacion)):?>
        <?php if(isset($autorizado) && $autorizado == 1 && $relacion->status_trabajo != 1):?>
        <div class="row rounded-3 mt-2">

            <div class="col-lg-12 font12 font500 text-center">
                INICIAR OPERACIÓN
            </div>
            <div class="col-6 d-grid">
                <?= Html::button('INICIAR', ['class' => 'btn btn-lg btn-primary btn-block','onclick'=>'cambiaStatusmaquina("1")']) ?>
            </div>
            <div class="col-6 d-grid">
                <?= Html::button('NO <i class="bi bi-x-lg"></i>', ['class' => 'btn btn-lg btn-danger btn-block','onclick'=>'cambiaStatusmaquina("2")']) ?>
            </div>
        </div>
        <?php endif;?>
        <?php if(isset($autorizado) && $autorizado == 1 && $relacion->status_trabajo == 1):?>
        <div class="row rounded-3 mt-2">
            <div class="container mb-3 font12">
                <div class="row">
                    <div class="col-6">
                        <?php
                        echo 'Fecha Inicio';
                        ?>
                    </div>
                    <div class="col-6 text-end">
                        <?php
                        echo 'Fecha Fin';
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <?php
                        $fecha_inicio = date('d/m/Y H:i A', strtotime($relacion->fecha_inicio));
                        echo $fecha_inicio;
                        ?>
                    </div>
                    <div class="col-6">

                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:50%"></div>
                </div>
            </div>
            <div class="col-lg-12 font12 font500 text-center">
                FINALIZAR OPERACIÓN
            </div>
            <div class="col-6 d-grid">
                <?= Html::button('FINALIZAR', ['class' => 'btn btn-lg btn-primary btn-block','onclick'=>'cambiaStatusmaquina("3")']) ?>
            </div>
            <div class="col-6 d-grid">
                <?= Html::button('NO <i class="bi bi-x-lg"></i>', ['class' => 'btn btn-lg btn-danger btn-block','onclick'=>'cambiaStatusmaquina("4")']) ?>
            </div>
        </div>
        <?php endif;?>
        <?php endif;?>

        <?php if(isset($maquina)):?>
        <?php
        $fotomaquina = '';
        if(isset($maquina->foto) && $maquina->foto != ""){
            $fotomaquina = '<div class="icon-cube y_centercenter" style="background-image: url(\'/web/resources/Maquinarias/'.$maquina->id.'/'.$maquina->foto.'\');  background-position: center; background-size: cover;"></div>';
        }
        ?>
        <div class="row rounded-3 shadow">
            <div class="col-12 p-2 text-center font500 bgcolor16 font14 text-uppercase border-bottom">
                Máquina
            </div>
            <div class="col-4">
                <div class="text-center"><?php echo $fotomaquina;?></div>
            </div>
            <div class="col-7 text-center">
                <label class="title2">
                    <?php echo $maquina->maquina?>
                </label>
                <div class="p-2 bg-light rounded-3 small text-dark"> <?php echo $maquina->clave?></div>
            </div>
        </div>
        <?php endif;?>
        <?php if(isset($model)):?>
        <?php
        $foto = '';
        if(isset($model->foto) && $model->foto != ""){
            $foto = '<div class="icon-cube y_centercenter" style="background-image: url(\'/web/resources/Empresas/'.$model->id_empresa."/Trabajadores/".$model->id."/Documentos/".$model->foto.'\');  background-position: center; background-size: cover;"></div>';
        }
        ?>

        <div class="row rounded-3 shadow mt-3">
            <div class="col-12 p-2 text-center font500 bgcolor16 font14 text-uppercase border-bottom">
                Trabajador
            </div>
            <div class="col-4">
                <div class="text-center"><?php echo $foto;?></div>
            </div>
            <div class="col-7 text-center">
                <label class="title2">
                    <?php echo $model->nombre.' '.$model->apellidos?>
                </label>
                <div class="p-2 bg-light rounded-3 small text-dark">
                    <?php if(isset($model->puesto)){
                      echo '<i class="bi bi-briefcase-fill"></i> '.$model->puesto->nombre;
                }?>
                </div>
            </div>

            <div class="col-lg-12 font12 font500 text-center">
                STATUS DOCUMENTOS
            </div>
            <div class="col-lg-12 mb-4">
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


        </div>
        <?php endif;?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php 
if ($status_msg == 100){
    echo Html::script('Swal.fire({  icon: "error", title: "NO AUTORIZADO", html: "'.$msg.'"});', ['defer' => true]);
} else if ($status_msg == 200){
    echo Html::script('Swal.fire({  icon: "success", title: "INICIO OPERACIÓN", html: "'.$msg.'"});', ['defer' => true]);
} else if ($status_msg == 400){
    echo Html::script('Swal.fire({  icon: "info", title: "OPERACIÓN FINALIZADA", html: "'.$msg.'"});', ['defer' => true]);
}
?>