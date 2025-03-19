<?php

namespace app\forms;

use app\models\Book;
use yii\base\Model;
use yii\helpers\Html;

/**
 * @property-read array $allowedStatuses
 */
class CategoryBooksForm extends Model
{
    public string $query = '';
    public string $searchBy = '';

    /** @var array */
    public $statuses = [];

    private array $_allowedStatuses = [];

    public function init(): void
    {
        $this->_allowedStatuses = Book::find()
            ->select('status')
            ->distinct('status')
            ->column();
    }

    public function rules(): array
    {
        return [
            ['query', 'required', 'when' => function(self $model) {
                return !empty($model->searchBy);
            }, 'whenClient' => $this->whenClient('searchBy')],
            ['searchBy', 'required', 'when' => function(self $model) {
                return !empty($model->query);
            }, 'whenClient' => $this->whenClient('query')],
            ['statuses', 'filter', 'filter' => function($value) {
                return is_array($value) ? array_filter($value) : [];
            }],
            ['statuses', 'each', 'rule' => ['in', 'range' => $this->_allowedStatuses]],
        ];
    }

    public function getAllowedStatuses(): array
    {
        return $this->_allowedStatuses;
    }

    private function whenClient(string $attribute): string
    {
        $id = Html::getInputId($this, $attribute);

        return 'function(attribute,value) {
            return $("#' . $id . '").val() !== "";
        }';
    }
}
