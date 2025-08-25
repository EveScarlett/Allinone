<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insumostockmin;
use Yii;

/**
 * InsumostockminSearch represents the model behind the search form of `app\models\Insumostockmin`.
 */
class InsumostockminSearch extends Insumostockmin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_insumo', 'id_consultorio', 'stock', 'stock_unidad','id_empresa','tipo_insumo'], 'integer'],
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
        $query = Insumostockmin::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        //$query->orderBy(['id'=>SORT_DESC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

      /*   $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['id_consultorio'] = [
            'asc' => ['id_consultorio' => SORT_ASC],
            'desc' => ['id_consultorio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_insumo'] = [
            'asc' => ['id_insumo' => SORT_ASC],
            'desc' => ['id_insumo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['stock'] = [
            'asc' => ['stock' => SORT_ASC],
            'desc' => ['stock' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['stock_unidad'] = [
            'asc' => ['stock_unidad' => SORT_ASC],
            'desc' => ['stock_unidad' => SORT_DESC]
        ];


        $this->load($params);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
        if(Yii::$app->user->identity->empresa_all != 1) {
            $id_consultorios = [];
           
            $items = Consultorios::find()->where(['in', 'id_empresa', $empresas])->all();
            foreach($items as $item){
                if(!in_array($item->id, $id_consultorios)){
                    array_push($id_consultorios, $item->id);
                }
            }

            if(count($id_consultorios)>0){
                $query->andFilterWhere(['in', 'id_consultorio', $id_consultorios]);
            }else{
                $query->andFilterWhere(['id_consultorio'=>'0']);
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
            'id_consultorio' => $this->id_consultorio,
            'tipo_insumo' => $this->tipo_insumo,
            'stock' => $this->stock,
            'stock_unidad' => $this->stock_unidad,
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

        

        if($this->id_empresa){
            $id_consultorios = [];
           
            $items = Consultorios::find()->where(['id_empresa'=> $this->id_empresa])->all();
            foreach($items as $item){
                if(!in_array($item->id, $id_consultorios)){
                    array_push($id_consultorios, $item->id);
                }
            }

            if(count($id_consultorios)>0){
                $query->andFilterWhere(['in', 'id_consultorio', $id_consultorios]);
            }else{
                $query->andFilterWhere(['id_consultorio'=>'0']);
            }  
        }

        return $dataProvider;
    }
}
