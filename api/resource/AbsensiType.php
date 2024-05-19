<?php


namespace api\resource;


/**
 * Class AbsensiType
 *

 * @package api\resource
 */
class AbsensiType extends \common\models\AbsensiType
{

    public function extraFields()
    {
        return ['absensi-type'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPost()
    // {
    //     return $this->hasOne(Post::class, ['id' => 'post_id']);
    // }
}
