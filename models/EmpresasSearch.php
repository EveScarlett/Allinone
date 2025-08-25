<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empresas;
use Yii;

/**
 * EmpresasSearch represents the model behind the search form of `app\models\Empresas`.
 */
class EmpresasSearch extends Empresas
{
    public $paises;
    public $lineas;
    public $ubicaciones;
    public $areas;
    public $consultorios;
    public $turnos;
    public $programas;

    public $tipo_req;
    public $estudio_req;
    public $periodicidad_req;
    public $status_req;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_user', 'update_user', 'delete_user', 'status','tipo_req','periodicidad_req','status_req'], 'integer'],
            [['razon', 'comercial', 'abreviacion', 'rfc', 'pais', 'estado', 'ciudad', 'municipio', 'contacto', 'telefono', 'correo', 'create_date', 'update_date', 'delete_date',
            'paises', 'lineas','ubicaciones','areas','consultorios','turnos','programas','estudio_req','cantidad_niveles'], 'safe'],
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
        $query = Empresas::find();

        //dd(Yii::$app->user->identity);
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id', $empresas]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['razon'] = [
            'asc' => ['razon' => SORT_ASC],
            'desc' => ['razon' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['cantidad_niveles'] = [
            'asc' => ['cantidad_niveles' => SORT_ASC],
            'desc' => ['cantidad_niveles' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['nivel1'] = [
            'asc' => ['nivel1_all' => SORT_ASC],
            'desc' => ['nivel1_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nivel2'] = [
            'asc' => ['nivel2_all' => SORT_ASC],
            'desc' => ['nivel2_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nivel3'] = [
            'asc' => ['nivel3_all' => SORT_ASC],
            'desc' => ['nivel3_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nivel4'] = [
            'asc' => ['nivel4_all' => SORT_ASC],
            'desc' => ['nivel4_all' => SORT_DESC]
        ]; */

        /* $dataProvider->sort->attributes['areas'] = [
            'asc' => ['areas_all' => SORT_ASC],
            'desc' => ['areas_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['consultorios'] = [
            'asc' => ['consultorios_all' => SORT_ASC],
            'desc' => ['consultorios_all' => SORT_DESC]
        ];
 */
        $dataProvider->sort->attributes['contacto'] = [
            'asc' => ['contacto' => SORT_ASC],
            'desc' => ['contacto' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        //$query->orderBy(['id'=>SORT_DESC]); 

       
        //$empresa_preseleccionada = Yii::$app->user->identity->id_empresa;
        //$query->andFilterWhere(['in', 'id', $empresas]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'create_date' => $this->create_date,
            'create_user' => $this->create_user,
            'update_date' => $this->update_date,
            'update_user' => $this->update_user,
            'delete_date' => $this->delete_date,
            'delete_user' => $this->delete_user,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'abreviacion', $this->abreviacion])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'municipio', $this->municipio]);


            if($this->razon){
              $query->andFilterWhere([ 'OR' ,
              [ 'like' , 'razon' , $this->razon],
              [ 'like' , 'comercial' , $this->razon],
              [ 'like' , 'rfc' , $this->razon],
              ]);
            } else {
                if(Yii::$app->user->identity->empresa_all != 1){
                    $empresa_preseleccionada = Yii::$app->user->identity->id_empresa;
                    $empresas = explode(',', Yii::$app->user->identity->empresas_select);

                    if(count($empresas) == 1){
                        //dd(count($empresas));
                        $query->andFilterWhere(['id'=>$empresa_preseleccionada]);
                    }
                    
                }
            }


            if($this->cantidad_niveles){
                $query->andFilterWhere([ 'OR' ,
                    [ 'like' , 'label_nivel1' , $this->cantidad_niveles],
                    [ 'like' , 'label_nivel2' , $this->cantidad_niveles],
                    [ 'like' , 'label_nivel3' , $this->cantidad_niveles],
                    [ 'like' , 'label_nivel4' , $this->cantidad_niveles],
                ]);
            }

            if($this->contacto){
                $query->andFilterWhere([ 'OR' ,
                [ 'like' , 'contacto' , $this->contacto],
                [ 'like' , 'telefono' , $this->contacto],
                [ 'like' , 'correo' , $this->contacto],
                ]);
              }

              if($this->paises){
                $ids_empresas = [];
               
                $items = Paisempresa::find()->where(['id_pais'=>$this->paises])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->lineas){
                $ids_empresas = [];
               
                $items = Lineas::find()->where(['like','linea',$this->lineas])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->ubicaciones){
                $ids_empresas = [];
               
                $items = Ubicaciones::find()->where(['like','ubicacion',$this->ubicaciones])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }


            if($this->areas){
                $ids_empresas = [];
               
                $items = Areas::find()->where(['like','area',$this->areas])->all();
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->consultorios){
                $ids_empresas = [];
               
                $items = Consultorios::find()->where(['like','consultorio',$this->consultorios])->all();
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->turnos){
                $ids_empresas = [];
               
                $items = Turnos::find()->where(['like','turno',$this->turnos])->all();
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->programas){
                $ids_empresas = [];
               
                $items = Programaempresa::find()->where(['in','id_programa',$this->programas])->all();
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_empresas)){
                        array_push($ids_empresas, $item->id_empresa);
                    }
                }

                if(count($ids_empresas)>0){
                    $query->andFilterWhere(['in', 'id', $ids_empresas]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }


            if(isset($this->tipo_req) && $this->tipo_req != ''){
                $ids_requerimientos = [];
    
                $items = Requerimientoempresa::find()->where(['id_tipo'=>$this->tipo_req])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_requerimientos)){
                        array_push($ids_requerimientos, $item->id_empresa);
                    }
                }
    
                if(count($ids_requerimientos)>0){
                    $query->andFilterWhere(['in', 'id', $ids_requerimientos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }


            if($this->estudio_req){
                $ids_requerimientos = [];
               
                $items = Requerimientoempresa::find()->where(['in','id_estudio',$this->estudio_req])->all();
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_requerimientos)){
                        array_push($ids_requerimientos, $item->id_empresa);
                    }
                }
    
                if(count($ids_requerimientos)>0){
                    $query->andFilterWhere(['in', 'id', $ids_requerimientos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                } 
            }


            if(isset($this->periodicidad_req) && $this->periodicidad_req != ''){
                $ids_requerimientos = [];
    
                $items = Requerimientoempresa::find()->where(['id_periodicidad'=>$this->periodicidad_req])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_requerimientos)){
                        array_push($ids_requerimientos, $item->id_empresa);
                    }
                }
    
                if(count($ids_requerimientos)>0){
                    $query->andFilterWhere(['in', 'id', $ids_requerimientos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if(isset($this->status_req) && $this->status_req != ''){
                $ids_requerimientos = [];
    
                $items = Requerimientoempresa::find()->where(['id_status'=>$this->status_req])->all();
                
                foreach($items as $item){
                    if(!in_array($item->id_empresa, $ids_requerimientos)){
                        array_push($ids_requerimientos, $item->id_empresa);
                    }
                }
    
                if(count($ids_requerimientos)>0){
                    $query->andFilterWhere(['in', 'id', $ids_requerimientos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }
    

        return $dataProvider;
    }
}
