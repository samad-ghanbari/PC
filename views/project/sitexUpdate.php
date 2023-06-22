<?php
use yii\bootstrap4\ActiveForm;
/* @var $this yii\web\View */
/* @var $sitex \app\models\BaseSitex */
/* @var $psid  */
/* @var $areas  */
/* @var $centers  */

use yii\helpers\Html;
$centerBool = false;
if($sitex->type == "مرکز")
    $centerBool = true;

$this->title = 'PC|Sitex|update';
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['project/index?id='.$project->id]];
$this->params['breadcrumbs'][] = ['label' => "جزيیات پروژه", 'url' => ['project/details?id='.$project->id]];
$this->params['breadcrumbs'][] = ['label' => "پارامترها", 'url' => ['project/sitex_tasks?id='.$psid]];
$this->params['breadcrumbs'][] = "ویرایش مرکز/سایت";

?>
<div class="project-sitex-update">

        <p class="hr-text"><span><?= 'ویرایش ' . $sitex->type .' '. $sitex->name; ?></span></p>

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-500px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'sitex-update',
            'method'=>'POST',
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]); ?>
        <?= $form->field($sitex, 'id')->hiddenInput()->label(false); ?>
        <?= $form->field($sitex, 'area',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->dropdownList($areas,['autofocus' => true, 'id'=>'areaCB', 'onchange'=>"areaChanged(this)", 'class'=>"col-md-9 form-control text-left en-font"]); ?>
        <?= $form->field($sitex, 'name',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->textInput(['autofocus' => true, 'class'=>"col-md-9 form-control text-right en-font"]); ?>
        <?= $form->field($sitex, 'abbr',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->textInput(['autofocus' => true, 'class'=>"col-md-9 form-control text-left en-font"]); ?>
        <?= $form->field($sitex, 'type',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->dropdownList(['مرکز'=>'مرکز', 'سایت'=>'سایت'],['autofocus' => true, 'onchange'=>"typeChanged(this)", 'id'=>'typeCB', 'class'=>"col-md-9 form-control text-right en-font"]); ?>
        <?= $form->field($sitex, 'center_id',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->dropdownList($centers[$sitex->area],['autofocus' => true, 'id'=>'centerCB', 'disabled'=>$centerBool, 'class'=>"col-md-9 form-control text-right en-font"]); ?>
        <?= $form->field($sitex, 'address',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-3   col-form-label text-right "]])->textarea(['rows'=>5, 'autofocus' => true, 'class'=>"col-md-9 form-control text-right en-font"]); ?>
        <br />
        <div class="form-group">
            <?= Html::submitButton('تایید ویرایش', ['class' => 'btn btn-success w-100px font-20px', 'name' => 'sitex-update-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br />
</div>

<?php
$bPath = Yii::$app->request->baseUrl;
$centersJson = json_encode($centers);
$script =<<< JS

var centersJson = $centersJson;
function areaChanged(obj)
{
    var area = $(obj).val();
    area = parseInt(area);
    $("#typeCB").val("مرکز");
    $("#centerCB").empty();
    $("#centerCB").prop("disabled", true);
    var centers = centersJson[area];
    for (var i in centers)
            $("#centerCB").append(new Option(centers[i], i));
    
    $("#centerCB").val(-1);
}

function typeChanged(obj)
{
    var type = $(obj).val();
    if(type == "مرکز")
        {
            $("#centerCB").val(-1);
            $("#centerCB").prop("disabled", true);
        }
    else
        {
            $("#centerCB").prop("disabled", false);
        }
        
}


JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>