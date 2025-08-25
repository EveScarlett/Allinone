<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bitacora".
 *
 * @property int $id
 * @property int $id_usuario_creo
 * @property string $nombre_creo
 * @property string $fecha_creacion
 * @property int $id_usuario_actualizo
 * @property string $nombre_actualizo
 * @property string $fecha_actualizacion
 */
class Bitacora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bitacora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['id_usuario_creo', 'id_usuario_actualizo','id_empresa','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['fecha_creacion', 'fecha_actualizacion'], 'safe'],
            [['nombre_creo', 'nombre_actualizo'], 'string', 'max' => 200],
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

            'id_usuario_creo' => 'Id Usuario Creo',
            'nombre_creo' => 'Nombre Creo',
            'fecha_creacion' => 'Fecha Creacion',
            'id_usuario_actualizo' => 'Id Usuario Actualizo',
            'nombre_actualizo' => 'Nombre Actualizo',
            'fecha_actualizacion' => 'Fecha Actualizacion',
        ];
    }

    /**
     * Crea un registro de creacion o actualización en la bitacora.
     * @param string $escenario Use 'crear' para crear un nuevo registro, o 'actualizar' para actualizar un registro.
     * @param int|null $id Indique el Id del registro, se solicita en el escenario de 'actualizar'
     * @return bool|object true, si se completa la accion, false si no se completa.
     */
    public static function bitacora($escenario, $id = null) {
        
        switch ($escenario) {
            case 'crear':
                $bitacora = new Bitacora();
                $bitacora->id_usuario_creo = Yii::$app->user->identity->id;
                $bitacora->nombre_creo = Yii::$app->user->identity->name;
                $bitacora->fecha_creacion = Date("Y-m-d H:i:s");
                return ($bitacora->save()) ? $bitacora : false;
                break;
            case 'actualizar':
                $bitacora = Bitacora::findOne($id);
                $bitacora->id_usuario_actualizo = Yii::$app->user->identity->id;
                $bitacora->nombre_actualizo = Yii::$app->user->identity->name;
                $bitacora->fecha_actualizacion = Date("Y-m-d H:i:s");
                return ($bitacora->update()) ? $bitacora : false;
                break;
            default:
                return false;
                break;
        }
    }

    public static function bitacoraToken($id_paciente, $escenario, $id = null)
    {
        switch ($escenario) {
            case 'crear':
                $bitacora = new Bitacora();
                $bitacora->id_usuario_creo = null;
                $bitacora->nombre_creo = Pacientes::getName($id_paciente);
                $bitacora->fecha_creacion = Date("Y-m-d H:i:s");
                return ($bitacora->save()) ? $bitacora : false;
                break;
            case 'actualizar':
                $bitacora = Bitacora::findOne($id);
                $bitacora->id_usuario_actualizo = Yii::$app->user->identity->id;
                $bitacora->nombre_actualizo = Yii::$app->user->identity->name;
                $bitacora->fecha_actualizacion = Date("Y-m-d H:i:s");
                return ($bitacora->update()) ? $bitacora : false;
                break;
            default:
                return false;
                break;
        }
    }
}
