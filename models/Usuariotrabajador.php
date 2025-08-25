<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_trabajador".
 *
 * @property int $id
 * @property int|null $id_usuario
 * @property int|null $id_trabajador
 * @property string|null $delete_date
 * @property int|null $status
 */
class Usuariotrabajador extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_trabajador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_trabajador', 'delete_date', 'status'], 'default', 'value' => null],
            [['id_usuario', 'id_trabajador', 'status'], 'integer'],
            [['delete_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_usuario' => Yii::t('app', 'Id Usuario'),
            'id_trabajador' => Yii::t('app', 'Id Trabajador'),
            'delete_date' => Yii::t('app', 'Delete Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }


    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'id_usuario']);
    }

    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

}
