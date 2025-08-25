<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpis".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_superior
 * @property int|null $nivel
 * @property string|null $kpi
 * @property int|null $id_programa
 * @property int|null $status
 * @property int|null $status_baja
 */
class Kpis extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_superior', 'nivel', 'kpi', 'id_programa', 'status', 'status_baja'], 'default', 'value' => null],
            [['id_empresa', 'id_superior', 'nivel', 'id_programa', 'status', 'status_baja'], 'integer'],
            [['kpi'], 'string', 'max' => 300],
            [['kpi_cumplimiento'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_empresa' => Yii::t('app', 'Id Empresa'),
            'id_superior' => Yii::t('app', 'Id Superior'),
            'nivel' => Yii::t('app', 'Nivel'),
            'kpi' => Yii::t('app', 'Kpi'),
            'id_programa' => Yii::t('app', 'Id Programa'),
            'status' => Yii::t('app', 'Status'),
            'status_baja' => Yii::t('app', 'Status Baja'),
        ];
    }

    public function getPrograma()
    {
        return $this->hasOne(ProgramaSalud::class, ['id' => 'id_programa']);
    }

    public function getActualiza()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'kpi_actualiza']);
    }

}
