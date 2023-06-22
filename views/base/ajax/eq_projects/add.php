<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\ProjectEquipmentsView */

?>
<div class="prj-equip-add">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'base-equip-add',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/base/add_pe",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>
        <div class="row m-2">
            <div class="col col-md-4">تجهیز</div>
            <div class="col col-md-8 en-font"><?= $equipment->equipment; ?></div>
        </div>
        <div class="row m-2">
            <div class="col col-md-4">توضیحات تجهیز</div>
            <div class="col col-md-8 en-font"><?= $equipment->description; ?></div>
        </div>

        <?= $form->field($model, 'equipment_id')->hiddenInput(['name'=>'equipment_id'])->label(false); ?>
        <?= $form->field($model, 'project_id',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->dropdownList($projects, ['autofocus' => true, 'name'=>'project_id', 'class'=>"col-md-8 form-control text-right "]); ?>
        <?= $form->field($model, 'quantity',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'type'=>'number', 'name'=>'quantity', 'required'=>true, 'class'=>"col-md-8 form-control text-right en-font"]); ?>
        <?= $form->field($model, 'description',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textarea(['rows'=>2, 'autofocus' => true, 'name'=>'description', 'class'=>"col-md-8 form-control text-right dir-rtl"]); ?>

        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'equip-add-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>