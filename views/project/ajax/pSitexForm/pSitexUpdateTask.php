<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $psitex \app\models\ProjectSitexView */
/* @var $task \app\models\ProjectTasks */
/* @var $pSitex_task  \app\models\ProjectSitexTasks */
/* @var $options  [      options=>[ op_id=>option, ... ]  ,   info=>[ op_id=>[done, default ] , ...]       ] */

?>
    <div class="project-sitex-update-task">

        <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
            <?php $form = ActiveForm::begin([
                'id' => 'sitex-update-phase',
                'method'=>'POST',
                'action'=>Yii::$app->request->baseUrl."/project/psitex_update_task",
                'options'=>["class"=>"p-3 w-100"],
                'layout' => 'horizontal',
                'fieldConfig' =>
                    [
                        'template' => "{label}\n{input}",//\n{error}
                    ],
            ]); ?>
            <div class="row m-2">
                <div class="col col-md-4">منطقه</div>
                <div class="col col-md-8"><?= $pSitex->area; ?></div>
            </div>
            <div class="row m-2">
                <div class="col col-md-4">نام</div>
                <div class="col col-md-8"><?= $pSitex->name; ?></div>
            </div>

            <?php if($pSitex->type == 'سایت'){ ?>
                <div class="row m-2 ">
                    <div class="col col-md-4">مرکز اصلی</div>
                    <div class="col col-md-8"><?= $pSitex->center_name; ?></div>
                </div>
            <?php } ?>

            <?= $form->field($pSitex_task, 'id',['options'=>['class'=>"d-none"]])->hiddenInput(['name'=>"psitex-task-id"])->label(false); ?>
            <?= $form->field($pSitex_task, 'project_sitex_id', ['options'=>['class'=>"d-none"]])->hiddenInput(['name'=>"psitex-id"])->label(false); ?>
            <?= $form->field($pSitex_task, 'task_id',['options'=>['class'=>"d-none"]])->hiddenInput(['name'=>"task-id"])->label(false); ?>

            <?php if($task->type == "select")
            {
                echo $form->field($pSitex_task, 'option_id', ['options' => ['class' => "row m-2"], 'labelOptions' => ['class' => "col-md-4   col-form-label text-right "]])->dropdownList($options['options'], ['autofocus' => true, 'name'=>"option_id", 'class' => "col-md-8 form-control text-right en-font"])->label($task->task);
            }
            else if($task->type == "text")
            {
                echo $form->field($pSitex_task, 'value', ['options' => ['class' => "row m-2"], 'labelOptions' => ['class' => "col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true,  'name'=>"task-value", 'class' => "col-md-8 form-control text-right en-font"])->label($task->task);
            }
            else if($task->type == "number")
            {
                echo $form->field($pSitex_task, 'value', ['options' => ['class' => "row m-2"], 'labelOptions' => ['class' => "col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'type'=>'number', 'min'=>0, 'name'=>"task-value", 'class' => "col-md-8  form-control text-right en-font"])->label($task->task);
            }
            else if($task->type == "date")
            {
                if(intval($pSitex_task->value) > 0)
                {
                    //convert ts to gerigorian
                    $pSitex_task->value  = gmdate("Y/m/d", intval($pSitex_task->value));
                }

                echo $form->field($pSitex_task, 'value', ['options' => ['class' => "row m-2"], 'labelOptions' => ['class' => "col-md-4  text-right "]])
                    ->widget(
                                mrlco\datepicker\Datepicker::className(),
                                [
                                        'options'=>['class'=>"col-md-8 max-width-200px text-left ", 'name'=>"task-value"],
                                        'clientOptions' => ['format' => 'YYYY/MM/DD']
                                ]
                             )->label($task->task);
            } ?>
            <hr />
            <div class="form-group">
                <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'sitex-update-btn']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

        <br class="clearfix" />
    </div>