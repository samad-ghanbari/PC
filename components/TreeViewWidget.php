<?php
namespace app\components;
use yii\base\Widget;

class TreeViewWidget extends Widget
{
    public $model, $id, $rtl, $editUrl; // [ [ info=>[], child=>[ [info=>[], child=>[...]] , [] ] ] ,  [] ,  [] ... ]

    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("TreeViewView", ["model"=>$this->model, 'id'=>$this->id , 'rtl'=>$this->rtl, 'editUrl'=>$this->editUrl]);
    }
}
?>
