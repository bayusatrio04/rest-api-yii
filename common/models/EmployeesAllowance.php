<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employees_allowance".
 *
 * @property int $id
 * @property float $tunjangan_makan
 * @property float $tunjangan_jabatan
 * @property float $tunjangan_keluarga
 * @property float $tunjangan_transport
 * @property float $tunjangan_kehadiran
 * @property float $bpjs_kesehatan
 * @property float $bpjs_ketenagakerjaan
 * @property string $created_at
 * @property string $updated_at
 */
class EmployeesAllowance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees_allowance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tunjangan_makan', 'tunjangan_jabatan', 'tunjangan_keluarga', 'tunjangan_transport', 'tunjangan_kehadiran', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tunjangan_makan' => 'Tunjangan Makan',
            'tunjangan_jabatan' => 'Tunjangan Jabatan',
            'tunjangan_keluarga' => 'Tunjangan Keluarga',
            'tunjangan_transport' => 'Tunjangan Transport',
            'tunjangan_kehadiran' => 'Tunjangan Kehadiran',
            'bpjs_kesehatan' => 'Bpjs Kesehatan',
            'bpjs_ketenagakerjaan' => 'Bpjs Ketenagakerjaan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
