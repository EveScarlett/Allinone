<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ubicaciones".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $ubicacion
 * @property int|null $status
 */
class Ubicaciones extends \yii\db\ActiveRecord
{
    public $envia_form;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ubicaciones';
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
            [['id_empresa','id_pais','id_linea', 'status', 'envia_form','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['ubicacion'], 'string', 'max' => 300],
            [['id_empresa','id_pais','id_linea','ubicacion','status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'ubicacion' => Yii::t('app', 'Ubicación'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
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
