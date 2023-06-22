<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base.project_meta".
 *
 * @property int $id
 * @property int $project_id
 * @property string $key
 * @property string|null $value
 * @property int|null $order
 */
class BaseProjectMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base.project_meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'key'], 'required'],
            [['project_id', 'order'], 'default', 'value' => null],
            [['project_id', 'order'], 'integer'],
            [['key', 'value'], 'string', 'max' => 1024],
            [['project_id', 'key'], 'unique', 'targetAttribute' => ['project_id', 'key']],
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
            'project_id' => 'Project ID',
            'key' => 'Key',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }
}
