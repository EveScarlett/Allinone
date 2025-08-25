<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configuracion;
use Yii;

/**
 * ConfiguracionSearch represents the model behind the search form of `app\models\Configuracion`.
 */
class ConfiguracionSearch extends Configuracion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'cantidad_trabajadores', 'cantidad_usuarios', 'cantidad_administradores'], 'integer'],
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
        $query = Configuracion::find();

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

        $dataProvider->sort->attributes['cantidad_usuarios'] = [
            'asc' => ['cantidad_usuarios' => SORT_ASC],
            'desc' => ['cantidad_usuarios' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cantidad_administradores'] = [
            'asc' => ['cantidad_administradores' => SORT_ASC],
            'desc' => ['cantidad_administradores' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cantidad_medicos'] = [
            'asc' => ['cantidad_medicos' => SORT_ASC],
            'desc' => ['cantidad_medicos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cantidad_medicoslaborales'] = [
            'asc' => ['cantidad_medicoslaborales' => SORT_ASC],
            'desc' => ['cantidad_medicoslaborales' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['verseccion_maquina'] = [
            'asc' => ['verseccion_maquina' => SORT_ASC],
            'desc' => ['verseccion_maquina' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['verqr_trabajador'] = [
            'asc' => ['verqr_trabajador' => SORT_ASC],
            'desc' => ['verqr_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['verqr_maquina'] = [
            'asc' => ['verqr_maquina' => SORT_ASC],
            'desc' => ['verqr_maquina' => SORT_DESC]
        ];

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
        }


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
            'cantidad_trabajadores' => $this->cantidad_trabajadores,
            'cantidad_usuarios' => $this->cantidad_usuarios,
            'cantidad_administradores' => $this->cantidad_administradores,
        ]);

        return $dataProvider;
    }
}
