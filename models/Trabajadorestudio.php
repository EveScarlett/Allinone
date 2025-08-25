<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajador_estudio".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_estudio
 * @property int|null $id_periodicidad
 * @property string|null $fecha_documento
 * @property string|null $fecha_vencimiento
 * @property string|null $evidencia
 * @property string|null $conclusion
 * @property int|null $status
 */
class Trabajadorestudio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajador_estudio';
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
            [['id_trabajador', 'id_estudio', 'id_periodicidad', 'status','orden','status_baja'], 'integer'],
            [['fecha_documento', 'fecha_vencimiento','fecha_apartir','date_update','hour_update'], 'safe'],
            [['evidencia', 'conclusion'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_trabajador' => Yii::t('app', 'Id Trabajador'),
            'id_estudio' => Yii::t('app', 'Id Estudio'),
            'id_periodicidad' => Yii::t('app', 'Id Periodicidad'),
            'fecha_apartir' => Yii::t('app', 'Requerido Desde el Día'),
            'fecha_documento' => Yii::t('app', 'Fecha Documento'),
            'fecha_vencimiento' => Yii::t('app', 'Fecha Vencimiento'),
            'evidencia' => Yii::t('app', 'Evidencia'),
            'conclusion' => Yii::t('app', 'Conclusion'),
            'date_update' => Yii::t('app', 'Fecha'),
            'hour_update' => Yii::t('app', 'Hora'),
            'status' => Yii::t('app', 'Status'),
            'orden' => Yii::t('app', 'Orden'),
        ];
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
}
