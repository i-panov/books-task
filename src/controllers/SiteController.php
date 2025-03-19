<?php

namespace app\controllers;

use app\models\Book;
use app\models\Category;
use app\models\CategoryBooksForm;
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

    public function actionCategory(int $categoryId, string $query = '', string $searchBy = '', array $statuses = []): string
    {
        $category = Category::findOne($categoryId);
        $booksQuery = $category->getBooks();
        $form = new CategoryBooksForm();

        if ($form->load(\Yii::$app->request->get()) && $form->validate()) {
            if ($form->searchBy === 'title') {
                $booksQuery->andWhere(['like', 'title', $form->query]);
            } else if ($form->searchBy === 'author') {
                $booksQuery->innerJoinWith('authors a')
                    ->andWhere(['like', 'a.name', $form->query]);
            }

            if ($form->statuses) {
                $booksQuery->andWhere(['status' => $form->statuses]);
            }
        }

        return $this->render('category', [
            'category' => $category,
            'books' => $booksQuery->all(),
            'formModel' => $form,
        ]);
    }

    public function actionBook($isbn): string
    {
        return $this->render('book', [
            'book' => Book::findOne($isbn),
        ]);
    }
}
