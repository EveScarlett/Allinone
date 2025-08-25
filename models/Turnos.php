<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "turnos".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $turno
 * @property int|null $status
 */
class Turnos extends \yii\db\ActiveRecord
{
    public $envia_form;
    public $aux_personal=[];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'turnos';
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
            [['id_empresa','id_pais','id_linea', 'status',
            'lunes_inicio','lunes_fin','martes_inicio','martes_fin','miercoles_inicio','miercoles_fin','jueves_inicio','jueves_fin',
            'viernes_inicio','viernes_fin','sabado_inicio','sabado_fin','domingo_inicio','domingo_fin',
            'requiere_enfermeros','requiere_medicos','requiere_extras','cantidad_enfermeros','cantidad_medicos','cantidad_extras','orden','id_superior','nivel','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['turno'], 'string', 'max' => 500],
            [['id_empresa','id_pais','id_linea','turno','status'], 'required','on' =>['create','update']],
            
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
            'id_superior' => Yii::t('app', 'Nivel Superior'),
            'nivel' => Yii::t('app', 'Nivel'),
            'turno' => Yii::t('app', 'Turno'),
            'orden' => Yii::t('app', 'Orden'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasMany(Turnopersonal::className(), ['id_turno' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    public function getEmpresa()
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
