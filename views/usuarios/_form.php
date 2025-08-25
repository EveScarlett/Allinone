<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empresas;
use app\models\Roles;
use kartik\file\FileInput;
use kartik\password\PasswordInput;

use app\models\Ubicaciones;
use app\models\Paises;
use app\models\Paisempresa;
use app\models\Lineas;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;


use app\models\Areas;
use app\models\Consultorios;
use app\models\ProgramaSalud;
use app\models\Programaempresa;

use app\models\Trabajadores;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$areas_trabajador = [];

$label_nivel1 = 'Nivel 1';
$label_nivel2 = 'Nivel 2';
$label_nivel3 = 'Nivel 3';
$label_nivel4 = 'Nivel 4';

$show_nivel1 = 'none';
$show_nivel2 = 'none';
$show_nivel3 = 'none';
$show_nivel4 = 'none';

$nivel1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['status'=>1])->orderBy('id_pais')->all(), 'id', function($data){
    $rtlvl1 = '';
    if($data->pais){
        $rtlvl1 = $data->pais->pais;
    }
    return $rtlvl1;
});
//dd($nivel1);

$nivel2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['status'=>1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
    $rtlvl2 = $data['nivelorganizacional2'];
    return $rtlvl2;
});

$nivel3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['status'=>1])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
    $rtlvl3 = $data['nivelorganizacional3'];
    return $rtlvl3;
});

$nivel4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['status'=>1])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
    $rtlvl4 = $data['nivelorganizacional4'];
    return $rtlvl4;
});


if(true){
    //dd($model->empresas_select);
    if($model->empresa_all == 1){
        $empresa = Empresas::findOne($model->id_empresa);
    } else {
        if($model->empresas_select == null || $model->empresas_select == '' || $model->empresas_select == ' '){
            $model->empresas_select = [$model->id_empresa];
        }
        if(count($model->empresas_select) == 1){
            $empresa = Empresas::find()->where(['in','id',$model->empresas_select])->one();
            if($empresa){
                $label_nivel1 = $empresa->label_nivel1;
                $label_nivel2 = $empresa->label_nivel2;
                $label_nivel3 = $empresa->label_nivel3;
                $label_nivel4 = $empresa->label_nivel4;
            }
        } else {
            $empresa = Empresas::find()->where(['in','id',$model->empresas_select])->orderBy(['cantidad_niveles'=>SORT_DESC])->one();
        }
    } 
    //dd($empresa,$model);

    if($empresa){
        $label_nivel1 = $empresa->label_nivel1;
        $label_nivel2 = $empresa->label_nivel2;
        $label_nivel3 = $empresa->label_nivel3;
        $label_nivel4 = $empresa->label_nivel4;

        if($empresa->cantidad_niveles >= 1){
            $show_nivel1 = 'block';

            //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel1])->andWhere(['nivel'=>1])->all(), 'id','area');
        }
        if($empresa->cantidad_niveles >= 2){
            $show_nivel2 = 'block';

            //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel2])->andWhere(['nivel'=>2])->all(), 'id','area');
        }
        if($empresa->cantidad_niveles >= 3){
            $show_nivel3 = 'block';

            //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel3])->andWhere(['nivel'=>3])->all(), 'id','area');
        }
        if($empresa->cantidad_niveles >= 4){
            $show_nivel4 = 'block';

            //$areas_trabajador = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['id_superior'=>$model->id_nivel4])->andWhere(['nivel'=>4])->all(), 'id','area');
        }
    }
}

//dd($label_nivel1,$label_nivel2,$label_nivel3,$label_nivel4,$show_nivel1,$show_nivel2,$show_nivel3,$show_nivel4);
?>

<?php
$showempresa = 'block';
$showpais = 'block';
$showlinea = 'block';
$showubicacion = 'block';
$empresas = explode(',', Yii::$app->user->identity->empresas_select);

if(Yii::$app->user->identity->empresa_all != 1){
    if(count($empresas) == 1){
        $showempresa = 'none';
    }
}
?>

<?php
$array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
if(Yii::$app->user->identity->empresa_all != 1) {
    $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$array_empresas])->orderBy('comercial')->all(), 'id', 'comercial');
} else{
    $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
}


if(Yii::$app->user->identity->role->hidden == 1){
    $roles = ArrayHelper::map(Roles::find()->orderBy('nombre')->all(), 'id', 'nombre');
} else {
    if($model->role && $model->role->hidden == 1){
        $roles = ArrayHelper::map(Roles::find()->orderBy('nombre')->all(), 'id', 'nombre');
    } else {
        $roles = ArrayHelper::map(Roles::find()->where(['IS', 'hidden', new \yii\db\Expression('NULL')])->orderBy('nombre')->all(), 'id', 'nombre');
    }
}


//NIVEL 1--------------------------------------------------------------------------------------
if($model->empresa_all != 1) {//NIVEL 1, NO TODAS LAS EMPRESAS *******************************
    $array_niveles_1 = NivelOrganizacional1::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['status'=>1])->orderBy('id_pais')->select(['id_pais'])->distinct()->all();
} else {//NIVEL 1, TODAS LAS EMPRESAS ********************************************************
    $array_niveles_1 = NivelOrganizacional1::find()->where(['status'=>1])->orderBy('id_pais')->select(['id_pais'])->distinct()->all();
}

$id_niveles1_temporal = [];
foreach($array_niveles_1 as $key=>$nivel){
    array_push($id_niveles1_temporal, $nivel->id_pais);
}
  
$paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_niveles1_temporal])->orderBy('pais')->all(), 'id', 'pais');

$retnivel1 = $model->nivel1_select;

if($model->nivel1_all == 1){
    if($model->empresa_all != 1) {
        $niveles_1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$model->empresas_select])->orderBy('id_pais')->all(), 'id', function($data){
                $ret_nivel = '';
                if($data->pais){
                    $ret_nivel = $data->pais->pais;
                }
                return $ret_nivel;
        });
    } else {
        $niveles_1 = ArrayHelper::map(NivelOrganizacional1::find()->orderBy('id_pais')->all(), 'id', function($data){
                $ret_nivel = '';
                if($data->pais){
                    $ret_nivel = $data->pais->pais;
                }
                return $ret_nivel;
        });
    }
    
} else {
    
    if($model->nivel1_select == null){
        $niveles_1 = null;
    } else {
        if($model->empresa_all != 1) {
            $niveles_1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_pais',$model->nivel1_select])->orderBy('id_pais')->all(), 'id', function($data){
                $ret_nivel = '';
                if($data->pais){
                    $ret_nivel = $data->pais->pais;
                }
                return $ret_nivel;
            });
        } else {
            $niveles_1 = ArrayHelper::map(NivelOrganizacional1::find()->where(['in','id_pais',$model->nivel1_select])->orderBy('id_pais')->all(), 'id', function($data){
                $ret_nivel = '';
                if($data->pais){
                    $ret_nivel = $data->pais->pais;
                }
                return $ret_nivel;
            });
        }
    }
}
//dd($niveles_1);
if($niveles_1){
    $retnivel1 = [];
    foreach($niveles_1 as $key=>$nivel){
        array_push($retnivel1, $key);
    }
} else {
    $retnivel1 = [];
}


//NIVEL 2--------------------------------------------------------------------------------------
if($model->empresa_all != 1) {//NIVEL 2, NO TODAS LAS EMPRESAS *******************************
    if($model->nivel1_all != 1) {//NO TODOS LOS NIVELES 1..........................
        $niveles_2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional1',$retnivel1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
            return $data['nivelorganizacional2'];
        });
    } else {//TODOS LOS NIVELES 1..................................................
        $niveles_2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional1',$retnivel1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
            return $data['nivelorganizacional2'];
        });
    }

} else {//NIVEL 2, TODAS LAS EMPRESAS ********************************************************
    if($model->nivel1_all != 1) {//NO TODOS LOS NIVELES 1..........................
        $niveles_2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['in','id_nivelorganizacional1',$retnivel1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
            return $data['nivelorganizacional2'];
        });
    } else {//TODOS LOS NIVELES 1..................................................
        $niveles_2 = ArrayHelper::map(NivelOrganizacional2::find()->where(['in','id_nivelorganizacional1',$retnivel1])->orderBy('nivelorganizacional2')->all(), 'id', function($data){
            return $data['nivelorganizacional2'];
        });
    }

}

$id_niveles2 = [];
if($niveles_2){
    foreach($niveles_2 as $key=>$nivel){
        array_push($id_niveles2, $key);
    }
}

$retnivel2 = $model->nivel2_select;
if($model->nivel2_select == null){
    $retnivel2 = [];
}


//NIVEL 3--------------------------------------------------------------------------------------
if($model->empresa_all != 1) {//NIVEL 3, NO TODAS LAS EMPRESAS *******************************
    if($model->nivel2_all != 1) {//NO TODOS LOS NIVELES 2..........................
        $niveles_3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional2',$retnivel2])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
            return $data['nivelorganizacional3'];
        });
    } else {//TODOS LOS NIVELES 2..................................................
        $niveles_3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional2',$id_niveles2])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
            return $data['nivelorganizacional3'];
        });
    }

} else {//NIVEL 3, TODAS LAS EMPRESAS ********************************************************
    if($model->nivel2_all != 1) {//NO TODOS LOS NIVELES 2..........................
        $niveles_3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['in','id_nivelorganizacional2',$retnivel2])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
            return $data['nivelorganizacional3'];
        });
    } else {//TODOS LOS NIVELES 2..................................................
        $niveles_3 = ArrayHelper::map(NivelOrganizacional3::find()->where(['in','id_nivelorganizacional2',$id_niveles2])->orderBy('nivelorganizacional3')->all(), 'id', function($data){
            return $data['nivelorganizacional3'];
        });
    }
}

$id_niveles3 = [];
if($niveles_3){
    foreach($niveles_3 as $key=>$nivel){
        array_push($id_niveles3, $key);
    }
}

$retnivel3 = $model->nivel3_select;
if($model->nivel3_select == null){
    $retnivel3 = [];
}



//NIVEL 4--------------------------------------------------------------------------------------
if($model->empresa_all != 1) {//NIVEL 4, NO TODAS LAS EMPRESAS *******************************
    if($model->nivel3_all != 1) {//NO TODOS LOS NIVELES 3..........................
        $niveles_4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional3',$retnivel3])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
            return $data['nivelorganizacional4'];
        });
    } else {//TODOS LOS NIVELES 3..................................................
        $niveles_4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['in','id_empresa',$model->empresas_select])->andWhere(['in','id_nivelorganizacional3',$id_niveles3])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
            return $data['nivelorganizacional4'];
        });
    }

} else {//NIVEL 4, TODAS LAS EMPRESAS ********************************************************
    if($model->nivel3_all != 1) {//NO TODOS LOS NIVELES 3..........................
        $niveles_4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['in','id_nivelorganizacional3',$retnivel3])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
            return $data['nivelorganizacional4'];
        });
    } else {//TODOS LOS NIVELES 3..................................................
        $niveles_4 = ArrayHelper::map(NivelOrganizacional4::find()->where(['in','id_nivelorganizacional3',$id_niveles3])->orderBy('nivelorganizacional4')->all(), 'id', function($data){
            return $data['nivelorganizacional4'];
        });
    }
}

$id_niveles4 = [];
if($niveles_4){
    foreach($niveles_4 as $key=>$nivel){
        array_push($id_niveles4, $key);
    }
}

$retnivel4 = $model->nivel4_select;
if($model->nivel4_select == null){
    $retnivel4 = [];
}



$areas = [];
$consultorios = [];
$programas = [];

//dd($model);
if(count($model->empresas_select) == 1){
    if($model->empresa_all == 1){
        $empresa = Empresas::findOne($model->id_empresa);
    } else {
        $empresa = Empresas::find()->where(['in','id',$model->empresas_select])->one();
    }
    
    if($empresa){
        if($empresa->cantidad_niveles == 1){
            $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>1])->andWhere(['in','id_superior',$retnivel1])->orderBy('area')->all(), 'id', function($data){
                return $data['area'];
            });

            $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>1])->andWhere(['in','id_superior',$retnivel1])->orderBy('consultorio')->all(), 'id', function($data){
                return $data['consultorio'];
            });

            $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>1])->andWhere(['in','id_superior',$retnivel1])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                return $data['id_programa'];
            });
            if($array_programas){
                $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                    return $data['nombre'];
                });
            }
        } else if($empresa->cantidad_niveles == 2){
            if($model->nivel2_all != 1) {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$retnivel2])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$retnivel2])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$retnivel2])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            } else {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$id_niveles2])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$id_niveles2])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>2])->andWhere(['in','id_superior',$retnivel2])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            }
            
        } else if($empresa->cantidad_niveles == 3){
            if($model->nivel3_all != 1) {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$retnivel3])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$retnivel3])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$retnivel3])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            } else {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$id_niveles3])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$id_niveles3])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>3])->andWhere(['in','id_superior',$retnivel3])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            }
            
        } else if($empresa->cantidad_niveles == 4){
            if($model->nivel4_all != 1) {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$retnivel4])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$retnivel4])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$retnivel4])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            } else {
                $areas = ArrayHelper::map(Areas::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$id_niveles4])->orderBy('area')->all(), 'id', function($data){
                    return $data['area'];
                });

                $consultorios = ArrayHelper::map(Consultorios::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$id_niveles4])->orderBy('consultorio')->all(), 'id', function($data){
                    return $data['consultorio'];
                });

                $array_programas = ArrayHelper::map(Programaempresa::find()->where(['id_empresa'=>$empresa->id])->andWhere(['nivel'=>4])->andWhere(['in','id_superior',$retnivel4])->orderBy('id_programa')->select(['id_programa'])->distinct()->all(), 'id_programa', function($data){
                    return $data['id_programa'];
                });
                if($array_programas){
                    $programas = ArrayHelper::map(ProgramaSalud::find()->where(['in','id',$array_programas])->orderBy('nombre')->all(), 'id', function($data){
                        return $data['nombre'];
                    });
                }
            }
            
        }
    }
}

//dd($model,$label_nivel1,$label_nivel2,$label_nivel3,$label_nivel4,$show_nivel1,$show_nivel2,$show_nivel3,$show_nivel4,'array_niveles_1',$array_niveles_1,'$id_niveles1','NIVEL 1',$niveles_1,$id_niveles1_temporal,'PAISES',$paises,$retnivel1,'NIVEL 2',$niveles_2,$id_niveles2,'NIVEL 3',$niveles_3,$id_niveles3,'NIVEL 4',$niveles_4,$id_niveles4);

//$lineas = ArrayHelper::map(Lineas::find()->where(['in','id_pais',$id_paises])->orderBy('linea')->all(), 'id', 'linea');

//$ubicaciones = ArrayHelper::map(Ubicaciones::find()->where(['id_empresa'=>$model->id_empresa])->orderBy('ubicacion')->all(), 'id', 'ubicacion');
  
/* $roles[0] = 'OTRO'; */
//dd($roles);
?>


<?php
$trabajadores = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$model->id_empresa])->orderBy(['status'=>SORT_ASC,'nombre'=>SORT_ASC])->all(), 'id', function($data){
    return $data['nombre'].' '.$data['apellidos'];
});
?>

<?php
$this->registerCss("
.kv-scorebar-border{
    border:1px solid transparent;
    background: none repeat scroll 0 0 transparent;
}
.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
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
<div class="usuarios-form">

    <?php $form = ActiveForm::begin(['id'=>'formOHC',
'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <div class="row">
        <div class="col-lg-4" style="display:none;">
            <?= $form->field($model, 'envia_form')->textInput(['maxlength' => true,'type'=>'hidden']) ?>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-lg-2">
            <div class="row">
                <?php
                     $avatar = '/resources/images/'.'user1.png';
                     if($model->foto){
                        $avatar = '/resources/images/perfil/'.$model->foto;
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
                <div class="col-lg-4" style="display:<?php echo $showempresa?>;">
                    <?= $form->field($model, 'id_empresa')->widget(Select2::classname(), [
                    'data' => $empresas,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaEmpresa(this.value,"trabajadores")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']) ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => ['1'=>'Activo','2'=>'Baja'],
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'),
                    'onchange' => 'cambiaStatususuario(this.value)'
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]); ?>
                </div>
            </div>
            <div class="row my-3">
                <?php
                $showrol = 'none';
                if(!$model->role || $model->role->hidden != 1){
                    $showrol = 'block';
                }
                ?>

                <div class="col-lg-3" style="display:<?php echo $showrol;?>;">
                    <?= $form->field($model, 'rol')->widget(Select2::classname(), [
                    'data' => $roles,
                    'options' => ['placeholder' => yii::t('app', 'SELECCIONA--'), 
                    'onchange' => 'cambiaOtro(this.value,"usuarios","rol")'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>
                </div>
                <div class="col-lg-3" id="otra_rol" style="display:none;">
                    <?= $form->field($model, 'otra_rol')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);']);?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-5">
                    <?= $form->field($model, 'password')->widget(PasswordInput::classname(), ['language' => 'es']); ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    $iconempresa = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
    <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022ZM6 8.694 1 10.36V15h5V8.694ZM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15Z"/>
    <path d="M2 11h1v1H2v-1Zm2 0h1v1H4v-1Zm-2 2h1v1H2v-1Zm2 0h1v1H4v-1Zm4-4h1v1H8V9Zm2 0h1v1h-1V9Zm-2 2h1v1H8v-1Zm2 0h1v1h-1v-1Zm2-2h1v1h-1V9Zm0 2h1v1h-1v-1ZM8 7h1v1H8V7Zm2 0h1v1h-1V7Zm2 0h1v1h-1V7ZM8 5h1v1H8V5Zm2 0h1v1h-1V5Zm2 0h1v1h-1V5Zm0-2h1v1h-1V3Z"/>
    </svg>';

    $iconpais = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z"/>
    </svg>';

    $iconubicacion = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-fill" viewBox="0 0 16 16">
    <path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
    </svg>';

    $iconlinea = '<i class="bi bi-layers-half"></i>';

    $iconnivel = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
    </svg>';

    $iconconsultorio = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5"/>
    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0"/>
    </svg>';

    $iconprograma = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse" viewBox="0 0 16 16">
    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5"/>
    <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z"/>
    </svg>';
    ?>
    <div class="row my-3 p-2" style="display:<?php echo $showempresa?>;">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo $iconempresa;?></span>Empresas
            <br><span class="small mx-2 color1 font500">Seleccione las empresas que podrá administrar el usuario</span>
        </h1>
        <div class="col-lg-3" style="display:none;">
            <?php
            echo $form->field($model, 'empresa_all')->checkBox([
                'label' => '<span class="font500">Todas las Empresas</span>',
                'onChange'=>'seleccionaEmpresas(this.value,"usuarios")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-8 offset-lg-1">
            <?php
                echo $form->field($model, 'empresas_select')->widget(Select2::classname(), [ 
                    'data' => $empresas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => 'seleccionaEmpresas(this.value,"usuarios")'],
                    'pluginOptions' => [    
                    ],])->label(); 
                ?>
        </div>
    </div>

    <div class="row my-3 p-2" style="display:<?php echo $show_nivel1?>;">
        <h1 class="title2 boxtitle2 p-1 rounded-3 text-dark my-3 p-3"><span
                class="mx-2"><?php echo $iconnivel;?></span><?=$label_nivel1;?>
            <br><span class="small mx-2 text-dark font500">Seleccione <?=$label_nivel1;?> que podrá administrar el
                usuario</span>
        </h1>
        <div class="col-lg-3" style="display:block;">
            <?php
            echo $form->field($model, 'nivel1_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'seleccionaEmpresas(this.value,"usuarios")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-7 offset-lg-1">
            <?php
                echo $form->field($model, 'nivel1_select')->widget(Select2::classname(), [ 
                    'data' => $paises,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => 'seleccionaEmpresas(this.value,"usuarios")'
                    ],
                    'pluginOptions' => [    
                    ],])->label('Seleccione '.$label_nivel1); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2" style="display:<?php echo $show_nivel2?>;">
        <h1 class="title2 boxtitle2 p-1 rounded-3 text-dark my-3 p-3"><span
                class="mx-2"><?php echo $iconnivel;?></span><?=$label_nivel2;?>
            <br><span class="small mx-2 text-dark font500">Seleccione <?=$label_nivel2;?> que podrá administrar el
                usuario</span>
        </h1>
        <div class="col-lg-3" style="display:block;">
            <?php
            echo $form->field($model, 'nivel2_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'seleccionaEmpresas(this.value,"usuarios")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-7 offset-lg-1">
            <?php
                echo $form->field($model, 'nivel2_select')->widget(Select2::classname(), [ 
                    'data' => $niveles_2,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => 'seleccionaEmpresas(this.value,"usuarios")'],
                    'pluginOptions' => [    
                    ],])->label('Seleccione '.$label_nivel2); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2" style="display:<?php echo $show_nivel3?>;">
        <h1 class="title2 boxtitle2 p-1 rounded-3 text-dark my-3 p-3"><span
                class="mx-2"><?php echo $iconnivel;?></span><?=$label_nivel3;?>
            <br><span class="small mx-2 text-dark font500">Seleccione <?=$label_nivel3;?> que podrá administrar el
                usuario</span>
        </h1>
        <div class="col-lg-3" style="display:block;">
            <?php
            echo $form->field($model, 'nivel3_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'seleccionaEmpresas(this.value,"usuarios")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-7 offset-lg-1">
            <?php
                echo $form->field($model, 'nivel3_select')->widget(Select2::classname(), [ 
                    'data' => $niveles_3,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => 'seleccionaEmpresas(this.value,"usuarios")'],
                    'pluginOptions' => [    
                    ],])->label('Seleccione '.$label_nivel3); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2" style="display:<?php echo $show_nivel4?>;">
        <h1 class="title2 boxtitle2 p-1 rounded-3 text-dark my-3 p-3"><span
                class="mx-2"><?php echo $iconnivel;?></span><?=$label_nivel4;?>
            <br><span class="small mx-2 text-dark font500">Seleccione <?=$label_nivel4;?> que podrá administrar el
                usuario</span>
        </h1>
        <div class="col-lg-3" style="display:block;">
            <?php
            echo $form->field($model, 'nivel4_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'seleccionaEmpresas(this.value,"usuarios")',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-7 offset-lg-1">
            <?php
                echo $form->field($model, 'nivel4_select')->widget(Select2::classname(), [ 
                    'data' => $niveles_4,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => 'seleccionaEmpresas(this.value,"usuarios")'],
                    'pluginOptions' => [    
                    ],])->label('Seleccione '.$label_nivel3); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo $iconubicacion;?></span><?='Áreas';?>
            <br><span class="small mx-2 color1 font500">Seleccione Áreas que podrá administrar el usuario</span>
        </h1>
        <div class="col-lg-2" style="display:block;">
            <?php
            echo $form->field($model, 'areas_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-10">
            <?php
                echo $form->field($model, 'areas_select')->widget(Select2::classname(), [ 
                    'data' => $areas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => ''],
                    'pluginOptions' => [    
                    ],])->label('Seleccione Áreas'); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo $iconconsultorio;?></span><?='Consultorios';?>
            <br><span class="small mx-2 color1 font500">Seleccione Consultorios que podrá administrar el usuario</span>
        </h1>
        <div class="col-lg-2" style="display:block;">
            <?php
            echo $form->field($model, 'consultorios_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-10">
            <?php
                echo $form->field($model, 'consultorios_select')->widget(Select2::classname(), [ 
                    'data' => $consultorios,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => ''],
                    'pluginOptions' => [    
                    ],])->label('Seleccione Consultorios'); 
                ?>
        </div>
    </div>


    <div class="row my-3 p-2">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo $iconprograma;?></span><?='Programas de Salud';?>
            <br><span class="small mx-2 color1 font500">Seleccione Programas de Salud que podrá administrar el
                usuario</span>
        </h1>
        <div class="col-lg-2" style="display:block;">
            <?php
            echo $form->field($model, 'programas_all')->checkBox([
                'label' => '<span class="font500">Todo/as</span>',
                'onChange'=>'',
                'class' => 'largerCheckbox25',
                'options' => [
                ]])->label(false);               
            ?>
        </div>
        <div class="col-lg-10">
            <?php
                echo $form->field($model, 'programas_select')->widget(Select2::classname(), [ 
                    'data' => $programas,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => ''],
                    'pluginOptions' => [    
                    ],])->label('Seleccione Consultorios'); 
                ?>
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
            <div class="row p-2" style="display:none;">
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

                        <tr style="display:none;">
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                            </th>
                            <!-- FILA 0-->
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_listado')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>', 
                                        'onClick'=>'cambiaPermisos("columna_listado")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php 
                                    echo $form->field($model, 'columna_exportar')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_exportar")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_crear')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_crear")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_ver')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_ver")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_actualizar")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_expediente')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_expediente")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #4149D9;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_cerrarexpediente')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>', 
                                        'onChange'=>'cambiaPermisos("columna_cerrarexpediente")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_eliminar')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_eliminar")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_entrega')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_entrega")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                            <th class="text-center text-light" style="font-weight:600;background-color: #636AF2;">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'columna_documento')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-down"></i></span>',
                                        'onChange'=>'cambiaPermisos("columna_documento")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <!-- FILA 1 EMPRESAS-->
                            <?php $index = 1;?>
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Empresas</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_1')->checkBox([
                                        'label' => '<span class="mx-2 text-light"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_1")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'empresas_listado')->checkBox([ 
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->empresas_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'empresas_exportar')->checkBox([ 
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->empresas_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'empresas_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->empresas_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'empresas_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->empresas_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'empresas_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->empresas_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_2')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_2")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_2 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'turnos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->turnos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'turnos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->turnos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'turnos_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->turnos_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Firmas Medico Laboral</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_3')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_3")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_2 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Firmas Medico Laboral</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_4')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_4")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_4 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'firmas_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->firmas_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consentimientos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consentimientos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consentimientos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consentimientos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consentimientos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consentimientos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consentimientos_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consentimientos_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_5')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_5")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_3 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_expediente')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-folder-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_expediente == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'trabajadores_delete')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->trabajadores_delete == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_6')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_6")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_4 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historial_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historial_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historial_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historial_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_7')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_7")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_5 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'puestos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->puestos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'puestos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->puestos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'puestos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->puestos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'puestos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->puestos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'puestos_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->puestos_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Vacantes</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_8')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_8")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_4 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'vacantes_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->vacantes_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'vacantes_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->vacantes_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'vacantes_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->vacantes_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'vacantes_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->vacantes_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'vacantes_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->vacantes_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_9')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_9")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_6 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'requerimientos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->requerimientos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'requerimientos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->requerimientos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <!-- <?php
                                    echo $form->field($model, 'requerimientos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?> -->
                                </div>
                                <?php 
                                if($model->requerimientos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'requerimientos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->requerimientos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'requerimientos_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->requerimientos_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Incapacidades</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_10')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_10")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_10 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'incapacidades_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->incapacidades_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'incapacidades_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->incapacidades_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'incapacidades_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->incapacidades_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'incapacidades_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->incapacidades_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'incapacidades_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->incapacidades_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_11')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_11")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_11 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargatrabajadores_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargatrabajadores_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargatrabajadores_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargatrabajadores_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargatrabajadores_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargatrabajadores_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargatrabajadores_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargatrabajadores_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargatrabajadores_eliminar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargatrabajadores_eliminar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_12')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_12")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_12 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consultas_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consultas_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consultas_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consultas_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consultas_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consultas_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consultas_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consultas_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'consultas_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->consultas_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_13')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_13")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_9 == 13){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_cerrarexpediente')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-folder2-open"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_cerrarexpediente == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_delete')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_delete == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_14')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_14")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_14 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagnosticoscie_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagnosticoscie_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagnosticoscie_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagnosticoscie_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagnosticoscie_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagnosticoscie_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagnosticoscie_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagnosticoscie_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagnosticoscie_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagnosticoscie_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_15')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_15")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_15 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'nordicos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->nordicos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'nordicos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->nordicos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'nordicos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->nordicos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'nordicos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->nordicos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_16')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_16")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_16 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'antropometricos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->antropometricos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'antropometricos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->antropometricos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'antropometricos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->antropometricos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'antropometricos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->antropometricos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <label class=""><?=$index++;?>) Stock Actual Medicamentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_17')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_17")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_17 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstock_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstock_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstock_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstock_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <label class=""><?=$index++;?>) Bitácora de Movimientos Medicamentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_18')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_18")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_18 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosbitacora_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosbitacora_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosbitacora_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosbitacora_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosbitacora_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosbitacora_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosbitacora_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosbitacora_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosbitacora_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosbitacora_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <label class=""><?=$index++;?>) Catálogo Medicamentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_19')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_19")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_19 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentos_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentos_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentos_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentos_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentos_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentos_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentos_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentos_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentos_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentos_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <label class=""><?=$index++;?>) Mínimos Medicamentos</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_20')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_20")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_20 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstockmin_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstockmin_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstockmin_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstockmin_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstockmin_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstockmin_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstockmin_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstockmin_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'medicamentosstockmin_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->medicamentosstockmin_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_21')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_21")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_21 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstock_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstock_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstock_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstock_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_22')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',  
                                        'onChange'=>'cambiaPermisos("linea_22")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_22 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsbitacora_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsbitacora_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsbitacora_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsbitacora_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsbitacora_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',  
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsbitacora_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php 
                                    echo $form->field($model, 'eppsbitacora_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsbitacora_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsbitacora_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsbitacora_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_23')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_23")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_23 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'epp_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->epp_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'epp_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->epp_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'epp_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->epp_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'epp_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->epp_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'epp_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->epp_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_24')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_24")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_24 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstockmin_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstockmin_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstockmin_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstockmin_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstockmin_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'', 
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstockmin_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstockmin_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstockmin_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'eppsstockmin_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->eppsstockmin_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_25')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_25")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_25 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_entrega')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-card-checklist"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_entrega == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'poes_documento')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-card-checklist"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->poes_documento == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <!-- FILA 26 CONFIGURAR LISTADO DE ESTUDIOS-->
                            <td class="text-light p-3" style="background-color: #636AF2">
                                <label class=""><?=$index++;?>) Configurar Listado de Estudios</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #636AF2">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_26')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_26")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_26 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'estudios_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->estudios_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php 
                                    echo $form->field($model, 'estudios_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->estudios_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'estudios_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->estudios_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'estudios_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->estudios_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'estudios_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->estudios_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_27')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_27")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_27 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'ordenpoe_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->ordenpoe_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'ordenpoe_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->ordenpoe_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'ordenpoe_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->ordenpoe_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'ordenpoe_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->ordenpoe_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'ordenpoe_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->ordenpoe_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_28')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_28")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_28 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargapoes_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargapoes_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargapoes_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargapoes_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargapoes_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargapoes_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargapoes_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargapoes_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'cargapoes_eliminar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-trash"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->cargapoes_eliminar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_29')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_29")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_29 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'usuarios_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->usuarios_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'usuarios_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->usuarios_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'usuarios_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->usuarios_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'usuarios_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->usuarios_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'usuarios_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->usuarios_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_30')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_30")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_30 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'configuracion_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->configuracion_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'configuracion_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->configuracion_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'configuracion_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->configuracion_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'configuracion_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->configuracion_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_31')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>',
                                        'onChange'=>'cambiaPermisos("linea_31")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_31 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'categoriaestudio_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->categoriaestudio_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'categoriaestudio_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-share-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->categoriaestudio_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php 
                                    echo $form->field($model, 'categoriaestudio_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-plus-lg"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->categoriaestudio_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'categoriaestudio_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->categoriaestudio_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php 
                                    echo $form->field($model, 'categoriaestudio_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->categoriaestudio_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Roles</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_32')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_32")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_32 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'roles_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->roles_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'roles_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->roles_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'roles_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->roles_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'roles_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->roles_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'roles_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->roles_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Programas de Salud</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_33')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_33")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_33 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-list-ol"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_exportar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_exportar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_crear')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_crear == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-eye-fill"></i></span>',
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <!-- FILA 34 Programas de Salud en Historia Clínica-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Registrar Programas de Salud en Historia Clínica</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_34')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_34")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_34 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'programasalud_hc')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->programasalud_hc == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <!-- FILA 35 Corregir Historia Clínica-->
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Corregir Historia Clínica</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_35')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_35")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_35 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'historias_corregir')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->historias_corregir == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Diagrama Empresa</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_36')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_36")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_36 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagrama_ver')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagrama_ver == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'diagrama_actualizar')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->diagrama_actualizar == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
                            <td class="text-light p-3" style="background-color: #4149D9">
                                <label class=""><?=$index++;?>) Papelera de Reciclaje</label>
                            </td>
                            <td class="text-center text-light" style="background-color: #4149D9">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'linea_37')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-arrow-right"></i></span>', 
                                        'onChange'=>'cambiaPermisos("linea_37")',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->linea_37 == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div style="display:none;">
                                    <?php
                                    echo $form->field($model, 'papelera_listado')->checkBox([
                                        'label' => '<span class="mx-2 color4"><i class="bi bi-pen-fill"></i></span>', 
                                        'onChange'=>'',
                                        'class' => 'largerCheckbox25',
                                        'options' => [
                                        ]])->label(false);
                                    ?>
                                </div>
                                <?php 
                                if($model->papelera_listado == 1){
                                    echo '<div class="badge font12 m-1 bgcumple text-dark"><span class="mx-2"><i class="bi bi-check2"></i></span></div>';
                                }
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
    <?php 
        $show = 'none';
        /* if($model->empresa){
            $roles_permitidos = $model->empresa->configuracion->rolesfirma;
            $roles_permitidos = explode(',', $roles_permitidos);
            if (in_array($model->rol, $roles_permitidos)) {
                $show = 'block';
            }
        } */

        $rolc = Roles::findOne($model->rol);
        if($rolc){
            if($rolc->admite_firma == 1){
                $show = 'block';
            }
        }

        

    ?>
    <div class="container my-3 p-2 mb-5" id='bloque_firmas' style="display:<?php echo $show;?>;">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo '<i class="bi bi-pen"></i>';?></span>Firma
            <br><span class="small mx-2 color1 font500">Ingrese correctamente los datos de firma del usuario, recuerde
                que la firma aparece en documentos con validez médica</span>
        </h1>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'f_nombre')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
            <div class="col-lg-8">
                <?= $form->field($model, 'f_universidad')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'f_titulo')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'f_abreviado_titulo')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'f_cedula')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'f_registro_sanitario')->textInput(['maxlength' => true,'onkeyup' => 'converToMayus(this);'])->label(); ?>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'file_firma')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*,pdf'],
                    ])->label('Firma (Formato .png fondo transparente)'); ?>
            </div>
            <div class="col-lg-4 text-center">
                <?php
                if(isset($model->f_firma)){
                    echo '<label class="control-label">Firma Actual</label><br>';
                    $filePath =  '/resources/firmas/'.$model->f_firma;
                    $ret = Html::img('@web'. $filePath, ['alt'=>' ', 'class' => "img-responsive", 'width' => '150px', 'height' =>'auto','style'=>'height:100px; width:auto;opacity: 0.6;']);
                    echo $ret;
                }
                ?>
            </div>
        </div>
    </div>


    <div class="row my-4">
        <h1 class="title2 boxtitle p-1 rounded-3 color3 my-3 p-3"><span
                class="mx-2"><?php echo '<i class="bi bi-people-fill"></i>';?></span>Trabajadores
            <br><span class="small mx-2 color1 font500">Vincule los trabajadores que manejarán este usuario. </span>
        </h1>
        <div class="col-lg-8">
            <?php
                echo $form->field($model, 'trabajadores_select')->widget(Select2::classname(), [ 
                    'data' => $trabajadores,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione ...', 'multiple' => true, 'autocomplete' => 'off',
                    'onchange' => ''],
                    'pluginOptions' => [    
                    ],])->label(); 
                ?>
        </div>

    </div>


    <div class="row">
        <div class="col-lg-4 d-grid">
            <?= Html::button('Guardar <i class="bi bi-hdd"></i>', ['class' => 'btn btn-primary btnnew btn-block','id'=>'guardarbutton']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>