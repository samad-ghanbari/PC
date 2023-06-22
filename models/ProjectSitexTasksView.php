<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_parameters_view".
 *
 * @property int|null $id
 * @property int|null $project_sitex_id
 * @property int|null $task_id
 * @property string|null $value
 * @property int|null $option_id
 * @property int|null $ts
 * @property int|null $modifier_id
 * @property string|null $modifier
 */
class ProjectSitexTasksView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_tasks_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_sitex_id', 'task_id', 'option_id', 'ts', 'modifier_id'], 'default', 'value' => null],
            [['id', 'project_sitex_id', 'task_id', 'option_id', 'ts', 'modifier_id'], 'integer'],
            [['modifier'], 'string'],
            [['value'], 'string', 'max' => 512],
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
            'modifier' => 'Modifier',
        ];
    }
}
