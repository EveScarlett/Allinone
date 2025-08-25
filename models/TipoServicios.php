<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_servicios".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string $logo
 */
class TipoServicios extends \yii\db\ActiveRecord
{
    public $listadoestudios;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_servicios';
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
            [['nombre'], 'string', 'max' => 1000],
            [['logo'], 'string', 'max' => 200],
            [['status','id_empresa','id_pais','id_linea','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['listadoestudios'], 'safe'],
            [['nombre','logo','status'], 'required','on' =>['create','update']],
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
            'nombre' => Yii::t('app', 'Nombre'),
            'logo' => Yii::t('app', 'Color'),
            'listadoestudios' => Yii::t('app', 'Estudios'),
        ];
    }


    public function getServicios()
    {
        return $this->hasMany(Servicios::className(), ['id_tiposervicio' => 'id'])->orderBy(['orden'=>SORT_ASC]);
    }

    public function getServiciosactivos()
    {
        return $this->hasMany(Servicios::className(), ['id_tiposervicio' => 'id'])->orderBy(['orden'=>SORT_ASC])->where(['status'=>1]);
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
}
