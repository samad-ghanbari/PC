<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $psitex \app\models\ProjectSitexView */
/* @var $e \app\models\BaseEquipments */
/* @var $pe \app\models\ProjectEquipments */
/* @var $pd \app\models\ProjectDedication */
/* @var $sd \app\models\ProjectSitexDedication */
?>
<div class="project-sitex-update-sd">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'sitex-update-phase',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/project/psitex_update_sd",
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
        <hr />
        <div class="row m-2">
            <div class="col col-md-4">تجهیز</div>
            <div class="col col-md-8  en-font"><?= $e->equipment." - ".$e->description; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">تعداد خرید پروژه</div>
            <div class="col col-md-8  en-font"><?= $pe->quantity." قلم ".$pe->description; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">تعداد تخصیص منطقه</div>
            <div class="col col-md-8 en-font"><?= $pd->quantity." قلم ".$pd->description; ?></div>
        </div>


        <?= $form->field($sd, 'id')->hiddenInput(['name'=>"sd-id"])->label(false); ?>
        <?= $form->field($psitex, 'id')->hiddenInput(['name'=>"psid"])->label(false); ?>
        <?= $form->field($sd, 'quantity',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4  text-right col-form-label  "]])
            ->textInput(['autofocus' => true, 'type'=>'number', 'min'=>0, 'name'=>'sd-quantity', 'class'=>"col-md-8 form-control text-right en-font"])->label("تعداد تخصیص مرکز/سایت"); ?>

        <?= $form->field($sd, 'description',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4  text-right col-form-label  "]])
            ->textInput(['autofocus' => true, 'name'=>'sd-desc', 'class'=>"col-md-8 form-control text-right en-font"]); ?>
        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'sitex-update-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>