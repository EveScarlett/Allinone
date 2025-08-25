<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historico_es".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_trabajador
 * @property int|null $id_maquina
 * @property int|null $status_trabajo
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $status
 * @property string|null $create_date
 * @property int|null $create_user
 */
class Historicoes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historico_es';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_model','id_empresa','id_pais','id_linea','id_ubicacion', 'id_trabajador', 'id_maquina', 'status_trabajo', 'fecha_inicio', 'fecha_fin', 'status', 'create_date', 'create_user'], 'default', 'value' => null],
            [['id_empresa', 'id_trabajador', 'id_maquina', 'status_trabajo', 'status', 'create_user','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'create_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_model' => 'Model',
            'id_empresa' => 'Empresa',

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_trabajador' => 'Operador',
            'id_maquina' => 'Máquina',
            'status_trabajo' => 'Status Trabajo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'status' => 'Status',
            'create_date' => 'Fecha Registro',
            'create_user' => 'Registró',
        ];
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
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaquina()
    {
        return $this->hasOne(Maquinaria::class, ['id' => 'id_maquina']);
    }

     public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }

}
