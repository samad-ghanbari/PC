<?php
use yii\grid\GridView;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $viewMode */ /* table widget */
$viewMode = Yii::$app->session->get('viewMode', 'table');

$qp = Yii::$app->request->queryParams;
$Url = $qp;
$Url[0] = '/base/equipments';

use yii\helpers\Html;

$this->title = 'PC|Equipments';
$this->params['breadcrumbs'][] = "تجهیزات";
?>
<div class="base-equipments">

    <?php
    if($viewMode == "table")
    {
        $Url['viewMode'] = 'widget';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;float:right;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => $Url, "right" => "#"], 'enabled' => "right"]);

        // add new
//        echo Html::a('<i class="fa fa-plus"></i>', ['base/add_equipment', 'id' => $id], ['class' => 'profile-link']);
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'onclick'=>"add_equipment()"]);


        echo  GridView::widget([
            'options' => ["class" => "dir-rtl"],
            'tableOptions' => ['id' => "projectsTable", 'class' => 'table table-striped table-bordered table-hover text-center'],
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
                        'attribute' => 'equipment',
                        'headerOptions' => ['class' => 'bg-secondary text-white text-center'],
                        'contentOptions'=>['class'=>"en-font"],
                    ],
                    [
                        'attribute' => 'description',
                        'headerOptions' => ['class' => 'bg-secondary text-center'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{project_equipments}',
                        'header' => "انتخاب",
                        'buttons' => ['project_equipments' => function ($url, $model, $key) {
                            return "<a href=\"$url\"><i class='fa fa-rectangle-list text-info'></i></a>";
                        }],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            $url = Yii::$app->request->baseUrl . '/base/eq_projects?id=' . $model->id;
                            return $url;
                        }
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
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'onclick'=>"add_equipment()"]);
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
                        <option value="equipment"  >تجهیز</option>
                        <option value="description"  >توضیحات</option>
                    </select>
            </div>
        </div>
<!--  filter items      -->
        <?= \app\components\FilterWidget::widget(['searchModel'=>$searchModel, "items"=>["equipment"=>"تجهیز", "description"=>"توضیحات"]]); ?>

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

                    $title = $model->equipment;
                    $body = [];
                    $body['توضیحات'] = $model->description;
                    $footer = [];
                    //edit
                    $icon="<i class='fa fa-edit text-info' title='ویرایش'></i>";
                    $jsFunc="widgetEdit(".$model->id.")";
                    $link="";
                    $footer['left'] = ['icon'=>$icon, 'modality'=>true, 'jsFunc'=>$jsFunc, 'link'=>$link];

                    $icon="<i class='fa fa-rectangle-list text-info' title='انتخاب'></i>";
                    $link = Yii::$app->request->baseUrl."/base/eq_projects?id=".$model->id;
                    $footer['right'] = ['icon'=>$icon, 'modality'=>false, 'jsFunc'=>"", 'link'=>$link];

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
echo \app\components\ModalWidget::widget(["id"=>"addEquipModal", 'maxWidth'=>"600px", "title"=>"افزودن تجهیز جدید" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);
echo \app\components\ModalWidget::widget(["id"=>"editEquipModal", 'maxWidth'=>"600px", "title"=>"ویرایش تجهیز" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);

$bPath = Yii::$app->request->baseUrl;
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
    urlParams.set("BaseEquipmentsSearch["+selectValue+"]", searchField);
    
    window.location.search = urlParams;
}

function filterClose(obj)
{
    var item = $(obj).attr("filter-item");
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete("BaseEquipmentsSearch["+item+"]");
    window.location.search = urlParams;
}


function add_equipment()
{
        $("#addEquipModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/base/add_equipment_form", 
      type:"POST",
      data:{},
      success: function(info)
          {
              $("#addEquipModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#addEquipModal").modal("show"); 
}

function widgetEdit(id)
{
        $("#editEquipModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/base/edit_equipment_form", 
      type:"POST",
      data:{'id':id},
      success: function(info)
          {
              $("#editEquipModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#editEquipModal").modal("show"); 
}




JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>