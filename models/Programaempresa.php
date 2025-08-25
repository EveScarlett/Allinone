<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programa_empresa".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_programa
 * @property int|null $status
 */
class Programaempresa extends \yii\db\ActiveRecord
{
    public $envia_form;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programa_empresa';
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
            [['id_empresa','id_pais','id_linea', 'envia_form', 'id_programa', 'status','id_superior','nivel','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['id_empresa','id_pais','id_linea','id_programa','status'], 'required','on' =>['create','update']],
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
            'id_superior' => Yii::t('app', 'Nivel Superior'),
            'nivel' => Yii::t('app', 'Nivel'),
            'id_programa' => Yii::t('app', 'Programa de Salud'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgramasalud()
    {
        return $this->hasOne(ProgramaSalud::class, ['id' => 'id_programa']);
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
