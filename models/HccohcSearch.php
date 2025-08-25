<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Hccohc;
use kartik\daterange\DateRangeBehavior;
use Yii;

/**
 * HccOhcSearch represents the model behind the search form of `app\models\HccOhc`.
 */
class HccohcSearch extends Hccohc
{
    public $condicion;
    public $timeStart;
    public $timeEnd;

    public $edad_inicio;
    public $edad_fin;

    public $edad_actual;

    public $edadactual_inicio;
    public $edadactual_fin;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'sexo', 'edad', 'estado_civil', 'nivel_lectura', 'nivel_escritura', 'dosis_vacunacion', 'peso', 'inspeccion_otros', 'cabeza_otros', 'oidos_otros', 'ojos_otros', 'boca_otros', 'cuello_otros', 'torax_otros', 'abdomen_otros', 'miembrossup_otros', 'miembrosinf_otros', 'columna_otros', 'neurologicos_otros', 'status', 'firma_medicolaboral','condicion'], 'integer'],
            [['folio', 'fecha', 'hora', 'examen', 'area', 'puesto', 'nombre', 'apellidos', 'fecha_nacimiento', 'grupo', 'rh', 'diabetess', 'diabetesstxt', 'hipertension', 'hipertensiontxt', 'cancer', 'cancertxt', 'nefropatias', 'nefropatiastxt', 'cardiopatias', 'cardiopatiastxt', 'reuma', 'reumatxt', 'hepa', 'hepatxt', 'tuber', 'tubertxt', 'psi', 'psitxt', 'tabaquismo', 'tabdesde', 'tabfrec', 'tabcantidad', 'alcoholismo', 'alcodesde', 'alcofrec', 'alcocantidad', 'cocina', 'cocinadesde', 'audifonos', 'audiodesde', 'audiocuando', 'droga', 'drogatxt', 'duracion_droga', 'fecha_droga', 'vacunacion_cov', 'nombre_vacunacion', 'fecha_vacunacion', 'mano', 'alergias', 'alergiastxt', 'asma', 'asmatxt', 'asma_anio', 'cardio', 'cardiotxt', 'cirugias', 'cirugiastxt', 'convulsiones', 'convulsionestxt', 'diabetes', 'diabetestxt', 'hiper', 'hipertxt', 'lumbalgias', 'lumbalgiastxt', 'nefro', 'nefrotxt', 'polio', 'politxt', 'poliomelitis_anio', 'saram', 'saram_anio', 'pulmo', 'pulmotxt', 'hematicos', 'hematicostxt', 'trauma', 'traumatxt', 'medicamentos', 'medicamentostxt', 'protesis', 'protesistxt', 'trans', 'transtxt', 'enf_ocular', 'enf_ocular_txt', 'enf_auditiva', 'enf_auditiva_txt', 'fractura', 'fractura_txt', 'amputacion', 'amputacion_txt', 'hernias', 'hernias_txt', 'enfsanguinea', 'enfsanguinea_txt', 'tumorescancer', 'tumorescancer_txt', 'enfpsico', 'enfpsico_txt', 'gestas', 'partos', 'abortos', 'cesareas', 'menarca', 'ivsa', 'fum', 'mpf', 'doc', 'docma', 'imc', 'categoria_imc', 'fc', 'fr', 'temp', 'ta', 'ta_diastolica', 'caries_rd', 'inspeccion', 'txt_inspeccion_otros', 'cabeza', 'txt_cabeza_otros', 'oidos', 'txt_oidos_otros', 'ojos', 'txt_ojos_otros', 'sLentes', 'sLentesD', 'cLentes', 'cLentesD', 'Rlentes', 'Ulentes', 'boca', 'txt_boca_otros', 'cuello', 'txt_cuello_otros', 'torax', 'txt_torax_otros', 'abdomen', 'txt_abdomen_otros', 'superior', 'txt_miembrossup_otros', 'inferior', 'txt_miembrosinf_otros', 'columna', 'txt_columna_otros', 'txtneurologicos', 'txt_neurologicos_otros', 'diagnostico', 'comentarios', 'conclusion', 'medico', 'id_trabajador','num_trabajador','id_nivel1','id_nivel2','id_nivel3','id_nivel4','id_medico'], 'safe'],
            [['talla','edad_inicio','edad_fin','edad_actual','edadactual_inicio','edadactual_fin'], 'number'],
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
        $query = HccOhc::find();

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

        $dataProvider->sort->attributes['num_trabajador'] = [
            'asc' => ['num_trabajador' => SORT_ASC],
            'desc' => ['num_trabajador' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['id_trabajador'] = [
            'asc' => ['apellidos' => SORT_ASC],
            'desc' => ['apellidos' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['edad'] = [
            'asc' => ['edad' => SORT_ASC],
            'desc' => ['edad' => SORT_DESC]
        ];

        /* $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['id_empresa' => SORT_ASC],
            'desc' => ['id_empresa' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['id_empresa'] = [
            'asc' => ['nombre_empresa' => SORT_ASC],
            'desc' => ['nombre_empresa' => SORT_DESC]
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

        /* $dataProvider->sort->attributes['id_medico'] = [
            'asc' => ['id_medico' => SORT_ASC],
            'desc' => ['id_medico' => SORT_DESC]
        ]; */

        $dataProvider->sort->attributes['id_medico'] = [
            'asc' => ['nombre_medico' => SORT_ASC],
            'desc' => ['nombre_medico' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['fecha'] = [
            'asc' => ['fecha' => SORT_ASC],
            'desc' => ['fecha' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['examen'] = [
            'asc' => ['examen' => SORT_ASC],
            'desc' => ['examen' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['status' => SORT_ASC],
            'desc' => ['status' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['conclusion'] = [
            'asc' => ['conclusion' => SORT_ASC],
            'desc' => ['conclusion' => SORT_DESC]
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
        

        $query->andFilterWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')]);

        $this->load($params);


        $dataProvider->sort->attributes['edad'] = [
            'asc' => ['edad' => SORT_ASC],
            'desc' => ['edad' => SORT_DESC]
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'hora' => $this->hora,
            'sexo' => $this->sexo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'edad' => $this->edad,
            'estado_civil' => $this->estado_civil,
            'nivel_lectura' => $this->nivel_lectura,
            'nivel_escritura' => $this->nivel_escritura,
            'dosis_vacunacion' => $this->dosis_vacunacion,
            'fecha_vacunacion' => $this->fecha_vacunacion,
            'peso' => $this->peso,
            'talla' => $this->talla,
            'inspeccion_otros' => $this->inspeccion_otros,
            'cabeza_otros' => $this->cabeza_otros,
            'oidos_otros' => $this->oidos_otros,
            'ojos_otros' => $this->ojos_otros,
            'boca_otros' => $this->boca_otros,
            'cuello_otros' => $this->cuello_otros,
            'torax_otros' => $this->torax_otros,
            'abdomen_otros' => $this->abdomen_otros,
            'miembrossup_otros' => $this->miembrossup_otros,
            'miembrosinf_otros' => $this->miembrosinf_otros,
            'columna_otros' => $this->columna_otros,
            'neurologicos_otros' => $this->neurologicos_otros,
            'status' => $this->status,
            'firma_medicolaboral' => $this->firma_medicolaboral,
        ]);

        $query->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'num_trabajador', $this->num_trabajador])
            ->andFilterWhere(['like', 'examen', $this->examen])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'puesto', $this->puesto])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'grupo', $this->grupo])
            ->andFilterWhere(['like', 'rh', $this->rh])
            ->andFilterWhere(['like', 'diabetess', $this->diabetess])
            ->andFilterWhere(['like', 'diabetesstxt', $this->diabetesstxt])
            ->andFilterWhere(['like', 'hipertension', $this->hipertension])
            ->andFilterWhere(['like', 'hipertensiontxt', $this->hipertensiontxt])
            ->andFilterWhere(['like', 'cancer', $this->cancer])
            ->andFilterWhere(['like', 'cancertxt', $this->cancertxt])
            ->andFilterWhere(['like', 'nefropatias', $this->nefropatias])
            ->andFilterWhere(['like', 'nefropatiastxt', $this->nefropatiastxt])
            ->andFilterWhere(['like', 'cardiopatias', $this->cardiopatias])
            ->andFilterWhere(['like', 'cardiopatiastxt', $this->cardiopatiastxt])
            ->andFilterWhere(['like', 'reuma', $this->reuma])
            ->andFilterWhere(['like', 'reumatxt', $this->reumatxt])
            ->andFilterWhere(['like', 'hepa', $this->hepa])
            ->andFilterWhere(['like', 'hepatxt', $this->hepatxt])
            ->andFilterWhere(['like', 'tuber', $this->tuber])
            ->andFilterWhere(['like', 'tubertxt', $this->tubertxt])
            ->andFilterWhere(['like', 'psi', $this->psi])
            ->andFilterWhere(['like', 'psitxt', $this->psitxt])
            ->andFilterWhere(['like', 'tabaquismo', $this->tabaquismo])
            ->andFilterWhere(['like', 'tabdesde', $this->tabdesde])
            ->andFilterWhere(['like', 'tabfrec', $this->tabfrec])
            ->andFilterWhere(['like', 'tabcantidad', $this->tabcantidad])
            ->andFilterWhere(['like', 'alcoholismo', $this->alcoholismo])
            ->andFilterWhere(['like', 'alcodesde', $this->alcodesde])
            ->andFilterWhere(['like', 'alcofrec', $this->alcofrec])
            ->andFilterWhere(['like', 'alcocantidad', $this->alcocantidad])
            ->andFilterWhere(['like', 'cocina', $this->cocina])
            ->andFilterWhere(['like', 'cocinadesde', $this->cocinadesde])
            ->andFilterWhere(['like', 'audifonos', $this->audifonos])
            ->andFilterWhere(['like', 'audiodesde', $this->audiodesde])
            ->andFilterWhere(['like', 'audiocuando', $this->audiocuando])
            ->andFilterWhere(['like', 'droga', $this->droga])
            ->andFilterWhere(['like', 'drogatxt', $this->drogatxt])
            ->andFilterWhere(['like', 'duracion_droga', $this->duracion_droga])
            ->andFilterWhere(['like', 'fecha_droga', $this->fecha_droga])
            ->andFilterWhere(['like', 'vacunacion_cov', $this->vacunacion_cov])
            ->andFilterWhere(['like', 'nombre_vacunacion', $this->nombre_vacunacion])
            ->andFilterWhere(['like', 'mano', $this->mano])
            ->andFilterWhere(['like', 'alergias', $this->alergias])
            ->andFilterWhere(['like', 'alergiastxt', $this->alergiastxt])
            ->andFilterWhere(['like', 'asma', $this->asma])
            ->andFilterWhere(['like', 'asmatxt', $this->asmatxt])
            ->andFilterWhere(['like', 'asma_anio', $this->asma_anio])
            ->andFilterWhere(['like', 'cardio', $this->cardio])
            ->andFilterWhere(['like', 'cardiotxt', $this->cardiotxt])
            ->andFilterWhere(['like', 'cirugias', $this->cirugias])
            ->andFilterWhere(['like', 'cirugiastxt', $this->cirugiastxt])
            ->andFilterWhere(['like', 'convulsiones', $this->convulsiones])
            ->andFilterWhere(['like', 'convulsionestxt', $this->convulsionestxt])
            ->andFilterWhere(['like', 'diabetes', $this->diabetes])
            ->andFilterWhere(['like', 'diabetestxt', $this->diabetestxt])
            ->andFilterWhere(['like', 'hiper', $this->hiper])
            ->andFilterWhere(['like', 'hipertxt', $this->hipertxt])
            ->andFilterWhere(['like', 'lumbalgias', $this->lumbalgias])
            ->andFilterWhere(['like', 'lumbalgiastxt', $this->lumbalgiastxt])
            ->andFilterWhere(['like', 'nefro', $this->nefro])
            ->andFilterWhere(['like', 'nefrotxt', $this->nefrotxt])
            ->andFilterWhere(['like', 'polio', $this->polio])
            ->andFilterWhere(['like', 'politxt', $this->politxt])
            ->andFilterWhere(['like', 'poliomelitis_anio', $this->poliomelitis_anio])
            ->andFilterWhere(['like', 'saram', $this->saram])
            ->andFilterWhere(['like', 'saram_anio', $this->saram_anio])
            ->andFilterWhere(['like', 'pulmo', $this->pulmo])
            ->andFilterWhere(['like', 'pulmotxt', $this->pulmotxt])
            ->andFilterWhere(['like', 'hematicos', $this->hematicos])
            ->andFilterWhere(['like', 'hematicostxt', $this->hematicostxt])
            ->andFilterWhere(['like', 'trauma', $this->trauma])
            ->andFilterWhere(['like', 'traumatxt', $this->traumatxt])
            ->andFilterWhere(['like', 'medicamentos', $this->medicamentos])
            ->andFilterWhere(['like', 'medicamentostxt', $this->medicamentostxt])
            ->andFilterWhere(['like', 'protesis', $this->protesis])
            ->andFilterWhere(['like', 'protesistxt', $this->protesistxt])
            ->andFilterWhere(['like', 'trans', $this->trans])
            ->andFilterWhere(['like', 'transtxt', $this->transtxt])
            ->andFilterWhere(['like', 'enf_ocular', $this->enf_ocular])
            ->andFilterWhere(['like', 'enf_ocular_txt', $this->enf_ocular_txt])
            ->andFilterWhere(['like', 'enf_auditiva', $this->enf_auditiva])
            ->andFilterWhere(['like', 'enf_auditiva_txt', $this->enf_auditiva_txt])
            ->andFilterWhere(['like', 'fractura', $this->fractura])
            ->andFilterWhere(['like', 'fractura_txt', $this->fractura_txt])
            ->andFilterWhere(['like', 'amputacion', $this->amputacion])
            ->andFilterWhere(['like', 'amputacion_txt', $this->amputacion_txt])
            ->andFilterWhere(['like', 'hernias', $this->hernias])
            ->andFilterWhere(['like', 'hernias_txt', $this->hernias_txt])
            ->andFilterWhere(['like', 'enfsanguinea', $this->enfsanguinea])
            ->andFilterWhere(['like', 'enfsanguinea_txt', $this->enfsanguinea_txt])
            ->andFilterWhere(['like', 'tumorescancer', $this->tumorescancer])
            ->andFilterWhere(['like', 'tumorescancer_txt', $this->tumorescancer_txt])
            ->andFilterWhere(['like', 'enfpsico', $this->enfpsico])
            ->andFilterWhere(['like', 'enfpsico_txt', $this->enfpsico_txt])
            ->andFilterWhere(['like', 'gestas', $this->gestas])
            ->andFilterWhere(['like', 'partos', $this->partos])
            ->andFilterWhere(['like', 'abortos', $this->abortos])
            ->andFilterWhere(['like', 'cesareas', $this->cesareas])
            ->andFilterWhere(['like', 'menarca', $this->menarca])
            ->andFilterWhere(['like', 'ivsa', $this->ivsa])
            ->andFilterWhere(['like', 'fum', $this->fum])
            ->andFilterWhere(['like', 'mpf', $this->mpf])
            ->andFilterWhere(['like', 'doc', $this->doc])
            ->andFilterWhere(['like', 'docma', $this->docma])
            ->andFilterWhere(['like', 'imc', $this->imc])
            ->andFilterWhere(['like', 'categoria_imc', $this->categoria_imc])
            ->andFilterWhere(['like', 'fc', $this->fc])
            ->andFilterWhere(['like', 'fr', $this->fr])
            ->andFilterWhere(['like', 'temp', $this->temp])
            ->andFilterWhere(['like', 'ta', $this->ta])
            ->andFilterWhere(['like', 'ta_diastolica', $this->ta_diastolica])
            ->andFilterWhere(['like', 'caries_rd', $this->caries_rd])
            ->andFilterWhere(['like', 'inspeccion', $this->inspeccion])
            ->andFilterWhere(['like', 'txt_inspeccion_otros', $this->txt_inspeccion_otros])
            ->andFilterWhere(['like', 'cabeza', $this->cabeza])
            ->andFilterWhere(['like', 'txt_cabeza_otros', $this->txt_cabeza_otros])
            ->andFilterWhere(['like', 'oidos', $this->oidos])
            ->andFilterWhere(['like', 'txt_oidos_otros', $this->txt_oidos_otros])
            ->andFilterWhere(['like', 'ojos', $this->ojos])
            ->andFilterWhere(['like', 'txt_ojos_otros', $this->txt_ojos_otros])
            ->andFilterWhere(['like', 'sLentes', $this->sLentes])
            ->andFilterWhere(['like', 'sLentesD', $this->sLentesD])
            ->andFilterWhere(['like', 'cLentes', $this->cLentes])
            ->andFilterWhere(['like', 'cLentesD', $this->cLentesD])
            ->andFilterWhere(['like', 'Rlentes', $this->Rlentes])
            ->andFilterWhere(['like', 'Ulentes', $this->Ulentes])
            ->andFilterWhere(['like', 'boca', $this->boca])
            ->andFilterWhere(['like', 'txt_boca_otros', $this->txt_boca_otros])
            ->andFilterWhere(['like', 'cuello', $this->cuello])
            ->andFilterWhere(['like', 'txt_cuello_otros', $this->txt_cuello_otros])
            ->andFilterWhere(['like', 'torax', $this->torax])
            ->andFilterWhere(['like', 'txt_torax_otros', $this->txt_torax_otros])
            ->andFilterWhere(['like', 'abdomen', $this->abdomen])
            ->andFilterWhere(['like', 'txt_abdomen_otros', $this->txt_abdomen_otros])
            ->andFilterWhere(['like', 'superior', $this->superior])
            ->andFilterWhere(['like', 'txt_miembrossup_otros', $this->txt_miembrossup_otros])
            ->andFilterWhere(['like', 'inferior', $this->inferior])
            ->andFilterWhere(['like', 'txt_miembrosinf_otros', $this->txt_miembrosinf_otros])
            ->andFilterWhere(['like', 'columna', $this->columna])
            ->andFilterWhere(['like', 'txt_columna_otros', $this->txt_columna_otros])
            ->andFilterWhere(['like', 'txtneurologicos', $this->txtneurologicos])
            ->andFilterWhere(['like', 'txt_neurologicos_otros', $this->txt_neurologicos_otros])
            ->andFilterWhere(['like', 'diagnostico', $this->diagnostico])
            ->andFilterWhere(['like', 'comentarios', $this->comentarios])
            ->andFilterWhere(['like', 'conclusion', $this->conclusion])
            ->andFilterWhere(['like', 'medico', $this->medico]);


            if($this->edad_inicio && $this->edad_fin){
                $query->andWhere(['between', 'edad', intval($this->edad_inicio), intval($this->edad_fin)]);
            }

            if($this->edadactual_inicio && $this->edadactual_fin){
                $ids_trabajadores = [];
               
                $items = Trabajadores::find()->where(['between', 'edad', intval($this->edadactual_inicio), intval($this->edadactual_fin)])->all();
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


            if($this->id_medico){
                $ids_medicos = [];
               
                $items = Usuarios::find()->where(['like', 'name', $this->id_medico])->all();
                foreach($items as $item){
                    if(!in_array($item->id, $ids_medicos)){
                        array_push($ids_medicos, $item->id);
                    }
                }
    
                if(count($ids_medicos)>0){
                    $query->andFilterWhere(['in', 'id_medico', $ids_medicos]);
                }else{
                    $query->andFilterWhere(['id'=>'0']);
                }  
            }

            if($this->condicion){
                //dd($this->condicion);
                $ids_trabajadores = [];
    
                if($this->condicion == 3){
                    $items = Trabajadores::find()->where(['status'=>3])->all();
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

            /* if ($this->timeStart) {
                $query->andWhere(['>=', "fecha", $this->timeToUTC($this->timeStart)]);
            }
            if ($this->timeStart) {
                $query->andWhere(['<=', "fecha", $this->timeToUTC($this->timeEnd)]);
            } */


        if($this->fecha){
            $fechas = explode(' - ', $this->fecha);
            $fechas[0] = $fechas[0].' 00:00:00';
            $fechas[1] = $fechas[1].' 23:59:59';
            $query->andWhere(['between', 'fecha', $fechas[0], $fechas[1]]);
        }

        return $dataProvider;
    }

    private function timeToUTC($time, $format='Y-m-d')
    {
        $timezoneOffset = \Yii::$app->formatter->asDate('now', 'php:O');
        return date($format, strtotime($time.$timezoneOffset));
    }
}