<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%absensi_log}}".
 *
 * @property int $id
 * @property int|null $id_absensi_type
 * @property int|null $id_absensi_status
 * @property string|null $tanggal_absensi
 * @property string|null $waktu_absensi
 * @property string|null $day
 * @property string|null $keterangan
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $bukti_hadir
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 *
 * @property AbsensiStatus $absensiStatus
 * @property AbsensiType $absensiType
 * @property User $createdBy
 */
class AbsensiLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%absensi_log}}';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_absensi_type', 'id_absensi_status', 'created_by'], 'integer'],
            [['tanggal_absensi', 'waktu_absensi', 'tanggal_lahir'], 'safe'],
            [['latitude', 'longitude', 'bukti_hadir', 'day', 'keterangan'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['id_absensi_status'], 'exist', 'skipOnError' => true, 'targetClass' => AbsensiStatus::class, 'targetAttribute' => ['id_absensi_status' => 'id']],
            [['id_absensi_type'], 'exist', 'skipOnError' => true, 'targetClass' => AbsensiType::class, 'targetAttribute' => ['id_absensi_type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_absensi_type' => 'Id Absensi Type',
            'id_absensi_status' => 'Id Absensi Status',
            'tanggal_absensi' => 'Tanggal Absensi',
            'waktu_absensi' => 'Waktu Absensi',
            'day' => 'Day',
            'keterangan' => 'Keterangan',
            'tanggal_lahir' => 'Tanggal Lahir',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'bukti_hadir' => 'Bukti Hadir',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[AbsensiStatus]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AbsensiStatusQuery
     */
    public function getAbsensiStatus()
    {
        return $this->hasOne(AbsensiStatus::class, ['id' => 'id_absensi_status']);
    }

    /**
     * Gets query for [[AbsensiType]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AbsensiTypeQuery
     */
    public function getAbsensiType()
    {
        return $this->hasOne(AbsensiType::class, ['id' => 'id_absensi_type']);
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
