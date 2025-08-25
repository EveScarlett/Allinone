<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historialdocumentos;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * HistorialdocumentosSearch represents the model behind the search form of `app\models\Historialdocumentos`.
 */
class HistorialdocumentosSearch extends Historialdocumentos
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
            [['id', 'id_empresa', 'id_trabajador', 'id_puesto', 'id_tipo', 'id_estudio', 'id_periodicidad'], 'integer'],
            [['fecha_documento', 'fecha_vencimiento', 'evidencia','update_date','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'safe'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_documento',
                'dateStartAttribute' => 'timeStart',
                'dateEndAttribute' => 'timeEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'update_date',
                'dateStartAttribute' => 'timeStart2',
                'dateEndAttribute' => 'timeEnd2',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ]
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
        $query = Historialdocumentos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

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

        $dataProvider->sort->attributes['id_puesto'] = [
            'asc' => ['id_puesto' => SORT_ASC],
            'desc' => ['id_puesto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_tipo'] = [
            'asc' => ['id_tipo' => SORT_ASC],
            'desc' => ['id_tipo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_estudio'] = [
            'asc' => ['id_estudio' => SORT_ASC],
            'desc' => ['id_estudio' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_documento'] = [
            'asc' => ['fecha_documento' => SORT_ASC],
            'desc' => ['fecha_documento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['update_date'] = [
            'asc' => ['update_date' => SORT_ASC],
            'desc' => ['update_date' => SORT_DESC]
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

        //$query->orderBy(['id'=>SORT_DESC]);

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
            'id_trabajador' => $this->id_trabajador,
            'id_tipo' => $this->id_tipo,
            'id_puesto' => $this->id_puesto,
            'id_periodicidad' => $this->id_periodicidad,
            'fecha_vencimiento' => $this->fecha_vencimiento,
        ]);

        if($this->id_estudio){
            //dd($this->id_estudio);
            $query->andFilterWhere(['in', 'id_estudio', $this->id_estudio]);
        }

        /* if ($this->timeStart) {
            $query->andWhere(['>=', "fecha_documento", $this->timeToUTC($this->timeStart)]);
        }
        if ($this->timeStart) {
            $query->andWhere(['<=', "fecha_documento", $this->timeToUTC($this->timeEnd)]);
        }

        if ($this->timeStart2) {
            $query->andWhere(['>=', "update_date", $this->timeToUTC($this->timeStart2)]);
        }
        if ($this->timeStart2) {
            $query->andWhere(['<=', "update_date", $this->timeToUTC($this->timeEnd2)]);
        } */

        if($this->fecha_documento){
            $fechas = explode(' - ', $this->fecha_documento);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_documento', $fechas[0], $fechas[1]]);
        }

        if($this->update_date){
            $fechas = explode(' - ', $this->update_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'update_date', $fechas[0], $fechas[1]]);
        }


        $query->andFilterWhere(['like', 'evidencia', $this->evidencia]);

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}
