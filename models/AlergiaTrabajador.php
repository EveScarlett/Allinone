<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alergia_trabajador".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property string|null $alergia
 */
class AlergiaTrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alergia_trabajador';
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
            [['id_trabajador'], 'integer'],
            [['alergia'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_trabajador' => Yii::t('app', 'Trabajador'),
            'alergia' => Yii::t('app', 'Alergia'),
        ];
    }
}
