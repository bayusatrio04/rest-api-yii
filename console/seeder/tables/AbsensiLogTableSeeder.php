<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\AbsensiLog;
use common\models\AbsensiType;
use common\models\AbsensiStatus;
use common\models\User;
use DateTime;
use DatePeriod;
use DateInterval;
/**
 * Handles the creation of seeder `AbsensiLog::tableName()`.
 */
class AbsensiLogTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Membuat rentang tanggal dari  March 2024 hingga May 2024
        $startDateMarch = new DateTime('2024-03-01');
        $endDateMarch = new DateTime('2024-03-31');

        $startDateApril = new DateTime('2024-04-01');
        $endDateApril = new DateTime('2024-04-30');

        $startDateMay = new DateTime('2024-05-01');
        $endDateMay = new DateTime('2024-05-31');
        $interval = new DateInterval('P1D');

        $datePeriodMarch = new DatePeriod($startDateMarch, $interval, $endDateMarch->add($interval));
        $datePeriodApril = new DatePeriod($startDateApril, $interval, $endDateApril->add($interval));
        $datePeriodMay = new DatePeriod($startDateMay, $interval, $endDateMay->add($interval));

        foreach ($datePeriodMarch as $date) {
            $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

            if ($dayOfWeek == 7) {
                // Skip Sunday
                continue;
            }

            // Determine check-out time based on the day of the week
            if ($dayOfWeek == 6) {
                // Saturday: 08:00 - 14:00
                $checkOutTime = '14:00:00';
            } else {
                // Monday - Friday: 08:00 - 16:00
                $checkOutTime = '16:00:00';
            }

            $dateString = $date->format('Y-m-d');
            $latitude = $this->generateRandomLatitude();
            $longitude = $this->generateRandomLongitude();
            $dayOfWeekText = $date->format('l');
            $buktiHadir = $this->generateRandomBuktiHadir();
            $createdBy = 1;
            // Data CHECK-IN
            $this->insert(AbsensiLog::tableName(), [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 1,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => '08:00:00',
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

            // Data CHECK-OUT
            $this->insert(AbsensiLog::tableName(), [
                'created_at' =>date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 2,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => $checkOutTime,
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

           
        }
        // foreach ($datePeriodMarch as $date) {
        //     $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

        //     if ($dayOfWeek == 7) {
        //         // Skip Sunday
        //         continue;
        //     }

        //     // Determine check-out time based on the day of the week
        //     if ($dayOfWeek == 6) {
        //         // Saturday: 08:00 - 14:00
        //         $checkOutTime = '14:00:00';
        //     } else {
        //         // Monday - Friday: 08:00 - 16:00
        //         $checkOutTime = '16:00:00';
        //     }

        //     $dateString = $date->format('Y-m-d');
        //     $latitude = $this->generateRandomLatitude();
        //     $longitude = $this->generateRandomLongitude();
        //     $dayOfWeekText = $date->format('l');
        //     $buktiHadir = $this->generateRandomBuktiHadir();
        //     $createdBy = 10;
        //     // Data CHECK-IN
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 1,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => '08:00:00',
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

        //     // Data CHECK-OUT
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' =>date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 2,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => $checkOutTime,
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

           
        // }

        foreach ($datePeriodApril as $date) {
            $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

            if ($dayOfWeek == 7) {
                // Skip Sunday
                continue;
            }

            // Determine check-out time based on the day of the week
            if ($dayOfWeek == 6) {
                // Saturday: 08:00 - 14:00
                $checkOutTime = '14:00:00';
            } else {
                // Monday - Friday: 08:00 - 16:00
                $checkOutTime = '16:00:00';
            }

            $dateString = $date->format('Y-m-d');
            $latitude = $this->generateRandomLatitude();
            $longitude = $this->generateRandomLongitude();
            $dayOfWeekText = $date->format('l');
            $buktiHadir = $this->generateRandomBuktiHadir();
            $createdBy = 1;
            // Data CHECK-IN
            $this->insert(AbsensiLog::tableName(), [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 1,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => '08:00:00',
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

            // Data CHECK-OUT
            $this->insert(AbsensiLog::tableName(), [
                'created_at' =>date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 2,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => $checkOutTime,
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

           
        }
        // foreach ($datePeriodApril as $date) {
        //     $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

        //     if ($dayOfWeek == 7) {
        //         // Skip Sunday
        //         continue;
        //     }

        //     // Determine check-out time based on the day of the week
        //     if ($dayOfWeek == 6) {
        //         // Saturday: 08:00 - 14:00
        //         $checkOutTime = '14:00:00';
        //     } else {
        //         // Monday - Friday: 08:00 - 16:00
        //         $checkOutTime = '16:00:00';
        //     }

        //     $dateString = $date->format('Y-m-d');
        //     $latitude = $this->generateRandomLatitude();
        //     $longitude = $this->generateRandomLongitude();
        //     $dayOfWeekText = $date->format('l');
        //     $buktiHadir = $this->generateRandomBuktiHadir();
        //     $createdBy = 10;
        //     // Data CHECK-IN
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 1,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => '08:00:00',
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

        //     // Data CHECK-OUT
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' =>date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 2,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => $checkOutTime,
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

           
        // }

        foreach ($datePeriodMay as $date) {
            $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

            if ($dayOfWeek == 7) {
                // Skip Sunday
                continue;
            }

            // Determine check-out time based on the day of the week
            if ($dayOfWeek == 6) {
                // Saturday: 08:00 - 14:00
                $checkOutTime = '14:00:00';
            } else {
                // Monday - Friday: 08:00 - 16:00
                $checkOutTime = '16:00:00';
            }

            $dateString = $date->format('Y-m-d');
            $latitude = $this->generateRandomLatitude();
            $longitude = $this->generateRandomLongitude();
            $dayOfWeekText = $date->format('l');
            $buktiHadir = $this->generateRandomBuktiHadir();
            $createdBy = 1;
            // Data CHECK-IN
            $this->insert(AbsensiLog::tableName(), [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 1,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => '08:00:00',
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

            // Data CHECK-OUT
            $this->insert(AbsensiLog::tableName(), [
                'created_at' =>date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'id_absensi_type' => 2,
                'id_absensi_status' => 2,
                'day'=> $dayOfWeekText,
                'tanggal_absensi' => $dateString,
                'waktu_absensi' => $checkOutTime,
                'latitude' => $latitude,
                'longitude' => $longitude,
          
                // 'bukti_hadir' => $buktiHadir,
            ]);

           
        }
        // foreach ($datePeriodMay as $date) {
        //     $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)

        //     if ($dayOfWeek == 7) {
        //         // Skip Sunday
        //         continue;
        //     }

        //     // Determine check-out time based on the day of the week
        //     if ($dayOfWeek == 6) {
        //         // Saturday: 08:00 - 14:00
        //         $checkOutTime = '14:00:00';
        //     } else {
        //         // Monday - Friday: 08:00 - 16:00
        //         $checkOutTime = '16:00:00';
        //     }

        //     $dateString = $date->format('Y-m-d');
        //     $latitude = $this->generateRandomLatitude();
        //     $longitude = $this->generateRandomLongitude();
        //     $dayOfWeekText = $date->format('l');
        //     $buktiHadir = $this->generateRandomBuktiHadir();
        //     $createdBy = 10;
        //     // Data CHECK-IN
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 1,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => '08:00:00',
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

        //     // Data CHECK-OUT
        //     $this->insert(AbsensiLog::tableName(), [
        //         'created_at' =>date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_by' => $createdBy,
        //         'id_absensi_type' => 2,
        //         'id_absensi_status' => 2,
        //         'day'=> $dayOfWeekText,
        //         'tanggal_absensi' => $dateString,
        //         'waktu_absensi' => $checkOutTime,
        //         'latitude' => $latitude,
        //         'longitude' => $longitude,
          
        //         'bukti_hadir' => $buktiHadir,
        //     ]);

           
        // }
    }
        // Generate random latitude
        private function generateRandomLatitude()
        {
            // Example: Generating latitude between -90 and 90
            return mt_rand(-90, 90);
        }
    
        // Generate random longitude
        private function generateRandomLongitude()
        {
            // Example: Generating longitude between -180 and 180
            return mt_rand(-180, 180);
        }
        private function generateRandomBuktiHadir()
        {
            // Define an array of possible bukti hadir values
            $buktiHadirValues = ['Fingerprint', 'Kartu Absensi', 'Wajah', 'Tanda Tangan'];
    
            // Return a random value from the array
            return $buktiHadirValues[array_rand($buktiHadirValues)];
        }

}
