<?php
use yii\grid\GridView;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $project \app\models\Projects */
/* @var $info \app\models\Projects */

use yii\helpers\Html;

$this->title = 'PC|Project';
$this->params['breadcrumbs'][] = $project->project_name;
?>
    <div class="project-index">
    <!-- info   -->
    <div class="card border bg-ccc border-secondary m-1 w-75 m-3 mx-auto h-auto rounded">
        <a href="#" onclick="getInfo(<?= $project->id; ?>)" class="d-inline-block">
            <h5 class="card-header text-center  text-info  font-weight-bold"><?= $project->project_name; ?></h5>
            <div class="card-body text-right w-100 dir-rtl">
                <h5 class="text-center m-3 float-left  text-info font-weight-bold"><?= \app\components\Jdf::jdate("Y/m/d", $project->ts); ?></h5>
                <h5 class="text-center m-3 float-right  text-info font-weight-bold"><?= $project->office; ?></h5>
            </div>
        </a>
    </div>

<?php $basepath = Yii::$app->request->baseUrl."/"; ?>
    <!--  tools  -->
    <div class="text-right d-flex flex-wrap justify-content-center w-100 m-5 mx-auto dir-rtl">
    <!--   detail     -->
        <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fa fa-rectangle-list  text-info", "title"=>"جزییات", "url"=>$basepath.'project/details?id='.$project->id]); ?>
    <!--   stat     -->
        <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fa fa-chart-area  text-info", "title"=>"آمار", "url"=>$basepath.'stat/index?id='.$project->id]); ?>

        <!--   import    -->
        <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fa fa-download  text-info", "title"=>"ورود اطلاعات", "url"=>$basepath.'import/index?id='.$project->id]); ?>

        <!--   equipments    -->
        <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fas fa-server  text-info", "title"=>"تجهیزات", "url"=>$basepath.'project/equipment?id='.$project->id]); ?>

    </div>

    <?php
    if(Yii::$app->user->can("Access Admin")){  ?>
    <!--  admin  -->
        <p class="hr-text"><span>مدیریت ادمین</span></p>
        <div class="text-right d-flex flex-wrap justify-content-center w-100 m-5 mx-auto dir-rtl">
            <!--  project users      -->
            <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fa fa-users text-info", "title"=>"کاربران پروژه", "url"=>$basepath.'project/details?id='.$project->id]); ?>

            <!--  tasks      -->
            <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fa fa-list-check text-info", "title"=>"فعالیت‌های پروژه", "url"=>$basepath.'project/details?id='.$project->id]); ?>

            <!--  equipments      -->
            <?= \app\components\ToolWidget::widget(['width'=>"150px", "height"=>"200px", "iconClass"=>"fas fa-server text-info", "title"=>"تجهیزات پروژه", "url"=>$basepath.'equipment/index?id='.$project->id]); ?>

        </div>
    <?php } ?>

<?php

//$id, $maxWidth, $title, $body, $buttonName, $buttonType
echo \app\components\ModalWidget::widget(["id"=>"infoModal", 'maxWidth'=>"600px", "title"=>"اطلاعات پروژه" , "body"=>"", "buttonName"=>"تایید", "buttonType"=>"btn-info"]);

$bPath = Yii::$app->request->baseUrl;
$script =<<< JS

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
              console.log(info);
              $("#infoModal .modal-dialog .modal-body").html(info);
          }
      }
  );
  
  $("#infoModal").modal("show");   
}

JS;
$this->registerJs($script, Yii\web\View::POS_END);
?>