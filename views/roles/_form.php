<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row my-3">
        <div class="col-lg-4">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
                'options' => ['placeholder' => 'Seleccione ...'],
            ]);?>
        </div>
    </div>

    <div class="row my-3 p-2">
        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  '<i class="bi bi-columns-gap"></i>';?></span>
                        Permisos
                    </label>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-lg-3">
                    <?php
            echo $form->field($model, 'permisos_all')->checkBox([
                'label' => '<span class="font500">Todos los Permisos</span>',
                'onChange'=>'cambiaPermisos("permisos_all")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
                </div>
            </div>
            <div class="row my-3 p-2">
                <table class="table table-hover table-striped table-sm table-borderless">
                    <thead>
                        <tr>
                            <th class="text-center color3" style="font-weight:600; width:15%;">
                                <?php echo Yii::t('app','Módulo');?>
                            </th>
                            <th class="text-center color3" style="font-weight:600;width:5%;">
                                <?php echo '';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #4149D9;">
                                <?php echo Yii::t('app','Ver Listado').'<span class="mx-3"><i class="bi bi-list-ol"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #636AF2;">
                                <?php echo Yii::t('app','Exportar Listado').'<span class="mx-3"><i class="bi bi-share-fill"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #4149D9;">
                                <?php echo Yii::t('app','Crear').'<span class="mx-3"><i class="bi bi-plus-lg"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #636AF2;">
                                <?php echo Yii::t('app','Ver').'<span class="mx-3"><i class="bi bi-eye-fill"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #4149D9;">
                                <?php echo Yii::t('app','Actualizar').'<span class="mx-3"><i class="bi bi-pen-fill"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #636AF2;">
                                <?php echo Yii::t('app','Expediente').'<span class="mx-3"><i class="bi bi-folder-fill"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #4149D9;">
                                <?php echo Yii::t('app','Cerrar Expediente').'<span class="mx-3"><i class="bi bi-folder2-open"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #636AF2;">
                                <?php echo Yii::t('app','Eliminar').'<span class="mx-3"><i class="bi bi-trash"></i></span>';?>
                            </th>
                            <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #4149D9;">
                                <?php echo Yii::t('app','Entrega Resultados').'<span class="mx-3"><i class="bi bi-card-checklist"></i></span>';?>
                            </th>
                             <th class="text-center text-light"
                                style="font-weight:600;width:8%;background-color: #636AF2;">
                                <?php echo Yii::t('app','Ver Documentos').'<span class="mx-3"><i class="bi bi-file-earmark-pdf-fill"></i></span>';?>
                            </th> 
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <!-- FILA 0 ENCABEZADO-->
                            <th class="color3">

                            </th>
                            <th class="color3">

                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <?php
                            echo $form->field($model, 'columna_listado')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onClick'=>'cambiaPermisos("columna_listado")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <?php
                            echo $form->field($model, 'columna_exportar')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_exportar")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <?php
                            echo $form->field($model, 'columna_crear')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_crear")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <?php
                            echo $form->field($model, 'columna_ver')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_ver")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <?php
                            echo $form->field($model, 'columna_actualizar')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_actualizar")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <?php
                            echo $form->field($model, 'columna_expediente')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_expediente")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <?php
                            echo $form->field($model, 'columna_cerrarexpediente')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_cerrarexpediente")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <?php
                            echo $form->field($model, 'columna_eliminar')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_eliminar")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <?php
                            echo $form->field($model, 'columna_entrega')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_entrega")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <?php
                            echo $form->field($model, 'columna_documento')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                            'onChange'=>'cambiaPermisos("columna_documento")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </th>
                        </tr>

                        <tr>
                            <!-- FILA 1 EMPRESAS-->
                            <?php $index = 1;?>

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Empresas</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_1')->checkBox([
                            'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_1")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'empresas_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'empresas_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'empresas_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'empresas_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'empresas_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 2 TURNOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Turnos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_2')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_2")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'turnos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'turnos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'turnos_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 3 FIRMAS MEDICO LABORAL-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Firmas Medico Laboral</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_3')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_3")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'firmas_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'firmas_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'firmas_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'firmas_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'firmas_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 4 FORMATO DE CONSENTIMIENTOS-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Formato de Consentimientos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_4')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_4")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consentimientos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consentimientos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consentimientos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consentimientos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consentimientos_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 5 LISTADO DE TRABAJADORES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Listado de Trabajadores</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_5')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_5")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'trabajadores_expediente')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-folder-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'trabajadores_delete')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 6 HISTORIAL DOCUMENTOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Historial Documentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_6')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_6")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historial_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historial_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 7 PUESTOS DE TRABAJO-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Puestos de Trabajo</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_7')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_7")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'puestos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'puestos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'puestos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'puestos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'puestos_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 8 VACANTES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Vacantes</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_8')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_8")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'vacantes_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'vacantes_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'vacantes_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'vacantes_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'vacantes_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 9 REQUISITOS MINIMOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Requisitos Mínimos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_9')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_9")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'requerimientos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'requerimientos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <!-- <?php
                            echo $form->field($model, 'requerimientos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?> -->
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'requerimientos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'requerimientos_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 10 INCAPACIDADES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Incapacidades</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_10')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_10")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'incapacidades_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'incapacidades_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'incapacidades_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'incapacidades_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'incapacidades_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 11 CARGA MASIVA DE TRABAJADORES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Carga Masiva de Trabajadores</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_11')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_11")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargatrabajadores_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargatrabajadores_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargatrabajadores_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargatrabajadores_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargatrabajadores_eliminar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 12 CONSULTAS MEDICAS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Consultas Médicas</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_12')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_12")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consultas_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consultas_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consultas_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consultas_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'consultas_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 13 HISTORIAS CLINICAS-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Historias Clínicas</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_13')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_13")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'historias_cerrarexpediente')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-folder2-open"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'historias_delete')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                            
                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 14 DIAGNOSTICOS CIE-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Diagnósticos CIE</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_14')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_14")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'diagnosticoscie_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'diagnosticoscie_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'diagnosticoscie_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'diagnosticoscie_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'diagnosticoscie_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 15 CUESTIONARIO NORDICO-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Cuestionario Nórdico</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_15')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_15")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'nordicos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'nordicos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'nordicos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'nordicos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 16 EVALUACION ANTROPOMETRICA-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Evaluación Antropométrica</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_16')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_16")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'antropometricos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'antropometricos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'antropometricos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'antropometricos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 17 STOCK ACTUAL-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Stock Actual</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_17')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_17")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstock_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstock_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 18 BITACORA DE MOVIMIENTOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Bitácora de Movimientos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_18')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_18")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosbitacora_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosbitacora_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosbitacora_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosbitacora_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosbitacora_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 19 CATALOGO DE MEDICAMENTOS-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Catálogo de Medicamentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_19')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_19")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentos_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentos_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentos_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentos_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentos_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 20 MINIMOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Mínimos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_20')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_20")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstockmin_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstockmin_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstockmin_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstockmin_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'medicamentosstockmin_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 21 STOCK ACTUAL (EPP)-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Stock Actual EPP</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_21')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_21")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstock_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstock_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 22 BITACORA DE MOVIMIENTOS (EPP)-->

                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Bitácora de Movimientos EPP</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_22')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_22")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsbitacora_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsbitacora_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsbitacora_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsbitacora_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsbitacora_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 23 CATALOGO (EPP)-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Catálogo EPP</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_23')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_23")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'epp_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'epp_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'epp_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'epp_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'epp_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 24 MINIMOS(EPP)-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Mínimos EPP</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_24')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_24")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstockmin_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstockmin_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstockmin_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstockmin_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'eppsstockmin_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 25 POES-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) POES</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_25')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_25")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'poes_entrega')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-card-checklist"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'poes_documento')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-file-earmark-pdf-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 26 CONFIGURAR LISTADO DE ESTUDIOS-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Configurar Listado de Estudios</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_26')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_26")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'estudios_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'estudios_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'estudios_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'estudios_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'estudios_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 27 ORDENES DE TRABAJO - POE -->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Ordenes de Trabajo - POE</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_27')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_27")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'ordenpoe_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'ordenpoe_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'ordenpoe_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'ordenpoe_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'ordenpoe_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 28 CARGA MASIVA - POES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Carga Masiva - POES</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_28')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_28")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargapoes_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargapoes_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargapoes_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargapoes_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'cargapoes_eliminar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 29 USUARIOS-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Usuarios</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_29')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_29")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'usuarios_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'usuarios_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'usuarios_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'usuarios_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'usuarios_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 30 CONFIGURACION-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Configuración</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_30')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_30")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'configuracion_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'configuracion_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'configuracion_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'configuracion_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 31 CONFIGURAR CATEGORIAS DE ESTUDIO-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Configurar Categorias de Estudio</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <?php
                            echo $form->field($model, 'linea_31')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_31")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'categoriaestudio_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'categoriaestudio_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'categoriaestudio_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'categoriaestudio_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'categoriaestudio_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 32 ROLES-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Roles</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_32')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_32")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'roles_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'roles_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'roles_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'roles_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'roles_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>


                        <tr>
                            <!-- FILA 33 PROGRAMAS DE SALUD-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Programas de Salud</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_33')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_33")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_exportar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_crear')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>


                        <tr>
                            <!-- FILA 34 PROGRAMAS DE SALUD EN HISTORIA CLINICA-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>)Registrar Programas de Salud en Historia Clínica</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_34')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_34")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'programasalud_hc')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-folder-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 35 CORREGIR HISTORIA CLINICA-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Corregir Historia Clínica</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_35')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_35")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'historias_corregir')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-unlock-fill"></i><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>


                        <tr>
                            <!-- FILA 36 DIAGRAMAS-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Diagrama Empresa</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_36')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_36")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'diagrama_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                            <?php
                            echo $form->field($model, 'diagrama_ver')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                             <?php
                            echo $form->field($model, 'diagrama_actualizar')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>


                        <tr>
                            <!-- FILA 37 PAPELERA DE RECICLAJE-->

                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Papelera de Reciclaje</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <?php
                            echo $form->field($model, 'linea_37')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                            'onChange'=>'cambiaPermisos("linea_37")',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?>
                            </td>
                            <td class="text-center">
                                <?php
                            echo $form->field($model, 'papelera_listado')->checkBox([
                            'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                            'onChange'=>'',
                            'class' => 'largerCheckbox25',
                            'options' => [
                            ]])->label(false);
                            ?> 
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                            
                            </td>
                            <td class="text-center">
                            
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row my-3 p-2">
        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  '<i class="bi bi-pen"></i>';?></span>
                        Firma
                    </label>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-lg-12">
                    <?php
            echo $form->field($model, 'admite_firma')->checkBox([
                'label' => '¿Este Rol Requiere Firma? Marque en caso de "SI"',
                'onChange'=>'',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
                </div>
            </div>
        </div>
    </div>


    <?php
    $showlimite = 'none';
    if(Yii::$app->user->identity->role->hidden == 1){
        $showlimite = 'block';
    }
    ?>
    <div class="row my-3 p-2" style="display:<?php echo $showlimite;?>;">
        <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span
                            class="mx-2"><?php echo  '<i class="bi bi-clock"></i><i class="bi bi-pencil-fill"></i>';?></span>
                        Limitar Tiempo
                    </label>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-lg-12">
                    <?php
            echo $form->field($model, 'tiempo_limitado')->checkBox([
                'label' => '¿Este rol tiene limite de tiempo para "crear" o "editar" informacion?, Marque en caso de "SI"',
                'onChange'=>'limitaTiempo("Roles")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>

                <?php
                 $show = 'none';
                 if($model->tiempo_limitado == 1){
                     $show = 'block';
                 }
                ?>
                </div>
                <div class="col-lg-12 mt-4 font500 seccion_tiempo" style="display:<?php echo $show;?>;">
                    Ingrese duración permitida
                </div>

                <div class="col-lg-1 mt-3 seccion_tiempo" style="display:<?php echo $show;?>;">
                    <?= $form->field($model, 'tiempo_dias')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'1', 'maxlength' => true, 'onchange' => ''])->label() ?>
                </div>
                <div class="col-lg-1 mt-3 seccion_tiempo" style="display:<?php echo $show;?>;">
                    <?= $form->field($model, 'tiempo_horas')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'1', 'maxlength' => true, 'onchange' => ''])->label() ?>
                </div>
                <div class="col-lg-1 mt-3 seccion_tiempo" style="display:<?php echo $show;?>;">
                    <?= $form->field($model, 'tiempo_minutos')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'1', 'maxlength' => true, 'onchange' => ''])->label() ?>
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