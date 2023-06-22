<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.tasks".
 *
 * @property int $id
 * @property int $project_id
 * @property string $task
 * @property int $priority
 * @property string $type
 * @property int $weight
 */
class ProjectTasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'task', 'priority', 'type'], 'required'],
            [['project_id', 'priority', 'weight'], 'default', 'value' => null],
            [['project_id', 'priority', 'weight'], 'integer'],
            [['type'], 'string'],
            [['task'], 'string', 'max' => 256],
            [['project_id', 'task'], 'unique', 'targetAttribute' => ['project_id', 'task']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseProjects::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'task' => 'Task',
            'priority' => 'Priority',
            'type' => 'Type',
            'weight' => 'Weight',
        ];
    }
}
