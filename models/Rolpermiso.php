<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rol_permiso".
 *
 * @property int $id
 * @property int|null $id_rol
 * @property int|null $id_permiso
 * @property int|null $status
 * @property int|null $soft_delete
 */
class Rolpermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rol_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rol', 'status', 'soft_delete'], 'integer'],
            [['id_permiso'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_rol' => Yii::t('app', 'Rol'),
            'id_permiso' => Yii::t('app', 'Permiso'),
            'status' => Yii::t('app', 'Status'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
        ];
    }
}
