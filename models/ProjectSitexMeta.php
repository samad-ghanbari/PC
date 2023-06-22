<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project.sitex_meta".
 *
 * @property int $id
 * @property int $project_sitex_id
 * @property string $key
 * @property string|null $value
 * @property int $order
 */
class ProjectSitexMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project.sitex_meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_sitex_id', 'key'], 'required'],
            [['project_sitex_id', 'order'], 'default', 'value' => null],
            [['project_sitex_id', 'order'], 'integer'],
            [['key', 'value'], 'string', 'max' => 1024],
            [['project_sitex_id', 'key'], 'unique', 'targetAttribute' => ['project_sitex_id', 'key']],
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
            'project_sitex_id' => 'Project Sitex ID',
            'key' => 'Key',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }
}
