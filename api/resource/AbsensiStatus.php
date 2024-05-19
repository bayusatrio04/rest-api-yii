<?php


namespace api\resource;


/**
 * Class AbsensiStatus
 *

 * @package api\resource
 */
class AbsensiStatus extends \common\models\AbsensiStatus
{

    public function extraFields()
    {
        return ['absensi-status'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
