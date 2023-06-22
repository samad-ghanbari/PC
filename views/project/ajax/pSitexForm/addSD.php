<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $psitex \app\models\ProjectSitexView */
/* @var $pe  */
/* @var $pe_desc  */
/* @var $pd  */
/* @var $pd_desc  */
/* @var $sd \app\models\ProjectSitexDedication */
?>
<div class="project-sitex-add-sd">

    <div class="d-md-flex flex-column align-items-center dir-rtl justify-content-center w-100 h-100 max-width-1000px mx-auto">
        <?php $form = ActiveForm::begin([
            'id' => 'sitex-update-phase',
            'method'=>'POST',
            'action'=>Yii::$app->request->baseUrl."/project/psitex_add_sd",
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

        <?= $form->field($sd, 'project_sitex_id')->hiddenInput(['name'=>"psitex-id"])->label(false); ?>

        <div class="row m-2">
            <label for="peCB" class="col-md-4  text-right col-form-label  ">تجهیز</label>
            <div class="col col-md-8 p-0">
                <?= Html::dropDownList("peCB",null, $pe,['id'=>'peCB', 'onchange'=>"peChanged(this)", 'required'=>true, 'class'=>"w-100 form-control en-font"]); ?>
                <h5 class="w-100 m-2 text-right text-secondary dir-rtl en-font" id="pe-desc"></h5>
            </div>
        </div>

        <div class="row m-2">
            <label for="pdCB" class="col-md-4  text-right col-form-label  ">تخصیص به منطقه</label>
            <div class="col col-md-8 p-0">
                <?= Html::dropDownList("pdCB",null, [],['id'=>'pdCB', 'onchange'=>"pdChanged(this)", 'required'=>true, 'class'=>"w-100 form-control en-font"]); ?>
                <h5 class="w-100 m-2 text-right text-secondary dir-rtl en-font" id="pd-desc"></h5>
            </div>
        </div>

        <?= $form->field($sd, 'quantity',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textInput(['autofocus' => true, 'name'=>'sd-quantity', 'type'=>'number', 'min'=>1, 'class'=>"col-md-8 form-control text-right en-font"]); ?>
        <?= $form->field($sd, 'description',['options'=>['class'=>"row m-2"], 'labelOptions'=>['class'=>"col-md-4   col-form-label text-right "]])->textarea(['rows'=>1, 'autofocus' => true, 'name'=>'sd-desc', 'class'=>"col-md-8 form-control text-right en-font"]); ?>

        <hr />
        <div class="form-group">
            <?= Html::submitButton('تایید', ['class' => 'btn btn-success w-100px font-20px float-left', 'name' => 'sitex-update-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <br class="clearfix" />
</div>

<?php
$peDesc = json_encode($pe_desc);
$pdDesc = json_encode($pd_desc);
$pdJson = json_encode($pd);

$script =<<< JS
var peDesc = $peDesc;
var pdDesc = $pdDesc;
var pdJson = $pdJson;

$("#pe-desc").text(null);
$("#pd-desc").text(null);
$("#peCB").val(-1);
$("#pdCB").val(-1);

function peChanged(obj)
{
        $("#pe-desc").text(null);
        $("#pd-desc").text(null);
        var val = $(obj).val();
        var desc = peDesc[val];
        $("#pe-desc").text(desc);
        // fill pd
        $("#pdCB").empty();
        var items = pdJson[val];
        for (var i in items)
            $("#pdCB").append(new Option(items[i], i));
    
        $("#pdCB").val(-1);
}
function pdChanged(obj)
{
        $("#pd-desc").text(null);
        var val = $(obj).val();
        var desc = pdDesc[val];
        $("#pd-desc").text(desc);
}
JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>