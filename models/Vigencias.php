<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vigencias".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $vigencia
 * @property int|null $cantidad_dias
 * @property int|null $cantidad_meses
 * @property int|null $cantidad_anios
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Vigencias extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vigencias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'vigencia', 'cantidad_dias', 'cantidad_meses', 'cantidad_anios', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_empresa', 'cantidad_dias', 'cantidad_meses', 'cantidad_anios', 'status', 'create_user','orden','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['create_date'], 'safe'],
            [['vigencia'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_empresa' => 'Id Empresa',

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),

            
            'vigencia' => 'Vigencia',
            'cantidad_dias' => 'Cantidad Dias',
            'cantidad_meses' => 'Cantidad Meses',
            'cantidad_anios' => 'Cantidad Anios',
            'status' => 'Status',
            'orden' => 'Orden',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }

}
