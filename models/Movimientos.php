<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movimientos".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $folio
 * @property int|null $tipo
 * @property int|null $id_consultorio
 * @property int|null $id_consultorio2
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property string|null $observaciones
 * @property int|null $movimiento_relacionados
 * @property int|null $status
 */
class Movimientos extends \yii\db\ActiveRecord
{
    public $titulo;
    public $aux_medicamentos = [];
    public $aux_medicamentossalida = [];

    public $aux_epp = [];
    public $aux_eppsalida = [];

    public $envia_empresa;
    public $envia_consultorio;
    public $envia_trabajador;
    public $envia_form;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movimientos';
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
            [['id_empresa','id_pais','id_linea','id_ubicacion', 'e_s', 'tipo', 'id_consultorio', 'id_consultorio2', 'create_user', 'update_user', 'delete_user', 'movimiento_relacionados', 'status','envia_empresa','envia_consultorio','envia_trabajador','id_consultahc','envia_form','e_s','tipo_insumo','id_trabajador','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['create_date', 'update_date', 'delete_date','aux_medicamentos','aux_medicamentossalida','aux_epp','aux_eppsalida','fechaultentrega'], 'safe'],
            [['observaciones'], 'string'],
            [['folio','titulo'], 'string', 'max' => 30],
            [['motivoentrega'], 'string', 'max' => 500],
            [['id_empresa'], 'required','on' =>['create','update']],
            [['id_empresa','e_s','tipo','create_date','folio','id_consultorio'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#movimientos-envia_form').val() == '1';
            }"],
            [['id_consultorio'], 'required', 'when' => function($model) {
                return $model->tipo == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#movimientos-tipo').val() == '2';
            }"],
           /*  [['id_trabajador','motivoentrega'], 'required', 'when' => function($model) {
                return $model->tipo == '9';
            }, 'whenClient' => "function (attribute, value) {
                return $('#movimientos-tipo').val() == '9';
            }"], */
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
            'folio' => Yii::t('app', 'Folio'),
            'tipo' => Yii::t('app', 'Tipo Movimiento'),
            'e_s' => Yii::t('app', 'Entrada/Salida'),
            'id_consultorio' => Yii::t('app', 'Consultorio'),
            'id_consultorio2' => Yii::t('app', 'Almacén Origen'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Registró'),
            'update_date' => Yii::t('app', 'Fecha Actualización'),
            'update_user' => Yii::t('app', 'Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Baja'),
            'delete_user' => Yii::t('app', 'Eliminó'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'id_trabajador' => Yii::t('app', 'Entregado al Trabajador'),
            'fechaultentrega' => Yii::t('app', 'Última entrega de EPP'),
            'motivoentrega' => Yii::t('app', 'Motivo de Entrega'),
            'movimiento_relacionados' => Yii::t('app', 'Movimiento Relacionado'),
            'status' => Yii::t('app', 'Status'),
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
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorio1()
    {
        return $this->hasOne(Consultorios::class, ['id' => 'id_consultorio']);
    }


    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultorio2()
    {
        return $this->hasOne(Consultorios::class, ['id' => 'id_consultorio2']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentos()
    {
        return $this->hasMany(Detallemovimiento::className(), ['id_movimiento' => 'id']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsulta()
    {
        return $this->hasOne(Consultas::class, ['id' => 'id_consultahc']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_trabajador']);
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
