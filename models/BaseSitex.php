<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base.sitex".
 *
 * @property int $id
 * @property int $area
 * @property string $name
 * @property string $abbr
 * @property string $type
 * @property int|null $center_id
 * @property string|null $address
 */
class BaseSitex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base.sitex';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area', 'name', 'abbr', 'type'], 'required'],
            [['area', 'center_id'], 'default', 'value' => null],
            [['area', 'center_id'], 'integer'],
            [['address'], 'string'],
            [['name'], 'string', 'max' => 256],
            [['abbr'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
            [['center_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseSitex::className(), 'targetAttribute' => ['center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'شناسه',
            'area' => 'منطقه',
            'name' => 'نام',
            'abbr' => 'اختصار',
            'type' => 'نوع',
            'center_id' => 'مرکز مادر',
            'address' => 'آدرس',
        ];
    }
}
