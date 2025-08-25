<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "puesto_trabajador".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_puesto
 * @property string|null $area
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property string|null $teamleader
 * @property int|null $status
 */
class Puestotrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'puesto_trabajador';
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
            [['id_trabajador', 'id_puesto', 'status'], 'integer'],
            [['fecha_inicio', 'fecha_fin','riesgos'], 'safe'],
            [['area', 'teamleader'], 'string', 'max' => 300],
            [['antiguedad'], 'string', 'max' => 100],
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
            'id_puesto' => Yii::t('app', 'Id Puesto'),
            'area' => Yii::t('app', 'Area'),
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'fecha_fin' => Yii::t('app', 'Fecha Fin'),
            'antiguedad' => Yii::t('app', 'Antigüedad'),
            'teamleader' => Yii::t('app', 'Teamleader'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuesto()
    {
        return $this->hasOne(Puestostrabajo::className(), ['id' => 'id_puesto']);
    }

    /**
     * Gets query for [[Riesgos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatariesgos()
    {
        return $this->hasMany(Riesgos::className(), ['id' => 'id_riesgo'])
        ->viaTable('puestotrabajoh_riesgo', ['id_puestotrabajador' => 'id']);
    }
}
