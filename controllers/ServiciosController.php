<?php

namespace app\controllers;

use app\models\Servicios;
use app\models\TipoServicios;
use app\models\ServiciosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Trashhistory;

use Yii;

/**
 * ServiciosController implements the CRUD actions for Servicios model.
 */
class ServiciosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Servicios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ServiciosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicios model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Servicios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Servicios();
        //date_default_timezone_set('America/Mazatlan');
        $model->scenario = 'create';
        $model->status = 1;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

                $ultimo = Servicios::find()->where(['id_tiposervicio'=>$model->id_tiposervicio])->orderBy(['orden'=>SORT_DESC])->one();
                
                if($ultimo){
                    $model->orden = $ultimo->orden + 1;
                    $model->save();
                } else{
                    $model->orden = 1;
                    $model->save();
                }
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Servicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $status_anterior = intval($model->getOldAttribute('status'));
            $status_actual = intval($model->status);
            if($status_actual == 2){
                if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){
                } else {
                    $model->status = $status_anterior;
                }
            
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Servicios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model){
            $model->status_backup = $model->status;
            $model->status = 2;
            $model->status_baja = 1;
            $model->save(false);
            //dd($model);

            $this->saveTrash($model,'Servicios');
        }

        return $this->redirect(['index']);
    }


    public function saveTrash($model,$tipo_modelo){
        $modelos = [
            '0' => 'AlergiaTrabajador',
            '1' => 'Almacen',
            '2' => 'Areas',
            '3' => 'Areascuestionario',
            '4' => 'Bitacora',
            '5' => 'Cargasmasivas',
            '6' => 'Configconsentimientos',
            '7' => 'Configuracion',
            '8' => 'Consentimientos',
            '9' => 'Consultas',
            '10' => 'Consultorios',
            '11' => 'ContactForm',
            '12' => 'Cuestionario',
            '13' => 'DetalleCuestionario',
            '14' => 'Detallehc',
            '15' => 'Detallemovimiento',
            '16' => 'Detalleordenpoe',
            '17' => 'Diagnosticoscie',
            '18' => 'Documentacion',
            '19' => 'DocumentoTrabajador',
            '20' => 'Empresamails',
            '21' => 'Empresas',
            '22' => 'Epps',
            '23' => 'Estudios',
            '24' => 'ExtraccionBd',
            '25' => 'Firmaempresa',
            '26' => 'Firmas',
            '27' => 'Hccohc',
            '28' => 'Hccohcestudio',
            '29' => 'Historialdocumentos',
            '30' => 'Historicoes',
            '31' => 'Insumos',
            '32' => 'Insumostockmin',
            '33' => 'Lineas',
            '34' => 'LoginForm',
            '35' => 'Lotes',
            '36' => 'Mantenimientoactividad',
            '37' => 'Mantenimientocomponentes',
            '38' => 'Mantenimientos',
            '39' => 'Maquinaria',
            '40' => 'Maquinariesgo',
            '41' => 'Medidas',
            '42' => 'Movimientos',
            '43' => 'NivelOrganizacional1',
            '44' => 'NivelOrganizacional2',
            '45' => 'NivelOrganizacional3',
            '46' => 'NivelOrganizacional4',
            '47' => 'Notification',
            '48' => 'Ordenespoes',
            '49' => 'Ordenpoetrabajador',
            '50' => 'Paisempresa',
            '51' => 'Paises',
            '52' => 'Parametros',
            '53' => 'Poeestudio',
            '54' => 'Poes',
            '55' => 'Preguntas',
            '56' => 'Presentaciones',
            '57' => 'Programaempresa',
            '58' => 'ProgramaSalud',
            '59' => 'Programasaludempresa',
            '60' => 'ProgramaTrabajador',
            '61' => 'PuestoEpp',
            '62' => 'PuestoEstudio',
            '63' => 'Puestoparametro',
            '64' => 'PuestoRiesgo',
            '65' => 'Puestostrabajo',
            '66' => 'Puestotrabajador',
            '67' => 'Requerimientoempresa',
            '68' => 'Riesgos',
            '69' => 'Roles',
            '70' => 'Rolpermiso',
            '71' => 'Secciones',
            '72' => 'Servicios',
            '73' => 'SolicitudesDelete',
            '74' => 'TipoCuestionario',
            '75' => 'TipoServicios',
            '76' => 'Trabajadorepp',
            '77' => 'Trabajadores',
            '78' => 'Trabajadorestudio',
            '79' => 'Trabajadormaquina',
            '80' => 'Trabajadorparametro',
            '81' => 'Trashhistory',
            '82' => 'Turnopersonal',
            '83' => 'Turnos',
            '84' => 'Ubicaciones',
            '84' => 'Unidades',
            '85' => 'Usuarios',
            '86' => 'Vacantes',
            '87' => 'Vacunacion',
            '88' => 'Vacantetrabajador',
            '89' => 'Vacunacion',
            '90' => 'Viasadministracion',
            '91' => 'Vigencias'
          
        ];

        $modelos2 = [
            'AlergiaTrabajador' => 'AlergiaTrabajador',
            'Almacen' => 'Almacen',
            'Areas' => 'Areas',
            'Areascuestionario' => 'Areascuestionario',
            'Bitacora' => 'Bitacora',
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
            'Trabajadores' => 'Trabajadores',
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

        date_default_timezone_set('America/Costa_Rica');
        $trash = new Trashhistory();
        $trash->model = $tipo_modelo;
        $trash->id_model = $model->id;
        $trash->id_empresa = $model->id_empresa;
        $trash->deleted_date = date('Y-m-d H:i:s');
        $trash->deleted_user = Yii::$app->user->identity->id;
        $trash->save();

        if($model){
            $trash->contenido = $model->nombre;
            $trash->save();
        }
        //dd($trash);

    }

    /**
     * Finds the Servicios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Servicios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
