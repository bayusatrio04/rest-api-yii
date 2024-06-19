<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employees_tax".
 *
 * @property int $id
 * @property float|null $percentage
 * @property int $masa_berlaku
 * @property string $created_at
 * @property string $updated_at
 */
class EmployeesTax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees_tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percentage'], 'number'],
            [['masa_berlaku'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percentage' => 'Percentage',
            'masa_berlaku' => 'Masa Berlaku',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
