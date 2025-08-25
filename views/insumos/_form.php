<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Presentaciones;
use app\models\Unidades;
use app\models\Viasadministracion;
use app\models\Puestostrabajo;
use app\models\PuestoEpp;
use app\models\Epps;
use kartik\file\FileInput;
use unclead\multipleinput\MultipleInput;

use app\models\Paisempresa;
use app\models\Paises;
use app\models\Lineas;
use app\models\Ubicaciones;


use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;
use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

/** @var yii\web\View $this */
/** @var app\models\Insumos $model */
/** @var yii\widgets\ActiveForm $form */
if($tipo == 1){
    $labelnombre = 'Nombre Comercial';
} else{
    $labelnombre = 'Nombre EPP';
}
?>

<?php
$showempresa = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}
?>

<?php
//dd($tipo);
    $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
    if(Yii::$app->user->identity->empresa_all != 1) {
        $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
    } else{
        $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
    }

    $presentaciones = ArrayHelper::map(Presentaciones::find()->where(['tipo'=>$tipo])->orderBy('presentacion')->all(), 'id', 'presentacion');
    if($tipo == 1){
        $presentaciones[0] = 'OTRO';
    }

   
    $unidades = ArrayHelper::map(Unidades::find()->orderBy('unidad')->all(), 'id', 'unidad');
    $unidades[0] = 'OTRO';
    $vias = ArrayHelper::map(Viasadministracion::find()->orderBy('via_administracion')->all(), 'id', 'via_administracion');
    $vias[0] = 'OTRO';
    $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('consultorio')->all(), 'id', 'consultorio');
    $colores = [1=>'Negro',2=>'Azul',3=>'Marrón',4=>'Gris',5=>'Verde',6=>'Naranja',7=>'Rosa',8=>'Púrpura',9=>'Rojo',10=>'Blanco',11=>'Amarillo'];
    $tallas = [1=>'Unitalla',2=>'0',3=>'1',4=>'2',5=>'3',6=>'4',7=>'5',8=>'6',9=>'7',10=>'8',11=>'9',12=>'10',13=>'11',14=>'12',15=>'13',16=>'14',17=>'15',18=>'16',19=>'17'
    ,20=>'18',21=>'19',22=>'20',23=>'21',24=>'22',25=>'23',26=>'24',27=>'25',28=>'26',29=>'27',30=>'28',31=>'29',32=>'30',33=>'31',34=>'32',35=>'33',36=>'34',37=>'35',38=>'36',39=>'37',40=>'38',41=>'39',42=>'40',43=>'41',44=>'42',45=>'43',46=>'44',47=>'45',48=>'46'
    ,49=>'47',50=>'48',51=>'49',52=>'50',53=>'51',54=>'52',55=>'53',56=>'54',57=>'55',58=>'56',59=>'57',60=>'58',61=>'59',62=>'60'];
    $sexo = [1=>'Masculino',2=>'Femenino',3=>'Unisex'];
    $categoriaepp = ['1'=>'Cabeza','2'=>'Cuerpo','3'=>'Camisa','4'=>'Pantalón','5'=>'Manos-Guantes','6'=>'Pies'];


    $empresasid = explode(',', Yii::$app->user->identity->empresas_select);
    $puestos = [];
    if(Yii::$app->user->identity->empresa_all != 1) {
        $puestos = ArrayHelper::map(Puestostrabajo::find()->where(['in','id_empresa',$empresasid])->orderBy('nombre')->all(), 'id', 'nombre');
    } else {
        $puestos = ArrayHelper::map(Puestostrabajo::find()->orderBy('nombre')->all(), 'id', 'nombre');
    }

    $ids_puestos = [];
    foreach($puestos as $key=>$item){
        if(!in_array($key, $ids_puestos)){
            array_push($ids_puestos, $key);
        }
    }

    $epppuestos = PuestoEpp::find()->where(['in','id_puesto',$ids_puestos])->select('id_epp')->distinct()->all();
    
    $ids_epp = [];
    foreach($epppuestos as $key=>$item){
        if(!in_array($item->id_epp, $ids_epp)){
            array_push($ids_epp, $item->id_epp);
        }
    }
    $epps = ArrayHelper::map(Epps::find()->where(['in','id',$ids_epp])->orderBy('epp')->all(), 'id', 'epp');

    $medidas = [];
    if($model->parte_corporal == 1 || $model->parte_corporal == 5){
        $medidas = ['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL'];
    } else if($model->parte_corporal == 2 || $model->parte_corporal == 3 || $model->parte_corporal == 4){
        $medidas = [
            '1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS',
            '2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS',
            '3'=>'MEX 26 | US 6 | EUR 32 | INTER S',
            '4'=>'MEX 28 | US 8 | EUR 34 | INTER M',
            '5'=>'MEX 30 | US 10 | EUR 34 | INTER M',
            '6'=>'MEX 32 | US 12 | EUR 36 | INTER L',
            '7'=>'MEX 36 | US 14 | EUR 36 | INTER L',
            '8'=>'MEX 38 | US 16 | EUR 38 | INTER XL',
            '9'=>'MEX 40 | US 18 | EUR 38 | INTER XL',
            '10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL',
            '11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',
        ];
    } else if($model->parte_corporal == 6){
        $medidas = ['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13'];
    }

?>


<?php
$id_paises = [];
$id_lineas = [];
$id_ubicaciones = [];

if(1==2){
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->select(['id_pais'])->distinct()->all(); 
} else {
    $paisempresa = Paisempresa::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id_pais',$id_paises])->select(['id_pais'])->distinct()->all(); 
}

$id_paises = [];
foreach($paisempresa as $key=>$pais){
    array_push($id_paises, $pais->id_pais);
}
   
$paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');



if(1==2){
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
} else {
    $lineas = ArrayHelper::map(Lineas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$model->id_pais])->andWhere(['in','id',$id_lineas])->orderBy('linea')->all(), 'id', function($data){
            $rest = ' [';
            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['linea'].$rest;
    });
}

$id_lineas = [];
foreach($lineas as $key=>$linea){
    array_push($id_lineas, $key);
}

if(1==2){
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
} else {
    $ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['in','id_linea',$id_lineas])->andWhere(['in','id',$id_ubicaciones])->orderBy('ubicacion')->all(), 'id', function($data){
            $rest = ' [';

            if($data->linea){
                $rest .= $data->linea->linea.' - ';
            }

            if($data->pais){
                $rest .= $data->pais->pais.' - ';
            }
            if($data->empresa){
                $rest .= $data->empresa->comercial;
            }
            $rest .= ']';
            return $data['ubicacion'].$rest;
    });
}

?>


<?php
$modelo_ = 'insumos';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$usuario = Yii::$app->user->identity;
if($usuario->nivel1_all == 1){
    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
}  else {
    $array = explode(',', $usuario->nivel1_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id_pais',$array])->orderBy('id_pais')->all(), 'id', function($data){
        $rtlvl1 = '';
        if($data->pais){
            $rtlvl1 = $data->pais->pais;
        }
        return $rtlvl1;
    });
}



if($usuario->nivel2_all == 1){
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}  else {
    $array = explode(',', $usuario->nivel2_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}



if($usuario->nivel3_all == 1){
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}  else {
    $array = explode(',', $usuario->nivel3_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}




if($usuario->nivel4_all == 1){
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}  else {
    $array = explode(',', $usuario->nivel4_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}



if($model->id_empresa != null && $model->id_empresa != '' && $model->id_empresa != ' '){
    $empresa = Empresas::findOne($model->id_empresa);

    if($empresa){
        $label_nivel1 = $empresa->label_nivel1;
        $label_nivel2 = $empresa->label_nivel2;
        $label_nivel3 = $empresa->label_nivel3;
        $label_nivel4 = $empresa->label_nivel4;

        if($empresa->cantidad_niveles >= 1){
            $show_nivel1 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 2){
            $show_nivel2 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 3){
            $show_nivel3 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->andWhere(['in','id',$array])->all(), 'id','area');
            }
        }
        if($empresa->cantidad_niveles >= 4){
            $show_nivel4 = 'block';

            $usuario = Yii::$app->user->identity;
            if($usuario->areas_all == 1){
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->all(), 'id','area');
            } else {
                $array = explode(',', $usuario->areas_select);
                if($array && count($array)>0){
                } else {
                    $array = [];
                }
                $areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->andWhere(['in','id',$array])->all(), 'id','area');
            }

        }
    }
}
?>

<div class="insumos-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data']]); ?>


    <div class="row my-3">
        <div class="col-lg-2">
            <div class="row">
                <?php
                $carpeta = 'Medicamentos';
                
                if($tipo == 1){
                    $avatar = '/resources/images/'.'medicamento2.jpg';
                } else{
                    $carpeta = 'EPP';
                    $avatar = '/resources/images/'.'epp2.jpg';
                }
                     
                     if($model->foto){
                        $avatar = '/resources/Empresas/'.$model->id_empresa.'/'.$carpeta.'/'.$model->id.'/'.$model->foto;
                     }
                     
                     $filePath =  $avatar;
                     echo '<span class="caret  mx-2">'.Html::img('@web'. $filePath, ['alt'=>' ','id'=>'img', 'class' => "rounded-circle shadow img-responsive", 'style'=>'object-fit: cover;
                     width: 150px;
                     height: 150px;']).'</span>';
                ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'file_foto')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*','id'=>'upload'],
                    'language' => yii::t('app','es'),
                    'pluginOptions' => [
                    'browseClass' => 'btn btn-block btn-sm btn-dark',
                    'uploadClass' => 'btn btn-block btn-sm btn-info',
                    'removeClass' => 'btn btn-block btn-sm btn-danger',
                    'cancelClass' => 'btn btn-block btn-sm btn-danger',
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                    ]
                    ])->label(false); ?>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="row my-3">
                <div class="col-lg-8 mt-3" style="display:<?php echo $showempresa?>;">
                    <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"insumos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <!--   <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"insumos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_linea')->widget(Select2::classname(), [
                    'data' => $lineas,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changelinea(this.value,"insumos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 mt-3">
                    <?= $form->field($model, 'id_ubicacion')->widget(Select2::classname(), [
                    'data' => $ubicaciones,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div> -->
                <div class="col-lg-4 mt-3" style="display:none;">
                    <?= $form->field($model, 'tipo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Medicamento','2'=>'EPP'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
                <div class="col-lg-4" style="display:none;">
                    <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
                    <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel1(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel1">'.$label_nivel1.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
                    <?= $form->field($model, 'id_nivel2')->widget(Select2::classname(), [
                    'data' => $nivel2,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel2(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel2">'.$label_nivel2.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
                    <?= $form->field($model, 'id_nivel3')->widget(Select2::classname(), [
                    'data' => $nivel3,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel3(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel3">'.$label_nivel3.'</span>'); ?>
                </div>
                <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
                    <?= $form->field($model, 'id_nivel4')->widget(Select2::classname(), [
                    'data' => $nivel4,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changeNivel4(this.value,"'.$modelo_.'")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-6">
                    <?= $form->field($model, 'nombre_comercial')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label($labelnombre); ?>
                </div>
                <?php
                //dd($model->tipo);
                $display_med = 'none';
                $display_epp = 'block';
                if($model->tipo == 1){
                    $display_med = 'block';
                    $display_epp = 'none';
                }
                ?>
                <div class="col-lg-6" style="display:<?php echo $display_med;?>;">
                    <?= $form->field($model, 'nombre_generico')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'parte_corporal')->widget(Select2::classname(), [
                    'data' => $categoriaepp,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaCuerpo(this.value,"insumos")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'categoria_epp')->widget(Select2::classname(), [
                    'data' => $epps,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaOtro(this.value,"insumos","presentacion")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-3 my-3">
                    <?= $form->field($model, 'id_presentacion')->widget(Select2::classname(), [
                    'data' => $presentaciones,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaOtro(this.value,"insumos","presentacion")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 my-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'color')->widget(Select2::classname(), [
                    'data' => $colores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 my-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'talla')->widget(Select2::classname(), [
                    'data' => $medidas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 my-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'data' => $sexo,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-6 my-3" style="display:<?php echo $display_epp;?>;">
                    <?= $form->field($model, 'fabricante')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3 my-3" id="otra_presentacion" style="display:none;">
                    <?= $form->field($model, 'otra_presentacion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-3 my-3" style="display:<?php echo $display_med;?>;">
                    <?= $form->field($model, 'id_unidad')->widget(Select2::classname(), [
                    'data' => $unidades,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaOtro(this.value,"insumos","unidad")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 my-3" id="otra_unidad" style="display:none;">
                    <?= $form->field($model, 'otra_unidad')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <!--  <div class="col-lg-3">
                    <?= $form->field($model, 'cantidad')->textInput(['type'=>'number']) ?>
                </div> -->
                <div class="col-lg-3 my-3" style="display:<?php echo $display_med;?>;">
                    <?= $form->field($model, 'unidades_individuales')->textInput(['type'=>'number']) ?>
                </div>
                <div class="col-lg-3 my-3" style="display:<?php echo $display_med;?>;">
                    <?= $form->field($model, 'via_administracion')->widget(Select2::classname(), [
                    'data' => $vias,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaOtro(this.value,"insumos","via_administracion")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-3 my-3" id="otra_via_administracion" style="display:none;">
                    <?= $form->field($model, 'otra_via_administracion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
            </div>

        </div>
    </div>



    <?php
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003 6.97 2.789ZM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461L10.404 2Z"/>
  </svg>';
    ?>
    <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo $icon;?></span>
                    Stock Mínimo
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'stock_minimo')->textInput(['type'=>'number'])->label('Stock Mínimo <span class="color11">(Aplicar a todos los consultorios)</span>') ?>
            </div>
            <div class="col-lg-8">
                <label for="" class="control-label">Stock Mínimo <span class="color11">por Consultorio</span></label>
                <?php echo $form->field($model, 'aux_stocks')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_stocks',
                        'cloneButton' => false,
                        'rowOptions' => [
                            'class' => 'border-bottom table-sm',
                            'id' => 'row{multiple_index}'
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation'      => false,
                            'enableClientValidation'    => false,
                            'validateOnChange'          => false,
                            'validateOnSubmit'          => true,
                            'validateOnBlur'            => false,
                        ],
                
                        'addButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '',
                            'options'=>['style'=>'display:none']
                        ], 
                        'layoutConfig' => [
                            'offsetClass' => '',
                            'labelClass' => '',
                            'wrapperClass' => 'col-md-12',
                            'errorClass' => 'col-md-offset-1 col-md-12',
                            'buttonActionClass' => 'col-md-offset-1 col-md-1',
                        ],
                        'allowEmptyList' => true,
                        'columns' => [
                            [
                                'name'  => 'id_consultorio',
                                'title'  => 'Consultorio',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $consultorios,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:60%;'],
                                    'pluginEvents' => [
                                        "change" => ''
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:60%;'
                                ],         
                            ],
                            [
                                'name'  => 'stock',
                                'title' => 'Stock Mínimo' ,
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'=>'id',
                                'type'=>'textInput',
                                'options'=>['style'=>'display:none;','class'=>'id_alergia'],
                                'headerOptions' => [
                                    'class' => 'text-light align-top text-center text-uppercase small space p-0',
                                    'style' => 'vertical-align: top;'
                                ],
                            ],
                        ]])->label(false);?>


            </div>
        </div>
    </div>



    <div class="row my-3">

        <!-- <div class="col-lg-3">
            <?= $form->field($model, 'concentracion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3">
            
        </div> -->
    </div>
    <div class="row my-3" style="display:<?php echo $display_med;?>;">
        <div class="col-lg-6 my-3">
            <?= $form->field($model, 'formula')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-6 my-3">
            <?= $form->field($model, 'condiciones_conservacion')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-lg-6 my-3" style="display:<?php echo $display_med;?>;">
            <?= $form->field($model, 'advertencias')->textArea(['rows'=>'3','maxlength' => true, 'class'=>"form-control color11",'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-2" style="display:<?php echo $display_med;?>;">
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>