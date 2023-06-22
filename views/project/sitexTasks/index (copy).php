<?php
$this->registerCssFile("@web/web/css/jstree.min.css");

/* @var $this yii\web\View */
/* @var $pSitex \app\models\ProjectSitexView */
/* @var $pSitexMeta \app\models\ProjectSitexMeta */
/* @var $project \app\models\BaseProjects */
/* @var $sitexParameters \app\models\ProjectSitexTasks */
/* @var $parameters \app\models\ProjectTasks */
/* @var $options \app\models\ProjectTaskOptions */
/* @var $rules \app\models\ProjectTaskRules */
/* @var $lom [e,pe,pd,sd] */

Yii::$app->formatter->nullDisplay = "";
$projectWeight = $project->project_weight;
$viewMode = Yii::$app->session->get('viewMode', 'table');
$tableView = "";
$widgetView = "";
if($viewMode == "table") {
    $tableView = "disabled";
    $widgetView = "text-info";
}
else {
    $widgetView = "disabled";
    $tableView = "text-info";
}

$qp = Yii::$app->request->queryParams;
$widgetUrl = $qp;
$widgetUrl[0] = '/project/sitex_param';
$widgetUrl['id'] = $pSitex->id;
$widgetUrl['viewMode'] = 'widget';

$tableUrl = $qp;
$tableUrl[0] = '/project/sitex_param';
$tableUrl['id'] = $pSitex->id;
$tableUrl['viewMode'] = 'table';


use yii\helpers\Html;

$this->title = 'PC|Site|Param';
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['project/index?id='.$project->id]];
$this->params['breadcrumbs'][] = ['label' => "جزيیات پروژه", 'url' => ['project/details?id='.$project->id]];
$this->params['breadcrumbs'][] = "پارامتر‌ها";

?>
<div class="project-sitex-info">

    <div class="view-type text-right mr-4">
        <?= Html::a("<i class='fa fa-th'></i>", $widgetUrl , ['class' => $widgetView, 'title'=>"نمایش ویجتی"]) ?>
        <?= Html::a("<i class='fa fa-table'></i>", $tableUrl , ['class' => $tableView, 'title'=>"نمایش جدولی"]) ?>
    </div>

    <?php if($viewMode == "table"){ ?>

        <p class="hr-text"><span>اطلاعات پایه</span></p>
        <?php
        $SitexUpdate = $access['Update Sitex'];
        // $pSitex $pSitexMeta
        if($SitexUpdate) { ?>
            <a  href="<?= Yii::$app->request->baseUrl.'/project/sitex_update?psid='.$pSitex->id; ?>" class="d-block w-75 text-left mx-auto" ><i class='fa fa-edit text-secondary'></i></a>
        <?php }?>
        <table class="table table-hover table-striped w-75 mx-auto max-width-1000px dir-rtl text-right">
            <tr>
                <td>منطقه</td>
                <td>
                    <?= $pSitex->area; ?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">نام</td>
                <td>
                    <?= $pSitex->name; ?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">اختصار</td>
                <td>
                    <?= $pSitex->abbr; ?>
                </td>
            </tr>
            <?php if($pSitex->type == 'سایت'){ ?>
                <tr>
                    <td class="font-weight-bold">مرکز اصلی</td>
                    <td>
                        <?= $pSitex->center_name; ?>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">اختصار مرکز اصلی</td>
                    <td>
                        <?= $pSitex->center_abbr; ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="font-weight-bold">نوع</td>
                <td>
                    <?= $pSitex->type; ?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">اتمام کار</td>
                <td>
                    <?php if($pSitex->done) echo "<i class='fa fa-check font-weight-bold text-success'></i>"; else echo "<i class='fa fa-times font-weight-bold text-danger'></i>"; ?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">درصد پیشرفت</td>
                <td>
                    <?= \app\components\ProgressWidget::widget(["width"=>"100%", "height"=>"20px", "percentage"=>round(100*($pSitex->weight/$project->project_weight), 1)]);?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">آدرس</td>
                <td>
                    <?= $pSitex->address; ?>
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold">فاز</td>
                <td>
                    <?= $pSitex->phase; ?>
                    <?php if($SitexUpdate) { ?>
                    <a  href="#" psitex-id="<?= $pSitex->id; ?>" field='phase' titr="ویرایش فاز" onclick="updateField(this)" class="float-left" ><i class='fa fa-edit text-secondary'></i></a>
                    <?php }?>
                </td>
            </tr>
            <?php
            foreach ($pSitexMeta as $sm)
            {
               echo "<tr>";
                echo "<td class='font-weight-bold'>".$sm->key."</td>";
                echo "<td>";
                    echo $sm->value;
                    if($SitexUpdate) { ?>
                      <a  href="#" psitex-id="<?= $sm->project_sitex_id; ?>" field="meta" titr="<?= 'ویرایش '.$sm->key; ?>" meta-id="<?= $sm->id; ?>" onclick="updateField(this)" class="float-left" ><i class='fa fa-edit text-secondary'></i></a>
                    <?php }
                echo "</td></tr>";
            }
                ?>
        </table>

        <p class="hr-text"><span>لیست تجهیزات</span></p>
        <?php
        $treeViewModel = \app\components\TreeViewModelGenerate::model4Layer($lom['e'], $lom['pe'], $lom['pd'], $lom['sd']);
        echo "<div class='w-100 d-flex justify-content-center en-font'>";
            echo \app\components\TreeViewWidget::widget(['model'=>$treeViewModel, "id"=>"lom-tree-view", "rtl"=>true]);
        echo "</div>";
        ?>
        <a href="#" psid="<?= $pSitex->id; ?>" class="d-block w-50px mx-auto text-center mt-3" onclick="addEquip(this)"><i class="fa fa-plus text-primary"></i></a>

        <p class="hr-text"><span>پارامتر ها</span></p>
            <?= \app\components\CodeGeneration::paramTable($parameters, $sitexParameters, $options, $rules, $access, $pSitex->id); ?>
    <?php } else {
        ?>
        <div class="d-flex flex-wrap justify-content-start w-100 dir-rtl ">
        </div>

    <?php } ?>
    <br />
    <br />
</div>

<?php
//$id, $maxWidth, $title, $body, $buttonName, $buttonType
echo \app\components\ModalWidget::widget(["id"=>"pageModal", 'maxWidth'=>"1000px", "title"=>"ویرایش اطلاعات" , "body"=>"", "buttonName"=>"", "buttonType"=>""]);

$bPath = Yii::$app->request->baseUrl;
$script =<<< JS

    $.jstree.defaults.core.themes.variant = "large";
	$.jstree.defaults.core.themes.icons = false;
	$('#lom-tree-view').jstree();
	
	
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

function editParam(obj)
{
    var pSitex_id = $(obj).attr('pSitex-id');
    var param_id = $(obj).attr('param-id');
    
    $("#pageModal .modal-body").innerHTML="";
    var titr = "ویرایش پارامتر";
    $("#pageModal .modal-title").text(titr);
    
      $.ajax(
              {
              url: "$bPath/project/psitex_ajax_param", 
              type:"POST",
              data:{'pSitex_id':pSitex_id, 'param_id': param_id},
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