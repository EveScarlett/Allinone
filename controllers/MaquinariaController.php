<?php

namespace app\controllers;

use app\models\Maquinaria;
use app\models\MaquinariaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use app\models\Trabajadores;
use app\models\Trabajadormaquina;
use app\models\Empresamails;
use app\models\Historicoes;
use app\models\Maquinariesgo;

use app\models\NivelOrganizacional1;

use Yii;
require_once __DIR__ . '/../web/phpqrcode/qrlib.php';
/**
 * MaquinariaController implements the CRUD actions for Maquinaria model.
 */
class MaquinariaController extends Controller
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
     * Lists all Maquinaria models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MaquinariaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Maquinaria model.
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
     * Creates a new Maquinaria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Maquinaria();
        $model->status = 1;
        $model->envia_form = 0;
        $model->scenario = 'create';
        $model->id_empresa = Yii::$app->user->identity->id_empresa;

        $this->actionGetniveles($model);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                if($model->envia_form == '1'){
                    $model->create_date = date('Y-m-d H:i:s');
                    $model->create_user = Yii::$app->user->identity->id;
                    $model->status_operacion = 2;
                    $model->save();

                    $archivo = UploadedFile::getInstance($model,'file_logo');
                    $dir0 = __DIR__ . '/../web/resources/Maquinarias/';
                    $dir1 = __DIR__ . '/../web/resources/Maquinarias/'.$model->id.'/';
                    $directorios = ['0'=>$dir0,'1'=>$dir1];
                    $this->actionCarpetas( $directorios);
                    
                    if($archivo){
                        $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                        $archivo->saveAs($directorios[1].'/'. $nombre_archivo);
                        $model->foto = $nombre_archivo; 
                        $model->save();
                    } 
                    

                    $this->actionCrearqr($model);
                    $this->saveMultiple($model);

                    return $this->redirect(['index']);
                } 
            } else {
                dd($model);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionGetniveles($model){
        $empresa_actual = null;
        if(Yii::$app->user->identity->empresa_all != 1){
            $array_empresas = explode(',', Yii::$app->user->identity->empresas_select);
            if(count($array_empresas) == 1){
                $empresa_actual = $array_empresas[0];
            }
        }

        //dd($model,Yii::$app->user->identity,$empresa_actual,Yii::$app->user->identity->id_empresa);
        if($empresa_actual == Yii::$app->user->identity->id_empresa){
            if(Yii::$app->user->identity->nivel1_all != 1){
                $array_nivel1 = explode(',', Yii::$app->user->identity->nivel1_select);
                if(count($array_nivel1) == 1){
                    $nivel1_= NivelOrganizacional1::find()->where(['id_empresa'=>$model->id_empresa])->andWhere(['id_pais'=>$array_nivel1[0]])->andWhere(['status'=>1])->one();
                    if($nivel1_){
                        
                        $model->id_nivel1 = $nivel1_->id;
                    }
                    
                }
            }

            if(Yii::$app->user->identity->nivel2_all != 1){
                $array_nivel2 = explode(',', Yii::$app->user->identity->nivel2_select);
                if(count($array_nivel2) == 1){
                    $model->id_nivel2 = $array_nivel2[0];
                }
            }

            if(Yii::$app->user->identity->nivel3_all != 1){
                $array_nivel3 = explode(',', Yii::$app->user->identity->nivel3_select);
                if(count($array_nivel3) == 1){
                    $model->id_nivel3 = $array_nivel3[0];
                }
            }

            if(Yii::$app->user->identity->nivel4_all != 1){
                $array_nivel4 = explode(',', Yii::$app->user->identity->nivel4_select);
                if(count($array_nivel4) == 1){
                    $model->id_nivel4 = $array_nivel4[0];
                }
            }
        }
        
    }

    /**
     * Updates an existing Maquinaria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if($model->qr == null || $model->qr == '' || $model->qr == ' '){
            $this->actionCrearqr($model);
        }

        foreach($model->riesgos as $key => $detalle){
            $model->aux_riesgos[$key]['riesgo'] = $detalle->riesgo;
            $model->aux_riesgos[$key]['id'] = $detalle->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            if($model->envia_form == '1'){

                $status_anterior = intval($model->getOldAttribute('status'));
                $status_actual = intval($model->status);
                if($status_actual == 0){
                    if($status_actual != $status_anterior && Yii::$app->user->identity->activo_eliminar != 2){

                    } else {
                        $model->status = $status_anterior;
                    }
                }

                $model->update_date = date('Y-m-d H:i:s');
                $model->update_user = Yii::$app->user->identity->id;

                $archivo = UploadedFile::getInstance($model,'file_logo');
                $dir0 = __DIR__ . '/../web/resources/Maquinarias/';
                $dir1 = __DIR__ . '/../web/resources/Maquinarias/'.$model->id.'/';
                $directorios = ['0'=>$dir0,'1'=>$dir1];
                $this->actionCarpetas( $directorios);
                
                if($archivo){
                    $nombre_archivo = 'foto_'.$model->id.'_'.date('Y-m-d-H-i-s').'.'. $archivo->extension;
                    $archivo->saveAs($directorios[1].'/'. $nombre_archivo);
                    $model->foto = $nombre_archivo; 
                    $model->save();
                } 

                $model->save();
                $this->saveMultiple($model);

                return $this->redirect(['index']);
            } 
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Maquinaria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Maquinaria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Maquinaria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Maquinaria::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function actionCarpetas(&$dir1) {
        foreach($dir1 as $key=>$dir){
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                umask(0);
                chmod($dir, 0777);
            } else {
                umask(0);
                chmod($dir, 0777);
            }
        }
    }

    public function actionCrearqr($model){
        date_default_timezone_set('America/Costa_Rica');
        $id_unique = ''.$model->id.$model->id_empresa.''.uniqid();
        $model->qr = $id_unique;
        $model->save();

        //dd($id_unique);

        $dir_qr = __DIR__ . '/../web/qrsmaquinas';
        $dir_qr2 = __DIR__ . '/../web/qrsmaquinas/'.$model->id;
        $directorios = ['0'=>$dir_qr,'1'=>$dir_qr2];
        $this->actionCarpetas( $directorios);
        
        $dominio = 'https://www.dmm-smo.com';
        $dirqr = __DIR__ . '/../web/qrsmaquinas/' . $model->id;

        if (!(file_exists( $dirqr . '/' . $model->id . '.png'))) {
            //dd('entra');
            $contenido = $dominio.'/web/index.php?r=maquinaria%2Fqr&id=' . $model->qr;
            $ECClevel = 'M';
            $pixelSize = 10;
            $frameSize = 4;
            \QRcode::png($contenido, $dirqr . '/' . $model->id . 'qr.png', $ECClevel, $pixelSize, $frameSize);
        }

        return null;
                
    }


    public function actionLoadqr($id)
    {
        date_default_timezone_set('America/Costa_Rica');
        $model = Maquinaria::findOne($id);

        return $this->renderAjax('viewqr', [
            'model' => $model,
        ]);
    }


    public function actionQr($id)
    {
        $session = Yii::$app->session;
        $id_trabajador = $session['trabajador'];

        date_default_timezone_set('America/Costa_Rica');
        $model = Trabajadores::findOne($id_trabajador);
        $maquina = Maquinaria::find()->where(['qr'=>$id])->one();
        $relacion = null;
        $autorizado = 0;
        $status_msg = null;
        $msg = '';
        
        $session->set('maquina', $maquina->id);
        //dd($session['maquina'],$session['trabajador']);
        if($model && $maquina){
            
            $relacion = Trabajadormaquina::find()->where(['id_trabajador'=>$model->id])->andWhere(['id_maquina'=>$maquina->id])->one();
            if($relacion && $relacion->status == 1 && ( $model->status_documentos == 0 || $model->status_documentos == 1)){
                $autorizado = 1;
            } else {
                $status_msg = 100;
                $msg = 'No esta autorizado a operar esta Maquinaria';
            }
        }

        if($maquina){
            $maquina->id_maquina = $session['maquina'];
            $maquina->id_trabajador = $session['trabajador'];

            if($relacion){
                $maquina->status_trabajo = $relacion->status_trabajo;
            }
        }

        if ($this->request->isPost) {
            if ($maquina->load($this->request->post())) {
                //dd($session['maquina'],$session['trabajador']);
                $relacion = Trabajadormaquina::find()->where(['id_trabajador'=>$maquina->id_trabajador])->andWhere(['id_maquina'=>$maquina->id_maquina])->one();
                if($relacion){
                    if($maquina->status_trabajo == 1 || $maquina->status_trabajo == 3){
                        
                        $relacion->status_trabajo = intval($maquina->status_trabajo);

                        if($maquina->status_trabajo == 1){
                            $relacionesactivas = Trabajadormaquina::find()->where(['id_maquina'=>$maquina->id_maquina])->andWhere(['status_trabajo'=>1])->all();
                            if(!$relacionesactivas){
                                $maquina->fecha_inicio = date('Y-m-d H:i:s');
                                $maquina->save(false);
                            }

                            $maquina->status_operacion = 1;
                            $maquina->save(false);

                            $relacion->fecha_inicio = date('Y-m-d H:i:s'); 
                            $relacion->fecha_fin = null;

                            $status_msg = 200;
                            $msg = 'El Trabajador inicio operación correctamente';


                            $mail_inicio = '';
                            $mail_fin = '';
                            $mail_horainicio = '';
                            $mail_horafin = '';
                            $mail_trabajador = '';
                            $mail_maquina = '';
                            

                            $mail_inicio = date('d/m/Y', strtotime($relacion->fecha_inicio));
                            $mail_fin = '--';
                            $mail_horainicio = date('H:i', strtotime($relacion->fecha_inicio));
                            $mail_horafin = '';

                            if($model){
                                $mail_trabajador = $model->nombre.' '.$model->apellidos;
                            }
                            if($maquina){
                                $mail_maquina = $maquina->maquina.' '.$maquina->clave;
                            }
                            
                            $mails_empresa = Empresamails::find()->where(['id_empresa'=>$maquina->id_empresa])->andwhere(['in','tipo_mail',[1,3]])->select('mail')->distinct()->all();
                            //dd($mails_empresa);
                            if($mails_empresa){
                                foreach($mails_empresa as $key=>$mail){
                                    $mail_send = $mail->mail;
                                    try {
                                        Yii::$app->mailer->compose()
                                        ->setFrom('noreply@dmm-td.com.medicalfil.com')
                                        ->setTo($mail_send)
                                        ->setSubject('NUEVO INICIO DE OPERACIÓN')
                                        ->setTextBody('Message Info')
                                        ->setHtmlBody('<h1>INICIO DE OPERACIÓN '.$mail_trabajador.'</h1>
                                                       <div class="container" style="font-size:23px; margin-top:20px;color:black;">
                                                       <div class="row">Fecha Inicio: '.$mail_inicio.' '.$mail_horainicio.'</div>
                                                       <div class="row">Fecha Fin: '.$mail_fin.' '.$mail_horafin.'</div>
                                                       <div class="row" style="color:black;margin-top:20px;">El Trabajador <span style="color:#0903A6;"><b>'.$mail_trabajador.'</b></span> <b>INICIÓ</b> operación en la máquina <span style="color:#0903A6;"><b>'.$mail_maquina.'</b></span> correctamente.</div>
                                                       </div>')
                                        ->send();
                            
                                    } catch (\Throwable $th) {
                                       //dd($th);
                                    }
                                }
                            }
                
                        } else if($maquina->status_trabajo == 3){
                            $relacion->fecha_fin = date('Y-m-d H:i:s');

                            $status_msg = 400;
                            $msg = 'El Trabajador finalizó operación correctamente';

                            $mail_inicio = '';
                            $mail_fin = '';
                            $mail_horainicio = '';
                            $mail_horafin = '';
                            $mail_trabajador = '';
                            $mail_maquina = '';
                            

                            $mail_inicio = date('d/m/Y', strtotime($relacion->fecha_inicio));
                            $mail_fin = date('d/m/Y', strtotime($relacion->fecha_fin));
                            $mail_horainicio = date('H:i', strtotime($relacion->fecha_inicio));
                            $mail_horafin = date('H:i', strtotime($relacion->fecha_fin));

                            if($model){
                                $mail_trabajador = $model->nombre.' '.$model->apellidos;
                            }
                            if($maquina){
                                $mail_maquina = $maquina->maquina.' '.$maquina->clave;
                            }
                            
                            
                            $mails_empresa = Empresamails::find()->where(['id_empresa'=>$maquina->id_empresa])->andwhere(['in','tipo_mail',[2,3]])->select('mail')->distinct()->all();
                            //dd($mails_empresa);
                            if($mails_empresa){
                                foreach($mails_empresa as $key=>$mail){
                                    $mail_send = $mail->mail;
                                    try {
                                        Yii::$app->mailer->compose()
                                        ->setFrom('noreply@dmm-td.com.medicalfil.com')
                                        ->setTo($mail_send)
                                        ->setSubject('FIN DE OPERACIÓN')
                                        ->setTextBody('Message Info')
                                        ->setHtmlBody('<h1>FIN DE OPERACIÓN '.$mail_trabajador.'</h1>
                                                       <div class="container" style="font-size:23px; margin-top:20px;color:black;">
                                                       <div class="row">Fecha Inicio: '.$mail_inicio.' '.$mail_horainicio.'</div>
                                                       <div class="row">Fecha Fin: '.$mail_fin.' '.$mail_horafin.'</div>
                                                       <div class="row" style="color:black;margin-top:20px;">El Trabajador <span style="color:#0903A6;"><b>'.$mail_trabajador.'</b></span> <b>FINALIZÓ</b> operación en la máquina <span style="color:#0903A6;"><b>'.$mail_maquina.'</b></span> correctamente.</div>
                                                       </div>')
                                        ->send();
                            
                                    } catch (\Throwable $th) {
                                       //dd($th);
                                    }
                                }
                            }
                            
                        }

                        $relacion->save();
                        if($relacion){
                            $this->guardarHistorico($relacion);
                        }

                    } else if ($maquina->status_trabajo == 2){

                        $relacion->status_trabajo = null;
                        $relacion->save();

                        if($relacion){
                            $this->guardarHistorico($relacion);
                        }

                    }


                    $relacionesactivas = Trabajadormaquina::find()->where(['id_maquina'=>$maquina->id])->andWhere(['status_trabajo'=>1])->all();
                    if(!$relacionesactivas){
                        $maquina->status_operacion = 2;
                        $maquina->fecha_fin = date('Y-m-d H:i:s');
                        $maquina->save(false);
                    } else {
                        $maquina->status_operacion = 1;
                        $maquina->fecha_inicio = date('Y-m-d H:i:s');
                        $maquina->save(false);
                    }
                }
            }
        }

        return $this->render('qr', [
            'model'=>$model,
            'maquina'=> $maquina,
            'relacion'=>$relacion,
            'autorizado'=>$autorizado,
            'status_msg'=>$status_msg,
            'msg'=>$msg
        ]);
    }


    public function guardarHistorico($model){
        date_default_timezone_set('America/Costa_Rica');

        if($model){
            if($model->status_trabajo == 1){
                $historico = new Historicoes();
                $historico->fecha_inicio = $model->fecha_inicio; 
                
                $historico->create_date = date('Y-m-d H:i:s');
                if(!Yii::$app->user->isGuest){
                    $historico->create_user = Yii::$app->user->identity->id;
                }
                
            } else {
                $historico = Historicoes::find()->where(['id_trabajador'=>$model->id_trabajador])->andWhere(['id_maquina'=>$model->id_maquina])->andWhere(['IS', 'fecha_fin', new \yii\db\Expression('NULL')])->orderBy(['id'=>SORT_DESC])->one();
                if($historico){
                    $historico->fecha_fin = $model->fecha_fin;
                } 
            }  
            
            if($historico){
                $historico->id_empresa = $model->trabajador->id_empresa;
                $historico->id_trabajador = $model->id_trabajador;
                $historico->id_maquina = $model->id_maquina;
                $historico->status_trabajo = $model->status_trabajo;
                
                
                $historico->status = 1;
                $historico->save();
            }
            
        }
        
        return null;
    }


    private function saveMultiple($model){
        if (true){

            $array_riesgos = $model->aux_riesgos;

            $id_riesgos = [];
            if(isset($array_riesgos) &&  $array_riesgos != null &&  $array_riesgos != ''){
                foreach($array_riesgos as $key => $riesgo){
    
                    if(isset($riesgo['id']) && $riesgo['id'] != null && $riesgo['id'] != ''){
                        $dm = Maquinariesgo::findOne($riesgo['id']);
                    } else {
                        $dm = new Maquinariesgo();
                        $dm->id_maquina = $model->id;
                        $dm->create_date = date('Y-m-d H:i:s');
                        $dm->create_user = Yii::$app->user->identity->id;
                    }
                    $dm->status = 1;
    
                    if(isset($riesgo['riesgo']) && $riesgo['riesgo'] != null && $riesgo['riesgo'] != '' && $riesgo['riesgo'] != ' ' ){
    
                        $dm->riesgo = $riesgo['riesgo'];
                        $dm->status =1;
                           
                        $dm->update_date = date('Y-m-d H:i:s');
                        $dm->update_user = Yii::$app->user->identity->id;
                        $dm->save();

                        if($dm){
                            array_push($id_riesgos, $dm->id);
                        }  
                        
                    }
                }
            }
    
            $deletes = Maquinariesgo::find()->where(['id_maquina'=>$model->id])->andWhere(['not in','id',$id_riesgos])->all();
            foreach($deletes as $delete){//Eliminar los que se hayan quitado
                $delete->delete();
            }

        }

        return null;
        
    }
}
