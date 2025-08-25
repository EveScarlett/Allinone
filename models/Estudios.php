<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estudios".
 *
 * @property int $id
 * @property string|null $estudio
 * @property int|null $periodicidad
 */
class Estudios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudios';
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
            [['periodicidad'], 'integer'],
            [['estudio'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'estudio' => Yii::t('app', 'Estudio'),
            'periodicidad' => Yii::t('app', 'Periodicidad'),
        ];
    }
}
