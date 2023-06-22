<?php
namespace app\components;
use Yii;
use yii\base\Component;

class Methods extends Component
{

    public function getPsitexDedicationArray($psitex_id, $cat=false)
    {
        //       [      sdid=>[quantity, description],      ]
        // cat:  [      pdid=>[ sdid=>[quantity, description] ] ,     ]
        $sd = \app\models\ProjectSitexDedication::find()->where(['project_sitex_id'=>$psitex_id])->asArray()->all();
        $res = [];
        foreach ($sd as $d)
        {
            if($cat)
                $res[$d['project_dedication_id']][$d['id']]= ['quantity'=>$d['quantity'], 'description'=>$d['description']];
            else
                $res[$d['id']]= ['quantity'=>$d['quantity'], 'description'=>$d['description']];
        }

        return $res;
    }
    public function getProjectDedicationArray($pe_id, $cat=false)
    {
        //        [      pdid=>[area, quantity, description ],     ]
        // cat:   [      peid=>[pdid=>[area, quantity, description ]],       ]
        $pd = \app\models\ProjectDedication::find()->where(['project_equipment_id'=>$pe_id])->asArray()->all();
        $res = [];
        foreach ($pd as $d)
        {
            if($cat)
                $res[$d['project_equipment_id']][$d['id']]= ['area'=>$d['area'], 'quantity'=>$d['quantity'], 'description'=>$d['description']];
            else
                $res[$d['id']]= ['area'=>$d['area'], 'quantity'=>$d['quantity'], 'description'=>$d['description']];
        }

        return $res;
    }
    public function getProjectEquipmentArray($project_id, $cat=false)
    {
        //        [      peid=>[ quantity, description ],     ]
        // cat:   [      eid=>[peid=>[quantity, description ]],       ]
        $pe = \app\models\ProjectEquipments::find()->where(['project_id'=>$project_id])->asArray()->all();
        $res = [];
        foreach ($pe as $e)
        {
            if($cat)
                $res[$e['equipment_id']][$e['id']]= ['quantity'=>$e['quantity'], 'description'=>$e['description']];
            else
                $res[$e['id']]= ['quantity'=>$e['quantity'], 'description'=>$e['description']];
        }
        return $res;
    }
    public function getEquipments($project_id = -1)
    {
        $res=[];
        if($project_id == -1)
        {
            //all equipments
            $e = \app\models\BaseEquipments::find()->asArray()->all();
            foreach ($e as $eq)
                $res[$eq['id']] = ['equipment'=>$eq['equipment'], 'description'=>$eq['description']];
        }
        else
        {
            //equipments of project
            $pe_ids = \app\models\ProjectEquipments::find()->select("equipment_id")->where(['project_id'=>$project_id]);
            $e = \app\models\BaseEquipments::find()->where(['id'=>$pe_ids])->asArray()->all();
            foreach ($e as $eq)
                $res[$eq['id']] = ['equipment'=>$eq['equipment'], 'description'=>$eq['description']];
        }

        return $res;
    }
    public function getAvailableEquipments($project_id, $area)
    {
        $res=[];
        $pe_ids = \app\models\ProjectEquipments::find()->select("id")->where(['project_id'=>$project_id]);

        $pd_ids = \app\models\ProjectDedication::find()->select("id")->where(['area'=>$area, 'project_equipment_id'=>$pe_ids]);
        $sd = \app\models\ProjectSitexDedication::find()->select("project_dedication_id, SUM(quantity) as sum")
                ->where(['project_dedication_id'=>$pd_ids])->groupBy("project_dedication_id")->asArray()->all();

        $used = [];
        foreach ($sd as $s)
            $used[$s['project_dedication_id']] = $s['sum'];
        $pd = \app\models\ProjectDedication::find()->where(['area'=>$area, 'project_equipment_id'=>$pe_ids])->asArray()->all();
        $pe_ids2 = [];
        foreach ($pd as $d)
        {
            $u = 0;
            if(isset($used[$d['id']]))
                $u = $used[$d['id']];
            $diff = $d['quantity'] - $u;
            if($diff > 0)
                $pe_ids2[] = $d['project_equipment_id'];
        }

        $e_ids = \app\models\ProjectEquipments::find()->select("equipment_id")->where(['project_id'=>$project_id, 'id'=>$pe_ids2]);
        $e = \app\models\BaseEquipments::find()->where(['id'=>$e_ids])->asArray()->all();
        foreach ($e as $eq)
            $res[$eq['id']] = ['equipment'=>$eq['equipment'], 'description'=>$eq['description']];

        return $res;
    }

    //date
    private function dateToGregorian($jalali) // 1400/02/01
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $jalali = str_replace($persian, $num, $jalali);
        $array = explode("/",$jalali);
        $y = intval($array[0]);
        $m = intval($array[1]);
        $d = intval($array[2]);
        $date = \app\components\Jdf::jalali_to_gregorian($y,$m,$d);
        $date = $date[0].'/'.$date[1].'/'.$date[2];
        return $date;
    }
    public function jalaliToUnix($time_string, $end = false)
    { //۱۳۹۹/۱۰/۱
        $time_string = str_replace(['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], $time_string);
        $ymd = explode('/', $time_string);
        $year = intval($ymd[0]);
        $month = intval($ymd[1]);
        $day = intval($ymd[2]);

        $ymd = \app\components\Jdf::jalali_to_gregorian($year, $month, $day);
        $year = intval($ymd[0]);
        $month = intval($ymd[1]);
        $day = intval($ymd[2]);

        if($end)
            $ts = mktime(23, 59,59,$month, $day,$year);
        else
            $ts = mktime(0, 1,1,$month, $day,$year);

        return $ts;
    }

    //chart
    public function getTasksTreeArray($project_id)
    {
        //res: [   2=>[4=>[5=>[], 6=>[] ]] ,  8=>[]   ]
        $res = [];
        $roots = \app\components\Methods::getRootItems($project_id);
        foreach($roots as $root)
            $res[$root] = \app\components\Methods::getTaskTreeArray($root);
        return $res;
    }
    public function getTaskTreeArray($root, $res=[])
    {
        //one root tree array
        //res: [   2=>[4=>[5=>[], 6=>[] ]]]
        $childs = \app\components\Methods::getTaskChilds($root);
        foreach ($childs as $child)
            $res[$child] = \app\components\Methods::getTaskTreeArray($child, []);

        return $res;
    }
    public function getTaskAllParents($task_id, $startToEnd=true)
    {
        // [4=>[ id1=>[] ], 1=>[id2=>[ 100=>[]] ], 2=>[id3=>[], id4=>[]], ... ]
        $items = \app\components\Methods::getTaskParent($task_id); // [4,5,6]
        if(empty($items)) return [];
        $res = [];
        foreach($items as $item)
        {
            if($item == $task_id) continue;
            $res[$item] = \app\components\Methods::getTaskAllParents($item, $startToEnd);
        }
        if($startToEnd)
            $res = \app\components\Methods::reverseAssociativeArray($res);

        return $res;
    }
    public function getTaskParent($task_id)
    {
        // return [id] or parallel dependence [id1, id2, id3,...]
        $ids = \app\models\ProjectTaskRules::find()->select("depend_task_id")->where(['task_id'=>$task_id])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
        if(empty($ids)) return [];
        $temp = [];
        foreach ($ids as $id)
            $temp[] = $id['depend_task_id']; // [2,3,4]
        $ids = $temp; $temp=[];
        if(sizeof($ids) ==  1) return [$ids[0]];
        //more than one task dependency
        $ids = \app\components\Methods::dropTasksCorrelations($ids);
        return $ids;
    }
    public function getTaskChilds($task_id)
    {
        $res = [];
        $ids = \app\models\ProjectTaskRules::find()->select("task_id")->where(['depend_task_id'=>$task_id])->asArray()->all();
        foreach ($ids as $id)
            if(!in_array($id['task_id'], $res))
                $res[] = $id['task_id'];
        // [2, 3] but 3 depend to 2 >> [2]
        $result =[];
        foreach($res as $task)
        {
            $parents = \app\components\Methods::getTaskParent($task);
            //[x, y, z] , [x]
            $skip = true;
            if(empty($parents)) $skip = false;
            foreach($parents as $p)
            {
                if(!in_array($p, $res))
                {
                    $skip = false;
                    break;
                }
            }
            if(!$skip)
                $result[] = $task;
        }
        return $result;
    }
    public function getRootItems($project_id)
    {
        // res:  [3, 4, 5, 6] task id of root items
        $task_ids = \app\models\ProjectTasks::find()->select("id")->where(['project_id'=>$project_id])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
        $rootItems=[];
        foreach ($task_ids as $tid)
        {
            if(\app\components\Methods::isRootTask($tid['id']))
                $rootItems[] = $tid['id'];
        }
        return $rootItems;
    }
    public function isRootTask($task_id)
    {
        //has parent: exists in rules
        $ids = \app\models\ProjectTaskRules::find()->where(['task_id'=>$task_id])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
        if(empty($ids)) return true; else return false;
    }
    public function dropTasksCorrelations($ids)
    {
        // 5 depends to 1>2>3>4  so 5 depends to 4
        //[1,2,3,4] >> [4]
        // [1=>[], 2=>[1], 3=>[1,2], 4=>[1,2,3]]

        $res = [];
        foreach ($ids as $id)
            $res[$id] = \app\components\Methods::getTaskParent($id);

        $res = \app\components\Methods::dropArrayCorrelations($res);
        return $res;
    }
    public function dropArrayCorrelations($array)
    {
        // array : [   1=>[], 2=>[1], 3=>[4], 4=>[1,2,3], 5=>[1, 2, 6]   ]    res: [4,5] | [3, 5]  ?? cross rel
        // array : [   1=>[], 2=>[1], 3=>[4], 4=>[8], 5=>[1, 2, 6]    ]    res: [3, 5]
        // array : [   1=>[], 2=>[1], 3=>[2], 4=>[3], 5=>[4]    ]             res: [5]
        //
        // array :    [   1=>[], 2=>[1], 3=>[4], 4=>[1,2,3], 5=>[1, 2, 6]   ]
        // fill rel : [   1=>[], 2=>[1], 3=>[1, 2, 4], 4=>[1,2,3], 5=>[1, 2, 6]   ] >>> [3, 5] | [4, 5]

        // array :   [   1=>[], 2=>[1], 3=>[4], 4=>[8], 5=>[1, 2, 6]    ]
        // fill rel: [   1=>[], 2=>[1], 3=>[4, 8], 4=>[8], 5=>[1, 2, 6]    ]   >>> [3,5]

        // array :  [   1=>[], 2=>[1], 3=>[2], 4=>[3], 5=>[4]    ]
        //fill rel: [   1=>[], 2=>[1], 3=>[1,2], 4=>[1, 2, 3], 5=>[1, 2, 3, 4]    ] >>> [5]

        // fill relations : omit repeat and its own key
        // length from 0 to max
        //if key exists in other values  >>> it can drop

        $array = \app\components\Methods::fillRelation($array);
        asort($array);
        $res = $array;
        foreach ($array as $key=>$value)
        {
            foreach ($res as $k=>$v)
            {
                if($key != $k)
                {
                        if(in_array($key, $v))
                        {
                            unset($res[$key]);
                        }
                }
            }
        }
        return array_keys($res);
    }
    public function fillRelation($array)
    {
        //[   1=>[], 2=>[1], 3=>[2], 4=>[3], 5=>[4]    ]
        //[   1=>[], 2=>[1], 3=>[4], 4=>[1,2,3], 5=>[1, 2, 6]   ]

        $res = [];
        foreach ($array as $key=>$value)
        {
            $res[$key] = $value;

            if(sizeof($value) > 0)
            {
                foreach ($value as $i)
                { // [3, 4 , 5]
                    $arr = \app\components\Methods::itemRelation($i, $array);
                    foreach($arr as $j)
                        if(!in_array($j,$res[$key]))
                            if($j != $key)
                                $res[$key][] = $j;
                }
            }
        }
        return $res;
    }
    public function itemRelation($item, $array, $res=[])
    {
        //res=[] for recursive loop prevention

        // 4, [   1=>[], 2=>[1], 3=>[2], 4=>[3, 6, 7], 5=>[4]    ] >>> [1, 2, 3, 6, 7]
        // 4, [   1=>[], 2=>[3], 3=>[2], 4=>[3, 6, 7], 5=>[4]    ] >>> [ 2, 3, 6, 7]
        if(isset($array[$item]))
        {
            $arr = $array[$item]; // [3, 6, 7]
            foreach ($arr as $i)
            {
                if(in_array($i, $res)) continue;
                $res[] = $i;
                $a = \app\components\Methods::itemRelation($i, $array, $res);
                foreach ($a as $j)
                {
                    if(in_array($j, $res)) continue;
                    $res[] = $j;
                }

            }
        }

        return $res;
    }


}
?>