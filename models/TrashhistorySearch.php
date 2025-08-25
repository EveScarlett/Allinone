<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Trashhistory;
use Yii;
/**
 * TrashhistorySearch represents the model behind the search form of `app\models\Trashhistory`.
 */
class TrashhistorySearch extends Trashhistory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_model','id_empresa'], 'integer'],
            [['model', 'restored_date', 'restored_user', 'deleted_date', 'deleted_user','id_nivel1','id_nivel2','id_nivel3','id_nivel4','contenido'], 'safe'],
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
        $query = Trashhistory::find();

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

        $dataProvider->sort->attributes['model'] = [
            'asc' => ['model' => SORT_ASC],
            'desc' => ['model' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_model'] = [
            'asc' => ['id_model' => SORT_ASC],
            'desc' => ['id_model' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['deleted_date'] = [
            'asc' => ['deleted_date' => SORT_ASC],
            'desc' => ['deleted_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['deleted_user'] = [
            'asc' => ['deleted_user' => SORT_ASC],
            'desc' => ['deleted_user' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['restored_date'] = [
            'asc' => ['restored_date' => SORT_ASC],
            'desc' => ['restored_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['restored_user'] = [
            'asc' => ['restored_user' => SORT_ASC],
            'desc' => ['restored_user' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['tiempo_transcurrido'] = [
            'asc' => ['deleted_date' => SORT_ASC],
            'desc' => ['deleted_date' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['contenido'] = [
            'asc' => ['contenido' => SORT_ASC],
            'desc' => ['contenido' => SORT_DESC]
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
            'id_model' => $this->id_model,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model]);

        $query->andFilterWhere(['like', 'contenido', $this->contenido]);

        if($this->deleted_date){
            $fechas = explode(' - ', $this->deleted_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'deleted_date', $fechas[0], $fechas[1]]);
        }

        if($this->restored_date){
            $fechas = explode(' - ', $this->restored_date);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'restored_date', $fechas[0], $fechas[1]]);
        }


        if($this->deleted_user){
            //dd($this->deleted_user);
            $ids_users = [];
               
            $items = Usuarios::find()->where(['like', 'name', $this->deleted_user])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_users)){
                    array_push($ids_users, $item->id);
                }
            }
    
            if(count($ids_users)>0){
                $query->andFilterWhere(['in', 'deleted_user', $ids_users]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }


        if($this->restored_user){
            //dd($this->deleted_user);
            $ids_users = [];
               
            $items = Usuarios::find()->where(['like', 'name', $this->restored_user])->all();
            foreach($items as $item){
                if(!in_array($item->id, $ids_users)){
                    array_push($ids_users, $item->id);
                }
            }
    
            if(count($ids_users)>0){
                $query->andFilterWhere(['in', 'restored_user', $ids_users]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            }  
        }

        return $dataProvider;
    }
}
