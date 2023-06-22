<?php
use yii\helpers\Url;

/* @var $model search model */
/* @var $items array area name abbr .... */

?>
<div class="d-flex flex-wrap m-1 justify-content-start w-100 dir-rtl " >
    <?php foreach ($items as $key=>$value)
        {
            if(!empty($model->$key)) {?>

            <div class="alert-warning text-right dir-rtl m-1 alert alert-dismissible" role="alert">
               <?= $value.": ".$model->$key; ?>
                <button class="close" type="button" filter-item="<?= $key; ?>" onclick="filterClose(this);" ><span aria-hidden="true">&times;</span></button>
            </div>

    <?php   }   } ?>
</div>
