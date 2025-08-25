<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hccohc_estudio".
 *
 * @property int $id
 * @property int|null $id_hccohc
 * @property int|null $id_tipo
 * @property int|null $id_estudio
 * @property string|null $fecha
 * @property string|null $evidencia
 * @property int|null $conclusion
 * @property string|null $comentario
 */
class Hccohcestudio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hccohc_estudio';
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
            [['id_hccohc', 'id_tipo', 'id_estudio', 'conclusion'], 'integer'],
            [['fecha','date_update','hour_update'], 'safe'],
            [['evidencia'], 'string', 'max' => 300],
            [['comentario'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_hccohc' => Yii::t('app', 'Id Hccohc'),
            'id_tipo' => Yii::t('app', 'Id Tipo'),
            'id_estudio' => Yii::t('app', 'Id Estudio'),
            'fecha' => Yii::t('app', 'Fecha'),
            'evidencia' => Yii::t('app', 'Evidencia'),
            'conclusion' => Yii::t('app', 'Conclusion'),
            'comentario' => Yii::t('app', 'Comentario'),
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(TipoServicios::class, ['id' => 'id_tipo']);
    }

    public function getEstudio()
    {
        return $this->hasOne(Servicios::class, ['id' => 'id_estudio']);
    }
}
