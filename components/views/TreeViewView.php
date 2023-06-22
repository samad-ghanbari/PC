<?php
use yii\helpers\Url;

/* @var $model */
/* @var $id */

// info: id, value,desc, href
$class="dir-ltr text-left";
if($rtl)
$class = "dir-rtl text-right";
?>
<div id="<?= $id; ?>" class="<?= $class; ?>" >
    <ul>
        <?php
        foreach($model as $node1) // node : info, child
        {
            echo "<li data-jstree='{ \"opened\" : true }'>";
            echo "<span id=".$node1['info']['id']." title='".$node1['info']['desc']."' class='font-weight-bold  text-dark'>".$node1['info']['value']."</span>";
            if(sizeof($node1['child']) > 0)
            {
                echo "<ul>";
                foreach($node1['child'] as $node2)
                {
                    echo "<li data-jstree='{ \"opened\" : true }'>";
                    echo "<span id=".$node2['info']['id']." title='".$node2['info']['desc']."'class='font-weight-bold text-info'>".$node2['info']['value']."</span>";
                    if(sizeof($node2['child']) > 0)
                    {
                        echo "<ul>";
                        foreach($node2['child'] as $node3)
                        {
                            echo "<li data-jstree='{ \"opened\" : true }'>";
                            echo "<span id=".$node3['info']['id']." title='".$node3['info']['desc']."' class='font-weight-bold text-secondary'>".$node3['info']['value']."</span>";
                            if(sizeof($node3['child']) > 0)
                            {
                                echo "<ul>";
                                foreach($node3['child'] as $node4)
                                {
                                    echo "<li data-jstree='{ \"opened\" : true }'>";
                                    echo "<span id=".$node4['info']['id']." title='".$node4['info']['desc']."' class='font-weight-bold text-danger'>".$node4['info']['value']."</span>";
                                    if(sizeof($node4['child']) > 0)
                                    {
                                        echo "<ul>";
                                        foreach($node4['child'] as $node5)
                                        {
                                            echo "<li data-jstree='{ \"opened\" : true }'>";
                                            echo "<span id=".$node5['info']['id']." title='".$node5['info']['desc']."' class='font-weight-bold text-primary'>".$node5['info']['value']."</span>";
                                        }
                                        echo "</ul>";
                                        echo "</li>";
                                    } else echo "</li>";
                                }
                                echo "</ul>";
                                echo "</li>";
                            }
                            else echo "</li>";
                        }
                        echo "</ul>";
                        echo "</li>";
                    }
                    else
                        echo "</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
            else
                echo "</li>";

        }
        ?>
    </ul>
</div>