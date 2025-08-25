<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Lineas;
use app\models\Ubicaciones;


use app\models\Areas;
use app\models\Consultorios;
use app\models\Turnos;
use app\models\Programaempresa;

use app\models\ProgramaSalud;

use app\models\Kpis;


use app\models\Trabajadorestudio;
use app\models\Historialdocumentos;
use app\models\Poes;
use app\models\Poeestudio;
use app\models\Hccohc;
use app\models\Consultas;
use app\models\Cuestionario;
use app\models\Puestotrabajador;
use app\models\Puestotrabajohriesgo;

use app\models\ProgramaTrabajador;

use app\models\Empresas;

/** @var yii\web\View $this */
/** @var app\models\Empresas $model */

$name_empresa = '';
if(Yii::$app->user->identity->empresa){
    $name_empresa = ' | '.Yii::$app->user->identity->empresa->comercial;

    try {
        $empresa_usuario = Yii::$app->user->identity->empresa;

        if($empresa_usuario){
            if($empresa_usuario->cantidad_niveles >= 1){
                if(Yii::$app->user->identity->nivel1_all != 1) {
                    $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
                    if(count($array_niveles_1) == 1){
                        $pais_n1= Paises::findOne($array_niveles_1[0]);
                        if($pais_n1){
                            $name_empresa .= ' / '.$pais_n1->pais;
                        }
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 2){
                $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(Yii::$app->user->identity->nivel2_all != 1 && (count($niveles_2) == 1)) {
                    $nivel_n2 = NivelOrganizacional2::findOne($niveles_2[0]);
                    if($nivel_n2){
                        $name_empresa .= ' / '.$nivel_n2->nivelorganizacional2;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 3){
                $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(Yii::$app->user->identity->nivel3_all != 1 && (count($niveles_3) == 1)) {
                    $nivel_n3 = NivelOrganizacional3::findOne($niveles_3[0]);
                    if($nivel_n3){
                        $name_empresa .= ' / '.$nivel_n3->nivelorganizacional3;
                    }
                }
            }
            if($empresa_usuario->cantidad_niveles >= 4){
                $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(Yii::$app->user->identity->nivel4_all != 1 && (count($niveles_4) == 1)) {
                    $nivel_n4 = NivelOrganizacional4::findOne($niveles_4[0]);
                    if($nivel_n4){
                        $name_empresa .= ' / '.$nivel_n4->nivelorganizacional4;
                    }
                }
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    
}

$this->title = 'Histórico de Documentos '.$name_empresa;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Empresas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}

$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}
//dd($empresas);
?>
<div class="empresas-view">

    <h1 class="title1 text-uppercase"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA EMPRESA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label(false); ?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-2">
                <?php
                echo 'Mostrando <span class="font500">'.count($workers).'</span> elementos';
                ?>
            </div>
            <div class="col-lg-8">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);','class'=>'form-control font30 text-center form-srch','placeholder'=>'Ingrese nombre Trabajador'])->label(false); ?>
            </div>
            <div class="col-lg-2 d-grid">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary btnnew btn-block font30']) ?>
            </div>
        </div>
    </div>



    <div class="container-fluid">
        <?php
        if($workers){
            foreach($workers as $key=>$worker){
                $retworker = '';

                $foto = '';

                $status_trabajador = '';

                if(isset($worker->foto) && $worker->foto != ""){
                    $avatar = '/resources/images/'.'av5.jpg';
                    if($worker->foto){
                        $avatar = '/resources/Empresas/'.$worker->id_empresa.'/Trabajadores/'.$worker->id.'/Documentos/'.$worker->foto;
                    }
                     
                    $filePath =  $avatar;
                    $foto = '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                    width: 150px;
                    height: 150px;']).'</span>';
                }

                $empresa = '';
                if($worker->empresa){
                    $empresa = $worker->empresa->comercial;

                    if($worker->nivel1){
                        $empresa .= ' / '.$worker->nivel1->pais->pais;
                    }

                    if($worker->nivel2){
                        $empresa .= ' / '.$worker->nivel2->nivelorganizacional2;
                    }

                    if($worker->nivel3){
                        $empresa .= ' / '.$worker->nivel3->nivelorganizacional3;
                    }

                    if($worker->nivel4){
                        $empresa .= ' / '.$worker->nivel4->nivelorganizacional4;
                    }
                }


                if($worker->status == 1){
                    $status_trabajador = '<span class="mx-1 color7"><i class="bi bi-circle-fill"></i></span>Status Activo';
                } else if($worker->status == 3){
                    $status_trabajador = '<span class="mx-1 color14"><i class="bi bi-circle-fill"></i></span>Status NI';
                } else if($worker->status == 5){
                    $status_trabajador = '<span class="mx-1 color11"><i class="bi bi-circle-fill"></i></span>Status Baja';
                }


                $retworker .= '<div class="row mt-3 bordercolor3">
                <div class="col-lg-4 p-2 card text-center">
                    <div class="col-lg-12">
                        '.$foto.'
                    </div>
                    <div class=" p-3">
                        <h2 class="title1">'.($key+1).') '.$worker->nombre.' '.$worker->apellidos.'</h2>
                        <h3 class="color3 font500">'.$empresa.'</h3>
                        <h5>'.$status_trabajador.'</h5>
                        <h5 class="my-2">Cumplimiento: <span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font500 text-end">'.$worker->porcentaje_cumplimiento.' %</span></h5>
                        <h6 class="color6">';

                if(isset($worker->puesto)){
                    $retworker .= '<i class="bi bi-briefcase-fill"></i> Puesto: '.$worker->puesto->nombre;
                }
                if(isset($worker->puesto_contable)){
                    $retworker .= '<br><span class=""><i class="bi bi-briefcase-fill"></i> Puesto Contable: '.$worker->puesto_contable.'</span>';
                }


                $retworker .= '<div class="col-lg-12">';
                if(isset($worker->sexo) && $worker->sexo != null && $worker->sexo != ''){
                    $sexos = ['1'=>'Hombre','2'=>'Mujer','3'=>'Otro'];
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$sexos[$worker->sexo].'</span>';
                }
                if(isset($worker->edad) && $worker->edad != null && $worker->edad != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1">'.$worker->edad.' Años</span>';
                }
                if(isset($worker->celular) && $worker->celular != null && $worker->celular != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-telephone mx-2"></i>'.$worker->celular.'</span>';
                }
                if(isset($worker->correo) && $worker->correo != null && $worker->correo != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-envelope mx-2"></i>'.$worker->correo.'</span>';
                }
                if(isset($worker->ruta) && $worker->ruta != null && $worker->ruta != ''){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-geo-alt-fill mx-2"></i> Ruta '.$worker->ruta.'</span>';
                }
                if(isset($worker->turno) && $worker->turnoactual ){
                    $retworker .= '<span class="badge bgcolor3 text-light rounded-pill font500 m-1"><i class="bi bi-calendar3"></i> Turno '.$worker->turnoactual->turno.'</span>';
                }
                if(isset($worker->antiguedad) && $worker->antiguedad ){
                    $retworker .= '<br><span class=""><i class="bi bi-hourglass-bottom"></i> Antigüedad: '.$worker->antiguedad.'</span>';
                }

                
                if(Yii::$app->user->identity->hidden_actions == 1){
                    $retworker .= '<br><span class="badge bg-dark text-light rounded-pill font500 m-1">ID: '.$worker->id.'</span>';
                }

                $retworker .= '</div>';

                $retworker .= '</h6>';

                $retworker .= '<div class="row mt-4"><input type="text" id="trabajadores-src-'.$worker->id.'"  name="worker_'.$worker->id.'" class="form-control icon-find rounded-3 bordercolor1 text-center" placeholder="Buscar Documentos" onkeyup=\'filtrarEstudios("'.$worker->id.'",this.value)\'></div>';

                $retworker .= '</div>
                </div>';


                $retworker .= '<div class="col-lg-8 p-2">
                    <div class="card">';


                
                
                



                //PUESTOS DE TRABAJO ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->puesto_cumplimiento.' %</span>Puestos de Trabajo</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="25%" class="font500">Puesto de Trabajo</td>
                        <td width="10%" class="text-center font500">Fecha Ingreso</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="10%" class="text-center font500">Fecha Fin</td>
                        <td width="20%" class="text-center font500">Antigüedad</td>
                        <td width="20%" class="text-center font500">Riesgos del Puesto</td>
                        </tr>';

                $worker_puestos = Puestotrabajador::find()->where(['id_trabajador'=>$worker->id])->orderBy(['status'=>SORT_ASC,'fecha_inicio'=>SORT_ASC])->all();
                if($worker_puestos){
                    foreach($worker_puestos as $keydocs1=>$estudio){
                        $nombre_doc = '';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $fechafin_doc = '--';
                        $aniofin_doc = '--';
                        $antiguedad = $estudio->antiguedad;
                        $riesgos_doc = '';

                        if($estudio->puesto){
                           $nombre_doc = $estudio->puesto->nombre; 
                        }

                        if($estudio->fecha_inicio != null && $estudio->fecha_inicio != '' && $estudio->fecha_inicio != ' '){
                            $fecha_doc = $estudio->fecha_inicio;
                            $anio_doc = date('Y', strtotime($estudio->fecha_inicio));
                        }

                        if($estudio->fecha_fin != null && $estudio->fecha_fin != '' && $estudio->fecha_fin != ' '){
                            $fechafin_doc = $estudio->fecha_fin;
                            $aniofin_doc = date('Y', strtotime($estudio->fecha_fin));
                        }

                        if($estudio->datariesgos){
                            foreach($estudio->datariesgos as $key4=>$riesgo){
                                $riesgos_doc .= $riesgo->riesgo;
                                if($key4 <(count($estudio->datariesgos)-1)){
                                    $riesgos_doc .= '<span class="color4 mx-1"><i class="bi bi-slash-lg"></i></span>';
                                }
                            }
                        }

                        $retworker .= '<tr id="puestotrabajo_'.$keydocs1.'_worker_'.$worker->id.'" value="_puestotrabajo_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="25%">'.$nombre_doc.'</td>
                        <td width="10%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="10%" class="text-center color9">'.$fechafin_doc.'</td>
                        <td width="20%" class="text-center ">'.$antiguedad.'</td>
                        <td width="20%" class="text-center">'.$riesgos_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //PUESTOS DE TRABAJO ----------------------------------------------------------------------


                


                //RIESGOS PUESTO ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->riesgo_cumplimiento.' %</span>Riesgos Expuesto - Actual</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="70%" class="font500">Riesgo</td>
                        <td width="15%" class="text-center font500">Fecha Inicio</td>
                        <td width="10%" class="text-center font500">Año</td>
                        </tr>';

                $worker_riesgos = Puestotrabajohriesgo::find()->where(['id_trabajador'=>$worker->id])->andWhere(['id_puesto'=>$worker->id_puesto])->orderBy(['fecha_inicio'=>SORT_ASC,'create_date'=>SORT_ASC])->all();
                if($worker_riesgos){
                    foreach($worker_riesgos as $keydocs1=>$estudio){
                        $nombre_doc = '';
                        $fecha_doc = '--';
                        $anio_doc = '--';

                        if($estudio->riesgo){
                           $nombre_doc = $estudio->riesgo->riesgo; 
                        }

                        if($estudio->fecha_inicio != null && $estudio->fecha_inicio != '' && $estudio->fecha_inicio != ' '){
                            $fecha_doc = $estudio->fecha_inicio;
                            $anio_doc = date('Y', strtotime($estudio->fecha_inicio));
                        }

                        $retworker .= '<tr id="riesgos_'.$keydocs1.'_worker_'.$worker->id.'" value="_riesgos_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="70%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=4>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //RIESGOS PUESTO ----------------------------------------------------------------------




                //RIESGOS PUESTO HISTORICO ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->riesgohistorico_cumplimiento.' %</span>Riesgos Histórico</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="70%" class="font500">Riesgo</td>
                        <td width="15%" class="text-center font500">Fecha Inicio</td>
                        <td width="10%" class="text-center font500">Año</td>
                        </tr>';

                $worker_riesgos = Puestotrabajohriesgo::find()->where(['id_trabajador'=>$worker->id])->andWhere(['<>','id_puesto',$worker->id_puesto])->orderBy(['fecha_inicio'=>SORT_ASC,'create_date'=>SORT_ASC])->all();
                if($worker_riesgos){
                    foreach($worker_riesgos as $keydocs1=>$estudio){
                        $nombre_doc = '';
                        $fecha_doc = '--';
                        $anio_doc = '--';

                        if($estudio->riesgo){
                           $nombre_doc = $estudio->riesgo->riesgo; 
                        }

                        if($estudio->fecha_inicio != null && $estudio->fecha_inicio != '' && $estudio->fecha_inicio != ' '){
                            $fecha_doc = $estudio->fecha_inicio;
                            $anio_doc = date('Y', strtotime($estudio->fecha_inicio));
                        }

                        $retworker .= '<tr id="riesgos_'.$keydocs1.'_worker_'.$worker->id.'" value="_riesgos_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="70%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=4>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //RIESGOS PUESTO HISTORICO ----------------------------------------------------------------------




                //PROGRAMAS DE SALUD ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->programasalud_cumplimiento.' %</span>Programas de Salud</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Riesgo</td>
                        <td width="15%" class="text-center font500">Fecha Ingreso</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Status</td>
                        </tr>';

                $worker_programas = ProgramaTrabajador::find()->where(['id_trabajador'=>$worker->id])->orderBy(['status'=>SORT_DESC,'fecha_inicio'=>SORT_DESC,'fecha_fin'=>SORT_DESC])->all();
                if($worker_programas){
                    foreach($worker_programas as $keydocs1=>$estudio){
                        $nombre_doc = '';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $status = '';

                        if($estudio->programa){
                           $nombre_doc = $estudio->programa->nombre; 
                        }

                        if($estudio->fecha_inicio != null && $estudio->fecha_inicio != '' && $estudio->fecha_inicio != ' '){
                            $fecha_doc = $estudio->fecha_inicio;
                            $anio_doc = date('Y', strtotime($estudio->fecha_inicio));
                        }

                        if($estudio->status == 1){
                            $status = '<span class="font10 color7 mx-2"><i class="bi bi-circle-fill"></i></span> Activo';
                        } else if($estudio->status == 2){
                            $status = '<span class="font10 color11 mx-2"><i class="bi bi-circle-fill"></i></span> Baja';
                        }

                        $retworker .= '<tr id="programas_'.$keydocs1.'_worker_'.$worker->id.'" value="_programas_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$status.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=4>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //PROGRAMAS DE SALUD ----------------------------------------------------------------------




                //EXPEDIENTE DOCUMENTOS ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->expediente_cumplimiento.' %</span>Expediente - Vista Actual</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                $worker_estudios = Trabajadorestudio::find()->where(['id_trabajador'=>$worker->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['fecha_documento'=>SORT_DESC,'id_estudio'=>SORT_ASC])->all();
                if($worker_estudios){
                    foreach($worker_estudios as $keydocs1=>$estudio){
                        $nombre_doc = '--';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';

                        if($estudio->estudio){
                            $nombre_doc = $estudio->estudio->estudio;
                        }

                        if($estudio->fecha_documento != null && $estudio->fecha_documento != '' && $estudio->fecha_documento != ' '){
                            $fecha_doc = $estudio->fecha_documento;
                            $anio_doc = date('Y', strtotime($estudio->fecha_documento));
                        }

                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';

                        if($estudio->evidencia && $estudio->evidencia != ''){
                            $filePath = 'resources/Empresas/'.$worker->id_empresa.'/Trabajadores/'.$worker->id.'/Documentos/'.$estudio->evidencia;
                            $evidencia_doc = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);                  
                        }

                        $retworker .= '<tr id="expediente_'.$keydocs1.'_worker_'.$worker->id.'" value="_expediente_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //EXPEDIENTE DOCUMENTOS ----------------------------------------------------------------------




                //HISTORICO DOCUMENTOS ----------------------------------------------------------------------
                /* $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src">Historico Expediente</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $worker_historico = Historialdocumentos::find()->where(['id_trabajador'=>$worker->id])->orderBy(['fecha_documento'=>SORT_DESC,'id_estudio'=>SORT_ASC])->all();
                if($worker_historico){
                    foreach($worker_historico as $keydocs1=>$estudio){
                        $nombre_doc = '--';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';

                        if($estudio->estudio){
                            $nombre_doc = $estudio->estudio->estudio;
                        }

                        if($estudio->fecha_documento != null && $estudio->fecha_documento != '' && $estudio->fecha_documento != ' '){
                            $fecha_doc = $estudio->fecha_documento;
                            $anio_doc = date('Y', strtotime($estudio->fecha_documento));
                        }

                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';

                        if($estudio->evidencia && $estudio->evidencia != ''){
                            $filePath = 'resources/Empresas/'.$worker->id_empresa.'/Trabajadores/'.$worker->id.'/Documentos/'.$estudio->evidencia;
                            $evidencia_doc = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);                  
                        }

                        $retworker .= '<tr>
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>'; */
                //HISTORICO DOCUMENTOS ----------------------------------------------------------------------




                //POES DOCUMENTOS ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->poe_cumplimiento.' %</span>Exámenes Médicos</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                $worker_poes = Poes::find()->where(['id_trabajador'=>$worker->id])->andWhere(['<>','status',2])->orderBy(['anio'=>SORT_DESC,'create_date'=>SORT_DESC])->all();
                
                if($worker_poes){

                    foreach($worker_poes as $keypoe=>$poe){
                        $status_entrega = '';

                        if($poe->status_entrega == 1){
                            $status_entrega = '<span class="font10 color7 mx-2"><i class="bi bi-circle-fill"></i></span>Entrega de resultados: COMPLETA';
                        } else {
                            $status_entrega = '<span class="font10 color11 mx-2"><i class="bi bi-circle-fill"></i></span>Entrega de resultados: FALTA';
                        }

                        $retworker .= '<tr>
                                <td width="60%" class="color3 font500" colspan=2>POE '.$poe->anio.' | '.$poe->create_date.'</td>
                                <td width="40%" class=" font500" colspan=3>'.$status_entrega.'</td>
                                </tr>';

                        $worker_estudiopoe = Poeestudio::find()->where(['id_poe'=>$poe->id])->orderBy(['fecha'=>SORT_ASC,'id_estudio'=>SORT_ASC])->all();
                        if($worker_estudiopoe){
                            foreach($worker_estudiopoe as $keydocs1=>$estudio){
                                $nombre_doc = '--';
                                $fecha_doc = '--';
                                $anio_doc = '--';
                                $evidencia_doc = 'SIN EVIDENCIA';

                                if($estudio->estudio){
                                    $nombre_doc = $estudio->estudio->nombre;
                                }

                                if($estudio->fecha != null && $estudio->fecha != '' && $estudio->fecha != ' '){
                                    $fecha_doc = $estudio->fecha;
                                }

                                $anio_doc = $poe->anio;

                                $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';

                                if($estudio->evidencia != '' && $estudio->evidencia != null){
                                    $filePath = 'resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/'.$estudio->evidencia;
                                    $evidencia_doc = Html::a('<span style="font-size:30px;" class="color3">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if($estudio->evidencia2 != '' && $estudio->evidencia2 != null){
                                    $filePath = 'resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/'.$estudio->evidencia2;
                                    $evidencia_doc .= Html::a('<span style="font-size:30px;" class="color4">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if($estudio->evidencia3 != '' && $estudio->evidencia3 != null){
                                    $filePath = 'resources/Empresas/'.$poe->id_empresa.'/Trabajadores/'.$poe->id_trabajador.'/Poes/'.$estudio->evidencia3;
                                    $evidencia_doc .= Html::a('<span style="font-size:30px;" class="color7">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                if(!isset($estudio->evidencia) && !isset($estudio->evidencia2) && !isset($estudio->evidencia3)){
                                    if($estudio->id_estudio == 1 && $estudio->id_hc != null){
                                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                        $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                                        $evidencia_doc = Html::a($image, Url::to(['hccohc/pdf','id' => $estudio->id_hc,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                        //$evidencia_doc .= Html::a($image2, Url::to(['hccohc/pdf','id' => $estudio->id_hc,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                                    }
                                }

                                $retworker .= '<tr id="poes_'.$poe->create_date.$keydocs1.'_worker_'.$worker->id.'" value="_poes_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                                <td width="5%">'.($keydocs1+1).'</td>
                                <td width="55%">'.$nombre_doc.'</td>
                                <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                                <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                                <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                                </tr>';
                            }
                        } else {
                            $retworker .= '<tr><td>SIN DATOS</td></tr>';
                        }
                    }
                    
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //POES DOCUMENTOS ----------------------------------------------------------------------




                //HISTORIAS CLINICAS ---------------------------------------------------------------------- 
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->hc_cumplimiento.' %</span>Historias Clínicas</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="40%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        <td width="15%" class="text-center font500">CAL</td>
                        </tr>';

                $worker_historias = Hccohc::find()->where(['id_trabajador'=>$worker->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['fecha'=>SORT_DESC])->all();
                if($worker_historias){
                    foreach($worker_historias as $keydocs1=>$estudio){
                        $nombre_doc = 'Historia Clínica';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';
                        $evidencia_cal = 'SIN CAL';

                        if($estudio->fecha != null && $estudio->fecha != '' && $estudio->fecha != ' '){
                            $fecha_doc = $estudio->fecha;
                            $anio_doc = date('Y', strtotime($estudio->fecha));
                        }

                        $image = '<span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span>';

                        if($estudio->tipo_hc_poe == 2){
                            $image = '<span class="" style="font-size:30px"><i class="bi bi-file-pdf-fill"></i></span>';

                            if($estudio->poe){
                                if($estudio->poe_doc1 != null && $estudio->poe_doc1 != '' && $estudio->poe_doc1 != ' '){
                                    $filePath = 'resources/Empresas/'.$estudio->poe->id_empresa.'/Trabajadores/'.$estudio->poe->id_trabajador.'/Poes/'.$estudio->poe_doc1;
                                    $evidencia_doc = Html::a('<span style="font-size:30px;" class="color3">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }
                                if($estudio->poe_doc2 != null && $estudio->poe_doc2 != '' && $estudio->poe_doc2 != ' '){
                                    $filePath = 'resources/Empresas/'.$estudio->poe->id_empresa.'/Trabajadores/'.$estudio->poe->id_trabajador.'/Poes/'.$estudio->poe_doc2;
                                    $evidencia_doc .= Html::a('<span style="font-size:30px;" class="color4">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }
                                if($estudio->poe_doc3 != null && $estudio->poe_doc3 != '' && $estudio->poe_doc3 != ' '){
                                    $filePath = 'resources/Empresas/'.$estudio->poe->id_empresa.'/Trabajadores/'.$estudio->poe->id_trabajador.'/Poes/'.$estudio->poe_doc3;
                                    $evidencia_doc .= Html::a('<span style="font-size:30px;" class="color7">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }
                            }

                        } else {
                            $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                            $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                            $evidencia_doc = Html::a($image, Url::to(['hccohc/pdf','id' => $estudio->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                            //$evidencia_doc .= Html::a($image2, Url::to(['hccohc/pdf','id' => $estudio->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        }

                        $image = '<span class="color11" style="font-size:25px"><i class="bi bi-file-pdf-fill"></i></span>';
                        $image2 = '<span class="color10" style="font-size:25px"><i class="bi bi-file-pdf-fill"></i></span>';
                    
                        if($model->status == 2){
                            $evidencia_cal = Html::a($image, Url::to(['hccohc/pdfcal','id' => $model->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                            //$evidencia_cal .= Html::a($image2, Url::to(['hccohc/pdfcal','id' => $model->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        }

                        $retworker .= '<tr id="historiasclinicas_'.$keydocs1.'_worker_'.$worker->id.'" value="_historiasclinicas_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="40%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_cal.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=6>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //HISTORIAS CLINICAS ----------------------------------------------------------------------




                //CONSULTAS ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src">Consultas Médicas</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                $worker_consultas = Consultas::find()->where(['id_trabajador'=>$worker->id])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['fecha'=>SORT_DESC])->all();
                if($worker_consultas){
                    $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
                    foreach($worker_consultas as $keydocs1=>$estudio){
                        $nombre_doc = 'Consulta Clínica';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';

                        if($estudio->tipo != null && $estudio->tipo != '' && $estudio->tipo != ' '){
                            $nombre_doc .= ' | '.$tipoexamen[$estudio->tipo];
                        }

                        if($estudio->fecha != null && $estudio->fecha != '' && $estudio->fecha != ' '){
                            $fecha_doc = $estudio->fecha;
                            $anio_doc = date('Y', strtotime($estudio->fecha));
                        }

                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $evidencia_doc = Html::a($image, Url::to(['consultas/pdf','id' => $estudio->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        //$evidencia_doc .= Html::a($image2, Url::to(['consultas/pdf','id' => $estudio->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                    
                        if(isset($estudio->evidencia) && $estudio->evidencia!= null && $estudio->evidencia != '' && $estudio->evidencia != ' '){
                            $imageevidencia = '<span class="color15" style="font-size:25px"><i class="bi bi-folder-fill"></i></span>';
                            $filePath =  '/resources/Empresas/'.$estudio->id_empresa.'/Trabajadores/'.$estudio->id_trabajador.'/Consultas/'.$estudio->evidencia;
                            $evidencia_doc .= Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                        }

                        $retworker .= '<tr id="consultas_'.$keydocs1.'_worker_'.$worker->id.'" value="_consultas_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //CONSULTAS ----------------------------------------------------------------------




                //CUESTIONARIO NÓRDICO ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->cuestionario_cumplimiento.' %</span>Cuestionarios Nórdicos</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                $worker_cuestionarios = Cuestionario::find()->where(['id_paciente'=>$worker->id])->andWhere(['id_tipo_cuestionario'=>1])->orderBy(['fecha_cuestionario'=>SORT_DESC])->all();
                if($worker_cuestionarios){
                    foreach($worker_cuestionarios as $keydocs1=>$estudio){
                        $nombre_doc = 'Cuestionario Nórdico';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';

                        if($estudio->fecha_cuestionario != null && $estudio->fecha_cuestionario != '' && $estudio->fecha_cuestionario != ' '){
                            $fecha_doc = $estudio->fecha_cuestionario;
                            $anio_doc = date('Y', strtotime($estudio->fecha_cuestionario));
                        }

                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    
                        if($estudio->id_tipo_cuestionario == 1){
                            $evidencia_doc = Html::a($image, Url::to(['cuestionario/pdf','id' => $estudio->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                            //$evidencia_doc .= Html::a($image2, Url::to(['cuestionario/pdf','id' => $estudio->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        }

                        $retworker .= '<tr id="cuestionariosnordicos_'.$keydocs1.'_worker_'.$worker->id.'" value="_cuestionariosnordicos_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //CUESTIONARIO NÓRDICO ----------------------------------------------------------------------




                //EVALUACIÓN ANTROPOMÉTRICA ----------------------------------------------------------------------
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src"><span class="rounded-1 p-1 bgcolor14 text-dark mx-2 font12 font550 text-end">'.$worker->antropometrica_cumplimiento.' %</span>Evaluación Antropométrica</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                $worker_evaluaciones = Cuestionario::find()->where(['id_paciente'=>$worker->id])->andWhere(['id_tipo_cuestionario'=>4])->orderBy(['fecha_cuestionario'=>SORT_ASC])->all();
                if($worker_evaluaciones){
                    foreach($worker_evaluaciones as $keydocs1=>$estudio){
                        $nombre_doc = 'Evaluación Antropométrica';
                        $fecha_doc = '--';
                        $anio_doc = '--';
                        $evidencia_doc = 'SIN EVIDENCIA';

                        if($estudio->fecha_cuestionario != null && $estudio->fecha_cuestionario != '' && $estudio->fecha_cuestionario != ' '){
                            $fecha_doc = $estudio->fecha_cuestionario;
                            $anio_doc = date('Y', strtotime($estudio->fecha_cuestionario));
                        }

                        $image = '<span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                        $image2 = '<span class="color2" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span>';
                    
                        if($estudio->id_tipo_cuestionario == 4){
                            $evidencia_doc = Html::a($image, Url::to(['antropometrica/pdf','id' => $estudio->id,'firmado'=>1]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Con Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                            //$evidencia_doc .= Html::a($image2, Url::to(['antropometrica/pdf','id' => $estudio->id,'firmado'=>0]), $options = ['target'=>'_blank','title' => Yii::t('app', 'Sin Firma'),'data-toggle'=>"tooltip",'data-placement'=>"top"]);
                        }

                        $retworker .= '<tr id="evaluacionantropometrica_'.$keydocs1.'_worker_'.$worker->id.'" value="_evaluacionantropometrica_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                        <td width="5%">'.($keydocs1+1).'</td>
                        <td width="55%">'.$nombre_doc.'</td>
                        <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                        <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                        <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                        </tr>';
                    }
                } else {
                    $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //EVALUACIÓN ANTROPOMÉTRICA ----------------------------------------------------------------------




                //ESTUDIOS COMPLEMENTARIOS HISTORIAS CLINICAS ---------------------------------------------------------------------- 
                $retworker .= '<div class="btnnew title2 text-light p-3 rounded-src">Estudios Complementarios</div>';
                $retworker .= '<div class="">';
                $retworker .= '<table class="table table-hover table-sm font12 table-bordered"><tbody>';

                $retworker .= '<tr>
                        <td width="5%" class="font500">#</td>
                        <td width="55%" class="font500">Estudio</td>
                        <td width="15%" class="font500">Fecha Documento</td>
                        <td width="10%" class="text-center font500">Año</td>
                        <td width="15%" class="text-center font500">Archivo</td>
                        </tr>';

                if($worker_historias){
                    $qty_estudios = 0;
                    foreach($worker_historias as $keydocs1=>$hc){
                        if($hc->testudios){
                            foreach($hc->testudios as $keydocs1=>$estudio){
                                $qty_estudios ++;
                                $nombre_doc = '';
                                $fecha_doc = '--';
                                $anio_doc = '--';
                                $evidencia_doc = 'SIN EVIDENCIA';

                                if($estudio->estudio){
                                    $nombre_doc = $estudio->estudio->nombre;
                                }
                            

                                if($estudio->fecha != null && $estudio->fecha != '' && $estudio->fecha != ' '){
                                    $fecha_doc = $estudio->fecha;
                                    $anio_doc = date('Y', strtotime($estudio->fecha));
                                }

                                if($estudio->evidencia != '' && $estudio->evidencia != null){
                                    $filePath = 'resources/Empresas/'.$hc->id_empresa.'/Trabajadores/'.$hc->id_trabajador.'/Documentos/'.$estudio->evidencia;
                                    $evidencia_doc = Html::a('<span style="font-size:30px;" class="color1">'.$image.'</span>', $filePath, $options = ['target'=>'_blank']);
                                }

                                $retworker .= '<tr id="estudioscomplementarios_'.$keydocs1.'_worker_'.$worker->id.'" value="_estudioscomplementarios_'.$nombre_doc.'_worker_'.$worker->id.'" name="worker_'.$worker->id.'">
                                <td width="5%">'.($keydocs1+1).'</td>
                                <td width="55%">'.$nombre_doc.'</td>
                                <td width="15%" class="text-center color9">'.$fecha_doc.'</td>
                                <td width="10%" class="text-center color3 font500">'.$anio_doc.'</td>
                                <td width="15%" class="text-center">'.$evidencia_doc.'</td>
                                </tr>';
                            }
                        }
                    }

                    if($qty_estudios == 0){
                        $retworker .= '<tr><td colspan=5>SIN DATOS</td></tr>'; 
                    }
                } else {
                    $retworker .= '<tr><td>SIN DATOS</td></tr>';
                }
                $retworker .= '</tbody>
                </table>';

                $retworker .= '</div>';
                //ESTUDIOS COMPLEMENTARIOS HISTORIAS CLINICAS ----------------------------------------------------------------------




                $retworker .= '</div>
                </div>
                </div>';

                echo $retworker;
            }
        }
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>