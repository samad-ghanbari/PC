<?php
/* @var $infoArray */
?>

<table class="table table-striped table-bordered table-hover text-center">
    <tbody>
    <?php foreach($infoArray as $key=>$value)
          { ?>
              <tr>
                  <td class="bg-info text-white font-weight-bold"><?= $key; ?></td>
                  <td class="bg-light text-dark"><?= $value; ?></td>
              </tr>
    <?php } ?>
    </tbody>
</table>
