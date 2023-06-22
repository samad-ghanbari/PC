<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base.user_projects".
 *
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property bool $visible
 * @property bool $enabled
 */
class UserProjects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user.projects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['user_id', 'project_id'], 'default', 'value' => null],
            [['user_id', 'project_id'], 'integer'],
            [['visible', 'enabled'], 'boolean'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProjects::className(), 'targetAttribute' => ['project_id' => 'id']],
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
            'project_id' => 'Project ID',
            'visible' => 'Visible',
            'enabled' => 'Enabled',
        ];
    }
}
