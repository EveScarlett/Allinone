<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property string|null $level
 * @property string|null $notifiable_type
 * @property int|null $notifiable_id
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $read_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Notification extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'notifiable_type', 'notifiable_id', 'subject', 'body', 'read_at', 'updated_at'], 'default', 'value' => null],
            [['notifiable_id','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['body'], 'string'],
            [['read_at', 'created_at', 'updated_at'], 'safe'],
            [['level', 'notifiable_type', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),

            'level' => 'Level',
            'notifiable_type' => 'Notifiable Type',
            'notifiable_id' => 'Notifiable ID',
            'subject' => 'Subject',
            'body' => 'Body',
            'read_at' => 'Read At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
