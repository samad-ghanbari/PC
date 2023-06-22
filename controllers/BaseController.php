<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;


class BaseController extends Controller
{
//    public function behaviors()
//    {
//        return [
//            'access' =>
//                [
//                    'class' => AccessControl::className(),
//                    'rules' =>
//                        [
//                            [
//                                'actions' => ['index', 'login', 'captcha', 'error'],
//                                'allow' => true,
//                                'roles' => ['?'], // guest user
//                            ],
//                            [
//                                'allow' => true,
//                                'roles' => ['@'],// logged in
//                            ]
//                        ],
//                ],
//        ];
//    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $user_ip =  \Yii::$app->request->getUserIP();
        $all_ips = \app\models\UserMeta::find()->select("value")->where(['key'=>"IP"])->distinct()->asArray()->all();
        $array = [];
        foreach ($all_ips as $ip)
            $array[] = $ip['value'];
        if(in_array($user_ip, $array) || in_array("*", $array) )
        {
            return $this->redirect(['user/login']);
        }
        else
        {
            $ts = time();
            $ts = \app\components\Jdf::jdate("Y/m/d - H:i", $ts);
            $type = \app\components\LogTypes::invalid_ip_try;
            $msg = "the system with mentioned IP address tried to log in illegally.";
//            $msg = "کاربر با IP تعیین شده قصد ورود غیرمجاز به سامانه را داشته است.";
            $log = \app\components\Logger::createLog($type, $msg);
            \Yii::info($log, 'LOG');

            $this->layout = "plain";
            return $this->render('invalid_ip', ['user_ip'=>$user_ip, 'ts'=>$ts]);
        }
    }

    public function actionHome()
    {
        if(yii::$app->user->isGuest)
            return $this->redirect(['base/index']);

        $qp = yii::$app->request->queryParams;
        if(isset($qp['viewMode'])) //table widget
        {
            $vm = $qp['viewMode'];
            if($vm == "table")
                Yii::$app->session->set('viewMode', "table");
            else
                Yii::$app->session->set('viewMode', "widget");
        }

        //get projects view=true
        //get ids
        $upIds = \app\models\UserProjects::find()->select("project_id")->where(['user_id'=>Yii::$app->user->id, 'visible'=>true]);
        $projects = \app\models\BaseProjects::find()->where(['visible'=>'true', 'id'=>$upIds])->orderBy(['ts'=>SORT_DESC]);
        $searchModel = new \app\models\BaseProjectsSearch();
        $dataProvider = $searchModel->search($qp);
        $dataProvider->query->andWhere(['id'=>$upIds])->orderBy(['ts'=>SORT_DESC]);;
        $dataProvider->pagination->pageSize = 25;

        $pages=new Pagination(['totalCount'=>($projects->count())]);
        $pages->pageSize=25;
        $pages->pageSizeParam=false;

        return $this->render('home', ['searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);
    }

    //__________________________ equipments
    public function actionEquipments()
    {
        if(Yii::$app->user->can("Access Admin"))
        {
            $qp = yii::$app->request->queryParams;
            if(isset($qp['viewMode'])) //table widget
            {
                $vm = $qp['viewMode'];
                if($vm == "table")
                    Yii::$app->session->set('viewMode', "table");
                else
                    Yii::$app->session->set('viewMode', "widget");
            }

            // base.equipments project.equipments  project.dedication project.sitex_dedication
            $qp = yii::$app->request->queryParams;
            $searchModel = new \app\models\BaseEquipmentsSearch();
            $dataProvider = $searchModel->search($qp);
            $dataProvider->pagination->pageSize = 25;

            $count = \app\models\BaseEquipments::find()->count();
            $pages=new Pagination(['totalCount'=>($count)]);
            $pages->pageSize=25;
            $pages->pageSizeParam=false;

            return $this->render('equipments/equipments', ['searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);

        }

        Yii::$app->session->setFlash('warning', "شما دسترسی لازم جهت مشاهده این صفحه را ندارید.");
        return $this->redirect(['base/home']);

    }
    //add eq
    public function actionAdd_equipment_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $model = new \app\models\BaseEquipments();
            return $this->renderAjax("ajax/equipment/add", ['model'=>$model]);
        }
    }
    public function actionAdd_equipment()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            $model = new \app\models\BaseEquipments();
            if (Yii::$app->request->isPost)
            {
                if ($model->load(Yii::$app->request->post()))
                {
                    try
                    {
                        $model->save(false);
                    }
                    catch (\Exception $e)
                    {
                        Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه گردید.");
                    }
                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

            return $this->redirect(['base/equipments']);
    }
    //edit eq
    public function actionEdit_equipment_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post("id", -1);
            $model = \app\models\BaseEquipments::findOne($id);
            return $this->renderAjax("ajax/equipment/edit", ['model'=>$model]);
        }
    }
    public function actionEdit_equipment()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            if (Yii::$app->request->isPost)
            {
                $id = Yii::$app->request->post("id", -1);
                $equipment = Yii::$app->request->post("equipment", "");
                $description = Yii::$app->request->post("description", "");
                $btn = Yii::$app->request->post("btn", "");

                if ( ($id > 0) && (!empty($equipment)))
                {
                    $model = \app\models\BaseEquipments::findOne($id);
                    $model->equipment = $equipment;
                    $model->description = $description;

                    if($btn == "edit")
                    {
                        try
                        {
                            $model->update(false);
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "ویرایش اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else if($btn == "remove")
                    {
                        try
                        {
                            $model->delete();
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "حذف اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else
                        Yii::$app->session->setFlash("error", "ارسال اطلاعات با خطا مواجه گردید.");
                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }

    // equipment projects
    ///// pe
    public function actionEq_projects($id = -1)
    {
        if(Yii::$app->user->can("Access Admin"))
        {
            $qp = yii::$app->request->queryParams;
            if(isset($qp['viewMode'])) //table widget
            {
                $vm = $qp['viewMode'];
                if($vm == "table")
                    Yii::$app->session->set('viewMode', "table");
                else
                    Yii::$app->session->set('viewMode', "widget");
            }
            if($id == -1) return $this->redirect(['base/equipments']);
            $equipment = \app\models\BaseEquipments::findOne($id);

            // base.equipments project.equipments  project.dedication project.sitex_dedication
            $qp = yii::$app->request->queryParams;
            $searchModel = new \app\models\ProjectEquipmentsViewSearch();
            $dataProvider = $searchModel->search($qp);
            $dataProvider->pagination->pageSize = 25;

            $count = \app\models\ProjectEquipments::find()->where(['equipment_id'=>$id])->count();
            $pages=new Pagination(['totalCount'=>($count)]);
            $pages->pageSize=25;
            $pages->pageSizeParam=false;

            return $this->render('equipments/eq_projects', ['searchModel'=>$searchModel, 'equipment'=>$equipment, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);

        }

        Yii::$app->session->setFlash('warning', "شما دسترسی لازم جهت مشاهده این صفحه را ندارید.");
        return $this->redirect(['base/home']);
    }
    // add pe
    public function actionAdd_pe_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $model = new \app\models\ProjectEquipments();
            $eid = Yii::$app->request->post("eid", -1);
            if($eid > 0)
            {
                $equipment = \app\models\BaseEquipments::findOne($eid);
                $model->equipment_id = $eid;
                $projects = \app\models\BaseProjects::find()->where(['enabled'=>true])->orderBy(['ts'=>SORT_DESC])->asArray()->all();
                $array = [];
                foreach ($projects as $project)
                {
                    $array[$project['id']] = $project['project_name'];
                }

                return $this->renderAjax("ajax/eq_projects/add", ['model'=>$model, 'equipment'=>$equipment, 'projects'=>$array]);
            }
        }
    }
    public function actionAdd_pe()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            $model = new \app\models\ProjectEquipments();
            if (Yii::$app->request->isPost)
            {
                $eid = Yii::$app->request->post("equipment_id", -1);
                $pid = Yii::$app->request->post("project_id", -1);
                $q = Yii::$app->request->post("quantity", -1);
                $d = Yii::$app->request->post("description", '');

                if (($eid > 0) && ($pid > 0) && ($q > -1) )
                {
                    $model->equipment_id = $eid;
                    $model->project_id = $pid;
                    $model->quantity = $q;
                    $model->description = $d;
                    try
                    {
                        $model->save(false);
                        return $this->redirect(['base/eq_projects', 'id'=>$eid]);
                    }
                    catch (\Exception $e)
                    {
                        Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه گردید.");
                    }
                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }
    // edit pe
    public function actionEdit_pe_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post("id", -1);
            if($id > 0)
            {
                $model = \app\models\ProjectEquipments::findOne($id);
                $equipment = \app\models\BaseEquipments::findOne($model->equipment_id);
                $projects = \app\models\BaseProjects::find()->where(['enabled'=>true])->orderBy(['ts'=>SORT_DESC])->asArray()->all();
                $array = [];
                foreach ($projects as $project)
                {
                    $array[$project['id']] = $project['project_name'];
                }

                return $this->renderAjax("ajax/eq_projects/edit", ['model'=>$model, 'equipment'=>$equipment, 'projects'=>$array]);
            }
        }
    }
    public function actionEdit_pe()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            if (Yii::$app->request->isPost)
            {
                $id = Yii::$app->request->post("id", -1);
                $project_id = Yii::$app->request->post("project_id", -1);
                $quantity = Yii::$app->request->post("quantity", -1);
                $description = Yii::$app->request->post("description", "");
                $btn = Yii::$app->request->post("btn", "");

                if ( ($id > 0) && ($project_id > 0))
                {
                    $model = \app\models\ProjectEquipments::findOne($id);
                    $model->project_id = $project_id;
                    $model->quantity = $quantity;
                    $model->description = $description;

                    if($btn == "edit")
                    {
                        try
                        {
                            $model->update(false);
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "ویرایش اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else if($btn == "remove")
                    {
                        try
                        {
                            $model->delete();
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "حذف اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else
                        Yii::$app->session->setFlash("error", "ارسال اطلاعات با خطا مواجه گردید.");

                    return $this->redirect(['base/eq_projects', 'id'=>$model->equipment_id]);

                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/eq_projects']);
    }

    //// project dedication
    public function actionPr_dedication($id=-1)
    {
        if(Yii::$app->user->can("Access Admin"))
        {
            $qp = yii::$app->request->queryParams;
            if(isset($qp['viewMode'])) //table widget
            {
                $vm = $qp['viewMode'];
                if($vm == "table")
                    Yii::$app->session->set('viewMode', "table");
                else
                    Yii::$app->session->set('viewMode', "widget");
            }
            if($id == -1) return $this->redirect(['base/equipments']);
            $pe = \app\models\ProjectEquipmentsView::findOne($id);
            $e = \app\models\BaseEquipments::findOne($pe->equipment_id);

            // base.equipments project.equipments  project.dedication project.sitex_dedication
            $qp = yii::$app->request->queryParams;
            $searchModel = new \app\models\ProjectDedicationSearch();
            $dataProvider = $searchModel->search($qp);
            $dataProvider->pagination->pageSize = 25;

            $count = \app\models\ProjectDedication::find()->where(['project_equipment_id'=>$id])->count();
            $pages=new Pagination(['totalCount'=>($count)]);
            $pages->pageSize=25;
            $pages->pageSizeParam=false;

            return $this->render('equipments/pr_dedication', ['searchModel'=>$searchModel, 'e'=>$e, 'pe'=>$pe, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);

        }

        Yii::$app->session->setFlash('warning', "شما دسترسی لازم جهت مشاهده این صفحه را ندارید.");
        return $this->redirect(['base/home']);
    }
    //add pd
    public function actionAdd_pd_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $model = new \app\models\ProjectDedication();
            $peid = Yii::$app->request->post("peid", -1);
            if($peid > 0)
            {
                $pe = \app\models\ProjectEquipmentsView::findOne($peid);
                $model->project_equipment_id = $peid;
                $areas = [2=>"2", 3=>"3", 4=>"4", 5=>"5", 6=>"6", 7=>"7", 8=>"8"];
                return $this->renderAjax("ajax/pr_dedication/add", ['model'=>$model, 'pe'=>$pe, 'areas'=>$areas]);
            }
        }
    }
    public function actionAdd_pd()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            $model = new \app\models\ProjectDedication();
            if (Yii::$app->request->isPost)
            {
                $peid = Yii::$app->request->post("peid", -1);
                $area = Yii::$app->request->post("area", -1);
                $q = Yii::$app->request->post("quantity", -1);
                $d = Yii::$app->request->post("description", '');

                if (($peid > 0) && ($area > 1) && ($q > -1) )
                {
                    $model->project_equipment_id = $peid;
                    $model->area = $area;
                    $model->quantity = $q;
                    $model->description = $d;
                    try
                    {
                        $model->save(false);
                        return $this->redirect(['base/pr_dedication', 'id'=>$peid]);
                    }
                    catch (\Exception $e)
                    {
                        Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه گردید.");
                    }
                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }
    //edit pd
    public function actionEdit_pd_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post("id", -1);
            if($id > 0)
            {
                $model = \app\models\ProjectDedication::findOne($id);
                $pe = \app\models\ProjectEquipmentsView::findOne($model->project_equipment_id);
                $areas = [2=>"2", 3=>"3", 4=>"4", 5=>"5", 6=>"6", 7=>"7", 8=>"8"];

                return $this->renderAjax("ajax/pr_dedication/edit", ['model'=>$model, 'pe'=>$pe, 'areas'=>$areas]);
            }
        }
    }
    public function actionEdit_pd()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            if (Yii::$app->request->isPost)
            {
                $id = Yii::$app->request->post("id", -1);
                $area = Yii::$app->request->post("area", -1);
                $quantity = Yii::$app->request->post("quantity", -1);
                $description = Yii::$app->request->post("description", "");
                $btn = Yii::$app->request->post("btn", "");

                if ( ($id > 0) && ($area > 1))
                {
                    $model = \app\models\ProjectDedication::findOne($id);
                    $model->area = $area;
                    $model->quantity = $quantity;
                    $model->description = $description;

                    if($btn == "edit")
                    {
                        try
                        {
                            $model->update(false);
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "ویرایش اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else if($btn == "remove")
                    {
                        try
                        {
                            $model->delete();
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "حذف اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else
                        Yii::$app->session->setFlash("error", "ارسال اطلاعات با خطا مواجه گردید.");

                    return $this->redirect(['base/pr_dedication', 'id'=>$model->project_equipment_id]);

                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }

    ///// site dedication
    public function actionSitex_dedication($id=-1)
    {
        // id: pd_id
        if(Yii::$app->user->can("Access Admin"))
        {
            $qp = yii::$app->request->queryParams;
            if(isset($qp['viewMode'])) //table widget
            {
                $vm = $qp['viewMode'];
                if($vm == "table")
                    Yii::$app->session->set('viewMode', "table");
                else
                    Yii::$app->session->set('viewMode', "widget");
            }
            if($id == -1) return $this->redirect(['base/equipments']);

            $pd = \app\models\ProjectDedication::findOne($id);
            $pe = \app\models\ProjectEquipmentsView::findOne($pd->project_equipment_id);
            $e = \app\models\BaseEquipments::findOne($pe->equipment_id);

            // base.equipments project.equipments  project.dedication project.sitex_dedication
            $qp = yii::$app->request->queryParams;
            $searchModel = new \app\models\ProjectSitexDedicationViewSearch();
            $dataProvider = $searchModel->search($qp);
            $dataProvider->pagination->pageSize = 25;

            $count = \app\models\ProjectSitexDedication::find()->where(['project_dedication_id'=>$id])->count();
            $pages=new Pagination(['totalCount'=>($count)]);
            $pages->pageSize=25;
            $pages->pageSizeParam=false;

            return $this->render('equipments/sitex_dedication', ['searchModel'=>$searchModel, 'e'=>$e, 'pe'=>$pe, 'pd'=>$pd, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);

        }

        Yii::$app->session->setFlash('warning', "شما دسترسی لازم جهت مشاهده این صفحه را ندارید.");
        return $this->redirect(['base/home']);
    }
    // add sd
    public function actionAdd_sd_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $model = new \app\models\ProjectSitexDedication();
            $pdid = Yii::$app->request->post("pdid", -1);
            if($pdid > 0)
            {
                $pd = \app\models\ProjectDedication::findOne($pdid);
                $pe = \app\models\ProjectEquipmentsView::findOne($pd->project_equipment_id);
                $model->project_dedication_id = $pdid;
                $area = $pd->area;
                $project_id = $pe->project_id;
                $array = \app\models\ProjectSitexView::find()->where(['area'=>$area, 'project_id'=>$project_id])->orderBy(['name'=>SORT_ASC])->asArray()->all();
                $sitex = [];
                foreach ($array as $a)
                    $sitex[$a['id']] = $a['name'];

                return $this->renderAjax("ajax/site_dedication/add", ['model'=>$model, 'pe'=>$pe, 'pd'=>$pd, 'sitex'=>$sitex]);
            }
        }
    }
    public function actionAdd_sd()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            $model = new \app\models\ProjectSitexDedication();
            if (Yii::$app->request->isPost)
            {
                $pdid = Yii::$app->request->post("pdid", -1);
                $psitex_id = Yii::$app->request->post("psitex_id", -1);
                $q = Yii::$app->request->post("quantity", -1);
                $d = Yii::$app->request->post("description", '');

                if (($pdid > 0) && ($psitex_id > 0) && ($q > -1) )
                {
                    $model->project_sitex_id = $psitex_id;
                    $model->project_dedication_id = $pdid;
                    $model->quantity = $q;
                    $model->description = $d;
                    try
                    {
                        $model->save(false);
                        return $this->redirect(['base/sitex_dedication', 'id'=>$pdid]);
                    }
                    catch (\Exception $e)
                    {
                        Yii::$app->session->setFlash("error", "ذخیره اطلاعات با خطا مواجه گردید.");
                    }
                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }
    // edit sd
    public function actionEdit_sd_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post("id", -1);
            if($id > 0)
            {
                $model = \app\models\ProjectSitexDedication::findOne($id);
                $pd = \app\models\ProjectDedication::findOne($model->project_dedication_id);
                $pe = \app\models\ProjectEquipmentsView::findOne($pd->project_equipment_id);

                $area = $pd->area;
                $project_id = $pe->project_id;
                $array = \app\models\ProjectSitexView::find()->where(['area'=>$area, 'project_id'=>$project_id])->orderBy(['name'=>SORT_ASC])->asArray()->all();
                $sitex = [];
                foreach ($array as $a)
                    $sitex[$a['id']] = $a['name'];

                return $this->renderAjax("ajax/site_dedication/edit", ['model'=>$model, 'pe'=>$pe, 'pd'=>$pd, 'sitex'=>$sitex]);
            }
        }
    }
    public function actionEdit_sd()
    {
        if (Yii::$app->user->can("Access Admin"))
        {
            if (Yii::$app->request->isPost)
            {
                $id = Yii::$app->request->post("id", -1);
                $psitex_id = Yii::$app->request->post("psitex_id", -1);
                $quantity = Yii::$app->request->post("quantity", -1);
                $description = Yii::$app->request->post("description", "");
                $btn = Yii::$app->request->post("btn", "");

                if ( ($id > 0) && ($psitex_id > 0))
                {
                    $model = \app\models\ProjectSitexDedication::findOne($id);
                    $model->project_sitex_id = $psitex_id;
                    $model->quantity = $quantity;
                    $model->description = $description;

                    if($btn == "edit")
                    {
                        try
                        {
                            $model->update(false);
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "ویرایش اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else if($btn == "remove")
                    {
                        try
                        {
                            $model->delete();
                        }
                        catch (\Exception $e)
                        {
                            Yii::$app->session->setFlash("error", "حذف اطلاعات با خطا مواجه گردید.");
                        }
                    }
                    else
                        Yii::$app->session->setFlash("error", "ارسال اطلاعات با خطا مواجه گردید.");

                    return $this->redirect(['base/sitex_dedication', 'id'=>$model->project_dedication_id]);

                }
                else
                    Yii::$app->session->setFlash('warning', "شما مجوز لازم برای این عملیات را ندارید.");
            }
        }
        else
            Yii::$app->session->setFlash('error', "شما مجوز لازم برای این عملیات را ندارید.");

        return $this->redirect(['base/equipments']);
    }

    //__________________________ projects
    public function actionProjects()
    {
        if(yii::$app->user->isGuest)
            return $this->redirect(['base/index']);

        if(yii::$app->user->can("Access Admin"))
        {
            $qp = yii::$app->request->queryParams;
            if(isset($qp['viewMode'])) //table widget
            {
                $vm = $qp['viewMode'];
                if($vm == "table")
                    Yii::$app->session->set('viewMode', "table");
                else
                    Yii::$app->session->set('viewMode', "widget");
            }

            $projects = \app\models\BaseProjects::find()->orderBy(['ts'=>SORT_DESC]);
            $searchModel = new \app\models\BaseProjectsSearch();
            $dataProvider = $searchModel->search($qp);
            $dataProvider->query->orderBy(['ts'=>SORT_DESC]);;
            $dataProvider->pagination->pageSize = 25;

            $pages=new Pagination(['totalCount'=>($projects->count())]);
            $pages->pageSize=25;
            $pages->pageSizeParam=false;

            return $this->render('projects/projects', ['searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, 'pages'=>$pages]);
        }

        Yii::$app->session->setFlash('warning', "شما دسترسی لازم جهت مشاهده این صفحه را ندارید.");
        return $this->redirect(['base/home']);

    }
    public function actionAjax_add_pr_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $project = new \app\models\BaseProjects();
            $array = \app\models\BaseProjectMeta::find()->distinct("key")->orderBy(['order'=>SORT_ASC])->asArray()->all();
            $meta = [];
            foreach ($array as $k)
                $meta[] = $k['key'];

            return $this->renderAjax("projects/ajax/add", ['project'=>$project, 'meta'=>$meta]);
        }
    }
    public function actionAdd_project()
    {
        if(yii::$app->user->isGuest)
            return $this->redirect(['base/index']);
        if(yii::$app->user->can("Access Admin"))
        {
            if(Yii::$app->request->isPost)
            {
                $project_name = Yii::$app->request->post("project_name", "");
                $office = Yii::$app->request->post("office", "");
                $enabled = Yii::$app->request->post("enabled", "");
                $visible = Yii::$app->request->post("visible", "");
                $project_weight = Yii::$app->request->post("project_weight", "");

                $mmn = Yii::$app->request->post("max-meta-number", 0);
                $metas = [];
                $index = 0;
                for ($i = 1; $i <= $mmn; $i++)
                {
                    $key = Yii::$app->request->post("k".$i, "");
                    $value = Yii::$app->request->post("v".$i, "");
                    if(!empty($key) && !empty($value))
                        $metas[$index] = [$key, $value];
                    $index++;
                }

                $project = new \app\models\BaseProjects();
                $project->project_name = $project_name;
                $project->office = $office;
                $project->enabled = $enabled;
                $project->visible = $visible;
                $project->ts = time();
                $project->project_weight = $project_weight;

                if($project->save(false))
                {
                    $id = $project->id;
                    foreach ($metas as $order=>$kv)
                    {
                        $projectMeta = new \app\models\BaseProjectMeta();
                        $projectMeta->project_id = $id;
                        $projectMeta->key = $kv[0];
                        $projectMeta->value = $kv[1];
                        $projectMeta->order = $order;

                        $projectMeta->save(false);
                    }
                }

            }
        }

        return $this->redirect(['base/projects']);
    }


    public function actionAjax_edit_pr_form()
    {
        if(Yii::$app->request->isAjax)
        {
            $id = Yii::$app->request->post('id', -1);
            if($id > 0)
            {
                $project = \app\models\BaseProjects::findOne($id);
                $pmetas = \app\models\BaseProjectMeta::find()->where(['project_id'=>$id])->orderBy(['order'=>SORT_ASC])->all();

                $array = \app\models\BaseProjectMeta::find()->distinct("key")->orderBy(['order'=>SORT_ASC])->asArray()->all();
                $meta = [];
                foreach ($array as $k)
                    $meta[] = $k['key'];

                return $this->renderAjax("projects/ajax/edit", ['project'=>$project, 'projectMeta'=>$pmetas, 'datalist'=>$meta]);

            }
        }
    }


    public function actionAbout()
    {
        return $this->render('about');
    }




}