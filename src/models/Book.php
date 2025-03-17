<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $isbn
 * @property string title
 * @property int $pageCount
 * @property int $publishedDate
 * @property string $shortDescription
 * @property string $longDescription
 * @property string $status
 *
 * @property-read Category[] $categories
 * @property-read Author[] $authors
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
            'timestamp' => TimestampBehavior::class,
            'attributeTypeCast' => [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterFind' => true,
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('books_categories', ['isbn' => 'isbn']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('books_authors', ['isbn' => 'isbn']);
    }
}
