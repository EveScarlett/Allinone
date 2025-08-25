<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "poe_estudio".
 *
 * @property int $id
 * @property int|null $id_poe
 * @property int|null $id_estudio
 * @property int|null $id_tipo
 * @property string|null $fecha
 * @property string|null $hora
 * @property string|null $evidencia
 * @property int|null $condicion
 * @property string|null $comentario
 * @property int|null $evolucion
 * @property int|null $status
 */
class Poeestudio extends \yii\db\ActiveRecord
{
    public $hc_enlazar = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poe_estudio';
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
            [['id_poe', 'id_estudio', 'id_tipo', 'condicion', 'evolucion', 'status','status_entrega','orden','obligatorio','id_hc'], 'integer'],
            [['fecha', 'hora', 'hc_enlazar'], 'safe'],
            [['comentario'], 'string'],
            [['evidencia','evidencia2','evidencia3'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_poe' => Yii::t('app', 'Id Poe'),
            'id_estudio' => Yii::t('app', 'Id Estudio'),
            'id_tipo' => Yii::t('app', 'Id Tipo'),
            'fecha' => Yii::t('app', 'Fecha'),
            'hora' => Yii::t('app', 'Hora'),
            'evidencia' => Yii::t('app', 'Evidencia'),
            'evidencia2' => Yii::t('app', 'Evidencia 2'),
            'evidencia3' => Yii::t('app', 'Evidencia 3'),
            'id_hc' => Yii::t('app', 'HC'),
            'condicion' => Yii::t('app', 'Condicion'),
            'comentario' => Yii::t('app', 'Comentario'),
            'evolucion' => Yii::t('app', 'Evolucion'),
            'status' => Yii::t('app', 'Status'),
            'obligatorio' => Yii::t('app', 'Obligatorio'),
        ];
    }

    /**
     * Gets query for [[Poes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoe()
    {
        return $this->hasOne(Poes::className(), ['id' => 'id_poe']);
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
    public function getTipo()
    {
        return $this->hasOne(TipoServicios::className(), ['id' => 'id_tipo']);
    }
}
