<?php

namespace common\models;

use Yii;
use yii\base\Model;

use common\models\Employees;
/**
 * Login form
 */
class AdminLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    // public function login()
    // {
    //     if ($this->validate()) {
    //         return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    //     }
        
    //     return false;
    // }
    public function login()
    {
        if ($this->validate()) {
            // Ambil data pengguna berdasarkan employee_id
            $user = $this->getUserByEmployeeId();
            if ($user && Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                return true;
            }
        }
        return false;
    }
protected function getUserByEmployeeId()
{
    if (!$this->_user) {
        // Cari pengguna berdasarkan employee_id
        $user = User::find()->where(['employee_id' => $this->employee_id])->one();
        if ($user) {
            // Ambil data Employees berdasarkan employee_id
            $employee = Employees::findOne($this->employee_id);
            if ($employee) {
                // Jika position_id == 2, pengguna adalah admin; jika tidak, pengguna adalah karyawan
                $user->role = ($employee->position_id == 2) ? 'admin' : 'karyawan';
            }
            $this->_user = $user;
        }
    }
    return $this->_user;
}


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function generateAccessToken()
    {
        $user = $this->getUser();
        if ($user) {
            $user->generateAccessToken();
            return true;
        }
        return false;
    }
}
