<?php
use yii\helpers\Html;

/* @var  $params [taskId, task, type, done, value, modifier, permit, access, titleHint ] */
/* @var $x, $y, $pSitex_id int */

$taskWidth = "400px"; // must be the same as CodeGeneration:getTasksPositions
$taskHeight = "200px";
$editable = ($params['permit'] && $params['access'])?true:false;

$bg = "";
if( $params['value'] == "" )
    $bg = "bg-danger";
else
    $bg = "bg-success";
if($params['type'] == "select")
{
    if($params['done'])
        $bg = "bg-success";
    else
        $bg = "bg-danger";
}
$mod = "";
if(!empty($params['modifier']))
    $mod = $params['modifier']." در ".$params['date'];
?>

<div title="<?=$params['titleHint'];?>" class="borderBox border border-info rounded" style="padding:5px;width:<?= $taskWidth; ?>; height:<?= $taskHeight; ?>; background-color:#eee; position: absolute; top:<?= $y.'px'; ?>; left:<?= $x.'px';?>;">
    <p class='font-weight-bold text-center'><?= $params['task'];?></p>
        <p class="text-center <?= $bg; ?>"><?= $params['value'];?></p>
        <hr />
        <p class='text-center font-18px' ><?= $mod; ?></p>
    <?php if($editable) { ?>
        <a style="width:50px;height:50px;display:block;margin:auto;" pSitex-id='<?=$pSitex_id;?>' task-id='<?=$params['taskId'];?>'  onclick='editTask(this)' ><i class='fa fa-edit text-secondary'></i></a>
    <?php } ?>
</div>
