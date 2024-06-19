<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\AbsensiType;

/**
 * Handles the creation of seeder `AbsensiType::tableName()`.
 */
class AbsensiTypeTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        
        $data = [
            [
                'id' => 1,
                'type' => 'CHECK-IN',
                'description' => 'Absen Masuk Kerja/Kantor',
                'created_at' => date('Y-m-d '),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'id' => 2,
                'type' => 'CHECK-OUT',
                'description' => 'Absen Keluar/Pulang Kerja',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'id' => 3,
                'type' => 'LEFT-IN',
                'description' => 'Absen Izin Masuk dari Tugas Kantor/Kerja',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'id' => 4,
                'type' => 'LEFT-OUT',
                'description' => 'Absen Izin Keluar/Dinas dari Tugas Kantor/Kerja',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
            [
                'id' => 5,
                'type' => 'HALF-DAY',
                'description' => 'Berkerja Setengah Hari',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            $this->insert(AbsensiType::tableName(), $item);
        }
    }
}
