<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user.project_meta".
 *
 * @property int $id
 * @property int $user_project_id
 * @property string $key
 * @property string|null $value
 * @property int $order
 */
class UserProjectMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user.project_meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_project_id', 'key'], 'required'],
            [['user_project_id', 'order'], 'default', 'value' => null],
            [['user_project_id', 'order'], 'integer'],
            [['key'], 'string', 'max' => 256],
            [['value'], 'string', 'max' => 1024],
            [['user_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProjects::className(), 'targetAttribute' => ['user_project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_project_id' => 'User Project ID',
            'key' => 'Key',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }
}
