
<?php
$footerVisible = "";
if(empty($buttonName)) $footerVisible = " d-none ";
?><!-- Modal -->
<div class="modal fade text-right dir-rtl box-shadow-dark"  data-backdrop="static" data-keyboard="false"  id="<?= $id; ?>" role="dialog" >
    <div class="modal-dialog modal-lg" style="max-width:<?= $maxWidth; ?>">
        <div class="modal-content" >

            <div class="modal-header text-dark bg-light border-0" >
                <h4 class="modal-title float-right"><?= $title; ?></h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body bg-ddd border-0">
                <?= $body; ?>
            </div>

            <div class="modal-footer bg-ddd border-0 <?= $footerVisible; ?>">
                <button class="btn <?= $buttonType; ?>" data-dismiss="modal" ><?= $buttonName; ?></button>
                <br style="clear: both;" />
            </div>

        </div>
    </div>
</div>
<!-- Modal -->