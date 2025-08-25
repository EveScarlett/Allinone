<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">

    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php

    $empresa_name = '';

    $ver_empresa = false;
    $ver_rh = false;
    $ver_consultorio = false;
    $ver_inventario = false;
    $ver_medis = false;
    $ver_epp = false;
    $ver_poe = false;
    $ver_usuario = false;
    $ver_configuracion = false;
    $ver_maquinaria = false;
    $ver_programasalud = false;
    $ver_diagrama = false;

    if (Yii::$app->user->can('consultas_listado') || Yii::$app->user->can('historias_listado') || Yii::$app->user->can('poes_listado') || Yii::$app->user->can('nordicos_listado') || Yii::$app->user->can('antropometricos_listado')){
        $ver_consultorio = true;
    }

    if (Yii::$app->user->can('trabajadores_listado') || Yii::$app->user->can('historial_listado') || Yii::$app->user->can('puestos_listado')|| Yii::$app->user->can('vacantes_listado') || Yii::$app->user->can('requerimientos_listado') || Yii::$app->user->can('incapacidades_listado')|| Yii::$app->user->can('cargatrabajadores_listado')){
        $ver_rh = true;
    }
    
    if (Yii::$app->user->can('medicamentos_listado') || Yii::$app->user->can('medicamentosstockmin_listado') || Yii::$app->user->can('medicamentosstock_listado') || Yii::$app->user->can('medicamentosbitacora_listado') || Yii::$app->user->can('epp_listado') || Yii::$app->user->can('eppsstockmin_listado') || Yii::$app->user->can('eppsstock_listado') || Yii::$app->user->can('eppsbitacora_listado')){
        $ver_inventario = true;
    }

    if (Yii::$app->user->can('medicamentos_listado') || Yii::$app->user->can('medicamentosstockmin_listado') || Yii::$app->user->can('medicamentosstock_listado') || Yii::$app->user->can('medicamentosbitacora_listado')){
        $ver_medis = true;
    }

    if (Yii::$app->user->can('epp_listado') || Yii::$app->user->can('eppsstockmin_listado') || Yii::$app->user->can('eppsstock_listado') || Yii::$app->user->can('eppsbitacora_listado')){
        $ver_epp = true;
    }

    if(!Yii::$app->user->isGuest && Yii::$app->user->identity->empresa->configuracion->verseccion_maquina == 1){
        $ver_maquinaria = true;
    }

    if (Yii::$app->user->can('configuracion_listado') || Yii::$app->user->can('categoriaestudio_listado')|| Yii::$app->user->can('estudios_listado') || Yii::$app->user->can('programasalud_listado')|| Yii::$app->user->can('ordenpoe_listado') || Yii::$app->user->can('cargapoes_listado') || Yii::$app->user->can('poes_exportar') || Yii::$app->user->can('diagnosticoscie_listado') || Yii::$app->user->can('firmas_listado') || Yii::$app->user->can('consentimientos_listado') || Yii::$app->user->can('roles_listado')){
        $ver_configuracion = true;
    }

    if (Yii::$app->user->can('empresas_listado') || Yii::$app->user->can('diagrama_actualizar') || Yii::$app->user->can('diagrama_ver')){
        $ver_empresa = true;
    }




    if (Yii::$app->user->can('poes_listado') || Yii::$app->user->can('estudios_listado') || Yii::$app->user->can('ordenpoe_listado') || Yii::$app->user->can('cargapoes_listado')){
        $ver_poe = true;
    }

    if (Yii::$app->user->can('usuarios_listado')){
        $ver_usuario = true;
    }

    

    if (Yii::$app->user->can('programasalud_listado')){
        $ver_programasalud = true;
    }

    if (Yii::$app->user->can('diagrama_actualizar') || Yii::$app->user->can('diagrama_ver')){
        $ver_diagrama = true;
    }


    if(!Yii::$app->user->isGuest){
        $iconoff = '<span class="color6 font20"><i class="bi bi-bell-fill"></i></span>';
        $iconon = '<span class="color15 font20"><i class="bi bi-bell-fill"></i></span>';

        $badge = '<span class="badge rounded-pill badge-notification bg-danger">1</span>';

        $btnnotify = '<a data-mdb-dropdown-init class="me-3 dropdown-toggle hidden-arrow btn btn-sm text-center shadow-sm btnnotify" href="#" id="navbarDropdownMenuLink"
        role="button" data-mdb-toggle="dropdown" aria-expanded="false">
            '.$iconoff.'
            '.$badge.'
        </a>';

        $contenidonotify = ' <div class="dropdown">
        <button type="button" class="btn btn-sm text-center shadow-sm btnnotify dropdown-toggle" data-bs-toggle="dropdown">
        '.$iconoff.'
        '.$badge.'
        </button>
        <ul class="dropdown-menu notificaciones">
          <li><a class="dropdown-item notificacionitem" href="#">Link 1</a></li>
          <li><a class="dropdown-item notificacionitem" href="#">Link 2</a></li>
          <li><a class="dropdown-item notificacionitem" href="#">Link 3</a></li>
        </ul>
        </div> ';

        $contenidonotify = '';

        //$btnnotify = '<button type="button" class="btn btn-sm text-center shadow-sm btnnotify" style="" onclick="showNotify('.Yii::$app->user->identity->id.')">'.$iconoff.'</button>';
    }
    
    ?>
    <?php $this->beginBody() ?>

    <header>
        <?php
    NavBar::begin([
        'brandLabel' => '<img src="resources/images/logo DMM.ico" class="px-2" height="30px" width="auto"/>AlI1',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid mx-0 px-0']
    ]);


    if( !Yii::$app->user->isGuest){

        if(Yii::$app->user->identity->empresa){
            if(isset(Yii::$app->user->identity->empresa->logo) && Yii::$app->user->identity->empresa->logo != null && Yii::$app->user->identity->empresa->logo != '' && Yii::$app->user->identity->empresa->logo != ' '){
                $filePath =  '/resources/Empresas/'.Yii::$app->user->identity->empresa->id.'/'.Yii::$app->user->identity->empresa->logo;  
                $foto = '<div class="rectangle y_centercenter" style="background-image: url(\'/web/resources/Empresas/'.Yii::$app->user->identity->empresa->id."/".Yii::$app->user->identity->empresa->logo.'\');  background-position: center; background-size: contain; background-repeat: no-repeat;"></div>';
                $empresa_name = $foto;
            } else {
                $empresa_name = '<span class="badge rounded-pill bg-light color6 font10">'.Yii::$app->user->identity->empresa->comercial.'</span>';
            }
        }
        

        $menuinventario =  \yii\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu treeview'],
            'items' => [
                ['label' => 'Medicamentos','visible'=>$ver_medis, 
                    'url' => ['insumos/index','tipo'=>1],
                    'template' => '<a href="{url}" class="dropdown-item"  style="list-style-type: none;">{label}</a>',
                    'items' => [
                        ['label' => '<span class="text-dark">Stock Actual</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['almacen/index','tipo'=>1],'visible'=>Yii::$app->user->can('medicamentosstock_listado')],
                        ['label' => '<span class="text-dark">Bitácora de Movimientos</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['movimientos/index','tipo'=>1],'visible'=>Yii::$app->user->can('medicamentosbitacora_listado')],
                        ['label' => '<span class="text-dark">Catálogo de Medicamentos</span>','class'=>'m-1 mr-0 dropdown-item', 'style'=>"list-style: none;", 'url' => ['insumos/index','tipo'=>1],'visible'=>Yii::$app->user->can('medicamentos_listado')],
                        ['label' => '<span class="text-dark">Mínimos</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['insumostockmin/index','tipo'=>1],'visible'=>Yii::$app->user->can('medicamentosstockmin_listado')],
                       
                        
                    ],
                ],
                ['label' => 'Equipo de Protección','visible'=>$ver_epp,  
                    'url' => ['insumos/index','tipo'=>2],
                    'template' => '<a href="{url}" class="dropdown-item">{label}</a>',
                    'items' => [
                        ['label' => '<span class="text-dark">Stock Actual</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['almacen/index','tipo'=>2],'visible'=>Yii::$app->user->can('eppsstock_listado')],
                        ['label' => '<span class="text-dark">Bitácora de Movimientos</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['movimientos/index','tipo'=>2],'visible'=>Yii::$app->user->can('eppsbitacora_listado')],
                        ['label' => '<span class="text-dark">Catálogo EPP</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['insumos/index','tipo'=>2],'visible'=>Yii::$app->user->can('epp_listado')],
                        ['label' => '<span class="text-dark">Mínimos</span>','class'=>'m-1 mr-0 dropdown-item', 'url' => ['insumostockmin/index','tipo'=>2],'visible'=>Yii::$app->user->can('eppsstockmin_listado')],
                       
                        
                    ],
                ],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu dropdown-item'>\n{items}\n</ul>\n",
            'encodeLabels' => false,
            'activateParents' => true,   ]); 
            
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav'],
            'items' => [
            
                 ['label' => 'Consultorio','class'=>'m-0', 'url' => ['consultas/index'],'visible'=>$ver_consultorio,
                    'items' => [
                        ['label' => 'Consultas Médicas','class'=>'m-1 mr-0', 'url' => ['consultas/index'],'visible'=> Yii::$app->user->can('consultas_listado')],
                        ['label' => 'Historias Clínicas', 'url' => ['hccohc/index'],'visible'=>Yii::$app->user->can('historias_listado')],
                        ['label' => 'Exámenes Médicos','class'=>'m-1 mr-0', 'url' => ['poes/index'],'visible'=>Yii::$app->user->can('poes_listado')],
                        ['label' => 'Cuestionario Nórdico', 'url' => ['cuestionario/index','tipo'=>1],'visible'=>Yii::$app->user->can('nordicos_listado')],
                        ['label' => 'Evaluación Antropométrica', 'url' => ['cuestionario/index','tipo'=>4],'visible'=>Yii::$app->user->can('antropometricos_listado')],
                        ['label' => 'Programas de Salud', 'url' => ['programatrabajador/index','tipo'=>4],'visible'=>Yii::$app->user->can('trabajadores_listado')],
                    ],
                ],
                ['label' => 'Recursos Humanos','class'=>'m-0', 'url' => ['trabajadores/index'],'visible'=>$ver_rh,
                    'items' => [
                        ['label' => 'Listado Trabajadores','class'=>'m-1 mr-0', 'url' => ['trabajadores/index'],'visible'=>Yii::$app->user->can('trabajadores_listado')],
                        ['label' => 'Vencimiento de Documentos','class'=>'m-1 mr-0', 'url' => ['vencimientos/index'],'visible'=>Yii::$app->user->can('trabajadores_listado')],
                        ['label' => 'Historial Expediente', 'url' => ['historialdocumentos/index'],'visible'=>Yii::$app->user->can('historial_listado')],
                        ['label' => 'Nuevo Ingreso','class'=>'m-1 mr-0', 'url' => ['ni/index'],'visible'=>false],
                        ['label' => 'Puestos de Trabajo','class'=>'m-1 mr-0', 'url' => ['puestostrabajo/index'],'visible'=>Yii::$app->user->can('puestos_listado')],
                        ['label' => 'Vacantes','class'=>'m-1 mr-0', 'url' => ['vacantes/index'],'visible'=>Yii::$app->user->can('vacantes_listado')],
                        ['label' => 'Requisitos Mínimos', 'url' => ['requerimientoempresa/index'],'visible'=>Yii::$app->user->can('requerimientos_listado')],
                        ['label' => 'Incapacidades', 'url' => ['trabajadores/incapacidades'],'visible'=>Yii::$app->user->can('incapacidades_listado')],
                        ['label' => 'Carga Masiva - Trabajadores','class'=>'m-1 mr-0', 'url' => ['cargasmasivas/index'],'visible'=>Yii::$app->user->can('cargatrabajadores_listado')],
                        ['label' => 'Histórico Documentos', 'url' => ['/diagramas/historicodocs'],'visible'=>$ver_diagrama],
                    ],
                ],
               /*  ['label' => 'Exámenes Médicos','visible'=>$ver_poe,
                    'items' => [
                        
                    ],
                ], */
                ['label' => 'Inventario', 'url' => ['insumos/index'],'visible' => $ver_inventario
                ,'encodeLabels' => true,
                'items' => [
                    $menuinventario
                ]
                ],
                ['label' => 'Maquinaria','class'=>'m-0', 'url' => ['/maquinaria'],'visible'=>$ver_maquinaria,
                    'items' => [
                        ['label' => 'Maquinaria', 'url' => ['/maquinaria'],'visible'=>$ver_maquinaria],
                        ['label' => 'Histórico Operación Maquinaria', 'url' => ['/historicoes'],'visible'=>$ver_maquinaria],
                        ['label' => 'Histórico de Mantenimiento', 'url' => ['/mantenimientos'],'visible'=>$ver_maquinaria],
                    ],
                ],
                ['label' => 'Acciones','visible'=>$ver_configuracion,
                    'items' => [
                        ['label' => 'Configuración','class'=>'m-1 mr-0', 'url' => ['/configuracion'],'visible'=>$ver_configuracion],
                        ['label' => 'Configurar Categorías de Estudio','class'=>'m-1 mr-0', 'url' => ['tiposervicios/index'],'visible'=>Yii::$app->user->can('categoriaestudio_listado')],
                        ['label' => 'Configurar Listado de Estudios','class'=>'m-1 mr-0', 'url' => ['servicios/index'],'visible'=>Yii::$app->user->can('estudios_listado')],
                        ['label' => 'Configurar Programas de Salud','class'=>'m-1 mr-0', 'url' => ['programasalud/index'],'visible'=>$ver_programasalud],
                        ['label' => 'Ordenes de Trabajo - POE','class'=>'m-1 mr-0', 'url' => ['ordenespoes/index'],'visible'=>Yii::$app->user->can('ordenpoe_listado')],
                        ['label' => 'Carga Masiva - POE','class'=>'m-1 mr-0', 'url' => ['cargasmasivas/indexpoe'],'visible'=>Yii::$app->user->can('cargapoes_listado')],
                        ['label' => 'Exportar POEs','class'=>'m-1 mr-0', 'url' => ['poes/formexportar'],'visible'=>Yii::$app->user->can('poes_exportar')],
                        ['label' => 'Diagnósticos CIE','class'=>'m-1 mr-0', 'url' => ['diagnosticoscie/index'],'visible'=>false],
                        ['label' => 'Diagnósticos CIE','class'=>'m-1 mr-0', 'url' => ['diagnosticoscie/indexcie'],'visible'=>Yii::$app->user->can('diagnosticoscie_listado')],
                        ['label' => 'Lineas', 'url' => ['/lineas'],'visible'=>false],
                        ['label' => 'Ubicaciones', 'url' => ['/ubicaciones'],'visible'=>false],
                        ['label' => 'Áreas', 'url' => ['/areas'],'visible'=>false],
                        ['label' => 'Consultorios', 'url' => ['/consultorios'],'visible'=>false],
                        ['label' => 'Turnos', 'url' => ['/turnosempresa'],'visible'=>false],
                        ['label' => 'Programas de Salud', 'url' => ['/programaempresa'],'visible'=>false],

                       /*  ['label' => 'Turnos', 'url' => ['turnos/index'],'visible'=>Yii::$app->user->can('turnos_listado')], */
                        ['label' => 'Firmas Medico Laboral','class'=>'m-1 mr-0', 'url' => ['firmas/index'],'visible'=>Yii::$app->user->can('firmas_listado')],
                        ['label' => 'Formato de Consentimientos','class'=>'m-1 mr-0', 'url' => ['configconsentimientos/index'],'visible'=>Yii::$app->user->can('consentimientos_listado')],
                        ['label' => 'Roles','class'=>'m-1 mr-0', 'url' => ['/roles'],'visible'=>Yii::$app->user->can('roles_listado')],
                    ],
                ],
                ['label' => 'Corporativo','class'=>'m-0', 'url' => ['empresas/index'],'visible'=>$ver_empresa,
                    'items' => [
                        ['label' => 'Empresas', 'url' => ['/empresas'],'visible'=>Yii::$app->user->can('empresas_listado')],
                        ['label' => 'Diagrama Empresa', 'url' => ['/diagramas'],'visible'=>$ver_diagrama],
                        ['label' => 'KPIs', 'url' => ['/diagramas/indexkpi'],'visible'=>$ver_diagrama],
                        
                    ],
                ],
               
                ['label' => 'Usuarios', 'url' => ['/usuarios'],'visible'=>$ver_usuario],
               
                ['label' => '<span class="color11"><i class="bi bi-trash"></i></span>', 'url' => ['/trashhistory'],'visible'=>Yii::$app->user->can('papelera_listado')],
            ],
        ]); 
            
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest ? (
               ''
            ) : (
                ''.$contenidonotify.''
            )
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
               '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')'.'<br>'.$empresa_name,
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);

    NavBar::end();
    ?>


    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid py-5 my-3 mt-5">
            <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'itemTemplate'=>'<li>{link}</li><span class="px-2 text-primary"><i class="bi bi-chevron-right"></i></span>',
            'homeLink'=>['url' => ['site/index'], 'label' =>'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
          </svg>','encode' => false]
        ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; Dmm Tecno Desarrollos <?= 2024 ?></p>
            <p class="float-right"><?= 'SMO TOOLS' ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>