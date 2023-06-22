<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;


class ProjectController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) return $this->redirect(['user/index']);
        else return parent::beforeAction($action);
    }

    public function actionAjax_info()
    {
        if(Yii::$app->request->isAjax)
        {
            $project_id = Yii::$app->request->post("id", -1);
            $project = \app\models\BaseProjects::findOne($project_id);
            $infoArray = [];
            if(!empty($project))
            {
                $infoArray["نام پروژه"] = $project->project_name;
                $infoArray["اداره کل"] = $project->office;
                $infoArray["وضعیت پروژه"] = ($project->enabled) ? 'فعال' : 'غیر فعال';
                $infoArray["امکان مشاهده"] = ($project->visible) ? 'دارد' : 'ندارد';
                $infoArray["وزن پروژه"] = $project->project_weight;
                $infoArray["زمان ثبت پروژه"] = \app\components\Jdf::jdate("Y/m/d", $project->ts);
                $meta = \app\models\BaseProjectMeta::find()->where(["project_id"=>$project_id])->orderBy(["order"=>SORT_ASC])->asArray()->all();
                foreach($meta as $m)
                    $infoArray[$m['key']] = $m['value'];
            }

            return $this->renderAjax("ajax/info", ['infoArray'=>$infoArray]);
        }
    }

    public function getProjectMeta($project_id)
    {
        $infoArray = [];
        $meta = \app\models\BaseProjectMeta::find()->where(["project_id"=>$project_id])->orderBy(["order"=>SORT_ASC])->asArray()->all();
        foreach($meta as $m)
            $infoArray[$m['key']] = $m['value'];

        return $infoArray;
    }

    public function actionIndex($id = -1)
    {
        if($id <= -1) return $this->redirect(['base/home']);
        if(! Yii::$app->user->canViewProject($id)) return $this->redirect(['base/home']);

        $project = \app\models\BaseProjects::findOne($id);
        $projectMeta = $this->getProjectMeta($id);

        return $this->render("index", ['project'=>$project, 'projectMeta'=>$projectMeta]);
    }

    public function actionDetails($id)
    {
        if(! Yii::$app->user->canViewProject($id)) return $this->redirect(['base/home']);

        $qp = yii::$app->request->queryParams;
        if(isset($qp['viewMode'])) //table widget
        {
            $vm = $qp['viewMode'];
            if($vm == "table")
                Yii::$app->session->set('viewMode', "table");
            else
                Yii::$app->session->set('viewMode', "widget");
        }

        $project = \app\models\BaseProjects::findOne($id);

        // project sitex
        $cond = Yii::$app->user->getCentersAccessCondition($id);
        $COND = [];
        if(sizeof($cond) > 1)
        {
            $COND = ['or'];

            foreach ($cond as $c)
            {
                $COND[] = $c;
            }
        }
        else if(sizeof($cond) == 1)
            $COND = $cond[0];

        $searchModel = new \app\models\ProjectSitexViewSearch();
        $dataProvider = $searchModel->search($qp);
        $dataProvider->query->andWhere(['project_id'=>$id]);
        $dataProvider->query->andWhere($COND);
        $dataProvider->query->orderBy("area, name");

        $dataProvider->pagination->pageSize = 25;
        $totalCount = $dataProvider->getTotalCount();
        $pages=new Pagination(['totalCount'=>($totalCount)]);
        $pages->pageSize=25;
        $pages->pageSizeParam=false;

        return $this->render('detail', ['project'=>$project, 'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);

    }

    public function actionSitex_ajax_info()
    {
        if(Yii::$app->request->isAjax)
        {
            $ps_id = Yii::$app->request->post("id", -1);// project_sitex_id
            $sitex = \app\models\ProjectSitexView::findOne($ps_id);
            $projectWeight = \app\models\BaseProjects::find()->select("project_weight")->where(['id'=>$sitex->project_id])->scalar();
            $infoArray = [];
            if(!empty($sitex))
            {
                $infoArray["منطقه"] = $sitex->area;
                $infoArray["نام مرکز/سایت"] = $sitex->name;
                $infoArray["اختصار"] = $sitex->abbr;
                $infoArray["مرکز / سایت"] = $sitex->type;
                if(!empty($sitex->center_name))
                    $infoArray["نام مرکز اصلی"] = $sitex->center_name;
                if(!empty($sitex->center_abbr))
                    $infoArray["اختصار مرکز اصلی"] = $sitex->center_abbr;
                $infoArray["آدرس"] = $sitex->address;
                $infoArray["اتمام کار"] = ($sitex->done)? "<i class='fa fa-check text-success'></i>"  : "<i class='fa fa-times text-danger'></i>";
                $infoArray["فاز"] = $sitex->phase;
                $infoArray["ضریب پیشرفت"] = \app\components\ProgressWidget::widget(['width'=>"100%", "height"=>"20px", "percentage"=>round(100*($sitex->weight/$projectWeight), 1)]);

                $meta = \app\models\ProjectSitexMeta::find()->where(["project_sitex_id"=>$ps_id])->orderBy(["order"=>SORT_ASC])->asArray()->all();
                foreach($meta as $m)
                    if(!empty($m['value']))
                        $infoArray[$m['key']] = $m['value'];
            }

            return $this->renderAjax("ajax/info", ['infoArray'=>$infoArray]);
        }
    }

    public function actionSitex_tasks($id = -1)
    {
        if($id <= 0) return $this->redirect(['base/home']);

        $pSitex = \app\models\ProjectSitexView::find()->where(['id'=>$id])->one();
        $project = \app\models\BaseProjects::find()->where(['id'=>$pSitex->project_id])->one();

        $center_id = $pSitex->center_id;
        if($pSitex->type == "مرکز")
            $center_id = $pSitex->id;
        if(! Yii::$app->user->canAccessCenter($center_id, $project->id)) return $this->redirect(['base/home']);

        $qp = yii::$app->request->queryParams;
        if(isset($qp['viewMode'])) //table widget
        {
            $vm = $qp['viewMode'];
            if($vm == "table")
                Yii::$app->session->set('viewMode', "table");
            else
                Yii::$app->session->set('viewMode', "widget");
        }
        $chart = Yii::$app->session->get('chart', 0);
        if(isset($qp['chart'])) //0 1
        {
            $chart = $qp['chart'];
            $chart = intval($chart);
            Yii::$app->session->set('chart', $chart);
        }

        $pSitexMeta = \app\models\ProjectSitexMeta::find()->where(['project_sitex_id'=>$pSitex->id])->orderBy(['order'=>SORT_ASC])->all();

        //lom
        // equip[e] prj-equip[pe] prj-dedication[pd] sitex-dedication[sd]
        $sDedication = \app\models\ProjectSitexDedication::find()->where(['project_sitex_id'=>$pSitex->id])->asArray()->all();
        $ids = [];
        foreach ($sDedication as $d)
            $ids[] = $d['project_dedication_id'];
        $pDedication = \app\models\ProjectDedication::find()->where(['id'=>$ids])->asArray()->all();
        $ids = [];
        foreach($pDedication as $e)
            $ids[] = $e['project_equipment_id'];
        $pEquipment = \app\models\ProjectEquipments::find()->where(['id'=>$ids])->asArray()->all();
        $ids = [];
        foreach ($pEquipment as $e) $ids[] = $e['equipment_id'];
        $equipments = \app\models\BaseEquipments::find()->where(['id'=>$ids])->asArray()->all();
        $lom = ['e'=>$equipments, 'pe'=>$pEquipment, 'pd'=>$pDedication,'sd'=>$sDedication ];

        //tasks
        $sitexTasks = \app\models\ProjectSitexTasksView::find()->where(['project_sitex_id'=>$pSitex->id])->asArray()->all();
        $temp = [];
        foreach ($sitexTasks as $st)
            $temp[$st['task_id']] = $st;
        $sitexTasks = $temp;
        $temp = [];

        $projectTasks = \app\models\ProjectTasks::find()->where(['project_id'=>$project->id])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
        $ids = [];
        foreach ($projectTasks as $pt)
            $ids[] = $pt['id'];

        $taskOptions = \app\models\ProjectTaskOptions::find()->where(['task_id'=>$ids])->asArray()->all();
        foreach ($taskOptions as $to)
        {
            $temp[$to['id']] = $to;
        }
        $taskOptions = $temp;
        $temp = [];

        $tasksRules = $this->getTasksRules($id,$ids);

        $access = Yii::$app->user->getAccessArray();

        $taskTree =[];
        if($chart)
            $taskTree = \app\components\Methods::getTasksTreeArray($project->id);

        return $this->render("sitexTasks/index", ['pSitex'=>$pSitex, 'pSitexMeta'=>$pSitexMeta, 'project'=>$project, 'lom'=>$lom, 'access'=>$access,
            'tasks'=>$projectTasks, 'options'=>$taskOptions, 'sitexTasks'=>$sitexTasks, 'rules'=>$tasksRules, 'taskTree'=>$taskTree]);
    }

    private function getTaskRules($pSitex_id, $task_id)
    {
        $result = [];// [ permit=>false , desc=>"", need=>[task1=>["option_id=1"], ...] ]

        $rules = \app\models\ProjectTaskRules::find()->where(['task_id'=>$task_id])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
        try
        {
            $result = $this->getRulesArray($pSitex_id, $rules);
        }
        catch (\Exception $e)
        {
            $result = ['permit'=>false, 'desc'=>"خطا در هنگام آنالیز روابط بین فعالیت‌ها", 'need'=>[]];
        }
        return $result;
    }

    private function getTasksRules($pSitex_id, $task_ids)
    {
        $result = [];// [ taskId=>[ permit=>false , desc=>"", need=>[task1=>["option_id=1"], task2=>"value not null"] ] ,  ...]
        foreach ($task_ids as $tid)
        {
            $rules = \app\models\ProjectTaskRules::find()->where(['task_id'=>$tid])->orderBy(['priority'=>SORT_ASC])->asArray()->all();
            try
            {
                $result[$tid] = $this->getRulesArray($pSitex_id, $rules);
            }
            catch (\Exception $e)
            {
                $result[$tid] = ['permit'=>false, 'desc'=>"خطا در هنگام آنالیز روابط بین فعالیت‌ها", 'need'=>[]];
            }

        }
        return $result;
    }

    private function getRulesArray($pSitex_id, $rules)
    {
        // result [permit=>false, desc=>"..." , need=>[] ]
        // rules : array of [id, task_id, depend_task_id, priority, operator, value]
        $result = ['permit'=>false, 'desc'=>"", 'need'=>[]];
        if(empty($rules))
            return ['permit'=>true, 'desc'=>"", 'need'=>[]];

        $permit = true;
        foreach ($rules as $rule)
        {
            $dep_id = $rule['depend_task_id'];
            $dep_op = $rule['operator'];
            $dep_val = $rule['value'];
            if( ($dep_val == "NULL") || ($dep_val == "null") || ($dep_val == "") )
                $dep_val = null;

            $depTask = \app\models\ProjectTasks::find()->where(['id'=>$dep_id])->one();
            if(empty($depTask)) continue;
            $taskName = $depTask->task;
            $dep_type = $depTask->type;
            //select, text, number, date
            if($dep_type == "select")
            {
                if(!empty($dep_val)) $dep_val = intval($dep_val);

                $check = \app\models\ProjectSitexTasks::find()->where(['project_sitex_id'=>$pSitex_id, 'task_id'=>$dep_id])->andWhere([$dep_op, 'option_id', $dep_val])->one();
                if(empty($check))
                {
                    $permit = false;
                    if(empty($result['desc']))
                        $result['desc'] = $taskName . " پیش‌نیاز این پارامتر می‌باشد. ";
                }

                $result['need'][$dep_id] = ['name'=>$taskName, 'type'=>'select','field'=>"option_id", 'operator'=>$dep_op, 'value'=>$dep_val];
            }
            else if($dep_type == "text")
            {
                $check = \app\models\ProjectSitexTasks::find()->where(['project_sitex_id'=>$pSitex_id, 'task_id'=>$dep_id])->andWhere([$dep_op, 'value', $dep_val])->one();
                if(empty($check))
                {
                    $permit = false;
                    if(empty($result['desc']))
                        $result['desc'] = $taskName . " پیش‌نیاز این پارامتر می‌باشد. ";
                }

                $result['need'][$dep_id] = ['name'=>$taskName,'type'=>'text','field'=>"value", 'operator'=>$dep_op, 'value'=>$dep_val];
            }
            else if($dep_type == "number")
            {
                if(!empty($dep_val)) $dep_val = intval($dep_val);
                $check = \app\models\ProjectSitexTasks::find()->where(['project_sitex_id'=>$pSitex_id, 'task_id'=>$dep_id])->andWhere([$dep_op, 'value', $dep_val])->one();
                if(empty($check))
                {
                    $permit = false;
                    if(empty($result['desc']))
                        $result['desc'] = $taskName . " پیش‌نیاز این پارامتر می‌باشد. ";
                }

                $result['need'][$dep_id] = ['name'=>$taskName, 'type'=>'number', 'field'=>"value", 'operator'=>$dep_op, 'value'=>$dep_val];
            }
            else if($dep_type == "date")
            {
                if(!empty($dep_val))
                    $dep_val = intval($dep_val);
                $check = \app\models\ProjectSitexTasks::find()->where(['project_sitex_id'=>$pSitex_id, 'task_id'=>$dep_id])->andWhere([$dep_op, 'value', $dep_val])->one();
                if(empty($check))
                {
                    $permit = false;
                    if(empty($result['desc']))
                        $result['desc'] = $taskName . " پیش‌نیاز این پارامتر می‌باشد. ";
                }

                $result['need'][$dep_id] = ['name'=>$taskName, 'type'=>'date', 'field'=>"value", 'operator'=>$dep_op, 'value'=>$dep_val];
            }
        }

        $result['permit'] = $permit;

        return $result;
    }

    private function getTasksHierarchy($pSitex_id, $task_ids)
    {
        //[    root1=>[] , root2=>[]    ]
        // root1 => [  info , child   ]
        // child=>[ info , child]
        $res = [];
        foreach ($task_ids as $task_id)
        {

        }

    }

    public function actionSitex_update($psid = -1)
    {
        if($psid == -1) return $this->redirect(['base/home']);
        if(Yii::$app->user->can("Update Sitex"))
        {
            $pSitex = \app\models\ProjectSitex::findOne($psid);
            $sitex = \app\models\BaseSitex::findOne($pSitex->sitex_id);
            if(Yii::$app->request->isPost)
            {
                if($sitex->load(Yii::$app->request->post()))
                {
                    if($sitex->type == "مرکز") $sitex->center_id = null;
                    if($sitex->type == "سایت")
                    {
                        if(($sitex->center_id == null) || ($sitex->center_id == -1) )
                            Yii::$app->session->setFlash("warning", "مرکز اصلی مشخص نگردیده است.");
                        else
                        {
                            try
                            {
                                $sitex->update(false);
                            }
                            catch (\Exception $e)
                            {
                                Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه گردید.");
                            }
                            return $this->redirect(['project/sitex_tasks?id='.$psid]);
                        }
                    }
                    else
                    {
                        try
                        {
                            $sitex->update(false);
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه گردید.");
                        }

                        return $this->redirect(['project/sitex_tasks?id='.$psid]);
                    }
                }
            }
            $project = \app\models\BaseProjects::findOne($pSitex->project_id);
            $areas = Yii::$app->user->getAreaList($project->id, true);
            $centers = Yii::$app->user->getCenterList($project->id, true);
            return $this->render("sitexUpdate", ['sitex'=>$sitex, 'project'=>$project, 'areas'=>$areas, 'centers'=>$centers, 'psid'=>$psid]);
        }

        Yii::$app->session->setFlash("error", "شما مجوز لازم جهت ویرایش اطلاعات مرکز/سایت را ندارید.");
        return $this->redirect(['project/sitex_tasks?id='.$psid]);
    }

    public function actionPsitex_ajax_field()
    {
        if(Yii::$app->request->isAjax)
        {
            $psitex_id = Yii::$app->request->post("psitex_id", -1);// project_sitex_id
            $field = Yii::$app->request->post("field", "");// field: phase, meta
            $meta_id = Yii::$app->request->post("meta_id", -1);// meta-id
            if( ($psitex_id == -1) || ($field == "") ) return;
            if( ($field == "meta") && ($meta_id == -1) ) return;

            $psitex = \app\models\ProjectSitexView::findOne($psitex_id);
            $sitex = \app\models\BaseSitex::findOne($psitex->sitex_id);

            // field phase-meta
            if($field == "phase")
                return $this->renderAjax("ajax/pSitexForm/phase", ['psitex'=>$psitex, 'sitex'=>$sitex]);
            else if($field == "meta")
            {
                $sitexMeta = \app\models\ProjectSitexMeta::findOne($meta_id);
                return $this->renderAjax("ajax/pSitexForm/meta", ['psitex'=>$psitex, 'sitex'=>$sitex, 'sitexMeta'=>$sitexMeta]);
            }
        }
    }

    public function actionPsitex_update_phase()
    {
        if(Yii::$app->request->isPost)
        {
            $psid = Yii::$app->request->post('psid', -1);
            $phase = Yii::$app->request->post('phase', -1);
            if($psid > 0)
            {
                $psitex = \app\models\ProjectSitex::findOne($psid);
                $psitex->phase = $phase;
                try
                {
                    $psitex->update(false);
                }
                catch (\Exception $e)
                {
                    Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه گردید.");
                }


                return $this->redirect(['project/sitex_tasks?id='.$psid]);
            }
        }

        return $this->redirect(['base/home']);
    }

    public function actionPsitex_update_meta()
    {
        if(Yii::$app->request->isPost)
        {
            $mid = Yii::$app->request->post('meta-id', -1);
            $psid = Yii::$app->request->post('psid', -1);
            $mval = Yii::$app->request->post('meta-value', "");
            if( ($mid > 0) && ($psid > 0) )
            {
                $meta = \app\models\ProjectSitexMeta::findOne($mid);
                $meta->value = $mval;
                try
                {
                    $meta->update(false);
                }
                catch (\Exception $e)
                {
                    Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه گردید.");
                }


                return $this->redirect(['project/sitex_tasks?id='.$psid]);
            }
        }

        return $this->redirect(['base/home']);
    }

    public function actionPsitex_ajax_ded()
    {
        if(Yii::$app->request->isAjax)
        {
            $sd_id = Yii::$app->request->post("sd_id", -1);// project_sitex_dedication_id
            if($sd_id == -1) return;
            $sd = \app\models\ProjectSitexDedication::findOne($sd_id);
            if(empty($sd)) return;
            $psitex = \app\models\ProjectSitexView::findOne($sd->project_sitex_id);
            $pd = \app\models\ProjectDedication::findOne($sd->project_dedication_id);
            $pe = \app\models\ProjectEquipments::findOne($pd->project_equipment_id);
            $e = \app\models\BaseEquipments::findOne($pe->equipment_id);
            if(empty($psitex) || empty($pd) || empty($pe) || empty($e) ) return;

            return $this->renderAjax("ajax/pSitexForm/editSD", ['psitex'=>$psitex, 'e'=>$e, 'pe'=>$pe, 'pd'=>$pd, 'sd'=>$sd]);

        }
    }

    public function actionPsitex_update_sd()
    {
        if(Yii::$app->request->isPost)
        {
            $sdid = Yii::$app->request->post('sd-id', -1);
            $psid = Yii::$app->request->post('psid', -1);
            $quantity = Yii::$app->request->post('sd-quantity', -1);
            $desc = Yii::$app->request->post('sd-desc', "");
            if( ($sdid == -1) || ($psid == -1) ) return $this->redirect(['base/home']);
            $sd = \app\models\ProjectSitexDedication::findOne($sdid);
            if($quantity > 0)
            {
                $sd->quantity = $quantity;
                $sd->description = $desc;
                try
                {
                    $sd->update(false);
                }
                catch (\Exception $e)
                {
                    Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه گردید.");
                }


            }
            else if($quantity == 0)
                $sd->delete();

            else
                Yii::$app->session->setFlash("warning", "مقدار تخصیص یافته نامعتبر می‌باشد.");

            return $this->redirect(['project/sitex_tasks?id='.$psid]);
        }

        return $this->redirect(['base/home']);
    }
    //sitex dedication
    public function actionPsitex_ajax_add_sd()
    {
        if(Yii::$app->request->isAjax)
        {
            $psid = Yii::$app->request->post("psid", -1);// project_sitex_id

            if ($psid == -1) return;
            $psitex = \app\models\ProjectSitexView::findOne($psid);
            $e = \app\components\Methods::getAvailableEquipments($psitex->project_id, $psitex->area);
            $array = [];
            $e_desc = [];
            $eids = [];
            foreach ($e as $id=>$eq)
            {
                $array[$id] = $eq['equipment'];
                $e_desc[$id] = $eq['description'];
                $eids[] = $id;
            }
            $e = $array; $array = [];
            //pe
            $pe = \app\models\ProjectEquipments::find()->where(['equipment_id'=>$eids])->asArray()->all();
            $pe_desc = [];
            foreach ($pe as $PE)
            {
                $array[$PE['id']] = $e[$PE['equipment_id']]." - ".$e_desc[$PE['equipment_id']];
                $pe_desc[$PE['id']] = $PE['description']." تعداد ".$PE['quantity']. " قلم.";
            }
            $pe = $array; $array=[];

            // pd
            $pd = \app\models\ProjectDedication::find()->where(['project_equipment_id'=>$eids])->asArray()->all();
            $pd_desc = [];
            foreach ($pd as $d)
            {
                $array[$d['project_equipment_id']][$d['id']] = "تخصیص تعداد ".$d['quantity']." قلم به منطقه ".$d['area'];
                $pd_desc[$d['id']] = $d['description'];
            }
            $pd = $array; $array = [];

            $sd = new \app\models\ProjectSitexDedication();
            $sd->project_sitex_id = $psitex->id;

            return $this->renderAjax("ajax/pSitexForm/addSD", ['psitex'=>$psitex, 'pe'=>$pe, 'pe_desc'=>$pe_desc, 'pd'=>$pd, 'pd_desc'=>$pd_desc, 'sd'=>$sd]);
        }
    }
    public function actionPsitex_add_sd()
    {
        if(Yii::$app->request->isPost)
        {
            $ps_id = Yii::$app->request->post('psitex-id', -1);
            $pd_id = Yii::$app->request->post('pdCB', -1);
            $sd_quantity = Yii::$app->request->post('sd-quantity', -1);
            $sd_desc = Yii::$app->request->post('sd-desc', null);
            if( ($ps_id == -1) || ($pd_id == -1) ) return $this->redirect(['base/home']);
            if($sd_quantity == 0) return $this->redirect(['project/sitex_tasks?id='.$ps_id]);
            if($sd_quantity < 0)
            {
                Yii::$app->session->setFlash("warning", "مقدار تخصیص یافته نامعتبر می‌باشد.");
                return $this->redirect(['project/sitex_tasks?id='.$ps_id]);
            }

            $sd = new \app\models\ProjectSitexDedication();
            $sd->project_sitex_id = $ps_id;
            $sd->project_dedication_id = $pd_id;
            $sd->quantity = $sd_quantity;
            $sd->description = $sd_desc;
            try
            {
                $sd->save(false);
            }
            catch (\Exception $e)
            {
                Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه گردید.");
            }

            return $this->redirect(['project/sitex_tasks?id='.$ps_id]);
        }

        return $this->redirect(['base/home']);
    }
    // sitex task
    public function actionPsitex_ajax_task()
    {
        if(Yii::$app->request->isAjax)
        {
            $pSitex_id = Yii::$app->request->post("pSitex_id", -1);// project_sitex_id
            $task_id = Yii::$app->request->post("task_id", -1);// task_id
            if ( ($pSitex_id == -1) || ($task_id == -1) ) return;

            $pSitex = \app\models\ProjectSitexView::findOne($pSitex_id);
            $task = \app\models\ProjectTasks::findOne($task_id);
            $pSitex_task = \app\models\ProjectSitexTasks::find()->where(['project_sitex_id'=>$pSitex_id, 'task_id'=>$task_id])->one();
            $options = [];
            if($task->type == 'select')
                $options = $this->getTaskOptions($task_id); // options, info

            if(empty($pSitex_task))
            {
                $pSitex_task = new \app\models\ProjectSitexTasks();
                $pSitex_task->project_sitex_id = $pSitex_id;
                $pSitex_task->task_id = $task_id;
                if($task->type == 'select')
                    $pSitex_task->option_id = $this->getTaskDefaultOption($task_id);
            }
            else
            {
                if($task->type == 'select')
                    if($pSitex_task->option_id == null)
                        $pSitex_task->option_id = $this->getTaskDefaultOption($task_id);
            }

            $rules = $this->getTaskRules($pSitex_id, $task_id);

            return $this->renderAjax("ajax/pSitexForm/pSitexUpdateTask", ['pSitex'=>$pSitex, 'task'=>$task, 'pSitex_task'=>$pSitex_task, 'options'=>$options]);
        }
    }

    private function getTaskOptions($task_id)
    {
        // [      options=>[ op_id=>option, ... ]  ,   info=>[ op_id=>[done, default ] , ...]       ]

        $res = [];
        $options = \app\models\ProjectTaskOptions::find()->where(['task_id'=>$task_id])->all();
        foreach ($options as $op)
        {
            $res['options'][$op->id]  = $op->option;
            $res['info'][$op->id]  = ['done'=>$op->done_option, 'default'=>$op->default_option];
        }
        return $res;
    }

    private function getTaskDefaultOption($task_id)
    {
        $id = \app\models\ProjectTaskOptions::find()->select('id')->where(['task_id'=>$task_id, 'default_option'=>true])->scalar();
        if($id > 0)
            return $id;
        else
            return -1;
    }

    public function actionPsitex_update_task()
    {
        if(Yii::$app->request->isPost)
        {
            $psitex_task_id = Yii::$app->request->post('psitex-task-id', -1);
            $psitex_id = Yii::$app->request->post('psitex-id', -1);
            $task_id = Yii::$app->request->post('task-id', -1);
            $option_id = Yii::$app->request->post('option_id', -1);
            $task_value = Yii::$app->request->post('task-value', -1);

            if( ($psitex_id == -1) || ($task_id == -1) ) return $this->redirect(['base/home']);

            $task = \app\models\ProjectTasks::findOne($task_id);

            if($psitex_task_id > 0)
            {
                //update
                $ps_task = \app\models\ProjectSitexTasks::find()
                    ->where(['id'=>$psitex_task_id, 'project_sitex_id'=>$psitex_id, 'task_id'=>$task_id])->one();

                if(!empty($ps_task))
                {
                    //update operation
                    $ps_task->ts = time();
                    $ps_task->modifier_id = Yii::$app->user->getId();
                    if($task->type == "select")
                    {
                        if($option_id > 0)
                            $ps_task->option_id = $option_id;
                        else
                        {
                            Yii::$app->session->setFlash("error", "گزینه انتخابی نامعتبر می‌باشد.");
                            return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                        }

                        $ps_task->value = null;
                    }
                    else if($task->type == "date")
                    {
                        // jalali to gerigorian and to ts
                        if(str_contains($task_value, '/'))
                        {
                            $ps_task->value = \app\components\Methods::jalaliToUnix($task_value);
                            $ps_task->value = strval($ps_task->value);
                            $ps_task->option_id = null;
                        }
                        else
                        {
                            Yii::$app->session->setFlash("error", "تاریخ ورودی نامعتبر می‌باشد.");
                            return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                        }
                    }
                    else
                    {
                        //text | number
                        $ps_task->value = strval($task_value);
                        $ps_task->option_id = null;
                    }

                    try
                    {
                        $ps_task->update(false);
                        return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                    }
                    catch (\Exception $e)
                    {
                        Yii::$app->session->setFlash("error", "بروزرسانی اطلاعات با خطا مواجه شد.");
                        return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                    }

                }
                else
                {
                    Yii::$app->session->setFlash("error", "عدم تطابق اطلاعات ارسالی وجود دارد.");
                    return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                }
            }
            else
            {
                //new record
                $ps_task = new \app\models\ProjectSitexTasks();
                $ps_task->project_sitex_id = $psitex_id;
                $ps_task->task_id = $task_id;
                $ps_task->ts = time();
                $ps_task->modifier_id = Yii::$app->user->getId();
                if($task->type == "select")
                {
                    if($option_id > 0)
                        $ps_task->option_id = $option_id;
                    else
                    {
                        Yii::$app->session->setFlash("error", "گزینه انتخابی نامعتبر می‌باشد.");
                        return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                    }

                    $ps_task->value = null;
                }
                else if($task->type == "date")
                {
                    // jalali to gerigorian and to ts
                    if(str_contains($task_value, '/'))
                    {
                        $ps_task->value = \app\components\Methods::jalaliToUnix($task_value);
                        $ps_task->value = strval($ps_task->value);
                        $ps_task->option_id = null;
                    }
                    else
                    {
                        Yii::$app->session->setFlash("error", "تاریخ ورودی نامعتبر می‌باشد.");
                        return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                    }
                }
                else
                {
                    //text | number
                    $ps_task->value = strval($task_value);
                    $ps_task->option_id = null;
                }

                try
                {
                    $ps_task->save(false);
                    return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                }
                catch (\Exception $e)
                {
                    Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه شد.");
                    return $this->redirect(['project/sitex_tasks?id='.$psitex_id]);
                }
            }
        }

        return $this->redirect(['base/home']);
    }




}