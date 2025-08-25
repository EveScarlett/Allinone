<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detallehc".
 *
 * @property int $id
 * @property int|null $id_hcc
 * @property string|null $seccion
 * @property string|null $descripcion
 * @property int|null $anio
 */
class Detallehc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detallehc';
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
            [['id_hcc', 'anio'], 'integer'],
            [['seccion'], 'string', 'max' => 30],
            [['descripcion'], 'string', 'max' => 100],
            [['fecha'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_hcc' => Yii::t('app', 'Id Hcc'),
            'seccion' => Yii::t('app', 'Seccion'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'anio' => Yii::t('app', 'Anio'),
            'fecha' => Yii::t('app', 'Fecha'),
        ];
    }

    public function getVacuna()
    {
        return $this->hasOne(Vacunacion::class, ['id' => 'descripcion']);
    }
}
