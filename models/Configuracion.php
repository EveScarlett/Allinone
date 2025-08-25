<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $cantidad_trabajadores
 * @property int|null $cantidad_usuarios
 * @property int|null $cantidad_administradores
 */
class Configuracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['id', 'id_empresa','id_pais','id_linea', 'cantidad_trabajadores', 'cantidad_usuarios', 'cantidad_administradores','cantidad_medicos','cantidad_medicoslaborales','verseccion_maquina','verqr_trabajador','verqr_maquina','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['cantidad_trabajadores'], 'integer', 'min' => 1,'max' => 100000],
            [['cantidad_usuarios'], 'integer', 'min' => 1,'max' => 1000],
            [['cantidad_administradores'], 'integer', 'min' => 0,'max' => 100],
            [['cantidad_medicos'], 'integer', 'min' => 0,'max' => 1000],
            [['cantidad_medicoslaborales'], 'integer', 'min' => 0,'max' => 100],
            [['rolesfirma'], 'safe'],
            [['id_empresa', 'cantidad_trabajadores', 'cantidad_usuarios', 'cantidad_administradores','cantidad_medicos','cantidad_medicoslaborales'], 'required','on' =>['create','update']],
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
            'cantidad_trabajadores' => Yii::t('app', 'Cantidad de Trabajadores'),
            'cantidad_usuarios' => Yii::t('app', 'Cantidad de Usuarios'),
            'cantidad_administradores' => Yii::t('app', 'Cantidad de Administradores'),
            'cantidad_medicos' => Yii::t('app', 'Cantidad de Médicos'),
            'cantidad_medicoslaborales' => Yii::t('app', 'Cantidad de Médicos Laborales'),
            'verseccion_maquina' => Yii::t('app', 'Ver Módulo Máquinas'),
            'verqr_trabajador' => Yii::t('app', 'Agregar QR a Trabajadores'),
            'verqr_maquina' => Yii::t('app', 'Agregar QR a Máquinas'),
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDempresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }
}
