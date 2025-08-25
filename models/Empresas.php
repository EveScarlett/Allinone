<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresas".
 *
 * @property int $id
 * @property string|null $razon
 * @property string|null $comercial
 * @property string|null $abreviacion
 * @property string|null $rfc
 * @property string|null $pais
 * @property string|null $estado
 * @property string|null $ciudad
 * @property string|null $municipio
 * @property string|null $logo
 * @property string|null $contacto
 * @property string|null $telefono
 * @property string|null $correo
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property int|null $status
 */
class Empresas extends \yii\db\ActiveRecord
{
    public $file_logo;
    public $aux_paises = [];
    public $aux_lineas = [];
    public $aux_ubicaciones = [];
    public $aux_areas = [];
    public $aux_consultorios = [];
    public $aux_turnos = [];
    public $aux_programas = [];
    public $aux_requisitos = [];
    public $aux_mails = [];

    public $lunes_inicio = [];
    public $martes_inicio = [];
    public $miercoles_inicio = [];
    public $jueves_inicio = [];
    public $viernes_inicio = [];
    public $sabado_inicio = [];
    public $domingo_inicio = [];

    public $lunes_fin = [];
    public $martes_fin = [];
    public $miercoles_fin = [];
    public $jueves_fin = [];
    public $viernes_fin = [];
    public $sabado_fin = [];
    public $domingo_fin = [];

    public $requiere_enfermeros = [];
    public $requiere_medicos = [];
    public $requiere_extras = [];

    public $cantidad_enfermeros = [];
    public $cantidad_medicos = [];
    public $cantidad_extras = [];

    public $aux_nivel1 = [];
    public $aux_nivel2 = [];
    public $aux_nivel3 = [];
    public $aux_nivel4 = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_logo'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['create_date', 'update_date', 'delete_date','aux_paises','aux_lineas','aux_ubicaciones','aux_areas','aux_consultorios','aux_turnos','aux_programas','aux_requisitos',
              'lunes_inicio','martes_inicio','miercoles_inicio','jueves_inicio','viernes_inicio','sabado_inicio','domingo_inicio',
              'lunes_fin','martes_fin','miercoles_fin','jueves_fin','viernes_fin','sabado_fin','domingo_fin','aux_mails','aux_nivel1','aux_nivel2','aux_nivel3','aux_nivel4'], 'safe'],
            [['create_user', 'update_user', 'delete_user', 'status','medico_laboral','cantidad_niveles','nivel1','nivel2','nivel3','nivel4','id_kpi1','id_kpi2','id_kpi3','id_kpi4','id_kpi5','id_kpi6','id_kpi7','id_kpi8','id_kpi9','id_kpi10','qty_trabajadores','mostrar_nivel_pdf'], 'integer'],
              [['lunes_inicio','lunes_fin','martes_inicio','martes_fin','miercoles_inicio','miercoles_fin','jueves_inicio','jueves_fin',
              'viernes_inicio','viernes_fin','sabado_inicio','sabado_fin','domingo_inicio','domingo_fin',
              'requiere_enfermeros','requiere_medicos','requiere_extras','cantidad_enfermeros','cantidad_medicos','cantidad_extras'], 'safe'],
            [['razon', 'comercial', 'contacto','label_nivel1','label_nivel2','label_nivel3','label_nivel4'], 'string', 'max' => 300],
            [['abreviacion'], 'string', 'max' => 5],
            [['rfc', 'telefono'], 'string', 'max' => 30],
            [['pais', 'estado', 'ciudad', 'municipio', 'logo', 'correo','correo_privacidad'], 'string', 'max' => 100],
            [['horario'], 'string', 'max' => 500],
            [['direccion','actividad'], 'string', 'max' => 1000],
            [['razon', 'comercial','abreviacion','status','cantidad_niveles','mostrar_nivel_pdf'], 'required','on' =>['create','update']],
            [['id'], 'required','on' =>['requerimientos']],

            [['label_nivel1','nivel1'], 'required', 'when' => function($model) {
                return $model->cantidad_niveles == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#empresas-cantidad_niveles').val() == 1;
            }"],
            [['label_nivel1','nivel1','label_nivel2','nivel2'], 'required', 'when' => function($model) {
                return $model->cantidad_niveles == 2;
            }, 'whenClient' => "function (attribute, value) {
                return $('#empresas-cantidad_niveles').val() == 2;
            }"],
            [['label_nivel1','nivel1','label_nivel2','nivel2','label_nivel3','nivel3'], 'required', 'when' => function($model) {
                return $model->cantidad_niveles == 3;
            }, 'whenClient' => "function (attribute, value) {
                return $('#empresas-cantidad_niveles').val() == 3;
            }"],
            [['label_nivel1','nivel1','label_nivel2','nivel2','label_nivel3','nivel3','label_nivel4','nivel4'], 'required', 'when' => function($model) {
                return $model->cantidad_niveles == 4;
            }, 'whenClient' => "function (attribute, value) {
                return $('#empresas-cantidad_niveles').val() == 4;
            }"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'razon' => Yii::t('app', 'Razón Social'),
            'comercial' => Yii::t('app', 'Nombre Comercial'),
            'abreviacion' => Yii::t('app', 'Abreviación'),
            'rfc' => Yii::t('app', 'Rfc'),
            'pais' => Yii::t('app', 'País'),
            'estado' => Yii::t('app', 'Estado'),
            'ciudad' => Yii::t('app', 'Ciudad'),
            'municipio' => Yii::t('app', 'Municipio'),
            'logo' => Yii::t('app', 'Logo'),
            'contacto' => Yii::t('app', 'Contacto'),
            'telefono' => Yii::t('app', 'Teléfono'),
            'correo' => Yii::t('app', 'Correo'),
            'direccion' => Yii::t('app', 'Dirección - Aviso se Privacidad'),
            'actividad' => Yii::t('app', 'Actividad de la Empresa'),
            'correo_privacidad' => Yii::t('app', 'Correo - Aviso se Privacidad'),
            'cantidad_niveles' => Yii::t('app', 'Cantidad de Niveles'),
            'nivel1' => Yii::t('app', 'Nivel 1'),
            'nivel2' => Yii::t('app', 'Nivel 2'),
            'nivel3' => Yii::t('app', 'Nivel 3'),
            'nivel4' => Yii::t('app', 'Nivel 4'),
            'aux_nivel1' => Yii::t('app', 'Nivel 1'),
            'aux_nivel2' => Yii::t('app', 'Nivel 2'),
            'aux_nivel3' => Yii::t('app', 'Nivel 3'),
            'aux_nivel4' => Yii::t('app', 'Nivel 4'),
            'label_nivel1' => Yii::t('app', 'Etiqueta Nivel 1'),
            'label_nivel2' => Yii::t('app', 'Etiqueta Nivel 2'),
            'label_nivel3' => Yii::t('app', 'Etiqueta Nivel 3'),
            'label_nivel4' => Yii::t('app', 'Etiqueta Nivel 4'),
            'create_date' => Yii::t('app', 'Fecha Creación'),
            'create_user' => Yii::t('app', 'Quien Creó'),
            'update_date' => Yii::t('app', 'Fecha Actualización'),
            'update_user' => Yii::t('app', 'Quien Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Eliminación'),
            'delete_user' => Yii::t('app', 'Quien Eliminó'),
            'medico_laboral' => Yii::t('app', 'Médico Laboral'),
            'id_kpi1' => Yii::t('app', 'KPI 1'),
            'id_kpi2' => Yii::t('app', 'KPI 2'),
            'id_kpi3' => Yii::t('app', 'KPI 3'),
            'id_kpi4' => Yii::t('app', 'KPI 4'),
            'id_kpi5' => Yii::t('app', 'KPI 5'),
            'id_kpi6' => Yii::t('app', 'KPI 6'),
            'id_kpi7' => Yii::t('app', 'KPI 7'),
            'id_kpi8' => Yii::t('app', 'KPI 8'),
            'id_kpi9' => Yii::t('app', 'KPI 9'),
            'id_kpi10' => Yii::t('app', 'KPI 10'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadores()
    {
        return $this->hasMany(Trabajadores::className(), ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadoresactivos()
    {
        return $this->hasMany(Trabajadores::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicaciones()
    {
        return $this->hasMany(Ubicaciones::className(), ['id_empresa' => 'id']);
    }

    public function getLineas()
    {
        return $this->hasMany(Lineas::className(), ['id_empresa' => 'id']);
    }

    public function getPaises()
    {
        return $this->hasMany(Paisempresa::className(), ['id_empresa' => 'id'])->where(['status'=>1]);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areas::className(), ['id_empresa' => 'id'])->where(['status'=>1]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorios()
    {
        return $this->hasMany(Consultorios::className(), ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurnos()
    {
        return $this->hasMany(Turnos::className(), ['id_empresa' => 'id'])->orderBy(['orden'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgramaempresa()
    {
        return $this->hasMany(Programaempresa::className(), ['id_empresa' => 'id']);
    }

     /**
     * Gets query for [[Programas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgramas()
    {
        return $this->hasMany(ProgramaSalud::className(), ['id' => 'id_programa'])
        ->viaTable('programa_empresa', ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuestos()
    {
        return $this->hasMany(Puestostrabajo::className(), ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequisitos()
    {
        return $this->hasMany(Requerimientoempresa::className(),  ['id_empresa' => 'id'])->orderBy(['id_tipo'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequisitosactivos()
    {
        return $this->hasMany(Requerimientoempresa::className(),  ['id_empresa' => 'id'])->orderBy(['id_tipo'=>SORT_ASC])->where(['id_status'=>'1']);
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracion()
    {
        return $this->hasOne(Configuracion::class, ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosactivos()
    {
        return $this->hasMany(Usuarios::className(),  ['id_empresa' => 'id'])->where(['status'=>1])->andWhere(['hidden'=>0]);
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdministradores()
    {
        return $this->hasMany(Usuarios::className(),  ['id_empresa' => 'id'])->where(['status'=>1])->andWhere(['hidden'=>0])->andWhere(['rol'=>1]);
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicos()
    {
        return $this->hasMany(Usuarios::className(),  ['id_empresa' => 'id'])->where(['status'=>1])->andWhere(['rol'=>2]);
    }

    public function getUCaptura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'create_user']);
    }

    public function getUActualiza()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'update_user']);
    }

    public function getUElimina()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'delete_user']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFirmasmedicoslaborales()
    {
        return $this->hasMany(Firmas::className(),  ['id_empresa' => 'id'])->where(['tipo'=>2])->orderBy(['id'=>SORT_ASC]);
    }

    public function getFirmasactivas()
    {
        return $this->hasMany(Firmas::className(),  ['id_empresa' => 'id'])->where(['tipo'=>2])->andWhere(['status'=>1])->orderBy(['id'=>SORT_ASC]);
    }

    public function getMails()
    {
        return $this->hasMany(Empresamails::className(),  ['id_empresa' => 'id'])->where(['status'=>1]);
    }

    /**
     * Gets query for [[Maquinaria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaquinas()
    {
        return $this->hasMany(Maquinaria::className(), ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaquinasactivas()
    {
        return $this->hasMany(Maquinaria::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

   
    public function getNiveles1()
    {
        return $this->hasMany(NivelOrganizacional1::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

    public function getNiveles2()
    {
        return $this->hasMany(NivelOrganizacional2::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

    public function getNiveles3()
    {
        return $this->hasMany(NivelOrganizacional3::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

    public function getNiveles4()
    {
        return $this->hasMany(NivelOrganizacional4::className(), ['id_empresa' => 'id'])->where(['status'=>'1']);
    }

}