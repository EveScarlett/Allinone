<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cie_consulta".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_nivel1
 * @property int|null $id_nivel2
 * @property int|null $id_nivel3
 * @property int|null $id_nivel4
 * @property int|null $id_consulta
 * @property int|null $id_cie
 */
class Cieconsulta extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cie_consulta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'id_consulta', 'id_cie'], 'default', 'value' => null],
            [['id_empresa', 'id_nivel1', 'id_nivel2', 'id_nivel3', 'id_nivel4', 'id_consulta', 'id_cie'], 'integer'],
            [['diagnostico'], 'string', 'max' => 3000],
            [['clave'], 'string', 'max' => 50],
            [['fecha'], 'safe'],
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
            'id_consulta' => Yii::t('app', 'Consulta'),
            'id_cie' => Yii::t('app', 'Diagnóstico Cie'),
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    public function getNivel1()
    {
        return $this->hasOne(NivelOrganizacional1::class, ['id' => 'id_nivel1']);
    }

    public function getNivel2()
    {
        return $this->hasOne(NivelOrganizacional2::class, ['id' => 'id_nivel2']);
    }

    public function getNivel3()
    {
        return $this->hasOne(NivelOrganizacional3::class, ['id' => 'id_nivel3']);
    }

    public function getNivel4()
    {
        return $this->hasOne(NivelOrganizacional4::class, ['id' => 'id_nivel4']);
    }

    public function getConsulta()
    {
        return $this->hasOne(Consultas::class, ['id' => 'id_consulta']);
    }

    public function getCie()
    {
        return $this->hasOne(Diagnosticoscie::class, ['id' => 'id_cie']);
    }

}
