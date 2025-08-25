<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_cuestionario".
 *
 * @property int $id
 * @property int $id_cuestionario
 * @property int $id_tipo_cuestionario
 * @property int $id_pregunta
 * @property int $id_area
 * @property string|null $respuesta_1
 * @property string|null $respuesta_2
 * @property int $status
 *
 * @property Areas $area
 * @property Areas $cuestionario
 * @property Preguntas $pregunta
 * @property TipoCuestionario $tipoCuestionario
 */
class DetalleCuestionario extends \yii\db\ActiveRecord
{
    public $_preguntas = [
        "p0" => [],
        "p1" => [],
        "p2" => [],
        "p3" => [],
        "p4" => [],
        "p5" => [],
        "p6" => [],
        "p7" => [],
        "p8" => [],
        "p9" => [],
        "p10" => [],
        "p11" => [],
    ];

    public $no_empleado;
    public $nombre_paciente;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_cuestionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['_preguntas'], 'required'],
            [['id_cuestionario', 'id_tipo_cuestionario', 'id_pregunta', 'id_area', 'status'], 'integer'],
            [['respuesta_1', 'respuesta_2'], 'string', 'max' => 500],
            [['_preguntas', 'no_empleado', 'nombre_paciente'], 'safe'],
            [['id_cuestionario'], 'exist', 'skipOnError' => true, 'targetClass' => Cuestionario::class, 'targetAttribute' => ['id_cuestionario' => 'id']],
            [['id_pregunta'], 'exist', 'skipOnError' => true, 'targetClass' => Preguntas::class, 'targetAttribute' => ['id_pregunta' => 'id']],
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
            'id_cuestionario' => 'Id Cuestionario',
            'id_tipo_cuestionario' => 'Id Tipo Cuestionario',
            'id_pregunta' => 'Id Pregunta',
            'id_area' => 'Id Area',
            'respuesta_1' => 'Respuesta 1',
            'respuesta_2' => 'Respuesta 2',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areascuestionario::class, ['id' => 'id_area']);
    }

    /**
     * Gets query for [[Cuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuestionario()
    {
        return $this->hasOne(Cuestionario::class, ['id' => 'id_cuestionario']);
    }

    /**
     * Gets query for [[Cuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuestionariopaciente()
    {
        //return $this->hasOne(Cuestionario::class, ['id' => 'id_cuestionario'])->viaTable('pacientes',['id' => 'id_paciente']);
        return $this->hasOne(Pacientes::class, ['id' => 'id_paciente'])->viaTable('cuestionario',['id' => 'id_cuestionario']);
    }

    /**
     * Gets query for [[Pregunta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPregunta()
    {
        return $this->hasOne(Preguntas::class, ['id' => 'id_pregunta']);
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

    /**
     * Gets query for [[Cuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuestionariotrabajador()
    {
        //return $this->hasOne(Cuestionario::class, ['id' => 'id_cuestionario'])->viaTable('pacientes',['id' => 'id_paciente']);
        return $this->hasOne(Trabajadores::class, ['id' => 'id_paciente'])->viaTable('cuestionario',['id' => 'id_cuestionario']);
    }
}
