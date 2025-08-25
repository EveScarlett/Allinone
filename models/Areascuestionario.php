<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "areascuestionario".
 *
 * @property int $id
 * @property int $id_tipo_cuestionario
 * @property string $id_pregunta
 * @property int $nombre
 * @property int $status
 */
class Areascuestionario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'areascuestionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_cuestionario', 'id_pregunta', 'nombre', 'status'], 'required'],
            [['id_tipo_cuestionario', 'nombre', 'status'], 'integer'],
            [['id_pregunta'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_tipo_cuestionario' => Yii::t('app', 'Id Tipo Cuestionario'),
            'id_pregunta' => Yii::t('app', 'Id Pregunta'),
            'nombre' => Yii::t('app', 'Nombre'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[DetalleCuestionarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCuestionarios()
    {
        return $this->hasMany(DetalleCuestionario::class, ['id_area' => 'id']);
    }

    /**
     * Gets query for [[DetalleCuestionarios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCuestionarios0()
    {
        return $this->hasMany(DetalleCuestionario::class, ['id_cuestionario' => 'id']);
    }

    /**
     * Gets query for [[TipoCuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoCuestionario()
    {
        return $this->hasOne(TipoCuestionario::class, ['id' => 'id_tipo_cuestionario']);
    }
}
