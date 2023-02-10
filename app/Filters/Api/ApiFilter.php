<?php

namespace App\Filters\Api;

use Illuminate\Http\Request;

class ApiFilter {

    protected array $allowedApiParams = [];

    protected array $columnMap = [];

    protected array $operatorMap = [
        'eq' => '=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<='
    ];

    public function transform(Request $request): array
    {
        $queryArray = [];

        foreach ($this->allowedApiParams as $field => $operators) {
            $query = $request->query($field);

            if (empty($query)) {
                continue;
            }

            $column = $this->columnMap[$field] ?? $field;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $queryArray[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $queryArray;
    }
}
