<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ordenespoes;
use kartik\daterange\DateRangeBehavior;

/**
 * OrdenespoesSearch represents the model behind the search form of `app\models\Ordenespoes`.
 */
class OrdenespoesSearch extends Ordenespoes
{
    public $timeStart;
    public $timeEnd;
    public $estudios;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'anio'], 'integer'],
            [['create_date', 'update_date', 'create_user', 'update_user','estudios'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ordenespoes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        //$query->orderBy(['id'=>SORT_DESC]);

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

        $dataProvider->sort->attributes['anio'] = [
            'asc' => ['anio' => SORT_ASC],
            'desc' => ['anio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_date'] = [
            'asc' => ['create_date' => SORT_ASC],
            'desc' => ['create_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_user'] = [
            'asc' => ['create_user' => SORT_ASC],
            'desc' => ['create_user' => SORT_DESC]
        ];

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'anio' => $this->anio,
        ]);

        if($this->create_user){
            $usuarios = Usuarios::find()->where(['like','name',$this->create_user])->all();
            $ids_usuarios = [];
            foreach($usuarios as $usuario){
                array_push($ids_usuarios, $usuario->id);
            }

            if(count($ids_usuarios)>0){
                $query->andFilterWhere(['in', 'create_user', $ids_usuarios]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->estudios){
            //dd($this->estudios);
            $ids_estudios = [];
           
            $items = Detalleordenpoe::find()->where(['in','id_estudio',$this->estudios])->all();
            foreach($items as $item){
                if(!in_array($item->id_ordenpoe, $ids_estudios)){
                    array_push($ids_estudios, $item->id_ordenpoe);
                }
            }

            if(count($ids_estudios)>0){
                $query->andFilterWhere(['in', 'id', $ids_estudios]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }


        /* if ($this->timeStart) {
            $query->andWhere(['>=', "create_date", $this->timeToUTC($this->timeStart)]);
        }
        if ($this->timeStart) {
            $query->andWhere(['<=', "create_date", $this->timeToUTC($this->timeEnd)]);
        } */

        if($this->create_date){
            $fechas = explode(' - ', $this->create_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'create_date', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }

    
    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}