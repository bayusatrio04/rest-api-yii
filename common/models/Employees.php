<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%employees}}".
 *
 * @property int $id
 * @property string|null $nama_depan
 * @property string|null $nama_belakang
 * @property string|null $email
 * @property string|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $no_telepon
 * @property string|null $status_nikah
 * @property int|null $jumlah_tanggungan
 * @property int|null $position_id
 * @property string|null $type_karyawan
 * @property int|null $created_at
 * @property int|null $updated_at
 
 *
 * @property EmployeesPosition $position
 */
class Employees extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employees}}';
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
            [['tanggal_lahir'], 'safe'],
            [['position_id', 'jumlah_tanggungan'], 'integer'],
            [['nama_depan', 'nama_belakang', 'email', 'jenis_kelamin', 'type_karyawan', 'created_at', 'updated_at', 'status_nikah'], 'string', 'max' => 255],
            [['no_telepon'], 'string', 'max' => 16],
            [['email'], 'unique'],
            [['no_telepon'], 'unique'],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesPosition::class, 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_depan' => 'Nama Depan',
            'nama_belakang' => 'Nama Belakang',
            'email' => 'Email',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'no_telepon' => 'No Telepon',
            'position_id' => 'Position ID',
            'type_karyawan' => 'Type Karyawan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\EmployeesPositionQuery
     */
    public function getPosition()
    {
        return $this->hasOne(EmployeesPosition::class, ['id' => 'position_id']);
    }
    public function getSalaries()
    {
        return $this->hasMany(Salaries::class, ['employee_id' => 'id']);
    }
    public function getUsers()
    {
        return $this->hasMany(User::class, ['employee_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\EmployeesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EmployeesQuery(get_called_class());
    }
}
