<?php

namespace app\commands;

use app\models\Book;
use yii\console\Controller;
use yii\console\ExitCode;

class BooksController extends Controller
{
    const BOOKS_URL = 'https://gitlab.grokhotov.ru/hr/yii-test-vacancy/-/raw/master/books.json';

    public function actionLoad($message = 'hello world'): int
    {
        $json = file_get_contents(self::BOOKS_URL);

        if ($json) {
            $data = json_decode($json, true);

            if ($data && is_array($data)) {
                // TODO: load data
            }
        }

        echo $message . "\n";

        return ExitCode::OK;
    }
}
