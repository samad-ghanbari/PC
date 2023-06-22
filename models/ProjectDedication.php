<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.equipment_dedication".
 *
 * @property int $id
 * @property int $project_equipment_id
 * @property int $area
 * @property int $quantity
 * @property string|null $description
 */
class ProjectDedication extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.dedication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_equipment_id', 'area', 'quantity'], 'required'],
            [['project_equipment_id', 'area', 'quantity'], 'default', 'value' => null],
            [['project_equipment_id', 'area', 'quantity'], 'integer'],
            [['description'], 'string', 'max' => 1024],
            [['project_equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectEquipments::className(), 'targetAttribute' => ['project_equipment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_equipment_id' => 'Project Equipment ID',
            'area' => 'منطقه',
            'quantity' => 'تعداد تخصیص',
            'description' => 'توضیحات',
        ];
    }
}
