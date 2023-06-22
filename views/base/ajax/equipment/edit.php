<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\BaseEquipments */

?>
<div class="base-equip-edit">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'base-equip-edit',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/base/edit_equipment",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>

        <?= $form->field($model, 'id')->hiddenInput(['name'=>'id'])->label(false); ?>
        <?= $form->field($model, 'equipment',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'name'=>'equipment', 'class'=>"col-md-8 form-control text-right en-font"]); ?>
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