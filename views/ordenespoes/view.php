<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Ordenespoes $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ordenes de Trabajo - POE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-.245z"/>
</svg>';

$iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
<path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
<path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
</svg>';
?>
<div class="ordenespoes-view">

    <div class="container">
        <h1 class="title1"><?= Html::encode($this->title) ?></h1>

        <div class="row mb-3">
            <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
                <div class="row m-0 p-0">
                    <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                        <label class="">
                            <span class="mx-2"><?php echo  $iconclip;?></span>
                            Datos Generales POE
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="control-label"><?php echo $model->getAttributeLabel('id_empresa');?></div>
                        <div class="form-control"><?php echo $model->empresa->comercial;?></div>
                    </div>
                    <div class="col-lg-2 offset-lg-2">
                        <div class="control-label"><?php echo $model->getAttributeLabel('anio');?></div>
                        <div class="form-control"><?php echo $model->anio;?></div>
                    </div>
                    <div class="col-lg-8 my-3">
                        <div class="control-label"><?php echo $model->getAttributeLabel('descripcion');?></div>
                        <div class="form-control"><?php echo $model->descripcion;?></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Estudios a Realizar
                                </label>
                            </div>
                        </div>

                        <div class="row my-3">

                            <div class="col-lg-12">
                                <?php
                                $ret = '';
                                if($model->estudios){
                                    $ret = '<table class="table table-bordered table-hover table-sm text-little">';
                                    $ret .= '<thead class="table-dark"><tr class="text-center">
                                    <th width="30%">Categoria</th>
                                    <th width="70%">Estudio</th>
                                    </tr></thead><tbody>';
            
                                    foreach($model->estudios as $key=>$estudio){
                                        $pdf = '';
                                        $pdf2 = '';
                                        $tipo = '';
                                        $nombre = '';
                                        $diagnostico = '';
                                        $evolucion = '';
                                        $color = '#FFFFFF';
            
                                        if($estudio->categoria){
                                            $tipo = $estudio->categoria->nombre;
                                            $color =$estudio->categoria->logo;
                                        }
            
                                        if($estudio->estudio){
                                            $nombre =  $estudio->estudio->nombre;
                                        }
                                        
            
                                        $ret .= '<tr>
                                        <td colspan="1" class="" style="background-color:'.$color.'8e;">'.$tipo.'</td>
                                        <td colspan="1" class="">'.$nombre.'</td>
                                        </tr>';
            
                                    }
            
                                    $ret .= '</tbody></table>';
            
                                } else{
                                    $ret = '<span class="color6">SIN ESTUDIOS</span>';
                                }
                                echo $ret;
                                ?>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
                        <div class="row m-0 p-0">
                            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                                <label class="">
                                    <span class="mx-2"><?php echo  $iconclip;?></span>
                                    Listado de Trabajadores
                                </label>
                            </div>
                        </div>

                        <div class="row px-3 py-3">
                            <?php
                        $ret = '';
                        if(isset($model->trabajadores)){
                            $ret = '<table class="table table-bordered table-hover table-sm text-little">';
                                    $ret .= '<tbody>';
                            foreach($model->trabajadores as $key=>$trabajador){
                                if($trabajador->trabajador){
                                    //dd($trabajador->trabajador);
                                    $ret .= '<tr>
                                        <td width="5%">'.($key+1).'</td>
                                        <td  width="95%">'.$trabajador->trabajador->nombre_trabajador.'</td>
                                        </tr>';
                                }

                            }
                            $ret .= '</tbody></table>';
                        }
                        echo $ret;
                        ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>