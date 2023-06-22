<?php
use yii\grid\GridView;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider; */
/* @var $searchModel \app\models\ProjectSitexViewSearch */
/* @var $viewMode */ /* table widget */

Yii::$app->formatter->nullDisplay = "";
$projectWeight = $project->project_weight;
$viewMode = Yii::$app->session->get('viewMode', 'table');
$qp = Yii::$app->request->queryParams;
$Url = $qp;
$Url[0] = '/project/details';
$Url['id'] = $project->id;

use yii\helpers\Html;

$this->title = 'PC|Project|Detail';
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['project/index?id='.$project->id]];
$this->params['breadcrumbs'][] = "جزییات پروژه";

?>

<div class="project-detail">

    <?php
    if($viewMode == "table")
    {
        $Url['viewMode'] = 'widget';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;height:40px;margin:5px;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => $Url, "right" => "#"], 'enabled' => "right"]);

        echo GridView::widget([
        'options'=>["class"=>"dir-rtl  overflow-x-scroll"],
        'tableOptions'=>['id'=>"projectsTable", 'class'=>'table table-striped table-bordered table-hover text-center min-width-1000px'],
        'headerRowOptions'=>['class'=>'link-white bg-secondary text-white text-center'],
        'summary'=>'{begin}-{end}/{totalCount}',
        'emptyText'=>"داده‌ای یافت نشد.",
        'rowOptions' =>
            function ($model, $key, $index, $grid)
            {
                return [ 'id'=>'row'.$model['id'],
                    'class'=>'table_row',
                    'onclick'=>'activateRow(this);',
                    'ondblclick'=>'',
                    "oncontextmenu" =>"event.preventDefault();"];
            },
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' =>
            [
                [
                    'attribute' =>'id',
                    'visible'=>false,
                ],
                [
                    'attribute' =>'project_id',
                    'visible'=>false,
                ],
                [
                    'attribute' =>'sitex_id',
                    'visible'=>false,
                ],
                [
                    'attribute' =>'area',
                    'headerOptions' => ['class' => 'bg-secondary text-white text-center'],
                    'filter' => ['2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8'],
                ],
                [
                    'attribute' =>'name',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'abbr',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'center_id',
                    'visible'=>false,
                ],
                [
                    'attribute' =>'center_name',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'center_abbr',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'address',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'done',
                    'value'=> function($model){ if($model->done) return "<i class='fa fa-check text-success'></i>"; else return "<i class='fa fa-times text-danger'></i>";},
                    'format'    => 'html',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                    'filterInputOptions' => ['class' => 'd-none'],
                ],
                [
                    'attribute' =>'weight',
                    'value'=> function($model) use($projectWeight){ return \app\components\ProgressWidget::widget(["width"=>"80px", "height"=>"10px", "percentage"=>round(100*$model->weight/$projectWeight, 1)]); },
                    'format'    => 'html',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                    'filterInputOptions' => ['class' => 'd-none'],
                ],
                [
                    'attribute' =>'phase',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{project_info}',
                    'header'=>"اطلاعات سایت/مرکز",
                    'buttons' => ['project_info' => function($url, $model, $key){ return "<a href='#' onclick=\"getInfo($model->id)\"><i class='fa fa-info-circle text-info'></i></a>";}],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{project_detail}',
                    'header'=>"پارامترها",
                    'buttons' => ['project_detail' => function($url, $model, $key){ return "<a href=\"$url\"><i class='fa fa-rectangle-list text-info'></i></a>";}],
                    'urlCreator' => function ($action, $model, $key, $index)
                        {
                                $url = Yii::$app->request->baseUrl.'/project/sitex_tasks?id='.$model->id;
                                return $url;
                        }
                ],
            ],

    ]);
    }else
        {

        $Url['viewMode'] = 'table';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;height:40px;margin:5px;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => "#", "right" => $Url], 'enabled' => "left"]);

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
                        <option value="center_name"  >مرکز اصلی</option>
                        <option value="center_abbr"  >اختصار مرکز اصلی</option>
                        <option value="address" >آدرس</option>
                        <option value="phase"  >فاز</option>
                    </select>
            </div>
        </div>
<!--  filter items      -->
        <?= \app\components\FilterWidget::widget(['searchModel'=>$searchModel, "items"=>["area"=>"منطقه", "name"=>"نام مرکز/سایت", "abbr"=>"اختصار", "center_name"=>"مرکز اصلی",  "center_abbr"=>"اختصار مرکز اصلی",  "address"=>"آدرس", "phase"=>"فاز"] ]); ?>

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

                        $title = ($model->center_id > 0)? "منطقه".$model->center_area ." ".$model->center_name : "منطقه".$model->area ." - ".$model->name;
                        $body = [];
                        $body['اختصار'] = $model->abbr;
                        if(!empty($model->center_name))
                            {
                                $body['مرکز اصلی'] = $model->center_name;
                                $body['اختصار مرکز اصلی'] = $model->center_abbr;
                            }

                        $body['آدرس'] = $model->address;
                        $body['اتمام کار'] = ($model->done)? "<i class='fa fa-check text-success'></i>" : "<i class='fa fa-times text-danger'></i>";
                        $body['فاز'] = $model->phase;
                        $body['پیشرفت'] = \app\components\ProgressWidget::widget(["width"=>"100%", "height"=>"20px", "percentage"=>round(100*$model->weight/$projectWeight, 1)]);

                        $footer = [];
                        //info
                        $icon="<i class='fa fa-info-circle text-info' title='ویرایش'></i>";
                        $jsFunc="getInfo(".$model->id.")";
                        $link="";
                        $footer['left'] = ['icon'=>$icon, 'modality'=>true, 'jsFunc'=>$jsFunc, 'link'=>$link];

                        $icon="<i class='fa fa-rectangle-list text-info' title='انتخاب'></i>";
                        $link = Yii::$app->request->baseUrl.'/project/sitex_tasks?id='.$model->id;
                        $footer['right'] = ['icon'=>$icon, 'modality'=>false, 'jsFunc'=>"", 'link'=>$link];

                        echo \app\components\GeneralWidget::widget([ 'id' => $model->id, 'title'=>$title, 'body'=>$body, 'footer'=>$footer, 'options'=>[] ]);
                    }
                        //echo \app\components\ProjectDetailWidget::widget(['model' => $model, 'projectWeight'=>$projectWeight]);
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

        ?>
    <?php } ?>
</div>

<?php
//$id, $maxWidth, $title, $body, $buttonName, $buttonType
echo \app\components\ModalWidget::widget(["id"=>"sitexMetaModal", 'maxWidth'=>"600px", "title"=>"اطلاعات مرکز/سایت" , "body"=>"", "buttonName"=>"تایید", "buttonType"=>"btn-info"]);

$bPath = Yii::$app->request->baseUrl;
$script =<<< JS

function activateRow(obj)
{
    $(".selectedRow").removeClass("selectedRow");
    $(obj).addClass("selectedRow");
}

function getInfo(id)
{
    $("#sitexMetaModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/project/sitex_ajax_info", 
      type:"POST",
      data:{'id':id},
      success: function(info)
          {
              $("#sitexMetaModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#sitexMetaModal").modal("show");   
}

function searchWidget()
{
    var selectValue = $("#widget-search-select").val();
    // ProjectSitexViewSearch: area center site done phase address
    var searchField = $("#search-widget-input").val();
    if(selectValue == null) return;
    
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set("ProjectSitexViewSearch["+selectValue+"]", searchField);
    
    window.location.search = urlParams;

}

function filterClose(obj)
{
    var item = $(obj).attr("filter-item");
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete("ProjectSitexViewSearch["+item+"]");
    window.location.search = urlParams;
}

JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>