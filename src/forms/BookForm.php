<?php

namespace app\forms;

use app\models\Author;
use app\models\Book;
use app\models\Category;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * @property-read ?Category $category
 * @property-read string[] $authorNames
 */
class BookForm extends Model
{
    public string $isbn = '';
    public string $title = '';
    public int $pageCount = 0;
    public string $publishedDate = '';
    public string $shortDescription = '';
    public string $longDescription = '';
    public string $status = '';
    public ?int $categoryId;
    public string $authors = '';

    public function attributeLabels()
    {
        return [
            'isbn' => 'ISBN',
            'title' => 'Название',
            'pageCount' => 'Количество страниц',
            'publishedDate' => 'Дата публикации',
            'shortDescription' => 'Краткое описание',
            'longDescription' => 'Полное описание',
            'status' => 'Статус',
            'categoryId' => 'Категория',
            'authors' => 'Авторы',
        ];
    }

    public function rules()
    {
        return [
            [['isbn', 'title', 'pageCount', 'publishedDate', 'status', 'categoryId', 'authors'], 'required'],
            ['isbn', 'string', 'min' => 5, 'max' => 20],
            ['title', 'string', 'max' => 50],
            ['pageCount', 'integer', 'min' => 1, 'max' => 100000],
            ['publishedDate', 'date', 'format' => 'yyyy-MM-dd'],
            ['status', 'string', 'max' => 20],
            ['categoryId', 'integer'],
            ['categoryId', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            ['shortDescription', 'string', 'max' => 300],
            ['longDescription', 'string', 'max' => 10000],
        ];
    }

    public function getCategory(): ?Category
    {
        return Category::findOne($this->categoryId);
    }

    public function getAuthorNames(): array
    {
        $result = explode(',', $this->authors);
        $result = array_map('trim', $result);
        $result = array_unique($result);
        return array_filter($result);
    }

    /**
     * @return Author[]
     * @throws \yii\db\Exception
     */
    public function processAuthors(): array
    {
        $allAuthorNames = $this->authorNames;
        $presentAuthors = Author::find()->where(['name' => $allAuthorNames])->all();
        $presentAuthorNames = ArrayHelper::getColumn($presentAuthors, 'name');
        $absentAuthorNames = array_udiff($allAuthorNames, $presentAuthorNames, 'strcasecmp');
        $newAuthors = [];

        foreach ($absentAuthorNames as $name) {
            $author = new Author(['name' => $name]);
            $author->save();
            $newAuthors[] = $author;
        }

        return [...$presentAuthors, ...$newAuthors];
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function toModel(): Book
    {
        $book = Book::findOne(['isbn' => $this->isbn]) ?? new Book(['isbn' => $this->isbn]);

        $book->title = $this->title;
        $book->pageCount = $this->pageCount;
        $book->publishedDate = $this->publishedDate;
        $book->shortDescription = $this->shortDescription;
        $book->longDescription = $this->longDescription;
        $book->status = $this->status;
        $book->category_id = $this->categoryId;

        $book->save();

        $authors = ArrayHelper::index($this->processAuthors(), 'id');
        $presentAuthorIds = $book->getAuthors()->select('id')->column();
        $absentAuthorIds = array_diff(array_keys($authors), $presentAuthorIds);

        foreach ($absentAuthorIds as $authorId) {
            $book->link('authors', $authors[$authorId]);
        }

        return $book;
    }
}
