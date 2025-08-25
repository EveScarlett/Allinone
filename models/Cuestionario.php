<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cuestionario".
 *
 * @property int $id
 * @property int $id_bitacora
 * @property int $id_tipo_cuestionario
 * @property int $id_empresa
 * @property int $id_paciente
 * @property int $id_medico
 * @property string $nombre_empresa
 * @property string|null $fecha_cuestionario
 * @property string|null $firma_paciente
 * @property int $es_local Indica si las FK son hacia la BD local o a la BD OHC
 * @property int|null $status
 *
 * @property ListadoTrabajadores $listadoTrabajador
 * @property TipoCuestionario $tipoCuestionario
 */
class Cuestionario extends \yii\db\ActiveRecord
{
    //status 
    const STATUS_ACTIVO = 1;
    const STATUS_INACTIVO = 0;

    const FKS_OHC = 1;
    const FKS_LOCAL = 2;

    //Tipos de Cuestionarios
    const TIPO_CUESTIONARIO_NORDICO = 1;
    const TIPO_CUESTIONARIO_NOM_605 = 2;
    const TIPO_CUESTIONARIO_MUSCULOESQUELETICAS = 3;
    const TIPO_CUESTIONARIO_ANTROPOMETRICO = 4;
    const TIPO_CUESTIONARIO_TRAUMASEVEROS = 5;
    const TIPO_CUESTIONARIO_CONDICIONES_TRABAJO = 6;

    public $no_empleado;
    public $nombre_trabajador;
    public $nombre_medico;
    public $firmado;

    public $filtro1;
    public $filtro2;
    public $filtro3;

    public $rango1desde;
    public $rango1hasta;
    public $rango2desde;
    public $rango2hasta;
    public $rango3desde;
    public $rango3hasta;
    public $rango4;
    public $rango5desde;
    public $rango5hasta;

    public $id_form;

    public $atributo1;

    public $consentimiento;
    public $name_empresa;
    public $model_;


    public $file_fotoweb;
    public $txt_base64_foto;
    public $txt_base64_ine;
    public $txt_base64_inereverso;

    public $file_evidencia_consentimiento;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cuestionario';
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
            [['id_medico'], 'required', 'on' => 'no_medico'],
            [['id_empresa','id_pais','id_linea', 'id_ubicacion','id_tipo_cuestionario', 'id_paciente', 'status', 'id_bitacora','sw','firmado','sexo','edad','filtro1','filtro2','filtro3','id_area','id_puesto','sexo','edad','id_form','id_nivel1','id_nivel2','id_nivel3','id_nivel4','tipo_consentimiento'], 'integer'],

            [['uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','tipo_identificacion','numero_identificacion','foto_web','base64_foto','fecha','hora','txt_base64_foto','txt_base64_ine','txt_base64_inereverso','nombre_empresa'], 'safe'],

            [['fecha_cuestionario', 'no_empleado', 'nombre_trabajador', 'nombre_medico', 'es_local','fecha_nacimiento'], 'safe'],
            [['firma_paciente'], 'string'],
            [['num_trabajador'], 'string', 'max' => 10],

            [['evidencia_consentimiento'], 'string', 'max' => 300],

            [['nombre_medico'], 'string', 'max' => 100],

            [['nombre','apellidos'], 'string', 'max' => 150],

            [['rango1desde','rango1hasta','rango2desde','rango2hasta','rango3desde','rango3hasta'], 'number'],
            [['nombre_empresa','name_empresa'], 'string', 'max' => 500],
            [['id_bitacora', 'id_tipo_cuestionario', 'id_empresa', 'id_paciente', 'id_medico'], 'required'],
            // [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Pacientes::class, 'targetAttribute' => ['id_paciente' => 'id']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Trabajadores::class, 'targetAttribute' => ['id_paciente' => 'id']],
            [['id_tipo_cuestionario'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCuestionario::class, 'targetAttribute' => ['id_tipo_cuestionario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_bitacora' => 'Bitacora',
            'id_tipo_cuestionario' => 'Id Tipo de Cuestionario',
            'id_empresa' => 'Empresa',

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'id_paciente' => 'Paciente',
            'id_medico' => 'Medico',
            'fecha_cuestionario' => 'Fecha Cuestionario',
            'nombre_empresa' => 'Empresa',
            'nombre_medico' => 'Médico',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'id_area' => 'Área',
            'id_puesto' => 'Puesto de Trabajo',
            'sexo' => 'Sexo',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'edad' => 'Edad',
            'firma_paciente' => 'Firma Paciente',
            'status' => 'Status',
            'filtro1' => 'Medida 1',
            'filtro2' => 'Medida 2',
            'filtro3' => 'Medida 3',
            'rango1desde' => 'Desde',
            'rango1hasta' => 'Hasta',
            'rango2desde' => 'Desde',
            'rango2hasta' => 'Hasta',
            'rango3desde' => 'Desde',
            'rango3hasta' => 'Hasta',
            'rango4' => 'Sexo',
            'rango5desde' => 'Edad Desde',
            'rango5hasta' => 'Edad Hasta',

            'evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
            'file_evidencia_consentimiento' => Yii::t('app', 'Evidencia Consentimiento'),
        ];
    }

        /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::class, ['id' => 'id_area']);
    }

    /**
     * Gets query for [[Puestostrabajo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuesto()
    {
        return $this->hasOne(Puestostrabajo::class, ['id' => 'id_puesto']);
    }
    
    public static function getWorkersByCompany($id_company, $columns = ["*"]) {
        if (is_numeric($id_company)) {
            $company = Empresas::findOne($id_company);

            if ($company) {
                $trabajadores = Trabajadores::find()
                                ->select($columns)
                                ->where(["id_empresa" => $company->id])
                                ->andWhere(["!=", "status", "5"])
                                ->orderBy(['nombre' => SORT_ASC])
                                ->all();

                $trabajadores_ = ArrayHelper::map($trabajadores, 'id', function($model){
                    return $model['nombre'].' '.$model['apellidos'];
                });

                //dd($trabajadores_);

                return $trabajadores_;
            }
        }

        return false;
    }

    
    public static function completeTest($type_test)
    {
        $user = Yii::$app->user->identity;

        if ($user->type_user != Usuarios::TIPO_ENCUESTADO) {
            return false;
        }

        if ($paciente = Pacientes::findOne(["id_usuario" => $user->id])) {
            $cuestionario = Cuestionario::find()
                            ->where(['id_tipo_cuestionario' => $type_test])
                            ->andWhere(["id_paciente" => $paciente->id])
                            ->andWhere(['status' => Cuestionario::STATUS_ACTIVO])
                            ->all();

            if ($cuestionario) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets query for [[Paciente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Pacientes::class, ['id' => 'id_paciente']);
    }

    /**
     * Gets query for [[TipoCuestionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoCuestionario()
    {
        return $this->hasOne(TipoCuestionario::class, ['id' => 'id_tipo_cuestionario']);
    }

    /**
     * Gets query for [[Trabajador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajador()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_paciente']);
    }

    /**
     * Gets query for [[Trabajador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadorsmo()
    {
        return $this->hasOne(Trabajadores::class, ['id' => 'id_paciente']);
    }

    /**
     * Gets query for [[Trabajador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    /**
     * Gets query for [[Medico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'id_medico']);
    }

    public function getUMedico()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'id_medico']);
    }

    //ATRIBUTO ANTROPOMETRICO GENERAL 1
    public function getAgeneral1()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>179]);
    }

    //ATRIBUTO ANTROPOMETRICO GENERAL 2
    public function getAgeneral2()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>180]);
    }

    //ATRIBUTO ANTROPOMETRICO GENERAL 3
    public function getAgeneral3()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>181]);
    }

    //ATRIBUTO ANTROPOMETRICO GENERAL 4
    public function getAgeneral4()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>182]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 1
    public function getPp1()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>161]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 2
    public function getPp2()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>162]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 3
    public function getPp3()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>163]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 4
    public function getPp4()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>164]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 5
    public function getPp5()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>165]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 6
    public function getPp6()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>166]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 7
    public function getPp7()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>167]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 8
    public function getPp8()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>168]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 9
    public function getPp9()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>169]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA PARADO 10
    public function getPp10()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>170]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 1
    public function getPs1()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>171]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 2
    public function getPs2()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>172]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 3
    public function getPs3()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>173]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 4
    public function getPs4()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>174]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 5
    public function getPs5()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>175]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 6
    public function getPs6()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>176]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 7
    public function getPs7()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>177]);
    }

    //ATRIBUTO ANTROPOMETRICO POSTURA SENTADO 8
    public function getPs8()
    {
        return $this->hasOne(DetalleCuestionario::className(), ['id_cuestionario' => 'id'])->where(['id_area'=>178]);
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
