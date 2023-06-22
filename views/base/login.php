<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Alert;
use yii\captcha\Captcha;

$this->title = 'PC | Login';
?>
<div class="login-cover overflow-auto ">
    <?php
    //display success message
    if (Yii::$app->session->hasFlash('success'))
        echo Alert::widget(['options' => ['class' => 'alert-success text-right fixed-top w-75 mx-auto',],'body' => Yii::$app->session->getFlash('success')]);
    // display error message
    if (Yii::$app->session->hasFlash('error'))
        echo Alert::widget(['options' => [ 'class' => 'alert-danger text-right fixed-top w-75 mx-auto',],'body' => Yii::$app->session->getFlash('error')]);
    ?>

    <div class="d-md-flex flex-column align-items-center justify-content-center w-100 h-100">
<!--        <h1 class="d-block p-3 text-center text-white bg-secondary font-weight-bold m-1 w-100 max-width-400px rounded">سامانه کنترل پروژه</h1>-->

        <div class="card border bg-white-transparent border-secondary m-1 w-100 max-width-400px rounded">

            <h4 class="card-header p-3 text-center text-white bg-secondary font-weight-bold">سامانه کنترل پروژه طرح و مهندسی</h4>
            <h3 class="card-header p-3 text-center text-muted  font-weight-bold">ورود به سامانه</h3>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'method'=>'POST',
                'options'=>["class"=>"card-body p-3 w-100"],
                'layout' => 'horizontal',
                'fieldConfig' =>
                    [
                        'template' => "{label}\n{input}",//\n{error}
                    ],
            ]); ?>
            <?= $form->field($model, 'natid',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->textInput(['autofocus' => true, 'class'=>"col-md-9 form-control text-left en-font"]); ?>

            <?= $form->field($model, 'auth_key', ['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->passwordInput(['class'=>"col-md-9 form-control text-left  en-font"]); ?>

            <?= $form->field($model, 'verifyCode', ['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->widget(Captcha::className(), [
                'options'=>['class'=>"h-100 w-100 form-control en-font text-left"],
                'template' => '  <div class="col-md-9 p-0">
                                        <div class="row w-100 p-0 m-0">
                                            <div class="col-md-5 p-0 m-0">{image}</div>
                                            <div class="col-md-7 p-0 m-0 ">{input}</div>
                                         </div>
                                      </div>
                               ',
            ]); ?>

            <div class="form-group">
                <?= Html::submitButton('ورود', ['class' => 'btn btn-primary w-25 font-20px', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

            <?= Html::img('@web/web/images/logo.png', ['alt' => 'PC logo', 'class'=>"d-block mx-auto w-50px "]); ?>
            <h6 class="text-center">شرکت مخابرات ایران</h6>
            <?php if(!empty($lastLogin)){ ?>
                <div class="card-footer bg-white-transparent">
                    <h6 class="text-right"><?= $lastLogin; ?></h6>
                </div>
            <?php } ?>
        </div>

    </div>
</div>