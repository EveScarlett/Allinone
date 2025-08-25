<?php

use app\models\Areas;
use app\models\ListadoTrabajadores;
use app\models\Pacientes;
use app\models\Preguntas;
use app\models\TipoCuestionario;
use app\models\UsuariosRh;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

require_once('../controllers/funciones.php');

/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Listado', 'url' => ['index']];
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

    <h3 class="label-secondary mb-4">
        <?= Html::encode("Detalles de la evaluación") ?>
    </h3>

    <div class="mb-2">
        <?= Html::a('<i class="bi bi-arrow-return-left"></i> Regresar', ['index'], ['class' => 'btn btn-dark btn-sm']) ?>
        <!-- <?= Html::a('<i class="bi bi-pencil-fill"></i> Editar', Url::toRoute(['update', 'id' => $model->id]) , ['class' => 'btn btn-dark btn-sm']) ?> -->
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
                },
                'visible' => false
            ],
            [
                'attribute' => 'id_paciente',
                'label' => 'Trabajador',
                'value' => function ($model) {
                    $ret = '';
                    ($model->sw == 2) ? $ret = $model->trabajadorsmo->nombre.' '.$model->trabajadorsmo->apellidos : $ret = $model->trabajador->nombre_completo;
                    return $ret;
                }
            ],
            [
                'attribute' => 'nombre_empresa',
                'label' => 'Empresa'
            ],
            [
                'attribute' => 'fecha_cuestionario',
                'label' => 'Fecha de evaluación',
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
                    return UsuariosRh::findOne($model->id_medico)->nombre;
                }
            ]
        ],
    ]) ?>

    <div class="row bm-3">
        <div class="table-responsive shadow-lg">
            <table class="table table-sm align-middle table-hover table-bordered caption-top">
                <caption>Pruebas diagnosticas</caption>
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Postura</th>
                        <th scope="col">Area</th>
                        <th scope="col">Medida</th>
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
                                
                                echo '<td>' . Areas::findOne($row['area'])->nombre . '</td>';
                                echo '<td>' . $row['respuesta'] . '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
