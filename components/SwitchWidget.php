<?php
namespace app\components;
use yii\base\Widget;

class SwitchWidget extends Widget
{
    public $options, $texts, $hrefs, $enabled;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("SwitchView", ["options"=>$this->options, "enabled"=>$this->enabled,
                                            "texts"=>$this->texts, "hrefs"=>$this->hrefs]);
    }
}
?>
