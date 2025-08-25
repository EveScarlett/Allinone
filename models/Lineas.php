<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lineas".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $linea
 * @property int|null $status
 * @property int|null $soft_delete
 */
class Lineas extends \yii\db\ActiveRecord
{

    public $envia_form;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lineas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_pais', 'linea', 'status', 'soft_delete'], 'default', 'value' => null],
            [['id_empresa','id_pais', 'status', 'soft_delete','envia_form','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['linea'], 'string', 'max' => 300],
            [['id_empresa','id_pais','linea','status'], 'required','on' =>['create','update']],
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
            'linea' => Yii::t('app', 'Linea'),
            'status' => Yii::t('app', 'Status'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
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
