<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Trabajadores;
use Yii;

/**
 * TrabajadorespsSearch represents the model behind the search form of `app\models\Trabajadores`.
 */
class TrabajadorespsSearch extends Trabajadores
{
    public $programas_salud;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tipo_registro', 'id_cargamasiva', 'id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'id_pais', 'id_linea', 'id_ubicacion', 'tipo_examen', 'sexo', 'estado_civil', 'edad', 'turno', 'nivel_lectura', 'nivel_escritura', 'escolaridad', 'grupo', 'rh', 'tipo_contratacion', 'antiguedad_dias', 'antiguedad_meses', 'antiguedad_anios', 'id_puesto', 'id_area', 'talla_cabeza', 'talla_general', 'talla_superior', 'talla_inferior', 'talla_manos', 'talla_pies', 'create_user', 'update_user', 'delete_user', 'status_documentos', 'status', 'status_backup', 'soft_delete', 'origen_extraccion', 'id_origen', 'hidden', 'uso_consentimiento', 'retirar_consentimiento', 'acuerdo_confidencialidad', 'qty_trabajadores', 'estudios_pendientes', 'status_baja', 'status_cumplimiento', 'ps_status', 'ps_activos','programas_salud'], 'integer'],
            [['nombre', 'apellidos', 'foto', 'fecha_nacimiento', 'num_imss', 'curp', 'rfc', 'correo', 'celular', 'contacto_emergencia', 'direccion', 'colonia', 'pais', 'estado', 'ciudad', 'municipio', 'cp', 'num_trabajador', 'fecha_contratacion', 'fecha_baja', 'motivo_baja', 'antiguedad', 'ruta', 'parada', 'puesto_contable', 'fecha_iniciop', 'fecha_finp', 'teamleader', 'personalidad', 'dato_extra1', 'dato_extra2', 'dato_extra3', 'dato_extra4', 'dato_extra5', 'dato_extra6', 'dato_extra7', 'dato_extra8', 'dato_extra9', 'dato_extra10', 'create_date', 'update_date', 'delete_date', 'id_link', 'tipo_identificacion', 'numero_identificacion', 'ife', 'ife_reverso', 'foto_web', 'base64_foto', 'base64_ine', 'base64_inereverso', 'firma', 'firma_ruta', 'fecha', 'hora'], 'safe'],
            [['puesto_sueldo', 'puesto_cumplimiento', 'riesgo_cumplimiento', 'programasalud_cumplimiento', 'expediente_cumplimiento', 'hc_cumplimiento', 'poe_cumplimiento', 'cuestionario_cumplimiento', 'antropometrica_cumplimiento', 'programassalud_cumplimiento', 'porcentaje_cumplimiento'], 'number'],
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
        $query = Trabajadores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['ps_status' => SORT_ASC,'status' => SORT_ASC,'id' => SORT_DESC]]
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

        //$query->andFilterWhere(['ps_status'=>1]);

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
            'ps_status' => $this->ps_status,
            'ps_activos' => $this->ps_activos,
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

        if($this->programas_salud){
            $ids_ps = [];
               
            $items = ProgramaTrabajador::find()->where(['id_programa'=>$this->programas_salud])->select('id_trabajador')->distinct()->all();
            
            foreach($items as $item){
                if(!in_array($item->id_trabajador, $ids_ps)){
                    array_push($ids_ps, $item->id_trabajador);
                }
            }
    
            if(count($ids_ps)>0){
                $query->andFilterWhere(['in', 'id', $ids_ps]);
            }else{
                $query->andFilterWhere(['id'=>'0']);
            } 
        }

        return $dataProvider;
    }
}
