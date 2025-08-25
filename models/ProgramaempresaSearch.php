<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Programaempresa;
use Yii;
/**
 * ProgramaempresaSearch represents the model behind the search form of `app\models\Programaempresa`.
 */
class ProgramaempresaSearch extends Programaempresa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa','id_pais' ,'id_programa', 'status', 'soft_delete'], 'integer'],
            [['id_linea','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = Programaempresa::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy(['id'=>SORT_DESC]);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);

        $show_nivel1 = false;
        $show_nivel2 = false;
        $show_nivel3 = false;
        $show_nivel4 = false;

        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
        } else {
            $empresa_usuario = Empresas::findOne($empresas[0]);

            if($empresa_usuario){
                if($empresa_usuario->cantidad_niveles >= 1){
                     $show_nivel1 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 2){
                     $show_nivel2 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 3){
                     $show_nivel3 = true;
                }
                if($empresa_usuario->cantidad_niveles >= 4){
                     $show_nivel4 = true;
                }
            }
        }


        if($show_nivel1){
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
        }
        
        if($show_nivel2){
            $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
            if(Yii::$app->user->identity->nivel2_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel2', $niveles_2]);
            }
        }
        
        if($show_nivel3){
            $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
            if(Yii::$app->user->identity->nivel3_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel3', $niveles_3]);
            } 
        }
        
        if($show_nivel4){
            $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
            if(Yii::$app->user->identity->nivel4_all != 1) {
                $query->andFilterWhere(['in', 'id_nivel4', $niveles_4]);
            }
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
            'id_programa' => $this->id_programa,
            'status' => $this->status,
            'soft_delete' => $this->soft_delete,
        ]);

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
