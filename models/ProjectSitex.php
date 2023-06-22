<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex".
 *
 * @property int $id
 * @property int $project_id
 * @property int $sitex_id
 * @property bool $done
 * @property int $phase
 * @property int $weight
 */
class ProjectSitex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'sitex_id'], 'required'],
            [['project_id', 'sitex_id', 'phase', 'weight'], 'default', 'value' => null],
            [['project_id', 'sitex_id', 'phase', 'weight'], 'integer'],
            [['done'], 'boolean'],
            [['project_id', 'sitex_id'], 'unique', 'targetAttribute' => ['project_id', 'sitex_id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseProjects::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['sitex_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseSitex::className(), 'targetAttribute' => ['sitex_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'پروژه',
            'sitex_id' => 'مرکز / سایت',
            'done' => 'اتمام کار',
            'phase' => 'فاز',
            'weight' => 'ضریب پیشرفت',
        ];
    }
}
