<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Trabajadores;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * TrabajadoresSearch represents the model behind the search form of `app\models\Trabajadores`.
 */
class TrabajadoresSearch extends Trabajadores
{
    public $cal;
    public $createStart;
    public $createEnd;
    public $fcontratoStart;
    public $fcontratoEnd;
    public $fvencimientoStart;
    public $fvencimientoEnd;
    public $riesgos;
    public $epps;

    public $vencimientos;
    public $consentimiento;

    public $estudios_pendientes;

    public $porcentaje_cumplimiento_inicio;
    public $porcentaje_cumplimiento_fin;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_area', 'id_puesto','tipo_examen', 'sexo', 'estado_civil', 'edad', 'nivel_lectura', 'nivel_escritura', 'escolaridad', 'grupo', 'rh', 'tipo_contratacion', 'create_user', 'update_user', 'delete_user', 'status','cal','antiguedad_anios','antiguedad_meses','antiguedad_dias','status_documentos','status_baja','dato_extra1','dato_extra2','consentimiento','uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','estudios_pendientes','porcentaje_cumplimiento'], 'integer'],
            [['nombre', 'apellidos', 'fecha_nacimiento', 'num_imss', 'celular', 'contacto_emergencia', 'direccion', 'colonia', 'pais', 'estado', 'ciudad', 'municipio', 'cp', 'num_trabajador', 'fecha_contratacion', 'fecha_baja', 'antiguedad', 'ruta', 'parada', 'create_date', 'update_date', 'delete_date','riesgos','epps','vencimientos','id_nivel1','id_nivel2','id_nivel3','id_nivel4','tipo_registro','porcentaje_cumplimiento','porcentaje_cumplimiento_inicio','porcentaje_cumplimiento_fin','puesto_contable'], 'safe'],
        ];
    }

    /* public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'create_date',
                'dateStartAttribute' => 'createStart',
                'dateEndAttribute' => 'createEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_contratacion',
                'dateStartAttribute' => 'fcontratoStart',
                'dateEndAttribute' => 'fcontratoEnd',
                'dateStartFormat' => ('Y-m-d'),
                'dateEndFormat' => ('Y-m-d'),
            ],
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'fecha_baja',
                'dateStartAttribute' => 'fvencimientoStart',
                'dateEndAttribute' => 'fvencimientoEnd',
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
        $query = Trabajadores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC,'id' => SORT_DESC]]
        ]);

        //$query->orderBy(['id'=>SORT_DESC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['num_trabajador'] = [
            'asc' => ['num_trabajador' => SORT_ASC],
            'desc' => ['num_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nombre'] = [
            'asc' => ['apellidos' => SORT_ASC],
            'desc' => ['apellidos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
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

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['edad'] = [
            'asc' => ['edad' => SORT_ASC],
            'desc' => ['edad' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['sexo'] = [
            'asc' => ['sexo' => SORT_ASC],
            'desc' => ['sexo' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha_contratacion'] = [
            'asc' => ['fecha_contratacion' => SORT_ASC],
            'desc' => ['fecha_contratacion' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['antiguedad'] = [
            'asc' => ['antiguedad' => SORT_ASC],
            'desc' => ['antiguedad' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_area'] = [
            'asc' => ['id_area' => SORT_ASC],
            'desc' => ['id_area' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_area'] = [
            'asc' => ['id_area' => SORT_ASC],
            'desc' => ['id_area' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_puesto'] = [
            'asc' => ['id_puesto' => SORT_ASC],
            'desc' => ['id_puesto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_puesto'] = [
            'asc' => ['id_puesto' => SORT_ASC],
            'desc' => ['id_puesto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status_documentos'] = [
            'asc' => ['status_documentos' => SORT_ASC],
            'desc' => ['status_documentos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['estudios_pendientes'] = [
            'asc' => ['estudios_pendientes' => SORT_ASC],
            'desc' => ['estudios_pendientes' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_link'] = [
            'asc' => ['id_link' => SORT_ASC],
            'desc' => ['id_link' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['porcentaje_cumplimiento'] = [
            'asc' => ['porcentaje_cumplimiento' => SORT_ASC],
            'desc' => ['porcentaje_cumplimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['puesto_contable'] = [
            'asc' => ['puesto_contable' => SORT_ASC],
            'desc' => ['puesto_contable' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['cal'] = [
            'asc' => ['cal' => SORT_ASC],
            'desc' => ['cal' => SORT_DESC]
        ]; */

        /* $dataProvider->sort->attributes['prog_salud'] = [
            'asc' => ['prog_salud' => SORT_ASC],
            'desc' => ['prog_salud' => SORT_DESC]
        ]; */

        /* $dataProvider->sort->attributes['riesgos'] = [
            'asc' => ['riesgos' => SORT_ASC],
            'desc' => ['riesgos' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['tipo_registro'] = [
            'asc' => ['tipo_registro' => SORT_ASC],
            'desc' => ['tipo_registro' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['consentimiento'] = [
            'asc' => ['consentimiento' => SORT_ASC],
            'desc' => ['consentimiento' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['create_date'] = [
            'asc' => ['create_date' => SORT_ASC],
            'desc' => ['create_date' => SORT_DESC]
        ];

        

        $show_nivel1 = false;
        $show_nivel2 = false;
        $show_nivel3 = false;
        $show_nivel4 = false;

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
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

        $ubicaciones = [];;
        if(1==2) {
            $query->andFilterWhere(['in', 'dato_extra1', $ubicaciones]);
        }

        $paises = [];;
        if(1==2) {
            $query->andFilterWhere(['in', 'dato_extra2', $paises]);
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

        $query->andFilterWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);

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
            'dato_extra1' => $this->dato_extra1,
            'dato_extra2' => $this->dato_extra2,
            'tipo_examen' => $this->tipo_examen,
            'sexo' => $this->sexo,
            'estado_civil' => $this->estado_civil,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'edad' => $this->edad,
            'nivel_lectura' => $this->nivel_lectura,
            'nivel_escritura' => $this->nivel_escritura,
            'escolaridad' => $this->escolaridad,
            'grupo' => $this->grupo,
            'rh' => $this->rh,
            'tipo_contratacion' => $this->tipo_contratacion,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
            'delete_date' => $this->delete_date,
            'delete_user' => $this->delete_user,
            'status' => $this->status,
            'id_area' => $this->id_area,
            'id_puesto' => $this->id_puesto,
            'estudios_pendientes'=>$this->estudios_pendientes,
            'status_documentos' => $this->status_documentos,
            'porcentaje_cumplimiento' => $this->porcentaje_cumplimiento,
        ]);

        $query->andFilterWhere(['like', 'Concat(nombre," ", apellidos)', $this->nombre])
            ->andFilterWhere(['like', 'num_imss', $this->num_imss])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'contacto_emergencia', $this->contacto_emergencia])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'colonia', $this->colonia])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'municipio', $this->municipio])
            ->andFilterWhere(['like', 'cp', $this->cp])
            ->andFilterWhere(['like', 'num_trabajador', $this->num_trabajador])
            ->andFilterWhere(['like', 'ruta', $this->ruta])
            ->andFilterWhere(['like', 'puesto_contable', $this->puesto_contable])
            ->andFilterWhere(['like', 'parada', $this->parada]);

            if($this->antiguedad){
                //dd($this->antiguedad);
                //'1'=>'≤ 1 mes',
                // '2'=>'≤ 3 meses',
                // '3'=>'≤ 6 meses',
                // '4'=>'≤ 9 meses',
                // '5'=>'< 1 año',
                // '6'=>'= 1 año',
                // '7'=>'>= 1 año < 2 años',
                // '8'=>'>= 2 años < 3 años',
                // '9'=>'>= 3 años < 4 años',
                // '10'=>'>= 4 años < 5 años',
                // '11'=>'>= 5 años < 6 años',
                // '12'=>'>= 6 años < 7 años',
                // '13'=>'>= 7 años < 8 años',
                // '14'=>'>= 8 años < 9 años',
                // '15'=>'>= 9 años < 10 años',
                // '16'=>'>= 10 años'

                if($this->antiguedad == 1){//≤ 1 mes
                    $query->andWhere(['<','antiguedad_meses',2]);
                    $query->andWhere(['antiguedad_anios'=>0]);
                } else if($this->antiguedad == 2){//≤ 3 meses
                    $query->andWhere(['<','antiguedad_meses',4]);
                    $query->andWhere(['antiguedad_anios'=>0]);
                } else if($this->antiguedad == 3){//≤ 6 meses
                    $query->andWhere(['<','antiguedad_meses',7]);
                    $query->andWhere(['antiguedad_anios'=>0]);
                } else if($this->antiguedad == 4){//≤ 9 meses
                    $query->andWhere(['<','antiguedad_meses',10]);
                    $query->andWhere(['antiguedad_anios'=>0]);
                } else if($this->antiguedad == 5){//< 1 año
                    $query->andWhere(['<','antiguedad_meses',12]);
                    $query->andWhere(['antiguedad_anios'=>0]);
                } else if($this->antiguedad == 6){//= 1 año
                    $query->andWhere( ['antiguedad_anios'=> 1]);
                    $query->andWhere(['antiguedad_meses'=>0]);
                    $query->andWhere(['antiguedad_dias'=>0]);
                } else if($this->antiguedad == 7){//>= 1 año < 2 años
                    $query->andWhere( ['antiguedad_anios'=> 1]);
                } else if($this->antiguedad == 8){//>= 2 años < 3 años
                    $query->andWhere( ['antiguedad_anios'=> 2]);
                } else if($this->antiguedad == 9){//>= 3 años < 4 años
                    $query->andWhere( ['antiguedad_anios'=> 3]);
                } else if($this->antiguedad == 10){//>= 4 años < 5 años
                    $query->andWhere( ['antiguedad_anios'=> 4]);
                } else if($this->antiguedad == 11){//>= 5 años < 6 años
                    $query->andWhere( ['antiguedad_anios'=> 5]);
                } else if($this->antiguedad == 12){//>= 6 años < 7 años
                    $query->andWhere( ['antiguedad_anios'=> 6]);
                } else if($this->antiguedad == 13){//>= 7 años < 8 años
                    $query->andWhere( ['antiguedad_anios'=> 7]);
                } else if($this->antiguedad == 14){//>= 8 años < 9 años
                    $query->andWhere( ['antiguedad_anios'=> 8]);
                } else if($this->antiguedad == 15){//>= 9 años < 10 años
                    $query->andWhere( ['antiguedad_anios'=> 9]);
                } else if($this->antiguedad == 16){//>= 10 años
                    $query->andWhere( ['>=','antiguedad_anios', 10]);
                }
                
            }

        /* if ($this->createStart) {
            $query->andWhere(['>=', "create_date", $this->timeToUTC($this->createStart)]);
        }
        if ($this->createStart) {
            $query->andWhere(['<=', "create_date", $this->timeToUTC($this->createEnd)]);
        }

        if ($this->fcontratoStart) {
            $query->andWhere(['>=', "fecha_contratacion", $this->timeToUTC($this->fcontratoStart)]);
        }
        if ($this->fcontratoStart) {
            $query->andWhere(['<=', "fecha_contratacion", $this->timeToUTC($this->fcontratoEnd)]);
        }

        if ($this->fvencimientoStart) {
            $query->andWhere(['>=', "fecha_baja", $this->timeToUTC($this->fvencimientoStart)]);
        }
        if ($this->fvencimientoStart) {
            $query->andWhere(['<=', "fecha_baja", $this->timeToUTC($this->fvencimientoEnd)]);
        } */

        if($this->create_date){
            $fechas = explode(' - ', $this->create_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'create_date', $fechas[0], $fechas[1]]);
        }

        if($this->fecha_contratacion){
            $fechas = explode(' - ', $this->fecha_contratacion);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_contratacion', $fechas[0], $fechas[1]]);
        }

        if($this->fecha_baja){
            $fechas = explode(' - ', $this->fecha_baja);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha_baja', $fechas[0], $fechas[1]]);
        }

        if($this->vencimientos){
            
            $fechas = explode(' - ', $this->vencimientos);

            if($fechas[0] == $fechas[1]){
                $fechas[0] = '1970-01-01';
            }
            
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';

            

            $ids_trabajadores = [];
    
            $items = Trabajadorestudio::find()->where(['between', 'fecha_vencimiento', $fechas[0], $fechas[1]])->select('id_trabajador')->distinct()->all();
            
            foreach($items as $item){
                if(!in_array($item->id_trabajador, $ids_trabajadores)){
                    array_push($ids_trabajadores, $item->id_trabajador);
                }
            }
            
            if(count($ids_trabajadores)>0){
                $query->andFilterWhere(['in', 'id', $ids_trabajadores]);
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
                $items = Trabajadores::find()->where(['in','uso_consentimiento',[1,2]])->andWhere(['in','retirar_consentimiento',[1,2]])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id, $ids_consentimiento)){
                        array_push($ids_consentimiento, $item->id);
                    }
                }
            } else {
                $items = Trabajadores::find()->where(['IS', 'uso_consentimiento', new \yii\db\Expression('NULL')])->andWhere(['IS', 'retirar_consentimiento', new \yii\db\Expression('NULL')])->all();
                
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


        if($this->porcentaje_cumplimiento_inicio && $this->porcentaje_cumplimiento_fin){
            $inicio = floatval($this->porcentaje_cumplimiento_inicio);
            $fin = floatval($this->porcentaje_cumplimiento_fin);

            if($inicio>$fin){
                $inicio = floatval($this->porcentaje_cumplimiento_fin);
                $fin = floatval($this->porcentaje_cumplimiento_inicio);
            }
            //dd($this->porcentaje_cumplimiento_inicio,$this->porcentaje_cumplimiento_fin,$inicio,$fin);
            
            $query->andWhere(['between', 'porcentaje_cumplimiento', $inicio, $fin]);
        }


        if($this->tipo_registro){
            if($this->tipo_registro == 2){
                $query->andFilterWhere(['in', 'tipo_registro', $this->tipo_registro]);
            } else {
                $query->andFilterWhere(['IS', 'tipo_registro', new \yii\db\Expression('NULL')]);
            }
        }

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}
