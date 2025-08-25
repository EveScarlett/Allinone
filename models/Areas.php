<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "areas".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $area
 * @property int|null $status
 */
class Areas extends \yii\db\ActiveRecord
{
    public $envia_form;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'areas';
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
            [['id_empresa','envia_form','id_pais','id_linea', 'status','id_superior','nivel'], 'integer'],
            [['area'], 'string', 'max' => 300],
            [['id_empresa','id_pais','id_linea','area','status'], 'required','on' =>['create','update']],
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
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_superior' => Yii::t('app', 'Nivel Superior'),
            'nivel' => Yii::t('app', 'Nivel'),
            'area' => Yii::t('app', 'Área'),
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
