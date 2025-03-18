<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\behaviors\AttributeTypecastBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $isbn
 * @property string title
 * @property int $pageCount
 * @property string $publishedDate
 * @property ?string $shortDescription
 * @property ?string $longDescription
 * @property string $status
 * @property int $category_id
 *
 * @property-read Category $category
 * @property-read Category[] $allCategories
 * @property-read Author[] $authors
 * @property-read string $thumbnailPath
 */
class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'books';
    }

    public function behaviors(): array
    {
        return [
            'attributeTypeCast' => [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterFind' => true,
                'attributeTypes' => [
                    'pageCount' => AttributeTypecastBehavior::TYPE_INTEGER,
                    //'publishedDate' => AttributeTypecastBehavior::TYPE_INTEGER,
                ],
            ],
        ];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getAllCategories(): array
    {
        $categories = [];
        $currentCategory = $this->category;

        while ($currentCategory) {
            $categories[] = $currentCategory;
            $currentCategory = $currentCategory->parent;
        }

        return $categories;
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('books_authors', ['isbn' => 'isbn']);
    }

    public function getThumbnailPath(): string
    {
        return "/images/book_thumbnails/{$this->isbn}.jpg";
    }
}
