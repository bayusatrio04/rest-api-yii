<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use console\seeder\tables\EmployeesPositionSalariesTableSeeder;
use console\seeder\tables\EmployeesPositionTableSeeder;
use console\seeder\tables\AbsensiTypeTableSeeder;
use console\seeder\tables\AbsensiStatusTableSeeder;
use console\seeder\tables\UserTableSeeder;
use console\seeder\tables\EmployeesAllowanceTableSeeder;
use console\seeder\tables\EmployeesTaxTableSeeder;
use console\seeder\tables\AbsensiLogTableSeeder;

class AllSeeder extends TableSeeder
{
    public function run()
    {
        // Seeder untuk Model1
        EmployeesPositionSalariesTableSeeder::create()->run();

        // Seeder untuk Model2
        EmployeesPositionTableSeeder::create()->run();
        UserTableSeeder::create()->run();
        AbsensiTypeTableSeeder::create()->run();
        AbsensiStatusTableSeeder::create()->run();
        EmployeesAllowanceTableSeeder::create()->run();
        EmployeesTaxTableSeeder::create()->run();
        // Seeder untuk Model3

        // AbsensiLogTableSeeder::create()->run();

        // Dan seterusnya sesuai urutan yang Anda tentukan
    }
}
