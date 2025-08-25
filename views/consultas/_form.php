<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Empresas;
use app\models\Trabajadores;
use app\models\Almacen;
use kartik\file\FileInput;
use app\models\Diagnosticoscie;
use yii\web\JsExpression;
use kartik\time\TimePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;


use app\models\Riesgos;
use app\models\Puestostrabajo;

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
/** @var app\models\Consultas $model */
/** @var yii\widgets\ActiveForm $form */
$usuario = Yii::$app->user->identity;
$modulo1 = 'Consultas';
$modulo2 = 'consultas';
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
$this->registerCss(".select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
    color: black;
    background: #636AF25a;
    border: 1px solid #636AF2;
    border-radius: 10px 10px 10px 10px;
    cursor: default;
    float: left;
    margin: 5px 0 0 6px;
    padding: 0 6px;
    font-size:17px;
}

.select2-container--bootstrap .select2-selection{
    background-color:transparent;
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
}

.select2-container--bootstrap .select2-selection {
    border: none;
    border-radius:0px;
    border-bottom: 1px solid #0d6efd;
    font-size:12px;
}
");
?>

<?php
$empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}

$riesgos = ArrayHelper::map(Riesgos::find()->orderBy('riesgo')->all(), 'id', 'riesgo');
$empresas[0] = 'OTRA';



if($usuario->areas_all == 1){
    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('area')->all(), 'id', 'area');
} else {
    $array = explode(',', $usuario->areas_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id',$array])->orderBy('area')->all(), 'id', 'area');
}

if($usuario->consultorios_all == 1){
    $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('consultorio')->all(), 'id', 'consultorio');
} else {
    $array = explode(',', $usuario->consultorios_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['in','id',$array])->orderBy('consultorio')->all(), 'id', 'consultorio');
}



$puestos = ArrayHelper::map(Puestostrabajo::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', 'nombre');
$programas = ArrayHelper::map(Programaempresa::find()->orderBy('id_programa')->where(['id_empresa'=>$model->id_empresa])->all(), 'id_programa', function($model){
    return $model->programasalud['nombre'];
});



$query_trabajadores = Trabajadores::find();
$query_trabajadores->andWhere(['id_empresa'=>$model->id_empresa]);

if(Yii::$app->user->identity->nivel1_all != 1) {
    $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
    $paises_nivel = NivelOrganizacional1::find()->where(['id_empresa'=> $model->id_empresa])->andWhere(['in', 'id_pais', $array_niveles_1])->all();
            
    $niveles_1 = [];
    foreach($paises_nivel as $item){
        if(!in_array($item->id, $niveles_1)){
            array_push($niveles_1, $item->id);
        }
    }
    $query_trabajadores->andWhere(['in', 'id_nivel1', $niveles_1]);
}
$niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
if(Yii::$app->user->identity->nivel2_all != 1) {
    $query_trabajadores->andWhere(['in', 'id_nivel2', $niveles_2]);
}
$niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
if(Yii::$app->user->identity->nivel3_all != 1) {
    $query_trabajadores->andWhere(['in', 'id_nivel3', $niveles_3]);
}
/* $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
if(Yii::$app->user->identity->nivel4_all != 1) {
    $query_trabajadores->andWhere(['in', 'id_nivel4', $niveles_4]);
} */

$query_trabajadores->orderBy(['apellidos'=>SORT_ASC]);
$consulta_trabajadores = $query_trabajadores->all();
$trabajadores = ArrayHelper::map($consulta_trabajadores, 'id', function($data){
    return $data['apellidos'].' '.$data['nombre'];
});

//dd($consulta_trabajadores,$trabajadores);

/* $trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('nombre')->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
}); */
//$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','9'=>'NUTRICIÓN','10'=>'PSICOLÓGICA','11'=>'ALCOHOLEMIA'];
$tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','11'=>'ALCOHOLEMIA'];

$riesgostipos = [
    '1'=>'Trabajos en caliente',
    '2'=>'Espacios confinados',
    '3'=>'Trabajo en componentes energizados',
    '4'=>'Trabajos en alturas',
    '5'=>'Trabajos en solitario y/o áreas remotas',
    '6'=>'Trabajo en sistemas presurizados',
    '7'=>'Trabajos químicos altamente peligrosos',
    '8'=>'Construcción/excavación/demolición/izaje'
];
$hoy = date('Y-m-d');
$almacenmeds = Almacen::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_consultorio'=>$model->id_consultorio])->andWhere([ 'or' ,['>', 'stock', 0],['>', 'stock_unidad', 0]])->andWhere(['>=','fecha_caducidad',$hoy])->orderBy(['id_insumo'=>SORT_DESC,'fecha_caducidad'=>SORT_ASC])->all();

$index = 1;
$medicamentos = ArrayHelper::map($almacenmeds, 'id', function($model) use($index){
    $ret = '';
    if($model->insumo){
        $ret =  $model->insumo['nombre_comercial'].' | '.$model->insumo['nombre_generico'];
    }
    $ret .= ' - [Exp. '.$model->fecha_caducidad.']';
    return $ret;
});


$tadiagnosticos = ['1'=>'Normal','2'=>'Hipotensión','3'=>'Hipertensión'];
$oxidiagnosticos = ['1'=>'Normal','2'=>'Hipoxia'];
$frdiagnosticos = ['1'=>'Normal','2'=>'Bradipnea','3'=>'Taquipnea'];
?>

<?php
    $iconclip = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-fill" viewBox="0 0 16 16">
    <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
    <path d="M3.5 1h.585A1.498 1.498 0 0 0 4 1.5V2a1.5 1.5 0 0 0 1.5 1.5h5A1.5 1.5 0 0 0 12 2v-.5c0-.175-.03-.344-.085-.5h.585A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1Z"/>
    </svg>';

    $iconcura = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bandaid" viewBox="0 0 16 16">
    <path d="M14.121 1.879a3 3 0 0 0-4.242 0L8.733 3.026l4.261 4.26 1.127-1.165a3 3 0 0 0 0-4.242ZM12.293 8 8.027 3.734 3.738 8.031 8 12.293 12.293 8Zm-5.006 4.994L3.03 8.737 1.879 9.88a3 3 0 0 0 4.241 4.24l.006-.006 1.16-1.121ZM2.679 7.676l6.492-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Z"/>
    <path d="M5.56 7.646a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708Zm1.415-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707ZM8.39 4.818a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707Zm0 5.657a.5.5 0 1 1-.708.707.5.5 0 0 1 .707-.707ZM9.803 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707Zm1.414-1.414a.5.5 0 1 1-.706.708.5.5 0 0 1 .707-.708ZM6.975 9.06a.5.5 0 1 1-.707.708.5.5 0 0 1 .707-.707ZM8.39 7.646a.5.5 0 1 1-.708.708.5.5 0 0 1 .707-.708Zm1.413-1.414a.5.5 0 1 1-.707.707.5.5 0 0 1 .707-.707Z"/>
  </svg>';

  $iconhospital = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
  <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
  <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
</svg>';
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
$modelo_ = 'consultas';
$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$usuario = Yii::$app->user->identity;
if($usuario->nivel1_all == 1){//$usuario->nivel1_all == 1
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



if($usuario->nivel2_all == 1){//$usuario->nivel2_all == 1
    //->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}  else {
    $array = explode(',', $usuario->nivel2_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }
    //->andWhere(['id_nivelorganizacional1'=>$model->id_nivel1])
    $nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
        $rtlvl2 = $data['nivelorganizacional2'];
        return $rtlvl2;
    });
}



if($usuario->nivel3_all == 1){//$usuario->nivel3_all == 1
    //->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}  else {
    $array = explode(',', $usuario->nivel3_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    //->andWhere(['id_nivelorganizacional2'=>$model->id_nivel2])
    $nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
        $rtlvl3 = $data['nivelorganizacional3'];
        return $rtlvl3;
    });
}




if($usuario->nivel4_all == 1){//$usuario->nivel4_all == 1
    //->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
        $rtlvl4 = $data['nivelorganizacional4'];
        return $rtlvl4;
    });
}  else {
    $array = explode(',', $usuario->nivel4_select);
    if($array && count($array)>0){
    } else {
        $array = [];
    }

    //->andWhere(['id_nivelorganizacional3'=>$model->id_nivel3])
    $nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['status'=>1])->andWhere(['in','id',$array])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
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
//dd($model);
?>


<div class="consultas-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row my-3">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'model_')->textInput(['maxlength' => true,'type'=>'hidden','id'=>'model_']) ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'solicitante')->widget(Select2::classname(), [
                    'data' => ['1'=>'EMPLEADO','2'=>'CONTRATISTA','3'=>'VISITANTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaSolicitante(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3 offset-lg-3">
            <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]) ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'hora_inicio')->textInput(['maxlength' => true,'onkeyup' => '','readonly'=>true]) ?>
        </div>
        <?php 
            $show = 'none';
            if($model->solicitante == '2' || $model->solicitante == '3'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <div class="row my-3 ">
        <div class="col-lg-5 mt-3"  style="display:<?php echo $showempresa?>;">
            <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa2(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <?php 
        
            $showempresa = 'block';
            if($model->id_empresa != '0'){
                $showempresa = 'none';
            }
        ?>
        <div class="col-lg-4 mt-3" id="bloqueempresa" style="display:<?php echo $showempresa;?>;">
            <?= $form->field($model, 'empresa')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']); ?>
        </div>
        <!-- <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_pais')->widget(Select2::classname(), [
                    'data' => $paises,
                    'disabled' => false,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'changepais(this.value,"consultas")'],
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
                    'onchange' => 'changelinea(this.value,"consultas")'],
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
        <div class="col-lg-3 mt-3">
            <?= $form->field($model, 'id_consultorio')->widget(Select2::classname(), [
                    'data' => $consultorios,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaConsultorio(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_consultorio')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'id_hccohc')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>


    <?php
    //changeNivel1(this.value,"'.$modelo_.'")
    //changeNivel2(this.value,"'.$modelo_.'")
    //changeNivel3(this.value,"'.$modelo_.'")
    //changeNivel4(this.value,"'.$modelo_.'")
    ?>


    <?php 
        $show = 'none';
        if($model->solicitante == '1'){
            $show = 'block';
        }
    ?>
    <div class="row my-3">
        <div class="col-lg-5 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'id_trabajador')->widget(Select2::classname(), [
                    'data' => $trabajadores,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTrabajador(this.value,"consultas")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-3" style="display:none;">
            <?= $form->field($model, 'folio')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-lg-3 border30 boxtitle p-2 text-center datos_trabajador" style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Historia Clínica
            </label>
            <div class="text-center" id="historia_clinica"></div>
        </div>
        <div class="col-lg-3 offset-lg-1 border30 boxtitle p-2 text-center datos_trabajador"
            style="display:<?php echo $show;?>;">
            <label class="title2 color3">
                Programas de Salud
            </label>
            <div class="text-center" id="programas_salud"></div>
        </div>

    </div>



    <div class="row my-3">
        <div class="col-lg-3 mt-3" id="show_nivel1" style="display:<?=$show_nivel1?>;">
            <?= $form->field($model, 'id_nivel1')->widget(Select2::classname(), [
                    'data' => $nivel1,
                
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel1">'.$label_nivel1.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel2" style="display:<?=$show_nivel2?>;">
            <?= $form->field($model, 'id_nivel2')->widget(Select2::classname(), [
                    'data' => $nivel2,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel2">'.$label_nivel2.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel3" style="display:<?=$show_nivel3?>;">
            <?= $form->field($model, 'id_nivel3')->widget(Select2::classname(), [
                    'data' => $nivel3,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel3">'.$label_nivel3.'</span>'); ?>
        </div>
        <div class="col-lg-3 mt-3" id="show_nivel4" style="display:<?=$show_nivel4?>;">
            <?= $form->field($model, 'id_nivel4')->widget(Select2::classname(), [
                    'data' => $nivel4,
                    
                    'readonly' =>true,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])->label('<span id="label_nivel4">'.$label_nivel4.'</span>'); ?>
        </div>
    </div>

    <?php
    $deshabilitados = false;
    if($model->solicitante == 1){
        $deshabilitados = true;
    }
    ?>

    <div class="row my-3">
        <div class="col-lg-2">
            <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Masculino','2'=>'Femenino','3'=>'Otro'],
                    'disabled'=>$deshabilitados,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false,
                        
                    ],
                    ]);?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'fecha_nacimiento')->textInput(['readonly'=>$deshabilitados, 'maxlength' => true])->label() ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'edad')->textInput(['type'=>'number','readonly'=>$deshabilitados, 'min'=>'0', 'step'=>'1', 'maxlength' => true])->label() ?>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'num_imss')->textInput(['maxlength' => true,'readonly'=>$deshabilitados]) ?>
        </div>
        <div class="col-lg-2 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'num_trabajador')->textInput(['maxlength' => true,'readonly'=>true]) ?>
        </div>
        <div class="col-lg-3 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'area')->widget(Select2::classname(), [
                    'data' => $areas,
                    'disabled'=>$deshabilitados,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-4 mt-3 datos_trabajador" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'puesto')->widget(Select2::classname(), [
                    'data' => $puestos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>
    <?php 
    $show = 'none';
    if($model->tipo == '1'){
        $show = 'block';
    }
   ?>

    <div class="container-fluid my-3 border30 bg-light p-4 datos_trabajador" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-exclamation-circle"></i></span>
                            Alergias
                        </label>
                    </div>
                    <div class="col-lg-12" id='alergias'></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row p-0 m-0">
                    <div class="col-lg-6 title2 boxtitle2 p-1 rounded-3 color10 my-3">
                        <label class="">
                            <span class="mx-2"><i class="bi bi-slash-circle"></i></span>
                            Riesgos de Trabajo
                        </label>
                    </div>
                    <div class="col-lg-12" id='riesgos'></div>
                </div>
            </div>

        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-5">
            <?php
            $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19','11'=>'ALCOHOLEMIA'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipoexamen = ['3'=>'CLÍNICA','1'=>'ACCIDENTE','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PREOCUPANTE','8'=>'COVID-19'];
            }
            ?>
            <?= $form->field($model, 'tipo')->widget(Select2::classname(), [
                    'data' => $tipoexamen,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaTipoconsulta(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'visita')->widget(Select2::classname(), [
                    'data' => ['1'=>'1A VEZ','2'=>'SUBSECUENTE'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <?php 
        $show = 'none';
        if($model->tipo == '7'){
            $show = 'block';
        }
        ?>
        <div class="col-lg-5 bloque_programas" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'id_programa')->widget(Select2::classname(), [
                    'data' => $programas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'multiple' => true,
                    'onchange' => 'cambiaPrograma(this.value)'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
    </div>


    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_accidente" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconcura;?></span>
                    Información del Accidente
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'accidente_hora')->widget(TimePicker::classname(), [
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ],
                   
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'accidente_horareporte')->widget(TimePicker::classname(), [
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ],
                   
                    ]); ?>
            </div>
            <div class="col-lg-8">
                <?= $form->field($model, 'accidente_zona')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 my-3">
                <?= $form->field($model, 'accidente_causa')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
            <div class="col-lg-6 my-3">
                <?= $form->field($model, 'accidente_descripcion')->textArea(['rows'=>'3','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
            </div>
        </div>
    </div>

    <?php 
    $show = 'none';
    if($model->tipo == '4'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_incapacidad"
        style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconhospital;?></span>
                    Datos de la Incapacidad
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_folio')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'incapacidad_tipo')->widget(Select2::classname(), [
                    'data' => ['1'=>'IMSS','2'=>'INTERNA','3'=>'PARTICULAR'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'incapacidad_ramo')->widget(Select2::classname(), [
                    'data' => ['1'=>'Maternidad','2'=>'Enfermedad General','3'=>'Riesgo del Trabajo'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_fechainicio')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'vencimientoIncapacidad()',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'incapacidad_dias')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Dias de Incapacidad','onchange'=>'vencimientoIncapacidad()'])->label() ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'incapacidad_fechafin')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]]); ?>
            </div>
        </div>
    </div>


    <?php 
    $show = 'none';
    if($model->tipo == '6'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4" id="bloque_riesgo" style="display:<?php echo $show;?>;">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3">
                <label class="">
                    <span class="mx-2"><i class="bi bi-exclamation-triangle"></i></span>
                    Trabajo de Riesgo
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'triesgo_tipo')->widget(Select2::classname(), [
                    'data' => $riesgostipos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
    </div>


    <?php
    $displaycam_preregistro = 'flex';
    $display_preregistro = 'block';
    $display_btn = 'none';
    ?>


    <?php
        $mostrar_firma = 'none';
        $mostrar_pad = 'block';
        if($model->scenario == 'update' && isset($model->firma)){
           
            $mostrar_firma = 'block';
            $mostrar_pad = 'none';
        }
        $url = Url::to(['firma']);
    ?>

    <div class="container-fluid bg1 p-3 my-3 shadow datos_consentimiento" style="display:block;">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
        <h5 class="mb-3 bgcolor1 text-light p-2 text-center ">
            CONSENTIMIENTO
        </h5>
        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                    class="mx-2 border-bottom-dot title2 color3 nombre_cliente"
                    id='nombre_cliente'><?php echo $model->nombre.' '.$model->apellidos?></span>, EN PLENO
                USO DE
                MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE SE HIZO
                DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO PARA LA
                REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS ACCIONES
                QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA INTEGRIDAD DE
                NINGUNA PERSONA.
            </div>
        </div>


        <div class="row my-4">
            <div class="col-lg-12 text-justify">
                FINALMENTE, Y CORRESPONDIENDO AL PRINCIPIO DE CONFIDENCIALIDAD, SE ME HA EXPLICADO QUE LA
                INFORMACIÓN QUE DERIVE COMO RESULTADO, RESPECTO A EL/LOS PROCEDIMIENTO(S) PRACTICADO(S),
                SERÁ MANEJADA DE MANERA CONFIDENCIAL Y ESTRICTAMENTE PARA EL USO DE:
            </div>
            <div class="col-lg-6">
                <?=$form->field($model, 'uso_consentimiento')->radioList( [1=>Yii::t('app','MI PERSONA'), 2 => Yii::t('app','ÁREA DE RECURSOS HUMANOS DE LA EMPRESA')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3 font-600','separator' => '<br>', 'itemOptions' => [
                        'class' => 'largerCheckbox'
                       ]] )->label(false);
                ?>
            </div>
            <div class="col-lg-6 shadow">
                <br>
                <span class="border-bottom-dot title2 color11 nombre_empresa mx-2 p-2" id='nombre_empresa'>
                    <?php
                    if($model->uso_consentimiento == 2){
                        echo $model->nombre_empresa;
                    }
                    ?>
                </span>
            </div>
        </div>
        <div class="row  mt-5 my-4">
            <div class="col-lg-12 text-justify">
                EN MI PRESENCIA HAN SIDO LLENADOS TODOS LOS ESPACIOS EN BLANCO EXISTENTES EN ESTE DOCUMENTO.
                TAMBIÉN ME ENCUENTRO ENTERADO(A) QUE TENGO LA FACULTAD DE RETIRAR ESTE CONSENTIMIENTO SI ASÍ
                LO DESEO (EN EL CASO DE QUE NO SE ME HAYA EXPLICADO EL/LOS PROCEDIMIENTO(S)).
            </div>
            <div class="col-lg-12">
                <?=$form->field($model, 'retirar_consentimiento')->radioList( [1=>Yii::t('app','SI'), 2 => Yii::t('app','NO')],['onClick'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")', 'class'=>'mb-0 title2 color3  font-600','separator' => '<br>', 'itemOptions' => [
                        'class' => 'largerCheckbox'
                       ]] )->label(false);
                ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-12 text-justify">
                YO <span
                    class="border-bottom-dot title2 color3 nombre_cliente mx-2"><?php echo $model->nombre.' '.$model->apellidos;?></span>
            </div>
            <div class="col-lg-12 text-justify">
                DOY MI CONSENTIMIENTO PARA LA REALIZACIÓN DEL PROCEDIMIENTO(S) ANTERIORMENTE SEÑALADO(S).
            </div>
        </div>
        <div class="row my-5 pt-3">
            <div class="col-lg-12 text-justify text-darkgray font-600">

                <?php $aviso= Html::a('Aviso de privacidad', Url::to(['trabajadores/privacy']), $options = ['target'=>'_blank','class'=>"btn boton btn-primary"]);?>
                <?php
                echo $form->field($model, 'acuerdo_confidencialidad')->checkBox([
                    'label' => '<span class="text-uppercase">He leído y aceptado el '.$aviso.' y comprendo que la información proporcionada se usará de acuerdo a los terminos establecidos.</span>',
                    'onChange'=>'aceptaTerminos("'.$modulo1.'","'.$modulo2.'")',
                    'class' => 'largerCheckbox',
                    'options' => [
                        
                        ]
                    ])->label(false);
                ?>

            </div>
        </div>
        

    </div>

    <div class="container-fluid bg1 p-3 my-3">
        <h5 class="mb-3 bgcolor1 text-light p-2">
            IDENTIFICACIÓN
        </h5>
        <div class="row my-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'tipo_identificacion')->widget(Select2::classname(), [
                    'data' => ['INE'=>'INE','PASAPORTE'=>'PASAPORTE','LICENCIA DE CONDUCIR'=>'LICENCIA DE CONDUCIR','GAFETE'=>'GAFETE','OTRO'=>'OTRO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--')],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ])?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'numero_identificacion')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']);?>
            </div>
        </div>


        <div class="row my-3 mt-4 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
            <div class="col-lg-6">
                <div id="live_camera"></div>
                <div class="row mt-3">
                    <div class="col-lg-8 d-grid text-center">
                        <input type=button value="Capturar Evidencia"
                            class="btn btn-dark border30 btn-lg btn-block my-2" onClick="capture_web_snapshot('consultas')">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3 datos_consentimientocamara" style="display:<?php echo $displaycam_preregistro;?>;">
                    <div class="col-lg-12 d-grid text-center">
                        <label class="control-label" for="canvasfoto">Evidencia</label>
                        <div id="preview">
                            <?php if(isset($model->foto_web)):?>

                            <?php
                            //define('UPLOAD_DIR', '../web/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/');    
                            //dd('/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->foto_web);
                            ?>
                            <img src="<?php  echo '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->foto_web;?>"
                                class="img-fluid img-responsive" width="350px" height="300px" />
                            <?php endif;?>
                        </div>
                        <canvas id="canvasfoto" class="canvasmedia text-center mx-auto" width="350px" height="300px"
                            style="display:none;"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <div class="row" style="display:none;">
            <?= $form->field($model, 'txt_base64_foto')->textArea(['maxlength' => true,'class'=>'form-control image-tag']); ?>
            <?= $form->field($model, 'txt_base64_ine')->textArea(['maxlength' => true]); ?>
            <?= $form->field($model, 'txt_base64_inereverso')->textArea(['maxlength' => true]); ?>
        </div>



        <?php
        $mostrar_firma = 'none';
        $mostrar_pad = 'block';
        if($model->scenario == 'update' && isset($model->firma)){
           
            $mostrar_firma = 'block';
            $mostrar_pad = 'none';
        }
        $url = Url::to(['firma']);?>

    </div>

    <div class="container-fluid my-3 border30 bg-light p-4">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Exploración Física
                </label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'peso')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'0.01','id' => 'peso', 'maxlength' => true, 'onchange' => 'calculoIMC(this.value);'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'talla')->textInput(['type'=>'number', 'min'=>'0', 'step'=>'0.01','id' => 'talla', 'maxlength' => true, 'onchange' => 'calculoIMC(this.value);'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'imc')->textInput(['id' => 'imcHcc', 'readonly' => true])->label() ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'categoria_imc')->textInput(['id' => 'imccat', 'readonly' => true])->label() ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'fc')->textInput(['type'=>'number','min'=>60,'max'=>100,'maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'fr')->textInput(['type'=>'number','maxlength' => true, 'onchange' => 'cambiaSignos(this.value,"4");'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'fr_diagnostico')->widget(Select2::classname(), [
                    'data' => $frdiagnosticos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'temp')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'oxigeno')->textInput(['type'=>'number','min'=>'0', 'step'=>'0.01','maxlength' => true, 'onchange' => 'cambiaSignos(this.value,"1");'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'oximetria_diagnostico')->widget(Select2::classname(), [
                    'data' => $oxidiagnosticos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'ta')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Sistólica', 'onchange' => 'cambiaSignos(this.value,"2");'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'ta_diastolica')->textInput(['type'=>'number','maxlength' => true,'placeholder'=>'Diastólica', 'onchange' => 'cambiaSignos(this.value,"3");'])->label() ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'tasis_diagnostico')->widget(Select2::classname(), [
                    'data' => $tadiagnosticos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'readonly'=>true
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'tadis_diagnostico')->widget(Select2::classname(), [
                    'data' => $tadiagnosticos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'ta_diagnostico')->widget(Select2::classname(), [
                    'data' => $tadiagnosticos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
            </div>
        </div>
    </div>


    <?php
    $iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
    <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9H1.475Z"/>
    <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.88Z"/>
    </svg>';
    ?>
    <?php 
    $show = 'none';
    if($model->tipo == '7'){
        $show = 'block';
    }
   ?>
    <div class="container-fluid my-3 border30 bg-light p-4 bloque_programas" style="display:<?php echo $show;?>;">
        <?php
        $array = [];
        if($model->id_programa){
            $array = $model->id_programa;
        }
        ?>
        <?php
        $showdiabetes = 'none';
        if (in_array('1', $array)) {
           $showdiabetes = 'block';
        }
        ?>
        <div id="diabetes" style="display:<?php echo $showdiabetes;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Diabetes
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes1');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes1')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes2');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes2')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes3');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_diabetes3')->widget(Select2::classname(), [
                    'data' => ['1'=>'Ardor/Disuria','2'=>'Sensación de vaciamiento incompleto/Tenesmo','3'=>'No presenta molestias'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => '', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes4');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes4')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes5');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes5')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes6');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes6')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes7');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes7')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes8');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes8')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_diabetes9');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_diabetes9')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
        </div>


        <?php
        $showhipertension = 'none';
        if (in_array('2', $array)) {
           $showhipertension = 'block';
        }
        ?>
        <div id="hipertension" style="display:<?php echo $showhipertension;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Hipertensión
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension1');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension1')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension2');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension2')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension3');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension3')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension4');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension4')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension5');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension5')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension6');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_hipertension6')->widget(Select2::classname(), [
                    'data' => ['1'=>'Frecuencia (incremento)','2'=>'Frecuencia (descenso)', '3'=>'Volumen (disminución del chorro)','4'=>'Orina con aspecto fuera de la normalidad','5'=>'No presenta cambios'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => '','multiple'=>true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension7');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension7')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension8');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension8')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension9');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension9')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hipertension10');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hipertension10')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
        </div>


        <?php
        $showmaternidad = 'none';
        if (in_array('3', $array)) {
           $showmaternidad = 'block';
        }
        ?>
        <div id="maternidad" style="display:<?php echo $showmaternidad;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Maternidad
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad1');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad1')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad2');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad2')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad3');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad3')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad4');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad4')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad5');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad5')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad6');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad6')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad7');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad7')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad8');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad8')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad9');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad9')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad10');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad10')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_maternidad11');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_maternidad11')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
        </div>


        <?php
        $showlactancia = 'none';
        if (in_array('4', $array)) {
           $showlactancia = 'block';
        }
        ?>
        <div id="lactancia" style="display:<?php echo $showlactancia;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Lactancia
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia1');?></label>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'ps_lactancia1')->widget(DatePicker::classname(), [ 
                     'options' => [
                         'placeholder' => 'YYYY-MM-DD',
                         'onchange'=>'',
                        ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd'
                         ]])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia2');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_lactancia2')->widget(Select2::classname(), [
                    'data' => ['1'=>'Parto','2'=>'Cesárea'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia3');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_lactancia3')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia4');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_lactancia4')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia5');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_lactancia5')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia6');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_lactancia6')->widget(Select2::classname(), [
                    'data' => ['1'=>'Lactancia materna exclusiva','2'=>'Lactancia materna complementaria','3'=>'Lactancia materna predominante'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_lactancia7');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_lactancia7')->widget(Select2::classname(), [
                    'data' => ['1'=>'Salida constante de leche','2'=>'Salida de material purulento','3'=>'Fiebre','4'=>'Abultamientos o endurecimientos de mama','5'=>'Fisuras o grietas mamarias','6'=>'Dolor al tacto'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => '','multiple'=>true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
        </div>


        <?php
        $showhiperdiabetes = 'none';
        if (in_array('5', $array)) {
           $showhiperdiabetes = 'block';
        }
        ?>
        <div id="hiperdiabetes" style="display:<?php echo $showhiperdiabetes;?>;">
            <div class="row m-0 p-0">
                <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                    <label class="">
                        <span class="mx-2"><?php echo  $iconheart;?></span>
                        Hispertensión y Diabetes
                    </label>
                </div>
            </div>
            <?php $contador = 1;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe1');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_hiperdiabe1')->widget(Select2::classname(), [
                    'data' => ['1'=>'Si, ambas','2'=>'No, ninguno','3'=>'Solo glucosa en control','4'=>'Solo presión arterial en control'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe2');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_hiperdiabe2')->widget(Select2::classname(), [
                    'data' => ['1'=>'Si, ambos','2'=>'No, ninguno','3'=>'Solo buena adherencia al tratamiendo para diabetes mellitus','4'=>'Solo buena adherencia al tratamiento para hipertensión'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe3');?></label>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'ps_hiperdiabe3')->widget(Select2::classname(), [
                    'data' => ['1'=>'Alteraciones en el aspecto de la orina','2'=>'Alteraciones en el olor de la orina','3'=>'Alteraciones en el color de la orina','4'=>'Alteraciones asociadas como disuria (ardor), tenesmo (sensación de vaciamiento incompleto)','5'=>'Alteraciones en la frecuencia','6'=>'Alteraciones en el horario (nicturia)'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => '','multiple'=>true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe4');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe4')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe5');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe5')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe6');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe6')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe7');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe7')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe8');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe8')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
            <?php $contador++;?>
            <div class="row py-1">
                <div class="col-lg-7">
                    <label
                        class="control-label"><?=$contador.'.- '.$model->getAttributeLabel('ps_hiperdiabe9');?></label>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ps_hiperdiabe9')->widget(Select2::classname(), [
                    'data' => ['1'=>'SI','2'=>'NO'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label(false); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-12">
            <?= $form->field($model, 'sintomatologia')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <?php 
            $show = 'none';
            if($model->solicitante == '2' || $model->solicitante == '3'){
                $show = 'block';
            }
        ?>
        <div class="col-lg-12 my-3 apartado_nombre" style="display:<?php echo $show;?>;">
            <?= $form->field($model, 'alergias')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-12 my-3">
            <?= $form->field($model, 'diagnostico')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="row my-3">
            <div class="col-lg-12 my-3">
                <?php
                        $dataList = Diagnosticoscie::find()->andWhere(['id' => $model->diagnosticocie])->all();
                        $data = ArrayHelper::map($dataList, 'id', function($dt){
                            return '('.$dt['clave'].') '.$dt['diagnostico'];
                        });
                        //dd($data);
                        $url = \yii\helpers\Url::to(['diagnosticos']);
            ?>
                <?= $form->field($model, 'diagnosticocie')->widget(Select2::classname(), [
                            'data' => $data,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'initValueText' => '',
                            'options' => ['placeholder' => 'Escriba diagnóstico(s) --',
                            'onchange' => ''],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple'=>true,
                                'minimumInputLength' => 3,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Cargando resultados...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(city) { return city.diagnostico; }'),
                                'templateSelection' => new JsExpression('function (city) { return city.diagnostico; }'),
                            ],
                            'showToggleAll' => false,
                            ])->label('DIAGNÓSTICO CIE');?>
            </div>
        </div>
        <div class="col-lg-12 my-3">
            <?= $form->field($model, 'estudios')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-12 my-3">
            <?= $form->field($model, 'manejo')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
        <div class="col-lg-12 my-3">
            <?= $form->field($model, 'seguimiento')->textArea(['rows'=>'5','maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
        </div>
    </div>

    <div class="row my-3 mt-5">
        <div class="col-lg-6">
            <?= $form->field($model, 'file_evidencia')->widget(FileInput::classname(), [
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
                    ])->label(); ?>
        </div>
        <div class="col-lg-6">
            <?php
            if(isset($model->evidencia) && $model->evidencia!= null && $model->evidencia != '' && $model->evidencia != ' '){
                $imageevidencia = '<span class="color15" style="font-size:100px"><i class="bi bi-folder-fill"></i></span><span class="badge bgtransparent2 color11 font12 m-1">Evidencia</span>';
                $filePath =  '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Consultas/'.$model->evidencia;
                $ret = Html::a($imageevidencia, '@web'.$filePath, $options = ['target'=>'_blank','title' => Yii::t('app', 'Evidencia'),'data-bs-toggle'=>"tooltip",'data-bs-placement'=>"top"]);
                echo $ret;
            }
            ?>
        </div>
    </div>


    <div class="container-fluid my-3 border30 bg-customlight border-custom p-2">
        <div class="row m-0 p-0">
            <div class="col-lg-4 title2 boxtitle p-1 rounded-3 color3 my-3">
                <label class="">
                    <span class="mx-2"><?php echo  $iconclip;?></span>
                    Medicamentos
                </label>
            </div>
        </div>
        <?php echo $form->field($model, 'aux_medicamentos')->widget(MultipleInput::className(), [
                        'rowOptions' => ['class' => 'border-bottom table-sm'],
                        'max' => 44,
                        'theme'=>'bs',
                        'id'=>'aux_medicamentos',
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
                            'label' => '<button type="button" class="btn text-primary shadow"><i class="bi bi-plus-lg"></i></button>',
                            'options'=>['style'=>'display:none']
                        ],
                        'removeButtonOptions' => [
                            'class' => 'font-weight-bold',
                            'label' => '<span class="color10 p-3"><i class="bi bi-trash2"></i></span>',
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
                                'name'  => 'index',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return 1;
                                },
                                'options' =>[
                                    'class' => 'index'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'foto',
                                'title'  => '',
                                'type'  => 'static',
                                'value' => function($data) {
                                    return Html::img('@web/resources/images/'.'medicamento.jpg', ['alt'=>'sin imagen', 'class' => "img-fluid", 'width' => '30px', 'height' =>'auto','style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:auto; width:35px;']);
                                },
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],  
                            ],
                            [
                                'name'  => 'id_insumo',
                                'title'  => 'Medicamento (Nombre Comercial | Genérico | [Caducidad])',
                                'type'  => kartik\select2\Select2::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'data' => $medicamentos,
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' =>['placeholder' => 'SELECCIONE ...','style'=>'width:45%;'],
                                    'pluginEvents' => [
                                        "change" => 'function(){
                                            var valor = $(this).val();
                                            var id = $(this).attr("id")+"!";

                                            var stock_id = id.replace("-id_insumo!", "-stock");
                                            var unidad_id = id.replace("-id_insumo!", "-unidad");
                                            var cantidad_id = id.replace("-id_insumo!", "-cantidad");
                                            var fecha_id = id.replace("-id_insumo!", "-fecha_caducidad");
                                            console.log("Valor que está cambiando: "+valor);
                                            console.log("Id que está cambiando: "+id);
                                            nuevoMedicamento(stock_id, unidad_id, cantidad_id,fecha_id, valor,"consultas"); 
                                        }'
                                    ]   
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top; width:35%;'
                                ],         
                            ],
                            [
                                'name'  => 'unidad',
                                'title' => 'Unidades x Caja' ,
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'color6 text-end',
                                    'style'=>'border:none;'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],          
                            ],
                            [
                                'name'=>'fecha_caducidad',
                                'title' => 'Caducidad' ,
                                'type'=>'textInput',
                                'options'=>[ 'readonly' =>'true','style'=>'','class'=>'bg-disabled text-end rounded-3'],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center',
                                    'style' => 'vertical-align: top;width:10%;'
                                ],   
                            ],
                           /*  [
                                'name'  => 'fecha_caducidad',
                                'title'  => 'Fecha Caducidad',
                                'type'  => kartik\date\DatePicker::className(),
                                'attributeOptions' => [
                                    'enableClientValidation' => true,
                                    'validateOnChange' => true,
                                ],
                                'enableError' => true,
                                'options' => [
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pickerIcon' => '<i class="bi bi-calendar2-date text-primary"></i>',
                     'removeIcon' => '<i class="bi bi-trash text-danger"></i>',
                                    'pluginOptions'=>[
                                        'placeholder' => 'YYYY-MM-DD',
                                        'onchange'=>'', 
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ] 
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500 text-center text-center',
                                    'style' => 'vertical-align: top; width:23%;'
                                ],         
                            ], */
                            [
                                'name'  => 'stock',
                                'title' => 'En Stock',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad',
                                'title' => 'Cantidad',
                                /* 'type'  => kartik\number\NumberControl::className(), */
                                'options' =>[
                                    /* 'maskedInputOptions' => [
                                        'allowMinus' => false,
                                        'digits' => 0,
                                        'min' => 1,
                                        'max' => 1000
                                    ], */
                                    /* 'pluginEvents' => [
                                        
                                    ] */
                                    "change" => 'function(){
                                           
                                        var valor = $(this).val();
                                        var id = $(this).attr("id")+"!";

                                        var stock_id = id.replace("-cantidad!", "-stock");

                                        var stock =  $("#"+stock_id).val();
                                        console.log("La cantidad que quire salir es: "+valor);
                                        console.log("El stock disponible es: "+stock);

                                        if(valor>stock){
                                            $("#"+$(this).attr("id")).val(stock);
                                        } else if(valor<1){
                                            $("#"+$(this).attr("id")).val(1);
                                        }
                                    }'
                                ],
                                'headerOptions' => [
                                    'class' => 'color9 font500',
                                    'style' => 'vertical-align: top;'
                                ],          
                            ],
                            [
                                'name'  => 'cantidad_unidades',
                                'title' => '',
                                'options' =>[
                                    'readonly' =>'true',
                                    'class'=>'bg-disabled text-end rounded-3',
                                    'style'=>'display:none;'
                                ],
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


    <div class="row my-3 mt-5">
        <div class="col-lg-4">
            <?= $form->field($model, 'resultado')->widget(Select2::classname(), [
                    'data' => ['1'=>'REGRESA A LABORAR (MISMA ACTIVIDAD)','2'=>'REGRESA A LABORAR (CAMBIO ACTIVIDAD)',
                    '3'=>'ENVIO IMSS','4'=>'ENVIO A DOMICILIO','5'=>'INCAPACIDAD IMSS','6'=>'EN OBSERVACIÓN'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
        <div class="col-lg-4">
            <?php
            $tipospadecimientos = ['1'=>'LABORAL','2'=>'NO LABORAL'];
            if($model->solicitante == 2 ||$model->solicitante == 3){
                $tipospadecimientos = ['3'=>'GENERAL'];
            }
            ?>
            <?= $form->field($model, 'tipo_padecimiento')->widget(Select2::classname(), [
                    'data' =>  $tipospadecimientos,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
        <div class="col-lg-4">
            <?php
            $tipososha = ['1'=>'ANOTHER RECORDABLE CASES','2'=>'NO  WORK RELEATED','3'=>'NO APLICA'];
            ?>
            <?= $form->field($model, 'calificacion_osha')->widget(Select2::classname(), [
                    'data' =>  $tipososha,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?>
        </div>
    </div>

    <?php
        $url = Url::to(['firma']);
    ?>
    <div class="row my-3">
        <div class="col-lg-12 text-center">
            <?php if(isset($model->firma_ruta)):?>
            <img src="<?php  echo '/resources/Empresas/'.$model->id_empresa.'/Consultas/'.$model->id.'/'.$model->firma_ruta;?>"
                class="img-fluid img-responsive" width="500px" height="auto" />
            <?php endif;?>
        </div>
        <div class="col-lg-12 text-center">
            <!--  <?= $form->field($model, 'firma')->textInput(['maxlength' => true]) ?> -->
            <?= \inquid\signature\SignatureWidget::widget(['clear' => true, 'undo' => false, 'width'=>'800px','height'=>'300px', 'change_color' => false, 'url' => $url, 'save_server' => false, 'description'=>'<h5 class="text-center mt-0">Firma Trabajador </h5>']) ?>
        </div>
    </div>
    <div class="row" style="display:none;">
        <?= $form->field($model, 'firma')->textArea(['maxlength' => true]); ?>
        <?= $form->field($model, 'firmatxt')->textArea(['maxlength' => true]); ?>
    </div>

    <div class="row my-4">
        <div class="col-lg-4">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Abierta','2'=>'Cerrada'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => ''],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
        </div>
        <div class="col-lg-8 color11 y_centercenter">
            <label for="" class="y_centercenter border-bottom"><span class="mx-2"><i
                        class="bi bi-info-circle-fill"></i></span>Una vez cerrada la consulta clínica ya no podrá ser
                editada <span class="mx-2"><i class="bi bi-pen-fill"></i></span></label>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
$(document).ready(function(){
    console.log('ESTOY EN DOCUMENT READY');
   
});

Webcam.set({
    width: 350,
    height: 300,
    image_format: 'jpeg',
    jpeg_quality: 90
});

Webcam.attach('#live_camera');


JS;
$this->registerJS($script)
?>