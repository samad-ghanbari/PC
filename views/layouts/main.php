<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
$userName = "";
$userOffice = "";
$userThumbnail = "";
if(!yii::$app->user->isGuest)
{
    $user = yii::$app->user->identity;
    $userName = $user->getUserName();
    $userOffice = $user->getOffice();
    $userThumbnail = $user->getThumbnail();
}
$CONTROLLER = yii::$app->controller->id;
$ACTION = yii::$app->controller->action->id;
//       menu
//my projects : base/home
$baseHome_li = "";
$baseHome_a = "";
if( ($CONTROLLER == "base") && ($ACTION == "home") ) {$baseHome_li = " menu-open "; $baseHome_a = " active "; }
//stat:
//      pieStat
//      tableStat
//      detailStat
//      percentStat
//

//setting:
//      users
//      projetcs
$basePr_li = "";
$basePr_a = "";
if( ($CONTROLLER == "base") && ($ACTION == "projects") ) {$basePr_li = " menu-open "; $basePr_a = " active "; }
//      equipments
$baseEq_li = "";
$baseEq_a = "";
if( ($CONTROLLER == "base") && ($ACTION == "equipments") ) {$baseEq_li = " menu-open "; $baseEq_a = " active "; }

//import import/sitex

//about
$baseAbout_li = "";
$baseAbout_a = "";
if( ($CONTROLLER == "base") && ($ACTION == "about") ) {$baseAbout_li = " menu-open "; $baseAbout_a = " active "; }

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100 hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <?php $this->beginBody() ?>

    <div class="wrapper bg-ddd">

        <!-- Main Sidebar Container -->
        <!--  menu button for small screens-->
        <a class="position-fixed m-2 p-2 rounded position-right-0" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars  text-info font-weight-bold text-shadow-dark" ></i>
        </a>

        <aside class="main-sidebar main-sidebar-right sidebar-light-info elevation-4" >
            <!-- Brand Logo -->
            <a href="index.html" class="brand-link border-0 clearfix">
                <img src="<?= Yii::$app->request->baseUrl.'/web/images/logo.png'; ?>" alt="PC" class="brand-image-center img-circle elevation-3" style="opacity: .8">
                <!--      <span class="brand-text-right text-right float-right font-weight-light">سامانه کنترل پروژه</span>-->
            </a>
            <!--  control buttons -->
            <div class="clearfix m-1 p-1 border-bottom border-info">
                <a class="float-left  text-secondary" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-thumbtack "></i>
                </a>
                <a class="float-right  text-secondary" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <a href="http:://profile.com" >
                    <div class="user-panel d-flex flex-row justify-content-end clearfix border-bottom border-info">
                        <div class="info p-1">
                            <p class="d-block text-right user-info-name"><?= $userName; ?></p>
                            <p class="d-block text-right user-info-office"><?= $userOffice; ?></p>
                        </div>
                        <div class="image align-self-center">
                            <img src="<?= $userThumbnail; ?>" class="rounded  border border-info" alt="User">
                        </div>
                    </div>
                </a>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item nav-item-right <?= $baseHome_li; ?>" >
                            <a href="<?= yii::$app->request->baseurl.'/base/home'; ?>" class="nav-link d-block <?= $baseHome_a; ?>">
                                <i class="nav-icon fa fa-home"></i>
                                <p >
                                    پروژه‌های من
                                </p>
                            </a>
                        </li>

                        <li  class="nav-item nav-item-right">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>
                                    آمار پروژه
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fas fa-chart-pie nav-icon text-info"></i>
                                        <p>نمودار آماری</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fa fa-table nav-icon text-info"></i>
                                        <p>جدول آماری</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fa fa-info-circle nav-icon text-info"></i>
                                        <p>آمار جزيیات</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fa fa-percent nav-icon text-info"></i>
                                        <p>درصد پیشرفت</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fa fa-server nav-icon text-info"></i>
                                        <p>چارت تخصیص تجهیزات</p>
                                    </a>
                                </li>                  <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="fa fa-server nav-icon text-info"></i>
                                        <p>جدول تخصیص تجهیزات</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li  class="nav-item nav-item-right <?= $baseEq_li.' '. $basePr_li; ?>">
                            <a href="#" class="nav-link <?= $baseEq_a; ?>">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    تنظیمات
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="<?= Yii::$app->request->baseUrl.'/user/users'; ?>" class="nav-link">
                                        <i class="fas fa-users nav-icon text-info"></i>
                                        <p>کاربران</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= Yii::$app->request->baseUrl.'/base/projects'; ?>" class="nav-link <?= $basePr_a; ?>">
                                        <i class="fa fa-check-square nav-icon text-info "></i>
                                        <p>پروژه</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= Yii::$app->request->baseUrl.'/base/equipments'; ?>" class="nav-link  <?= $baseEq_a; ?>">
                                        <i class="fa fa-server nav-icon text-info"></i>
                                        <p>تجهیزات</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item nav-item-right">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-life-ring"></i>
                                <p>
                                    پشتیبانی
                                    <i class="fas fa-angle-left right"></i>
                                    <span class="badge badge-success right">6</span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-edit text-info nav-icon"></i>
                                        <p>ثبت تیکت پشتیبانی</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-envelope text-info nav-icon"></i>
                                        <p>صندوق تیکت‌های پشتیبانی</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item nav-item-right">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-bell"></i>
                                <p>
                                    اعلانات
                                    <i class=""></i>
                                    <span class="badge badge-danger right">1</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item nav-item-right <?= $baseAbout_li; ?>">
                            <a href="<?= Yii::$app->request->baseUrl.'/base/about'; ?>" class="nav-link <?= $baseAbout_a; ?>">
                                <i class="nav-icon fa fa-info-circle"></i>
                                <p>
                                    درباره سامانه
                                </p>
                            </a>
                        </li>

                        <li class="nav-item nav-item-right">
                            <a href="<?= Yii::$app->request->baseUrl.'/user/logout'; ?>" class="nav-link">
                                <i class="nav-icon fa fa-sign-out text-danger"></i>
                                <p>
                                    خروج از سامانه
                                </p>
                            </a>
                        </li>

                        <li class="nav-header text-center  text-secondary border-top border-info">سامانه کنترل پروژه طرح و مهندسی</li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->

            </div>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">
            <?= Breadcrumbs::widget([
                'itemTemplate' => "<li class='mr-2  text-secondary'>{link} / </li>\n",
                'activeItemTemplate'=>"<li class='mr-2 text-secondary'>{link} </li>\n",
                'homeLink' => [
                    'label' => Yii::t('yii', ' پروژه‌های من '),
                    'url' => Yii::$app->homeUrl,
                ],
                'options'=>['class'=>"dir-rtl pr-5 en-font"],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <?= Alert::widget(["options"=>['class'=>"text-right dir-rtl fixed-top w-100 z-index-top mx-auto"]]); ?>

            <div class="p-2">
                <?= $content; ?>
            </div>
        </div>

    </div>
    <!-- ./wrapper -->

    <footer class="footer mt-auto py-3 text-dark" title="Developer Samad Ghanbari <s.ghanbari@tci.ir>">
            <div class="container">
                <img src="<?= Yii::$app->request->baseUrl.'/web/images/tci.png'; ?>">
                <p> Developed By Planning Office &copy TCT</p>
            </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>