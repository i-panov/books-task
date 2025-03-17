<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 *
 * @property Book[] $books
 */
class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'authors';
    }

    /**
     * @throws InvalidConfigException
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['isbn' => 'isbn'])
            ->viaTable('books_authors', ['author_id' => 'id']);
    }
}
