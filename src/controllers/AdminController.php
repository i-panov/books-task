<?php

namespace app\controllers;

use app\forms\BookForm;
use app\forms\CategoryForm;
use app\forms\LoginForm;
use app\models\Book;
use app\models\Category;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
                    [
                        'allow' => true,
                        'actions' => ['category', 'book'],
                        'verbs' => ['get', 'post', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
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

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionCategory(?int $categoryId = null): Response|string
    {
        $category = $categoryId ? Category::findOne($categoryId) : new Category();
        $form = new CategoryForm();

        if (!$category) {
            throw new NotFoundHttpException("Категория $categoryId не найдена");
        }

        if ($categoryId && Yii::$app->request->isDelete) {
            $category->delete();
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $category->load($form->attributes, '');
            $category->save();

            Yii::$app->session->addFlash('success', 'Категория сохранена');

            return $this->redirect(['site/index']);
        } elseif ($categoryId) {
            $form->load($category->attributes, '');
        }

        $categoriesQuery = Category::find()->select('name')->indexBy('id');

        if ($categoryId) {
            $categoriesQuery->andWhere(['!=', 'id', $categoryId]);
        }

        return $this->render('category', [
            'formModel' => $form,
            'categories' => $categoriesQuery->column(),
        ]);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionBook(?string $isbn = null): Response|string
    {
        $book = $isbn ? Book::findOne($isbn) : new Book();
        $form = new BookForm();

        if (!$book) {
            throw new NotFoundHttpException("Книга $isbn не найдена");
        }

        if ($isbn && Yii::$app->request->isDelete) {
            $book->delete();
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->saveToModel($book);

            Yii::$app->session->addFlash('success', 'Книга сохранена');

            return $this->redirect(['site/index']);
        } elseif ($isbn) {
            $form->load($book->attributes, '');
            $form->categoryId = $book->category->id;
            $form->authors = implode(', ', ArrayHelper::getColumn($book->authors, 'name'));
        }

        return $this->render('book', [
            'formModel' => $form,
            'isbn' => $isbn,
            'categories' => Category::find()->select('name')->indexBy('id')->column(),
        ]);
    }
}
