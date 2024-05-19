<?php


namespace frontend\resource;


/**
 * Class AbsensiLog
 *

 * @package frontend\resource
 */
class AbsensiLog extends \common\models\AbsensiLog
{

    public function extraFields()
    {
        return ['absensi-log'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
