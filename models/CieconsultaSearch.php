<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cieconsulta;
use Yii;
/**
 * CieconsultaSearch represents the model behind the search form of `app\models\Cieconsulta`.
 */
class CieconsultaSearch extends Cieconsulta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'id_consulta', 'id_cie'], 'integer'],
            [['clave', 'diagnostico', 'fecha'], 'safe'],
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
        $query = Cieconsulta::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_cie' => SORT_ASC]]
        ]);

        //$query->orderBy(['id_cie'=>SORT_ASC]);
        //$query->orderBy(['id'=>SORT_DESC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['clave'] = [
            'asc' => ['clave' => SORT_ASC],
            'desc' => ['clave' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['diagnostico'] = [
            'asc' => ['diagnostico' => SORT_ASC],
            'desc' => ['diagnostico' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_cie'] = [
            'asc' => ['id_cie' => SORT_ASC],
            'desc' => ['id_cie' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['qty'] = [
            'asc' => ['qty' => SORT_ASC],
            'desc' => ['qty' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['fecha'] = [
            'asc' => ['fecha' => SORT_ASC],
            'desc' => ['fecha' => SORT_DESC]
        ];

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
            'id_nivel1' => $this->id_nivel1,
            'id_nivel2' => $this->id_nivel2,
            'id_nivel3' => $this->id_nivel3,
            'id_nivel4' => $this->id_nivel4,
            'id_consulta' => $this->id_consulta,
            'id_cie' => $this->id_cie,
            'id_empresa' => $this->id_empresa,
        ]);

        $query->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'diagnostico', $this->diagnostico]);

        if($this->fecha){
            $fechas = explode(' - ', $this->fecha);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }
}
