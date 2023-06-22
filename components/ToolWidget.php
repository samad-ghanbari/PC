<?php
namespace app\components;
use yii\base\Widget;

class ToolWidget extends Widget
{
    public $width, $height, $iconClass, $title, $url;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("ToolView", ["width"=>$this->width, "height"=>$this->height, "iconClass"=>$this->iconClass, "title"=>$this->title, "url"=>$this->url]);
    }
}
?>
