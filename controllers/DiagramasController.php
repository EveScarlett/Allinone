<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\DiagramasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\models\NivelOrganizacional1;
use app\models\NivelOrganizacional2;
use app\models\NivelOrganizacional3;
use app\models\NivelOrganizacional4;

use app\models\Trabajadores;
use app\models\Hccohc;
use app\models\Consultas;
use app\models\Poes;
use app\models\Cuestionario;
use app\models\ProgramaSalud;
use app\models\ProgramaTrabajador;
use app\models\Kpis;

use app\models\Historicodiassinaccidentes;
use app\models\Usuarios;


use Yii;
/**
 * DiagramasController implements the CRUD actions for Empresas model.
 */
class DiagramasController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Empresas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiagramasSearch();
        $searchModel->id = Yii::$app->user->identity->id_empresa;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $model = Empresas::findOne($searchModel->id);
        if($model){
            $this->calcularKPI2($model);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionIndexkpi($id=null)
    {
        $searchModel = new DiagramasSearch();

        if($id != null && $id != '' && $id != ' '){
            $searchModel->id = $id;
        } else {
            $searchModel->id = Yii::$app->user->identity->id_empresa;
        }
        
        $dataProvider = $searchModel->search($this->request->queryParams);

        $model = Empresas::findOne($searchModel->id);
        if($model){
            $this->calcularKPI3($model);
        }

        return $this->render('indexkpi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function calcularKPI3($model){
        //return null;
        //dd('entra a calcular kpi');

        $id_empresa = $model->id;


        $cantidad_niveles = $model->cantidad_niveles;

        if($cantidad_niveles != null && $cantidad_niveles != '' && $cantidad_niveles != ' '){

            $sumatoria_empresa = 0;
            $qty_empresa = 0;
            $kpi_empresa = 0;

            $nivel_1 = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->orderBy('id_pais')->all();
            //dd($nivel_1);
            if($nivel_1){
                $qty_empresa = count($nivel_1);

                foreach($nivel_1 as $key1=>$nivel1){
                    
                    $sumatoria_nivel1 = 0;
                    $qty_nivel1 = 0;
                    $kpi_nivel1 = 0;

                    if($cantidad_niveles > 1){
                        $nivel_2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['status'=>1])->all();

                        if($nivel_2){
                            $qty_nivel1 = count($nivel_2);

                            foreach($nivel_2 as $key2=>$nivel2){

                                $sumatoria_nivel2 = 0;
                                $qty_nivel2 = 0;
                                $kpi_nivel2 = 0;

                                if($cantidad_niveles > 2){
                                    $nivel_3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['status'=>1])->all();

                                    if($nivel_3){
                                        $qty_nivel2 = count($nivel_3);

                                        foreach($nivel_3 as $key3=>$nivel3){

                                            $sumatoria_nivel3 = 0;
                                            $qty_nivel3 = 0;
                                            $kpi_nivel3 = 0;


                                            if($cantidad_niveles > 3){
                                                $nivel_4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->all();

                                                if($nivel_4){
                                                    $qty_nivel3 = count($nivel_4);

                                                    foreach($nivel_4 as $key4=>$nivel4){

                                                        $sumatoria_nivel4 = 0;
                                                        $qty_nivel4 = 0;
                                                        $kpi_nivel4 = 0;

                                                        $sumatoria_nivel3 += $nivel4->kpi_cumplimiento;
                                                    }
                                                }

                                                if($qty_nivel3 > 0){
                                                    $kpi_nivel3 = ($sumatoria_nivel3)/$qty_nivel3;
                                                }
                                                $nivel3->kpi_cumplimiento = $kpi_nivel3;
                                                $nivel3->save(false);
                        
                                            }

                                            $sumatoria_nivel2 += $nivel3->kpi_cumplimiento;
                                        }
                                    }

                                    if($qty_nivel2 > 0){
                                        $kpi_nivel2 = ($sumatoria_nivel2)/$qty_nivel2;
                                    }
                                    $nivel2->kpi_cumplimiento = $kpi_nivel2;
                                    $nivel2->save(false);
                        
                                }

                                $sumatoria_nivel1 += $nivel2->kpi_cumplimiento;
                            }
                        }

                        if($qty_nivel1 > 0){
                            $kpi_nivel1 = ($sumatoria_nivel1)/$qty_nivel1;
                        }
                        $nivel1->kpi_cumplimiento = $kpi_nivel1;
                        $nivel1->save(false);
                        
                    }

                    $sumatoria_empresa += $nivel1->kpi_cumplimiento;
                }
                
                if($qty_empresa > 0){
                    $kpi_empresa = ($sumatoria_empresa)/$qty_empresa;
                }
                
            }

            $model->kpi_cumplimiento = $kpi_empresa;
            $model->save(false);
            //dd('cantidad_niveles',$cantidad_niveles,'cantidad_kpis',$cantidad_kpis,'id_kpis',$id_kpis,'model',$model,'nivel_1',$nivel_1);
        }
    }

    private function calcularKPI2($model){
        //return null;
        //dd('entra a calcular kpi');

        $id_empresa = $model->id;


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
        //dd($kpis_mixed);


        $cantidad_niveles = $model->cantidad_niveles;

        if($cantidad_niveles != null && $cantidad_niveles != '' && $cantidad_niveles != ' '){

            $valor_porcentaje = 100;
           
            $qty_workers_empresa = 0;
            $kpi_empresa = 0;
            $valor_porcentaje_empresa = 0;

            $nivel_1 = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->orderBy('id_pais')->all();
            
            if($nivel_1){
                $valor_porcentaje_empresa = 100/count($nivel_1);
                $sumatoria_empresa = 0;

                foreach($nivel_1 as $key=>$nivel1){
                    $kpi_nivel1_[$key] = 0;
                    $id_nivel1 = $nivel1->id;
                    $valor_porcentaje_nivel1 = 0;
                    $qty_workers_nivel1_[$key] = 0;
                

                    if($model->cantidad_niveles > 1){
                        $nivel_2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['status'=>1])->all();
                        
                        if($nivel_2){
                            $valor_porcentaje_nivel1 = 100/count($nivel_2);
                            $sumatoria_nivel1 = 0;

                            foreach($nivel_2 as $key2=>$nivel2){
                                $kpi_nivel2_[$key2] = 0;
                                $id_nivel2 = $nivel2->id;
                                $valor_porcentaje_nivel2 = 0;
                                $qty_workers_nivel2_[$key2] = 0;


                                if($model->cantidad_niveles > 2){
                                    $nivel_3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id_pais])->andWhere(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['status'=>1])->all();
                                    
                                    if($nivel_3){
                                        $valor_porcentaje_nivel2 = 100/count($nivel_3);
                                        $sumatoria_nivel2 = 0;

                                        foreach($nivel_3 as $key3=>$nivel3){
                                            $kpi_nivel3_[$key3] = 0;
                                            $id_nivel3 = $nivel3->id;
                                            $qty_workers_nivel3_[$key3] = 0;


                                            if($model->cantidad_niveles > 3){
                                                $valor_porcentaje_nivel3 = 0;

                                                $nivel_4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id_pais])->andWhere(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->all();
                                                
                                                if($nivel_4){
                                                    $valor_porcentaje_nivel3 = 100/count($nivel_4);
                                                    $sumatoria_nivel3 = 0;

                                                    foreach($nivel_4 as $key4=>$nivel4){
                                                        $kpi_nivel4_[$key4] = 0;
                                                        $id_nivel4 = $nivel4->id;
                                                        $qty_workers_nivel4_[$key4] = 0;
                                                        $nivel = 4;
                                                        $valor_porcentaje = 100;


                                                        $cantidad_trabajadores = 0;
                                                        $id_trabajadores = [];
                                                        $trabajadores_activos = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','status',[1,3]])->all(), 'id', 'id');

                                                        if($trabajadores_activos){
                                                            $cantidad_trabajadores = count($trabajadores_activos);
                                                        }

                                                        $id_kpis = [];
                                                        $id_kpis = Kpis::find()->where(['id_superior'=>$nivel4->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id])->andWhere(['status'=>1])->all();
                                                        //dd($id_kpis);
                                    
                                                        if(count($id_kpis)>0){
                                                            $valor_porcentaje = 100/(count($id_kpis));//% que vale cada kpi, si hay 4 entonces seria 25% etc

                                                            foreach($id_kpis as $key_kpi=>$kpi){
                                                                $data_kpi = $kpi->kpi;
                                                                $id_programa = $kpi->id_programa;

                                                                $ret_kpi = $this->calcularKpiindividual2($data_kpi,$id_programa,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel4,$kpis_mixed,$trabajadores_activos,$cantidad_trabajadores);
                                                                $result = $ret_kpi['kpi'];
                                                                $kpi_nivel4_[$key4] += $result;
                                                            }
                                                        }

                                                        $qty_workers_nivel4_[$key4] = $cantidad_trabajadores;


                                                        $nivel4->kpi_cumplimiento = $kpi_nivel4_[$key4];
                                                        $nivel4->save(false);

                    
                                                        $nivel4->qty_trabajadores = $qty_workers_nivel4_[$key4];
                                                        $nivel4->save(false);
                                                        $qty_workers_nivel3_[$key3] += $qty_workers_nivel4_[$key4];

                                                        $sumatoria_nivel3 += (($kpi_nivel4_[$key4]*$valor_porcentaje_nivel3)/100);
   
                                                    }
                                                    $kpi_nivel3_[$key3] = $sumatoria_nivel3;
                                                    //dd($sumatoria_nivel3);
                                                }
                                            } else {
                                                //calcular aqui solo lo de 3 nivel
                                                $nivel = 3;
                                                $id_nivel4 = null;

                                                $valor_porcentaje = 100;


                                                $cantidad_trabajadores = 0;
                                                $id_trabajadores = [];
                                                $trabajadores_activos = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','status',[1,3]])->all(), 'id', 'id');

                                                if($trabajadores_activos){
                                                    $cantidad_trabajadores = count($trabajadores_activos);
                                                }

                                                $id_kpis = [];
                                                $id_kpis = Kpis::find()->where(['id_superior'=>$nivel3->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id])->andWhere(['status'=>1])->all();


                                                if(count($id_kpis)>0){
                                                    $valor_porcentaje = 100/(count($id_kpis));//% que vale cada kpi, si hay 4 entonces seria 25% etc
                                                    
                                                    foreach($id_kpis as $key_kpi=>$kpi){
                                                        $data_kpi = $kpi->kpi;
                                                        $id_programa = $kpi->id_programa;

                                                        $ret_kpi = $this->calcularKpiindividual2($data_kpi,$id_programa,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel3,$kpis_mixed,$trabajadores_activos,$cantidad_trabajadores);
                                                        $result = $ret_kpi['kpi'];
                                                        $kpi_nivel3_[$key3] += $result;
                                                    }
                                                }

                                                $qty_workers_nivel3_[$key3] = $cantidad_trabajadores;
                                            }


                                            $nivel3->kpi_cumplimiento = $kpi_nivel3_[$key3];
                                            $nivel3->save(false);


                                            $nivel3->qty_trabajadores = $qty_workers_nivel3_[$key3];
                                            $nivel3->save(false);
                                            $qty_workers_nivel2_[$key2] += $qty_workers_nivel3_[$key3];

                                            $sumatoria_nivel2 += (($kpi_nivel3_[$key3]*$valor_porcentaje_nivel2)/100);
                                        }

                                        $kpi_nivel2_[$key2] = $sumatoria_nivel2;
                                    }
                                } else {
                                    //calcular aqui solo lo de 2 nivel
                                    $nivel = 2;
                                    $id_nivel3 = null;
                                    $id_nivel4 = null;

                                    $valor_porcentaje = 100;


                                    $cantidad_trabajadores = 0;
                                    $id_trabajadores = [];
                                    $trabajadores_activos = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','status',[1,3]])->all(), 'id', 'id');

                                    if($trabajadores_activos){
                                        $cantidad_trabajadores = count($trabajadores_activos);
                                    }

                                    $id_kpis = [];
                                    $id_kpis = Kpis::find()->where(['id_superior'=>$nivel2->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id])->andWhere(['status'=>1])->all();


                                    if(count($id_kpis)>0){
                                        $valor_porcentaje = 100/(count($id_kpis));//% que vale cada kpi, si hay 4 entonces seria 25% etc

                                        foreach($id_kpis as $key_kpi=>$kpi){
                                            $data_kpi = $kpi->kpi;
                                            $id_programa = $kpi->id_programa;

                                            $ret_kpi = $this->calcularKpiindividual2($data_kpi,$id_programa,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel2,$kpis_mixed,$trabajadores_activos,$cantidad_trabajadores);
                                            $result = $ret_kpi['kpi'];
                                            $kpi_nivel2_[$key2] += $result;
                                        }
                                    }

                                    $qty_workers_nivel2_[$key2] = $cantidad_trabajadores;
                                }


                                $nivel2->kpi_cumplimiento = $kpi_nivel2_[$key2];
                                $nivel2->save(false);


                                $nivel2->qty_trabajadores = $qty_workers_nivel2_[$key2];
                                $nivel2->save(false);
                                $qty_workers_nivel1_[$key] += $qty_workers_nivel2_[$key2];


                                $sumatoria_nivel1 += (($kpi_nivel2_[$key2]*$valor_porcentaje_nivel1)/100);

                            }

                            $kpi_nivel1_[$key] = $sumatoria_nivel1;
                        }

                    } else {
                        //calcular aqui solo lo de 1 nivel
                        $nivel = 1;
                        $id_nivel2 = null;
                        $id_nivel3 = null;
                        $id_nivel4 = null;

                        $valor_porcentaje = 100;


                        $cantidad_trabajadores = 0;
                        $id_trabajadores = [];
                        $trabajadores_activos = ArrayHelper::map(Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','status',[1,3]])->all(), 'id', 'id');

                        if($trabajadores_activos){
                            $cantidad_trabajadores = count($trabajadores_activos);
                        }

                        $id_kpis = [];
                        $id_kpis = Kpis::find()->where(['id_superior'=>$nivel1->id])->andWhere(['nivel'=>$nivel])->andWhere(['id_empresa'=>$model->id])->andWhere(['status'=>1])->all();


                        if(count($id_kpis)>0){
                            foreach($id_kpis as $key_kpi=>$kpi){
                                $data_kpi = $kpi->kpi;
                                $id_programa = $kpi->id_programa;

                                $ret_kpi = $this->calcularKpiindividual2($data_kpi,$id_programa,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel1,$kpis_mixed,$trabajadores_activos,$cantidad_trabajadores);
                                $result = $ret_kpi['kpi'];
                                $kpi_nivel1_[$key] += $result;
                            }
                        }

                        $qty_workers_nivel1_[$key] = $cantidad_trabajadores;
                    }


                    $nivel1->kpi_cumplimiento = $kpi_nivel1_[$key];
                    $nivel1->save(false);

                    $nivel1->qty_trabajadores = $qty_workers_nivel1_[$key];
                    $nivel1->save(false);
                    $qty_workers_empresa += $qty_workers_nivel1_[$key];

                    $sumatoria_empresa += (($kpi_nivel1_[$key]*$valor_porcentaje_empresa)/100);
                }

                $kpi_empresa = $sumatoria_empresa;
            }

            $model->kpi_cumplimiento = $kpi_empresa;
            $model->save(false);
            //dd('cantidad_niveles',$cantidad_niveles,'cantidad_kpis',$cantidad_kpis,'id_kpis',$id_kpis,'model',$model,'nivel_1',$nivel_1);
        }
    }


    private function calcularKPI($model){
        $id_empresa = $model->id;

        $cantidad_niveles = $model->cantidad_niveles;
        $kpis = [
            '1'=>'Trabajadores',
            '2'=>'CAL',
            '3'=>'POE',
            '4'=>'Programas de Salud',
            '5'=>'Accidentes',
            '6'=>'Incapacidades',
            '7'=>'Consultas Clínicas',
            '8'=>'Historias Clínicas',
            '9'=>'Cuestionario Nórdico',
            '10'=>'Evaluacion Antropométrica'
        ];

        $cantidad_kpis = 0;
        $id_kpis = [];
        

        if($cantidad_niveles != null && $cantidad_niveles != '' && $cantidad_niveles != ' '){
            
            for($i = 1; $i < 11; ++$i) {
                if($model['id_kpi'.$i] != null && $model['id_kpi'.$i] != '' && $model['id_kpi'.$i] != ' '){
                    $cantidad_kpis ++;
                    array_push($id_kpis, $model['id_kpi'.$i]);
                }
            }


            $valor_porcentaje = 100;
            if($cantidad_kpis != null && $cantidad_kpis > 0){
                $valor_porcentaje = 100/$cantidad_kpis;
            }
            $kpi_empresa = 0;
            $valor_porcentaje_empresa = 0;

            $nivel_1 = NivelOrganizacional1::find()->where(['id_empresa'=>$model->id])->andWhere(['status'=>1])->orderBy('id_pais')->all();
            if($nivel_1){
                $valor_porcentaje_empresa = 100/count($nivel_1);
                $sumatoria_empresa = 0;

                foreach($nivel_1 as $key=>$nivel1){
                    $kpi_nivel1_[$key] = 0;
                    $id_nivel1 = $nivel1->id;
                    $valor_porcentaje_nivel1 = 0;
                

                    if($model->cantidad_niveles > 1){
                        $nivel_2 = NivelOrganizacional2::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id])->andWhere(['status'=>1])->all();
                        if($nivel_2){
                            $valor_porcentaje_nivel1 = 100/count($nivel_2);
                            $sumatoria_nivel1 = 0;

                            foreach($nivel_2 as $key2=>$nivel2){
                                $kpi_nivel2_[$key2] = 0;
                                $id_nivel2 = $nivel2->id;
                                $valor_porcentaje_nivel2 = 0;


                                if($model->cantidad_niveles > 2){
                                    $nivel_3 = NivelOrganizacional3::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id_pais])->andWhere(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['status'=>1])->all();
                                    if($nivel_3){
                                        $valor_porcentaje_nivel2 = 100/count($nivel_3);
                                        $sumatoria_nivel2 = 0;

                                        foreach($nivel_3 as $key3=>$nivel3){
                                            $kpi_nivel3_[$key3] = 0;
                                            $id_nivel3 = $nivel3->id;


                                            if($model->cantidad_niveles > 3){
                                                $valor_porcentaje_nivel3 = 0;

                                                $nivel_4 = NivelOrganizacional4::find()->where(['id_empresa'=>$model->id])->andWhere(['id_nivelorganizacional1'=>$nivel1->id_pais])->andWhere(['id_nivelorganizacional2'=>$nivel2->id])->andWhere(['id_nivelorganizacional3'=>$nivel3->id])->andWhere(['status'=>1])->all();
                                                if($nivel_4){
                                                    $valor_porcentaje_nivel3 = 100/count($nivel_4);
                                                    $sumatoria_nivel3 = 0;

                                                    foreach($nivel_4 as $key4=>$nivel4){
                                                        $kpi_nivel4_[$key4] = 0;
                                                        $id_nivel4 = $nivel4->id;
                                                        $nivel = 4;


                                                        if(count($id_kpis)>0){
                                                            foreach($id_kpis as $key_kpi=>$id_kpi){
                                                                $ret_kpi = $this->calcularKpiindividual($id_kpi,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel4,$kpis);
                                                                $result = $ret_kpi['kpi'];
                                                                $kpi_nivel4_[$key4] += $result;
                                                            }
                                                        }


                                                        $nivel4->kpi_cumplimiento = $kpi_nivel4_[$key4];
                                                        $nivel4->save(false);

                                                        $sumatoria_nivel3 += (($kpi_nivel4_[$key4]*$valor_porcentaje_nivel3)/100);
                                                    }
                                                    $kpi_nivel3_[$key3] = $sumatoria_nivel3;
                                                    //dd($sumatoria_nivel3);
                                                }
                                            } else {
                                                //calcular aqui solo lo de 3 nivel
                                                $nivel = 3;
                                                $id_nivel4 = null;


                                                if(count($id_kpis)>0){
                                                    foreach($id_kpis as $key_kpi=>$id_kpi){
                                                        $ret_kpi = $this->calcularKpiindividual($id_kpi,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel3,$kpis);
                                                        $result = $ret_kpi['kpi'];
                                                        $kpi_nivel3_[$key3] += $result;
                                                    }
                                                }
                                            }


                                            $nivel3->kpi_cumplimiento = $kpi_nivel3_[$key3];
                                            $nivel3->save(false);

                                            $sumatoria_nivel2 += (($kpi_nivel3_[$key3]*$valor_porcentaje_nivel2)/100);
                                        }

                                        $kpi_nivel2_[$key2] = $sumatoria_nivel2;
                                    }
                                } else {
                                    //calcular aqui solo lo de 2 nivel
                                    $nivel = 2;
                                    $id_nivel3 = null;
                                    $id_nivel4 = null;


                                    if(count($id_kpis)>0){
                                        foreach($id_kpis as $key_kpi=>$id_kpi){
                                            $ret_kpi = $this->calcularKpiindividual($id_kpi,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel2,$kpis);
                                            $result = $ret_kpi['kpi'];
                                            $kpi_nivel2_[$key2] += $result;
                                        }
                                    }
                                }


                                $nivel2->kpi_cumplimiento = $kpi_nivel2_[$key2];
                                $nivel2->save(false);

                                $sumatoria_nivel1 += (($kpi_nivel2_[$key2]*$valor_porcentaje_nivel1)/100);
                            }

                            $kpi_nivel1_[$key] = $sumatoria_nivel1;
                        }

                    } else {
                        //calcular aqui solo lo de 1 nivel
                        $nivel = 1;
                        $id_nivel2 = null;
                        $id_nivel3 = null;
                        $id_nivel4 = null;


                        if(count($id_kpis)>0){
                            foreach($id_kpis as $key_kpi=>$id_kpi){
                                $ret_kpi = $this->calcularKpiindividual($id_kpi,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$nivel1,$kpis);
                                $result = $ret_kpi['kpi'];
                                $kpi_nivel1_[$key] += $result;
                            }
                        }
                    }


                    $nivel1->kpi_cumplimiento = $kpi_nivel1_[$key];
                    $nivel1->save(false);

                    $sumatoria_empresa += (($kpi_nivel1_[$key]*$valor_porcentaje_empresa)/100);
                }

                $kpi_empresa = $sumatoria_empresa;
            }

            $model->kpi_cumplimiento = $kpi_empresa;
            $model->save(false);
            //dd('cantidad_niveles',$cantidad_niveles,'cantidad_kpis',$cantidad_kpis,'id_kpis',$id_kpis,'model',$model,'nivel_1',$nivel_1);
        }
        
    }


    private function calcularKpiindividual($id_kpi,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$model,$kpis){
        $kpi = 0;
        //100% = valor porcentaje
        $total_trabajadores = 0;
        $trabajadores = null;
        $trabajadores_activos = null;

        $cantidad_trabajadores = 0;
        $cantidad_trabajadores_activos = 0;
        $id_trabajadores = [];
        $id_trabajadores_activos = [];

        if($nivel == 1){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['status'=>1])->all();
        } else if($nivel == 2){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['status'=>1])->all();
        } else if($nivel == 3){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['status'=>1])->all();
        } else if($nivel == 4){
            $trabajadores = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->all();
            $trabajadores_activos = Trabajadores::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['status'=>1])->all();
        }

        $total = 0;

        $cantidad_kpi = null;
        $model_kpi = null;


        if($trabajadores){
            $cantidad_trabajadores = count($trabajadores);

            foreach($trabajadores as $key=>$trabajador){
                array_push($id_trabajadores, $trabajador->id);
            }
        }
        if($trabajadores_activos){
            $cantidad_trabajadores_activos = count($trabajadores_activos);

            foreach($trabajadores_activos as $key=>$trabajador){
                array_push($id_trabajadores_activos, $trabajador->id);
            }
        }


        //dd($trabajadores,$trabajadores_activos,$cantidad_trabajadores,$cantidad_trabajadores_activos,$id_trabajadores,$id_trabajadores_activos);
        switch ($id_kpi) {
            case '1':
                //Trabajadores, aqui aun no se que quiere que evalue
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            case '2':
                //CAL, cuantos trabajadores tienen cal
                $total = 0;

                $qty_cals = 0;
                $qty_cals_activas = 0;

                $cals = null;
                $cals_activos = null;

                if($cantidad_trabajadores > 0){
                    if($nivel == 1){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['status'=>2])->all();

                        if(true){
                            $cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>2])->all();
                            $cals_activos = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['status'=>2])->all();

                            $qty_cals = count($cals);
                            $qty_cals_activas = count($cals_activos);

                            $total = ($qty_cals*100)/$cantidad_trabajadores;
                            $total = ($qty_cals_activas*100)/$cantidad_trabajadores_activos;

                            
                            $cantidad_kpi = $qty_cals_activas;
                            $model_kpi = $cals_activos;
                        }
                    
                    } else if($nivel == 2){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['status'=>2])->all();

                        if(true){
                            $cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>2])->all();
                            $cals_activos = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['status'=>2])->all();

                            $qty_cals = count($cals);
                            $qty_cals_activas = count($cals_activos);

                            $total = ($qty_cals*100)/$cantidad_trabajadores;
                            $total = ($qty_cals_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_cals_activas;
                            $model_kpi = $cals_activos;
                        }
                    
                    } else if($nivel == 3){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['status'=>2])->all();

                        if(true){
                            $cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>2])->all();
                            $cals_activos = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['status'=>2])->all();

                            $qty_cals = count($cals);
                            $qty_cals_activas = count($cals_activos);

                            $total = ($qty_cals*100)/$cantidad_trabajadores;
                            $total = ($qty_cals_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_cals_activas;
                            $model_kpi = $cals_activos;
                        }
                    
                    } else if($nivel == 4){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['status'=>2])->all();

                        if(true){
                            $cals = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['status'=>2])->all();
                            $cals_activos = Hccohc::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['status'=>2])->all();

                            $qty_cals = count($cals);
                            $qty_cals_activas = count($cals_activos);

                            $total = ($qty_cals*100)/$cantidad_trabajadores;
                            $total = ($qty_cals_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_cals_activas;
                            $model_kpi = $cals_activos;
                        }
                    
                    }
                }

                $kpi = ($total * $valor_porcentaje)/100;
                //dd('$kpi',$kpi,'total',$total,'$cantidad_trabajadores',$cantidad_trabajadores,'$cantidad_trabajadores_activos',$cantidad_trabajadores_activos,'$total',$total,'$valor_porcentaje',$valor_porcentaje,'$qty_cals',$qty_cals,'$qty_cals_activas',$qty_cals_activas);
                
                break;
            case '3':
                //POE, cuantos trabajadores tienen poe
                $total = 0;

                $qty_poes = 0;
                $qty_poes_activas = 0;

                $poes = null;
                $poes_activos = null;

                if($cantidad_trabajadores > 0){
                    if($nivel == 1){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['<>','status',2])->all();

                        if(true){
                            $poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['<>','status',2])->all();
                            $poes_activos = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['<>','status',2])->all();

                            $qty_poes = count($poes);
                            $qty_poes_activas = count($poes_activos);

                            $total = ($qty_poes*100)/$cantidad_trabajadores;
                            $total = ($qty_poes_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_poes_activas;
                            $model_kpi = $poes_activos;
                        }
                    
                    } else if($nivel == 2){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['<>','status',2])->all();

                        if(true){
                            $poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['<>','status',2])->all();
                            $poes_activos = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['<>','status',2])->all();

                            $qty_poes = count($poes);
                            $qty_poes_activas = count($poes_activos);

                            $total = ($qty_poes*100)/$cantidad_trabajadores;
                            $total = ($qty_poes_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_poes_activas;
                            $model_kpi = $poes_activos;
                        }
                    
                    } else if($nivel == 3){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['<>','status',2])->all();

                        if(true){
                            $poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['<>','status',2])->all();
                            $poes_activos = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['<>','status',2])->all();

                            $qty_poes = count($poes);
                            $qty_poes_activas = count($poes_activos);

                            $total = ($qty_poes*100)/$cantidad_trabajadores;
                            $total = ($qty_poes_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_poes_activas;
                            $model_kpi = $poes_activos;
                        }
                    
                    } else if($nivel == 4){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['<>','status',2])->all();

                        if(true){
                            $poes = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['<>','status',2])->all();
                            $poes_activos = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['<>','status',2])->all();

                            $qty_poes = count($poes);
                            $qty_poes_activas = count($poes_activos);

                            $total = ($qty_poes*100)/$cantidad_trabajadores;
                            $total = ($qty_poes_activas*100)/$cantidad_trabajadores_activos;


                            $cantidad_kpi = $qty_poes_activas;
                            $model_kpi = $poes_activos;
                        }
                    
                    }
                }

                $kpi = ($total * $valor_porcentaje)/100;
                //dd('$kpi',$kpi,'total',$total,'$cantidad_trabajadores',$cantidad_trabajadores,'$cantidad_trabajadores_activos',$cantidad_trabajadores_activos,'$total',$total,'$valor_porcentaje',$valor_porcentaje,'$qty_poes',$qty_poes,'$qty_poes_activas',$qty_poes_activas,'$poes',$poes,'$poes_activos',$poes_activos);
                
                break;
            case '4':
                //Programas de Salud, cuantos trabajadores tienen programas de salud y tienen seguimiento
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            case '5':
                //Accidentes, cuantos trabajadores tienen Accidentes en relacion a su cantidad de trabajadores
                $total = 0;
                $total_contrario = 0;

                $qty_accidentes = 0;
                $qty_accidentes_activas = 0;

                $accidentes = null;
                $accidentes_activos = null;

                if($cantidad_trabajadores > 0){
                    if($nivel == 1){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                        if(true){
                            $accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $accidentes_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_accidentes = count($accidentes);
                            $qty_accidentes_activas = count($accidentes_activos);

                            $total = ($qty_accidentes*100)/$cantidad_trabajadores;
                            $total = ($qty_accidentes_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_accidentes_activas;
                            $model_kpi = $accidentes_activos;
                        }
                    
                    } else if($nivel == 2){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['<>','status',2])->all();

                        if(true){
                            $accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $accidentes_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_accidentes = count($accidentes);
                            $qty_accidentes_activas = count($accidentes_activos);

                            $total = ($qty_accidentes*100)/$cantidad_trabajadores;
                            $total = ($qty_accidentes_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_accidentes_activas;
                            $model_kpi = $accidentes_activos;
                        }
                    
                    } else if($nivel == 3){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['<>','status',2])->all();

                        if(true){
                            $accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $accidentes_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_accidentes = count($accidentes);
                            $qty_accidentes_activas = count($accidentes_activos);

                            $total = ($qty_accidentes*100)/$cantidad_trabajadores;
                            $total = ($qty_accidentes_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_accidentes_activas;
                            $model_kpi = $accidentes_activos;
                        }
                    
                    } else if($nivel == 4){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['<>','status',2])->all();

                        if(true){
                            $accidentes = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $accidentes_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_accidentes = count($accidentes);
                            $qty_accidentes_activas = count($accidentes_activos);

                            $total = ($qty_accidentes*100)/$cantidad_trabajadores;
                            $total = ($qty_accidentes_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_accidentes_activas;
                            $model_kpi = $accidentes_activos;
                        }
                    
                    }
                }

                //$kpi = ($total * $valor_porcentaje)/100;
                $kpi = ($total_contrario * $valor_porcentaje)/100;
                //dd('$kpi',$kpi,'total',$total,'total_contrario',$total_contrario,'$cantidad_trabajadores',$cantidad_trabajadores,'$cantidad_trabajadores_activos',$cantidad_trabajadores_activos,'$total',$total,'$valor_porcentaje',$valor_porcentaje,'$qty_accidentes',$qty_accidentes,'$qty_accidentes_activas',$qty_accidentes_activas,'$accidentes',$accidentes,'$accidentes_activos',$accidentes_activos);
                
                break;
            case '6':
                //Incapacidades, cuantos trabajadores estan incapacitados
                $total = 0;
                $total_contrario = 0;

                $qty_incapacidades = 0;
                $qty_incapacidades_activas = 0;

                $incapacidades = null;
                $incapacidades_activos = null;

                date_default_timezone_set('America/Costa_Rica');
                $hoy = date('Y-m-d');

                if($cantidad_trabajadores > 0){
                    if($nivel == 1){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                        if(true){
                            $incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $incapacidades_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_incapacidades = count($incapacidades);
                            $qty_incapacidades_activas = count($incapacidades_activos);

                            $total = ($qty_incapacidades*100)/$cantidad_trabajadores;
                            $total = ($qty_incapacidades_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_incapacidades_activas;
                            $model_kpi = $incapacidades_activos;
                        }
                    
                    } else if($nivel == 2){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['<>','status',2])->all();

                        if(true){
                            $incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $incapacidades_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_incapacidades = count($incapacidades);
                            $qty_incapacidades_activas = count($incapacidades_activos);

                            $total = ($qty_incapacidades*100)/$cantidad_trabajadores;
                            $total = ($qty_incapacidades_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_incapacidades_activas;
                            $model_kpi = $incapacidades_activos;
                        }
                    
                    } else if($nivel == 3){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['<>','status',2])->all();

                        if(true){
                            $incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $incapacidades_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_incapacidades = count($incapacidades);
                            $qty_incapacidades_activas = count($incapacidades_activos);

                            $total = ($qty_incapacidades*100)/$cantidad_trabajadores;
                            $total = ($qty_incapacidades_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_incapacidades_activas;
                            $model_kpi = $incapacidades_activos;
                        }
                    
                    } else if($nivel == 4){
                        //en la primera no filtramos por trabajadores sino por todo lo que encuentre que coincida con los niveles
                        //$incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['<>','status',2])->all();

                        if(true){
                            $incapacidades = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                            $incapacidades_activos = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$id_trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();

                            $qty_incapacidades = count($incapacidades);
                            $qty_incapacidades_activas = count($incapacidades_activos);

                            $total = ($qty_incapacidades*100)/$cantidad_trabajadores;
                            $total = ($qty_incapacidades_activas*100)/$cantidad_trabajadores_activos;

                            $total_contrario = 100 - $total;


                            $cantidad_kpi = $qty_incapacidades_activas;
                            $model_kpi = $incapacidades_activos;
                        }
                    
                    }
                }

                //$kpi = ($total * $valor_porcentaje)/100;
                $kpi = ($total_contrario * $valor_porcentaje)/100;
                //dd('$kpi',$kpi,'total',$total,'total_contrario',$total_contrario,'$cantidad_trabajadores',$cantidad_trabajadores,'$cantidad_trabajadores_activos',$cantidad_trabajadores_activos,'$total',$total,'$valor_porcentaje',$valor_porcentaje,'$qty_incapacidades',$qty_incapacidades,'$qty_incapacidades_activas',$qty_incapacidades_activas,'$incapacidades',$incapacidades,'$incapacidades_activos',$incapacidades_activos);
                
                break;
            case '7':
                //Consultas Clínicas, cuantos trabajadores tienen Consultas Clínicas(?)
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            case '8':
                //Historias Clínicas, cuantos trabajadores tienen Historias Clínicas(?)
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            case '9':
                //Cuestionario Nórdico, cuantos trabajadores tienen Cuestionario Nórdico(?)
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            case '10':
                //Evaluacion Antropométrica, cuantos trabajadores tienen Evaluacion Antropométrica(?)
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            default:
                # code...
                break;
        }

        if($id_kpi != 1){
            //dd('$kpi',$kpi,'$id_kpi',$id_kpi,'$valor_porcentaje',$valor_porcentaje,'$cantidad_trabajadores_activos',$cantidad_trabajadores_activos,'$total',$total,'$cantidad_kpi',$cantidad_kpi,'$model_kpi',$model_kpi);
        }

        $resultados = [
            'kpi'=>$kpi,
            'id_kpi'=>$id_kpi,
            'valor_porcentaje'=>$valor_porcentaje,
            'cantidad_trabajadores_activos'=>$cantidad_trabajadores_activos,
            'total'=>$total,
            'cantidad_kpi'=>$cantidad_kpi,
            'model_kpi'=>$model_kpi
        ];
        //dd('$id_kpi',$id_kpi,'$valor_porcentaje',$valor_porcentaje,'$id_empresa',$id_empresa,'$id_nivel1',$id_nivel1,'$id_nivel2',$id_nivel2,'$id_nivel3',$id_nivel3,'$id_nivel4',$id_nivel4,'$nivel',$nivel,'$model',$model,'$kpis',$kpis,'$kpi',$kpi);
        return $resultados;
    }


    private function calcularKpiindividual2($data_kpi,$id_programa,$valor_porcentaje,$id_empresa,$id_nivel1,$id_nivel2,$id_nivel3,$id_nivel4,$nivel,$model,$kpis,$trabajadores_activos,$cantidad_trabajadores){
        //dd('$data_kpi',$data_kpi,'$id_programa',$id_programa,'$valor_porcentaje',$valor_porcentaje,'$id_empresa',$id_empresa,'$id_nivel1',$id_nivel1,'$id_nivel2',$id_nivel2,'$id_nivel3',$id_nivel3,'$id_nivel4',$id_nivel4,'$nivel',$nivel,'$model',$model,'$kpis',$kpis,'$trabajadores_activos',$trabajadores_activos,'$cantidad_trabajadores',$cantidad_trabajadores);
        //dd($kpis);
        $kpi = 0;
        $anio_before = date('Y-m-d', strtotime('-1 years'));
        $mes_before = date('Y-m-d', strtotime('-1 month'));
        
        //100% = valor porcentaje
        //KPIS -----------------------------------
        // "A" => "ACCIDENTES"
        // "B" => "NUEVOS INGRESOS"
        // "C" => "INCAPACIDADES"
        // "D" => "PROGRAMAS DE SALUD"
        // "E" => "POES"

        $total = 0;

        $cantidad_kpi = null;
        $model_kpi = null;

        //dd($trabajadores,$trabajadores_activos,$cantidad_trabajadores,$cantidad_trabajadores_activos,$id_trabajadores,$id_trabajadores_activos);
        switch ($data_kpi) {
            case 'A':
                //"ACCIDENTES"
                $total = 0;
                
                if($nivel == 1){
                    $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 2){
                    $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 3){
                    $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 4){
                    $accidentes_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>1])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                }

                $qty_accidentes_activas = count($accidentes_kpi);

                if($cantidad_trabajadores > 0){
                    $total = ($qty_accidentes_activas*100)/$cantidad_trabajadores;
                } else {
                    $total = 100;
                }

                $total_contrario = 100 - $total;


                $cantidad_kpi = $qty_accidentes_activas;
                $model_kpi = $accidentes_kpi;

                $kpi = ($total_contrario * $valor_porcentaje)/100;
                
                break;
            case 'B':
                //"NUEVOS INGRESOS"
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;

                break;
            case 'C':
                //"INCAPACIDADES"
                $total = 0;

                $hoy = date('Y-m-d');
                if($nivel == 1){
                    $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 2){
                    $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 3){
                    $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                } else if($nivel == 4){
                    $incapacidades_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['tipo'=>4])->andWhere(['>=','incapacidad_fechafin',$hoy])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->all();
                }

                $qty_incapacidades_activas = count($incapacidades_kpi);

                if($cantidad_trabajadores > 0){
                    $total = ($qty_incapacidades_activas*100)/$cantidad_trabajadores;
                } else {
                    $total = 100;
                }
                

                $total_contrario = 100 - $total;


                $cantidad_kpi = $qty_incapacidades_activas;
                $model_kpi = $incapacidades_kpi;

                $kpi = ($total_contrario * $valor_porcentaje)/100;
                
                break;
            case 'D':
                //"PROGRAMAS DE SALUD"
                $total = 0;
                $qty_trabajadores_programa = 0;
                $qty_consultas_programa = 0;

                
                $trabajadores_kpi = ArrayHelper::map(ProgramaTrabajador::find()->where(['id_programa'=>$id_programa])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['status'=>1])->select(['id_trabajador'])->distinct()->all(), 'id_trabajador', 'id_trabajador');
                if($trabajadores_kpi){
                    $qty_trabajadores_programa = count($trabajadores_kpi);

                    $consulprog_kpi = null;
                    if($nivel == 1){
                        $consulprog_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$trabajadores_kpi])->andWhere(['tipo'=>7])->andWhere(['>=','fecha',$mes_before])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id_trabajador'])->distinct()->all();
                    } else if($nivel == 2){
                        $consulprog_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$trabajadores_kpi])->andWhere(['tipo'=>7])->andWhere(['>=','fecha',$mes_before])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id_trabajador'])->distinct()->all();
                    } else if($nivel == 3){
                        $consulprog_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$trabajadores_kpi])->andWhere(['tipo'=>7])->andWhere(['>=','fecha',$mes_before])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id_trabajador'])->distinct()->all();
                    } else if($nivel == 4){
                        $consulprog_kpi = Consultas::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$trabajadores_kpi])->andWhere(['tipo'=>7])->andWhere(['>=','fecha',$mes_before])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->select(['id_trabajador'])->distinct()->all();
                    }

                    if($consulprog_kpi){
                        $qty_consultas_programa = count($consulprog_kpi);
                    }

                    $total = ($qty_consultas_programa*100)/$qty_trabajadores_programa;
                } else {
                    $total = 100;
                }
                
                $kpi = ($total * $valor_porcentaje)/100;
            break;

            case 'E':
                //"POES"
                $total = 0;

                if($nivel == 1){
                    $poes_kpi = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                } else if($nivel == 2){
                    $poes_kpi = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                } else if($nivel == 3){
                    $poes_kpi = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                } else if($nivel == 4){
                    $poes_kpi = Poes::find()->where(['id_empresa'=>$id_empresa])->andWhere(['id_nivel1'=>$id_nivel1])->andWhere(['id_nivel2'=>$id_nivel2])->andWhere(['id_nivel3'=>$id_nivel3])->andWhere(['id_nivel4'=>$id_nivel4])->andWhere(['in','id_trabajador',$trabajadores_activos])->andWhere(['>=','create_date',$anio_before])->andWhere(['<>', 'status', 2])->all();
                }

                $qty_poes_activas = count($poes_kpi);

                if($cantidad_trabajadores > 0){
                    $total = ($qty_poes_activas*100)/$cantidad_trabajadores;
                } else {
                    $total = 0;
                }

                $cantidad_kpi = $qty_poes_activas;
                $model_kpi = $poes_kpi;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            
                //Evaluacion Antropométrica, cuantos trabajadores tienen Evaluacion Antropométrica(?)
                $total = 100;

                $kpi = ($total * $valor_porcentaje)/100;
                
                break;
            default:
                # code...
                break;
        }

         $resultados = [
            'kpi'=>$kpi,
        ];

        /* $resultados = [
            'kpi'=>$kpi,
            'id_kpi'=>$data_kpi,
            'valor_porcentaje'=>$valor_porcentaje,
            'cantidad_trabajadores_activos'=>$cantidad_trabajadores_activos,
            'total'=>$total,
            'cantidad_kpi'=>$cantidad_kpi,
            'model_kpi'=>$model_kpi
        ]; */
        //dd('$id_kpi',$id_kpi,'$valor_porcentaje',$valor_porcentaje,'$id_empresa',$id_empresa,'$id_nivel1',$id_nivel1,'$id_nivel2',$id_nivel2,'$id_nivel3',$id_nivel3,'$id_nivel4',$id_nivel4,'$nivel',$nivel,'$model',$model,'$kpis',$kpis,'$kpi',$kpi);
        return $resultados;
    }

    /**
     * Displays a single Empresas model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Empresas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Empresas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Empresas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Empresas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Empresas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Empresas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empresas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionGuardardias(){
        $nivel_dias = '';
        $nivel_fecha = '';
        $nivel_usuario = '';
        $status = 400; //400 = no guardó

        $nivel = Yii::$app->request->post('nivel');
        $id_empresa = Yii::$app->request->post('id_empresa');
        $aux_dias_sin_accidentes = Yii::$app->request->post('aux_dias_sin_accidentes');
        $id_nivel1 = Yii::$app->request->post('id_nivel1');
        $id_nivel2 = Yii::$app->request->post('id_nivel2');
        $id_nivel3 = Yii::$app->request->post('id_nivel3');
        $id_nivel4 = Yii::$app->request->post('id_nivel4');

        $total_accidentes = Yii::$app->request->post('total_accidentes');
        $objetivo_accidentes = Yii::$app->request->post('objetivo_accidentes');
        $comentarios_accidentes = Yii::$app->request->post('comentarios_accidentes');
        $cumplimiento_accidentes = Yii::$app->request->post('cumplimiento_accidentes');

        $model_nivel = null;
        if($nivel == 1){
            $model_nivel = NivelOrganizacional1::findOne($id_nivel1);
        } else if($nivel == 2){
            $model_nivel = NivelOrganizacional2::findOne($id_nivel2);
        } else if($nivel == 3){
            $model_nivel = NivelOrganizacional3::findOne($id_nivel3);
        } else if($nivel == 4){
            $model_nivel = NivelOrganizacional4::findOne($id_nivel4);
        }
        

        if($model_nivel){

            $model_nivel->dias_sin_accidentes = $aux_dias_sin_accidentes;
            $model_nivel->fecha_dias_sin_accidentes = date('Y-m-d H:i:s');
            $model_nivel->actualiza_dias_sin_accidentes = Yii::$app->user->identity->id;

            $model_nivel->accidentes_anio_dias_sin_accidentes = intval($total_accidentes);
            $model_nivel->objetivo_dias_sin_accidentes = intval($objetivo_accidentes);
            $model_nivel->comentario_dias_sin_accidentes = $comentarios_accidentes;
            $model_nivel->cumplimiento_dias_sin_accidentes = floatval($cumplimiento_accidentes);

            //dd($model_nivel);
        
            if($model_nivel->save()){
                $nivel_dias = $aux_dias_sin_accidentes;
                $nivel_fecha = date('Y-m-d', strtotime($model_nivel->fecha_dias_sin_accidentes));
                $nivel_usuario = Yii::$app->user->identity->name;
                $status = 100;


                $historico = new Historicodiassinaccidentes();
                $historico->id_empresa = $id_empresa;
                $historico->id_superior = $model_nivel->id;
                $historico->nivel = $nivel;
                $historico->objetivo_dias_sin_accidentes = $model_nivel->objetivo_dias_sin_accidentes;
                $historico->accidentes_anio_dias_sin_accidentes = $model_nivel->accidentes_anio_dias_sin_accidentes;
                $historico->dias_sin_accidentes = $model_nivel->dias_sin_accidentes;
                $historico->fecha_dias_sin_accidentes = $model_nivel->fecha_dias_sin_accidentes;
                $historico->actualiza_dias_sin_accidentes = $model_nivel->actualiza_dias_sin_accidentes;
                $historico->comentario_dias_sin_accidentes = $model_nivel->comentario_dias_sin_accidentes;
                $historico->record_dias_sin_accidentes = $model_nivel->record_dias_sin_accidentes;
                $historico->cumplimiento_dias_sin_accidentes = $model_nivel->cumplimiento_dias_sin_accidentes;
                $historico->save();
            }

        }
    
        return \yii\helpers\Json::encode(['nivel_dias' => $nivel_dias,'nivel_fecha'=>$nivel_fecha,'nivel_usuario'=>$nivel_usuario,'status'=>$status]);
    }


    public function actionHistoricodocs($src= null){
        $model = new Trabajadores();
        $model->id_empresa = Yii::$app->user->identity->id_empresa;
        $workers = [];


        if($src != null){
            $trabajador = Trabajadores::findOne($src);
            if($trabajador){
                $model->nombre = $trabajador->nombre.' '.$trabajador->apellidos;
                $model->id_empresa = $trabajador->id_empresa;
            }

            $trabajadores = Trabajadores::find();
            $trabajadores->andWhere(['id'=> $src]);

            $show_nivel1 = false;
            $show_nivel2 = false;
            $show_nivel3 = false;
            $show_nivel4 = false;

            $empresas = explode(',', Yii::$app->user->identity->empresas_select);
            if(Yii::$app->user->identity->empresa_all != 1) {
                $trabajadores->andWhere(['in', 'id_empresa', $empresas]);
            } else {
                $empresa_usuario = Empresas::findOne($empresas[0]);

                if($empresa_usuario){
                    if($empresa_usuario->cantidad_niveles >= 1){
                        $show_nivel1 = true;
                    }
                    if($empresa_usuario->cantidad_niveles >= 2){
                        $show_nivel2 = true;
                    }
                    if($empresa_usuario->cantidad_niveles >= 3){
                        $show_nivel3 = true;
                    }
                    if($empresa_usuario->cantidad_niveles >= 4){
                        $show_nivel4 = true;
                    }
                }
            }

            $trabajadores->andWhere(['id_empresa'=>$model->id_empresa]);

            $workers = $trabajadores->limit(2)->all();
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $trabajadores = Trabajadores::find();
                $trabajadores->andWhere(['like', 'Concat(nombre," ", apellidos)', $model->nombre]);

                $show_nivel1 = false;
                $show_nivel2 = false;
                $show_nivel3 = false;
                $show_nivel4 = false;

                $empresas = explode(',', Yii::$app->user->identity->empresas_select);
                if(Yii::$app->user->identity->empresa_all != 1) {
                    $trabajadores->andWhere(['in', 'id_empresa', $empresas]);
                } else {
                    $empresa_usuario = Empresas::findOne($empresas[0]);

                    if($empresa_usuario){
                        if($empresa_usuario->cantidad_niveles >= 1){
                            $show_nivel1 = true;
                        }
                        if($empresa_usuario->cantidad_niveles >= 2){
                            $show_nivel2 = true;
                        }
                        if($empresa_usuario->cantidad_niveles >= 3){
                            $show_nivel3 = true;
                        }
                        if($empresa_usuario->cantidad_niveles >= 4){
                            $show_nivel4 = true;
                        }
                    }
                }

                $trabajadores->andWhere(['id_empresa'=>$model->id_empresa]);

                $workers = $trabajadores->limit(2)->all();

                //dd('GET WORKERS',$model->nombre,$workers);
            }
        }

        return $this->render('historico', [
            'model' => $model,
            'workers'=>$workers
        ]);
    }
}
