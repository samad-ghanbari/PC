<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_parameters".
 *
 * @property int $id
 * @property int $project_sitex_id
 * @property int $task_id
 * @property string|null $value
 * @property int|null $option_id
 * @property int $ts
 * @property int $modifier_id
 */
class ProjectSitexTasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_sitex_id', 'task_id', 'ts', 'modifier_id'], 'required'],
            [['project_sitex_id', 'task_id', 'option_id', 'ts', 'modifier_id'], 'default', 'value' => null],
            [['project_sitex_id', 'task_id', 'option_id', 'ts', 'modifier_id'], 'integer'],
            [['value'], 'string', 'max' => 512],
            [['project_sitex_id', 'task_id'], 'unique', 'targetAttribute' => ['project_sitex_id', 'task_id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTaskOptions::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTasks::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['project_sitex_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectSitex::className(), 'targetAttribute' => ['project_sitex_id' => 'id']],
            [['modifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserUsers::className(), 'targetAttribute' => ['modifier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_sitex_id' => 'Project Sitex ID',
            'task_id' => 'Task ID',
            'value' => 'Value',
            'option_id' => 'Option ID',
            'ts' => 'Ts',
            'modifier_id' => 'Modifier ID',
        ];
    }
}
