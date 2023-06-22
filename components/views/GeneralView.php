<?php
use yii\helpers\Url;

/* @var $id, $title, $body, $footer, $options */
//title : text
//body : key value
//footer: [
//          left=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
//          center=>[icon=>"", modality=>false,  jsFunc=>""       , link=>"a/s/f.php" ],
//          right=> [icon=>"", modality=> true,  jsFunc=>"add(1)" , link=>"" ]
//        ]
//options=>[class=>"", style=>""]

$leftFlag = false;
$centerFlag = false;
$rightFlag = false;
$class=" w-300px "; $style="";
if(isset($footer['left'])) $leftFlag = true;
if(isset($footer['center'])) $centerFlag = true;
if(isset($footer['right'])) $rightFlag = true;

if(isset($options['class'])) $class = $options['class'];
if(isset($options['style'])) $style = $options['style'];
?>

<div class="card border bg-bbb border-secondary m-1 rounded <?= $class; ?> " style="<?= $style; ?>" onclick="activateRow(this)">
    <h5 class="card-header text-center text-info font-weight-bold en-font"><?= $title; ?></h5>
    <div class="card-body dir-rtl text-right p-0">
        <table class="table table-striped table-bordered table-hover text-center overflow-y-scroll m-0">
            <tbody>
            <?php foreach($body as $k=>$v){ ?>
                <tr class="dir-rtl">
                    <td class="font-weight-bold"><?= $k; ?></td>
                    <td class="text-dark font-16px"><?= $v; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer row w-100 m-0 dir-ltr">
        <div class="col-sm-4">
            <?php
            if($leftFlag)
            {
                //left=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
                $icon = $footer['left']['icon'];
                $modality = $footer['left']['modality'];
                $jsFunc = "";
                $link = "";
                if($modality)
                    $jsFunc = $footer['left']['jsFunc'];
                else
                    $link = $footer['left']['link'];

                if($modality)
                {
                    // modal ?>
                    <a onclick="<?= $jsFunc; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
                else
                {
                    //link ?>
                    <a href="<?= $link; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-sm-4">
            <?php
            if($centerFlag)
            {
                //center=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
                $icon = $footer['center']['icon'];
                $modality = $footer['center']['modality'];
                $jsFunc = "";
                $link = "";
                if($modality)
                    $jsFunc = $footer['center']['jsFunc'];
                else
                    $link = $footer['center']['link'];

                if($modality)
                {
                    // modal ?>
                    <a onclick="<?= $jsFunc; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
                else
                {
                    //link ?>
                    <a href="<?= $link; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-sm-4">
            <?php
            if($rightFlag)
            {
                //right=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
                $icon = $footer['right']['icon'];
                $modality = $footer['right']['modality'];
                $jsFunc = "";
                $link = "";
                if($modality)
                    $jsFunc = $footer['right']['jsFunc'];
                else
                    $link = $footer['right']['link'];

                if($modality)
                {
                    // modal ?>
                    <a onclick="<?= $jsFunc; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
                else
                {
                    //link ?>
                    <a href="<?= $link; ?>" class="col-sm"><?= $icon; ?></a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>