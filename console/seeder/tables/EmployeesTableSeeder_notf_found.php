<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\Employees;
use common\models\EmployeesPosition;

/**
 * Handles the creation of seeder `Employees::tableName()`.
 */
class EmployeesTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $employeesPosition = EmployeesPosition::find()->all();

        $count = 30;
        $jenis_kelamin = $this->faker->randomElement(['Perempuan', 'Laki-Laki']);
      


        for ($i = 0; $i < $count; $i++) {
            // Generate unique phone number
            do {
                $no_telepon = $this->faker->numerify('08##########');
                $existingEmployee = Employees::findOne(['no_telepon' => $no_telepon]);
            } while ($existingEmployee !== null);

            // Insert employee data
            $this->insert(Employees::tableName(), [
                'nama_depan' => $this->faker->word,
                'nama_belakang' => $this->faker->word,
                'email' =>$this->faker->email,
                'tanggal_lahir' => $this->faker->dateTime()->format("Y-m-d"),
                'jenis_kelamin' => $jenis_kelamin,
                'no_telepon' => $no_telepon,
                'position_id' => $this->faker->randomElement($employeesPosition)->id,
                'type_karyawan' => "Full Time",
                'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
                'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}

