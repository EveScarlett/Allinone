<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "secciones".
 *
 * @property int $id
 * @property int $id_tipo_cuestionario
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $orden
 * @property int $hide_start
 * @property int $status
 *
 * @property TipoCuestionario $tipoCuestionario
 */
class Secciones extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVO = 1;
    const STATUS_INACTIVO = 0;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_cuestionario', 'nombre', 'orden', 'status'], 'required'],
            [['id_tipo_cuestionario', 'orden', 'hide_start', 'status'], 'integer'],
            [['nombre'], 'string', 'max' => 250],
            [['descripcion'], 'string', 'max' => 250],
            [['id_tipo_cuestionario'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCuestionario::class, 'targetAttribute' => ['id_tipo_cuestionario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tipo_cuestionario' => 'Id Tipo Cuestionario',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'orden' => 'Orden',
            'status' => 'Status',
        ];
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
