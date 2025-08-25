<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historico_diassinaccidentes".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_superior
 * @property int|null $nivel
 * @property int|null $dias_sin_accidentes
 * @property string|null $fecha_dias_sin_accidentes
 * @property int|null $actualiza_dias_sin_accidentes
 */
class Historicodiassinaccidentes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historico_diassinaccidentes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_superior', 'nivel', 'dias_sin_accidentes', 'fecha_dias_sin_accidentes', 'actualiza_dias_sin_accidentes'], 'default', 'value' => null],
            [['id_empresa', 'id_superior', 'nivel','objetivo_dias_sin_accidentes', 'accidentes_anio_dias_sin_accidentes', 'dias_sin_accidentes', 'actualiza_dias_sin_accidentes','record_dias_sin_accidentes'], 'integer'],
            [['fecha_dias_sin_accidentes','cumplimiento_dias_sin_accidentes'], 'safe'],
            [['comentario_dias_sin_accidentes'], 'string', 'max' => 1000],
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
            'objetivo_dias_sin_accidentes' => Yii::t('app', 'QTY Máxima aceptable de Accidentes por Año'),
            'accidentes_anio_dias_sin_accidentes' => Yii::t('app', 'Accidentes Año'),
            'dias_sin_accidentes' => Yii::t('app', 'Dias Sin Accidentes'),
            'fecha_dias_sin_accidentes' => Yii::t('app', 'Fecha Dias Sin Accidentes'),
            'actualiza_dias_sin_accidentes' => Yii::t('app', 'Actualiza Dias Sin Accidentes'),
            'comentario_dias_sin_accidentes' => Yii::t('app', 'Comentarios'),
            'record_dias_sin_accidentes' => Yii::t('app', 'Record dias sin accidentes'),
            'cumplimiento_dias_sin_accidentes' => Yii::t('app', 'Cumplimiento'),
        ];
    }

}
