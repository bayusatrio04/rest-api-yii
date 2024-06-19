<?php

namespace console\seeder\tables;

use diecoding\seeder\TableSeeder;
use common\models\Comment;
use common\models\Post;
use common\models\User;

/**
 * Handles the creation of seeder `Comment::tableName()`.
 */
class CommentTableSeeder extends TableSeeder
{
    // public $truncateTable = false;
    // public $locale = 'en_US';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $post = Post::find()->all();
        $user = User::find()->all();

        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $this->insert(Comment::tableName(), [
                'title' => $this->faker->word,
				'body' => $this->faker->word,
				'post_id' => $this->faker->randomElement($post)->id,
				'created_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'updated_at' => $this->faker->dateTime()->format("Y-m-d H:i:s"),
				'created_by' => $this->faker->randomElement($user)->id,
            ]);
        }
    }
}
