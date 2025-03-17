<?php

use yii\db\Migration;

class m250316_094158_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('books', [
            'isbn' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'pageCount' => $this->integer()->notNull()->defaultValue(0),
            'publishedDate' => $this->date()->notNull(),
            'shortDescription' => $this->text()->notNull(),
            'longDescription' => $this->text()->notNull(),
            'status' => $this->string()->notNull(),
        ]);

        $this->addPrimaryKey('books_pk', 'books', 'isbn');

        $this->createTable('authors', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('books_authors', [
            'author_id' => $this->integer()->notNull(),
            'isbn' => $this->string()->notNull(),
        ]);

        $this->addPrimaryKey('books_authors_pk', 'books_authors', ['author_id', 'isbn']);

        $this->addForeignKey('books_authors_author_id_fk', 'books_authors', 'author_id',
            'authors', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('books_authors_isbn_fk', 'books_authors', 'isbn',
            'books', 'isbn', 'CASCADE', 'CASCADE');

        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->null(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->addForeignKey('categories_parent_id_fk', 'categories', 'parent_id',
            'categories', 'id', 'CASCADE', 'CASCADE');

        $this->insert('categories', ['name' => 'NEW']);

        $this->createTable('books_categories', [
            'isbn' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('books_categories_pk', 'books_categories', ['isbn', 'category_id']);

        $this->addForeignKey('books_categories_isbn_fk', 'books_categories', 'isbn',
            'books', 'isbn', 'CASCADE', 'CASCADE');

        $this->addForeignKey('books_categories_category_id_fk', 'books_categories', 'category_id',
            'categories', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown(): bool
    {
        echo "m250316_094158_init cannot be reverted.\n";

        return false;
    }
}
