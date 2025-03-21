<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \app\models\Book $book */

$this->title = "Книга: {$book->title}";

foreach ($book->category->breadcrumbs as $breadcrumb) {
    $this->params['breadcrumbs'][] = $breadcrumb;
}

$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::img($book->thumbnailPath, [
    'alt' => 'Image not found',
    'onerror' => 'this.src = "/images/alt.png"',
]) ?>

<br>
<br>

<h3><?= $book->title ?></h3>

<p>Количество страниц: <?= $book->pageCount ?></p>
<p>ISBN: <?= $book->isbn ?></p>
<p>Авторы: <?= implode(', ', ArrayHelper::getColumn($book->authors, 'name')) ?></p>

<div class="form-group">
    <?php
    echo Html::a('Редактировать', ['admin/book', 'isbn' => $book->isbn], ['class' => 'btn btn-link']);
    echo Html::beginForm(['admin/book', 'isbn' => $book->isbn], 'delete');
    echo Html::submitButton('Удалить', ['class' => 'btn btn-link', 'data-confirm' => 'Вы действительно хотите удалить книгу?']);
    echo Html::endForm();
    ?>
</div>
