<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Maquinaria;

use Yii;
/**
 * MaquinariaSearch represents the model behind the search form of `app\models\Maquinaria`.
 */
class MaquinariaSearch extends Maquinaria
{
    public $riesgos;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_ubicacion', 'id_area', 'status','status_operacion', 'create_user', 'update_user'], 'integer'],
            [['clave', 'maquina', 'fabricante', 'modelo', 'marca', 'combustible', 'detalles_tecnicos', 'funcion', 'qr', 'create_date', 'update_date','riesgos','fecha_mantenimiento','proximo_mantenimiento','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
            [['peso', 'altura', 'ancho', 'largo', 'alto'], 'number'],
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
        $query = Maquinaria::find();

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

        $dataProvider->sort->attributes['maquina'] = [
            'asc' => ['maquina' => SORT_ASC],
            'desc' => ['maquina' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status_operacion'] = [
            'asc' => ['status_operacion' => SORT_ASC],
            'desc' => ['status_operacion' => SORT_DESC]
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

        $dataProvider->sort->attributes['fabricante'] = [
            'asc' => ['fabricante' => SORT_ASC],
            'desc' => ['fabricante' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['modelo'] = [
            'asc' => ['modelo' => SORT_ASC],
            'desc' => ['modelo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['marca'] = [
            'asc' => ['marca' => SORT_ASC],
            'desc' => ['marca' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_area'] = [
            'asc' => ['id_area' => SORT_ASC],
            'desc' => ['id_area' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_ubicacion'] = [
            'asc' => ['id_ubicacion' => SORT_ASC],
            'desc' => ['id_ubicacion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['qr'] = [
            'asc' => ['qr' => SORT_ASC],
            'desc' => ['qr' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_mantenimiento'] = [
            'asc' => ['fecha_mantenimiento' => SORT_ASC],
            'desc' => ['fecha_mantenimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['proximo_mantenimiento'] = [
            'asc' => ['proximo_mantenimiento' => SORT_ASC],
            'desc' => ['proximo_mantenimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
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
            'id_ubicacion' => $this->id_ubicacion,
            'id_area' => $this->id_area,
            'peso' => $this->peso,
            'altura' => $this->altura,
            'ancho' => $this->ancho,
            'largo' => $this->largo,
            'alto' => $this->alto,
            'status' => $this->status,
            'status_operacion' => $this->status_operacion,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
        ]);

        $query->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'maquina', $this->maquina])
            ->andFilterWhere(['like', 'fabricante', $this->fabricante])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'marca', $this->marca])
            ->andFilterWhere(['like', 'combustible', $this->combustible])
            ->andFilterWhere(['like', 'detalles_tecnicos', $this->detalles_tecnicos])
            ->andFilterWhere(['like', 'funcion', $this->funcion])
            ->andFilterWhere(['like', 'qr', $this->qr]);

        
        if(isset($this->riesgos) && $this->riesgos != ''){
                $ids_maquina = [];
    
                $items = Maquinariesgo::find()->where(['like','riesgo',$this->riesgos])->select(['id_maquina'])->distinct()->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_maquina, $ids_maquina)){
                        array_push($ids_maquina, $item->id_maquina);
                    }
                }
                if(count($ids_maquina)>0){
                    $query->andFilterWhere(['in', 'id', $ids_maquina]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
        }


        if($this->fecha_mantenimiento){
            $fechas = explode(' - ', $this->fecha_mantenimiento);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_mantenimiento', $fechas[0], $fechas[1]]);
        }

        if($this->proximo_mantenimiento){
            $fechas = explode(' - ', $this->proximo_mantenimiento);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'proximo_mantenimiento', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }
}
