<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pais_empresa".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_pais
 * @property int|null $status
 */
class Paisempresa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pais_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_pais', 'status'], 'default', 'value' => null],
            [['id_empresa', 'id_pais', 'status'], 'integer'],
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
            'id_pais' => Yii::t('app', 'Id Pais'),
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

}
