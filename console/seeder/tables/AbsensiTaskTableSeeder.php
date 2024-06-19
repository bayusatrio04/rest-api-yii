<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\AbsensiTask;
use common\models\User;

/**
 * Handles the creation of seeder `AbsensiTask::tableName()`.
 */
class AbsensiTaskTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $user = User::find()->all();

        $count = 100;
        for ($i = 0; $i < $count; $i++) {
            $this->insert(AbsensiTask::tableName(), [
                'judul_task' => $this->faker->word,
				'deskripsi_task' => $this->faker->word,
				'latitude' => $this->faker->word,
				'longitude' => $this->faker->word,
				'alamat_task' => $this->faker->word,
				'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'created_by' => $this->faker->randomElement($user)->id,
            ]);
        }
    }
}
