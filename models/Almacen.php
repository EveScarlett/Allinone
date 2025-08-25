<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacen".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_consultorio
 * @property int|null $id_insumo
 * @property int|null $stock
 * @property int|null $stock_unidad
 * @property string|null $update_date
 * @property int|null $update_user
 */
class Almacen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacen';
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
            [['id_empresa','id_nivel1','id_nivel2','id_nivel3','id_nivel4','id_pais','id_linea','id_ubicacion', 'id_consultorio', 'id_insumo', 'id_lote', 'stock', 'stock_unidad', 'update_user','tipo_insumo'], 'integer'],
            [['update_date','fecha_caducidad'], 'safe'],
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
            'id_consultorio' => Yii::t('app', 'Consultorio'),
            'id_insumo' => Yii::t('app', 'Medicamento'),
            'id_lote' => Yii::t('app', 'Lote'),
            'stock' => Yii::t('app', 'Stock'),
            'fecha_caducidad' => Yii::t('app', 'Fecha de Caducidad'),
            'stock_unidad' => Yii::t('app', 'Stock Actual'),
            'update_date' => Yii::t('app', 'Último Movimiento'),
            'update_user' => Yii::t('app', 'Actualizó'),
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
     * Gets query for [[Consultorio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorio()
    {
        return $this->hasOne(Consultorios::class, ['id' => 'id_consultorio']);
    }

    /**
     * Gets query for [[Insumos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsumo()
    {
        return $this->hasOne(Insumos::class, ['id' => 'id_insumo']);
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
