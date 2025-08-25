<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cuestionario;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * CuestionarioSearch represents the model behind the search form of `app\models\Cuestionario`.
 */
class CuestionarioSearch extends Cuestionario
{
    public $timeStart;
    public $timeEnd;
    public $tipo;

    public $consentimiento;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sw', 'id_bitacora','id_empresa', 'id_medico', 'es_local', 'status','sexo','edad','filtro1','filtro2','filtro3','tipo'], 'integer'],
            [['nombre_empresa', 'fecha_cuestionario', 'firma_paciente','id_paciente','rango1desde','rango1hasta','rango2desde','rango2hasta','rango3desde','rango3hasta','rango4','rango5desde','rango5hasta','num_trabajador','id_nivel1','id_nivel2','id_nivel3','id_nivel4','consentimiento','uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad'], 'safe'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_cuestionario',
                'dateStartAttribute' => 'timeStart',
                'dateEndAttribute' => 'timeEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
        ];
    } */

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
        $query = Cuestionario::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['nombre_empresa' => SORT_ASC],
            'desc' => ['nombre_empresa' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_paciente'] = [
            'asc' => ['apellidos' => SORT_ASC],
            'desc' => ['apellidos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel1'] = [
            'asc' => ['id_nivel1' => SORT_ASC],
            'desc' => ['id_nivel1' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel2'] = [
            'asc' => ['id_nivel2' => SORT_ASC],
            'desc' => ['id_nivel2' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel3'] = [
            'asc' => ['id_nivel3' => SORT_ASC],
            'desc' => ['id_nivel3' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel4'] = [
            'asc' => ['id_nivel4' => SORT_ASC],
            'desc' => ['id_nivel4' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['num_trabajador'] = [
            'asc' => ['num_trabajador' => SORT_ASC],
            'desc' => ['num_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['edad'] = [
            'asc' => ['edad' => SORT_ASC],
            'desc' => ['edad' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['sexo'] = [
            'asc' => ['sexo' => SORT_ASC],
            'desc' => ['sexo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_tipo_cuestionario'] = [
            'asc' => ['id_tipo_cuestionario' => SORT_ASC],
            'desc' => ['id_tipo_cuestionario' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_cuestionario'] = [
            'asc' => ['fecha_cuestionario' => SORT_ASC],
            'desc' => ['fecha_cuestionario' => SORT_DESC]
        ];

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

        //$query->orderBy(['id'=>SORT_DESC]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_bitacora' => $this->id_bitacora,
            'id_tipo_cuestionario' => $this->id_tipo_cuestionario,
            'id_empresa' => $this->id_empresa,
            'id_medico' => $this->id_medico,
            'es_local' => $this->es_local,
            'status' => $this->status,
        ]);

        if($this->id_paciente){
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['like', 'Concat(nombre," ", apellidos)', $this->id_paciente])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_paciente', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->rango4){
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['sexo'=> $this->rango4])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_paciente', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->sexo){
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['sexo'=> $this->sexo])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_paciente', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->edad){
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['edad'=> $this->edad])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_paciente', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->rango5desde || $this->rango5hasta){
            $rango5desde = '';
            $rango5hasta = '';

            if($this->rango5desde && $this->rango5hasta){
                $rango5desde = $this->rango5desde;
                $rango5hasta = $this->rango5hasta;
            } else if($this->rango5desde && !($this->rango5hasta)){
                $rango5desde = $this->rango5desde;
                $rango5hasta = $this->rango5desde;
            } else if(!($this->rango5desde) && $this->rango5hasta){
                $rango5desde = $this->rango5hasta;
                $rango5hasta = $this->rango5hasta;
            }

            //dd('Estoy buscando por rango de edad');
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['between', 'edad',$rango5desde, $rango5hasta])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_paciente', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        /* if ($this->timeStart) {
            $query->andWhere(['>=', "fecha_cuestionario", $this->timeToUTC($this->timeStart)]);
        }
        if ($this->timeStart) {
            $query->andWhere(['<=', "fecha_cuestionario", $this->timeToUTC($this->timeEnd)]);
        } */

        if($this->fecha_cuestionario){
            
            $fechas = explode(' - ', $this->fecha_cuestionario);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_cuestionario', $fechas[0], $fechas[1]]);
        }


        if($this->filtro1){
            //dd($this->filtro1);
            $ids_cuestionario = [];
            $rango1desde = '';
            $rango1hasta = '';
           
            if($this->rango1desde && $this->rango1hasta){
                $rango1desde = $this->rango1desde;
                $rango1hasta = $this->rango1hasta;
            } else if($this->rango1desde && !($this->rango1hasta)){
                $rango1desde = $this->rango1desde;
                $rango1hasta = $this->rango1desde;
            } else if(!($this->rango1desde) && $this->rango1hasta){
                $rango1desde = $this->rango1hasta;
                $rango1hasta = $this->rango1hasta;
            }
            //dd('Atributo: '.$this->filtro1.' | Desde: '.$rango1desde.' | Hasta: '.$rango1hasta);
           
            $items = DetalleCuestionario::find()->where(['id_area'=> $this->filtro1])->andWhere(['between', 'respuesta_1',$rango1desde, $rango1hasta])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_cuestionario, $ids_cuestionario)){
                    array_push($ids_cuestionario, $item->id_cuestionario);
                }
            }

            if(count($ids_cuestionario)>0){
                $query->andFilterWhere(['in', 'id', $ids_cuestionario]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->filtro2){
            //dd($this->filtro1);
            $ids_cuestionario = [];
            $rango2desde = '';
            $rango2hasta = '';

            if($this->rango2desde && $this->rango2hasta){
                $rango2desde = $this->rango2desde;
                $rango2hasta = $this->rango2hasta;
            } else if($this->rango2desde && !($this->rango2hasta)){
                $rango2desde = $this->rango2desde;
                $rango2hasta = $this->rango2desde;
            } else if(!($this->rango2desde) && $this->rango2hasta){
                $rango2desde = $this->rango2hasta;
                $rango2hasta = $this->rango2hasta;
            }
            //dd('Atributo: '.$this->filtro1.' | Desde: '.$rango1desde.' | Hasta: '.$rango1hasta);
           
            $items = DetalleCuestionario::find()->where(['id_area'=> $this->filtro2])->andWhere(['between', 'respuesta_1',$rango2desde, $rango2hasta])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_cuestionario, $ids_cuestionario)){
                    array_push($ids_cuestionario, $item->id_cuestionario);
                }
            }

            if(count($ids_cuestionario)>0){
                $query->andFilterWhere(['in', 'id', $ids_cuestionario]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }

        if($this->filtro3){
            //dd($this->filtro1);
            $ids_cuestionario = [];
            $rango3desde = '';
            $rango3hasta = '';

            if($this->rango3desde && $this->rango3hasta){
                $rango3desde = $this->rango3desde;
                $rango3hasta = $this->rango3hasta;
            } else if($this->rango3desde && !($this->rango3hasta)){
                $rango3desde = $this->rango3desde;
                $rango3hasta = $this->rango3desde;
            } else if(!($this->rango3desde) && $this->rango3hasta){
                $rango3desde = $this->rango3hasta;
                $rango3hasta = $this->rango3hasta;
            }
            //dd('Atributo: '.$this->filtro1.' | Desde: '.$rango1desde.' | Hasta: '.$rango1hasta);
           
            $items = DetalleCuestionario::find()->where(['id_area'=> $this->filtro3])->andWhere(['between', 'respuesta_1',$rango3desde, $rango3hasta])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_cuestionario, $ids_cuestionario)){
                    array_push($ids_cuestionario, $item->id_cuestionario);
                }
            }

            if(count($ids_cuestionario)>0){
                $query->andFilterWhere(['in', 'id', $ids_cuestionario]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }



        if($this->id_nivel1){
            //dd($this->id_nivel1);
            $ids_nivel1 = [];
               
            $items = NivelOrganizacional1::find()->where(['id_pais'=>$this->id_nivel1])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel1)){
                    array_push($ids_nivel1, $item->id);
                }
            }
    
            if(count($ids_nivel1)>0){
                $query->andFilterWhere(['in', 'id_nivel1', $ids_nivel1]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel2){
            $ids_nivel2 = [];
               
            $items = NivelOrganizacional2::find()->where(['like', 'nivelorganizacional2', $this->id_nivel2])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel2)){
                    array_push($ids_nivel2, $item->id);
                }
            }
    
            if(count($ids_nivel2)>0){
                $query->andFilterWhere(['in', 'id_nivel2', $ids_nivel2]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel3){
            $ids_nivel3 = [];
               
            $items = NivelOrganizacional3::find()->where(['like', 'nivelorganizacional3', $this->id_nivel3])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel3)){
                    array_push($ids_nivel3, $item->id);
                }
            }
    
            if(count($ids_nivel3)>0){
                $query->andFilterWhere(['in', 'id_nivel3', $ids_nivel3]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel4){
            $ids_nivel4 = [];
               
            $items = NivelOrganizacional4::find()->where(['like', 'nivelorganizacional4', $this->id_nivel4])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel4)){
                    array_push($ids_nivel4, $item->id);
                }
            }
    
            if(count($ids_nivel4)>0){
                $query->andFilterWhere(['in', 'id_nivel4', $ids_nivel4]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->consentimiento){
            $ids_consentimiento = [];
            $items = null;
            
            if($this->consentimiento == 1){
                $items = Cuestionario::find()->where(['in','uso_consentimiento',[1,2]])->andWhere(['in','retirar_consentimiento',[1,2]])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }
            } else {
                $items = Consultas::find()->where(['IS', 'uso_consentimiento', new \yii\db\Expression('NULL')])->andWhere(['IS', 'retirar_consentimiento', new \yii\db\Expression('NULL')])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }
            }
            //dd($this->consentimiento,count($items),$items);
            if(count($ids_consentimiento)>0){
                $query->andFilterWhere(['in', 'id', $ids_consentimiento]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }
        }

        $query->andFilterWhere(['like', 'nombre_empresa', $this->nombre_empresa])
            ->andFilterWhere(['like', 'num_trabajador', $this->num_trabajador])
            ->andFilterWhere(['like', 'firma_paciente', $this->firma_paciente]);


        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}
