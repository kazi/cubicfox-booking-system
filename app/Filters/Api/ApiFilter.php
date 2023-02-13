<?php

namespace App\Filters\Api;

use Illuminate\Http\Request;

class ApiFilter {

    protected const OPERATORS = 'operators';
    protected const MAPPING = 'mapping';
    protected const DEFAULT_VALUE = 'default';

    protected array $columnMap = [];

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
                if (!empty($fieldConfig[self::DEFAULT_VALUE])) {
                    $query = $fieldConfig[self::DEFAULT_VALUE];
                }
                else {
                    continue;
                }
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

    protected function getColumnMap(): array
    {
        return $this->columnMap;
    }
}
