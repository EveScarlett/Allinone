<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SolicitudesDelete;

/**
 * SolicitudesDeleteSearch represents the model behind the search form of `app\models\SolicitudesDelete`.
 */
class SolicitudesDeleteSearch extends SolicitudesDelete
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_solicitud', 'modelo', 'id_modelo', 'user_solicita', 'user_aprueba'], 'integer'],
            [['date_solicita', 'date_aprueba'], 'safe'],
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
        $query = SolicitudesDelete::find();

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
            'status_solicitud' => $this->status_solicitud,
            'modelo' => $this->modelo,
            'id_modelo' => $this->id_modelo,
            'user_solicita' => $this->user_solicita,
            'date_solicita' => $this->date_solicita,
            'user_aprueba' => $this->user_aprueba,
            'date_aprueba' => $this->date_aprueba,
        ]);

        return $dataProvider;
    }
}
