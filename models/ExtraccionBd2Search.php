<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExtraccionBd;

/**
 * ExtraccionBd2Search represents the model behind the search form of `app\models\ExtraccionBd`.
 */
class ExtraccionBd2Search extends ExtraccionBd
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_user'], 'integer'],
            [['base_datos', 'tabla', 'create_date'], 'safe'],
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
        $query = ExtraccionBd::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
        ]);

        $query->andFilterWhere(['like', 'base_datos', $this->base_datos])
            ->andFilterWhere(['like', 'tabla', $this->tabla]);

        return $dataProvider;
    }
}
