<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%position_salaries}}".
 *
 * @property int $id
 * @property int $position_id
 * @property float $basic_salary
 * @property float $meal_allowance
 * @property float $tax_percentage
 * @property string $created_at
 * @property string $updated_at
 *
 * @property EmployeesPosition $position
 * @property Salaries[] $salaries
 */
class PositionSalaries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%position_salaries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id', 'basic_salary', 'meal_allowance', 'tax_percentage'], 'required'],
            [['position_id'], 'integer'],
            [['basic_salary', 'meal_allowance', 'tax_percentage'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesPosition::class, 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_id' => 'Position ID',
            'basic_salary' => 'Basic Salary',
            'meal_allowance' => 'Meal Allowance',
            'tax_percentage' => 'Tax Percentage',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\EmployeesPositionQuery
     */
    public function getPosition()
    {
        return $this->hasOne(EmployeesPosition::class, ['id' => 'position_id']);
    }

    /**
     * Gets query for [[Salaries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SalariesQuery
     */
    public function getSalaries()
    {
        return $this->hasMany(Salaries::class, ['position_salary_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PositionSalariesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PositionSalariesQuery(get_called_class());
    }
}
