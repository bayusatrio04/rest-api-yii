<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "salaries".
 *
 * @property int $id
 * @property int $employee_id
 * @property string $tanggal_penggajian
 * @property float $total_gaji_pokok
 * @property float $total_tunjangan_jabatan
 * @property float $total_tunjangan_keluarga
 * @property float $total_tunjangan_makan
 * @property float $total_tunjangan_transport
 * @property float $total_tunjangan_kehadiran
 * @property float $total_bpjs_kesehatan
 * @property float $total_bpjs_ketenagakerjaan
 * @property float $persentase_pajak_pph_21
 * @property float $total_pajak_pph_21
 * @property float $total_gaji
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Employees $employee
 */
class Salaries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salaries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'tanggal_penggajian'], 'required'],
            [['employee_id'], 'integer'],
            [['tanggal_penggajian', 'created_at', 'updated_at'], 'safe'],
            [['total_gaji_pokok', 'total_tunjangan_jabatan', 'total_tunjangan_keluarga', 'total_tunjangan_makan', 'total_tunjangan_transport', 'total_tunjangan_kehadiran', 'total_bpjs_kesehatan', 'total_bpjs_ketenagakerjaan', 'persentase_pajak_pph_21', 'total_pajak_pph_21', 'total_gaji'], 'number'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'tanggal_penggajian' => 'Tanggal Penggajian',
            'total_gaji_pokok' => 'Total Gaji Pokok',
            'total_tunjangan_jabatan' => 'Total Tunjangan Jabatan',
            'total_tunjangan_keluarga' => 'Total Tunjangan Keluarga',
            'total_tunjangan_makan' => 'Total Tunjangan Makan',
            'total_tunjangan_transport' => 'Total Tunjangan Transport',
            'total_tunjangan_kehadiran' => 'Total Tunjangan Kehadiran',
            'total_bpjs_kesehatan' => 'Total Bpjs Kesehatan',
            'total_bpjs_ketenagakerjaan' => 'Total Bpjs Ketenagakerjaan',
            'persentase_pajak_pph_21' => 'Persentase Pajak Pph 21',
            'total_pajak_pph_21' => 'Total Pajak Pph 21',
            'total_gaji' => 'Total Gaji',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['id' => 'employee_id']);
    }
}
