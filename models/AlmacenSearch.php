<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Almacen;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * AlmacenSearch represents the model behind the search form of `app\models\Almacen`.
 */
class AlmacenSearch extends Almacen
{
    public $timeStart;
    public $timeEnd;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_consultorio', 'stock', 'stock_unidad', 'update_user','tipo_insumo'], 'integer'],
            [['update_date','id_insumo','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'update_date',
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
        $query = Almacen::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['fecha_caducidad' => SORT_ASC,'id'=>SORT_DESC]]
        ]);

        //$query->orderBy(['fecha_caducidad'=>SORT_ASC,'id'=>SORT_DESC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
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

        $dataProvider->sort->attributes['id_consultorio'] = [
            'asc' => ['id_consultorio' => SORT_ASC],
            'desc' => ['id_consultorio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_insumo'] = [
            'asc' => ['id_insumo' => SORT_ASC],
            'desc' => ['id_insumo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_caducidad'] = [
            'asc' => ['fecha_caducidad' => SORT_ASC],
            'desc' => ['fecha_caducidad' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['stock_minimo'] = [
            'asc' => ['stock_minimo' => SORT_ASC],
            'desc' => ['stock_minimo' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['stock'] = [
            'asc' => ['stock' => SORT_ASC],
            'desc' => ['stock' => SORT_DESC]
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
        

        
        $this->load($params);

        
        $query->andFilterWhere(['>', 'stock_unidad', 0]);
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'id_consultorio' => $this->id_consultorio,
            'tipo_insumo' => $this->tipo_insumo,
            'stock' => $this->stock,
            'stock_unidad' => $this->stock_unidad,
            'update_user' => $this->update_user,
        ]);

        
        if($this->id_insumo){
            $ids_insumos = [];
           
            $items = Insumos::find()->where(['or',
            ['like', 'nombre_comercial', $this->id_insumo],
            ['like', 'nombre_generico', $this->id_insumo]])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_insumos)){
                    array_push($ids_insumos, $item->id);
                }
            }

            if(count($ids_insumos)>0){
                $query->andFilterWhere(['in', 'id_insumo', $ids_insumos]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        /* if ($this->timeStart) {
            $query->andWhere(['>=', "update_date", $this->timeToUTC($this->timeStart)]);
        }
        if ($this->timeStart) {
            $query->andWhere(['<=', "update_date", $this->timeToUTC($this->timeEnd)]);
        } */


        if($this->update_date){
            $fechas = explode(' - ', $this->update_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'update_date', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    
}
}