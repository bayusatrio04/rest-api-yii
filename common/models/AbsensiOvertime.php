<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use DateTime;

/**
 * This is the model class for table "absensi_overtime".
 *
 * @property int $id
 * @property string $employee_name
 * @property string $employee_id
 * @property string $department
 * @property string $position
 * @property string $overtime_date
 * @property string $start_time
 * @property string $end_time
 * @property float $total_hours
 * @property string $overtime_reason
 * @property bool $employee_signature
 * @property bool $supervisor_signature
 * @property bool $manager_signature
 * @property bool $hrd_signature
 * @property string|null $approval_date
 * @property string|null $overtime_tasks
 * @property float $overtime_rate
 * @property float $total_compensation
 * @property string|null $additional_notes
 */
class AbsensiOvertime extends ActiveRecord
{
    public static function tableName()
    {
        return 'absensi_overtime';
    }

    public function rules()
    {
        return [
            [['employee_name', 'employee_id', 'department', 'position', 'overtime_date', 'start_time', 'end_time', 'overtime_reason', 'overtime_rate'], 'required'],
            [['overtime_date', 'approval_date'], 'safe'],
            [['total_hours', 'overtime_rate', 'total_compensation'], 'number'],
            [['overtime_reason', 'overtime_tasks', 'additional_notes'], 'string'],
            [['employee_signature', 'supervisor_signature', 'manager_signature', 'hrd_signature'], 'boolean'],
            [['employee_name', 'employee_id', 'department', 'position', 'start_time', 'end_time'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Calculate total hours
            $start = new DateTime($this->overtime_date . ' ' . $this->start_time);
            $end = new DateTime($this->overtime_date . ' ' . $this->end_time);
            $interval = $start->diff($end);
            $hours = $interval->h + ($interval->i / 60);
            $this->total_hours = $hours;

            // Calculate total compensation
            $this->total_compensation = $this->total_hours * $this->overtime_rate;

            return true;
        }
        return false;
    }
}
