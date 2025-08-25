<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puesto_estudio".
 *
 * @property int $id
 * @property int|null $id_puesto
 * @property int|null $id_estudio
 */
class PuestoEstudio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puesto_estudio';
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
            [['id_puesto', 'id_tipo', 'id_estudio', 'periodicidad', 'id_status'], 'integer'],
            [['fecha_apartir'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_tipo' => Yii::t('app', 'Tipo'),
            'id_puesto' => Yii::t('app', 'Puesto de Trabajo'),
            'id_estudio' => Yii::t('app', 'Estudio'),
            'id_periodicidad' => Yii::t('app', 'Periodicidad'),
            'fecha_apartir' => Yii::t('app', 'Requerido Desde el Día'),
            'id_status' => Yii::t('app', 'Status'),
        ];
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
}
