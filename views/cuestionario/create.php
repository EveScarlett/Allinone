<?php
use yii\helpers\Html;


/** @var yii\web\View $this */
/** @var app\models\Cuestionario $model */

$this->title = 'Cuestionario nórdico de Kuorinka';
// $this->params['breadcrumbs'][] = ['label' => 'Cuestionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuestionario-create">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <?php 
        if (isset($_GET['message']) && isset($_GET['type'])) {
            echo "<script>
                Swal.fire(
                    '".$_GET['message']."',
                    '',
                    '".$_GET['type']."'
                )
            </script>";
        } else if (isset($message) && isset($type)) {
            echo "<script>
                Swal.fire(
                    '".$message."',
                    '',
                    '".$type."'
                )
            </script>";

            unset($message, $type, $title);
        }
    ?>

        <?= $this->render('_form', [
        'model' => $model,
        'm_trabajadores' => $m_trabajadores,
        'm_empresas' => $m_empresas,
        'm_detalle' => $m_detalle,
        'm_medicos' => $m_medicos
    ]) ?>
    </div>
</div>