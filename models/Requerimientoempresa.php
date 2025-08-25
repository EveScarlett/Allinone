<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requerimiento_empresa".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_tipo
 * @property int|null $id_estudio
 * @property int|null $id_periodicidad
 * @property int|null $id_status
 */
class Requerimientoempresa extends \yii\db\ActiveRecord
{
    public $destudio;
    public $dtipo;
    public $dperiodicidad;
    public $dstatus;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requerimiento_empresa';
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
            [['id_empresa','id_pais','id_linea', 'id_tipo', 'id_estudio', 'id_periodicidad', 'id_status','destudio','dtipo','dperiodicidad','dstatus','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha_apartir'],'safe']
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

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_tipo' => Yii::t('app', 'Id Tipo'),
            'id_estudio' => Yii::t('app', 'Id Estudio'),
            'id_periodicidad' => Yii::t('app', 'Id Periodicidad'),
            'fecha_apartir' => Yii::t('app', 'Requerido Desde el Día'),
            'id_status' => Yii::t('app', 'Id Status'),
        ];
    }

    /**
     * Gets query for [[Estudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudio()
    {
        return $this->hasOne(Estudios::className(), ['id' => 'id_estudio']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }
}
