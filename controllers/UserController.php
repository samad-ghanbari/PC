<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;


class UserController extends Controller
{

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

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
            return $this->redirect(['base/home']);

        $model = new \app\models\UserUsers();
        $user_ip =  \Yii::$app->request->getUserIP();
        if ($model->load(Yii::$app->request->post()))
        {
            //authentication
            $pass = $model->hashPassword($model->auth_key);
            $user = \app\models\UserUsers::find()->where(['natid'=>$model->natid, 'auth_key'=>$pass])->one();
            if(empty($user))
            {
                //invalid try
                $type = \app\components\LogTypes::invalid_password;
                $msg = "Username or Password is incorrect.";
                $log = \app\components\Logger::createLog($type, $msg, $model->natid);
                \Yii::info($log, 'LOG');

                Yii::$app->session->setFlash("login-error", "نام کاربری یا رمز عبور اشتباه است.");
                return $this->redirect(['user/login']);
            }
            else
            {
                //user enabled or not
                if(!$user->enabled)
                {
                    //user is disabled log the file and return
                    $type = \app\components\LogTypes::user_disabled;
                    $msg = "user is disabled and is not allowed to login";
                    $log = \app\components\Logger::createLog($type, $msg, $model->natid);
                    \Yii::info($log, 'LOG');

                    Yii::$app->session->setFlash("login-error", "نام کاربری شما غیرفعال شده است.");
                    return $this->redirect(['user/login']);
                }

                //check ip
                $all_ips = \app\models\UserMeta::find()->select("value")->where(['key'=>"IP", 'user_id'=>$user->id])->distinct()->asArray()->all();
                $array = [];
                foreach ($all_ips as $ip)
                    $array[] = $ip['value'];
                if(in_array($user_ip, $array) || in_array("*", $array) )
                {
                    ////////    login
                    Yii::$app->user->login($user);
                    //log
                    $type = \app\components\LogTypes::login;
                    $msg = "user was successfully logged-in";
                    $log = \app\components\Logger::createLog($type, $msg);
                    \Yii::info($log, 'LOG');
                    // last login
                    $user_meta = \app\models\UserMeta::find()->where(['user_id'=>$user->id, 'key'=>"آخرین ورود موفق"])->one();
                    if(empty($user_meta))
                    {
                        //create meta
                        $meta = new  \app\models\UserMeta();
                        $meta->user_id = $user->id;
                        $meta->key = "آخرین ورود موفق";
                        $meta->value = str(time());
                        $meta->save(false);
                    }
                    else
                    {
                        $user_meta->value = strval(time());
                        $user_meta->update(false);
                    }

                    return $this->redirect(['base/home']);
                }
                else
                {
                    //invalid ip
                    //log the file and return
                    $type = \app\components\LogTypes::invalid_username_ip;
                    $msg = "user invalid ip request";
                    $log = \app\components\Logger::createLog($type, $msg, $model->natid);
                    \Yii::info($log, 'LOG');

                    Yii::$app->session->setFlash("login-error", "ورود به سامانه با IP غیرمجاز مقدور نمی‌باشد.");
                    return $this->redirect(['user/login']);
                }
            }
        }

        $userId = \app\models\UserMeta::find()->select("user_id")->where(['key'=>"IP", "value"=>$user_ip])->one();
        $lastLogin = \app\models\UserMeta::find()->select("value")->where(['user_id'=>$userId, 'key'=>"آخرین ورود موفق"])->scalar();
        if(!empty($lastLogin))
        {
            $lastLogin = \app\components\Jdf::jdate("Y/m/d ساعت H:i", $lastLogin);
            $lastLogin = "آخرین ورود موفق شما: ".$lastLogin;
        }

        $model->auth_key = '';
        $this->layout = "plain";
        $flash = ""; $msg = "";
        if(Yii::$app->session->hasFlash('login-error'))  { $flash = "login-error"; $msg = Yii::$app->session->getFlash("error");}
        Yii::$app->session->destroy();
        if(!empty($flash)) Yii::$app->session->setFlash($flash, $msg);
        return $this->render('login', ['model' => $model, 'lastLogin'=>$lastLogin]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->goHome();
    }


}