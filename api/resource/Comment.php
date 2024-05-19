<?php


namespace api\resource;


/**
 * Class 
 *
 * @author 
 * @package api\resource
 */
class Comment extends \common\models\Comment
{

    public function extraFields()
    {
        return ['post'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }
}
