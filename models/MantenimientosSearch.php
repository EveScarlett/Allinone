<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mantenimientos;

use Yii;
/**
 * MantenimientosSearch represents the model behind the search form of `app\models\Mantenimientos`.
 */
class MantenimientosSearch extends Mantenimientos
{
    public $timeStart;
    public $timeEnd;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'tipo_mantenimiento', 'status_maquina', 'status', 'create_user', 'update_user'], 'integer'],
            [['clave', 'id_maquina', 'realiza_mantenimiento', 'descripcion', 'proximo_mantenimiento', 'nombre_firma1', 'firma1', 'nombre_firma2', 'firma2', 'nombre_firma3', 'firma3', 'create_date', 'update_date','fecha','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
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
        $query = Mantenimientos::find();

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

        $dataProvider->sort->attributes['clave'] = [
            'asc' => ['clave' => SORT_ASC],
            'desc' => ['clave' => SORT_DESC]
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

        $dataProvider->sort->attributes['tipo_mantenimiento'] = [
            'asc' => ['tipo_mantenimiento' => SORT_ASC],
            'desc' => ['tipo_mantenimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_maquina'] = [
            'asc' => ['id_maquina' => SORT_ASC],
            'desc' => ['id_maquina' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['realiza_mantenimiento'] = [
            'asc' => ['realiza_mantenimiento' => SORT_ASC],
            'desc' => ['realiza_mantenimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha'] = [
            'asc' => ['fecha' => SORT_ASC],
            'desc' => ['fecha' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status_maquina'] = [
            'asc' => ['status_maquina' => SORT_ASC],
            'desc' => ['status_maquina' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['proximo_mantenimiento'] = [
            'asc' => ['proximo_mantenimiento' => SORT_ASC],
            'desc' => ['proximo_mantenimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['descripcion'] = [
            'asc' => ['descripcion' => SORT_ASC],
            'desc' => ['descripcion' => SORT_DESC]
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
            'tipo_mantenimiento' => $this->tipo_mantenimiento,
            'status_maquina' => $this->status_maquina,
            'status' => $this->status,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
        ]);

        if($this->fecha){
            $fechas = explode(' - ', $this->fecha);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha', $fechas[0], $fechas[1]]);
        }

        if($this->proximo_mantenimiento){
            $fechas = explode(' - ', $this->proximo_mantenimiento);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'proximo_mantenimiento', $fechas[0], $fechas[1]]);
        }

        if(isset($this->id_maquina) && $this->id_maquina != ''){
            
            $query->andFilterWhere([ 'OR' ,
                ['like', 'nombre',$this->id_maquina],
                ['like', 'marca',$this->id_maquina],
                ['like', 'modelo', $this->id_maquina],
                ['like', 'numero_serie', $this->id_maquina],
                ]); 
        }

        $query->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'realiza_mantenimiento', $this->realiza_mantenimiento])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'nombre_firma1', $this->nombre_firma1])
            ->andFilterWhere(['like', 'firma1', $this->firma1])
            ->andFilterWhere(['like', 'nombre_firma2', $this->nombre_firma2])
            ->andFilterWhere(['like', 'firma2', $this->firma2])
            ->andFilterWhere(['like', 'nombre_firma3', $this->nombre_firma3])
            ->andFilterWhere(['like', 'firma3', $this->firma3]);

        return $dataProvider;
    }
}
