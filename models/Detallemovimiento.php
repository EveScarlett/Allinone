<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_movimiento".
 *
 * @property int $id
 * @property int|null $id_movimiento
 * @property int|null $id_insumo
 * @property int|null $id_lote
 * @property int|null $cantidad
 * @property int|null $cantidad_unidad
 * @property string|null $fecha
 * @property string|null $observaciones
 */
class Detallemovimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_movimiento';
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
            [['id_movimiento', 'id_insumo', 'id_lote', 'cantidad', 'cantidad_unidad'], 'integer'],
            [['fecha','fecha_caducidad'], 'safe'],
            [['observaciones'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_movimiento' => Yii::t('app', 'Id Movimiento'),
            'id_insumo' => Yii::t('app', 'Id Insumo'),
            'id_lote' => Yii::t('app', 'Id Lote'),
            'cantidad' => Yii::t('app', 'Cantidad'),
            'cantidad_unidad' => Yii::t('app', 'Cantidad Unidad'),
            'fecha' => Yii::t('app', 'Fecha'),
            'fecha_caducidad' => Yii::t('app', 'Fecha Caducidad'),
            'observaciones' => Yii::t('app', 'Observaciones'),
        ];
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
}
