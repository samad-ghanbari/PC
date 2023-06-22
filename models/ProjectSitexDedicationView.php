<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_dedication_view".
 *
 * @property int|null $id
 * @property int|null $project_sitex_id
 * @property int|null $area
 * @property string|null $name
 * @property string|null $abbr
 * @property string|null $type
 * @property string|null $address
 * @property int|null $phase
 * @property int|null $project_id
 * @property int|null $project_dedication_id
 * @property int|null $quantity
 * @property string|null $description
 */
class ProjectSitexDedicationView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_dedication_view';
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
            [['id', 'project_sitex_id', 'area', 'phase', 'project_id', 'project_dedication_id', 'quantity'], 'default', 'value' => null],
            [['id', 'project_sitex_id', 'area', 'phase', 'project_id', 'project_dedication_id', 'quantity'], 'integer'],
            [['address'], 'string'],
            [['name'], 'string', 'max' => 256],
            [['abbr'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 1024],
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
            'area' => 'منطقه',
            'name' => 'نام مرکز/سایت',
            'abbr' => 'اختصار',
            'type' => 'نوع مرکز/سایت',
            'address' => 'آدرس',
            'phase' => 'فاز',
            'project_id' => 'پروژه',
            'project_dedication_id' => 'Project Dedication ID',
            'quantity' => 'تعداد تخصیص',
            'description' => 'توضیحات',
        ];
    }
}
