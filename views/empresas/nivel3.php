<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use app\models\Paises;
use app\models\Trabajadores;
use app\models\Areas;
use app\models\Kpis;
use app\models\Puestostrabajo;
use app\models\Consultorios;
use app\models\Programaempresa;
use app\models\Turnos;
use app\models\ProgramaTrabajador;
use app\models\Poes;
use app\models\ProgramaSalud;
use app\models\Consultas;

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

$asterisco = '<span class="px-2 color11 font11"><i class="bi bi-asterisk"></i></span>';
?>

<?php
$label = 'Nivel 3';

if($empresa){
    $label = $empresa->label_nivel3;
}
?>

<?php
$programas = ArrayHelper::map(ProgramaSalud::find()->orderBy('nombre')->all(), 'id', function($data){
        $ret = 'PS - '.$data['nombre'];
        
        return $ret;
});

$kpis = [
    'A'=>'ACCIDENTES',
    'B'=>'NUEVOS INGRESOS',
    'C'=>'INCAPACIDADES',
    'E'=>'POES'
];

$kpis_mixed = $kpis + $programas;
?>

<div class="ubicaciones-form p-3">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_nivelorganizacional1')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-12" style="display:none;">
            <?= $form->field($model, 'id_nivelorganizacional2')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
        <?php if($empresa): ?>
        <span class="font12 color6 font600">
            <?php
            if($empresa){
                echo $empresa->comercial;
            }
            ?>
        </span>
        <?php endif; ?>
        <?php if($nivel_1): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_1){
                echo $nivel_1->pais->pais;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_2): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_2){
                echo $nivel_2->nivelorganizacional2;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_3): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_3){
                echo $nivel_3->nivelorganizacional3;
            }
            ?>
        </span>
        <?php endif; ?>

        <?php if($nivel_4): ?>
        <span class="color3 mx-2"><i class="bi bi-chevron-right"></i></span>
        <span class="font12 color6 font600">
            <?php
            if($nivel_4){
                echo $nivel_4->nivelorganizacional4;
            }
            ?>
        </span>
        <?php endif; ?>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            <?= $form->field($model, 'nivelorganizacional3')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label($label); ?>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            <?= $form->field($model, 'actividad')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
        </div>
    </div>


    <div class="row my-3">
        <div class="col-lg-6">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(); ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12 d-grid">
            <?= Html::submitButton('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block']) ?>
        </div>
    </div>


    <div class="container p-3 shadow rounded-3 mt-5">
        <div class="row mt-2">
            <div class="col-lg-10">
                <h1 class="title1">
                    QTY Trabajadores Activos:
                    <span class="p-2 bgcolor2 rounded-3">
                        <?php
                    echo $model->qty_trabajadores;
                    ?>
                    </span>
                </h1>
            </div>
            <div class="col-lg-2 p-2 rounded-3 bgcolor14">
                <label class="font11">Cumplimiento</label>
                <div class="title1">
                    <?php
                    echo(number_format($model->kpi_cumplimiento, 2, '.', ',')).'%';
                ?>
                </div>
            </div>
        </div>
        <?php
        $content_areas = '';
        $content_puestos = '';
        $content_kpis = '';

        $cantidad_niveles = $empresa->cantidad_niveles;

        $nivel = 3;
        $qty_areas = 0;
        $qty_puestos = 0;
        $qty_kpis = 0;

        if($model){
            $trabajadores_activos =  null;
            if($nivel == $cantidad_niveles){
                $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$model->id])->andWhere(['in','status',[1,3]])->all();
            }
            //dd($trabajadores_activos);

            $id_trabajadores = [];
            if($trabajadores_activos){
                foreach($trabajadores_activos as $key=>$trab){
                    array_push($id_trabajadores, $trab->id);
                }
            }


            $retareas = [];
            $retkpis = [];
            $retpuestos = [];


            if($nivel == $cantidad_niveles){
                $retareas = Areas::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->all();
                $retkpis = Kpis::find()->where(['id_superior'=>$model->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$empresa->id])->andWhere(['status'=>1])->all();
                $retpuestos = Puestostrabajo::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel3'=>$model->id])->andWhere(['status'=>1])->all();
            }


            $qty_areas = count($retareas);
            $qty_puestos = count($retpuestos);
            $qty_kpis = count($retkpis);

            $id_areas = [];
            //dd($retareas,$retpuestos,$retkpis);
            $content_areas .= '<table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width:5%" class="font600 color3"></th>
                            <th style="width:85%" class="font600 color3">Áreas</th>
                            <th style="width:10%" class="font600 color3">Qty. T</th>
                        </tr>
                    </thead>
            <tbody>';
            foreach($retareas as $key=>$data){
                $index = ($key + 1 );
                $name = $data->area;
                $qty = 0;
                
                $trabajadores_area = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$model->id])->andWhere(['id_area'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                
                $qty = count($trabajadores_area);
               
                $content_areas .= '
                <tr>
                    <td style="width:5%">'.$index.'</td>
                    <td style="width:85%">'.$name.'</td>
                    <td style="width:10%">'.$qty.'</td>
                </tr>';
            }
            $content_areas .= '</tbody>
            </table>';


            $content_puestos .= '<table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width:5%" class="font600 color3"></th>
                            <th style="width:85%" class="font600 color3">Puestos</th>
                            <th style="width:10%" class="font600 color3">Qty. T</th>
                        </tr>
                    </thead>
            <tbody>';
            foreach($retpuestos as $key=>$data){
                $index = ($key + 1 );
                $name = $data->nombre;
                $qty = 0;

                $trabajadores_puesto = Trabajadores::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$model->id])->andWhere(['id_puesto'=>$data->id])->andWhere(['in','status',[1,3]])->all();
                
                $qty = count($trabajadores_puesto);
               

                $content_puestos .= '
                <tr>
                    <td style="width:5%">'.$index.'</td>
                    <td style="width:85%">'.$name.'</td>
                    <td style="width:10%">'.$qty.'</td>
                </tr>';
            }
            $content_puestos .= '</tbody>
            </table>';
           
           
            $id_kpi = [];
            $content_kpis .= '<table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width:5%" class="font600 color3"></th>
                            <th style="width:85%" class="font600 color3">KPIs</th>
                            <th style="width:10%" class="font600 color3">Qty. T</th>
                        </tr>
                    </thead>
            <tbody>';
            foreach($retkpis as $key=>$data){
                $index = ($key + 1 );
                $name = '';
                $qty = 0;

                if (array_key_exists($data->kpi,$kpis)){
                    $name = $kpis[$data->kpi];
                }
        
                if($data->kpi == 'D'){
                    $name = 'PS - ';
                    if($data->programa){
                        $name .= $data->programa->nombre;
                    }

                    $trabajadores_kpi = ProgramaTrabajador::find()->where(['id_programa'=>$data->id_programa])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>1])->all();
                    if($trabajadores_kpi){
                        $qty = count($trabajadores_kpi);
                    }
                } else {
                    
                    if($data->kpi == 'A'){//ACCIDENTES

                        $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$nivel_3->id])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        $qty = count($accidentes_kpi);

                    } else if($data->kpi == 'B'){//NUEVOS INGRESOS
                    
                    } else if($data->kpi == 'C'){//INCAPACIDADES
                    
                        $hoy = date('Y-m-d');
                        $incapacidades_kpi = [];
                        $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$nivel_3->id])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                        $qty = count($incapacidades_kpi);

                    } else if($data->kpi == 'E'){//POES
                        $anio_before = date('Y-m-d', strtotime('-1 years'));
                        $poes_kpi = [];
                        $poes_kpi = Poes::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_nivel1'=>$nivel_1->id])->andWhere(['id_nivel2'=>$nivel_2->id])->andWhere(['id_nivel3'=>$nivel_3->id])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                        $qty = count($poes_kpi);
                    }
                }
                $content_kpis .= '
                <tr>
                    <td style="width:5%">'.$index.'</td>
                    <td style="width:85%">'.$name.'</td>
                    <td style="width:10%">'.$qty.'</td>
                </tr>';
                
            }
            $content_kpis .= '</tbody>
            </table>';
             
            echo $content_areas;
            echo $content_puestos;
            echo $content_kpis;
        }
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>