<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "base.users".
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $gender
 * @property string $natid
 * @property string $empcode
 * @property string $auth_key
 * @property string|null $auth_key_confirm
 * @property string $province
 * @property string $office
 * @property string $position
 * @property string|null $telephone
 * @property bool $enabled
 * @property bool $reset_password
 */
class UserUsers extends ActiveRecord implements IdentityInterface
{
        public $verifyCode;
        public $auth_key_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user.users';
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // we don't have token in table
    }

    public function getGender()
    {
        return $this->gender;
    }
    public function getNatId()
    {
        return $this->natid;
    }
    public function getUserName()
    {
        return $this->name ." ".$this->lastname;
    }
    public function hashPassword($pass)
    {
        $salt = "Pc@140011.Samad";
        $pass = $pass.$salt;
        $pass = md5($pass);
        return $pass;
    }
    public function getOffice()
    {
        return $this->office;
    }
    public function getThumbnail()
    {
        $path = yii::$app->request->baseUrl.'/web/images/profiles/';
        $id = $this->id;
        $thumbnail = \app\models\UserMeta::find()->select("value")->where(['user_id'=>$id, 'key'=>'عکس کاربر'])->scalar();
        if(empty($thumbnail))
        {
            if($this->gender == "male")
                return $path."male.png";
            else
                return $path."female.png";
        }
        else
            return $path.$thumbnail;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lastname', 'gender', 'natid', 'empcode', 'auth_key', 'province', 'office', 'position'], 'required', 'message'=>""],
            [['enabled', 'reset_password'], 'boolean'],
            [['enabled'], 'default', 'value' => true],
            [['reset_password'], 'default', 'value' => false],
            [['name'], 'string', 'max' => 50],
            [['lastname', 'province', 'office', 'position', 'telephone'], 'string', 'max' => 100],
            [['gender', 'natid', 'empcode'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 1024],
            [['auth_key_confirm'], 'compare', 'compareAttribute' => 'auth_key', 'message'=>'رمز های عبور یکسان نیستتند'],
            [['empcode'], 'unique'],
            [['natid'], 'unique'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => "شناسه", // Yii::t('app', 'ID'),
            'name' => "نام",
            'lastname' => "نام خانوادگی",
            'gender' => "جنسیت",
            'natid' => "کد ملی",
            'empcode' => "کد مستخدمی" ,
            'auth_key' => "رمز عبور" ,
            'auth_key_confirm' => "تاییدیه رمز عبور",
            'province' => "منطقه" ,
            'office' => "اداره کل" ,
            'position' =>  "سمت",
            'telephone' => "شماره تماس",
            'enabled' =>"وضعیت کاربر",
            'reset_password' =>"تغییر رمز عبور",
            'verifyCode' =>"کد تایید"
        ];
    }
}
