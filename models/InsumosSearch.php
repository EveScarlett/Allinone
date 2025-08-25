<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insumos;
use Yii;

/**
 * InsumosSearch represents the model behind the search form of `app\models\Insumos`.
 */
class InsumosSearch extends Insumos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'tipo', 'id_presentacion', 'id_unidad', 'cantidad', 'create_user', 'update_user', 'delete_user', 'status','sexo','talla','fabricante','color'], 'integer'],
            [['nombre_comercial', 'nombre_generico', 'foto', 'concentracion', 'fabricante', 'formula', 'condiciones_conservacion', 'create_date', 'update_date', 'delete_date','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = Insumos::find();

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

        $dataProvider->sort->attributes['nombre_comercial'] = [
            'asc' => ['nombre_comercial' => SORT_ASC],
            'desc' => ['nombre_comercial' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['stock_minimo'] = [
            'asc' => ['stock_minimo' => SORT_ASC],
            'desc' => ['stock_minimo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_presentacion'] = [
            'asc' => ['id_presentacion' => SORT_ASC],
            'desc' => ['id_presentacion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_unidad'] = [
            'asc' => ['id_unidad' => SORT_ASC],
            'desc' => ['id_unidad' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fabricante'] = [
            'asc' => ['fabricante' => SORT_ASC],
            'desc' => ['fabricante' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['talla'] = [
            'asc' => ['talla' => SORT_ASC],
            'desc' => ['talla' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['sexo'] = [
            'asc' => ['sexo' => SORT_ASC],
            'desc' => ['sexo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['color'] = [
            'asc' => ['color' => SORT_ASC],
            'desc' => ['color' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['unidades_individuales'] = [
            'asc' => ['unidades_individuales' => SORT_ASC],
            'desc' => ['unidades_individuales' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['via_administracion'] = [
            'asc' => ['via_administracion' => SORT_ASC],
            'desc' => ['via_administracion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['formula'] = [
            'asc' => ['formula' => SORT_ASC],
            'desc' => ['formula' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['advertencias'] = [
            'asc' => ['advertencias' => SORT_ASC],
            'desc' => ['advertencias' => SORT_DESC]
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
            'tipo' => $this->tipo,
            'id_presentacion' => $this->id_presentacion,
            'id_unidad' => $this->id_unidad,
            'cantidad' => $this->cantidad,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
            'delete_date' => $this->delete_date,
            'delete_user' => $this->delete_user,
            'status' => $this->status,
            'sexo' => $this->sexo,
            'talla' => $this->talla,
            'color' => $this->color,
        ]);

        $query->andFilterWhere(['or',
        ['like', 'nombre_comercial', $this->nombre_comercial],
        ['like', 'nombre_generico', $this->nombre_comercial]])
           
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'concentracion', $this->concentracion])
            ->andFilterWhere(['like', 'fabricante', $this->fabricante])
            ->andFilterWhere(['like', 'formula', $this->formula])
            ->andFilterWhere(['like', 'condiciones_conservacion', $this->condiciones_conservacion]);

        return $dataProvider;
    }
}
