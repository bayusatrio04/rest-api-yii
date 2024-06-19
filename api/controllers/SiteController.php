<?php

namespace api\controllers;
use yii\web\NotFoundHttpException;

use api\models\ResendVerificationEmailForm;
use api\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\LoginForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use yii\filters\auth\HttpBearerAuth;

use common\models\User;
use yii\rest\Controller; //Controller API


/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionLogin()
    {
        $model = new LoginForm();
     
        // $user = $model->getUser();
        // dd($user);
        if ($model->load(Yii::$app->request->post(), '') && ($token= $model->login())) {

            return [
           
                'status' => '200', 
                'access_token' => $token,
            ]; 
        } else {
            return $model;
        }
        

    }
    public function actionRegister()
    {
        $model = new SignupForm();
        $model->load(Yii::$app->request->post(), '');

        if ($model->signup()) {
            return [
                'status' => 'success',
                'message' => 'Registration successful.',
            ];
        } else {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return [
                'status' => 'error',
                'message' => 'Failed to register.',
                'errors' => $model->errors,
            ];
        }
    }
    public function actionRead($id)
    {
        $user = User::findOne($id);
    
        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }
    
        return [
            'status' => 'success',
            'data' => $user,
        ];
    }
    
    public function actionDelete($id)
    {
        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }

        if ($user->delete()) {
            return [
                'status' => 'success',
                'message' => 'Account deleted successfully.',
            ];
        } else {
            Yii::$app->response->statusCode = 500; // Internal Server Error
            return [
                'status' => 'error',
                'message' => 'Failed to delete account.',
            ];
        }

        
    }
    public function actionResetPasswordDefault($id)
    {
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }

        $user->setPassword('Djipay@123');
        if ($user->save()) {
            return [
                'status' => 'success',
                'message' => 'Password has been reset to default.',
            ];
        } else {
            Yii::$app->response->statusCode = 500; // Internal Server Error
            return [
                'status' => 'error',
                'message' => 'Failed to reset password.',
                'errors' => $user->errors,
            ];
        }
    }
    

    
    
    

    
    


}
