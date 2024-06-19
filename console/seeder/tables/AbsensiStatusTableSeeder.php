<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\AbsensiStatus;

/**
 * Handles the creation of seeder `AbsensiStatus::tableName()`.
 */
class AbsensiStatusTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Data yang sudah ditentukan
        $data = [
            [
                'id' => 1,
                'status' => 'On-Progress',
                'description' => 'Admin sedang melakukan pengecekan. Mohon Tunggu !',
                'created_at' => '2024-05-10 02:49:10',
                'updated_at' => '2024-05-10 02:55:46',
            ],
            [
                'id' => 2,
                'status' => 'Completed',
                'description' => 'Admin telah menyetujui kehadiran karyawan tersebut',
                'created_at' => '2024-05-10 02:49:10',
                'updated_at' => '2024-05-10 02:49:10',
            ],
        ];

        // Sisipkan data ke tabel AbsensiStatus
        foreach ($data as $item) {
            $this->insert(AbsensiStatus::tableName(), $item);
        }
    }
}
