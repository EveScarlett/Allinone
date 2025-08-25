<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nivel_organizacional1".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_pais
 * @property string|null $nivelorganizacional1
 * @property int|null $status
 */
class NivelOrganizacional1 extends \yii\db\ActiveRecord
{

    public $aux_areas = [];
    public $aux_kpis = [];
    public $aux_puestos = [];

    public $aux_consultorios = [];
    public $aux_programas = [];
    public $aux_turnos = [];

    public $aux_dias_sin_accidentes;
    public $aux_fecha_dias_sin_accidentes;
    public $aux_actualiza_dias_sin_accidentes;

    public $nivel;
    public $id_nivelorganizacional1;
    public $id_nivelorganizacional2;
    public $id_nivelorganizacional3;
    public $id_nivelorganizacional4;

    public $dias_sin_dig_0;
    public $dias_sin_dig_1;
    public $dias_sin_dig_2;
    public $dias_sin_dig_3;
    public $dias_sin_dig_4;

    public $actualiza_usuario;
    public $actualiza_fecha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nivel_organizacional1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'id_pais', 'nivelorganizacional1', 'status'], 'default', 'value' => null],
            [['aux_areas', 'aux_kpis', 'aux_puestos', 'aux_consultorios', 'aux_programas', 'aux_turnos','kpi_objetivo','kpi_real','kpi_cumplimiento','kpi_fecha','fecha_dias_sin_accidentes','aux_dias_sin_accidentes','aux_fecha_dias_sin_accidentes','aux_actualiza_dias_sin_accidentes','cumplimiento_dias_sin_accidentes','actualiza_usuario','actualiza_fecha'], 'safe'],
            [['id_empresa', 'id_pais', 'status','qty_trabajadores','kpi_actualiza','dias_sin_accidentes','actualiza_dias_sin_accidentes','nivel','id_nivelorganizacional1','id_nivelorganizacional2','id_nivelorganizacional3','id_nivelorganizacional4','objetivo_dias_sin_accidentes','record_dias_sin_accidentes','dias_sin_dig_0','dias_sin_dig_1','dias_sin_dig_2','dias_sin_dig_3','dias_sin_dig_4','accidentes_anio_dias_sin_accidentes'], 'integer'],
            [['nivelorganizacional1'], 'string', 'max' => 400],
            [['id_empresa','id_pais','status'], 'required','on' =>['create','update']],
            [['kpi_descripcion'], 'string', 'max' => 500],
            [['kpi_responsable'], 'string', 'max' => 100],
            [['comentario_dias_sin_accidentes','actividad'], 'string', 'max' => 1000],
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
            'id_pais' => Yii::t('app', 'País'),
            'nivelorganizacional1' => Yii::t('app', 'Nivel Organizacional 1'),
            'actividad' => Yii::t('app', 'Actividad de la Empresa'),
            'status' => Yii::t('app', 'Status'),
            'dias_sin_accidentes' => Yii::t('app', 'QTY Días'),
            'fecha_dias_sin_accidentes' => Yii::t('app', 'Ultima Actualización'),
            'objetivo_dias_sin_accidentes' => Yii::t('app', 'QTY Máxima aceptable de Accidentes por Año'),
            'actualiza_dias_sin_accidentes' => Yii::t('app', 'Actualizó'),
            'aux_dias_sin_accidentes' => Yii::t('app', 'QTY Días'),
            'aux_fecha_dias_sin_accidentes' => Yii::t('app', 'Ultima Actualización'),
            'aux_actualiza_dias_sin_accidentes' => Yii::t('app', 'Actualizó'),
            'comentario_dias_sin_accidentes' => Yii::t('app', 'Comentarios'),
            'record_dias_sin_accidentes' => Yii::t('app', 'Record dias sin accidentes'),
            'cumplimiento_dias_sin_accidentes' => Yii::t('app', 'Cumplimiento'),
            'accidentes_anio_dias_sin_accidentes' => Yii::t('app', 'Accidentes Año'),
        ];
    }

    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getActualizadas()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'actualiza_dias_sin_accidentes']);
    }

}
