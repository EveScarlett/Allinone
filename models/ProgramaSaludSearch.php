<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProgramaSalud;

/**
 * ProgramaSaludSearch represents the model behind the search form of `app\models\ProgramaSalud`.
 */
class ProgramaSaludSearch extends ProgramaSalud
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'vigencia', 'status', 'nodelete', 'soft_delete'], 'integer'],
            [['nombre', 'descripcion', 'color'], 'safe'],
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
        $query = ProgramaSalud::find();

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

        $dataProvider->sort->attributes['nombre'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['descripcion'] = [
            'asc' => ['descripcion' => SORT_ASC],
            'desc' => ['descripcion' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['empresa_all'] = [
            'asc' => ['empresa_all' => SORT_ASC],
            'desc' => ['empresa_all' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'vigencia' => $this->vigencia,
            'status' => $this->status,
            'nodelete' => $this->nodelete,
            'soft_delete' => $this->soft_delete,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'color', $this->color]);

        return $dataProvider;
    }
}
