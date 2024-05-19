<?php

namespace common\models;
use Yii;
use yii\base\Model;

class SalaryCalculator extends Model
{
    public $hourlyRate;

    public function rules()
    {
        return [
            [['hourlyRate'], 'required'],
            [['hourlyRate'], 'number'],
        ];
    }

    public function calculateSalary()
    {
        // Menghitung total jam dalam sebulan
        $totalHoursWeekdays = 8 * 5 * 4; // 8 jam x 5 hari x 4 minggu
        $totalHoursWeekend = 6 * 4;      // 6 jam x 1 hari (Sabtu) x 4 minggu
        $totalHours = $totalHoursWeekdays + $totalHoursWeekend;

        // Menghitung gaji
        $salary = $this->hourlyRate * $totalHours;

        return $salary;
    }
}
