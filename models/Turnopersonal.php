<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "turno_personal".
 *
 * @property int $id
 * @property int|null $id_turno
 * @property string|null $nombre_personal
 * @property int|null $cantidad
 */
class Turnopersonal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'turno_personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'id_turno', 'cantidad'], 'integer'],
            [['nombre_personal'], 'string', 'max' => 500],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_turno' => 'Id Turno',
            'nombre_personal' => 'Nombre Personal',
            'cantidad' => 'Cantidad',
        ];
    }
}
