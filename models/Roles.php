<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $color
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
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
            [['nombre'], 'string', 'max' => 300],
            [['color'], 'string', 'max' => 10],
            [['id_empresa','id_pais','id_linea','admite_firma','tiempo_limitado','tiempo_dias','tiempo_horas','tiempo_minutos','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
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
            'nombre' => Yii::t('app', 'Rol'),
            'color' => Yii::t('app', 'Color'),
            'tiempo_limitado' => Yii::t('app', 'Limitar Tiempo de Edición'),
            'tiempo_dias' => Yii::t('app', 'Días'),
            'tiempo_horas' => Yii::t('app', 'Horas'),
            'tiempo_minutos' => Yii::t('app', 'Minutos'),
        ];
    }

    
    public function getPermisos()
    {
        return $this->hasMany(Rolpermiso::className(),  ['id_rol' => 'id'])->where(['status'=>1])->orderBy(['id'=>SORT_ASC]);
    }

    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(),  ['rol' => 'id'])->orderBy(['id'=>SORT_ASC]);
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
