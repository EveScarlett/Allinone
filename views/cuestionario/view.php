<?php

use app\models\Areascuestionario;
use app\models\ListadoTrabajadores;
use app\models\Pacientes;
use app\models\Preguntas;
use app\models\TipoCuestionario;
use app\models\Usuarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

require_once('../controllers/funciones.php');

/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cuestionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$datos = [];
foreach ($m_detalle as $key => $value) {
    array_push($datos,[
        'pregunta' => $value->id_pregunta,
        'area' => $value->id_area,
        'respuesta' => $value->respuesta_1
    ]);
}

?>
<div class="cuestionario-view">

    <h3 class="label-secondary mb-5">
        <?= Html::encode("Detalles del cuestionario") ?>
    </h3>

    <div class="mb-2">
        <?= Html::a('<i class="bi bi-arrow-return-left"></i> Regresar', ['index','tipo'=>$model->id_tipo_cuestionario], ['class' => 'btn btn-dark btn-sm']) ?>
        <?php if (Yii::$app->user->can('cuestionario_nordico-editar') && false) { ?>
            <?= Html::a('<i class="bi bi-pencil-fill"></i> Editar', Url::toRoute(['update', 'id' => $model->id]) , ['class' => 'btn btn-dark btn-sm']) ?>
        <?php } ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id_paciente',
                'label' => 'No. de empleado',
                'value' => function ($model) {
                    if ($paciente = Pacientes::findOne($model->id_paciente)) {
                        return $paciente->no_empleado;
                    }
                } ,
                'visible' => false
            ],
            [
                'attribute' => 'id_paciente',
                'label' => 'Trabajador',
                'value' => function ($model) {
                    $ret = '';
                    if($model->trabajadorsmo){
                        $ret = $model->trabajadorsmo->nombre.' '.$model->trabajadorsmo->apellidos;
                    }
                    return $ret;
                }
            ],
            [
                'attribute' => 'nombre_empresa',
                'label' => 'Empresa'
            ],
            [
                'attribute' => 'fecha_cuestionario',
                'label' => 'Fecha del cuestionario',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->fecha_cuestionario, "full");
                }
            ],
            [
                'attribute' => 'id_cuestionario',
                'label' => 'Cuestionario',
                'value' => function ($model) {
                    return TipoCuestionario::findOne($model->id_tipo_cuestionario)->nombre;
                }
            ],
            [
                'attribute' => 'id_medico',
                'label' => 'Medico',
                'value' => function ($model) {
                    return Usuarios::findOne($model->id_medico)->name;
                }
            ]
        ],
    ]) ?>

    <div class="row bm-3">
        <div class="table-responsive shadow-lg">
            <table class="table table-sm align-middle table-hover table-bordered caption-top">
                <caption>Cuestionario</caption>
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Pregunta</th>
                        <th scope="col">Parte corporal</th>
                        <th scope="col">Respuesta</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $rowspan = [];
                        foreach ($datos as $fila)
                        {
                            array_push($rowspan, $fila["pregunta"]);
                        }

                        foreach ($datos as $key => $row) {
                            echo '<tr>';
                                if (CountValuesArray($rowspan, $row['pregunta']) > 0) {
                                    $rows = CountValuesArray($rowspan, $row['pregunta']);
                                    echo '<td rowspan="' . $rows . '">' . Preguntas::findOne($row['pregunta'])->pregunta .'</td>';
                                    RemoveValueArray($rowspan, $row["pregunta"]);
                                }

                                echo '<td>' . Areascuestionario::findOne($row['area'])->nombre . '</td>';
                                echo '<td>' . $row['respuesta'] . '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
