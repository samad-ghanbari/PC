<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_view".
 *
 * @property int|null $id
 * @property int|null $project_id
 * @property int|null $sitex_id
 * @property int|null $area
 * @property string|null $name
 * @property string|null $abbr
 * @property string|null $type
 * @property int|null $center_id
 * @property string|null $center_name
 * @property string|null $center_abbr
 * @property string|null $address
 * @property bool|null $done
 * @property int|null $phase
 * @property int|null $weight
 */
class ProjectSitexView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_view';
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
            [['id', 'project_id', 'sitex_id', 'area', 'center_id', 'phase', 'weight'], 'default', 'value' => null],
            [['id', 'project_id', 'sitex_id', 'area', 'center_id', 'phase', 'weight'], 'integer'],
            [['address'], 'string'],
            [['done'], 'boolean'],
            [['name', 'center_name', 'center_abbr'], 'string', 'max' => 256],
            [['abbr'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'شناسه',
            'project_id' => 'پروژه',
            'sitex_id' => 'شناسه سایت',
            'area' => 'منطقه',
            'name' => 'نام مرکز/سایت',
            'abbr' => 'اختصار',
            'type' => 'نوع مرکز/سایت',
            'center_id' => 'شناسه مرکز',
            'center_name' => 'مرکز اصلی',
            'center_abbr' => 'اختصار مرکز اصلی',
            'address' => 'آدرس',
            'done' => 'اتمام کار',
            'phase' => 'فاز',
            'weight' => 'ضریب پیشرفت',
        ];
    }
}
