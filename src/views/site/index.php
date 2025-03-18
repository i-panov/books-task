<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \app\models\Category[] $categories */

$this->title = 'Категории';

?>

<h3>Категории</h3>

<ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <?= Html::a($category->name, ['site/category', 'categoryId' => $category->id]) ?>
        </li>
    <?php endforeach; ?>
</ul>
