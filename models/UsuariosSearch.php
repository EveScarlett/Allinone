<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuarios;
use Yii;

/**
 * UsuariosSearch represents the model behind the search form of `app\models\Usuarios`.
 */
class UsuariosSearch extends Usuarios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rol', 'id_firma', 'id_empresa', 'empresas_todos', 'status','hidden'], 'integer'],
            [['name', 'username', 'firma', 'authKey', 'accessToken'], 'safe'],
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
        $query = Usuarios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status'=>SORT_ASC,'id'=>SORT_DESC]]
        ]);

        //$query->orderBy(['status'=>SORT_ASC,'id'=>SORT_DESC]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => ['id' => SORT_ASC],
            'desc' => ['id' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['name'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['username'] = [
            'asc' => ['username' => SORT_ASC],
            'desc' => ['username' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['rol'] = [
            'asc' => ['rol' => SORT_ASC],
            'desc' => ['rol' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['empresa_all'] = [
            'asc' => ['empresa_all' => SORT_ASC],
            'desc' => ['empresa_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['nivel1'] = [
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
        ];

        $dataProvider->sort->attributes['areas'] = [
            'asc' => ['areas_all' => SORT_ASC],
            'desc' => ['areas_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['consultorios'] = [
            'asc' => ['consultorios_all' => SORT_ASC],
            'desc' => ['consultorios_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['programas'] = [
            'asc' => ['programas_all' => SORT_ASC],
            'desc' => ['programas_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['permisos_all'] = [
            'asc' => ['permisos_all' => SORT_ASC],
            'desc' => ['permisos_all' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['firma'] = [
            'asc' => ['firma' => SORT_ASC],
            'desc' => ['firma' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['last_login'] = [
            'asc' => ['last_login' => SORT_ASC],
            'desc' => ['last_login' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];



        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        
        if(Yii::$app->user->identity->empresa_all != 1) {
            $query->andFilterWhere(['in', 'id_empresa', $empresas]);
        }

        if(Yii::$app->user->identity->hidden != 1){
            //dd('Entra aqui');
            $query->andFilterWhere(['hidden'=>0]);
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
            'rol' => $this->rol,
            'id_firma' => $this->id_firma,
            'id_empresa' => $this->id_empresa,
            'empresas_todos' => $this->empresas_todos,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'firma', $this->firma])
            ->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken])
            ;

        if($this->last_login){
            $fechas = explode(' - ', $this->last_login);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'last_login', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }
}
