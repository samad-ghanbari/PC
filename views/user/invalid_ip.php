<?php
$this->title = 'User Forbidden';
/* @var user_ip */
/* @var ts */
?>

<div class="bg-secondary d-flex vh-100 w-100 border border-warning position-fixed overflow-auto">
    <div class="bg-transparent h-75 w-75  mx-auto  text-center text-white">
        <p class="en-font font-weight-bold text-center text-warning display-4">
            <i class="fa fa-ban fa-2x text-warning"></i>
            <br />
            403
        </p>
        <hr class=" border-top w-50 mx-auto border-warning " />
        <p class="fa-font h4">
            شما مجوز ورود به سامانه را ندارید.
        </p>
        <br />
        <p class="fa-font text-center h5">
            اگر چنانچه به اشتباه دسترسی شما محدود شده است لطفا با ادمین سامانه مشکل را در میان بگذارید تا مجوز دسترسی شما مورد بررسی قرار گیرد.
        </p>

        <div class="text-white rounded text-center h3 p-2">
                <div class="h4 p-2 text-center ">
                    IP سیستم شما
                    <span class="en-font"><?= $user_ip; ?></span>
                    می‌باشد.
                </div>
                    <hr class="border-top border-warning w-50 mx-auto" />
                <p class="h4 p-2 text-light">
                    IP شما در مورخ  <?= $ts; ?>   در لاگ سیستم ثبت گردید تا در صورت نیاز مورد بررسی قرار گیرد.
                </p>
            </div>

        <img src="<?=Yii::$app->request->baseUrl."/web/images/logo.png"; ?>" class="d-block  max-width-100px mx-auto">
        <br />
    </div>
</div>
