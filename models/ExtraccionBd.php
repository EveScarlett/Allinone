<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extraccion_bd".
 *
 * @property int $id
 * @property string|null $base_datos
 * @property string|null $tabla
 * @property string|null $create_date
 * @property int|null $create_user
 */
class ExtraccionBd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extraccion_bd';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date'], 'safe'],
            [['create_user'], 'integer'],
            [['base_datos', 'tabla'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_datos' => Yii::t('app', 'Base Datos'),
            'tabla' => Yii::t('app', 'Tabla'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
        ];
    }
}
