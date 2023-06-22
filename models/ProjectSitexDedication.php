<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_dedication".
 *
 * @property int $id
 * @property int $project_sitex_id
 * @property int $project_dedication_id
 * @property int $quantity
 * @property string|null $description
 */
class ProjectSitexDedication extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_dedication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_sitex_id', 'project_equipment_dedication_id', 'quantity'], 'required'],
            [['project_sitex_id', 'project_equipment_dedication_id', 'quantity'], 'default', 'value' => null],
            [['project_sitex_id', 'project_equipment_dedication_id', 'quantity'], 'integer'],
            [['description'], 'string', 'max' => 1024],
            [['project_sitex_id', 'project_equipment_dedication_id'], 'unique', 'targetAttribute' => ['project_sitex_id', 'project_equipment_dedication_id']],
            [['project_dedication_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectDedication::className(), 'targetAttribute' => ['project_dedication_id' => 'id']],
            [['project_sitex_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectSitex::className(), 'targetAttribute' => ['project_sitex_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_sitex_id' => 'مرکز/سایت',
            'project_dedication_id' => 'Project Dedication ID',
            'quantity' => 'تعداد',
            'description' => 'توضیحات',
        ];
    }
}
