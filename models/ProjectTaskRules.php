<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.parameter_rules".
 *
 * @property int $id
 * @property int $task_id
 * @property int $depend_task_id
 * @property int $priority
 * @property string $operator
 * @property string $value
 */
class ProjectTaskRules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.task_rules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'depend_task_id', 'priority', 'operator', 'value'], 'required'],
            [['task_id', 'depend_task_id', 'priority'], 'default', 'value' => null],
            [['task_id', 'depend_task_id', 'priority'], 'integer'],
            [['operator'], 'string', 'max' => 16],
            [['value'], 'string', 'max' => 1024],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTasks::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['depend_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTasks::className(), 'targetAttribute' => ['depend_task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'depend_task_id' => 'Depend Task ID',
            'priority' => 'Priority',
            'operator' => 'Operator',
            'value' => 'Value',
        ];
    }
}
