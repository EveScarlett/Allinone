<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajador_epp".
 *
 * @property int $id
 * @property int|null $id_puesto
 * @property int|null $id_trabajador
 * @property int|null $id_epp
 * @property int|null $talla
 * @property int|null $status
 */
class Trabajadorepp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajador_epp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_puesto', 'id_trabajador', 'id_epp', 'talla', 'status'], 'integer'],
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
            'id_trabajador' => Yii::t('app', 'Id Trabajador'),
            'id_epp' => Yii::t('app', 'Id Epp'),
            'talla' => Yii::t('app', 'Talla'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
