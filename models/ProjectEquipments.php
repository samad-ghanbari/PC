<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.equipments".
 *
 * @property int $id
 * @property int $project_id
 * @property int $equipment_id
 * @property int $quantity
 * @property string|null $description
 */
class ProjectEquipments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.equipments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'equipment_id'], 'required'],
            [['project_id', 'equipment_id', 'quantity'], 'default', 'value' => null],
            [['project_id', 'equipment_id', 'quantity'], 'integer'],
            [['description'], 'string', 'max' => 1024],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseEquipments::className(), 'targetAttribute' => ['equipment_id' => 'id']],
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
            'project_id' => 'پروژه',
            'equipment_id' => 'تجهیز',
            'quantity' => 'تعداد خرید',
            'description' => 'توضیحات خرید',
        ];
    }
}
