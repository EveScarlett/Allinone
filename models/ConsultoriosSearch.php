<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Consultorios;
use Yii;
/**
 * ConsultoriosSearch represents the model behind the search form of `app\models\Consultorios`.
 */
class ConsultoriosSearch extends Consultorios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa','id_pais', 'status', 'soft_delete'], 'integer'],
            [['consultorio','id_linea'], 'safe'],
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
        $query = Consultorios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy(['id'=>SORT_DESC]);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
        }

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
            'id_pais' => $this->id_pais,
            'status' => $this->status,
            'soft_delete' => $this->soft_delete,
        ]);

        $query->andFilterWhere(['like', 'consultorio', $this->consultorio]);

        if($this->id_linea){
            $ids_lineas= [];
               
            $items = Lineas::find()->where(['like', 'linea', $this->id_linea])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_lineas)){
                    array_push($ids_lineas, $item->id);
                }
            }
    
            if(count($ids_lineas)>0){
                $query->andFilterWhere(['in', 'id_linea', $ids_lineas]);
            } else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        return $dataProvider;
    }
}
