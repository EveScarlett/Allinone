<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configconsentimientos".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_tipoconsentimiento
 * @property string|null $texto_consentimiento
 * @property int|null $aviso_privacidad
 * @property string|null $texto_aviso
 * @property int|null $status
 */
class Configconsentimientos extends \yii\db\ActiveRecord
{
    public $titulo;
    public $parrafo;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configconsentimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa','id_pais','id_linea','id_ubicacion', 'id_tipoconsentimiento', 'aviso_privacidad', 'status','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['texto_consentimiento', 'texto_aviso'], 'string'],
            [['id_empresa', 'id_tipoconsentimiento', 'status','texto_consentimiento'], 'required','on' =>['create','update']],
            [['fecha_inicio', 'fecha_fin','titulo','parrafo'], 'safe'],
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

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),

            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_tipoconsentimiento' => 'Tipo de Consentimiento',
            'texto_consentimiento' => 'Consentimiento',
            'aviso_privacidad' => 'Requiere Aviso de Privacidad',
            'texto_aviso' => 'Aviso de Privacidad',
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'fecha_fin' => Yii::t('app', 'Fecha Fin'),
            'status' => 'Status',
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

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }
}
