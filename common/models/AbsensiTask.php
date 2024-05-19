<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%absensi_task}}".
 *
 * @property int $id
 * @property string|null $judul_task
 * @property string|null $deskripsi_task
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $alamat_task
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 */
class AbsensiTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%absensi_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alamat_task'], 'string'],
            [[ 'created_by'], 'integer'],
            [['judul_task', 'deskripsi_task', 'latitude', 'longitude'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul_task' => 'Judul Task',
            'deskripsi_task' => 'Deskripsi Task',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'alamat_task' => 'Alamat Task',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AbsensiLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AbsensiLogQuery(get_called_class());
    }
}
