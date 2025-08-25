<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Consultas;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * ConsultasSearch represents the model behind the search form of `app\models\Consultas`.
 */
class ConsultasSearch extends Consultas
{
    public $timeStart;
    public $timeEnd;
    public $timeStart2;
    public $timeEnd2;
    public $timeStart3;
    public $timeEnd3;
    public $condicion;

    public $consentimiento;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_consultorio', 'tipo', 'visita', 'solicitante', 'sexo', 'area', 'puesto', 'resultado', 'tipo_padecimiento','incapacidad_tipo','incapacidad_ramo','visita','incapacidad_dias','consentimiento','uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad'], 'integer'],
            [['folio', 'id_trabajador', 'fecha', 'hora_inicio', 'hora_fin', 'num_imss', 'evidencia', 'fc', 'fr', 'temp', 'ta', 'ta_diastolica', 'pulso', 'oxigeno', 'imc', 'categoria_imc', 'sintomatologia', 'aparatos', 'alergias', 'embarazo', 'diagnosticocie', 'diagnostico', 'estudios', 'manejo', 'seguimiento','num_trabajador','incapacidad_fechainicio','incapacidad_fechafin','id_nivel1','id_nivel2','id_nivel3','id_nivel4','status_baja','create_user'], 'safe'],
            [['peso', 'talla', 'condicion'], 'number'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha',
                'dateStartAttribute' => 'timeStart',
                'dateEndAttribute' => 'timeEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'incapacidad_fechainicio',
                'dateStartAttribute' => 'timeStart2',
                'dateEndAttribute' => 'timeEnd2',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'incapacidad_fechafin',
                'dateStartAttribute' => 'timeStart3',
                'dateEndAttribute' => 'timeEnd3',
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
        $query = Consultas::find();

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
            'asc' => ['empresa' => SORT_ASC],
            'desc' => ['empresa' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel1'] = [
            'asc' => ['id_nivel1' => SORT_ASC],
            'desc' => ['id_nivel1' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel2'] = [
            'asc' => ['id_nivel2' => SORT_ASC],
            'desc' => ['id_nivel2' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel3'] = [
            'asc' => ['id_nivel3' => SORT_ASC],
            'desc' => ['id_nivel3' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_nivel4'] = [
            'asc' => ['id_nivel4' => SORT_ASC],
            'desc' => ['id_nivel4' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['num_trabajador'] = [
            'asc' => ['num_trabajador' => SORT_ASC],
            'desc' => ['num_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_trabajador'] = [
            'asc' => ['apellidos' => SORT_ASC],
            'desc' => ['apellidos' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['condicion'] = [
            'asc' => ['condicion' => SORT_ASC],
            'desc' => ['condicion' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['fecha'] = [
            'asc' => ['fecha' => SORT_ASC],
            'desc' => ['fecha' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['tipo'] = [
            'asc' => ['tipo' => SORT_ASC],
            'desc' => ['tipo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['visita'] = [
            'asc' => ['visita' => SORT_ASC],
            'desc' => ['visita' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['solicitante'] = [
            'asc' => ['solicitante' => SORT_ASC],
            'desc' => ['solicitante' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_user'] = [
            'asc' => ['nombre_medico' => SORT_ASC],
            'desc' => ['nombre_medico' => SORT_DESC]
        ];


        /* $dataProvider->sort->attributes['consentimiento'] = [
            'asc' => ['consentimiento' => SORT_ASC],
            'desc' => ['consentimiento' => SORT_DESC]
        ]; */


        //$query->orderBy(['id'=>SORT_DESC]);

        $query->andFilterWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);

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
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'id_consultorio' => $this->id_consultorio,
            'tipo' => $this->tipo,
            'visita' => $this->visita,
            'solicitante' => $this->solicitante,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'sexo' => $this->sexo,
            'area' => $this->area,
            'puesto' => $this->puesto,
            'peso' => $this->peso,
            'talla' => $this->talla,
            'resultado' => $this->resultado,
            'tipo_padecimiento' => $this->tipo_padecimiento,
            'incapacidad_tipo' => $this->incapacidad_tipo,
            'incapacidad_ramo' => $this->incapacidad_ramo,
            'visita' => $this->visita,
            'incapacidad_dias' => $this->incapacidad_dias,
        ]);

        $query->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'num_trabajador', $this->num_trabajador])
            ->andFilterWhere(['like', 'num_imss', $this->num_imss])
            ->andFilterWhere(['like', 'evidencia', $this->evidencia])
            ->andFilterWhere(['like', 'fc', $this->fc])
            ->andFilterWhere(['like', 'fr', $this->fr])
            ->andFilterWhere(['like', 'temp', $this->temp])
            ->andFilterWhere(['like', 'ta', $this->ta])
            ->andFilterWhere(['like', 'ta_diastolica', $this->ta_diastolica])
            ->andFilterWhere(['like', 'pulso', $this->pulso])
            ->andFilterWhere(['like', 'oxigeno', $this->oxigeno])
            ->andFilterWhere(['like', 'imc', $this->imc])
            ->andFilterWhere(['like', 'categoria_imc', $this->categoria_imc])
            ->andFilterWhere(['like', 'sintomatologia', $this->sintomatologia])
            ->andFilterWhere(['like', 'aparatos', $this->aparatos])
            ->andFilterWhere(['like', 'alergias', $this->alergias])
            ->andFilterWhere(['like', 'embarazo', $this->embarazo])
            ->andFilterWhere(['like', 'diagnosticocie', $this->diagnosticocie])
            ->andFilterWhere(['like', 'diagnostico', $this->diagnostico])
            ->andFilterWhere(['like', 'estudios', $this->estudios])
            ->andFilterWhere(['like', 'manejo', $this->manejo])
            ->andFilterWhere(['like', 'seguimiento', $this->seguimiento]);

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


            if($this->create_user){
                //dd($this->create_user);
                $ids_medicos = [];
               
                $items = Usuarios::find()->where(['like', 'name', $this->create_user])->all();
                foreach($items as $item){
                    if(!in_array($item->id, $ids_medicos)){
                        array_push($ids_medicos, $item->id);
                    }
                }
    
                if(count($ids_medicos)>0){
                    $query->andFilterWhere(['in', 'create_user', $ids_medicos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->condicion){
                //dd($this->condicion);
                $ids_trabajadores = [];
    
                if($this->condicion == 2){
                    $items = Trabajadores::find()->where(['status'=>2])->all();
                }else{
                    $items = Trabajadores::find()->where(['status'=>$this->condicion])->all();
                }
                
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


        if($this->id_nivel1){
            //dd($this->id_nivel1);
            $ids_nivel1 = [];
               
            $items = NivelOrganizacional1::find()->where(['id_pais'=>$this->id_nivel1])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel1)){
                    array_push($ids_nivel1, $item->id);
                }
            }
    
            if(count($ids_nivel1)>0){
                $query->andFilterWhere(['in', 'id_nivel1', $ids_nivel1]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel2){
            $ids_nivel2 = [];
               
            $items = NivelOrganizacional2::find()->where(['like', 'nivelorganizacional2', $this->id_nivel2])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel2)){
                    array_push($ids_nivel2, $item->id);
                }
            }
    
            if(count($ids_nivel2)>0){
                $query->andFilterWhere(['in', 'id_nivel2', $ids_nivel2]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel3){
            $ids_nivel3 = [];
               
            $items = NivelOrganizacional3::find()->where(['like', 'nivelorganizacional3', $this->id_nivel3])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel3)){
                    array_push($ids_nivel3, $item->id);
                }
            }
    
            if(count($ids_nivel3)>0){
                $query->andFilterWhere(['in', 'id_nivel3', $ids_nivel3]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->id_nivel4){
            $ids_nivel4 = [];
               
            $items = NivelOrganizacional4::find()->where(['like', 'nivelorganizacional4', $this->id_nivel4])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_nivel4)){
                    array_push($ids_nivel4, $item->id);
                }
            }
    
            if(count($ids_nivel4)>0){
                $query->andFilterWhere(['in', 'id_nivel4', $ids_nivel4]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }


        if($this->consentimiento){
            $ids_consentimiento = [];
            $items = null;
            
            if($this->consentimiento == 1){
                $items = Consultas::find()->where(['in','uso_consentimiento',[1,2]])->andWhere(['in','retirar_consentimiento',[1,2]])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }
            } else {
                $items = Consultas::find()->where(['IS', 'uso_consentimiento', new \yii\db\Expression('NULL')])->andWhere(['IS', 'retirar_consentimiento', new \yii\db\Expression('NULL')])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }
            }
            //dd($this->consentimiento,count($items),$items);
            if(count($ids_consentimiento)>0){
                $query->andFilterWhere(['in', 'id', $ids_consentimiento]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }
        }
        

            /* if ($this->timeStart) {
                $query->andWhere(['>=', "fecha", $this->timeToUTC($this->timeStart)]);
            }
            if ($this->timeStart) {
                $query->andWhere(['<=', "fecha", $this->timeToUTC($this->timeEnd)]);
            }

            if ($this->timeStart2) {
                $query->andWhere(['>=', "incapacidad_fechainicio", $this->timeToUTC($this->timeStart2)]);
            }
            if ($this->timeStart2) {
                $query->andWhere(['<=', "incapacidad_fechainicio", $this->timeToUTC($this->timeEnd2)]);
            }

            if ($this->timeStart3) {
                $query->andWhere(['>=', "incapacidad_fechafin", $this->timeToUTC($this->timeStart3)]);
            }
            if ($this->timeStart3) {
                $query->andWhere(['<=', "incapacidad_fechafin", $this->timeToUTC($this->timeEnd3)]);
            } */

        if($this->fecha){
            $fechas = explode(' - ', $this->fecha);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha', $fechas[0], $fechas[1]]);
        }

        if($this->incapacidad_fechainicio){
            $fechas = explode(' - ', $this->incapacidad_fechainicio);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'incapacidad_fechainicio', $fechas[0], $fechas[1]]);
        }

        if($this->incapacidad_fechafin){
            $fechas = explode(' - ', $this->incapacidad_fechafin);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'incapacidad_fechafin', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}
