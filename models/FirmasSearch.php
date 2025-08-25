<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Firmas;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * FirmasSearch represents the model behind the search form of `app\models\Firmas`.
 */
class FirmasSearch extends Firmas
{
    public $timeStart;
    public $timeEnd;
    public $timeStart2;
    public $timeEnd2;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status','tipo', 'id_empresa'], 'integer'],
            [['nombre', 'universidad', 'cedula', 'firma', 'titulo', 'abreviado_titulo', 'registro_sanitario', 'fecha_inicio', 'fecha_fin','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
        ];
    }



    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_inicio',
                'dateStartAttribute' => 'timeStart',
                'dateEndAttribute' => 'timeEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_fin',
                'dateStartAttribute' => 'timeStart2',
                'dateEndAttribute' => 'timeEnd2',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
        ];
    } */

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
        $query = Firmas::find();

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

        $dataProvider->sort->attributes['nombre'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC]
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

        $dataProvider->sort->attributes['universidad'] = [
            'asc' => ['universidad' => SORT_ASC],
            'desc' => ['universidad' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cedula'] = [
            'asc' => ['cedula' => SORT_ASC],
            'desc' => ['cedula' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['firma'] = [
            'asc' => ['firma' => SORT_ASC],
            'desc' => ['firma' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['registro_sanitario'] = [
            'asc' => ['registro_sanitario' => SORT_ASC],
            'desc' => ['registro_sanitario' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_inicio'] = [
            'asc' => ['fecha_inicio' => SORT_ASC],
            'desc' => ['fecha_inicio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_fin'] = [
            'asc' => ['fecha_fin' => SORT_ASC],
            'desc' => ['fecha_fin' => SORT_DESC]
        ];


        $this->load($params);

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
        

        $query->andFilterWhere(['tipo'=> 2]);

        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'universidad', $this->universidad])
            ->andFilterWhere(['like', 'cedula', $this->cedula])
            ->andFilterWhere(['like', 'firma', $this->firma])
            ->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'abreviado_titulo', $this->abreviado_titulo])
            ->andFilterWhere(['like', 'registro_sanitario', $this->registro_sanitario]);

           /*  if ($this->timeStart) {
                $query->andWhere(['>=', "fecha_inicio", $this->timeToUTC($this->timeStart)]);
            }
            if ($this->timeStart) {
                $query->andWhere(['<=', "fecha_inicio", $this->timeToUTC($this->timeEnd)]);
            }

            if ($this->timeStart2) {
                $query->andWhere(['>=', "fecha_inicio", $this->timeToUTC($this->timeStart2)]);
            }
            if ($this->timeStart2) {
                $query->andWhere(['<=', "fecha_fin", $this->timeToUTC($this->timeEnd2)]);
            } */

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

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}
