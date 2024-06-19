<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employees_position".
 *
 * @property int $id
 * @property int|null $position_salary_id
 * @property string $position_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Employees[] $employees
 * @property EmployeesPositionSalaries $positionSalary
 */
class EmployeesPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_salary_id'], 'integer'],
            [['position_name', 'description', 'created_at', 'updated_at'], 'required'],
            [['position_name', 'description', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['position_salary_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesPositionSalaries::class, 'targetAttribute' => ['position_salary_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_salary_id' => 'Position Salary ID',
            'position_name' => 'Position Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::class, ['position_id' => 'id']);
    }

    /**
     * Gets query for [[PositionSalary]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPositionSalary()
    {
        return $this->hasOne(EmployeesPositionSalaries::class, ['id' => 'position_salary_id']);
    }
}
