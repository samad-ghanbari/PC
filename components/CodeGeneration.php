<?php
namespace app\components;
use Yii;
use yii\base\Component;

class CodeGeneration extends Component
{

    private function getTaskParams($task, $sitexTasks,$options, $rules, $access )
    {
        //res: [taskId, task, type, done, value, modifier, date, permit, access, titleHint ]
        $title = $task['task'];
        $type = $task['type'];
        $modifier = "";
        $date = "";
        if(isset($sitexTasks[$task['id']]))
        {
            if(!empty($sitexTasks[$task['id']]['modifier']))
            {
                $ts = \app\components\Jdf::jdate("Y/m/d", $sitexTasks[$task['id']]['ts'] );
                $modifier = "ویرایش شده توسط ".$sitexTasks[$task['id']]['modifier'];
                $date = " تاریخ ".$ts;
            }
        }
        $value = "";
        $done = false;
        if($type == "select")
        {
            if(isset($sitexTasks[$task['id']]['option_id']))
            {
                $optionId = $sitexTasks[$task['id']]['option_id'];
                $done = $options[$optionId]['done_option'];
                $value = $options[$optionId]['option'];
            }
        }
        else if($type == "text")
        {
            if(isset($sitexTasks[$task['id']]['value']))
                $value = $sitexTasks[$task['id']]['value'];

        }
        else if($task['type'] == "number")
        {
            if(isset($sitexTasks[$task['id']]['value']))
                $value = $sitexTasks[$task['id']]['value'];
        }
        else if($task['type'] == "date")
        {
            if(isset($sitexTasks[$task['id']]['value']))
            {
                if(empty($sitexTasks[$task['id']]['value']))
                    $value = "";
                else
                {
                    $value = $sitexTasks[$task['id']]['value'];
                    $value = intval($value);
                    $value = \app\components\Jdf::jdate("Y/m/d", $value);
                }
            }
        }

        $permit = $rules[$task['id']]['permit'];
        $acc = $access['Update Record'];
        $titleHint = "";
        $needs = $rules[$task['id']]['need'];
        if(!empty($needs))
        {
            $titleHint = "پیش‌نیازها: ";
            foreach ($needs as $i=>$n )
                $titleHint .= "\n".$n['name'];
        }

        $res = ['taskId'=>$task['id'], 'task'=>$title, 'type'=>$type, 'done'=>$done, 'value'=>$value, 'modifier'=>$modifier, 'date'=>$date, 'permit'=>$permit, 'access'=>$acc, 'titleHint'=>$titleHint];
        return $res;
    }

    public function taskTable($tasks, $sitexTasks, $options, $rules, $access, $pSitex_id)
    {
        //   rules : [parid=>[permit, desc, need] , ... ]
        //   access :
        //  'Access Admin' => boolean true
        //  'Access Statistics' => boolean true
        //  'Create Record' => boolean true
        //  'Import From Excel' => boolean true
        //  'Remove Record' => boolean true
        //  'Update Record' => boolean true
        //  'Update Sitex' => boolean true

        $code = "<table class='table table-hover table-striped w-75 mx-auto max-width-1000px dir-rtl text-right'>";

        foreach ($tasks as $task)
        {
            $param = \app\components\CodeGeneration::getTaskParams($task, $sitexTasks,$options, $rules, $access);
            $code .= \app\components\CodeGeneration::taskRow($param['task'], $param['type'], $param['done'], $param['value'], $param['modifier'], $param['date'], $param['permit'] , $param['access'], $param['titleHint'], $pSitex_id, $task['id']);
        }
        $code .= "</table>";

        return $code;
    }
    private function taskRow($title, $type, $done, $value, $modifier, $date, $permit, $access, $titleHint, $pSitex_id, $task_id )
    {
        $code = "<tr title='".$titleHint."'>";
        $code .= "<td class='font-weight-bold'>".$title."</td>";
        if(empty($value))
        {
            $code .= "<td class='bg-warning'></td> <td>".$modifier." - ".$date."</td>";
            if($permit && $access)
                $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'  onclick='editTask(this)' ><i class='fa fa-edit text-secondary float-left'></i></a></td>";
            else
                $code .= "<td></td>";
        }
        else
        {
            if($type == "select")
            {
                if($done)
                {
                    $code .= "<td class='bg-success'>".$value."</td>";
                    $code .= "<td>".$modifier."</td>";
                    if($permit && $access)
                        $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'  onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                    else
                        $code .= "<td></td>";
                }
                else
                {
                    $code .= "<td class='bg-danger'>".$value."</td>";
                    $code .= "<td>".$modifier."</td>";
                    if($permit && $access)
                        $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'  onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                    else
                        $code .= "<td></td>";
                }
            }
            else if($type == "text")
            {
                $code .= "<td>".$value."</td>";
                $code .= "<td>".$modifier."</td>";
                if($permit && $access)
                    $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'   onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                else
                    $code .= "<td></td>";
            }
            else if($type == "number")
            {
                $code .= "<td>".$value."</td>";
                $code .= "<td>".$modifier."</td>";
                if($permit && $access)
                    $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'  onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                else
                    $code .= "<td></td>";
            }
            else if($type == "date")
            {
                $code .= "<td>".$value."</td>";
                $code .= "<td>".$modifier."</td>";
                if($permit && $access)
                    $code .= "<td><a  pSitex-id='".$pSitex_id."' task-id='".$task_id."'   onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                else
                    $code .= "<td></td>";
            }
            else
            {
                $code .= "<td class='bg-warning'></td> <td></td>";
                if($permit && $access)
                    $code .= "<td><a pSitex-id='".$pSitex_id."' task-id='".$task_id."'  onclick='editTask(this)'><i class='fa fa-edit text-secondary float-left'></i></a></td>";
                else
                    $code .= "<td></td>";
            }
        }

        $code .= "</tr>";
        return $code;
    }

    public function taskChart($tasks, $taskTree, $sitexTasks, $options, $rules, $access, $pSitex_id)
    {
        // tasks:      [ [id, project_id, task, priority, type, weight], ... ]
        // tasktree:   [ id1=>[id2=>[id3=>[]] ,  ]
        // sitexTasks: [ tid=>[id, project_sitex_id, task_id, value, option_id, ts, modifiler_id, modifier] , ...]
        // options:    [ tid=>[tid, task_id, option, default_option, done_option]]
        // rules:      [ tid=>[permit, desc, need=>[...]] ,...]
        // access:     [ 'Access Admin', 'Access Statistics', 'Create Record', 'Import From Excel', 'Remove Record', 'Update Record', 'Update Sitex' ]
        // pSitex_id:  int

        $tasksPosition = \app\components\CodeGeneration::getTasksPositions($taskTree); // [ tid1=>[x,y], ... ]

        $divHeight = 0;
        foreach ($tasksPosition as $k=>$v)
        {
            if($v['y'] > $divHeight) $divHeight = $v['y'];
        }
        $divHeight += 250;
        $code = "<div style='direction:ltr; width:100%; height:".$divHeight."px; overflow:auto; position:relative;padding:5px;'>";

        foreach ($tasks as $task)
        {
            $param = \app\components\CodeGeneration::getTaskParams($task, $sitexTasks,$options, $rules, $access);
            //res: [taskId, task, type, done, value, modifier, date, permit, access, titleHint ]
            $x = 0; $y = 0;
            if(isset($tasksPosition[$param['taskId']]))
            {
                $x = $tasksPosition[$param['taskId']]['x'];
                $y = $tasksPosition[$param['taskId']]['y'];
            }

            $code .= \app\components\TaskWidget::widget(['params'=>$param, 'x'=>$x, 'y'=>$y,'pSitex_id'=>$pSitex_id ]);
        }
        // draw node & links
        // [  [x1, y1, x2, y2] , ...   ]
        $links = \app\components\CodeGeneration::getTasksLinks($taskTree, $tasksPosition);
        $height = 0;
        $width = 0;
        foreach($links as $link)
        {
            if($width < $link[0]) $width = $link[0];
            if($width < $link[2]) $width = $link[2];
            if($height < $link[1]) $height = $link[1];
            if($height < $link[3]) $height = $link[3];

            $top1 = $link[1].'px';
            $left1 = $link[0].'px';
            $top2 = $link[3].'px';
            $left2 = $link[2].'px';
            $code .= "<div style='width:10px; height:10px;background-color:#17a2b8;border-radius:50%; position:absolute; left:".$left1."; top:".$top1.";'></div>";
            $code .= "<div style='width:10px; height:10px;background-color:#17a2b8;border-radius:50%; position:absolute; left:".$left2."; top:".$top2.";'></div>";
        }

        $code .= "<svg height='".$height."' width='".$width."' style='position:absolute; top:5px; left:5px;'>";
        $code .= "<defs>
                    <marker id='arrowhead' markerWidth='10' markerHeight='7' 
                    refX='10' refY='3.5' orient='auto'>
                      <polygon points='0 0, 10 3.5, 0 7' style='fill:#17a2b8;' />
                    </marker>
                  </defs>";
        foreach($links as $link)
            $code .= "<line x1='".$link[0]."' y1='".$link[1]."' x2='".$link[2]."' y2='".$link[3]."' marker-end='url(#arrowhead)' style='stroke:#17a2b8;stroke-width:2' />";
        $code .= "</svg>";

//        $code .= "<svg style='background-color:gray;' height='100%' width='100%'>
//            <line  x1='1' y1='1' x2='190' y2='190' style='stroke:#17a2b8;stroke-width:2' />
//            </svg>";

        $code .= "</div>";
        return $code;
    }
    private function getTasksPositions0($taskTree)
    {
        // tasktree:   [ id1=>[id2=>[id3=>[]] , id5=>[id6=>[id7=>[]] ]
        // res:  [ tId=>[x, y], ... ]
        $taskWidth = 400;
        $taskHeight = 200;
        $gap = 0;
        $vItem = 0;
        $res = [];
        $rootCounter = -1;

        foreach($taskTree as $root=>$childs)
        {
            $rootCounter++;
            $startY = $vItem * $taskHeight;
            $temp = \app\components\CodeGeneration::getTreePositions($root, $childs, $rootCounter, 0, $startY, $taskWidth, $taskHeight, $gap);
            // [ id=>[x,y], ... ]
            foreach($temp as $k=>$v)
            {
                $res[$k] = $v;
                $vItem++;
            }
        }

        return $res;
    }
    private function getTreePositions0($root, $childs, $rootCounter, $x, $y, $taskWidth, $taskHeight, $gap, $res = [])
    {
        // one tree
        //  [ id1=>[id2=>[ id3=>[] ]] ]
        // res: [ id=>[x,y], ... ]
        $x0 = $gap + $x;
        $x = $x0 + $taskWidth;
        $y0 = $y + $rootCounter*100  + $gap;
        $res[$root] = ['x'=>$x0, 'y'=>$y0];
        if(empty($childs)) return $res;
        foreach($childs as $rt=>$ch)
        {
            //$x = $x + $taskWidth;
            $y = $y + $taskHeight+$gap;
            $res = \app\components\CodeGeneration::getTreePositions($rt, $ch, $rootCounter, $x, $y, $taskWidth, $taskHeight, $gap, $res);
        }
        return $res;
    }
    private function getTasksPositions($taskTree)
    {
        // tasktree:   [ id1=>[id2=>[id3=>[]] , id5=>[id6=>[id7=>[]] ]
        // res:  [ tId=>[x, y], ... ]
        $taskWidth = 400;
        $taskHeight = 200;
        $gap = 50;
        $vItem = 0;
        $res = [];
        $rootCounter = -1;

        foreach($taskTree as $root=>$childs)
        {
            $rootCounter++;
            $startY = 0 + $rootCounter*$gap;
            if(!empty($res))
            {
                foreach($res as $r=>$k)
                    if($k['y'] > $startY)
                        $startY = $k['y'] + $taskHeight + 100;
            }
            $x = $gap;
            $startY = $gap + $startY;

            $temp = \app\components\CodeGeneration::getTreePositions($root, $childs, $rootCounter, $x, $startY, $taskWidth, $taskHeight, $gap);
            // [ id=>[x,y], ... ]
            foreach($temp as $k=>$v)
            {
                $res[$k] = $v;
            }
        }

        return $res;
    }
    private function getTreePositions($root, $childs, $rootCounter, $x, $y, $taskWidth, $taskHeight, $gap, $res = [])
    {
        // one tree
        //  [ id1=>[id2=>[ id3=>[] ]] ]
        // res: [ id=>[x,y], ... ]

        $res[$root] = ['x'=>$x, 'y'=>$y];
        if(empty($childs)) return $res;
        $x = $x + $taskWidth + $gap;
        foreach($childs as $rt=>$ch)
        {
            $res = \app\components\CodeGeneration::getTreePositions($rt, $ch, $rootCounter, $x, $y, $taskWidth, $taskHeight, $gap, $res);
            $y = $y + $taskHeight+$gap;
        }
        return $res;
    }
    private function getCenterPoint($x, $y, $parent=true)
    {
        $taskWidth = 400;
        $taskHeight = 200;
        $y = $y + ($taskHeight / 2);
        if($parent)//left
        {
            $x = $x + $taskWidth - 2;
        }
        else
        {
            $x -= 10;
        }
        return [$x,$y];
    }
    private function getTasksLinks($taskTree, $tasksPosition)
    {
        $links = [];
        foreach($taskTree as $root=>$childs)
        {
            $links = \app\components\CodeGeneration::getTaskLink($tasksPosition, $root, $childs, $links);
        }

        return $links;
    }
    private function getTaskLink($tasksPosition, $root, $childs, $links = [])
    {
        // root: taskid  childs: [  tid1=>[] , tid2==>[]  ]
        $x1 = $tasksPosition[$root]['x'];
        $y1 = $tasksPosition[$root]['y'];
        $points = \app\components\CodeGeneration::getCenterPoint($x1, $y1, true);
        $x1 = $points[0];
        $y1 = $points[1];
        foreach($childs as $r=>$ch)
        {
            $x2 = $tasksPosition[$r]['x'];
            $y2 = $tasksPosition[$r]['y'];
            $points = \app\components\CodeGeneration::getCenterPoint($x2, $y2, false);
            $x2 = $points[0];
            $y2 = $points[1];
            $links[] = [$x1, $y1, $x2, $y2];
        }

        foreach($childs as $r=>$c)
            $links = \app\components\CodeGeneration::getTasklink($tasksPosition, $r, $c, $links);

        return $links;
    }

}
?>