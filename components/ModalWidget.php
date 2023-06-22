<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class ModalWidget extends Widget
{
    public $id, $maxWidth, $title, $body, $buttonName, $buttonType ;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("ModalView", ["id"=>$this->id, "maxWidth"=>$this->maxWidth, "title"=>$this->title, "body"=>$this->body, "buttonName"=>$this->buttonName, "buttonType"=>$this->buttonType]);
    }
}
?>
