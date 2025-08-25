<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Poes;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * PoesSearch represents the model behind the search form of `app\models\Poes`.
 */
class PoesSearch extends Poes
{
    public $condicion;
    public $timeStart;
    public $timeEnd;
    public $estudios;
    public $diagnosticos;
    public $evolucion;

    public $categoria;
    public $entrega;
    public $documento;

    public $poes_anteriores;

    public $consentimiento;
    public $id_worker;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'sexo', 'anio', 'id_ubicacion', 'create_user', 'update_user', 'delete_user', 'status','condicion','diagnosticos','evolucion','status_entrega','categoria','entrega','documento','poes_anteriores','consentimiento','uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','tipo_poe','tiene_consentimiento','id_worker'], 'integer'],
            [['nombre', 'apellidos', 'num_trabajador', 'id_trabajador', 'id_puesto','id_area', 'fecha_nacimiento', 'num_imss', 'observaciones', 'create_date', 'update_date', 'delete_date','estudios','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'create_date',
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
        $query = Poes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['tipo_poe'] = [
            'asc' => ['tipo_poe' => SORT_ASC],
            'desc' => ['tipo_poe' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['nombre_empresa' => SORT_ASC],
            'desc' => ['nombre_empresa' => SORT_DESC]
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

        $dataProvider->sort->attributes['id_trabajador'] = [
            'asc' => ['apellidos' => SORT_ASC],
            'desc' => ['apellidos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['sexo'] = [
            'asc' => ['sexo' => SORT_ASC],
            'desc' => ['sexo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_area'] = [
            'asc' => ['id_area' => SORT_ASC],
            'desc' => ['id_area' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_puesto'] = [
            'asc' => ['id_puesto' => SORT_ASC],
            'desc' => ['id_puesto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['anio'] = [
            'asc' => ['anio' => SORT_ASC],
            'desc' => ['anio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_date'] = [
            'asc' => ['create_date' => SORT_ASC],
            'desc' => ['create_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status_entrega'] = [
            'asc' => ['status_entrega' => SORT_ASC],
            'desc' => ['status_entrega' => SORT_DESC]
        ];

        

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
        

        $query->andWhere(['<>','status',2]);

        //$query->orderBy(['id'=>SORT_DESC]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipo_poe' => $this->tipo_poe,
            'id_empresa' => $this->id_empresa,
            'sexo' => $this->sexo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'anio' => $this->anio,
            'id_ubicacion' => $this->id_ubicacion,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
            'delete_date' => $this->delete_date,
            'delete_user' => $this->delete_user,
            'status' => $this->status,
            'tiene_consentimiento' => $this->tiene_consentimiento,
            'id_trabajador' => $this->id_worker,
        ]);

        //$query->andFilterWhere(['like', 'Concat(nombre," ", apellidos)', $this->id_trabajador]);

        $query->andFilterWhere([ 'OR' ,
              ['like', 'Concat(nombre," ", apellidos)', $this->id_trabajador],
              ['like', 'num_trabajador', $this->id_trabajador],
        ]);

        if($this->condicion){
            $ids_trabajadores = [];

            if($this->condicion == 5){
                $items = Trabajadores::find()->where(['status'=>5])->all();
            }else{
                $items = Trabajadores::find()->where(['status'=>$this->condicion])->all();
            }
            
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_trabajador', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }


        if($this->poes_anteriores){
            //dd($this->poes_anteriores);
            $ids_trabajadores = [];

            $items = Poes::find()->where(['<>','status','2'])->orderBy(['id_trabajador'=>SORT_DESC])->select('id_trabajador')->distinct()->all();
            //dd($items);

            foreach($items as $item){
                $poesanteriores = Poes::find()->where(['id_trabajador'=>$item->id_trabajador])->andWhere(['<>','status',2])->orderBy(['id'=>SORT_DESC])->all();

                if($this->poes_anteriores == 1){//CON ANTERIORES
                    
                } else{ //SIN ANTERIORES
                    
                }
            }
            /* 
            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }   */
        }


        if($this->id_puesto){
            $ids_puestos = [];

            $items = Puestostrabajo::find()->where(['like','nombre',$this->id_puesto])->all();
            
            foreach($items as $item){
                if(!in_array($item->id, $ids_puestos)){
                    array_push($ids_puestos, $item->id);
                }
            }

            if(count($ids_puestos)>0){
                $query->andFilterWhere(['in', 'id_puesto', $ids_puestos]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }


        if($this->id_area){
            $ids_areas = [];

            $items = Areas::find()->where(['like','area',$this->id_area])->all();
            
            foreach($items as $item){
                if(!in_array($item->id, $ids_areas)){
                    array_push($ids_areas, $item->id);
                }
            }

            if(count($ids_areas)>0){
                $query->andFilterWhere(['in', 'id_area', $ids_areas]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }


        if($this->consentimiento){
            $ids_consentimiento = [];
            $items = null;
            
            if($this->consentimiento == 1){
                $items = Poes::find()->where(['in','uso_consentimiento',[1,2]])->andWhere(['in','retirar_consentimiento',[1,2]])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }

                if(count($ids_consentimiento)>0){
                    $query->andFilterWhere(['in', 'id', $ids_consentimiento]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }
            } else {
                $items = Poes::find()->where(['in','uso_consentimiento',[1,2]])->andWhere(['in','retirar_consentimiento',[1,2]])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }

                if(count($ids_consentimiento)>0){
                    $query->andFilterWhere(['not in', 'id', $ids_consentimiento]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }
            }
            
        }

        if($this->create_date){
            
            $fechas = explode(' - ', $this->create_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'create_date', $fechas[0], $fechas[1]]);
        }

        /* if ($this->timeStart) {
            $query->andWhere(['>=', "create_date", $this->timeToUTC($this->timeStart)]);
        }
        if ($this->timeStart) {
            $query->andWhere(['<=', "create_date", $this->timeToUTC($this->timeEnd)]);
        } */


        $arrstatus_entrega = [];
        if($this->estudios){
            $id_estudios = $this->estudios;

            if (in_array("A", $this->estudios, true) || in_array("B", $this->estudios, true)) {
                if (in_array("A", $this->estudios, true)) {
                    array_push($arrstatus_entrega, 1);
                    $keydelete = array_search("A", $id_estudios);
                    if (false !== $keydelete) {
                        unset($id_estudios[$keydelete]);
                    }
                }
                if (in_array("B", $this->estudios, true)) {
                    array_push($arrstatus_entrega, 0);
                    $keydelete = array_search("B", $id_estudios);
                    if (false !== $keydelete) {
                        unset($id_estudios[$keydelete]);
                    }
                }
            }
            if(count($id_estudios)){
                $ids_poes = [];
           
                $items = Poeestudio::find()->where(['in','id_estudio',$id_estudios])->all();
                foreach($items as $item){
                    if(!in_array($item->id_poe, $ids_poes)){
                        array_push($ids_poes, $item->id_poe);
                    }
                }
    
                if(count($ids_poes)>0){
                    $query->andFilterWhere(['in', 'id', $ids_poes]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                } 
            }
             
        }
        

        //$query->andFilterWhere(['in','status_entrega',$arrstatus_entrega]);


        if(isset($this->diagnosticos) && $this->diagnosticos != ''){
            $ids_poes = [];

            $items = Poeestudio::find()->where(['condicion'=>$this->diagnosticos])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_poe, $ids_poes)){
                    array_push($ids_poes, $item->id_poe);
                }
            }

            if(count($ids_poes)>0){
                $query->andFilterWhere(['in', 'id', $ids_poes]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if(isset($this->evolucion) && $this->evolucion != ''){
            $ids_poes = [];

            $items = Poeestudio::find()->where(['evolucion'=>$this->evolucion])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_poe, $ids_poes)){
                    array_push($ids_poes, $item->id_poe);
                }
            }
            if(count($ids_poes)>0){
                $query->andFilterWhere(['in', 'id', $ids_poes]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if(isset($this->categoria) && $this->categoria != ''){
            $ids_poes = [];

            $items = Poeestudio::find()->where(['id_tipo'=>$this->categoria])->all();
            
            foreach($items as $item){
                if(!in_array($item->id_poe, $ids_poes)){
                    array_push($ids_poes, $item->id_poe);
                }
            }
            if(count($ids_poes)>0){
                $query->andFilterWhere(['in', 'id', $ids_poes]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if(isset($this->entrega) && $this->entrega != ''){
            //dd();
            $query->andFilterWhere(['status_entrega'=>$this->entrega]);
        }

        if(isset($this->documento) && $this->documento != ''){
            $ids_poes = [];
            $items = [];

            if($this->documento == 1){
                $items = Poeestudio::find()->where([ 'OR' ,
                ['IS NOT', 'evidencia', null],
                ['IS NOT', 'evidencia2', null],
                ['IS NOT', 'evidencia3', null],
                ] )->all();
            } else {
                $items = Poeestudio::find()->where([ 'AND' ,
                ['IS', 'evidencia', null],
                ['IS', 'evidencia2', null],
                ['IS', 'evidencia3', null],
                ] )->all();
            }
            
            foreach($items as $item){
                if(!in_array($item->id_poe, $ids_poes)){
                    array_push($ids_poes, $item->id_poe);
                }
            }

            if(count($ids_poes)>0){
                $query->andFilterWhere(['in', 'id', $ids_poes]);
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


        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'num_imss', $this->num_imss])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}