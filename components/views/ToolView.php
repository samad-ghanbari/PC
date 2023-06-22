<?php
use yii\helpers\Url;

/* @var $width, $height, $iconClass, $title, $url; */
$STYLE = "width:$width; height:$height;"
?>

<div class="card border bg-ccc border-secondary m-1 rounded" style="<?= $STYLE; ?>">
    <a href="<?= $url; ?>">
        <div class="card-body">
            <i class="<?= $iconClass; ?> w-100 h-100px"></i>
        </div>
        <div class="card-footer">
            <h6 class="text-center dir-rtl"><?= $title; ?></h6>
        </div>
    </a>
</div>