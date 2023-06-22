<?php
namespace app\components;
use Yii;
use yii\base\Component;

class TreeViewModelGenerate extends Component
{
    public static function model4Layer($e, $pe, $pd, $sd)
    {
        // arrays:  equipment  projectEquipment projectDedication siteDedication
        // e[]  id equipment description
        // pe[] id project_id equipment_id quantity description
        // pd[] id project_equipment_id area quantity description
        // sd[] id project_sitex_id project_dedication_id  quantity description

        $model = []; // [ [info=>[id, value,desc, href], child=>[] ] , []  ]

        foreach ($e as $eInfo)
        {
            $eId = $eInfo['id'];
            $eChild = [];
            //e childs
            foreach ($pe as $peInfo)
            {
                $peId = $peInfo['id'];
                if($eId == $peInfo['equipment_id'])
                {
                    $peChild = [];
                    //pe childs
                    foreach ($pd as $pdInfo)
                    {
                        $pdId = $pdInfo['id'];
                        if($peId == $pdInfo['project_equipment_id'])
                        {
                            $pdChild = [];
                            // pd child
                            foreach($sd as $sdInfo)
                            {
                                $sdId = $sdInfo['id'];
                                if($pdId == $sdInfo['project_dedication_id'])
                                    $pdChild[] = ["info"=>['id'=>$sdInfo['id'], 'value'=>'تخصیص تعداد '.$sdInfo['quantity']." قلم به مرکز/سایت "."<i class='fa fa-edit mr-4' onclick='editSitexDed(this)'></i>", 'desc'=>$sdInfo['description'],'href'=>''] , "child"=>[]];
                            }

                            $peChild[] = ["info"=>['id'=>$pdInfo['id'], 'value'=>'تخصیص تعداد '.$pdInfo['quantity']." قلم به منطقه ".$pdInfo['area'], 'desc'=>$pdInfo['description'], 'href'=>''] , "child"=>$pdChild];
                        }
                    }

                    $eChild[] = ["info"=>['id'=>$peInfo['id'], 'value'=>'خرید تعداد '.$peInfo['quantity']." تجهیز در پروژه", 'desc'=>'', 'href'=>''] , "child"=>$peChild];
                }
            }

            $model[] = ["info"=>['id'=>$eInfo['id'], 'value'=>"تجهیز ".$eInfo['equipment'], 'desc'=>$eInfo['description'], 'href'=>""] , "child"=>$eChild];
        }

        return $model;
    }
}
?>