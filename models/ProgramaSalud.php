<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programa_salud".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property string|null $color
 * @property int|null $vigencia
 * @property int|null $status
 */
class ProgramaSalud extends \yii\db\ActiveRecord
{
    public $empresas_select2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programa_salud';
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
            [['descripcion'], 'string'],
            [['empresas_select2'], 'safe'],
            [['vigencia', 'status'], 'integer'],
            [['nombre'], 'string', 'max' => 300],
            [['color'], 'string', 'max' => 30],
            [['nombre','empresas_select2','status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'descripcion' => Yii::t('app', 'Descripción'),
            'color' => Yii::t('app', 'Color'),
            'vigencia' => Yii::t('app', 'Vigencia'),
            'status' => Yii::t('app', 'Status'),
            'empresas_select2' => Yii::t('app', 'Empresas Donde Aplica'),
        ];
    }

    public function getEmpresas()
    {
        return $this->hasMany(Programasaludempresa::className(), ['id_programa' => 'id']);
    }
}
