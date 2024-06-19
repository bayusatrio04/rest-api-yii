<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\EmployeesPositionSalaries;

/**
 * Handles the creation of seeder `EmployeesPositionSalaries::tableName()`.
 */
class EmployeesPositionSalariesTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {

        $count = 1;
            $gaji_pokok =[
                [
                'gaji_pokok' => 2650000,
				'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                ],
            ];
            $gaji_pokok_manager =[
                [
                'gaji_pokok' => 4500000,
				'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                ],
            ];
            foreach ($gaji_pokok as $salary) {
                for ($i = 0; $i < $count; $i++) {
                    $this->insert(EmployeesPositionSalaries::tableName(), $salary);
                }
            }
            
            // Loop untuk memasukkan gaji kedua
            foreach ($gaji_pokok_manager as $salary) {
                for ($i = 0; $i < $count; $i++) {
                    $this->insert(EmployeesPositionSalaries::tableName(), $salary);
                }
            }
        }
    }

