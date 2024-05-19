<?php


namespace frontend\resource;


/**
 * Class User
 *

 * @package frontend\resource
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
