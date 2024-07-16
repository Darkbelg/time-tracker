<?php

namespace App\Database\Query;

use App\Models\TimeEntryApi;
use RuntimeException;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Query\Builder;

class ApiBuilder extends Builder
{
    protected $baseUrl;
    public $wheres = [];

    public function __construct($connection, $grammar = null, $processor = null)
    {
        parent::__construct($connection, $grammar, $processor);
        $this->baseUrl = 'localhost/api';
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => $boolean,
        ];

        return $this;
    }

    protected function buildQueryString()
    {
        $queryParams = [];

        foreach ($this->wheres as $where) {
            $value = $where['value'];

            switch ($where['operator']) {
                case '=':
                    $queryParams[$where['column']] = $value;
                    break;
                default:
                    $queryParams[$where['column']] = $where['operator'] . $value;
                    break;
            }
        }

        return http_build_query($queryParams);
    }

    /**
     * Get the results of the query.
     *
     * @param  array  $columns
     * @return array
     * @throws RuntimeException
     */
    public function get($columns = ['*'])
    {
        if ($columns !== ['*']) {
            throw new RuntimeException('Selecting specific columns is not supported in the API implementation.');
        }

        $endpoint = $this->from;
        $queryString = $this->buildQueryString();

        $url = $this->baseUrl . '/' . $endpoint;
        if (!empty($queryString)) {
            $url .= '?' . $queryString;
        }

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new RuntimeException("API request failed: " . $response->body());
        }

        $responseData = $response->json();

        // If it's a single item, wrap it in an array
        if (!is_array($responseData) || !isset($responseData[0])) {
            $responseData = [$responseData];
        }

        $models = collect();
        foreach ($responseData as $data) {
            $model = new TimeEntryApi();
            $model->setRawAttributes($data, true); // This ensures all attributes are set correctly
            $model->exists = true; // Mark the model as existing
            $models->push($model);
        }

        return $models;
    }
}
