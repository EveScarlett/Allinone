<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ni;

/**
 * NiSearch represents the model behind the search form of `app\models\Ni`.
 */
class NiSearch extends Ni
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'status'], 'integer'],
            [['ni', 'descripcion'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Ni::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'id_nivel1' => $this->id_nivel1,
            'id_nivel2' => $this->id_nivel2,
            'id_nivel3' => $this->id_nivel3,
            'id_nivel4' => $this->id_nivel4,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ni', $this->ni])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
