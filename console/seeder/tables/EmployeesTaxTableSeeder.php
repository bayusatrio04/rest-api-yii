<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\EmployeesTax;

/**
 * Handles the creation of seeder `EmployeesTax::tableName()`.
 */
class EmployeesTaxTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        
        $currentYear = date("Y"); // Mendapatkan tahun sekarang
        $previousYear = $currentYear - 1; // Tahun sebelumnya
        
        $count = 15; // Jumlah data yang ingin dimasukkan
        $percentage = 0.001; // Persentase awal
        
        for ($i = 0; $i < $count; $i++) {
            $this->insert(EmployeesTax::tableName(), [
                'percentage' => $percentage,
                'masa_berlaku' => mt_rand($previousYear, $currentYear), // Mengatur tahun secara acak dari tahun sebelumnya hingga tahun sekarang
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ]);
        
            // Increment persentase
            $percentage += 0.001;
        }
        
        
    }
}
