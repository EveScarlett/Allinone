<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documentacion".
 *
 * @property int $id
 * @property string|null $documento
 * @property int|null $status
 */
class Documentacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documentacion';
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
            [['status'], 'integer'],
            [['documento'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'documento' => Yii::t('app', 'Documento'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
