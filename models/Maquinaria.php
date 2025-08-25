<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "maquinaria".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $clave
 * @property string|null $foto
 * @property string|null $maquina
 * @property string|null $fabricante
 * @property string|null $modelo
 * @property string|null $marca
 * @property string|null $combustible
 * @property int|null $id_ubicacion
 * @property int|null $id_area
 * @property float|null $peso
 * @property float|null $altura
 * @property float|null $ancho
 * @property float|null $largo
 * @property float|null $alto
 * @property string|null $detalles_tecnicos
 * @property string|null $funcion
 * @property string|null $qr
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 */
class Maquinaria extends \yii\db\ActiveRecord
{
    public $file_logo;
    public $envia_form;

    public $id_trabajador;
    public $id_maquina;
    public $status_trabajo;

    public $aux_riesgos = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maquinaria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_logo'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['id_trabajador', 'id_maquina', 'status_trabajo','id_empresa','id_pais','id_linea','id_ubicacionl', 'id_ubicacion', 'id_area', 'status','status_operacion', 'create_user', 'update_user','envia_form','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['peso', 'altura', 'ancho', 'largo', 'alto'], 'number'],
            [['create_date', 'update_date','fecha_inicio','fecha_fin','aux_riesgos','fecha_mantenimiento','proximo_mantenimiento'], 'safe'],
            [['clave'], 'string', 'max' => 100],
            [['foto'], 'string', 'max' => 300],
            [['maquina', 'fabricante', 'modelo', 'marca', 'combustible'], 'string', 'max' => 1000],
            [['detalles_tecnicos', 'funcion'], 'string', 'max' => 2000],
            [['qr'], 'string', 'max' => 500],
            [['maquina','id_empresa','status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacionl' => Yii::t('app', 'Ubicación'),
            'clave' => Yii::t('app', 'Clave'),
            'foto' => Yii::t('app', 'Foto'),
            'file_logo' => Yii::t('app', 'Foto'),
            'maquina' => Yii::t('app', 'Máquina'),
            'fabricante' => Yii::t('app', 'Fabricante'),
            'modelo' => Yii::t('app', 'Modelo'),
            'marca' => Yii::t('app', 'Marca'),
            'combustible' => Yii::t('app', 'Combustible'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_area' => Yii::t('app', 'Área'),
            'peso' => Yii::t('app', 'Peso'),
            'altura' => Yii::t('app', 'Altura'),
            'ancho' => Yii::t('app', 'Ancho'),
            'largo' => Yii::t('app', 'Largo'),
            'alto' => Yii::t('app', 'Alto'),
            'detalles_tecnicos' => Yii::t('app', 'Detalles Técnicos'),
            'funcion' => Yii::t('app', 'Función'),
            'qr' => Yii::t('app', 'QR'),
            'status' => Yii::t('app', 'Status'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
            'update_date' => Yii::t('app', 'Update Date'),
            'update_user' => Yii::t('app', 'Update User'),
            'fecha_inicio' => Yii::t('app', 'Inicio'),
            'fecha_fin' => Yii::t('app', 'Fin'),
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::class, ['id' => 'id_area']);
    }

    /**
     * Gets query for [[Ubicaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }

    public function getRiesgos()
    {
        return $this->hasMany(Maquinariesgo::className(), ['id_maquina' => 'id']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacionl()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacionl']);
    }
}
