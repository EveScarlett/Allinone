<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diagnosticoscie".
 *
 * @property int $id
 * @property int|null $id_grupo
 * @property string|null $clave
 * @property string|null $clave_epi
 * @property string|null $diagnostico
 * @property int|null $cie_version
 */
class Diagnosticoscie extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnosticoscie';
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
            [['cie_version'], 'integer'],
            [['clave', 'clave_epi'], 'string', 'max' => 50],
            [['diagnostico'], 'string', 'max' => 3000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'clave' => Yii::t('app', 'Clave'),
            'clave_epi' => Yii::t('app', 'Clave Epi'),
            'diagnostico' => Yii::t('app', 'Diagnóstico'),
            'cie_version' => Yii::t('app', 'CIE Versión'),
        ];
    }
}
