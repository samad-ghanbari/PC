<?php
use yii\helpers\Url;

/* @var $width, $height, $title, $value, $href, $onclick, $attr */
$STYLE="";
if(!empty($width))
    $STYLE .= "width:$width;";
if(!empty($height))
    $STYLE .= "height:$height;";
?>

<div class="card border bg-ccc border-secondary m-1 rounded" style="<?= $STYLE; ?>">
    <div class="card-header text-center font-weight-bold dir-rtl"><?= $title; ?></div>
    <div class="card-body text-center dir-rtl">
        <?= $value; ?>
    </div>
    <?php if(!empty($href)){ ?>
    <div class="card-footer">
        <a href="<?= $href; ?>" class="w-100">
            <h6 class="text-center dir-rtl text-center"><i class="fa fa-edit text-info"></i></h6>
        </a>
    </div>
    <?php }
    else if(!empty($onclick)) { ?>
        <div class="card-footer">
        <a <?= $attr; ?> onclick="<?= $onclick; ?>" class="w-100">
            <h6 class="text-center dir-rtl text-center"><i class="fa fa-edit text-info"></i></h6>
        </a>
    </div>
    <?php } ?>
</div>