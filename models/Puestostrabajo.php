<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puestos_trabajo".
 *
 * @property int $id
 * @property string|null $nombre
 * @property int|null $status
 */
class Puestostrabajo extends \yii\db\ActiveRecord
{
    public $aux_riesgos = [];
    public $aux_ni = [];
    public $aux_epps = [];
    public $aux_estudios = [];
    public $aux_otrosriesgos = [];
    public $aux_otrosepps = [];
    public $otros_riesgos;
    public $aux_psicologico = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puestos_trabajo';
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
            [['status','id_empresa','id_pais','id_linea','id_ubicacion','otros_riesgos','create_user','update_user','delete_user','medida1','medida2','medida3','sexo','edaddesde','edadhasta','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['rango1desde','rango1hasta','rango2desde','rango2hasta','rango3desde','rango3hasta','cargamaxima','carga'], 'number'],
            [['nombre'], 'string', 'max' => 300],
            [['descripcion'], 'string', 'max' => 1000],
            [['aux_riesgos', 'aux_estudios', 'aux_epps','aux_otrosriesgos','aux_otrosepps','create_date','update_date','delete_date','aux_psicologico','aux_ni'], 'safe'],
            [['nombre','id_empresa','status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Puesto de Trabajo'), 
            'descripcion' => Yii::t('app', 'Descripción'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'status' => Yii::t('app', 'Status'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Registró'),
            'update_date' => Yii::t('app', 'Fecha Actualiza'),
            'update_user' => Yii::t('app', 'Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Elimina'),
            'medida1' => Yii::t('app', 'Medida Antropométrica'),
            'rango1desde' => Yii::t('app', 'Desde'),
            'rango1hasta' => Yii::t('app', 'Hasta'),
            'medida2' => Yii::t('app', 'Medida Antropométrica'),
            'rango2desde' => Yii::t('app', 'Desde'),
            'rango2hasta' => Yii::t('app', 'Hasta'),
            'medida3' => Yii::t('app', 'Medida Antropométrica'),
            'rango3desde' => Yii::t('app', 'Desde'),
            'rango3hasta' => Yii::t('app', 'Hasta'),
            'sexo' => Yii::t('app', 'Género Requerido'),
            'edaddesde' => Yii::t('app', 'Rango Edad Desde'),
            'edadhasta' => Yii::t('app', 'Hasta'),
            'cargamaxima' => Yii::t('app', 'Carga Máxima (kg)'),
            'carga' => Yii::t('app', 'Carga Máxima (kg)'),
            'delete_user' => Yii::t('app', 'Eliminó'),
        ];
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Riesgos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiesgos()
    {
        return $this->hasMany(Riesgos::className(), ['id' => 'id_riesgo'])
        ->viaTable('puesto_riesgo', ['id_puesto' => 'id']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Estudios::className(), ['id' => 'id_estudio'])
        ->viaTable('puesto_estudio', ['id_puesto' => 'id']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPestudios()
    {
        return $this->hasMany(PuestoEstudio::className(), ['id_puesto' => 'id'])->orderBy(['id_tipo'=>SORT_ASC]);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPestudiosactivos()
    {
        return $this->hasMany(PuestoEstudio::className(), ['id_puesto' => 'id'])->orderBy(['id_tipo'=>SORT_ASC])->where(['id_status'=>'1']);
    }


    public function getRequisitosni()
    {
        return $this->hasMany(Nirequisitos::className(), ['id_puesto' => 'id'])->orderBy(['status'=>SORT_ASC]);
    }

    public function getRequisitosniactivos()
    {
        return $this->hasMany(Nirequisitos::className(), ['id_puesto' => 'id'])->where(['status'=>1])->orderBy(['status'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Epps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEpps()
    {
        return $this->hasMany(Epps::className(), ['id' => 'id_epp'])
        ->viaTable('puesto_epp', ['id_puesto' => 'id']);
    }

    /**
     * Gets query for [[PuestoEpp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPepps()
    {
        return $this->hasMany(PuestoEpp::className(), ['id_puesto' => 'id']);
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
    public function getTrabajadores()
    {
        return $this->hasMany(Trabajadores::className(), ['id_puesto' => 'id']);
    }

    /**
     * Gets query for [[Parametros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametros()
    {
        return $this->hasMany(Puestoparametro::className(), ['id_puesto' => 'id']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }


    public function getNivel1()
    {
        return $this->hasOne(NivelOrganizacional1::class, ['id' => 'id_nivel1']);
    }
    public function getNivel2()
    {
        return $this->hasOne(NivelOrganizacional2::class, ['id' => 'id_nivel2']);
    }
    public function getNivel3()
    {
        return $this->hasOne(NivelOrganizacional3::class, ['id' => 'id_nivel3']);
    }
    public function getNivel4()
    {
        return $this->hasOne(NivelOrganizacional4::class, ['id' => 'id_nivel4']);
    }


}
