<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firma_empresa".
 *
 * @property int $id
 * @property int|null $id_firma
 * @property int|null $id_empresa
 */
class Firmaempresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firma_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_firma', 'id_empresa','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_firma' => Yii::t('app', 'Id Firma'),
            'id_empresa' => Yii::t('app', 'Id Empresa'),
            
            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
        ];
    }

    public function getEmpresa()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'id_empresa']);
    }
}
