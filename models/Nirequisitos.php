<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ni_requisitos".
 *
 * @property int $id
 * @property int|null $id_ni
 * @property int|null $id_requisito
 * @property int|null $status
 */
class Nirequisitos extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ni_requisitos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ni', 'id_requisito', 'status'], 'default', 'value' => null],
            [['id_ni', 'id_requisito', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_ni' => Yii::t('app', 'Id Ni'),
            'id_requisito' => Yii::t('app', 'Id Requisito'),
            'status' => Yii::t('app', 'Status'),
        ];
    }


    public function getEstudio()
    {
        return $this->hasOne(Estudios::className(), ['id' => 'id_requisito']);
    }

    public function getExamenmedico()
    {
        return $this->hasOne(Servicios::className(), ['id' => 'id_requisito']);
    }

}
