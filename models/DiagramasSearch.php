<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empresas;
use Yii;
/**
 * DiagramasSearch represents the model behind the search form of `app\models\Empresas`.
 */
class DiagramasSearch extends Empresas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lunes_inicio', 'lunes_fin', 'martes_inicio', 'martes_fin', 'miercoles_inicio', 'miercoles_fin', 'jueves_inicio', 'jueves_fin', 'viernes_inicio', 'viernes_fin', 'sabado_inicio', 'sabado_fin', 'domingo_inicio', 'domingo_fin', 'create_user', 'update_user', 'delete_user', 'medico_laboral', 'status', 'soft_delete'], 'integer'],
            [['razon', 'comercial', 'abreviacion', 'rfc', 'pais', 'estado', 'ciudad', 'municipio', 'logo', 'contacto', 'telefono', 'correo', 'horario', 'create_date', 'update_date', 'delete_date'], 'safe'],
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
        $query = Empresas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy(['id'=>SORT_DESC]);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id', $empresas]);
        }

        //$query->andFilterWhere(['id'=> Yii::$app->user->identity->id_empresa]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
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
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
            'delete_date' => $this->delete_date,
            'delete_user' => $this->delete_user,
            'medico_laboral' => $this->medico_laboral,
            'status' => $this->status,
            'soft_delete' => $this->soft_delete,
        ]);

        $query->andFilterWhere(['like', 'razon', $this->razon])
            ->andFilterWhere(['like', 'comercial', $this->comercial])
            ->andFilterWhere(['like', 'abreviacion', $this->abreviacion])
            ->andFilterWhere(['like', 'rfc', $this->rfc])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'municipio', $this->municipio])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'contacto', $this->contacto])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'correo', $this->correo])
            ->andFilterWhere(['like', 'horario', $this->horario]);

        return $dataProvider;
    }
}
