<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lotes".
 *
 * @property int $id
 * @property int|null $id_movimiento
 * @property int|null $id_empresa
 * @property string|null $folio
 * @property int|null $id_consultorio
 * @property int|null $id_insumo
 * @property string|null $fecha_caducidad
 * @property string|null $fecha_registro
 * @property int|null $cantidad
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 */
class Lotes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lotes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_movimiento', 'id_empresa', 'id_consultorio', 'id_insumo', 'cantidad','cantidad_unidad', 'create_user', 'update_user', 'delete_user','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha_caducidad', 'fecha_registro', 'create_date', 'update_date', 'delete_date'], 'safe'],
            [['folio'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_movimiento' => Yii::t('app', 'Id Movimiento'),
            'id_empresa' => Yii::t('app', 'Id Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'folio' => Yii::t('app', 'Folio'),
            'id_consultorio' => Yii::t('app', 'Id Consultorio'),
            'id_insumo' => Yii::t('app', 'Id Insumo'),
            'fecha_caducidad' => Yii::t('app', 'Fecha Caducidad'),
            'fecha_registro' => Yii::t('app', 'Fecha Registro'),
            'cantidad' => Yii::t('app', 'Cantidad'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
            'update_date' => Yii::t('app', 'Update Date'),
            'update_user' => Yii::t('app', 'Update User'),
            'delete_date' => Yii::t('app', 'Delete Date'),
            'delete_user' => Yii::t('app', 'Delete User'),
        ];
    }
}
