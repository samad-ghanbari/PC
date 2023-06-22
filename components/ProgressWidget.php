<?php
namespace app\components;
use yii\base\Widget;

class ProgressWidget extends Widget
{
    public $width, $height, $percentage;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("ProgressView", ["width"=>$this->width, "height"=>$this->height, "percentage"=>$this->percentage]);
    }
}
?>
