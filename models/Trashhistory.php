<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trash_history".
 *
 * @property int $id
 * @property int|null $model
 * @property int|null $id_model
 * @property int|null $deleted_date
 * @property int|null $deleted_user
 */
class Trashhistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trash_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'id_model', 'deleted_date', 'deleted_user'], 'default', 'value' => null],
            [['id_model', 'deleted_user','id_empresa','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['model', 'deleted_date'], 'safe'],
            [['contenido'], 'string', 'max' => 1000],
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
            
            'model' => Yii::t('app', 'Módulo'),
            'id_model' => Yii::t('app', 'Datos Módulo'),
            'deleted_date' => Yii::t('app', 'Fecha Borrado'),
            'deleted_user' => Yii::t('app', 'Eliminó'),
            'restored_date' => Yii::t('app', 'Fecha Restaurado'),
            'restored_user' => Yii::t('app', 'Restauró'),
            'contenido' => Yii::t('app', 'Datos Módulo'),
        ];
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

    
    public function getDatamodel()
    {
        $atributo = null;
        $modelos2 = [
            'AlergiaTrabajador' => 'Alergia Trabajador',
            'Almacen' => 'Almacén',
            'Areas' => 'Área',
            'Areascuestionario' => 'Areascuestionario',
            'Bitacora' => 'Bitácora',
            'Cargasmasivas' => 'Cargasmasivas',
            'Configconsentimientos' => 'Configconsentimientos',
            'Configuracion' => 'Configuracion',
            'Consentimientos' => 'Consentimientos',
            'Consultas' => 'Consultas',
            'Consultorios' => 'Consultorios',
            'ContactForm' => 'ContactForm',
            'Cuestionario' => 'Cuestionario',
            'DetalleCuestionario' => 'DetalleCuestionario',
            'Detallehc' => 'Detallehc',
            'Detallemovimiento' => 'Detallemovimiento',
            'Detalleordenpoe' => 'Detalleordenpoe',
            'Diagnosticoscie' => 'Diagnosticoscie',
            'Documentacion' => 'Documentacion',
            'DocumentoTrabajador' => 'DocumentoTrabajador',
            'Empresamails' => 'Empresamails',
            'Empresas' => 'Empresas',
            'Epps' => 'Epps',
            'Estudios' => 'Estudios',
            'ExtraccionBd' => 'ExtraccionBd',
            'Firmaempresa' => 'Firmaempresa',
            'Firmas' => 'Firmas',
            'Hccohc' => 'Hccohc',
            'Hccohcestudio' => 'Hccohcestudio',
            'Historialdocumentos' => 'Historialdocumentos',
            'Historicoes' => 'Historicoes',
            'Insumos' => 'Insumos',
            'Insumostockmin' => 'Insumostockmin',
            'Lineas' => 'Lineas',
            'LoginForm' => 'LoginForm',
            'Lotes' => 'Lotes',
            'Mantenimientoactividad' => 'Mantenimientoactividad',
            'Mantenimientocomponentes' => 'Mantenimientocomponentes',
            'Mantenimientos' => 'Mantenimientos',
            'Maquinaria' => 'Maquinaria',
            'Maquinariesgo' => 'Maquinariesgo',
            'Medidas' => 'Medidas',
            'Movimientos' => 'Movimientos',
            'NivelOrganizacional1' => 'NivelOrganizacional1',
            'NivelOrganizacional2' => 'NivelOrganizacional2',
            'NivelOrganizacional3' => 'NivelOrganizacional3',
            'NivelOrganizacional4' => 'NivelOrganizacional4',
            'Notification' => 'Notification',
            'Ordenespoes' => 'Ordenespoes',
            'Ordenpoetrabajador' => 'Ordenpoetrabajador',
            'Paisempresa' => 'Paisempresa',
            'Paises' => 'Paises',
            'Parametros' => 'Parametros',
            'Poeestudio' => 'Poeestudio',
            'Poes' => 'Poes',
            'Preguntas' => 'Preguntas',
            'Presentaciones' => 'Presentaciones',
            'Programaempresa' => 'Programaempresa',
            'ProgramaSalud' => 'ProgramaSalud',
            'Programasaludempresa' => 'Programasaludempresa',
            'ProgramaTrabajador' => 'ProgramaTrabajador',
            'PuestoEpp' => 'PuestoEpp',
            'PuestoEstudio' => 'PuestoEstudio',
            'Puestoparametro' => 'Puestoparametro',
            'PuestoRiesgo' => 'PuestoRiesgo',
            'Puestostrabajo' => 'Puestostrabajo',
            'Puestotrabajador' => 'Puestotrabajador',
            'Requerimientoempresa' => 'Requerimientoempresa',
            'Riesgos' => 'Riesgos',
            'Roles' => 'Roles',
            'Rolpermiso' => 'Rolpermiso',
            'Secciones' => 'Secciones',
            'Servicios' => 'Servicios',
            'SolicitudesDelete' => 'SolicitudesDelete',
            'TipoCuestionario' => 'TipoCuestionario',
            'TipoServicios' => 'TipoServicios',
            'Trabajadorepp' => 'Trabajadorepp',
            'Trabajadores' => 'Trabajador',
            'Trabajadorestudio' => 'Trabajadorestudio',
            'Trabajadormaquina' => 'Trabajadormaquina',
            'Trabajadorparametro' => 'Trabajadorparametro',
            'Trashhistory' => 'Trashhistory',
            'Turnopersonal' => 'Turnopersonal',
            'Turnos' => 'Turnos',
            'Ubicaciones' => 'Ubicaciones',
            'Unidades' => 'Unidades',
            'Usuarios' => 'Usuarios',
            'Vacantes' => 'Vacantes',
            'Vacunacion' => 'Vacunacion',
            'Vacantetrabajador' => 'Vacantetrabajador',
            'Vacunacion' => 'Vacunacion',
            'Viasadministracion' => 'Viasadministracion',
            'Vigencias' => 'Vigencias'
          
        ];

        $atributo = $modelos2[$this->model];

        
        return $atributo;
    }

    public function getElimina()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'deleted_user']);
    }

    public function getRestaura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'restored_user']);
    }

    public function getDataTrabajadores()
    {
        return $this->hasOne(Trabajadores::className(), ['id' => 'id_model']);
    }

    public function getDataServicios()
    {
        return $this->hasOne(Servicios::className(), ['id' => 'id_model']);
    }

}
