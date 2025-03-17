<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $name
 *
 * @property-read Category $parent
 * @property-read Book[] $books
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

    /**
     * @throws InvalidConfigException
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['category_id' => 'id'])
            ->viaTable('books_categories', ['category_id' => 'id']);
    }
}
