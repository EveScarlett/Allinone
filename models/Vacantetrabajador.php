<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacante_trabajador".
 *
 * @property int $id
 * @property int|null $id_vacante
 * @property int|null $id_trabajador
 */
class Vacantetrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacante_trabajador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_vacante', 'id_trabajador'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_vacante' => Yii::t('app', 'Id Vacante'),
            'id_trabajador' => Yii::t('app', 'Id Trabajador'),
        ];
    }
}
