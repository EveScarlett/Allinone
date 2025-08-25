<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'AlI1',
    'name'=>'All In One',
    'language'=>'es',
    'timeZone' => 'America/Costa_Rica',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8s5ujnr3hAf2AV_6KpDw2Goxrl5cR9ZU',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Usuarios',
            'enableAutoLogin' => false,
            'authTimeout' => 3600, 
            'absoluteAuthTimeout' => 86400,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app*' => 'app.php',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'dmm-td.com.medicalfil.com', // ej. smtp.mandrillapp.com o smtp.gmail.com
                'username' => 'noreply@dmm-td.com.medicalfil.com',
                'password' => 'M6Y~v9[3Q3eN',
                'port' => '465', // El puerto 25 es un puerto comn también
                'encryption' => 'ssl',
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
            'useFileTransport'=> false,
        ],
       
        'mailerGmail' => [
			'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,

			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.gmail.com',
				'username' => 'dmmsmomail@gmail.com',
				'password' => 'M6Y~v9[3Q3eN',
				'port' => '587',
				'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
			],
		],
        'notifier' => [
            'class' => '\tuyakhov\notifications\Notifier',
            'channels' => [
                'mail' => [
                    'class' => '\tuyakhov\notifications\channels\MailChannel',
                    'from' => 'noreply@dmm-td.com.medicalfil.com'
                ],
                'database' => [
                     'class' => '\tuyakhov\notifications\channels\ActiveRecordChannel'
                ]
            ],
            
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'tuyakhov\notifications\migrations',
            ],
        ],
        /* 'db' => $db, */
        'db' => $db[1],
        'db_cuestionario' => $db[2],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class,
        'allowActions' => [
            'site/login',
            'site/logout',
            'site/index',
            'site/captcha',
            'rbac/*',

            'consultas/pdf',
            'hccohc/pdf',
            'cuestionario/pdf',
            'antropometrica/pdf',
            'almacen/infoinsumo',
            'poes/indexexportar',
            'poes/card',
            'poes/delete',
            'poes/formexportar',
            'trabajadores/loadqr',
            'trabajadores/qr',
            'empresas/registrarqrs',
            'maquinaria/*',
            'historicoes/*',
            'mantenimientos/*',
            'hccohc/infopuesto',
            'vencimientos/*',
            'hccohc/delete',
            'trabajadores/refresh',
            'trabajadores/refreshpoes',
            'hccohc/correction',

            'areas/*',
            'consultorios/*',
            'lineas/*',
            'programaempresa/*',
            'turnosempresa/*',
            'ubicaciones/*',
            'diagramas/*',
            'empresas/getpaises',
            'empresas/getlineas',

            'empresas/listpaises',
            'empresas/listlineas',
            'empresas/listubicaciones',
            'empresas/addnivel1',
            'empresas/addnivel2',
            'empresas/addnivel3',
            'empresas/addnivel4',
            'empresas/addcontenido',

            'empresas/editnivel1',
            'empresas/editnivel2',
            'empresas/editnivel3',
            'empresas/editnivel4',

            'empresas/editkpinivel1',
            'empresas/editkpinivel2',
            'empresas/editkpinivel3',
            'empresas/editkpinivel4',

            'empresas/editkpi',

            'hccohc/infonivel1',
            'hccohc/infonivel2',
            'hccohc/infonivel3',
            'hccohc/infonivel4',

            'trabajadores/privacy',
            'trabajadores/consentimiento',
            'trabajadores/getnivelestrabajador',

            'trashhistory/*',

            'poes/consentimiento',
            'hccohc/consentimiento',
            'consultas/consentimiento',
            'cuestionario/consentimiento',

            'poes/consentimientopdf',
            'hccohc/consentimientopdf',
            'consultas/consentimientopdf',
            'cuestionario/consentimientopdf',

            'consultas/delete',

            'cargasmasivas/viewpoe',

            'servicios/delete',

            'poes/enlazar',

            'diagnosticoscie/indexcie',
            'diagramas/indexkpi',
            'trabajadores/refreshtrabhc',
            'hccohc/refreshnames',
            'consultas/refreshnames',
            'poes/refreshnames',
            'cuestionario/refreshnames',
            'antropometrica/refreshnames',
            'poes/index',
            'hccohc/pdfcal',
            'trabajadores/refreshcumplimientos',
            'trashhistory/refresh',
            'trashhistory/restore',
            'trabajadores/refreshprogramas',
            'programatrabajador/*',
            'ni/*',

/*             'almacen/*',
            'consultas/*',
            'diagnosticoscie/*',
            'empresas/*',
            'historialdocumentos/*',
            'insumos/*',
            'movimientos/*',
            'poes/*',
            'puestostrabajo/*',
            'requerimientoempresa/*',
            'trabajadores/*',
            'usuarios/*',
            'antropometrica/*',
            'cargasmasivas/*',
            'configconsentimientos/*',
            'configuracion/*',
            'cuestionario/*',
            'firmas/*',
            'insumostockmin/*',
            'ordenespoes/*',
            'roles/*',
            'servicios/*',
            'solicitudesdelete/*',
            'tiposervicios/*',
            'turnos/*',

            'hccohc/close',
            'hccohc/delete',
            'hccohc/index',
            'hccohc/infoempresa',
            'hccohc/infotrabajador',
            'hccohc/pdf',
            'hccohc/pdfcal',
            'hccohc/update',
            'hccohc/view',
            'vacantes/*', */

            'gii/*'
        ] 
     ], 
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
       
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
