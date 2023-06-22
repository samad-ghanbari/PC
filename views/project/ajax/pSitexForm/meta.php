<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $sitex \app\models\BaseSitex */
/* @var $psitex \app\models\ProjectSitex */
/* @var $sitexMeta \app\models\ProjectSitexMeta */
?>
<div class="project-sitex-update-meta">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-500px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'sitex-update-phase',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/project/psitex_update_meta",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>
        <div class="row m-2">
            <div class="col col-md-4">منطقه</div>
            <div class="col col-md-8"><?= $psitex->area; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">نام</div>
            <div class="col col-md-8"><?= $psitex->name; ?></div>
        </div>

        <?php if($psitex->type == 'سایت'){ ?>
            <div class="row m-2 ">
                <div class="col col-md-4">مرکز اصلی</div>
                <div class="col col-md-8"><?= $psitex->center_name; ?></div>
            </div>
        <?php } ?>

        <?= $form->field($sitexMeta, 'id')->hiddenInput(['name'=>"meta-id"])->label(false); ?>
        <?= $form->field($psitex, 'id')->hiddenInput(['name'=>"psid"])->label(false); ?>
        <?= $form->field($sitexMeta, 'value',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4  text-right col-form-label  "]])
            ->textInput(['autofocus' => true, 'name'=>'meta-value', 'class'=>"col-md-8 form-control text-right en-font"])->label($sitexMeta->key); ?>
        <br />
        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-25 font-20px float-left', 'name' => 'sitex-update-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>