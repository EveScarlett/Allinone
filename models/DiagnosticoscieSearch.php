<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Diagnosticoscie;

/**
 * DiagnosticoscieSearch represents the model behind the search form of `app\models\Diagnosticoscie`.
 */
class DiagnosticoscieSearch extends Diagnosticoscie
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cie_version'], 'integer'],
            [['clave', 'clave_epi', 'diagnostico'], 'safe'],
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
        $query = Diagnosticoscie::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'cie_version' => $this->cie_version,
        ]);

        $query->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'clave_epi', $this->clave_epi])
            ->andFilterWhere(['like', 'diagnostico', $this->diagnostico]);

        return $dataProvider;
    }
}
