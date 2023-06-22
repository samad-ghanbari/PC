<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user.meta".
 *
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @property int $order
 */
class UserMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user.meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'key'], 'required'],
            [['user_id', 'order'], 'default', 'value' => null],
            [['user_id', 'order'], 'integer'],
            [['key', 'value'], 'string', 'max' => 1024],
            [['user_id', 'key', 'value'], 'unique', 'targetAttribute' => ['user_id', 'key', 'value']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'key' => 'Key',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }
}
