<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoServicios;
use Yii;
/**
 * TipoServiciosSearch represents the model behind the search form of `app\models\TipoServicios`.
 */
class TipoServiciosSearch extends TipoServicios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'logo','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = TipoServicios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status'=>SORT_ASC,'orden'=>SORT_ASC]]
        ]);

        // $query->orderBy(['status'=>SORT_ASC,'orden'=>SORT_ASC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nombre'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        $this->load($params);


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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
