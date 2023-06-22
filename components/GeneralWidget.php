<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;

class GeneralWidget extends Widget
{
    public $id, $title, $body, $footer, $options;
    //title : text
    //body : key value
    //footer: [
    //          left=>  [icon=>"", modality=> true,  jsFunc=>"edit(1)", link=>"" ] ,
    //          center=>[icon=>"", modality=>false,  jsFunc=>""       , link=>"a/s/f.php" ],
    //          right=> [icon=>"", modality=> true,  jsFunc=>"add(1)" , link=>"" ]
    //        ]
    //options=>[class=>"", style=>""]

    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render("GeneralView", ["id"=>$this->id, "title"=>$this->title, 'body'=>$this->body, 'footer'=>$this->footer, 'options'=>$this->options ]);
    }
}
?>
