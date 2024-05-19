<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%absensi_type}}".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AbsensiLog[] $absensiLogs
 */
class AbsensiType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%absensi_type}}';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return date('d-m-Y H:i:s');
                },
            ],
            // [
            //     'class' => BlameableBehavior::class,
            //     'updatedByAttribute' => false
            // ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['created_at', 'updated_at'], 'integer'],
            [['type', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AbsensiLogs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AbsensiLogQuery
     */
    public function getAbsensiLogs()
    {
        return $this->hasMany(AbsensiLog::class, ['id_absensi_type' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AbsensiTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AbsensiTypeQuery(get_called_class());
    }
}
