<?php

namespace app\controllers;

use app\models\Book;
use app\models\Category;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'categories' => Category::find()->where(['parent_id' => null])->all(),
        ]);
    }

    public function actionCategory(int $categoryId): string
    {
        return $this->render('category', [
            'category' => Category::findOne($categoryId),
        ]);
    }

    public function actionBook($isbn): string
    {
        return $this->render('book', [
            'book' => Book::findOne($isbn),
        ]);
    }
}
