<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacantes".
 *
 * @property int $id
 * @property int|null $id_puesto
 * @property string|null $titulo
 * @property string|null $descripcion
 * @property string|null $ubicacion
 * @property int|null $cantidad_vacantes
 * @property string|null $pais
 * @property float|null $salario
 * @property int|null $remoto
 * @property string|null $fecha_limite
 * @property int|null $status
 */
class Vacantes extends \yii\db\ActiveRecord
{
    public $aux_trabajadores = [];
    public $trabajador;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacantes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_puesto','id_empresa','id_pais','id_linea','id_ubicacion', 'cantidad_vacantes', 'remoto', 'status','create_user','update_user','vacantesllenas','trabajador','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['descripcion'], 'string'],
            [['salario'], 'number'],
            [['fecha_limite','aux_trabajadores'], 'safe'],
            [['titulo'], 'string', 'max' => 300],
            [['ubicacion','trabajadores'], 'string', 'max' => 500],
            [['pais'], 'string', 'max' => 200],
            [['create_date','update_date'], 'safe'],
            [['id'], 'required','on' =>['add']],
            [['id_empresa','id_puesto','titulo','cantidad_vacantes', 'status'], 'required','on' =>['create','update']],
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
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_puesto' => Yii::t('app', 'Puesto'),
            'titulo' => Yii::t('app', 'Título'),
            'descripcion' => Yii::t('app', 'Descripción de la vacante'),
            'ubicacion' => Yii::t('app', 'Ubicación'),
            'cantidad_vacantes' => Yii::t('app', 'Cantidad Vacantes'),
            'pais' => Yii::t('app', 'País'),
            'salario' => Yii::t('app', 'Salario'),
            'remoto' => Yii::t('app', 'Puesto Remoto'),
            'fecha_limite' => Yii::t('app', 'Fecha Limite'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Puestostrabajo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuesto()
    {
        return $this->hasOne(Puestostrabajo::class, ['id' => 'id_puesto']);
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

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadoresvac()
    {
        return $this->hasMany(Vacantetrabajador::className(), ['id_vacante' => 'id']);
    }

    public function getDatapais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getDataubicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }
}
