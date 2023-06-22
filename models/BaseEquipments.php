<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base.equipments".
 *
 * @property int $id
 * @property string $equipment
 * @property string|null $description
 */
class BaseEquipments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base.equipments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['equipment'], 'required'],
            [['equipment'], 'string', 'max' => 512],
            [['description'], 'string', 'max' => 1024],
            [['equipment'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'equipment' => 'تجهیز',
            'description' => 'توضیحات',
        ];
    }
}
