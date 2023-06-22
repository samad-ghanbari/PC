<?php
namespace app\components;
use yii\base\Widget;

class CardWidget extends Widget
{
    public $width, $height, $title, $value, $href, $onclick, $attr;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("CardView", ["width"=>$this->width, "height"=>$this->height, "title"=>$this->title, "value"=>$this->value, "href"=>$this->href, "onclick"=>$this->onclick, "attr"=>$this->attr]);
    }
}
?>
