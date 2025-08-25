<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "poes".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $id_trabajador
 * @property string|null $nombre
 * @property string|null $apellidos
 * @property int|null $sexo
 * @property string|null $fecha_nacimiento
 * @property int|null $anio
 * @property string|null $num_imss
 * @property int|null $id_puesto
 * @property int|null $id_ubicacion
 * @property string|null $observaciones
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property int|null $status
 */
class Poes extends \yii\db\ActiveRecord
{
    public $aux_estudios = [];
    public $aux_entregados = [];
    public $firmatxt;
    public $firmado;

    public $src_empresa;
    public $src_trabajador;
    public $src_condicion;
    public $src_area;
    public $src_puesto;
    public $src_anio;
    public $src_fecha;
    public $src_estudio;
    public $src_diagnostico;
    public $src_evaluacion;
    public $src_categoria;
    public $src_entrega;
    public $src_evidencia;

    public $consentimiento;
  

    public $model_;

    public $file_fotoweb;
    public $txt_base64_foto;
    public $txt_base64_ine;
    public $txt_base64_inereverso;

    public $envia_form;

    public $file_evidencia_consentimiento;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poes';
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
            [['file_evidencia_consentimiento'], 'file','extensions' => 'png, jpg, jpeg, pdf', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['src_empresa','src_condicion','src_anio','src_diagnostico','src_evaluacion','src_categoria','src_entrega','src_evidencia','envia_form'], 'integer'],
            [['src_trabajador','src_area','src_puesto','src_fecha','src_estudio'], 'safe'],
            [['origen','id_ordenpoetrabajador','id_empresa','id_pais','id_linea', 'id_trabajador', 'sexo', 'anio', 'id_puesto', 'id_ubicacion', 'create_user', 'update_user', 'delete_user', 'status','status_entrega','tipo_identificacion','firmado','cerrar_entrega','id_nivel1','id_nivel2','id_nivel3','id_nivel4','model_','tipo_poe','tipo_consentimiento','status_backup'], 'integer'],
            [['fecha_nacimiento', 'create_date', 'update_date', 'delete_date', 'aux_estudios','firma','firmatxt','aux_entregados','fecha_entrega','hora_entrega'], 'safe'],
            [['observaciones'], 'string'],
            [['nombre', 'apellidos','nombre_empresa','nombre_medico'], 'string', 'max' => 100],
            [['firma_ruta'], 'string', 'max' => 200],
            [['num_imss'], 'string', 'max' => 20],
            [['num_trabajador'], 'string', 'max' => 10],
            [['numero_identificacion'], 'string', 'max' => 100],

            [['evidencia_consentimiento'], 'string', 'max' => 300],

            [['uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','tipo_identificacion_p','numero_identificacion_p','foto_web','base64_foto','fecha','hora','txt_base64_foto','txt_base64_ine','txt_base64_inereverso','nombre_empresa'], 'safe'],
            /* [['model_'], 'required','on' =>['create','update']], */

            [['tipo_poe','id_empresa','id_trabajador','sexo','anio','nombre', 'apellidos','fecha_nacimiento'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#poes-envia_form').val() == '1';
            }"],
            /* [['id_empresa','id_trabajador','sexo','anio','nombre', 'apellidos','fecha_nacimiento'], 'required','on' =>['create','update']], */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tipo_poe' => Yii::t('app', 'Clasificación'),
            'origen' => Yii::t('app', 'Origen'),
            'id_ordenpoetrabajador' => Yii::t('app', 'Orden de Trabajo'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_trabajador' => Yii::t('app', 'Trabajador'),
            'id_area' => Yii::t('app', 'Área'),
            'nombre' => Yii::t('app', 'Nombre'),
            'apellidos' => Yii::t('app', 'Apellidos'),
            'sexo' => Yii::t('app', 'Sexo'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'anio' => Yii::t('app', 'Año del POE'),
            'num_imss' => Yii::t('app', 'N° IMSS'),
            'id_puesto' => Yii::t('app', 'Puesto de Trabajo'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Usuario Registró'),
            'update_date' => Yii::t('app', 'Fecha Actualiza'),
            'update_user' => Yii::t('app', 'Usuario Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Elimina'),
            'delete_user' => Yii::t('app', 'Fecha Eliminó'),
            'id_poeanterior' => Yii::t('app', 'POE Anterior'),
            'status' => Yii::t('app', 'Status'),
            'src_empresa' => Yii::t('app', 'Empresa'),
            'src_trabajador' => Yii::t('app', 'Trabajador'),
            'src_condicion' => Yii::t('app', 'Condición'),
            'src_area' => Yii::t('app', 'Área'),
            'src_puesto' => Yii::t('app', 'Puesto de Trabajo'),
            'src_anio' => Yii::t('app', 'Año del POE'),
            'src_fecha' => Yii::t('app', 'Fecha Registro'),
            'src_estudio' => Yii::t('app', 'Estudio'),
            'src_diagnostico' => Yii::t('app', 'Diagnóstico'),
            'src_evaluacion' => Yii::t('app', 'Evolución'),
            'src_categoria' => Yii::t('app', 'Categoria'),
            'src_entrega' => Yii::t('app', 'Entrega'),
            'src_evidencia' => Yii::t('app', 'Evidencia'),


            'tipo_identificacion_p' => Yii::t('app', 'Tipo Identificación'),
            'numero_identificacion_p' => Yii::t('app', 'Número de Identificación'),

            'evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
            'file_evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),

            'nombre_empresa' => Yii::t('app', 'Empresa'),
            'nombre_medico' => Yii::t('app', 'Médico'),
        ];
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
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

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuesto()
    {
        return $this->hasOne(Puestostrabajo::className(), ['id' => 'id_puesto']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::className(), ['id' => 'id_area']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Poeestudio::className(), ['id_poe' => 'id'])->orderBy(['orden'=>SORT_ASC]);
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

    public function getPoesanteriores()
    {
        return $this->hasMany(Poes::className(), ['id_trabajador' => 'id_trabajador'])->where(['<>','status',2])->orderBy(['id'=>SORT_DESC]);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }


    public function getNivel1()
    {
        return $this->hasOne(NivelOrganizacional1::class, ['id' => 'id_nivel1']);
    }
    public function getNivel2()
    {
        return $this->hasOne(NivelOrganizacional2::class, ['id' => 'id_nivel2']);
    }
    public function getNivel3()
    {
        return $this->hasOne(NivelOrganizacional3::class, ['id' => 'id_nivel3']);
    }
    public function getNivel4()
    {
        return $this->hasOne(NivelOrganizacional4::class, ['id' => 'id_nivel4']);
    }
}
