<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_cuestionario".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property int|null $status
 *
 * @property Areas[] $areas
 * @property Cuestionario[] $cuestionarios
 * @property DetalleCuestionario[] $detalleCuestionarios
 * @property Preguntas[] $preguntas
 */
class TipoCuestionario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_cuestionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['nombre'], 'string', 'max' => 250],
            [['descripcion'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areascuestionario::class, ['id_tipo_cuestionario' => 'id']);
    }

    /**
     * Gets query for [[Cuestionarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuestionarios()
    {
        return $this->hasMany(Cuestionario::class, ['id_tipo_cuestionario' => 'id']);
    }

    /**
     * Gets query for [[DetalleCuestionarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCuestionarios()
    {
        return $this->hasMany(DetalleCuestionario::class, ['id_tipo_cuestionario' => 'id']);
    }

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Preguntas::class, ['id_tipo_cuestionario' => 'id']);
    }
}
