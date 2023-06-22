<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\BaseEquipments */

?>
<div class="base-equip-add">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'base-equip-add',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/base/add_equipment",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>

        <?= $form->field($model, 'equipment',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'class'=>"col-md-8 form-control text-right en-font"]); ?>
        <?= $form->field($model, 'description',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textarea(['rows'=>2, 'autofocus' => true, 'class'=>"col-md-8 form-control text-right dir-rtl"]); ?>

        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'equip-add-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>