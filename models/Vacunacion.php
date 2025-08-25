<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacunacion".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $vacuna
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Vacunacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacunacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'vacuna', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_empresa', 'status', 'create_user','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['create_date'], 'safe'],
            [['vacuna'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_empresa' => Yii::t('app', 'Id Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'vacuna' => Yii::t('app', 'Vacuna'),
            'status' => Yii::t('app', 'Status'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
        ];
    }

}
