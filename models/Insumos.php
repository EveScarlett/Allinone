<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insumos".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $tipo
 * @property string|null $nombre_comercial
 * @property string|null $nombre_generico
 * @property string|null $foto
 * @property string|null $concentracion
 * @property string|null $fabricante
 * @property string|null $formula
 * @property string|null $condiciones_conservacion
 * @property int|null $id_presentacion
 * @property int|null $id_unidad
 * @property int|null $cantidad
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property int|null $status
 */
class Insumos extends \yii\db\ActiveRecord
{
    public $otra_presentacion;
    public $otra_unidad;
    public $file_foto;
    public $otra_via_administracion;
    public $envia_empresa;
    public $envia_form;
    public $aux_stocks = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insumos';
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
            [['file_foto'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['id_empresa','id_pais','id_linea','id_ubicacion', 'tipo', 'id_presentacion', 'id_unidad', 'unidades_individuales', 'cantidad', 'stock_minimo', 'via_administracion', 'create_user', 'update_user', 'delete_user', 'status', 'envia_empresa', 'envia_form','color','talla','sexo','parte_corporal','categoria_epp','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['create_date', 'update_date', 'delete_date', 'aux_stocks'], 'safe'],
            [['nombre_comercial', 'nombre_generico'], 'string', 'max' => 500],
            [['advertencias'], 'string', 'max' => 1000],
            [['clave'], 'string', 'max' => 30],
            [['foto', 'concentracion', 'fabricante', 'formula', 'condiciones_conservacion','otra_presentacion','otra_unidad'], 'string', 'max' => 300],
            [['id_empresa'], 'required','on' =>['create','update']],
            [['tipo','nombre_comercial','id_presentacion'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#insumos-envia_form').val() == '1';
            }"],
           /*  [['talla'], 'required', 'when' => function($model) {
                return $model->tipo == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#insumos-tipo').val() == '2';
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
            'tipo' => Yii::t('app', 'Tipo'),
            'nombre_comercial' => Yii::t('app', 'Nombre Comercial'),
            'nombre_generico' => Yii::t('app', 'Nombre Genérico'),
            'foto' => Yii::t('app', 'Foto'),
            'concentracion' => Yii::t('app', 'Concentración'),
            'fabricante' => Yii::t('app', 'Fabricante'),
            'formula' => Yii::t('app', 'Sustancia Activa'),
            'condiciones_conservacion' => Yii::t('app', 'Condiciones de Conservación'),
            'id_presentacion' => Yii::t('app', 'Presentación'),
            'id_unidad' => Yii::t('app', 'Unidad'),
            'cantidad' => Yii::t('app', 'Cantidad'),
            'unidades_individuales' => Yii::t('app', 'Unidades x Caja'),
            'create_date' => Yii::t('app', 'Create Date'),
            'create_user' => Yii::t('app', 'Create User'),
            'update_date' => Yii::t('app', 'Update Date'),
            'update_user' => Yii::t('app', 'Update User'),
            'delete_date' => Yii::t('app', 'Delete Date'),
            'delete_user' => Yii::t('app', 'Delete User'),
            'otra_presentacion' => Yii::t('app', 'Otra Presentación'),
            'otra_unidad' => Yii::t('app', 'Otra Unidad'),
            'stock_minimo'=> Yii::t('app', 'Stock Mínimo'),
            'color'=> Yii::t('app', 'Color'),
            'talla'=> Yii::t('app', 'Talla'),
            'sexo'=> Yii::t('app', 'Genero'),
            'via_administracion' => Yii::t('app', 'Vía de Administración'),
            'otra_via_administracion' => Yii::t('app', 'Otra Vía de Administración'),
            'advertencias' => Yii::t('app', 'Advertencias y Precauciones'),
            'status' => Yii::t('app', 'Status'),
            'parte_corporal' => Yii::t('app', 'Área Corporal'),
            'categoria_epp' => Yii::t('app', 'Categoría EPP'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Usuario Registró'),
            'update_date' => Yii::t('app', 'Fecha Actualiza'),
            'update_user' => Yii::t('app', 'Usuario Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Elimina'),
            'delete_user' => Yii::t('app', 'Fecha Eliminó'),
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
     * Gets query for [[Presentaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacion()
    {
        return $this->hasOne(Presentaciones::class, ['id' => 'id_presentacion']);
    }

    /**
     * Gets query for [[Unidades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidad()
    {
        return $this->hasOne(Unidades::class, ['id' => 'id_unidad']);
    }

    /**
     * Gets query for [[Viasadministracion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViasadministracion()
    {
        return $this->hasOne(Viasadministracion::class, ['id' => 'via_administracion']);
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockconsultorios()
    {
        return $this->hasMany(Insumostockmin::className(), ['id_insumo' => 'id']);
    }

    public function getUCaptura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'create_user']);
    }

    public function getUActualiza()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'update_user']);
    }

    public function getUElimina()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'delete_user']);
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