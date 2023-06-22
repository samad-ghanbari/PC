<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $project \app\models\BaseProjects */
/* @var $meta array */


?>
<div class="base-pr-add">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'base-pr-add',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/base/add_project",
            'options'=>["class"=>"p-3 w-100"],
            'layout' => 'horizontal',
            'fieldConfig' =>
                [
                    'template' => "{label}\n{input}",//\n{error}
                ],
        ]);

        echo "<div id='project-item-div' style='padding:0; margin:0;width:100%;'>";
            echo $form->field($project, 'project_name',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'name'=>'project_name', 'class'=>"col-md-8 form-control text-right en-font"]);
            echo $form->field($project, 'office',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput([ 'name'=>'office', 'class'=>"col-md-8 form-control text-right dir-rtl"]);
            echo $form->field($project, 'enabled',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->dropdownList([true=>'فعال', false =>"غیرفعال" ],[  'name'=>'enabled', 'class'=>"col-md-8 form-control text-right dir-rtl"]);
            echo $form->field($project, 'visible',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->dropdownList([true=>'قابل مشاهده', false =>"غیرقابل مشاهده" ],[ 'name'=>'visible', 'class'=>"col-md-8 form-control text-right dir-rtl"]);
            echo $form->field($project, 'project_weight',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['type'=>"number", 'name'=>'project_weight', 'class'=>"col-md-8 form-control text-right dir-rtl"]);
            echo "<hr />";

            echo "<input id='max-meta-number' type='hidden' name='max-meta-number' value='0' >";
            echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-primary', 'style'=>'width:40px;height:40px;padding:10px;margin:auto;display:block;', 'title'=>"افزودن ویژگی جدید", 'onclick'=>"addMeta()"]);
        echo "</div>"; ?>

        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'add-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>
<?php
$meta = json_encode($meta);
$jss =<<< JS
var meta = $meta;
var datalist = "";
for(var m in meta)
    {
       datalist = datalist + "<option>"+meta[m]+"</option>"; 
    }
function addMeta()
{
var maxMetaNumber = $("#max-meta-number").val();
maxMetaNumber = parseInt(maxMetaNumber);
maxMetaNumber = maxMetaNumber + 1;
$("#max-meta-number").val(maxMetaNumber);
var fieldHtml = "<div class='row m-2'>" +
    "<input list='k"+maxMetaNumber+"' name='k"+maxMetaNumber+"' class='col col-md-4  form-control text-right dir-rtl'>" +
    "<datalist id='k"+maxMetaNumber+"' >"+
    datalist +
    "</datalist>"+
    "<input name='"+"v"+maxMetaNumber+"' type='text' class='col-md-7 form-control text-right dir-rtl' >" +
    "<button type='button' mmn='"+maxMetaNumber+"' class='col-md-1 btn btn-danger p-10px' style='width:40px; height:40px;' onclick='removeMeta(this);' ><i class='fa fa-times'></i></button>" +
    "</div>";
$("#project-item-div").append(fieldHtml);
}

function removeMeta(obj)
{
    var mmn = $(obj).attr('mmn');
    var objTitle = $("[name='k"+mmn+"']");
    $(objTitle).val('');
    var objValue = $("[name='v"+mmn+"']");
    $(objValue).val('');
    
    var div = $(obj).parent();
    $(div).hide();    
}

JS;
$this->registerJs($jss, Yii\web\View::POS_END);
?>

