<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base.projects".
 *
 * @property int $id
 * @property string $project_name
 * @property string $office
 * @property int $ts
 * @property bool $enabled
 * @property bool $visible
 * @property int $project_weight
 */
class BaseProjects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base.projects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_name', 'office', 'ts'], 'required'],
            [['ts'], 'default', 'value' => null],
            [['ts', 'project_weight'], 'integer'],
            [['project_name', 'office'], 'string', 'max' => 256],
            [['project_name', 'office'], 'unique', 'targetAttribute' => ['project_name', 'office']],
            [['enabled', 'visible'], 'boolean'],
            [['enabled', 'visible'], 'default', 'value' => true],
            [['project_weight'], 'default', 'value' => 0],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => 'نام پروژه',
            'office' => 'اداره کل',
            'ts' => 'زمان',
            'enabled' => 'وضعیت پروژه',
            'visible' => 'نمایش پروژه',
            'project_weight' => 'وزن پروژه',
        ];
    }
}
