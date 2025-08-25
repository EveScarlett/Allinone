<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consentimientos".
 *
 * @property int $id
 * @property int|null $id_consentimiento
 * @property int|null $id_documento
 * @property string|null $fecha
 * @property string|null $hora
 * @property string|null $razon
 * @property string|null $comercial
 * @property string|null $rfc
 * @property string|null $telefono
 * @property string|null $correo
 * @property int|null $id_empresa
 * @property int|null $id_trabajador
 * @property string|null $nombre
 * @property string|null $apellidos
 * @property int|null $id_area
 * @property int|null $id_puesto
 * @property string|null $fecha_nacimiento
 * @property int|null $edad
 * @property int|null $sexo
 * @property int|null $firma
 * @property string|null $firma_ruta
 * @property int|null $status
 */
class Consentimientos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consentimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_consentimiento', 'id_documento', 'id_empresa', 'id_trabajador', 'id_area', 'id_puesto', 'edad', 'sexo', 'firma', 'status','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha', 'hora', 'fecha_nacimiento'], 'safe'],
            [['razon', 'comercial'], 'string', 'max' => 300],
            [['rfc', 'telefono'], 'string', 'max' => 30],
            [['correo', 'nombre', 'apellidos'], 'string', 'max' => 100],
            [['firma_ruta'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),

            'id_consentimiento' => 'Id Consentimiento',
            'id_documento' => 'Id Documento',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'razon' => 'Razon',
            'comercial' => 'Comercial',
            'rfc' => 'Rfc',
            'telefono' => 'Telefono',
            'correo' => 'Correo',
            'id_empresa' => 'Empresa',
            'id_trabajador' => 'Trabajador',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'id_area' => 'Área',
            'id_puesto' => 'Puesto',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'edad' => 'Edad',
            'sexo' => 'Sexo',
            'firma' => 'Firma',
            'firma_ruta' => 'Firma Ruta',
            'status' => 'Status',
        ];
    }
}
