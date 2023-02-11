<?php

namespace App\Filters\Api;

use Illuminate\Http\Request;

class ApiFilter {

    protected const OPERATORS = 'operators';
    protected const MAPPING = 'mapping';

    protected array $operatorMap = [
        'eq' => '=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<='
    ];

    public function transform(Request $request, array $columnMap): array
    {
        $queryArray = [];

        foreach ($columnMap as $fieldName => $fieldConfig) {
            $query = $request->query($fieldName);

            if (empty($query)) {
                continue;
            }

            $column = $fieldConfig[self::MAPPING] ?? $fieldName;

            foreach ($fieldConfig[self::OPERATORS] as $operator) {
                if (isset($query[$operator])) {
                    $queryArray[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $queryArray;
    }
}
