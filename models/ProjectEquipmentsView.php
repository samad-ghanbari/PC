<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.equipments_view".
 *
 * @property int|null $id
 * @property int|null $project_id
 * @property string|null $project_name
 * @property string|null $office
 * @property int|null $ts
 * @property int|null $equipment_id
 * @property string|null $equipment
 * @property string|null $e_desc
 * @property int|null $quantity
 * @property string|null $pe_desc
 */
class ProjectEquipmentsView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.equipments_view';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'ts', 'equipment_id', 'quantity'], 'default', 'value' => null],
            [['id', 'project_id', 'ts', 'equipment_id', 'quantity'], 'integer'],
            [['project_name', 'office'], 'string', 'max' => 256],
            [['equipment'], 'string', 'max' => 512],
            [['e_desc', 'pe_desc'], 'string', 'max' => 1024],
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
            'project_name' => 'پروژه',
            'office' => 'اداره کل',
            'ts' => 'زمان',
            'equipment_id' => 'شناسه تجهیز',
            'equipment' => 'تجهیز',
            'e_desc' => 'توضیحات تجهیز',
            'quantity' => 'تعداد',
            'pe_desc' => 'توضیحات',
        ];
    }
}
