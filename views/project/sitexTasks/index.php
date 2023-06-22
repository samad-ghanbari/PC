<?php
$this->registerCssFile("@web/web/css/jstree.min.css");

/* @var $this yii\web\View */
/* @var $pSitex \app\models\ProjectSitexView */
/* @var $pSitexMeta \app\models\ProjectSitexMeta */
/* @var $project \app\models\BaseProjects */
/* @var $sitexTasks \app\models\ProjectSitexTasks */
/* @var $tasks \app\models\ProjectTasks */
/* @var $taskTree array */
/* @var $options \app\models\ProjectTaskOptions */
/* @var $rules \app\models\ProjectTaskRules */
/* @var $lom [e,pe,pd,sd] */

Yii::$app->formatter->nullDisplay = "";
$projectWeight = $project->project_weight;
$viewMode = Yii::$app->session->get('viewMode', 'table');
$chart = Yii::$app->session->get('chart', 0);
$chart = intval($chart);

$qp = Yii::$app->request->queryParams;
$Url = $qp;
$Url[0] = '/project/sitex_tasks';
$Url['id'] = $pSitex->id;

use yii\helpers\Html;

$this->title = 'PC|Site|Param';
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['project/index?id='.$project->id]];
$this->params['breadcrumbs'][] = ['label' => "جزيیات پروژه", 'url' => ['project/details?id='.$project->id]];
$this->params['breadcrumbs'][] = "فعالیت‌ها";

?>
<div class="project-sitex-info">

    <p class="hr-text"><span>اطلاعات پایه</span></p>

    <?php
    if($viewMode == "table")
        {
            $Url['viewMode'] = 'widget';
            echo \app\components\SwitchWidget::widget(['options' => "width:200px;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => $Url, "right" => "#"], 'enabled' => "right"]);
            require_once("tableView.php");
        }
    else
    {
        $Url['viewMode'] = 'table';
        echo \app\components\SwitchWidget::widget(['options' => "width:200px;", 'texts' => ['left' => "ویجت", "right" => "جدول"], 'hrefs' => ['left' => "#", "right" => $Url], 'enabled' => "left"]);
        require_once("widgetView.php");
    }
    ?>


    <p class="hr-text"><span>لیست تجهیزات</span></p>
    <?php
    $treeViewModel = \app\components\TreeViewModelGenerate::model4Layer($lom['e'], $lom['pe'], $lom['pd'], $lom['sd']);
    echo "<div class='w-100 d-flex justify-content-center en-font'>";
    echo \app\components\TreeViewWidget::widget(['model'=>$treeViewModel, "id"=>"lom-tree-view", "rtl"=>true]);
    echo "</div>";
    ?>
    <a psid="<?= $pSitex->id; ?>" class="d-block w-50px mx-auto text-center mt-3" onclick="addEquip(this)"><i class="fa fa-plus text-primary"></i></a>

    <p class="hr-text"><span>فعالیت‌ها</span></p>
    <div id="tasks" class="view-type text-right mr-4">
        <?php if($chart == 1) {
            $Url['viewMode'] = $viewMode;
            $Url['chart'] = 0;
            ?>
            <?= \app\components\SwitchWidget::widget(['options'=>"width:200px;", 'texts'=>['left'=>"چارت", "right"=>"جدول"], 'hrefs'=>['left'=>"#", "right"=>$Url], 'enabled'=>"left"]); ?>
            <?= \app\components\CodeGeneration::taskChart($tasks, $taskTree, $sitexTasks, $options, $rules, $access, $pSitex->id); ?>

        <?php } else {
            $Url['viewMode'] = $viewMode;
            $Url['chart'] = 1;?>

            <?= \app\components\SwitchWidget::widget(['options'=>"width:200px;", 'texts'=>['left'=>"چارت", "right"=>"جدول"], 'hrefs'=>['left'=>$Url, "right"=>"#"], 'enabled'=>"right"]); ?>
            <?= \app\components\CodeGeneration::taskTable($tasks, $sitexTasks, $options, $rules, $access, $pSitex->id); ?>

        <?php } ?>
    </div>
    <br />
    <br />
</div>

<?php
//$id, $maxWidth, $title, $body, $buttonName, $buttonType
echo \app\components\ModalWidget::widget(["id"=>"pageModal", 'maxWidth'=>"1000px", "title"=>"ویرایش اطلاعات" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);

$bPath = Yii::$app->request->baseUrl;
$script =<<< JS

$(document).ready(
    function()
    {
        $.jstree.defaults.core.themes.variant = "large";
        $.jstree.defaults.core.themes.icons = false;
        $('#lom-tree-view').jstree();
        // if(urlSuffix.startsWith('#'))
        //     $('html, body').animate({scrollTop: $(urlSuffix).offset().top}, 0);
    }
);

function activateRow(obj)
{
    $(".selectedRow").removeClass("selectedRow");
    $(obj).addClass("selectedRow");
}

function updateField(obj)
{
        $("#pageModal .modal-body").innerHTML="";
        var titr = $(obj).attr("titr");
        $("#pageModal .modal-title").text(titr);
        
        var psitex_id = $(obj).attr("psitex-id");
        var field = $(obj).attr("field");
        var meta_id = -1;
        if(field == 'meta') meta_id = $(obj).attr("meta-id");
        

          $.ajax(
                  {
                  url: "$bPath/project/psitex_ajax_field", 
                  type:"POST",
                  data:{'psitex_id':psitex_id, 'field':field, 'meta_id':meta_id},
                  success: function(info)
                      {
                          if(info !== "")
                              {
                                  $("#pageModal .modal-body").html(info);
                                  $("#pageModal").modal("show"); 
                              }
                          else 
                              {
                                  alert("دریافت اطلاعات فرم با خطا مواجه گردید.");
                              }
                      }
                  }
              );
}

function editSitexDed(obj)
{
        var parent = $(obj).parent();
        $("#pageModal .modal-body").innerHTML="";
        var titr = "ویرایش تخصیص تجهیزات";
        $("#pageModal .modal-title").text(titr);
        
        var sd_id = $(parent).attr("id");

          $.ajax(
                  {
                  url: "$bPath/project/psitex_ajax_ded", 
                  type:"POST",
                  data:{'sd_id':sd_id},
                  success: function(info)
                      {
                          if(info !== "")
                              {
                                  $("#pageModal .modal-body").html(info);
                                  $("#pageModal").modal("show"); 
                              }
                          else 
                              {
                                  alert("دریافت اطلاعات فرم با خطا مواجه گردید.");
                              }
                      }
                  }
              );
}

function addEquip(obj)
{
        $("#pageModal .modal-body").innerHTML="";
        var titr = "تخصیص تجهیز جدید";
        $("#pageModal .modal-title").text(titr);
        
        var psid = $(obj).attr("psid");

          $.ajax(
                  {
                  url: "$bPath/project/psitex_ajax_add_sd", 
                  type:"POST",
                  data:{'psid':psid},
                  success: function(info)
                      {
                          if(info !== "")
                              {
                                  $("#pageModal .modal-body").html(info);
                                  $("#pageModal").modal("show"); 
                              }
                          else 
                              {
                                  alert("دریافت اطلاعات فرم با خطا مواجه گردید.");
                              }
                      }
                  }
              );
}

function editTask(obj)
{
    var pSitex_id = $(obj).attr('pSitex-id');
    var task_id = $(obj).attr('task-id');
    
    $("#pageModal .modal-body").innerHTML="";
    var titr = "ویرایش پارامتر";
    $("#pageModal .modal-title").text(titr);
    
      $.ajax(
              {
              url: "$bPath/project/psitex_ajax_task", 
              type:"POST",
              data:{'pSitex_id':pSitex_id, 'task_id': task_id},
              success: function(info)
                  {
                      if(info !== "")
                          {
                              $("#pageModal .modal-body").html(info);
                              $("#pageModal").modal("show"); 
                          }
                      else 
                          {
                              alert("دریافت اطلاعات فرم با خطا مواجه گردید.");
                          }
                  }
              }
          );
          
    
}






JS;
$this->registerJs($script, Yii\web\View::POS_END);

$this->registerJsFile( '@web/web/js/jstree.min.js', ['depends' => [\yii\web\JqueryAsset::class]] );

?>