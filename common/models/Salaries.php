<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%salaries}}".
 *
 * @property int $id
 * @property int $employee_id
 * @property int $position_salary_id
 * @property string $salary_date
 * @property float $basic_salary
 * @property float $meal_allowance
 * @property float $tax_amount
 * @property float $total_salary
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Employees $employee
 * @property PositionSalaries $positionSalary
 */
class Salaries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%salaries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'position_salary_id', 'salary_date'], 'required'],
            [['employee_id', 'position_salary_id'], 'integer'],
            [['salary_date', 'created_at', 'updated_at'], 'safe'],
            [['basic_salary', 'meal_allowance', 'tax_amount', 'total_salary'], 'number'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['position_salary_id'], 'exist', 'skipOnError' => true, 'targetClass' => PositionSalaries::class, 'targetAttribute' => ['position_salary_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'position_salary_id' => 'Position Salary ID',
            'salary_date' => 'Salary Date',
            'basic_salary' => 'Basic Salary',
            'meal_allowance' => 'Meal Allowance',
            'tax_amount' => 'Tax Amount',
            'total_salary' => 'Total Salary',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\EmployeesQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['id' => 'employee_id']);
    }

    /**
     * Gets query for [[PositionSalary]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PositionSalariesQuery
     */
    public function getPositionSalary()
    {
        return $this->hasOne(PositionSalaries::class, ['id' => 'position_salary_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SalariesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SalariesQuery(get_called_class());
    }
}
