<?php
namespace app\components;
use Yii;
use yii\base\Component;

abstract class LogTypes  extends Component
{
    const invalid_ip_try = "invalid ip try" ;
    const invalid_password = "invalid password";
    const invalid_username_ip = "invalid username ip";
    const user_disabled = "user disabled";
    const login = "user logged-in";
    const update = "update";
    const remove = "remove";
    const create = "create";
    const import = "import";
}




?>