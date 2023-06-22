<?php
use yii\helpers\Url;

/* @var $width, $height, $percentage */
$STYLE = "width:".$width."; height:".$height.";margin:auto;max-width:500px;";
if($percentage < 30) $COLOR="danger"; else if($percentage < 60) $COLOR="primary"; else if($percentage >= 60) $COLOR="success";
$PERCENTAGE = "width:".$percentage."%";
?>


 <div class="progress border border-secondary rounded-0" style="<?= $STYLE; ?>" >
  <div class="progress-bar progress-bar-striped <?= "bg-".$COLOR; ?> " style="<?= $PERCENTAGE; ?>"></div>
</div>
<p class="text-center font-weight-bold en-font <?= "text-".$COLOR; ?>"><?= "% ".$percentage; ?></p>
