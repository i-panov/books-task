<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \app\models\Category[] $categories */

$this->title = 'Категории';

?>

<h3>Категории</h3>

<ul class="list-group">
    <?php foreach ($categories as $category): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= Html::a($category->name, ['site/category', 'categoryId' => $category->id]) ?>
            <div class="badge rounded-pill justify-content-between">
                <?php
                echo Html::a('Редактировать', ['admin/category', 'categoryId' => $category->id], [
                    'class' => 'btn btn-link',
                ]);

                echo Html::beginForm(['admin/category', 'categoryId' => $category->id], 'delete');
                echo Html::submitButton('Удалить', ['class' => 'btn btn-link', 'data-confirm' => 'Вы действительно хотите удалить категорию?']);
                echo Html::endForm();
                ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
