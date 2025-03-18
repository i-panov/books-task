<?php

namespace app\commands;

use app\models\Author;
use app\models\Book;
use app\models\Category;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;

class BooksController extends Controller
{
    const BOOKS_URL = 'https://gitlab.grokhotov.ru/hr/yii-test-vacancy/-/raw/master/books.json';

    public function actionLoad(): int
    {
        if ($json = file_get_contents(self::BOOKS_URL)) {
            $data = json_decode($json, true);

            if ($data && is_array($data)) {
                $webPath = \Yii::getAlias('@app') . '/web';

                foreach ($data as $item) {
                    try {
                        $this->processItem($item, $webPath);
                    } catch (\Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }

                return ExitCode::OK;
            }
        }

        return ExitCode::NOINPUT;
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    private function processItem(array $item, string $webPath): void
    {
        $isbn = ArrayHelper::getValue($item, 'isbn');

        if (!$isbn || Book::find()->where(['isbn' => $isbn])->exists()) {
            return;
        }

        $publishedDateString = ArrayHelper::getValue($item, 'publishedDate.$date');
        $publishedDate = $publishedDateString ? new \DateTime($publishedDateString) : null;

        $book = new Book();
        $book->isbn = $isbn;
        $book->title = ArrayHelper::getValue($item, 'title');
        $book->pageCount = (int)ArrayHelper::getValue($item, 'pageCount');
        $book->publishedDate = $publishedDate?->format('Y-m-d');
        $book->shortDescription = ArrayHelper::getValue($item, 'shortDescription');
        $book->longDescription = ArrayHelper::getValue($item, 'longDescription');
        $book->status = ArrayHelper::getValue($item, 'status');
        $book->save();

        //---------------------------------------------------

        $authorNames = $this->clearNames(ArrayHelper::getValue($item, 'authors'));
        $presentAuthors = Author::findAll(['name' => $authorNames]);
        $presentAuthorNames = ArrayHelper::getColumn($presentAuthors, 'name');
        $absentAuthorNames = $this->diffNames($authorNames, $presentAuthorNames);

        foreach ($absentAuthorNames as $name) {
            $author = new Author(['name' => $name]);
            $author->save();
            $book->link('authors', $author);
        }

        foreach ($presentAuthors as $author) {
            $book->link('authors', $author);
        }

        //---------------------------------------------------

        $categoryNames = $this->clearNames(ArrayHelper::getValue($item, 'categories'));

        if ($categoryNames) {
            $presentCategoryNames = Category::find()
                ->where(['name' => $categoryNames])
                ->select('name')
                ->column();

            $absentCategoryNames = $this->diffNames($categoryNames, $presentCategoryNames);

            foreach ($absentCategoryNames as $name) {
                (new Category(['name' => $name]))->save();
            }

            $book->link('category', Category::findOne(['name' => $categoryNames[0]]));
        }

        //---------------------------------------------------

        if ($thumbnailUrl = ArrayHelper::getValue($item, 'thumbnailUrl')) {
            $thumbnail = file_get_contents($thumbnailUrl);

            if ($thumbnail) {
                $path = "$webPath/$book->thumbnailPath";
                file_put_contents($path, $thumbnail);
            }
        }
    }

    private function clearNames(array $names): array
    {
        return array_filter(array_unique($names));
    }

    private function diffNames(array $left, array $right): array
    {
        return array_udiff($left, $right, 'strcasecmp');
    }
}
