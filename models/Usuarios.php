<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $username
 * @property string|null $password
 * @property int|null $rol
 * @property string|null $firma
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property int|null $id_firma
 * @property int|null $id_empresa
 * @property string|null $foto
 * @property int|null $empresas_todos
 * @property int|null $status
 */
class Usuarios extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $envia_form;
    //Atributos temporales 
    public $select_empresas;
    public $otra_rol;
    public $file_foto;
    public $file_firma;

    public $f_nombre;
    public $f_universidad;
    public $f_cedula;
    public $f_firma;
    public $f_titulo;
    public $f_abreviado_titulo;
    public $f_registro_sanitario;
    public $admite_firma;

    public $trabajadores_select;


    //Atributos que hice para marcar todas las filas o columnas de los permisos de usuario
    public $columna_listado;
    public $columna_exportar;
    public $columna_crear;
    public $columna_ver;
    public $columna_actualizar;
    public $columna_expediente;
    public $columna_cerrarexpediente;
    public $columna_eliminar;
    public $columna_entrega;
    public $columna_documento;
    public $linea_1;
    public $linea_2;
    public $linea_3;
    public $linea_4;
    public $linea_5;
    public $linea_6;
    public $linea_7;
    public $linea_8;
    public $linea_9;
    public $linea_10;
    public $linea_11;
    public $linea_12;
    public $linea_13;
    public $linea_14;
    public $linea_15;
    public $linea_16;
    public $linea_17;
    public $linea_18;
    public $linea_19;
    public $linea_20;
    public $linea_21;
    public $linea_22;
    public $linea_23;
    public $linea_24;
    public $linea_25;
    public $linea_26;
    public $linea_27;
    public $linea_28;
    public $linea_29;
    public $linea_30;
    public $linea_31;
    public $linea_32;
    public $linea_33;
    public $linea_34;
    public $linea_35;
    public $linea_36;
    public $linea_37;
    public $linea_38;
    public $linea_39;
    public $linea_40;

    public $almacen_exportar;
    public $almacen_listado;

    public $antropometricos_crear;
    public $antropometricos_exportar;
    public $antropometricos_listado;
    public $antropometricos_ver;

    public $cargapoes_crear;
    public $cargapoes_eliminar;
    public $cargapoes_exportar;
    public $cargapoes_listado;
    public $cargapoes_ver; 

    public $cargatrabajadores_crear;
    public $cargatrabajadores_eliminar;
    public $cargatrabajadores_exportar;
    public $cargatrabajadores_listado;
    public $cargatrabajadores_ver;

    public $categoriaestudio_actualizar;
    public $categoriaestudio_crear;
    public $categoriaestudio_exportar;
    public $categoriaestudio_listado;
    public $categoriaestudio_ver;

    public $configuracion_actualizar;
    public $configuracion_exportar;
    public $configuracion_listado;
    public $configuracion_ver;

    public $consultas_actualizar;
    public $consultas_crear;
    public $consultas_exportar;
    public $consultas_listado;
    public $consultas_ver;

    public $diagnosticoscie_actualizar;
    public $diagnosticoscie_crear;
    public $diagnosticoscie_exportar;
    public $diagnosticoscie_listado;
    public $diagnosticoscie_ver;

    public $diagrama_actualizar;
    public $diagrama_listado;
    public $diagrama_ver;

    public $empresas_actualizar;
    public $empresas_crear;
    public $empresas_exportar;
    public $empresas_listado;
    public $empresas_ver;

    public $entradas_actualizar;
    public $entradas_crear;
    public $entradas_exportar;
    public $entradas_listado;
    public $entradas_ver;

    public $epp_actualizar;
    public $epp_crear;
    public $epp_exportar;
    public $epp_listado;
    public $epp_ver;

    public $eppsbitacora_actualizar;
    public $eppsbitacora_crear;
    public $eppsbitacora_exportar;
    public $eppsbitacora_listado;
    public $eppsbitacora_ver;

    public $eppsstock_exportar;
    public $eppsstock_listado;

    public $eppsstockmin_actualizar;
    public $eppsstockmin_crear;
    public $eppsstockmin_exportar;
    public $eppsstockmin_ver;
    public $eppsstockmin_listado;

    public $estudios_actualizar;
    public $estudios_crear;
    public $estudios_exportar;
    public $estudios_listado;
    public $estudios_ver;

    public $firmas_actualizar;
    public $firmas_crear;
    public $firmas_exportar;
    public $firmas_listado;
    public $firmas_ver;

    public $historial_exportar;
    public $historial_listado;

    public $historias_actualizar;
    public $historias_cerrarexpediente;
    public $historias_corregir;
    public $historias_crear;
    public $historias_delete;
    public $historias_exportar;
    public $historias_listado;
    public $historias_ver;

    public $medicamentos_actualizar;
    public $medicamentos_crear;
    public $medicamentos_exportar;
    public $medicamentos_listado;
    public $medicamentos_ver;

    public $medicamentosbitacora_actualizar;
    public $medicamentosbitacora_crear;
    public $medicamentosbitacora_exportar;
    public $medicamentosbitacora_listado;
    public $medicamentosbitacora_ver;

    public $medicamentosstock_exportar;
    public $medicamentosstock_listado;

    public $medicamentosstockmin_actualizar;
    public $medicamentosstockmin_crear;
    public $medicamentosstockmin_exportar;
    public $medicamentosstockmin_listado;
    public $medicamentosstockmin_ver;

    public $nordicos_crear;
    public $nordicos_exportar;
    public $nordicos_listado;
    public $nordicos_ver;

    public $ordenpoe_actualizar;
    public $ordenpoe_crear;
    public $ordenpoe_exportar;
    public $ordenpoe_listado;
    public $ordenpoe_ver;

    public $papelera_listado;

    public $poes_actualizar;
    public $poes_crear;
    public $poes_documento;
    public $poes_exportar;
    public $poes_listado;
    public $poes_ver;
    public $poes_entrega;

    public $programasalud_actualizar;
    public $programasalud_crear;
    public $programasalud_exportar;
    public $programasalud_hc;
    public $programasalud_listado;
    public $programasalud_ver;

    public $puestos_actualizar;
    public $puestos_crear;
    public $puestos_exportar;
    public $puestos_listado;
    public $puestos_ver;

    public $requerimientos_actualizar;
    public $requerimientos_crear;
    public $requerimientos_exportar;
    public $requerimientos_listado;
    public $requerimientos_ver;

    public $salidas_actualizar;
    public $salidas_crear;
    public $salidas_exportar;
    public $salidas_listado;
    public $salidas_ver;

    public $trabajadores_actualizar;
    public $trabajadores_crear;
    public $trabajadores_delete;
    public $trabajadores_expediente;
    public $trabajadores_exportar;
    public $trabajadores_listado;
    public $trabajadores_ver;

    public $turnos_actualizar;
    public $turnos_listado;
    public $turnos_ver;

    public $usuarios_actualizar;
    public $usuarios_crear;
    public $usuarios_exportar;
    public $usuarios_listado;
    public $usuarios_ver;

    public $consentimientos_actualizar;
    public $consentimientos_crear;
    public $consentimientos_exportar;
    public $consentimientos_listado;
    public $consentimientos_ver;

    public $vacantes_actualizar;
    public $vacantes_asignar;
    public $vacantes_crear;
    public $vacantes_exportar;
    public $vacantes_listado;
    public $vacantes_ver;

    public $incapacidades_actualizar;
    public $incapacidades_crear;
    public $incapacidades_exportar;
    public $incapacidades_listado;
    public $incapacidades_ver;

    public $roles_actualizar;
    public $roles_crear;
    public $roles_exportar;
    public $roles_listado;
    public $roles_ver;

    public $nombre;
    public $color;

    public $permisos = [
        'almacen_exportar'=>'Permiso para exportar listado de almacen',
        'almacen_listado'=>'Permiso para ver listado de almacen',

        'antropometricos_crear'=>'Permiso para crear nuevo cuestionario antropometrico',
        'antropometricos_exportar'=>'Permiso para exportar cuestionarios antropometricos',
        'antropometricos_listado'=>'Permiso para ver el listado de cuestionarios antropometricos',
        'antropometricos_ver'=>'Permiso para ver cuestionario antropometrico',

        'cargapoes_crear'=> 'Permiso para crear nueva carga masiva de poes',
        'cargapoes_eliminar'=> 'Permiso para eliminar carga masiva de poes',
        'cargapoes_exportar'=> 'Permiso para exportar listado de cargas poes',
        'cargapoes_listado'=> 'Permiso para ver el listado de cargas masivas poes',
        'cargapoes_ver'=> 'Permiso para ver carga masiva de poe',

        'cargatrabajadores_crear'=> 'Permiso para crear nueva carga masiva de trabajadores',
        'cargatrabajadores_eliminar'=> 'Permiso para eliminar carga masiva de trabajadores',
        'cargatrabajadores_exportar'=> 'Permiso para exportar listado de carga trabajadores',
        'cargatrabajadores_listado'=> 'Permiso para ver el listado de carga de trabajadores',
        'cargatrabajadores_ver'=> 'Permiso para ver la carga masiva de trabajadores',

        'categoriaestudio_actualizar'=> 'Permiso para actualizar categoria de estudios',
        'categoriaestudio_crear'=> 'Permiso para crear nueva categoria de estudio',
        'categoriaestudio_exportar'=> 'Permiso para exportar listado de categorias estudios',
        'categoriaestudio_listado'=> 'Permiso para ver el listado de categorias estudios',
        'categoriaestudio_ver'=> 'Permiso para ver categoria de estudio',

        'configuracion_actualizar'=>'Permiso para actualizar configuración',
        'configuracion_exportar'=>'Permiso para exportar configuración',
        'configuracion_listado'=>'Permiso para ver el listado de configuracion',
        'configuracion_ver'=>'Permiso para ver configuracion',

        'consultas_actualizar'=>'Permiso para actualizar consulta',
        'consultas_crear'=>'Permiso para crear consulta',
        'consultas_exportar'=>'Permiso para exportar listado de consultas',
        'consultas_listado'=>'Permiso para ver listado de consultas',
        'consultas_ver'=>'Permiso para ver consulta',

        'diagnosticoscie_actualizar'=>'Permiso para actualizar diagnostico cie',
        'diagnosticoscie_crear'=>'Permiso para crear diagnostico cie',
        'diagnosticoscie_exportar'=>'Permiso para exportar listado de diagnosticos cie',
        'diagnosticoscie_listado'=>'Permiso para ver el listado de diagnosticos cie',
        'diagnosticoscie_ver'=>'Permiso para ver diagnostico cie',

        'diagrama_actualizar'=>'Permiso para actualizar diagrama',
        'diagrama_listado' => 'Permiso para ver el parte corporativa',
        'diagrama_ver'=>'Permiso para ver diagrama',

        'empresas_actualizar'=>'Permiso para actualizar empresa',
        'empresas_crear'=>'Permiso para crear nueva empresa',
        'empresas_exportar'=>'Permiso para exportar listado de empresas',
        'empresas_listado'=>'Permiso para ver el listado de empresas',
        'empresas_ver'=>'Permiso para ver empresa',

        'entradas_actualizar'=>'Permiso para actualizar entrada',
        'entradas_crear'=>'Permiso para crear entrada',
        'entradas_exportar'=>'Permiso para exportar listado de entradas',
        'entradas_listado'=>'Permiso para ver listado de entradas',
        'entradas_ver'=>'Permiso para ver entrada',

        'epp_actualizar'=>'Permiso para actualizar epp',
        'epp_crear'=>'Permiso para crear epp',
        'epp_exportar'=>'Permiso para exportar listado de epp',
        'epp_listado'=>'Permiso para ver el listado de epp',
        'epp_ver'=>'Permiso para ver epp',

        'eppsbitacora_actualizar'=>'Permiso para actualizar movimiento de epps',
        'eppsbitacora_crear'=>'Permiso para crear nuevo movimiento de epp',
        'eppsbitacora_exportar'=>'Permiso para exportar bitacora de epps',
        'eppsbitacora_listado'=>'Permiso para ver el listado bitacora de epps',
        'eppsbitacora_ver'=>'Permiso para ver movimiento de epp',

        'eppsstock_exportar'=>'Permiso para exportar listado de stock de epp',
        'eppsstock_listado'=>'Permiso para ver el listado de stock de epp',

        'eppsstockmin_actualizar'=>'Permiso para actualizar el listado stock minimo de epps',
        'eppsstockmin_crear'=>'Permiso para crear nuevo stock minimo de epps',
        'eppsstockmin_exportar'=>'Permiso para exportar listado de stock minimo de epp',
        'eppsstockmin_ver'=>'Permiso para ver stock minimo de epps',
        'eppsstockmin_listado'=>'Permiso para ver el listado de stocks minimos de epp',

        'estudios_actualizar'=>'Permiso para actualizar estudio',
        'estudios_crear'=>'Permiso para crear un nuevo estudio',
        'estudios_exportar'=>'Permiso para exportar listado de estudios',
        'estudios_listado'=>'Permiso para ver el listado de estudios',
        'estudios_ver'=>'Permiso para ver estudio',

        'firmas_actualizar'=>'Permiso para actualizar firma',
        'firmas_crear'=>'Permiso para crear nueva firma',
        'firmas_exportar'=>'Permiso para exportar listado de firmas',
        'firmas_listado'=>'Permiso para ver el listado de firmas',
        'firmas_ver'=>'Permiso para ver firma',

        'historial_exportar'=>'Permiso para exportar listado de historial documentos',
        'historial_listado'=>'Permiso para ver el listado historial de documentos',

        'historias_actualizar'=>'Permiso para actualizar historia clínica',
        'historias_cerrarexpediente'=>'Permiso para cerrar historia',
        'historias_corregir'=>'Permiso para corregir historia clínica',
        'historias_crear'=>'Permiso para crear historia clinica',
        'historias_delete'=>'Permiso para eliminar historia clinica',
        'historias_exportar'=>'Permiso para exportar listado de historias clínicas',
        'historias_listado'=>'Permiso para ver listado de historias clínicas',
        'historias_ver'=>'Permiso para ver historia clinica',

        'medicamentos_actualizar'=>'Permiso para actualizar medicamento',
        'medicamentos_crear'=>'Permiso para crear medicamento',
        'medicamentos_exportar'=>'Permiso para exportar listado de medicamentos',
        'medicamentos_listado'=>'Permiso para ver listado de medicamentos',
        'medicamentos_ver'=>'Permiso para ver medicamento',

        'medicamentosbitacora_actualizar'=>'Permiso para actualizar movimiento de medicamentos',
        'medicamentosbitacora_crear'=>'Permiso para crear nuevo movimiento de medicamento',
        'medicamentosbitacora_exportar'=>'Permiso para exportar bitacora de medicamentos',
        'medicamentosbitacora_listado'=>'Permiso para ver el listado bitacora de medicamentos',
        'medicamentosbitacora_ver'=>'Permiso para ver movimiento de medicamento',

        'medicamentosstock_exportar'=>'Permiso para exportar listado de stock de medicamentos',
        'medicamentosstock_listado'=>'Permiso para ver el listado de stock de medicamentos',

        'medicamentosstockmin_actualizar'=>'Permiso para actualizar el listado stock minimo de medicamentos',
        'medicamentosstockmin_crear'=>'Permiso para crear nuevo stock minimo de medicamentos',
        'medicamentosstockmin_exportar'=>'Permiso para exportar listado de stock minimo de medicamentos',
        'medicamentosstockmin_listado'=>'Permiso para ver el listado de stocks minimos',
        'medicamentosstockmin_ver'=>'Permiso para ver stock minimo de medicamentos',

        'nordicos_crear'=>'Permiso para crear nuevo cuestionario nordico',
        'nordicos_exportar'=>'Permiso para exportar cuestionario nordico',
        'nordicos_listado'=>'Permiso para ver el listado de cuestionarios nordicos',
        'nordicos_ver'=>'Permiso para ver cuestionario nordico',

        'ordenpoe_actualizar'=>'Permiso para actualizar orden poe',
        'ordenpoe_crear'=>'Permiso para crear orden poe',
        'ordenpoe_exportar'=>'Permiso para exportar listado de ordenes poes',
        'ordenpoe_listado'=>'Permiso para ver listado de ordenes poes',
        'ordenpoe_ver'=>'Permiso para ver orden poe',

        'papelera_listado'=>'Permiso para ver listado de papelera de reciclaje',

        'poes_actualizar'=>'Permiso para actualizar poe',
        'poes_crear'=>'Permiso para crear poe',
        'poes_documento' => 'Permiso para ver documento de poe',
        'poes_entrega'=>'Permiso para entrega poe',
        'poes_exportar'=>'Permiso para exportar listado de poes',
        'poes_listado'=>'Permiso para ver listado de poes',
        'poes_ver'=>'Permiso para ver poe',

        'programasalud_actualizar'=>'Permiso para actualizar programa de salud',
        'programasalud_crear'=>'Permiso para crear nuevo programa de salud',
        'programasalud_exportar'=>'Permiso para exportar listado de programas de salud',
        'programasalud_hc'=>'Permiso para actualizar programas de salud en historia clínica',
        'programasalud_listado'=>'Permiso para ver el listado de programas de salud',
        'programasalud_ver'=>'Permiso para ver programa de salud',

        'puestos_actualizar'=>'Permiso para actualizar puesto de trabajo',
        'puestos_crear'=>'Permiso para crear puesto de trabajo',
        'puestos_exportar'=>'Permiso para exportar puestos de trabajo',
        'puestos_listado'=>'Permiso para ver listado de puestos de trabajo',
        'puestos_ver'=>'Permiso para ver el puesto de trabajo',

        'requerimientos_actualizar'=>'Permiso para actualizar el requerimiento minimo',
        'requerimientos_crear'=>'Permiso para crear requerimiento minimo',
        'requerimientos_exportar'=>'Permiso para exportar requerimientos minimos',
        'requerimientos_listado'=>'Permiso para ver el listado de requerimientos minimos',
        'requerimientos_ver'=>'Permiso para ver requerimiento mínimo',

        'salidas_actualizar'=>'Permiso para actualizar salida',
        'salidas_crear'=>'Permiso para crear salida',
        'salidas_exportar'=>'Permiso para exportar listado de salidas',
        'salidas_listado'=>'Permiso para ver listado de salidas',
        'salidas_ver'=>'Permiso para ver salida',

        'trabajadores_actualizar'=>'Permiso para actualizar trabajador',
        'trabajadores_crear'=>'Permiso para crear trabajador',
        'trabajadores_delete' => 'Permiso para eliminar trabajador',
        'trabajadores_expediente'=>'Permiso para ver el expediente del trabajador',
        'trabajadores_exportar'=>'Permiso para exportar listado de trabajadores',
        'trabajadores_listado'=>'Permiso para ver el listado de trabajadores',
        'trabajadores_ver'=>'Permiso para ver trabajador',

        'turnos_actualizar'=>'Permiso para actualizar turno',
        'turnos_listado'=>'Permiso para ver el listado de turnos',
        'turnos_ver'=>'Permiso para ver turno',

        'usuarios_actualizar'=>'Permiso para actualizar usuario',
        'usuarios_crear'=>'Permiso para crear usuario',
        'usuarios_exportar'=>'Permiso para exportar listado de usuarios',
        'usuarios_listado'=>'Permiso para ver listado de usuarios',
        'usuarios_ver'=>'Permiso para ver usuario',

        'consentimientos_actualizar'=>'Permiso para actualizar Consentimiento',
        'consentimientos_crear'=>'Permiso para crear nuevo Consentimiento',
        'consentimientos_exportar'=>'Permiso para exportar listado de Consentimientos',
        'consentimientos_listado'=>'Permiso para ver el listado de Consentimientos',
        'consentimientos_ver'=>'Permiso para ver consentimiento',

        'vacantes_actualizar'=>'Permiso para actualizar Vacantes',
        'vacantes_asignar'=>'Permiso para asignar trabajador a vacante',
        'vacantes_crear'=>'Permiso para crear nueva vacante',
        'vacantes_exportar'=>'Permiso para exportar listado de vacantes',
        'vacantes_listado'=>'Permiso para ver el listado de vacantes',
        'vacantes_ver'=>'Permiso para ver vacante',

        'incapacidades_actualizar'=>'Permiso para actualizar incapacidad',
        'incapacidades_crear'=>'Permiso para crear nueva incapacidad',
        'incapacidades_exportar'=>'Permiso para exportar listado de incapacidades',
        'incapacidades_listado'=>'Permiso para ver el listado de incapacidades',
        'incapacidades_ver'=>'Permiso para ver incapacidad',

        'roles_actualizar'=>'Permiso para actualizar rol',
        'roles_crear'=>'Permiso para crear nuevo rol',
        'roles_exportar'=>'Permiso para exportar listado de roles',
        'roles_listado'=>'Permiso para ver el listado de roles',
        'roles_ver'=>'Permiso para ver roles',
        
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
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
            [['file_foto','file_firma'], 'file','extensions' => 'png, jpg', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['rol', 'id_firma', 'id_empresa','id_pais','id_linea', 'empresas_todos', 'status',
            
            'almacen_exportar',
            'almacen_listado',
    
            'antropometricos_crear',
            'antropometricos_exportar',
            'antropometricos_listado',
            'antropometricos_ver',

            'cargapoes_crear',
            'cargapoes_eliminar',
            'cargapoes_exportar',
            'cargapoes_listado',
            'cargapoes_ver',
    
            'cargatrabajadores_crear',
            'cargatrabajadores_eliminar',
            'cargatrabajadores_exportar',
            'cargatrabajadores_listado',
            'cargatrabajadores_ver',
    
            'categoriaestudio_actualizar',
            'categoriaestudio_crear',
            'categoriaestudio_exportar',
            'categoriaestudio_listado',
            'categoriaestudio_ver',
    
            'configuracion_actualizar',
            'configuracion_exportar',
            'configuracion_listado',
            'configuracion_ver',
    
            'consultas_actualizar',
            'consultas_crear',
            'consultas_exportar',
            'consultas_listado',
            'consultas_ver',
    
            'diagnosticoscie_actualizar',
            'diagnosticoscie_crear',
            'diagnosticoscie_exportar',
            'diagnosticoscie_listado',
            'diagnosticoscie_ver',

            'diagrama_actualizar',
            'diagrama_listado',
            'diagrama_ver',
    
            'empresas_actualizar',
            'empresas_crear',
            'empresas_exportar',
            'empresas_listado',
            'empresas_ver',
    
            'entradas_actualizar',
            'entradas_crear',
            'entradas_exportar',
            'entradas_listado',
            'entradas_ver',
    
            'epp_actualizar',
            'epp_crear',
            'epp_exportar',
            'epp_listado',
            'epp_ver',
    
            'eppsbitacora_actualizar',
            'eppsbitacora_crear',
            'eppsbitacora_exportar',
            'eppsbitacora_listado',
            'eppsbitacora_ver',
    
            'eppsstock_exportar',
            'eppsstock_listado',
    
            'eppsstockmin_actualizar',
            'eppsstockmin_crear',
            'eppsstockmin_exportar',
            'eppsstockmin_ver',
            'eppsstockmin_listado',

            'estudios_actualizar',
            'estudios_crear',
            'estudios_exportar',
            'estudios_listado',
            'estudios_ver',

            'firmas_actualizar',
            'firmas_crear',
            'firmas_exportar',
            'firmas_listado',
            'firmas_ver',
    
            'historial_exportar',
            'historial_listado',
    
            'historias_actualizar',
            'historias_cerrarexpediente',
            'historias_corregir',
            'historias_crear',
            'historias_delete',
            'historias_exportar',
            'historias_listado',
            'historias_ver',
    
            'medicamentos_actualizar',
            'medicamentos_crear',
            'medicamentos_exportar',
            'medicamentos_listado',
            'medicamentos_ver',
    
            'medicamentosbitacora_actualizar',
            'medicamentosbitacora_crear',
            'medicamentosbitacora_exportar',
            'medicamentosbitacora_listado',
            'medicamentosbitacora_ver',
    
            'medicamentosstock_exportar',
            'medicamentosstock_listado',
    
            'medicamentosstockmin_actualizar',
            'medicamentosstockmin_crear',
            'medicamentosstockmin_exportar',
            'medicamentosstockmin_listado',
            'medicamentosstockmin_ver',
    
            'nordicos_crear',
            'nordicos_exportar',
            'nordicos_listado',
            'nordicos_ver',

            'ordenpoe_actualizar',
            'ordenpoe_crear',
            'ordenpoe_exportar',
            'ordenpoe_listado',
            'ordenpoe_ver',

            'papelera_listado',
    
            'poes_actualizar',
            'poes_exportar',
            'poes_crear',
            'poes_documento',
            'poes_listado',
            'poes_ver',
            'poes_entrega',

            'programasalud_actualizar',
            'programasalud_crear',
            'programasalud_exportar',
            'programasalud_hc',
            'programasalud_listado',
            'programasalud_ver',
    
            'puestos_actualizar',
            'puestos_crear',
            'puestos_exportar',
            'puestos_listado',
            'puestos_ver',
    
            'requerimientos_actualizar',
            'requerimientos_crear',
            'requerimientos_exportar',
            'requerimientos_listado',
            'requerimientos_ver',
    
            'salidas_actualizar',
            'salidas_crear',
            'salidas_exportar',
            'salidas_listado',
            'salidas_ver',
    
            'trabajadores_actualizar',
            'trabajadores_crear',
            'trabajadores_delete',
            'trabajadores_expediente',
            'trabajadores_exportar',
            'trabajadores_listado',
            'trabajadores_ver',
    
            'turnos_actualizar',
            'turnos_listado',
            'turnos_ver',
    
            'usuarios_actualizar',
            'usuarios_crear',
            'usuarios_exportar',
            'usuarios_listado',
            'usuarios_ver',

            'consentimientos_actualizar',
            'consentimientos_crear',
            'consentimientos_exportar',
            'consentimientos_listado',
            'consentimientos_ver',

            'vacantes_actualizar',
            'vacantes_asignar',
            'vacantes_crear',
            'vacantes_exportar',
            'vacantes_listado',
            'vacantes_ver',

            'incapacidades_actualizar',
            'incapacidades_crear',
            'incapacidades_exportar',
            'incapacidades_listado',
            'incapacidades_ver',

            'roles_actualizar',
            'roles_crear',
            'roles_exportar',
            'roles_listado',
            'roles_ver',

    'empresa_all','permisos_all','areas_all','consultorios_all','programas_all',
    'linea_1','linea_2','linea_3','linea_4','linea_5','linea_6','linea_7','linea_8','linea_9','linea_10','linea_11','linea_12','linea_13','linea_14','linea_15','linea_16','linea_17','linea_18','linea_19','linea_20','linea_21','linea_22','linea_23','linea_24','linea_25','linea_26','linea_27','linea_28','linea_29','linea_30','linea_31','linea_32','linea_33','linea_34','linea_34','linea_35','linea_36','linea_37','linea_38','linea_39','linea_40','envia_form','hidden','admite_firma','tiempo_limitado','tiempo_dias','tiempo_horas','tiempo_minutos','cantidad_deleted_day','activo_eliminar','nivel1_all','nivel2_all','nivel3_all','nivel4_all'], 'integer'],
            [['name','f_nombre', 'f_universidad','nombre'], 'string', 'max' => 300],
            [['f_cedula','f_registro_sanitario'], 'string', 'max' => 50],
            [['empresas_select','trabajadores_select','areas_select','consultorios_select','programas_select','nivel1_select','nivel2_select','nivel3_select','nivel4_select'], 'safe'],
            [['username', 'password'], 'string', 'max' => 150],
            [['firma', 'foto','f_titulo'], 'string', 'max' => 100],
            [['f_abreviado_titulo','color'], 'string', 'max' => 10],
            [['authKey', 'accessToken','f_firma'], 'string', 'max' => 200],
            
            [['nombre'], 'required','on' =>['rol']],
            [['otra_rol'], 'required', 'when' => function($model) {
                return $model->rol == '0';
            }, 'whenClient' => "function (attribute, value) {
                return $('#usuarios-rol').val() == '0';
            }"],

            [['id_empresa','rol','name','username','password','status'], 'required', 'when' => function($model) {
                return $model->envia_form == '1';
            }, 'whenClient' => "function (attribute, value) {
                return $('#usuarios-envia_form').val() == '1';
            }"],

            ['username', 'unique'],
            [['status'], 'required','on' =>['create','update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Nombre'),
            'username' => Yii::t('app', 'Usuario'),
            'password' => Yii::t('app', 'Contraseña'),
            'rol' => Yii::t('app', 'Rol'),
            'otra_rol' => Yii::t('app', 'Nuevo Rol'),
            'firma' => Yii::t('app', 'Firma'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
            'id_firma' => Yii::t('app', 'Firma'),
            'id_empresa' => Yii::t('app', 'Empresa'),
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'empresa_all'=> Yii::t('app', 'Todas las Empresas'),
            'permisos_all' => Yii::t('app', 'Todos los Permisos'),

            'nivel1_all'=> Yii::t('app', 'Todo Nivel 1'),
            'nivel2_all'=> Yii::t('app', 'Todo Nivel 2'),
            'nivel3_all'=> Yii::t('app', 'Todo Nivel 3'),
            'nivel4_all'=> Yii::t('app', 'Todo Nivel 4'),

            'areas_all'=> Yii::t('app', 'Todas'),
            'consultorios_all'=> Yii::t('app', 'Todos'),
            'programas_all'=> Yii::t('app', 'Todos'),


            'empresas_select'=> Yii::t('app', 'Seleccione Empresas'),

            'trabajadores_select'=> Yii::t('app', 'Seleccione Trabajadores'),

            'nivel1_select'=> Yii::t('app', 'Seleccione Niveles 1'),
            'nivel2_select'=> Yii::t('app', 'Seleccione Niveles 2'),
            'nivel3_select'=> Yii::t('app', 'Seleccione Niveles 3'),
            'nivel4_select'=> Yii::t('app', 'Seleccione Niveles 4'),


            'areas_select'=> Yii::t('app', 'Seleccione Áreas'),
            'consultorios_select'=> Yii::t('app', 'Seleccione Consultorios'),
            'programas_select'=> Yii::t('app', 'Seleccione Programas'),
            
            'foto' => Yii::t('app', 'Foto'),
            'empresas_todos' => Yii::t('app', 'Todas las Empresas'),
            'status' => Yii::t('app', 'Status'),
            'f_nombre'=> Yii::t('app', 'Nombre Completo'),
            'f_universidad'=> Yii::t('app', 'Universidad'),
            'f_cedula'=> Yii::t('app', 'Cédula'),
            'f_firma'=> Yii::t('app', 'Firma'),
            'f_titulo'=> Yii::t('app', 'Título'),
            'f_abreviado_titulo'=> Yii::t('app', 'Abreviado Título'),
            'f_registro_sanitario'=> Yii::t('app', 'Registro Sanitario'),
            'nombre' => Yii::t('app', 'Rol'),
            'color' => Yii::t('app', 'Color'),
            'tiempo_limitado' => Yii::t('app', 'Limitar Tiempo de Edición'),
            'tiempo_dias' => Yii::t('app', 'Días'),
            'tiempo_horas' => Yii::t('app', 'Horas'),
            'tiempo_minutos' => Yii::t('app', 'Minutos'),

            'cantidad_deleted_day'=> Yii::t('app', 'Cantidad Eliminaciones'),
        ];
    }

    //Este lo pide pero lo dejamos como null por que no lo usamos por el momento
    public function getAuthKey() {
        return null;
       //return $this->auth_key;
    }    
    
    // interface
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() == $authKey;
    }
    
    // interface
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \yii\base\NotSupportedException("'findIdentityByAccessToken' is not implemented");
    }
    
    /* Identity Interface */
    public function getId(){
        return $this->id;
    }
        
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    // Utilizamos el mismo nombre de método que definimos como el nombre de usuario
    public static function findByUserName($username) {
        return static::findOne(['username' => $username]);
    }
    
    public function validatePassword($password) {
        return ($this->password === $password && $this->status == 1);
        //return Yii::$app->security->validatePassword($password, $this->password);
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
     * Gets query for [[Roles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::class, ['id' => 'rol']);
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFirmaa()
    {
        return $this->hasOne(Firmas::className(), ['id' => 'id_firma']);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getTrabajadores()
    {
        return $this->hasMany(Usuariotrabajador::className(), ['id_usuario' => 'id'])->orderBy(['status'=>SORT_ASC,'id_trabajador'=>SORT_ASC]);
    }

}