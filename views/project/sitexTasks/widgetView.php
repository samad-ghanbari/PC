<?php
/* @var $this yii\web\View */
/* @var $pSitex \app\models\ProjectSitexView */
/* @var $pSitexMeta \app\models\ProjectSitexMeta */
/* @var $project \app\models\BaseProjects */
/* @var $sitexParameters \app\models\ProjectSitexTasks */
/* @var $parameters \app\models\ProjectTasks */
/* @var $options \app\models\ProjectTaskOptions */
/* @var $rules \app\models\ProjectTaskRules */
/* @var $lom [e,pe,pd,sd] */
?>

<?php
$SitexUpdate = $access['Update Sitex'];
// $pSitex $pSitexMeta
if($SitexUpdate) { ?>
    <a  href="<?= Yii::$app->request->baseUrl.'/project/sitex_update?psid='.$pSitex->id; ?>" class="d-block w-50px text-center mx-auto" ><i class='fa fa-edit text-secondary'></i></a>
<?php }?>

<div class="d-flex flex-wrap justify-content-center w-100 dir-rtl ">
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"منطقه", 'value'=>$pSitex->area, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"نام", 'value'=>$pSitex->name, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"اختصار", 'value'=>$pSitex->abbr, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?php if($pSitex->type == 'سایت'){ ?>
        <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"مرکز اصلی", 'value'=>$pSitex->center_name, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
        <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"اختصار مرکز اصلی", 'value'=>$pSitex->center_abbr, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?php } ?>

    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"نوع", 'value'=>$pSitex->type, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?php $DONE=""; if($pSitex->done) $DONE="<i class='fa fa-check font-weight-bold text-success'></i>"; else $DONE="<i class='fa fa-times font-weight-bold text-danger'></i>"; ?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"اتمام کار", 'value'=>$DONE, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?php $PERC = \app\components\ProgressWidget::widget(["width"=>null, "height"=>null, "percentage"=>round(100*($pSitex->weight/$project->project_weight), 1)]);?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"درصد پیشرفت", 'value'=>$PERC, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"آدرس", 'value'=>$pSitex->address, 'href'=>null, 'onclick'=>null, 'attr'=>null]); ?>

    <?php $attr= "psitex-id='".$pSitex->id."' field='phase' titr='ویرایش فاز'";
        $onclick=null; if ($SitexUpdate) $onclick="updateField(this)";
    ?>
    <?= \app\components\CardWidget::widget(['width'=>null, 'height'=>null, 'title'=>"فاز", 'value'=>$pSitex->phase, 'href'=>null, 'onclick'=>$onclick, 'attr'=>$attr]); ?>

    <?php
    foreach ($pSitexMeta as $sm)
    {
        $attr=  "psitex-id='".$sm->project_sitex_id."' field='meta' titr=".'ویرایش '.$sm->key." meta-id='".$sm->id."'";
        $onclick=null; if ($SitexUpdate) $onclick="updateField(this)";
        echo \app\components\CardWidget::widget(['width' =>null, 'height' =>null, 'title' => $sm->key, 'value' => $sm->value, 'href' => null, 'onclick' => $onclick, 'attr' => $attr]);
    }
    ?>

</div>