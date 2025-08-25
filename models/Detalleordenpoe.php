<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_ordenpoe".
 *
 * @property int $id
 * @property int|null $id_ordenpoe
 * @property int|null $id_categoria
 * @property int|null $id_estudio
 */
class Detalleordenpoe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_ordenpoe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ordenpoe', 'id_categoria', 'id_estudio'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ordenpoe' => 'Id Ordenpoe',
            'id_categoria' => 'Id Categoria',
            'id_estudio' => 'Id Estudio',
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
     * Gets query for [[Estudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudio()
    {
        return $this->hasOne(Servicios::className(), ['id' => 'id_estudio']);
    }

    /**
     * Gets query for [[TipoServicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(TipoServicios::className(), ['id' => 'id_categoria']);
    }

}
