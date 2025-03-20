<?php

namespace app\controllers;

use app\forms\CategoryForm;
use app\forms\LoginForm;
use app\models\Category;
use Yii;
use yii\db\StaleObjectException;
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
                        'actions' => ['category'],
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
    public function actionCategory(?int $categoryId = null)
    {
        $category = $categoryId ? Category::findOne($categoryId) : new Category();
        $form = new CategoryForm();

        if ($categoryId && Yii::$app->request->isDelete) {
            $category?->delete();
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $category->name = $form->name;
            $category->parent_id = $form->parentId;
            $category->save();

            Yii::$app->session->addFlash('success', 'Категория сохранена');

            return $this->redirect(['site/index']);
        } elseif ($categoryId) {
            $form->name = $category->name;
            $form->parentId = $category->parent_id;
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
}
