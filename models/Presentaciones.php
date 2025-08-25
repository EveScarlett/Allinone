<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presentaciones".
 *
 * @property int $id
 * @property string|null $presentacion
 */
class Presentaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presentaciones';
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
            [['presentacion'], 'string', 'max' => 400],
            [['tipo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'presentacion' => Yii::t('app', 'Presentacion'),
        ];
    }
}
