<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresa_mails".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $tipo_mail
 * @property string|null $mail
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Empresamails extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresa_mails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'tipo_mail', 'mail', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_empresa', 'tipo_mail', 'status', 'create_user'], 'integer'],
            [['create_date'], 'safe'],
            [['mail'], 'string', 'max' => 500],
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
            'tipo_mail' => 'Tipo Mail',
            'mail' => 'Mail',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'create_user' => 'Create User',
        ];
    }

}
