<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Usuarios;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\db\Expression;
use app\models\Empresas;
use app\models\Trabajadores;
use yii\helpers\ArrayHelper;
use Carbon\Carbon;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            //$this->calculaAntiguedad();

            if (Yii::$app->user->can('trabajadores_listado') ){
                return $this->redirect(['trabajadores/index']);
            } else {
                return $this->render('index');
            }
            
        }else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //date_default_timezone_set('America/Mazatlan');
            $user = Usuarios::findIdentity(Yii::$app->user->id);
            $user->last_login = new \yii\db\Expression('NOW()');
            $user->save();

            $session = Yii::$app->session;
            $session->set('trabajador', null);
            $session->set('maquina', null);

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    private function calculaAntiguedad(){
        $empresas = explode(',', Yii::$app->user->identity->empresas_select);
        if(Yii::$app->user->identity->empresa_all != 1) {
            $empresas = ArrayHelper::map(Empresas::find()->where(['in','id',$empresas])->orderBy('comercial')->all(), 'id', 'comercial');
        } else{
            $empresas = ArrayHelper::map(Empresas::find()->orderBy('comercial')->all(), 'id', 'comercial');
        }

        $ids_empresas = [];
        foreach($empresas as $key=>$item){
            if(!in_array($key, $ids_empresas)){
                array_push($ids_empresas, $key);
            }
        }

        $trabajadores = Trabajadores::find()->where(['in','id_empresa',$ids_empresas])->all();
        foreach($trabajadores as $key=>$model){
            if(isset($model->fecha_contratacion) && $model->fecha_contratacion != null && $model->fecha_contratacion != ''){
                if($model->status != 2){
                    $resultado_antiguedad = $this->actionCalculateantig($model->fecha_contratacion,$model);
                
                    $model->antiguedad = $resultado_antiguedad[0];
                    $model->antiguedad_anios = $resultado_antiguedad[1];
                    $model->antiguedad_meses = $resultado_antiguedad[2];
                    $model->antiguedad_dias = $resultado_antiguedad[3];
                    $model->save();
                }
            }
        }
        //dd($trabajadores);
        //dd($empresas);
    }

    public function actionCalculateantig($fecha,$model){
        //dd('Fecha: '.$fecha);
        $resultados = ['','','',''];

        $date = Carbon::parse($fecha.' 00:00:00');//Convertir a Carbon la fecha de contratación

        $now = Carbon::now();
        if($model->status == 2 && (isset($model->fecha_baja) && $model->fecha_baja != null  && $model->fecha_baja != '')){
            $now = Carbon::parse($model->fecha_baja.' 00:00:00');
        }
        
        $dias = $date->diffInDays($now);
        $meses = $date->diffInMonths($now);
        $anios = $date->diffInYears($now);
        
        $ret_anios = $anios;//Los años ya van en automatico
        $ret_meses = $meses;//los meses luego los calcularemos bien (restando es mes x año)
        $ret_dias = $dias; //Los dias luego los calcularemos bien

        $ret_antiguedad = '';

        if($ret_anios > 0){ //Si hay años
            /* $anios_dias = $ret_anios*365;
            $ret_dias = $dias - $anios_dias; */
            if($meses > 0){
                $ret_meses = $meses - ($ret_anios * 12);
            }
        }

        //Aumentar años y meses a la fecha inicial, ahora solo sacar los dias de diferencia y listo xd
        $new_date = $date->addYears($ret_anios);
        $new_date = $new_date->addMonths($ret_meses);
        $ret_dias = $new_date->diffInDays($now);
        //dd($ret_dias);

        //dd('FECHA CONTRATACIÓN: '.$fecha.' | Años: '.$anios.' | Meses: '.$meses.' | Días: '.$dias.' ------ Ret Años: '.$ret_anios.' | Ret Meses: '.$ret_meses.' | Ret Días: '.$ret_dias);

        /* if($ret_dias > 30){ //Si hay meses, calcular los dias sobrantes
            $ret_meses = intval($ret_dias/31);
            $ret_dias = $dias - ($ret_meses * 31);
        } */


        if($ret_anios > 0){
            $label = ' años';
            if($ret_anios == 1){
                $label = ' año';
            }
            $ret_antiguedad .= $ret_anios.$label;
        }
        if($ret_meses > 0){
            $label = ' meses';
            if($ret_meses == 1){
                $label = ' mes';
            }
            $ret_antiguedad .= ' '.$ret_meses.$label;
        }
        if($ret_dias > 0){
            $label = ' dias';
            if($ret_dias == 1){
                $label = ' dia';
            }
            $ret_antiguedad .= ' '.$ret_dias.$label;
        }

        $resultados[0] = trim($ret_antiguedad);
        $resultados[1] = $ret_anios;
        $resultados[2] = $ret_meses;
        $resultados[3] = $ret_dias;

        //dd('dias o: '.$dias.' | años o: '.$anios.' |||||| años: '.$ret_anios.' | meses: '.$ret_meses.' | dias: '.$ret_dias);

        return $resultados;
    }
}