<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Login';
?>
<?php
$this->registerCss("
body {
    background-image: linear-gradient(to right, #4776E6 0%, #8E54E9 51%, #4776E6 100%);
    background-image: url('../web/resources/images/bg3.jpg');
    background-position: 25% 65%;
    /* background-position: right center, left top;
     */
    background-repeat: no-repeat, repeat;
    background-size: cover; 
    height:100vh; 
    position: relative;
    opacity: 0.9;
}
");
?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 imgalfil" style="">
                <center><img src="resources/images/logo DMM.ico" class="img-responsive"
                        style="height:130px;width:auto;"><label class="font-login">SMO Tools</label>
                </center>
            </div>
            <div class="col-lg-6 p-5 border-login back-body">
                <h1 class="text-light">
                    Iniciar Sesión
                </h1>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true ,'class'=>'form-control text-light form-login'])->label('Usuario'); ?>

                <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control text-light form-login'])->label('Contraseña'); ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-12 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",
    ]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row text-center"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])->label(false) ?>
                <?php
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access" viewBox="0 0 16 16">
                <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM6 5.5l-4.535-.442A.531.531 0 0 1 1.531 4H14.47a.531.531 0 0 1 .066 1.058L10 5.5V9l.452 6.42a.535.535 0 0 1-1.053.174L8.243 9.97c-.064-.252-.422-.252-.486 0l-1.156 5.624a.535.535 0 0 1-1.053-.174L6 9V5.5Z"/>
              </svg>';
                ?>
                <div class="form-group">
                    <div class="col-lg-12 d-grid">
                        <?= Html::submitButton('Login'.$icon, ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>