<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Turnos;

/**
 * TurnosSearch represents the model behind the search form of `app\models\Turnos`.
 */
class TurnosSearch extends Turnos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'lunes_inicio', 'lunes_fin', 'martes_inicio', 'martes_fin', 'miercoles_inicio', 'miercoles_fin', 'jueves_inicio', 'jueves_fin', 'viernes_inicio', 'viernes_fin', 'sabado_inicio', 'sabado_fin', 'domingo_inicio', 'domingo_fin', 'status'], 'integer'],
            [['turno'], 'safe'],
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
        $query = Turnos::find();

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
            'id_empresa' => $this->id_empresa,
            'lunes_inicio' => $this->lunes_inicio,
            'lunes_fin' => $this->lunes_fin,
            'martes_inicio' => $this->martes_inicio,
            'martes_fin' => $this->martes_fin,
            'miercoles_inicio' => $this->miercoles_inicio,
            'miercoles_fin' => $this->miercoles_fin,
            'jueves_inicio' => $this->jueves_inicio,
            'jueves_fin' => $this->jueves_fin,
            'viernes_inicio' => $this->viernes_inicio,
            'viernes_fin' => $this->viernes_fin,
            'sabado_inicio' => $this->sabado_inicio,
            'sabado_fin' => $this->sabado_fin,
            'domingo_inicio' => $this->domingo_inicio,
            'domingo_fin' => $this->domingo_fin,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'turno', $this->turno]);

        return $dataProvider;
    }
}
