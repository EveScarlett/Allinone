<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puesto_riesgo".
 *
 * @property int $id
 * @property int|null $id_puesto
 * @property int|null $id_riesgo
 */
class PuestoRiesgo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puesto_riesgo';
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
            [['id_puesto', 'id_riesgo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_puesto' => Yii::t('app', 'Id Puesto'),
            'id_riesgo' => Yii::t('app', 'Id Riesgo'),
        ];
    }
}
