<?php

namespace api\modules\typeabsence\controllers;

use Yii;
use frontend\resource\AbsensiType;
use yii\rest\ActiveController;
use yii\web\Response;

class TypeAbsenceController extends ActiveController
{
    public $modelClass = AbsensiType::class;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if ($response->data !== null && $response->statusCode == 200) {
                $response->setStatusCode(201);
            }
        });
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'create' => ['POST'],
        ];
    }

    /**
     * Creates a new AbsensiType model.
     * If creation is successful, returns the created model with status code 201.
     * @return AbsensiType|array
     */
    public function actionCreate()
    {
        $model = new AbsensiType();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save()) {
            return $model;
        } else {
            return $model->getErrors();
        }
    }
}
