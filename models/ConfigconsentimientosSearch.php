<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configconsentimientos;
use Yii;
/**
 * ConfigconsentimientosSearch represents the model behind the search form of `app\models\Configconsentimientos`.
 */
class ConfigconsentimientosSearch extends Configconsentimientos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_tipoconsentimiento', 'aviso_privacidad', 'status'], 'integer'],
            [['texto_consentimiento', 'texto_aviso','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = Configconsentimientos::find();

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

        $dataProvider->sort->attributes['id_tipoconsentimiento'] = [
            'asc' => ['id_tipoconsentimiento' => SORT_ASC],
            'desc' => ['id_tipoconsentimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['texto_consentimiento'] = [
            'asc' => ['texto_consentimiento' => SORT_ASC],
            'desc' => ['texto_consentimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];



        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->nivel1_all != 1) {
            $array_niveles_1 = explode(',', Yii::$app->user->identity->nivel1_select);
            $paises_nivel = NivelOrganizacional1::find()->where(['in', 'id_empresa', $empresas])->andWhere(['in', 'id_pais', $array_niveles_1])->all();
            
            $niveles_1 = [];
            foreach($paises_nivel as $item){
                if(!in_array($item->id, $niveles_1)){
                    array_push($niveles_1, $item->id);
                }
            }
            $query->andFilterWhere(['in', 'id_nivel1', $niveles_1]);
        }
        $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
        if(Yii::$app->user->identity->nivel2_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel2', $niveles_2]);
        }
        $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
        if(Yii::$app->user->identity->nivel3_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel3', $niveles_3]);
        }
        $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
        if(Yii::$app->user->identity->nivel4_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel4', $niveles_4]);
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
            'id_tipoconsentimiento' => $this->id_tipoconsentimiento,
            'aviso_privacidad' => $this->aviso_privacidad,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'texto_consentimiento', $this->texto_consentimiento])
            ->andFilterWhere(['like', 'texto_aviso', $this->texto_aviso]);

        return $dataProvider;
    }
}
