<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_trabajador".
 *
 * @property int $id
 * @property int|null $id_trabajador
 * @property int|null $id_documento
 * @property string|null $fecha
 * @property string|null $documento
 * @property string|null $upload_date
 * @property int|null $upload_user
 */
class DocumentoTrabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_trabajador';
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
            [['id_trabajador', 'id_documento', 'upload_user'], 'integer'],
            [['fecha', 'upload_date'], 'safe'],
            [['documento'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_trabajador' => Yii::t('app', 'Trabajador'),
            'id_documento' => Yii::t('app', 'Documento'),
            'fecha' => Yii::t('app', 'Fecha'),
            'documento' => Yii::t('app', 'Documento'),
            'upload_date' => Yii::t('app', 'Fecha Subida'),
            'upload_user' => Yii::t('app', 'Quien Subió'),
        ];
    }
}
