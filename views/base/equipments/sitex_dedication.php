<?php
use yii\grid\GridView;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $e \app\models\BaseEquipments */
/* @var $pe \app\models\ProjectEquipmentsView */
/* @var $pd \app\models\ProjectDedication */

/* @var $viewMode */ /* table widget */
$viewMode = Yii::$app->session->get('viewMode', 'table');

$qp = Yii::$app->request->queryParams;
$Url = $qp;
$Url[0] = '/base/sitex_dedication';

use yii\helpers\Html;

$this->title = 'PC|sitex.dedic';
$this->params['breadcrumbs'][] = ['label' => "تجهیزات", 'url' => ['base/equipments']];
$this->params['breadcrumbs'][] = ['label' => $e->equipment, 'url' => ['base/eq_projects?id='.$pe->id]];
$this->params['breadcrumbs'][] = ['label' => $pe->project_name, 'url' => ['base/pr_dedication?id='.$pd->id]];
$this->params['breadcrumbs'][] = "تخصیص به مرکز/سایت";
?>
<div class="project-sitex-ded">

    <table class="table table-striped table-bordered table-active text-center en-font">
        <tr><td><?= $e->equipment." | ".$e->description; ?></td></tr>
        <tr><td><?= $pe->project_name." | "."خرید تعداد ".$pe->quantity." عدد"; ?></td></tr>
        <tr><td><?= "تخصیص تعداد ".$pd->quantity." عدد"." | "." منطقه ".$pd->area; ?></td></tr>
    </table>

    <?php
    if($viewMode == "table")
    {
        $Url['viewMode'] = 'widget';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;float:right;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => $Url, "right" => "#"], 'enabled' => "right"]);

        // add new
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'onclick'=>"add_sd()"]);


        echo  GridView::widget([
            'options' => ["class" => "dir-rtl"],
            'tableOptions' => ['id' => "sd-table", 'class' => 'table table-striped table-bordered table-hover text-center'],
            'headerRowOptions' => ['class' => 'link-white bg-secondary text-white text-center'],
            'summary' => '{begin}-{end}/{totalCount}',
            'emptyText' => "داده‌ای یافت نشد.",
            'rowOptions' =>
                function ($model, $key, $index, $grid) {
                    return ['id' => 'row' . $model['id'],
                        'class' => 'table_row',
                        'onclick' => 'activateRow(this);',
                        'ondblclick' => '',
                        "oncontextmenu" => "event.preventDefault();"];
                },
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' =>
                [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => "ردیف",
                    ],
                    [
                        'attribute' => 'id',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'project_sitex_id',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'area',
                        'headerOptions' => ['class' => 'bg-secondary text-white text-center'],
                    ],
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['class' => 'bg-secondary text-center'],
                    ],
                    [
                        'attribute' => 'abbr',
                        'headerOptions' => ['class' => 'bg-secondary text-center'],
                    ],
                    [
                        'attribute' => 'quantity',
                        'headerOptions' => ['class' => 'bg-secondary text-center'],
                    ],
                    [
                        'attribute' => 'description',
                        'headerOptions' => ['class' => 'bg-secondary text-center'],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{equipments_edit}',
                        'header' => "ویرایش",
                        'buttons' => ['equipments_edit' => function ($url, $model, $key) {
                            return "<a onclick='widgetEdit(".$model->id.")'><i class='fa fa-edit text-info'></i></a>";
                        }]
                    ],
                ],

        ]);

    }
    else
    {
        $Url['viewMode'] = 'table';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;float:right;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => "#", "right" => $Url], 'enabled' => "left"]);
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'onclick'=>"add_sd()"]);
                $models = $dataProvider->getModels();
        ?>
        <div class="input-group m-1">
            <div class="input-group-prepend">
                <button class="form-control text-light bg-secondary" type="button" onclick="searchWidget();" ><i class='fa fa-filter'></i></button>
            </div>
            <input type="text" id="search-widget-input" class="form-control text-right dir-rtl en-font" placeholder="جستجو"" >
            <div class="input-group-append">
                    <select required class="text-right fa-font border-0 text-light bg-secondary font-weight-bold form-control " id="widget-search-select" onchange="$('#search-widget-input').val('');">
                        <option selected disabled >فیلد جستجو</option>
                        <option value="area"  >منطقه</option>
                        <option value="name"  >نام مرکز/سایت</option>
                        <option value="abbr"  >اختصار</option>
                        <option value="quantity"  >تعداد</option>
                        <option value="description"  >توضیحات</option>
                    </select>
            </div>
        </div>
<!--  filter items      -->
        <?= \app\components\FilterWidget::widget(['searchModel'=>$searchModel, "items"=>["area"=>"منطقه", "name"=>"نام مرکز/سایت", "abbr"=>"اختصار", "quantity"=>"تعداد", "description"=>"توضیحات"]]); ?>

        <div class="d-flex flex-wrap justify-content-start w-100 dir-rtl ">
            <?php
            if(sizeof($models) > 0)
            {
                foreach ($models as $model)
                {
                    //title : text
                    //body : key value
                    //footer: [
                    //          left=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
                    //          center=>[icon=>"", modality=>false,  jsFunc=>""       , link=>"a/s/f.php" ],
                    //          right=> [icon=>"", modality=> true,  jsFunc=>"add(1)" , link=>"" ]
                    //        ]
                    //options=>[class=>"", style=>""]

                    $title = $model->name;
                    $body = [];
                    $body['منطقه'] = $model->area;
                    $body['اختصار'] = $model->abbr;
                    $body['تعداد تخصیص'] = $model->quantity;
                    $body['توضیحات'] = $model->description;
                    $footer = [];
                    //edit
                    $icon="<i class='fa fa-edit text-info' title='ویرایش'></i>";
                    $jsFunc="widgetEdit(".$model->id.")";
                    $link="";
                    $footer['left'] = ['icon'=>$icon, 'modality'=>true, 'jsFunc'=>$jsFunc, 'link'=>$link];


                    echo \app\components\GeneralWidget::widget([ 'id' => $model->id, 'title'=>$title, 'body'=>$body, 'footer'=>$footer, 'options'=>[] ]);
                }
            }
            else
            {
                echo "<br /><h5 class='dir-rtl text-center w-100 font-weight-bold text-info'>داده‌ای یافت نشد.</h5>";
            }

            ?>
        </div>
        <br />
        <?php
        echo \yii\widgets\LinkPager::widget([
            'pagination'=>$pages,
            'nextPageLabel'=>'»',
            'prevPageLabel'=>'«',
            'maxButtonCount'=>5,
            'options'=>["class"=>"pagination dir-rtl"]
        ]);

    }
    ?>
</div>

<?php
echo \app\components\ModalWidget::widget(["id"=>"addSdModal", 'maxWidth'=>"600px", "title"=>"تخصیص جدید" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);
echo \app\components\ModalWidget::widget(["id"=>"editSdModal", 'maxWidth'=>"600px", "title"=>"ویرایش" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);

$bPath = Yii::$app->request->baseUrl;
$pdId = $pd->id;
$script =<<< JS

function activateRow(obj)
{
    $(".selectedRow").removeClass("selectedRow");
    $(obj).addClass("selectedRow");
}

function searchWidget()
{
    var selectValue = $("#widget-search-select").val();
    var searchField = $("#search-widget-input").val();
    if(selectValue == null) return;
    
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set("ProjectSitexDedicationViewSearch["+selectValue+"]", searchField);
    
    window.location.search = urlParams;
}

function filterClose(obj)
{
    var item = $(obj).attr("filter-item");
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete("ProjectSitexDedicationViewSearch["+item+"]");
    window.location.search = urlParams;
}


function add_sd()
{
    var pdid = $pdId;
        $("#addSdModal .modal-dialog .modal-body").innerHTML="";
          $.ajax(
              {
              url: "$bPath/base/add_sd_form", 
              type:"POST",
              data:{'pdid':pdid},
              success: function(info)
                  {
                      $("#addSdModal .modal-dialog .modal-body").html(info);
                  }
              }
        );
  
  $("#addSdModal").modal("show"); 
}

function widgetEdit(id)
{
        $("#editSdModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/base/edit_sd_form", 
      type:"POST",
      data:{'id':id},
      success: function(info)
          {
              $("#editSdModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#editSdModal").modal("show"); 
}




JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>