<?php
/* @var $pSitex \app\models\ProjectSitexView */
/* @var $pSitexMeta \app\models\ProjectSitexMeta */
/* @var $project \app\models\BaseProjects */
?>
<?php
$SitexUpdate = $access['Update Sitex'];
// $pSitex $pSitexMeta
if($SitexUpdate) { ?>
    <a  href="<?= Yii::$app->request->baseUrl.'/project/sitex_update?psid='.$pSitex->id; ?>" class="d-block text-center w-50px mx-auto" ><i class='fa fa-edit text-secondary'></i></a>
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
            <a  psitex-id="<?= $pSitex->id; ?>" field='phase' titr="ویرایش فاز" onclick="updateField(this)" class="float-left" ><i class='fa fa-edit text-secondary'></i></a>
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
              <a psitex-id="<?= $sm->project_sitex_id; ?>" field="meta" titr="<?= 'ویرایش '.$sm->key; ?>" meta-id="<?= $sm->id; ?>" onclick="updateField(this)" class="float-left" ><i class='fa fa-edit text-secondary'></i></a>
            <?php }
        echo "</td></tr>";
    }
        ?>
</table>
