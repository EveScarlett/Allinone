<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "riesgos".
 *
 * @property int $id
 * @property string|null $riesgo
 */
class Riesgos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riesgos';
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
            [['riesgo'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'riesgo' => Yii::t('app', 'Riesgo'),
        ];
    }
}
