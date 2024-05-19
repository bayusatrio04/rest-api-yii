<?php


namespace api\resource;


/**
 * Class User
 *

 * @package api\resource
 */
class User extends \common\models\User
{

    public function extraFields()
    {
        return ['user'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
