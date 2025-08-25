<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ordenespoes".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $anio
 * @property string|null $create_date
 * @property string|null $update_date
 * @property int|null $create_user
 * @property int|null $update_user
 */
class Ordenespoes extends \yii\db\ActiveRecord
{
    public $aux_estudios = [];
    public $lista_trabajadores = [];
    public $envia_empresa;
    public $envia_form;
    public $trabajadores_todos;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ordenespoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa','id_pais','id_linea','id_ubicacion', 'anio', 'create_user', 'update_user','envia_empresa','envia_form','trabajadores_todos'], 'integer'],
            [['create_date', 'update_date','lista_trabajadores','aux_estudios'], 'safe'],
            [['id_empresa'], 'required','on' =>['create','update']],
            [['anio'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#ordenespoes-envia_form').val() == '1';
            }"],
            [['descripcion'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_empresa' => 'Empresa',
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'descripcion' => 'Detalles OT',
            'anio' => 'Año POE',
            'create_date' => 'Fecha de Registro',
            'update_date' => 'Fecha de Actualización',
            'create_user' => 'Registra',
            'update_user' => 'Actualiza',
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
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Detalleordenpoe::className(), ['id_ordenpoe' => 'id']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadores()
    {
        return $this->hasMany(Ordenpoetrabajador::className(), ['id_ordenpoe' => 'id']);
    }

    public function getUCaptura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'create_user']);
    }

    public function getUActualiza()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'update_user']);
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