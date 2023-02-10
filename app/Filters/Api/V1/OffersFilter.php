<?php

namespace App\Filters\Api\V1;

use App\Filters\Api\ApiFilter;

class OffersFilter extends ApiFilter {

    protected array $allowedApiParams = [
        'firstDay' => ['lte'],
        'lastDay' => ['gte'],
        'roomId' => ['eq', 'gt', 'gte', 'lt', 'lte'],
        'price' => ['eq', 'gt', 'gte', 'lt', 'lte'],
        'isAvailable' => ['eq']
    ];

    protected array $columnMap = [
        'firstDay' => 'day',
        'lastDay' => 'day',
        'roomId' => 'room_id',
        'price' => 'price',
        'isAvailable' => 'is_available'
    ];
}
