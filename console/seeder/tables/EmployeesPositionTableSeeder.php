<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\EmployeesPosition;
use common\models\EmployeesPositionSalaries;

/**
 * Handles the creation of seeder `EmployeesPosition::tableName()`.
 */
class EmployeesPositionTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $employeesPositionSalaries = EmployeesPositionSalaries::find()->all();

        
        $positions = [
            [
                'position_name' => 'General Manager',
                'position_salary_id'=> 2,
                'description' => 'Mengatur Jalannya Proses Bisnis Perusahaan',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Produksi',
                'position_salary_id'=> 1,
                'description' => 'Produksi adalah bagian dari aktivitas ekonomi seperti membuat, menciptakan, hingga menghasilkan barang atau jasa',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Penjualan',
                'position_salary_id'=> 1,
                'description' => 'Divisi Penjualan (salesman/ saleswoman) merupakan orang-orang yang berdiri di garda terdepan pada transaksi jual-beli barang dan/atau jasa produksi perusahaan.',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Keuangan',
                'position_salary_id'=> 1,
                'description' => 'Divisi Keuangan bertugas menganalisis laporan itu. Selain itu, Divisi Keuangan juga bertugas melakukan eksekusi pembayaran tagihan.',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Media Sosial',
                'position_salary_id'=> 1,
                'description' => 'Divisi Social Media bertugas memanfaatkan media baru dan alat-alat digital sebagai upaya dalam kegiatan publikasi, pemasaran, periklanan, dan promosi dari berbagai produk serta aktivitas perusahaan.',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],

            [
                'position_name' => 'Human Resource Development',
                'position_salary_id'=> 1,
                'description' => 'Human Resource Development adalah bagian dari manajemen sumber daya manusia yang secara khusus menangani pelatihan dan pengembangan karyawan dalam organisasi. Pengembangan SDM yang dimaksud mencakup pelatihan dan memberikan kesempatan kepada karyawan untu',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Editor',
                'position_salary_id'=> 1,
                'description' => 'Memegang kamera',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Team Lapangan',
                'position_salary_id'=> 1,
                'description' => 'Survey Lokasi Pemancingan',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Team Leader',
                'position_salary_id'=> 1,
                'description' => 'Mengatur Team Lapangan',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Admin Penjualan',
                'position_salary_id'=> 1,
                'description' => 'Rekap Penjualan',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
            [
                'position_name' => 'Design Graphic',
                'position_salary_id'=> 1,
                'description' => 'Membuat Desain terkait perusahaan',
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ],
        ];

        foreach ($positions as $position) {
            $this->insert(EmployeesPosition::tableName(), $position);
        }
    }
}
