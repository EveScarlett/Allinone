<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lineas;
use yii\helpers\ArrayHelper;
use Yii;
/**
 * LineasSearch represents the model behind the search form of `app\models\Lineas`.
 */
class LineasSearch extends Lineas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_pais', 'status', 'soft_delete'], 'integer'],
            [['linea'], 'safe'],
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
        $query = Lineas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy(['id'=>SORT_DESC]);

        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
            $id_empresas_usuario = explode(',', Yii::$app->user->identity->empresas_select);
        } else {
            $array_empresas = Empresas::find()->orderBy('comercial')->all();
            $id_empresas_usuario = [];
            foreach($array_empresas as $key=>$data){
                array_push($id_empresas_usuario, $data->id);
            }
        }


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
        $niveles_2 = explode(',', Yii::$app->user->identity->nivel2_select);
        if(Yii::$app->user->identity->nivel2_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel2', $niveles_2]);
        }
        $niveles_3 = explode(',', Yii::$app->user->identity->nivel3_select);
        if(Yii::$app->user->identity->nivel3_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel3', $niveles_3]);
        }
        $niveles_4 = explode(',', Yii::$app->user->identity->nivel4_select);
        if(Yii::$app->user->identity->nivel4_all != 1) {
            $query->andFilterWhere(['in', 'id_nivel4', $niveles_4]);
        }
        
        $id_paises_usuario = [];
        $id_lineas_usuario = [];
        $id_ubicaciones_usuario = [];


        if(Yii::$app->user->identity->empresa_all != 1) { //Seleccionar solo los paises segun las empresas seleccionadas
            $paisempresa = Paisempresa::find()->where(['in','id_empresa',$id_empresas_usuario])->select(['id_pais'])->distinct()->all(); 
        } else { // Seleccionar todos los paises
            $paisempresa = Paisempresa::find()->select(['id_pais'])->distinct()->all(); 
        }
        
        $id_paises = [];
        foreach($paisempresa as $key=>$pais){
            array_push($id_paises, $pais->id_pais);
        }
        $paises = ArrayHelper::map(Paises::find()->where(['in','id',$id_paises])->orderBy('pais')->all(), 'id', 'pais');
        
        if(Yii::$app->user->identity->empresa_all != 1) {//Si solo puede ver ciertas empresas, mostrar solo las lineas de esas empresas
            if(Yii::$app->user->identity->paises_all != 1) {//Seleccionar solo los paises segun las empresas seleccionadas
                $lineas = ArrayHelper::map(Lineas::find()->where(['in','id_empresa',$id_empresas_usuario])->andWhere(['in','id_pais',$id_paises_usuario])->orderBy('linea')->all(), 'id', function($data){
                $rest = ' [';
                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['linea'].$rest;
            });
            } else {// Si puede ver todos los paises
            $lineas = ArrayHelper::map(Lineas::find()->where(['in','id_empresa',$id_empresas_usuario])->andWhere(['in','id_pais',$id_paises])->orderBy('linea')->all(), 'id', function($data){
                $rest = ' [';
                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['linea'].$rest;
            });
          }

        } else { //Si puede ver todas las empresas
            
          if(Yii::$app->user->identity->paises_all != 1) {//Seleccionar solo los paises segun las empresas seleccionadas
            $lineas = ArrayHelper::map(Lineas::find()->where(['in','id_empresa',$id_empresas_usuario])->andWhere(['in','id_pais',$id_paises_usuario])->orderBy('linea')->all(), 'id', function($data){
                $rest = ' [';
                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['linea'].$rest;
            });
          } else {
            $lineas = ArrayHelper::map(Lineas::find()->where(['in','id_empresa',$id_empresas_usuario])->andWhere(['in','id_pais',$id_paises])->orderBy('linea')->all(), 'id', function($data){
                $rest = ' [';
                if($data->pais){
                    $rest .= $data->pais->pais.' - ';
                }
                if($data->empresa){
                    $rest .= $data->empresa->comercial;
                }
                $rest .= ']';
                return $data['linea'].$rest;
            });
          }
        }

        $id_lineas = [];
        if($lineas){
            foreach($lineas as $key=>$line){
                array_push($id_lineas, $key);
            }
        }

        $query->andFilterWhere(['in', 'id', $id_lineas]);
        //dd($lineas);


        

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
            'id_pais' => $this->id_pais,
            'status' => $this->status,
            'soft_delete' => $this->soft_delete,
        ]);

        $query->andFilterWhere(['like', 'linea', $this->linea]);

        return $dataProvider;
    }
}
