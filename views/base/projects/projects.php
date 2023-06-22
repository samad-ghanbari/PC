<?php
use yii\grid\GridView;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $viewMode */ /* table widget */
$viewMode = Yii::$app->session->get('viewMode', 'table');
$qp = Yii::$app->request->queryParams;
$Url = $qp;
$Url[0] = '/base/projects';

use yii\helpers\Html;

$this->title = 'PC|Projects';
$this->params['breadcrumbs'][] = "پروژه‌ها";//['label' => "پروژه‌ها", 'url' => ['base/projects']];

?>
<div class="base-home">
    <?php if($viewMode == "table"){
        $Url['viewMode'] = 'widget';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;float:right;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => $Url, "right" => "#"], 'enabled' => "right"]);

        // add new
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'title'=>"افزودن پروژه جدید", 'onclick'=>"addProject()"]);

        ?>
        <?= GridView::widget([
        'options'=>["class"=>"dir-rtl"],
        'tableOptions'=>['id'=>"projectsTable", 'class'=>'table table-striped table-bordered table-hover text-center'],
        'headerRowOptions'=>['class'=>'link-white bg-secondary text-white text-center'],
        'summary'=>'{begin}-{end}/{totalCount}',
        'emptyText'=>"داده‌ای یافت نشد.",
        'rowOptions' =>
            function ($model, $key, $index, $grid)
            {
                return [ 'id'=>'row'.$model['id'],
                    'class'=>'table_row',
                    'ts'=> \app\components\Jdf::jdate("Y/m/d", $model->ts),
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
                    'attribute' =>'project_name',
                    'headerOptions' => ['class' => 'bg-secondary text-white text-center'],
                ],
                [
                    'attribute' =>'office',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                ],
                [
                    'attribute' =>'ts',
                    'headerOptions' => ['class' => 'bg-secondary text-center'],
                    'value'=>function($model){return \app\components\Jdf::jdate("Y/m/d", $model->ts); },
                    'filterInputOptions' => ['class' => 'd-none'],

                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{project_info}',
                    'header'=>"اطلاعات پروژه",
                    'buttons' => ['project_info' => function($url, $model, $key){ return "<a href='#' onclick=\"getInfo($model->id)\"><i class='fa fa-info-circle text-info'></i></a>";}],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{project_edit}',
                    'header'=>"ویرایش پروژه",
                    'buttons' => ['project_edit' => function($url, $model, $key){ return "<a href='#' onclick=\"editProject($model->id)\"><i class='fa fa-edit text-info'></i></a>";}],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{project_detail}',
                    'header'=>"جزییات پروژه",
                    'buttons' => ['project_detail' => function($url, $model, $key){ return "<a href=\"$url\"><i class='fa fa-rectangle-list text-info'></i></a>";}],
                    'urlCreator' => function ($action, $model, $key, $index)
                        {
                                $url = Yii::$app->request->baseUrl.'/project/index?id='.$model->id;
                                return $url;
                        }
                ],
            ],

    ]);
    ?>
    <?php } else {

        $Url['viewMode'] = 'table';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;float:right;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => "#", "right" => $Url], 'enabled' => "left"]);

        // add new
        echo Html::button("<i class='fa fa-plus'></i>", ['class' => 'btn btn-success', 'style'=>'width:40px;height:40px;padding:10px;', 'title'=>"افزودن پروژه جدید", 'onclick'=>"addProject()"]);

        $models = $dataProvider->getModels();
        ?>
        <div class="d-flex flex-wrap justify-content-start w-100 dir-rtl ">
            <?php
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
                    $title = \app\components\Jdf::jdate("Y/m/d", $model->ts);
                    $body = [];
                    $body['عنوان'] = $model->project_name;
                    $body['اداره کل'] = $model->office;
                    $footer = [];
                    //info
                    $icon='<i class="fa fa-info-circle text-info" title="اطلاعات پروژه"></i>';
                    $jsFunc="getInfo(".$model->id.")";
                    $link="";
                    $footer['left'] = ['icon'=>$icon, 'modality'=>true, 'jsFunc'=>$jsFunc, 'link'=>$link];

                    //edit
                    $icon='<i class="fa fa-edit text-info" title="ویرایش پروژه"></i>';
                    $jsFunc="editProject(".$model->id.")";
                    $link="";
                    $footer['center'] = ['icon'=>$icon, 'modality'=>true, 'jsFunc'=>$jsFunc, 'link'=>$link];

                    //link
                    $link = Yii::$app->request->baseUrl.'/project/index?id='.$model->id;
                    $icon="<i class='fa fa-rectangle-list text-info' title='جزییات پروژه'></i>";
                    $footer['right'] = ['icon'=>$icon, 'modality'=>false, 'jsFunc'=>"", 'link'=>$link];

                    echo \app\components\GeneralWidget::widget([ 'id' => $model->id, 'title'=>$title, 'body'=>$body, 'footer'=>$footer, 'options'=>[] ]);
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
echo \app\components\ModalWidget::widget(["id"=>"infoModal", 'maxWidth'=>"600px", "title"=>"اطلاعات پروژه" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);
echo \app\components\ModalWidget::widget(["id"=>"addModal",  'maxWidth'=>"600px", "title"=>"افزودن پروژه" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);
echo \app\components\ModalWidget::widget(["id"=>"editModal", 'maxWidth'=>"600px", "title"=>"ویرایش پروژه" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);

$bPath = Yii::$app->request->baseUrl;

$script =<<< JS

function activateRow(obj)
{
    $(".selectedRow").removeClass("selectedRow");
    $(obj).addClass("selectedRow");
}

function getInfo(id)
{
    $("#infoModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/project/ajax_info", // get body of modal
      type:"POST",
      data:{'id':id},
      success: function(info)
          {
              $("#infoModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#infoModal").modal("show");   
}

function addProject()
{
    $("#addModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/base/ajax_add_pr_form", // get body of modal
      type:"POST",
      data:{},
      success: function(info)
          {
              $("#addModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#addModal").modal("show");   
}

function editProject(id)
{
    $("#editModal .modal-dialog .modal-body").innerHTML="";
  $.ajax(
      {
      url: "$bPath/base/ajax_edit_pr_form", // get body of modal
      type:"POST",
      data:{'id':id},
      success: function(info)
          {
              $("#editModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#editModal").modal("show");   
}


JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>