<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servicios".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $nombre_completo
 * @property int|null $id_tiposervicio
 * @property int|null $orden
 * @property int|null $id_pruebalab
 */
class Servicios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicios';
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
            [['id_empresa','id_pais','id_linea','id_tiposervicio', 'orden', 'id_pruebalab','orden','status','id_nivel1','id_nivel2','id_nivel3','id_nivel4','status_baja','status_backup'], 'integer'],
            [['nombre', 'nombre_completo'], 'string', 'max' => 300],
            [['id_tiposervicio','nombre','status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre Estudio'),
            'nombre_completo' => Yii::t('app', 'Nombre Completo'),
            'orden' => Yii::t('app', 'Orden'),
            'id_tiposervicio' => Yii::t('app', 'Categoría'),
            'id_pruebalab' => Yii::t('app', 'Prueba de Laboratorio | Insumos'),
        ];
    }

    /**
     * Gets query for [[Servicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoServicio()
    {
        return $this->hasOne(TipoServicios::class, ['id' => 'id_tiposervicio']);
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
