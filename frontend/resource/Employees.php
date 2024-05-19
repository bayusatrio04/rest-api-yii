<?php


namespace frontend\resource;


/**
 * Class Employees
 *

 * @package frontend\resource
 */
class Employees extends \common\models\Employees
{

    public function extraFields()
    {
        return ['employees'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
