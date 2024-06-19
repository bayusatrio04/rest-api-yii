<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\User;
use common\models\Employees;
use common\models\EmployeesPosition;
use Yii;
use DateTime;
/**
 * Handles the creation of seeder `User::tableName()`.
 */
class UserTableSeeder extends TableSeeder
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $fakerDateTime = $this->faker->dateTimeThisYear;
        $created_at = new DateTime($fakerDateTime->format("Y-m-d H:i:s"));
        $updated_at = new DateTime($fakerDateTime->format("Y-m-d H:i:s"));
        $count = 10;
        $employeesPosition = EmployeesPosition::find()->all();
        for ($i = 0; $i < $count; $i++) {
            // Generate unique phone number
            do {
                $no_telepon = $this->faker->numerify('08##########');
                $existingEmployee = Employees::findOne(['no_telepon' => $no_telepon]);
            } while ($existingEmployee !== null);

            // Buat data karyawan baru
            $employee = new Employees();
            $employee->nama_depan = $this->faker->firstName;
            $employee->nama_belakang = $this->faker->lastName;
            $employee->email = $this->faker->unique()->safeEmail; // Menggunakan email yang unik
            $employee->tanggal_lahir = $this->faker->dateTimeThisYear->format("Y-m-d"); // Gunakan format yang valid
            $employee->jenis_kelamin = $this->faker->randomElement(['Perempuan', 'Laki-Laki']);
            $employee->no_telepon = $no_telepon;
            $employee->position_id = $this->faker->randomElement($employeesPosition)->id; // Pastikan $employeesPosition sudah didefinisikan sebelumnya
            $employee->type_karyawan = "Full Time";
            $employee->created_at = "2024-05-24"; // Gunakan format yang valid
            $employee->updated_at = "2024-05-24"; // Gunakan format yang valid
            $employee->save();

            // Buat data pengguna baru dan hubungkan dengan karyawan yang sesuai
            $user = new User();
            $user->username = $this->faker->userName;
            $user->email = $employee->email; // Gunakan email karyawan
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->password_hash = Yii::$app->security->generatePasswordHash('password123'); // Ganti dengan kata sandi yang sesuai
            $user->status = User::STATUS_ACTIVE;
            $user->created_at = $created_at->format("Y-m-d H:i:s");
            $user->updated_at = $updated_at->format("Y-m-d H:i:s");

            $user->employee_id = $employee->id;
            $user->save();

        
        }
    }
}
