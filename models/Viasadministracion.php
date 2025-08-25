<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vias_administracion".
 *
 * @property int $id
 * @property string|null $via_administracion
 */
class Viasadministracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vias_administracion';
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
            [['via_administracion'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'via_administracion' => Yii::t('app', 'Via Administracion'),
        ];
    }
}
