<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmas".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $universidad
 * @property string|null $cedula
 * @property string|null $firma
 * @property string|null $titulo
 * @property string|null $abreviado_titulo
 * @property string|null $registro_sanitario
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $status
 */
class Firmas extends \yii\db\ActiveRecord
{
    public $file_firma;
    public $empresas_select2;
    public $envia_form;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmas';
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
            [['file_firma'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['fecha_inicio', 'fecha_fin','empresas_select','empresas_select2'], 'safe'],
            [['status','id_empresa','id_pais','id_linea','id_ubicacion','envia_form','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['nombre', 'universidad'], 'string', 'max' => 300],
            [['cedula', 'registro_sanitario'], 'string', 'max' => 50],
            [['firma'], 'string', 'max' => 200],
            [['titulo'], 'string', 'max' => 100],
            [['abreviado_titulo'], 'string', 'max' => 10],
            [['id_empresa','nombre','cedula','fecha_inicio','fecha_fin'], 'required','on' =>['create','update']],
            
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
            'nombre' => Yii::t('app', 'Nombre'),
            'universidad' => Yii::t('app', 'Universidad'),
            'cedula' => Yii::t('app', 'Cedula'),
            'firma' => Yii::t('app', 'Firma'),
            'titulo' => Yii::t('app', 'Titulo'),
            'abreviado_titulo' => Yii::t('app', 'Abreviado Titulo'),
            'registro_sanitario' => Yii::t('app', 'Registro Sanitario'),
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'fecha_fin' => Yii::t('app', 'Fecha Fin'),
            'status' => Yii::t('app', 'Status'),
            'empresas_select' => Yii::t('app', 'Empresas Donde Aplica la Firma'),
            'empresas_select2' => Yii::t('app', 'Empresas Donde Aplica la Firma'),
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

    public function getEmpresas()
    {
        return $this->hasMany(Firmaempresa::className(), ['id_firma' => 'id']);
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
