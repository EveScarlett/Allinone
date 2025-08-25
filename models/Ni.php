<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ni".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_nivel1
 * @property int|null $id_nivel2
 * @property int|null $id_nivel3
 * @property int|null $id_nivel4
 * @property string|null $ni
 * @property int|null $status
 * @property string|null $descripcion
 */
class Ni extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'ni', 'status', 'descripcion'], 'default', 'value' => null],
            [['id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'status'], 'integer'],
            [['descripcion'], 'string'],
            [['ni'], 'string', 'max' => 1000],
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
            'id_nivel1' => Yii::t('app', 'Id Nivel1'),
            'id_nivel2' => Yii::t('app', 'Id Nivel2'),
            'id_nivel3' => Yii::t('app', 'Id Nivel3'),
            'id_nivel4' => Yii::t('app', 'Id Nivel4'),
            'ni' => Yii::t('app', 'Ni'),
            'status' => Yii::t('app', 'Status'),
            'descripcion' => Yii::t('app', 'Descripcion'),
        ];
    }

}
