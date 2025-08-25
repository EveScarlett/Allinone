<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitudes_delete".
 *
 * @property int $id
 * @property int|null $status_solicitud
 * @property int|null $modelo
 * @property int|null $id_modelo
 * @property int|null $user_solicita
 * @property string|null $date_solicita
 * @property int|null $user_aprueba
 * @property string|null $date_aprueba
 */
class SolicitudesDelete extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitudes_delete';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'status_solicitud', 'modelo', 'id_modelo', 'user_solicita', 'user_aprueba'], 'integer'],
            [['date_solicita', 'date_aprueba'], 'safe'],
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
            'status_solicitud' => 'Status Solicitud',
            'modelo' => 'Módulo',
            'id_modelo' => 'Detalle Módulo',
            'user_solicita' => 'Solicita',
            'date_solicita' => 'Fecha Solicitud',
            'user_aprueba' => 'Aprobado por',
            'date_aprueba' => 'Fecha Aprobado',
        ];
    }
}
