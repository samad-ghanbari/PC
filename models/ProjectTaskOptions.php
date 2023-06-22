<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.parameter_options".
 *
 * @property int $id
 * @property int $task_id
 * @property string $option
 * @property bool $default_option
 * @property bool $done_option
 */
class ProjectTaskOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.task_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'option'], 'required'],
            [['task_id'], 'default', 'value' => null],
            [['task_id'], 'integer'],
            [['default_option', 'done_option'], 'boolean'],
            [['option'], 'string', 'max' => 512],
            [['task_id', 'option'], 'unique', 'targetAttribute' => ['task_id', 'option']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTasks::className(), 'targetAttribute' => ['task_id' => 'id']],
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
            'option' => 'Option',
            'default_option' => 'Default Option',
            'done_option' => 'Done Option',
        ];
    }
}
