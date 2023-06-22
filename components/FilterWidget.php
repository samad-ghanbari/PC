<?php
namespace app\components;
use yii\base\Widget;

class FilterWidget extends Widget
{
    public $searchModel, $items;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("FilterView", ["model"=>$this->searchModel, 'items'=>$this->items]);
    }
}
?>
