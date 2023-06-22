<?php
use yii\helpers\Html;

/* @var $options, $texts, $hrefs, $enabled; */
$left = "";
$right = "";
if($enabled == "right")
{
    if($hrefs['left'] == "#")
        $left = "text-secondary bg-white disabled";
    else
        $left = "text-secondary bg-white";

    if($hrefs['right'] == "#")
        $right = "text-white bg-info font-weight-bold disabled";
    else
        $right = "text-white bg-info font-weight-bold";
}
else
{
    if($hrefs['right'] == "#")
        $right = "text-secondary bg-white disabled";
    else
        $right = "text-secondary bg-white";

    if($hrefs['left'] == "#")
        $left = "text-white bg-info font-weight-bold disabled";
    else
        $left = "text-white bg-info font-weight-bold";
}
?>

<div style="direction:rtl;">
    <div style="text-align:center; <?= $options; ?>">
        <?= Html::a($texts['left'], $hrefs['left'] , ['class'=>"float-left p-1 border border-secondary ".$left , 'style'=>"width:50%;box-sizing: border-box;"]); ?>
        <?= Html::a($texts['right'], $hrefs['right'] , ['class'=>"float-left p-1 border border-secondary ".$right , 'style'=>"width:50%;box-sizing: border-box;"]); ?>
    </div>
</div>
