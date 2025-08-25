<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puestotrabajoh_riesgo".
 *
 * @property int $id
 * @property int|null $id_puestotrabajador
 * @property int|null $id_puesto
 * @property int|null $id_riesgo
 */
class Puestotrabajohriesgo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puestotrabajoh_riesgo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_puestotrabajador', 'id_puesto', 'id_riesgo'], 'default', 'value' => null],
            [['id_puestotrabajador','id_trabajador', 'id_puesto', 'id_riesgo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_puestotrabajador' => Yii::t('app', 'Id Puestotrabajador'),
            'id_puesto' => Yii::t('app', 'Id Puesto'),
            'id_riesgo' => Yii::t('app', 'Id Riesgo'),
        ];
    }

    public function getRiesgo()
    {
        return $this->hasOne(Riesgos::className(), ['id' => 'id_riesgo']);
    }

}
