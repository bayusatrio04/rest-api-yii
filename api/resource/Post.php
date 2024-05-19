<?php

namespace api\resource;


/**
 * Class Post

 * @package api\resource
 */
class Post extends \common\models\Post
{
    /**
 
     * @var Comment[]
     */
    public $postComments = [];

    public function extraFields()
    {
        return ['comments', 'createdBy'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function load($data, $formName = null)
    {
        $this->postComments = [];
        if ($this->isNewRecord && isset($data['comments'])) {

            foreach ($data['comments'] as $commentData) {
                $comment = new Comment();
                $comment->load($commentData, "");
                $this->postComments[] = $comment;
            }
        }
        return parent::load($data, $formName);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $wasNewRecord = $this->isNewRecord;
        $result = parent::save($runValidation, $attributeNames);
        if ($wasNewRecord && $this->postComments) {
            foreach ($this->postComments as $comment) {
                $comment->post_id = $this->id;
                if (!$comment->save()) {
                    $this->addError('comments', array_values($comment->errors));
                    $transaction->rollBack();
                    return false;
                }
            }
        }
        $transaction->commit();
        return $result;
    }
}
