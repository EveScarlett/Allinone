<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ordenpoe_trabajador".
 *
 * @property int $id
 * @property int|null $id_ordenpoe
 * @property int|null $id_trabajador
 */
class Ordenpoetrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ordenpoe_trabajador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ordenpoe', 'id_trabajador'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ordenpoe' => 'Orden de Trabajo',
            'id_trabajador' => 'Id Trabajador',
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenpoe()
    {
        return $this->hasOne(Ordenespoes::class, ['id' => 'id_ordenpoe']);
    }

         /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

}