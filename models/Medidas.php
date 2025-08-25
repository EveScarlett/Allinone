<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medidas".
 *
 * @property int $id
 * @property int|null $parte_corporal
 * @property int|null $medida
 * @property int|null $status
 */
class Medidas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medidas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parte_corporal','id_empresa', 'status','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['medida'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'parte_corporal' => Yii::t('app', 'Parte Corporal'),
            'medida' => Yii::t('app', 'Medida'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
