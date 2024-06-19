<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\Post;
use common\models\User;

/**
 * Handles the creation of seeder `Post::tableName()`.
 */
class PostTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $user = User::find()->all();

        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $this->insert(Post::tableName(), [
                'title' => $this->faker->word,
				'body' => $this->faker->word,
				'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'created_by' => $this->faker->randomElement($user)->id,
            ]);
        }
    }
}
