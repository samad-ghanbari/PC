<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\ProjectSitexDedication */
/* @var $pe \app\models\ProjectEquipmentsView */
/* @var $pd \app\models\ProjectDedication */

?>
<div class="prj-sitex-ded-edit">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'prj-sd-edit',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/base/edit_sd",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>

        <div class="row m-2">
            <div class="col col-md-4">تجهیز</div>
            <div class="col col-md-8 en-font"><?= $pe->equipment; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">پروژه</div>
            <div class="col col-md-8 en-font"><?= $pe->project_name; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">تخصیص</div>
            <div class="col col-md-8 en-font"><?= $pd->quantity." عدد به منطقه ".$pd->area; ?></div>
        </div>

        <?= $form->field($model, 'id')->hiddenInput(['name'=>'id'])->label(false); ?>
        <?= $form->field($model, 'project_sitex_id',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->dropdownList($sitex, ['autofocus' => true, 'name'=>'psitex_id', 'class'=>"col-md-8 form-control text-right "]); ?>
        <?= $form->field($model, 'quantity',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'type'=>'number', 'name'=>'quantity', 'required'=>true, 'class'=>"col-md-8 form-control text-right en-font"]); ?>
        <?= $form->field($model, 'description',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textarea(['rows'=>2, 'autofocus' => true, 'name'=>'description', 'class'=>"col-md-8 form-control text-right dir-rtl"]); ?>


        <hr />
        <div class="form-group">
            <?= Html::submitButton('ویرایش', ['class' => 'btn btn-success w-100px font-20px float-left', 'value'=>'edit', 'name' => 'btn']) ?>
            <?= Html::submitButton('حذف', ['class' => 'btn btn-danger w-100px font-20px float-right', 'value'=>'remove', 'name' => 'btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>