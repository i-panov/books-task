<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \app\forms\BookForm $formModel */
/** @var ?string $isbn */
/** @var array $categories */

if ($isbn) {
    $this->title = "Редактирование книги: {$formModel->title}";
} else {
    $this->title = "Создание книги";
}

$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

?>

<div class="container">
    <div class="row">
        <div class="col-4">
            <?= $form->field($formModel, 'title'); ?>
        </div>
        <div class="col-4">
            <?= $form->field($formModel, 'authors'); ?>
        </div>
        <div class="col-4">
            <?= $form->field($formModel, 'categoryId')->dropDownList($categories); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <?= $form->field($formModel, 'isbn'); ?>
        </div>
        <div class="col-3">
            <?= $form->field($formModel, 'status'); ?>
        </div>
        <div class="col-3">
            <?= $form->field($formModel, 'pageCount')->input('number'); ?>
        </div>
        <div class="col-3">
            <?= $form->field($formModel, 'publishedDate')->input('date'); ?>
        </div>
    </div>
    <div class="row">
        <?= $form->field($formModel, 'shortDescription')->textarea(); ?>
    </div>
    <div class="row">
        <?= $form->field($formModel, 'longDescription')->textarea(['rows' => 10]); ?>
    </div>
    <div class="row">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
    </div>
</div>

<?php

ActiveForm::end();

