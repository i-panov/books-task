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
            'shortDescription' => $this->text()->notNull()->defaultValue(''),
            'longDescription' => $this->text()->notNull()->defaultValue(''),
            'status' => $this->string()->notNull(),
        ]);

        $this->addPrimaryKey('books_pk', 'books', 'isbn');

        $this->createTable('authors', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('author_books', [
            'author_id' => $this->integer()->notNull(),
            'isbn' => $this->string()->notNull(),
        ]);

        $this->addPrimaryKey('author_books_pk', 'author_books', ['author_id', 'isbn']);

        $this->addForeignKey('author_books_author_id_fk', 'author_books', 'author_id',
            'authors', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('author_books_isbn_fk', 'author_books', 'isbn',
            'books', 'isbn', 'CASCADE', 'CASCADE');

        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->null(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->addForeignKey('categories_parent_id_fk', 'categories', 'parent_id',
            'categories', 'id', 'CASCADE', 'CASCADE');

        $this->insert('categories', ['name' => 'NEW']);

        $this->createTable('book_categories', [
            'isbn' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('book_categories_pk', 'book_categories', ['isbn', 'category_id']);

        $this->addForeignKey('book_categories_isbn_fk', 'book_categories', 'isbn',
            'books', 'isbn', 'CASCADE', 'CASCADE');

        $this->addForeignKey('book_categories_category_id_fk', 'book_categories', 'category_id',
            'categories', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        echo "m250316_094158_init cannot be reverted.\n";

        return false;
    }
}
