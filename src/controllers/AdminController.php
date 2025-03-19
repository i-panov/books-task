<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    public function behaviors(): array
    {
        return [
            'class' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'verbs' => ['get'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'verbs' => ['get', 'post'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'verbs' => ['post'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
