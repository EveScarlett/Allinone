<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historial_documentos".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_trabajador
 * @property int|null $id_tipo
 * @property int|null $id_estudio
 * @property int|null $id_periodicidad
 * @property string|null $fecha_documento
 * @property string|null $fecha_vencimiento
 * @property string|null $evidencia
 */
class Historialdocumentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historial_documentos';
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
            [['id_empresa','id_pais','id_linea','id_ubicacion', 'id_trabajador', 'id_tipo', 'id_estudio', 'id_periodicidad','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha_documento', 'fecha_vencimiento'], 'safe'],
            [['evidencia'], 'string', 'max' => 300],
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
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_trabajador' => Yii::t('app', 'Nombre Trabajador'),
            'id_tipo' => Yii::t('app', 'Requisito'),
            'id_estudio' => Yii::t('app', 'Documento'),
            'id_periodicidad' => Yii::t('app', 'Periodicidad'),
            'fecha_documento' => Yii::t('app', 'Fecha Documento'),
            'fecha_vencimiento' => Yii::t('app', 'Fecha Vencimiento'),
            'update_date' => Yii::t('app', 'Fecha Subida'),
            'evidencia' => Yii::t('app', 'Evidencia'),
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
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuesto()
    {
        return $this->hasOne(Puestostrabajo::class, ['id' => 'id_puesto']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudio()
    {
        return $this->hasOne(Estudios::class, ['id' => 'id_estudio']);
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
