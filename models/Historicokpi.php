<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historico_kpi".
 *
 * @property int $id
 * @property int|null $id_kpi
 * @property float|null $kpi_objetivo
 * @property float|null $kpi_real
 * @property float|null $kpi_cumplimiento
 * @property string|null $kpi_responsable
 * @property string|null $kpi_descripcion
 * @property string|null $kpi_fecha
 * @property int|null $kpi_actualiza
 * @property int|null $kpi_qty_trabajadores
 */
class Historicokpi extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historico_kpi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kpi', 'kpi_objetivo', 'kpi_real', 'kpi_cumplimiento', 'kpi_responsable', 'kpi_descripcion', 'kpi_fecha', 'kpi_actualiza', 'kpi_qty_trabajadores'], 'default', 'value' => null],
            [['id_kpi', 'kpi_actualiza', 'kpi_qty_trabajadores'], 'integer'],
            [['kpi_objetivo', 'kpi_real', 'kpi_cumplimiento'], 'number'],
            [['kpi_fecha'], 'safe'],
            [['kpi_responsable'], 'string', 'max' => 100],
            [['kpi_descripcion'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_kpi' => Yii::t('app', 'Id Kpi'),
            'kpi_objetivo' => Yii::t('app', 'Kpi Objetivo'),
            'kpi_real' => Yii::t('app', 'Kpi Real'),
            'kpi_cumplimiento' => Yii::t('app', 'Kpi Cumplimiento'),
            'kpi_responsable' => Yii::t('app', 'Kpi Responsable'),
            'kpi_descripcion' => Yii::t('app', 'Kpi Descripcion'),
            'kpi_fecha' => Yii::t('app', 'Kpi Fecha'),
            'kpi_actualiza' => Yii::t('app', 'Kpi Actualiza'),
            'kpi_qty_trabajadores' => Yii::t('app', 'Kpi Qty Trabajadores'),
        ];
    }

}
