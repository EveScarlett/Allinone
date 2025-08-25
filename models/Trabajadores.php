<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajadores".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $tipo_examen
 * @property string|null $nombre
 * @property string|null $apellidos
 * @property string|null $foto
 * @property int|null $sexo
 * @property int|null $estado_civil
 * @property string|null $fecha_nacimiento
 * @property int|null $edad
 * @property int|null $nivel_lectura
 * @property int|null $nivel_escritura
 * @property int|null $escolaridad
 * @property int|null $grupo
 * @property int|null $rh
 * @property string|null $num_imss
 * @property string|null $celular
 * @property string|null $contacto_emergencia
 * @property string|null $direccion
 * @property string|null $colonia
 * @property string|null $pais
 * @property string|null $estado
 * @property string|null $ciudad
 * @property string|null $municipio
 * @property string|null $cp
 * @property string|null $num_trabajador
 * @property int|null $tipo_contratacion
 * @property string|null $fecha_contratacion
 * @property string|null $fecha_baja
 * @property string|null $antiguedad
 * @property string|null $ruta
 * @property string|null $parada
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $update_date
 * @property int|null $update_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 * @property int|null $status
 */
class Trabajadores extends \yii\db\ActiveRecord
{
    public $code_maquina;
    public $file_qrmaquina;
    public $file_foto;
    public $envia_puesto;
    public $envia_form;
    public $aux_estudios = [];
    public $aux_puestos = [];
    public $aux_programas = [];
    public $nombre_trabajador;

    public $extra1;
    public $extra2;
    public $extra3;
    public $extra4;
    public $extra5;
    public $extra6;
    public $extra7;
    public $extra8;
    public $extra9;
    public $extra10;
    public $extra11;
    public $extra12;
    public $extra13;
    public $extra14;
    public $extra15;
    public $extra16;
    public $extra17;
    public $extra18;
    public $extra19;
    public $extra20;
    public $extra21;
    public $extra22;
    public $extra23;
    public $extra24;
    public $extra25;

    public $file_excel;

    public $aux_epps = [];
    public $aux_tallas = [];
    public $aux_tallas2 = [];
    public $aux_psicologico = [];

    public $talla_cabezaotro;
    public $talla_superiorotro;
    public $talla_inferiorotro;
    public $talla_manosotro;
    public $talla_piesotro;
    

    public $maquina_select;
    public $nombre_empresa;


    public $firmatxt;
    public $file_ife;
    public $file_ife_reverso;
    public $file_fotoweb;
    public $txt_base64_foto;
    public $txt_base64_ine;
    public $txt_base64_inereverso;

    public $consentimiento;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajadores';
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
            [['file_foto','file_qrmaquina'], 'file','extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['file_excel'], 'file','extensions' => 'csv', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['file_ife','file_ife_reverso','file_fotoweb'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['tipo_registro','id_cargamasiva','id_empresa','id_pais','id_linea','id_ubicacion', 'tipo_examen', 'sexo', 'estado_civil', 'nivel_lectura', 'nivel_escritura', 'escolaridad', 'grupo', 'rh', 'tipo_contratacion', 'create_user', 'update_user', 'delete_user', 'status', 'status_documentos','envia_puesto','id_area','id_puesto','antiguedad_dias','antiguedad_meses','antiguedad_anios',
              'extra1','extra2','extra3','extra4','extra5','extra6','extra7','extra8','extra9','extra10','extra11','extra12','extra13','extra14','extra15','extra16','extra17','extra18','extra19','extra20','extra21','extra22','extra23','extra24','extra25','talla_cabeza','talla_general','talla_superior','talla_inferior','talla_manos','talla_pies','turno','status_baja','id_nivel1','id_nivel2','id_nivel3','id_nivel4',
              'uso_consentimiento','retirar_consentimiento','acuerdo_confidencialidad','consentimiento','estudios_pendientes','status_cumplimiento','status_backup','ps_status','ps_activos','refreshupdated_1','refreshupdated_2','refreshupdated_3','refreshupdated_4','refreshupdated_5'], 'integer'],
            [['fecha_nacimiento', 'fecha_contratacion', 'fecha_baja', 'create_date', 'update_date', 'delete_date','aux_estudios','aux_puestos','fecha_iniciop','fecha_finp','aux_epps','aux_tallas','aux_tallas2','aux_psicologico','firmatxt','txt_base64_foto','txt_base64_ine','txt_base64_inereverso','file_ife','file_ife_reverso','file_fotoweb','puesto_cumplimiento','riesgo_cumplimiento','riesgohistorico_cumplimiento','programasalud_cumplimiento','expediente_cumplimiento','hc_cumplimiento','poe_cumplimiento','cuestionario_cumplimiento','antropometrica_cumplimiento','programassalud_cumplimiento','estudioscomplementarios_cumplimiento','porcentaje_cumplimiento','puesto_sueldo','aux_programas'], 'safe'],
            [['nombre', 'apellidos', 'foto', 'contacto_emergencia', 'direccion', 'colonia', 'pais', 'estado', 'ciudad', 'municipio', 'ruta', 'parada'], 'string', 'max' => 100],
            [['tipo_identificacion','numero_identificacion'], 'string', 'max' => 50],
            [['teamleader','code_maquina','nombre_empresa'], 'string', 'max' => 300],
            [['cp', 'num_trabajador'], 'string', 'max' => 10],
            [['celular','curp','rfc'], 'string', 'max' => 30],
            [['correo','dato_extra1','dato_extra2','dato_extra3','dato_extra4','dato_extra5','dato_extra6','dato_extra7','dato_extra8','dato_extra9','dato_extra10'], 'string', 'max' => 150],
            [['personalidad','ife','ife_reverso','foto_web','firma_ruta','puesto_contable'], 'string', 'max' => 200],
            [['num_imss'], 'string', 'max' => 20],
            [['edad','maquina_select','base64_foto','base64_ine','base64_inereverso','firma','fecha','hora'], 'safe'],
            [['antiguedad','talla_cabezaotro','talla_superiorotro','talla_inferiorotro','talla_manosotro','talla_piesotro'], 'string', 'max' => 50],
            [['motivo_baja','id_link'], 'string', 'max' => 500],
            [['id_empresa'], 'required','on' =>['create','update']],
            [['id'], 'required','on' =>['folder']],
            [['id'], 'required','on' =>['epp']],
            [['id_empresa','file_excel'], 'required','on' =>['carga']],
            [['id_empresa','status','nombre', 'apellidos','sexo','fecha_nacimiento'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#trabajadores-envia_form').val() == '1';
            }"],
            [['fecha_baja','motivo_baja'], 'required', 'when' => function($model) {
                return $model->status == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#trabajadores-status').val() == '2';
            }"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tipo_registro' => Yii::t('app', 'Tipo Registro'),
            'id_cargamasiva' => Yii::t('app', 'N° Carga Masiva'),
            'id_empresa' => Yii::t('app', 'Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),

            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'tipo_examen' => Yii::t('app', 'Tipo Exámen'),
            'nombre' => Yii::t('app', 'Nombre'),
            'apellidos' => Yii::t('app', 'Apellidos'),
            'foto' => Yii::t('app', 'Foto'),
            'sexo' => Yii::t('app', 'Sexo'),
            'estado_civil' => Yii::t('app', 'Estado Civil'),
            'fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'edad' => Yii::t('app', 'Edad'),
            'nivel_lectura' => Yii::t('app', 'Nivel Lectura'),
            'nivel_escritura' => Yii::t('app', 'Nivel Escritura'),
            'escolaridad' => Yii::t('app', 'Escolaridad'),
            'grupo' => Yii::t('app', 'Gpo.'),
            'rh' => Yii::t('app', 'Rh'),
            'num_imss' => Yii::t('app', 'N° IMSS'),
            'celular' => Yii::t('app', 'Celular'),
            'contacto_emergencia' => Yii::t('app', 'Contacto Emergencia'),
            'direccion' => Yii::t('app', 'Dirección'),
            'colonia' => Yii::t('app', 'Colonia'),
            'pais' => Yii::t('app', 'País'),
            'estado' => Yii::t('app', 'Estado'),
            'ciudad' => Yii::t('app', 'Ciudad'),
            'municipio' => Yii::t('app', 'Municipio'),
            'cp' => Yii::t('app', 'CP.'),
            'num_trabajador' => Yii::t('app', 'N° Trabajador'),
            'tipo_contratacion' => Yii::t('app', 'Tipo Contratación'),
            'fecha_contratacion' => Yii::t('app', 'Fecha Contratación'),
            'fecha_baja' => Yii::t('app', 'Fecha Baja'),
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'antiguedad' => Yii::t('app', 'Antigüedad'),
            'ruta' => Yii::t('app', 'Ruta'),
            'parada' => Yii::t('app', 'Parada'),
            'id_puesto' => Yii::t('app', 'Puesto x Riesgo'),

            'puesto_contable' => Yii::t('app', 'Puesto Contable'),
            'puesto_sueldo' => Yii::t('app', 'Sueldo'),

            'id_area' => Yii::t('app', 'Área'),
            'fecha_iniciop' => Yii::t('app', 'Fecha de Inicio'),
            'fecha_finp' => Yii::t('app', 'Fecha de Fin'),
            'teamleader' => Yii::t('app', 'Teamleader'),
            'create_date' => Yii::t('app', 'Fecha Registro'),
            'create_user' => Yii::t('app', 'Registró'),
            'update_date' => Yii::t('app', 'Fecha Actualiza'),
            'update_user' => Yii::t('app', 'Actualizó'),
            'delete_date' => Yii::t('app', 'Fecha Elimina'),
            'delete_user' => Yii::t('app', 'Eliminó'),
            'status_documentos' => Yii::t('app', 'Status Documentos'),
            'status' => Yii::t('app', 'Condición'),
            'file_excel' => Yii::t('app', 'Listado Trabajadores .CSV'),
            'file_qrmaquina' => Yii::t('app', 'QR Maquinaria'),
            'extra1' => Yii::t('app', 'Columna Extra 1'),
            'extra2' => Yii::t('app', 'Columna Extra 2'),
            'extra3' => Yii::t('app', 'Columna Extra 3'),
            'extra4' => Yii::t('app', 'Columna Extra 4'),
            'extra5' => Yii::t('app', 'Columna Extra 5'),
            'extra6' => Yii::t('app', 'Columna Extra 6'),
            'extra7' => Yii::t('app', 'Columna Extra 7'),
            'extra8' => Yii::t('app', 'Columna Extra 8'),
            'extra9' => Yii::t('app', 'Columna Extra 9'),
            'extra10' => Yii::t('app', 'Columna Extra 10'),
            'extra11' => Yii::t('app', 'Columna Extra 11'),
            'extra12' => Yii::t('app', 'Columna Extra 12'),
            'extra13' => Yii::t('app', 'Columna Extra 13'),
            'extra14' => Yii::t('app', 'Columna Extra 14'),
            'extra15' => Yii::t('app', 'Columna Extra 15'),
            'extra16' => Yii::t('app', 'Columna Extra 16'),
            'extra17' => Yii::t('app', 'Columna Extra 17'),
            'extra18' => Yii::t('app', 'Columna Extra 18'),
            'extra19' => Yii::t('app', 'Columna Extra 19'),
            'extra20' => Yii::t('app', 'Columna Extra 20'),
            'extra21' => Yii::t('app', 'Columna Extra 21'),
            'extra22' => Yii::t('app', 'Columna Extra 22'),
            'extra23' => Yii::t('app', 'Columna Extra 23'),
            'extra24' => Yii::t('app', 'Columna Extra 24'),
            'extra25' => Yii::t('app', 'Columna Extra 25'),
            'dato_extra1' => Yii::t('app', 'Ubicación'),
            'dato_extra2' => Yii::t('app', 'Pais'),
            'dato_extra3' => Yii::t('app', 'Extra 3'),
            'dato_extra4' => Yii::t('app', 'Extra 4'),
            'dato_extra5' => Yii::t('app', 'Extra 5'),
            'dato_extra6' => Yii::t('app', 'Extra 6'),
            'dato_extra7' => Yii::t('app', 'Extra 7'),
            'dato_extra8' => Yii::t('app', 'Extra 8'),
            'dato_extra9' => Yii::t('app', 'Extra 9'),
            'dato_extra10' => Yii::t('app', 'Extra 10'),
            'talla_cabeza' => Yii::t('app', 'Cabeza'),
            'talla_general' => Yii::t('app', 'Cuerpo'),
            'talla_superior' => Yii::t('app', 'Camisa'),
            'talla_inferior' => Yii::t('app', 'Pantalón'),
            'talla_manos' => Yii::t('app', 'Manos-Guantes'),
            'talla_pies' => Yii::t('app', 'Calzado'),
            'curp' => Yii::t('app', 'Curp'),
            'rfc' => Yii::t('app', 'Rfc'),
            'correo' => Yii::t('app', 'Correo Electrónico'),
            'personalidad' => Yii::t('app', 'Personalidad'),
            'talla_cabezaotro' => Yii::t('app', 'Medida Cabeza'),
            'talla_superiorotro' => Yii::t('app', 'Medida Camisa'),
            'talla_inferiorotro' => Yii::t('app', 'Medida Pantalón'),
            'talla_manosotro' => Yii::t('app', 'Medida Manos-Guantes'),
            'talla_piesotro' => Yii::t('app', 'Medida Calzado'),
            'code_maquina' => Yii::t('app', 'Código de Máquina'),
            'maquina_select' => Yii::t('app', 'Máquina Autorizada para el Trabajador'),

            'ife' => Yii::t('app', 'IDENTIFICACIÓN'),
            'ife_reverso' => Yii::t('app', 'IDENTIFICACIÓN REVERSO'),
            'foto_web' => Yii::t('app', 'FOTO'),
            'firma' => Yii::t('app', 'Firma'),
            'fecha' => Yii::t('app', 'Fecha'),
            'acuerdo_confidencialidad' => Yii::t('app', 'Aviso de Privacidad'),


            'mostrar_nivel_pdf' => Yii::t('app', 'Mostrar Nombre Oficial en Archivos PDF'),

            'puesto_cumplimiento' => Yii::t('app', 'Cumplimiento Puesto'),
            'riesgo_cumplimiento' => Yii::t('app', 'Cumplimiento Riesgo'),
            'riesgohistorico_cumplimiento' => Yii::t('app', 'Cumplimiento  Histórico'),
            'programasalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programa de Salud'),
            'expediente_cumplimiento' => Yii::t('app', 'Cumplimiento Expediente Médico'),
            'hc_cumplimiento' => Yii::t('app', 'Cumplimiento Historia Clínica'),
            'poe_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Médicos'),
            'cuestionario_cumplimiento' => Yii::t('app', 'Cumplimiento Cuestionario Nórdico'),
            'antropometrica_cumplimiento' => Yii::t('app', 'Cumplimiento Medidas Antropométricas'),

            'programassalud_cumplimiento' => Yii::t('app', 'Cumplimiento Programas de Salud'),
            'estudioscomplementarios_cumplimiento' => Yii::t('app', 'Cumplimiento Estudios Complementarios'),
            'porcentaje_cumplimiento' => Yii::t('app', 'Cumplimiento Trabajador'),
            'status_cumplimiento' => Yii::t('app', 'Status Cumplimiento'),

            'ps_status' => Yii::t('app', 'Con Programas de Salud'),
            'ps_activos' => Yii::t('app', 'PS Activos'),

            'refreshupdated_1' => Yii::t('app', 'Actualizado Refresh 1'),
            'refreshupdated_2' => Yii::t('app', 'Actualizado Refresh 2'),
            'refreshupdated_3' => Yii::t('app', 'Actualizado Refresh 3'),
            'refreshupdated_4' => Yii::t('app', 'Actualizado Refresh 4'),
            'refreshupdated_5' => Yii::t('app', 'Actualizado Refresh 5'),
        ];
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuestos()
    {
        return $this->hasMany(Puestotrabajador::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuestoactivo()
    {
        return $this->hasOne(Puestotrabajador::className(), ['id_trabajador' => 'id'])->where(['status'=>1]);
    }

    /**
     * Gets query for [[Alergias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlergias()
    {
        return $this->hasMany(AlergiaTrabajador::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

     /**
     * Gets query for [[Poes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastpoe()
    {
        return $this->hasOne(Poes::className(), ['id_trabajador' => 'id'])->orderBy(['anio'=>SORT_DESC,'id'=>SORT_DESC]);
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
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::class, ['id' => 'id_area']);
    }

    public function getInfopais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
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

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'dato_extra1']);
    }

    public function getDatapais()
    {
        return $this->hasOne(Paises::class, ['id' => 'dato_extra2']);
    }

    public function getDataubicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Estudios::className(), ['id' => 'id_estudio'])
        ->viaTable('trabajador_estudio', ['id_trabajador' => 'id']);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestudios()
    {
        return $this->hasMany(Trabajadorestudio::className(), ['id_trabajador' => 'id'])->andWhere(['IS', 'status_baja', new \yii\db\Expression('NULL')])->orderBy(['orden'=>SORT_ASC,'id_tipo'=>SORT_ASC]);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoriaclinica()
    {
        return $this->hasOne(Hccohc::class, ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_DESC]);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoriasclinicas()
    {
        return $this->hasMany(Hccohc::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_DESC]);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoriasactivas()
    {
        $hcs = $this->hasMany(Hccohc::className(), ['id_trabajador' => 'id'])->where(['IS', 'status_baja', new \yii\db\Expression('NULL')])->andWhere(['<>', 'status', [0,3]])->orderBy(['id'=>SORT_DESC])->all();
        
        return count($hcs);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultas()
    {
        return $this->hasMany(Consultas::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentostrabajadores()
    {
        return $this->hasMany(DocumentoTrabajador::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistorialdocumentos()
    {
        return $this->hasMany(Historialdocumentos::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenespoes()
    {
        return $this->hasMany(Ordenpoetrabajador::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoes()
    {
        return $this->hasMany(Poes::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuestionarios()
    {
        return $this->hasMany(Cuestionario::className(), ['id_paciente' => 'id'])->orderBy(['id'=>SORT_ASC]);
    }

    /**
     * Gets query for [[PuestoEstudio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgramas()
    {
        return $this->hasMany(ProgramaTrabajador::className(), ['id_trabajador' => 'id'])->where(['status'=>1]);
    }


    public function getProgramasall()
    {
        return $this->hasMany(ProgramaTrabajador::className(), ['id_trabajador' => 'id'])->orderBy(['status'=>SORT_ASC,'fecha_inicio'=>SORT_DESC]);
    }

    public function afterFind() {
        parent::afterFind();
        $this->nombre_trabajador = "{$this->nombre} {$this->apellidos}";
    }

    /**
     * Retorna el nombre de la empresa al que pertenece el trabajador
     * @param int $id ID del trabajador
     * @return string Nombre de la empresa
     */
    public static function getCompany($id) {
        if ($trabajador = Trabajadores::findOne($id)) {
            return $trabajador->empresa->comercial;
        }
    }

    public static function getDataWorker($id, $attribute = null) {
        if ($trabajador = ListadoTrabajadores::findOne($id)) {
            switch ($attribute) {
                case 'nombre':
                    return $trabajador->nombre;
                    break;
                case 'apellido':
                    return $trabajador->apellidos;
                    break;
                case 'nombre_completo':
                    return $trabajador->nombre_completo;
                    break;
                case 'nacimiento':
                    return $trabajador->fecha_nacimiento;
                    break;
                case 'sexo':
                    return $trabajador->sexo;
                    break;
                case 'id_puesto':
                    return $trabajador->id_puesto;
                    break;
                case 'id_area':
                    return $trabajador->id_area;
                    break;
                case 'id_empresa':
                    return $trabajador->id_empresa;
                    break;
                default:
                    return [
                        'nombre' => $trabajador->nombre_completo,
                        'nacimiento' => $trabajador->fecha_nacimiento,
                        'sexo' => $trabajador->sexo,
                        'id_puesto' => $trabajador->id_puesto,
                        'id_area' => $trabajador->id_area
                    ];
                    break;
            }
        }
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

    /**
     * Gets query for [[Epps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormatosepp()
    {
        return $this->hasMany(Trabajadorepp::className(), ['id_trabajador' => 'id']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntregasepp()
    {
        return $this->hasMany(Movimientos::className(), ['id_trabajador' => 'id'])->orderBy(['id'=>SORT_DESC]);
    }

  
    public function getAntropometrica()
    {
        return $this->hasOne(Cuestionario::className(), ['id_paciente' => 'id'])->where(['id_tipo_cuestionario'=>4])->orderBy(['id'=>SORT_DESC]);
    }

    /**
     * Gets query for [[Parametros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametros()
    {
        return $this->hasMany(Trabajadorparametro::className(), ['id_trabajador' => 'id']);
    }

    public function getTurnoactual()
    {
        return $this->hasOne(Turnos::className(), ['id' => 'turno']);
    }

    public function getMaquinas()
    {
        return $this->hasMany(Trabajadormaquina::className(), ['id_trabajador' => 'id'])->where(['status'=>1])->orderBy(['id'=>SORT_ASC]);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacionl()
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