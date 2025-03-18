<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property string $name
 *
 * @property-read ?Category $parent
 * @property-read Category[] $children
 * @property-read Book[] $books
 * @property-read array $breadcrumbs
 */
class Category extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'categories';
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    public function getChildren(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['category_id' => 'id']);
    }

    public function getBreadcrumbs(): array
    {
        $category = $this;
        $breadcrumbs = [];

        while ($category) {
            $breadcrumbs[] = [
                'label' => $category->name,
                'url' => ['site/category', 'categoryId' => $category->id],
            ];

            $category = $category->parent;
        }

        return array_reverse($breadcrumbs);
    }
}
