<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mantenimientos".
 *
 * @property int $id
 * @property string|null $clave
 * @property int|null $id_empresa
 * @property int|null $tipo_mantenimiento
 * @property int|null $id_maquina
 * @property string|null $realiza_mantenimiento
 * @property string|null $descripcion
 * @property int|null $status_maquina
 * @property string|null $proximo_mantenimiento
 * @property string|null $nombre_firma1
 * @property string|null $firma1
 * @property string|null $nombre_firma2
 * @property string|null $firma2
 * @property string|null $nombre_firma3
 * @property string|null $firma3
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 */
class Mantenimientos extends \yii\db\ActiveRecord
{
    public $envia_form;
    public $guardar_firma;
    public $firma1txt;
    public $aux_actividades = [];
    public $aux_componentes = [];

    public $firmado;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mantenimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clave', 'id_empresa','id_pais','id_linea', 'tipo_mantenimiento', 'id_maquina', 'realiza_mantenimiento', 'descripcion', 'status_maquina', 'proximo_mantenimiento', 'nombre_firma1', 'firma1', 'nombre_firma2', 'firma2', 'nombre_firma3', 'firma3', 'status', 'create_date', 'create_user', 'update_date', 'update_user', 'envia_form','guardar_firma'], 'default', 'value' => null],
            [['id_empresa', 'tipo_mantenimiento', 'id_maquina', 'status_maquina', 'status', 'create_user', 'update_user','firmado','id_area','id_ubicacion','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['descripcion', 'firma1', 'firma2', 'firma3'], 'string'],
            [['proximo_mantenimiento', 'create_date', 'update_date','fecha','aux_actividades','aux_componentes'], 'safe'],
            [['clave'], 'string', 'max' => 50],
            [['nombre','marca','modelo','ruta_firma1','ruta_firma2','ruta_firma3'], 'string', 'max' => 300],
            [['numero_serie'], 'string', 'max' => 100],
            [['realiza_mantenimiento', 'nombre_firma1', 'nombre_firma2', 'nombre_firma3'], 'string', 'max' => 300],
            [['id_empresa','id_maquina','tipo_mantenimiento','realiza_mantenimiento','fecha','descripcion','status_maquina'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clave' => 'Folio',
            'id_empresa' => 'Empresa',

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'tipo_mantenimiento' => 'Tipo de Mantenimiento',
            'id_maquina' => 'Máquina',
            'realiza_mantenimiento' => 'Persona que realiza mantenimiento',
            'descripcion' => 'Descripción del Mantenimiento Realizado',
            'id_area' => 'Área',
            'id_ubicacion' => 'Ubicación',
            'status_maquina' => 'Status Máquina',
            'proximo_mantenimiento' => 'Próximo Mantenimiento',
            'nombre_firma1' => 'Responsable Interno',
            'firma1' => 'Firma1',
            'nombre_firma2' => 'Nombre Firma2',
            'firma2' => 'Firma2',
            'nombre_firma3' => 'Nombre Firma3',
            'firma3' => 'Firma3',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
            'update_date' => 'Update Date',
            'update_user' => 'Update User',
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
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaquina()
    {
        return $this->hasOne(Maquinaria::class, ['id' => 'id_maquina']);
    }

    /**
     * Gets query for [[Mantenimientoactividad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividades()
    {
        return $this->hasMany(Mantenimientoactividad::className(), ['id_mantenimiento' => 'id']);
    }

    /**
     * Gets query for [[Mantenimientocomponentes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponentes()
    {
        return $this->hasMany(Mantenimientocomponentes::className(), ['id_mantenimiento' => 'id']);
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

}
