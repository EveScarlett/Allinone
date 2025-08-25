<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vacantes;
use app\models\Trabajadores;
use app\models\Puestostrabajo;
use Yii;


/**
 * VacantesSearch represents the model behind the search form of `app\models\Vacantes`.
 */
class VacantesSearch extends Vacantes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_puesto', 'cantidad_vacantes', 'remoto', 'status','trabajador','id_empresa'], 'integer'],
            [['titulo', 'descripcion', 'ubicacion', 'pais', 'fecha_limite','id_nivel1','id_nivel2','id_nivel3','id_nivel4','create_date','fecha_limite'], 'safe'],
            [['salario'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Vacantes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_pais'] = [
            'asc' => ['id_pais' => SORT_ASC],
            'desc' => ['id_pais' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_linea'] = [
            'asc' => ['id_linea' => SORT_ASC],
            'desc' => ['id_linea' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_ubicacion'] = [
            'asc' => ['id_ubicacion' => SORT_ASC],
            'desc' => ['id_ubicacion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_puesto'] = [
            'asc' => ['id_puesto' => SORT_ASC],
            'desc' => ['id_puesto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['titulo'] = [
            'asc' => ['titulo' => SORT_ASC],
            'desc' => ['titulo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['ubicacion'] = [
            'asc' => ['ubicacion' => SORT_ASC],
            'desc' => ['ubicacion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cantidad_vacantes'] = [
            'asc' => ['cantidad_vacantes' => SORT_ASC],
            'desc' => ['cantidad_vacantes' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_date'] = [
            'asc' => ['create_date' => SORT_ASC],
            'desc' => ['create_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_limite'] = [
            'asc' => ['fecha_limite' => SORT_ASC],
            'desc' => ['fecha_limite' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];


        //$query->orderBy(['id'=>SORT_DESC]);

        $this->load($params);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
        $show_nivel1 = false;
        $show_nivel2 = false;
        $show_nivel3 = false;
        $show_nivel4 = false;

        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
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



        if($show_nivel1){
            if(Yii::$app->user->identity->nivel1_all != 1) {

                $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
                $paises_nivel = NivelOrganizacional1::find()->where(['in', 'id_empresa', $empresas])->andWhere(['in', 'id_pais', $array_niveles_1])->all();
            
                $niveles_1 = [];
                foreach($paises_nivel as $item){
                    if(!in_array($item->id, $niveles_1)){
                        array_push($niveles_1, $item->id);
                    }
                }
                $query->andFilterWhere(['in', 'id_nivel1', $niveles_1]);
            }
        }
        
        if($show_nivel2){
            $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
            if(Yii::$app->user->identity->nivel2_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel2', $niveles_2]);
            }
        }
        
        if($show_nivel3){
            $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
            if(Yii::$app->user->identity->nivel3_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel3', $niveles_3]);
            } 
        }
        
        if($show_nivel4){
            $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
            if(Yii::$app->user->identity->nivel4_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel4', $niveles_4]);
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_puesto' => $this->id_puesto,
            'cantidad_vacantes' => $this->cantidad_vacantes,
            'salario' => $this->salario,
            'remoto' => $this->remoto,
            'fecha_limite' => $this->fecha_limite,
            'status' => $this->status,
        ]);

        if($this->trabajador){
            $trabajador = Trabajadores::findOne($this->trabajador);
            
            $hay_medidas = false;
            $hay_perfil = false;
            $cuestionario = null;
    
            if($trabajador->antropometrica){//OBTENER MEDIDAS ANTROPOMETRICAS MAS RECIENTES DEL TRABAJADOR
                $hay_medidas = true;
                $cuestionario = $trabajador->antropometrica;
            }
    
            $perfil_worker = [];
            if($trabajador->parametros){//OBTENER PARAMETROS DE PERFIL PSICOLOGICO DEL TRABAJADOR
                $hay_perfil = true;
    
                foreach($trabajador->parametros as $key=>$param){
                    $perfil_worker[$param->id_parametro]= $param->valor;
                }
                
            }
            
            $porcentaje_medidas = 0;//50% del nivel evaluarlo con las medidas antropométricas
            $porcentaje_psicometrico = 0;//50% del nivel evaluarlo con evaluacion psicometrica
    
            $medidas_trabajador = [];
            $psicologico_trabajador = [];
    
            $medidas_porcentaje = [];
            $psicologico_porcentaje = [];
            $puesto_porcentaje = [];
            
            $vacantes = Vacantes::find()->where(['id_empresa'=>$trabajador->id_empresa])->andWhere(['status'=>1])->all();
            $id_puestos = [];
            
            foreach($vacantes as $key=>$vacante){
                if (!in_array($vacante->id_puesto, $id_puestos)) {
                    array_push($id_puestos, $vacante->id_puesto);
                }
            }
            $puestosvacantes = Puestostrabajo::find()->where(['in','id',$id_puestos])->all();

            foreach ($puestosvacantes as $key=>$puesto){
                $medidas_trabajador[$puesto->id] = 0;
                $medidas_porcentaje[$puesto->id] = 0;
                
                if($hay_medidas){
                    //OBTENER RESULTADO DE LAS MEDIDAS ANTROPOMETRICAS---------------------------------------INICIO
                    //dd($puesto);
                    $medidas = [];
                    $medidas[0]= null;
                    $medidas[1]= null;
                    $medidas[2]= null;
        
                    if(isset($puesto->medida1) && $puesto->medida1 != null && $puesto->medida1 != '' && $puesto->medida1 != ' '){
        
                        if(isset($puesto->rango1desde) && $puesto->rango1desde != null && $puesto->rango1desde != '' && $puesto->rango1hasta != ' ' && isset($puesto->rango1hasta) && $puesto->rango1hasta != null && $puesto->rango1hasta != '' && $puesto->rango1hasta != ' '){
                            $desde = $puesto->rango1desde;
                            $hasta = $puesto->rango1hasta;
                            $intermedio = ($puesto->rango1hasta-$puesto->rango1desde)/2;
                            $intermedio = $desde + $intermedio;
                            $valor = null;
        
                            //Obtener la medida del trabajador
                            $c_medida1 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida1])->one();
                            
                            if($c_medida1){
                                $resultado = 0;
                                $valor = $c_medida1->respuesta_1;
        
                                if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                    $resultado = 100;
                                } else{
                                    if($valor > $hasta){
                                        $resultado = ($valor*100)/$intermedio;
                                        $resultado = $resultado - 100;
                                        $resultado = 100 - $resultado;
                                    } else if($valor < $desde){
                                        $resultado = ($valor*100)/$intermedio;
                                    }
                                }
                                $resultado = round($resultado, 2);
                                //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                                $medidas[0]= $resultado;
                            }
                        }
                        
                    }
        
                    if(isset($puesto->medida2) && $puesto->medida2 != null && $puesto->medida2 != '' && $puesto->medida2 != ' '){
        
                        if(isset($puesto->rango2desde) && $puesto->rango2desde != null && $puesto->rango2desde != '' && $puesto->rango2hasta != ' ' && isset($puesto->rango2hasta) && $puesto->rango2hasta != null && $puesto->rango2hasta != '' && $puesto->rango2hasta != ' '){
                            $desde = $puesto->rango2desde;
                            $hasta = $puesto->rango2hasta;
                            $intermedio = ($puesto->rango2hasta-$puesto->rango2desde)/2;
                            $intermedio = $desde + $intermedio;
                            $valor = null;
        
                            //Obtener la medida del trabajador
                            $c_medida2 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida2])->one();
                            //dd($c_medida2);
                            if($c_medida2){
                                $resultado = 0;
                                $valor = $c_medida2->respuesta_1;
        
                                if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                    $resultado = 100;
                                } else{
                                    if($valor > $hasta){
                                        $resultado = ($valor*100)/$intermedio;
                                        $resultado = $resultado - 100;
                                        $resultado = 100 - $resultado;
                                    } else if($valor < $desde){
                                        $resultado = ($valor*100)/$intermedio;
                                    }
                                }
                                $resultado = round($resultado, 2);
                                //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                                $medidas[1]= $resultado;
                            }
                        }
                        
                    }
        
                    if(isset($puesto->medida3) && $puesto->medida3 != null && $puesto->medida3 != '' && $puesto->medida3 != ' '){
        
                        if(isset($puesto->rango3desde) && $puesto->rango3desde != null && $puesto->rango3desde != '' && $puesto->rango3hasta != ' ' && isset($puesto->rango3hasta) && $puesto->rango3hasta != null && $puesto->rango3hasta != '' && $puesto->rango3hasta != ' '){
                            $desde = $puesto->rango3desde;
                            $hasta = $puesto->rango3hasta;
                            $intermedio = ($puesto->rango3hasta-$puesto->rango3desde)/2;
                            $intermedio = $desde + $intermedio;
                            $valor = null;
        
                            //Obtener la medida del trabajador
                            $c_medida3 = DetalleCuestionario::find()->where(['id_cuestionario'=>$cuestionario->id])->andWhere(['id_area'=>$puesto->medida3])->one();
                            //dd($c_medida2);
                            if($c_medida3){
                                $resultado = 0;
                                $valor = $c_medida3->respuesta_1;
        
                                if($valor>=$desde &&$valor<=$hasta){//Si esta dentro del rango le ponemos un 100%
                                    $resultado = 100;
                                } else{
                                    if($valor > $hasta){
                                        $resultado = ($valor*100)/$intermedio;
                                        $resultado = $resultado - 100;
                                        $resultado = 100 - $resultado;
                                    } else if($valor < $desde){
                                        $resultado = ($valor*100)/$intermedio;
                                    }
                                }
        
                                $resultado = round($resultado, 2);
                                //dd('Desde: '.$desde.' | Hasta: '.$hasta.' | Valor Trabajador: '.$valor.' | Coincide un '.$resultado.'% |  Intermedio: '.$intermedio);
                                $medidas[2]= $resultado;
                            }
                        }
                        
                    }
        
                    $medidas_trabajador[$puesto->id] = $medidas;
                    $percenfinal = 0;
        
                    foreach($medidas as $key=>$medida){
                        $porcentajeindividual = ($medida * 33.33)/100;
                        $percenfinal += $porcentajeindividual;
                    }
        
                    $percenfinal = round($percenfinal, 2);
                    $medidas_porcentaje[$puesto->id] = $percenfinal;
                    //OBTENER RESULTADO DE LAS MEDIDAS ANTROPOMETRICAS---------------------------------------FIN
                
                }
    
                $psicologico_trabajador[$puesto->id] = 0;
                $psicologico_porcentaje[$puesto->id] = 0;
                if($hay_perfil){
                    //OBTENER RESULTADO DEL PERFIL PSICOLÓGICO---------------------------------------INICIO
                    $valor_maximo = 10;
                    $sumatoria = 0;
                    $parametros = [];
    
                    
                    foreach($puesto->parametros as $key=>$parametro){
                        $sumatoria += $parametro->valor;
                    }
                    
                    if($puesto->parametros){
                        $parametromax = Puestoparametro::find()->where(['id_puesto'=>$puesto->id])->orderBy(['valor'=>SORT_DESC])->one();
                        if($parametromax){
                            $valor_maximo = $parametromax->valor;
                        }
                        foreach($puesto->parametros as $key=>$parametro){
                            $parametros[$key] = 0;
                            $resultado = 0;
                            $valor = 0;
                            $parametrovalor = $parametro->valor;
                            $porcentaje_parametro = ($parametrovalor*100)/$sumatoria;
                            $porcentaje_parametro = round($porcentaje_parametro, 2);
    
                            
                            $paramworker = Trabajadorparametro::find()->where(['id_trabajador'=>$trabajador->id])->andWhere(['id_parametro'=>$parametro->id_parametro])->one();
                            
                            if ($paramworker) {
                                $valor = $paramworker->valor;
                            }
    
                            if($valor>$parametrovalor){
                                $resultado = $porcentaje_parametro;
                            } else{
                                $resultado = ($valor*$porcentaje_parametro)/$parametrovalor;
                                $resultado = round($resultado, 2);
                            }
    
                            $parametros[$key]= $resultado;
                            //dd('Si existe el parametro: '.$parametro->id_parametro.'-'.$parametro->parametro->nombre.' | Valor Puesto: '.$parametrovalor.' | Valor Trabajador: '.$valor.' | Porcentaje Parámetro: '.$porcentaje_parametro);
                        }
                    }
    
                    $psicologico_trabajador[$puesto->id] = $parametros;
                    $percenfinal = 0;
        
                    foreach($parametros as $key=>$parametro){
                        $percenfinal += $parametro;
                    }
        
                    $percenfinal = round($percenfinal, 2);
                    $psicologico_porcentaje[$puesto->id] = $percenfinal;
                     //OBTENER RESULTADO DEL PERFIL PSICOLÓGICO---------------------------------------FIN
                    
                }
            }
            foreach($medidas_porcentaje as $key=>$porcentaje){
                $medidaantropo = $porcentaje;
                $perfilpsico = $psicologico_porcentaje[$key];
                $resultadofinal = ($medidaantropo+$perfilpsico)/2;
                $resultadofinal = round($resultadofinal, 2);
                $puesto_porcentaje[$key] = $resultadofinal;
    
                //dd('PUESTO: '.$key.' | MEDIDA: '.$medidaantropo.'%'.' | PSICOLOGICO: '.$perfilpsico.'% | RESULTADO: '.$resultadofinal);
            }
            arsort($puesto_porcentaje);
            $id_vacantes = [];

            //dd($puesto_porcentaje);
            foreach($puesto_porcentaje as $key=>$puesto){
                $vacantepuesto = Vacantes::find()->where(['id_puesto'=>$key])->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->one();
                
                if($vacantepuesto){
                    if (!in_array($vacantepuesto->id, $id_vacantes)) {
                        array_push($id_vacantes, $vacantepuesto->id);
                    }
                }
                
            }

            $query->andFilterWhere(['in', 'id', $id_vacantes]);
            //dd($id_vacantes);
            $query->orderBy([new \yii\db\Expression('FIELD(id, '. implode(',', $id_vacantes) . ')')]);

            //dd($id_vacantes);
        }

        if($this->create_date){
            $fechas = explode(' - ', $this->create_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'create_date', $fechas[0], $fechas[1]]);
        }

        if($this->fecha_limite){
            $fechas = explode(' - ', $this->fecha_limite);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_limite', $fechas[0], $fechas[1]]);
        }

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'pais', $this->pais]);

        return $dataProvider;
    }
}