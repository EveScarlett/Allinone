<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programa_trabajador".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_programa
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property int|null $status
 */
class ProgramaTrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programa_trabajador';
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
            [['id_trabajador', 'id_programa','conclusion', 'create_user', 'update_user', 'delete_user', 'status','status_baja'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'create_date', 'update_date', 'delete_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_trabajador' => Yii::t('app', 'Id Trabajador'),
            'id_programa' => Yii::t('app', 'Id Programa'),
            'conclusion' => Yii::t('app', 'Conclusión'),
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'fecha_fin' => Yii::t('app', 'Fecha Fin'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
            'update_date' => Yii::t('app', 'Update Date'),
            'update_user' => Yii::t('app', 'Update User'),
            'delete_date' => Yii::t('app', 'Delete Date'),
            'delete_user' => Yii::t('app', 'Delete User'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[ProgramaSalud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma()
    {
        return $this->hasOne(ProgramaSalud::className(), ['id' => 'id_programa']);
    }
}
