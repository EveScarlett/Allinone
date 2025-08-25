<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paises".
 *
 * @property int $id
 * @property string|null $pais
 * @property string|null $abreviado
 */
class Paises extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paises';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pais', 'abreviado'], 'default', 'value' => null],
            [['pais'], 'string', 'max' => 300],
            [['abreviado'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'pais' => Yii::t('app', 'Pais'),
            'abreviado' => Yii::t('app', 'Abreviado'),
        ];
    }

    public function getLineas()
    {
        return $this->hasMany(Lineas::className(), ['id_pais' => 'id']);
    }

}
