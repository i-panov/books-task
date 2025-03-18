<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \app\models\Category $category */

$this->title = "Категория: {$category->name}";

foreach ($category->parent?->breadcrumbs ?? [] as $breadcrumb) {
    $this->params['breadcrumbs'][] = $breadcrumb;
}

$this->params['breadcrumbs'][] = $this->title;

?>

<h3>Категории</h3>

<ul>
    <?php foreach ($category->children as $child): ?>
        <li>
            <?= Html::a($child->name, ['site/category', 'categoryId' => $child->id]) ?>
        </li>
    <?php endforeach; ?>
</ul>

<h3>Книги</h3>

<ul>
    <?php foreach ($category->books as $book): ?>
        <li>
            <?= Html::a($book->title, ['site/book', 'isbn' => $book->isbn]) ?>
        </li>
    <?php endforeach; ?>
</ul>
