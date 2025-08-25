<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puesto_parametro".
 *
 * @property int $id
 * @property int|null $id_puesto
 * @property int|null $id_parametro
 * @property int|null $valor
 */
class Puestoparametro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puesto_parametro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_puesto', 'id_parametro', 'valor'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_puesto' => Yii::t('app', 'Id Puesto'),
            'id_parametro' => Yii::t('app', 'Id Parametro'),
            'valor' => Yii::t('app', 'Valor'),
        ];
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametro()
    {
        return $this->hasOne(Parametros::className(), ['id' => 'id_parametro']);
    }

}
