<?php

namespace app\forms;

use app\models\Category;
use yii\base\Model;

class CategoryForm extends Model
{
    public ?string $parentId = null;
    public string $name = '';

    public function attributeLabels(): array
    {
        return [
            'parentId' => 'Родитель',
            'name' => 'Название',
        ];
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['parentId', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
        ];
    }
}
