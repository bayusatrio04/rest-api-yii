<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Employees;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    // public function signup()
    // {
    //     if (!$this->validate()) {
    //         return null;
    //     }
        
    //     $user = new User();
    //     $employees = new Employees();
    //     $user->username = $this->username;
    //     $user->email = $this->email;
    //     $user->setPassword($this->password);
    //     $user->generateAuthKey();
    //     $user->generateAccessToken();
       
    //     $user->generateEmailVerificationToken();

    //     return $user->save() && $this->sendEmail($user);
    // }
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $employees = new Employees(); // Buat objek Employees baru
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        // $user->generateAccessToken(); // Generate access token
        $user->generateEmailVerificationToken();

        // Simpan data user
        if (!$user->save()) {
            return null;
        }

        // Set data Employees
        $employees->nama_depan = $this->username; 
        $employees->email = $this->email; 
     
        // $employees->access_token = $user->access_token; 

 
        if (!$employees->save()) {
       
            $user->delete();
            return null;
        }


        $employeeId = $employees->id;


        $user->employee_id = $employeeId;
        return $user->save() && $this->sendEmail($user);
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
