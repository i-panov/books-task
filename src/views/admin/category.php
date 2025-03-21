<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \app\forms\CategoryForm $formModel */
/** @var array $categories */

if ($formModel->name) {
    $this->title = "Редактирование категории: {$formModel->name}";
} else {
    $this->title = "Создание категории";
}

$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

echo $form->field($formModel, 'name');

echo $form->field($formModel, 'parentId')->listBox($categories);

echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);

ActiveForm::end();

?>

