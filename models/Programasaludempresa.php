<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programasalud_empresa".
 *
 * @property int $id
 * @property int|null $id_programa
 * @property int|null $id_empresa
 */
class Programasaludempresa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programasalud_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programa', 'id_empresa'], 'default', 'value' => null],
            [['id_programa', 'id_empresa'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_programa' => Yii::t('app', 'Id Programa'),
            'id_empresa' => Yii::t('app', 'Id Empresa'),
        ];
    }

    public function getEmpresa()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'id_empresa']);
    }

    public function getPrograma()
    {
        return $this->hasOne(ProgramaSalud::className(), ['id' => 'id_programa']);
    }

}
