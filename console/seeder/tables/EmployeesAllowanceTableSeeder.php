<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\EmployeesAllowance;

/**
 * Handles the creation of seeder `EmployeesAllowance::tableName()`.
 */
class EmployeesAllowanceTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        
        $count = 3;
        $tunjangan_makan_values = [90000, 100000, 110000];
        
        for ($i = 0; $i < $count; $i++) {
            $this->insert(EmployeesAllowance::tableName(), [
                'tunjangan_makan' => $tunjangan_makan_values[$i],
                'tunjangan_jabatan' => 0,
                'tunjangan_keluarga' => 0,
                'tunjangan_transport' => 0,
                'tunjangan_kehadiran' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ]);
        }
        
    }
}
