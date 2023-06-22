<?php
namespace app\components;
use yii\base\Widget;

class TaskWidget extends Widget
{
    public $params, $x, $y, $pSitex_id;
    // params [taskId, task, type, done, value, modifier, permit, access, titleHint ]
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("TaskView", ["params"=>$this->params, "x"=>$this->x, "y"=>$this->y, 'pSitex_id'=>$this->pSitex_id]);
    }
}
?>
