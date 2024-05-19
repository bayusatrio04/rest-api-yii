<?php


namespace frontend\resource;


/**
 * Class EmployeesPosition
 *

 * @package frontend\resource
 */
class EmployeesPosition extends \common\models\EmployeesPosition
{

    public function extraFields()
    {
        return ['employeesPosition'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
