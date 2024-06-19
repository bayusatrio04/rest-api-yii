<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\AbsensiOvertime;

/**
 * Handles the creation of seeder `AbsensiOvertime::tableName()`.
 */
class AbsensiOvertimeTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        
        $count = 1;
        for ($i = 0; $i < $count; $i++) {
            $this->insert(AbsensiOvertime::tableName(), [
                'employee_name' => $this->faker->word,
				'employee_id' => $this->faker->word,
				'department' => $this->faker->word,
				'position' => $this->faker->word,
				'overtime_date' => 2024-04-01,
				'start_time' => '16:00',
				'end_time' => '21:00',
				'total_hours' => 5,
				'overtime_reason' => $this->faker->word,
				'employee_signature' => $this->faker->boolean,
				'supervisor_signature' => $this->faker->boolean,
				'manager_signature' => $this->faker->boolean,
				'hrd_signature' => $this->faker->boolean,
				'approval_date' => $this->faker->word,
				'overtime_tasks' => $this->faker->word,
				'overtime_rate' => $this->faker->word,
				'total_compensation' => $this->faker->word,
				'additional_notes' => $this->faker->word,
            ]);
        }
    }
}
