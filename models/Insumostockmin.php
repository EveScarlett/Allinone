<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insumo_stockmin".
 *
 * @property int $id
 * @property int|null $id_insumo
 * @property int|null $id_consultorio
 * @property int|null $stock
 * @property int|null $stock_unidad
 */
class Insumostockmin extends \yii\db\ActiveRecord
{
    public $id_empresa;
    public $cantidad_caja;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insumo_stockmin';
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
            [['id_insumo', 'tipo_insumo','id_consultorio', 'stock', 'stock_unidad','id_empresa','cantidad_caja'], 'integer'],
            [['id_insumo','id_consultorio','stock'], 'required','on' =>['create','update']],
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
            'id_insumo' => Yii::t('app', 'Insumo'),
            'tipo_insumo' => Yii::t('app', 'Tipo Insumo'),
            'id_consultorio' => Yii::t('app', 'Consultorio'),
            'stock' => Yii::t('app', 'Stock Mínimo'),
            'stock_unidad' => Yii::t('app', 'Stock Mínimo por Unidad'),
        ];
    }

    /**
     * Gets query for [[Insumos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsumo()
    {
        return $this->hasOne(Insumos::class, ['id' => 'id_insumo']);
    }

    /**
     * Gets query for [[Consultorio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorio()
    {
        return $this->hasOne(Consultorios::class, ['id' => 'id_consultorio']);
    }

    /**
     * Gets query for [[Cuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        //return $this->hasOne(Cuestionario::class, ['id' => 'id_cuestionario'])->viaTable('pacientes',['id' => 'id_paciente']);
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa'])->viaTable('consultorios',['id' => 'id_consultorio']);
    }

}
