<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historicoes;

use Yii;
/**
 * HistoricoesSearch represents the model behind the search form of `app\models\Historicoes`.
 */
class HistoricoesSearch extends Historicoes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_model', 'id_empresa', 'id_trabajador', 'id_maquina', 'status_trabajo', 'status', 'create_user'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'create_date','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = Historicoes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['fecha_inicio' => SORT_DESC]]
        ]);

        //$query->orderBy(['fecha_inicio'=>SORT_DESC]);

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

        $dataProvider->sort->attributes['id_trabajador'] = [
            'asc' => ['id_trabajador' => SORT_ASC],
            'desc' => ['id_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_maquina'] = [
            'asc' => ['id_maquina' => SORT_ASC],
            'desc' => ['id_maquina' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_inicio'] = [
            'asc' => ['fecha_inicio' => SORT_ASC],
            'desc' => ['fecha_inicio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_fin'] = [
            'asc' => ['fecha_fin' => SORT_ASC],
            'desc' => ['fecha_fin' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status_trabajo'] = [
            'asc' => ['status_trabajo' => SORT_ASC],
            'desc' => ['status_trabajo' => SORT_DESC]
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

        // add conditions that should always apply here

       

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_model' => $this->id_model,
            'id_empresa' => $this->id_empresa,
            'status_trabajo' => $this->status_trabajo,
            'status' => $this->status,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
        ]);

        if($this->id_trabajador){
            $ids_trabajadores = [];
           
            $items = Trabajadores::find()->where(['like', 'Concat(nombre," ", apellidos)', $this->id_trabajador])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id);
                }
            }

            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id_trabajador', $ids_trabajadores]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        if($this->fecha_inicio){
            $fechas = explode(' - ', $this->fecha_inicio);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_inicio', $fechas[0], $fechas[1]]);
        }

        if($this->fecha_fin){
            $fechas = explode(' - ', $this->fecha_fin);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_fin', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }
}
