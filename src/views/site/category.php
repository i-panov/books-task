<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \app\models\Category $category */
/** @var \app\models\Book[] $books */
/** @var \app\forms\CategoryBooksForm $formModel */

$this->title = "Категория: {$category->name}";

foreach ($category->parent?->breadcrumbs ?? [] as $breadcrumb) {
    $this->params['breadcrumbs'][] = $breadcrumb;
}

$this->params['breadcrumbs'][] = $this->title;

$statuses = array_combine($formModel->allowedStatuses, $formModel->allowedStatuses);

if (!Yii::$app->user->isGuest) {
    echo Html::a('Редактировать категорию', ['admin/category', 'categoryId' => $category->id], [
        'class' => 'btn btn-link',
    ]);
}

?>

<h3>Книги</h3>

<?php $form = ActiveForm::begin(['method' => 'get']); ?>
<div class="container">
    <div class="row">
        <div class="col-4">
            <?= $form->field($formModel, 'query'); ?>
        </div>
        <div class="col-2">
            <?= $form->field($formModel, 'searchBy')->dropDownList([
                'title' => 'title',
                'author' => 'author',
            ], [
                'prompt' => '',
            ]); ?>
        </div>
        <div class="col-2">
            <?= $form->field($formModel, 'statuses')->dropDownList($statuses, [
                'prompt' => '',
                'multiple' => true,
            ]); ?>
        </div>
        <div class="col form-group">
            <?= Html::submitInput('Фильтровать', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<ul>
    <?php foreach ($books as $book): ?>
        <li>
            <?= Html::a($book->title, ['site/book', 'isbn' => $book->isbn]) ?>
        </li>
    <?php endforeach; ?>
</ul>

<hr>

<h3>Подкатегории</h3>

<ul>
    <?php foreach ($category->children as $child): ?>
        <li>
            <?= Html::a($child->name, ['site/category', 'categoryId' => $child->id]) ?>
        </li>
    <?php endforeach; ?>
</ul>
