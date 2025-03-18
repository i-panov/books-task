<?php

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
    'onerror' => 'this.src = "https://fakeimg.pl/350x200/?text=Image+not+found"',
]) ?>

<br>
<br>

<h3><?= $book->title ?></h3>

<p>Количество страниц: <?= $book->pageCount ?></p>
<p>ISBN: <?= $book->isbn ?></p>
