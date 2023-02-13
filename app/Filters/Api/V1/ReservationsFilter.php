<?php

namespace App\Filters\Api\V1;

use App\Filters\Api\ApiFilter;
use Illuminate\Http\Request;

class ReservationsFilter extends ApiFilter
{
    private array $reservationsFilterColumnMap = [
        'arrivalDate' => [
            self::OPERATORS => ['eq', 'gt', 'gte', 'lt', 'lte'],
            self::MAPPING => 'arrival_date'
        ],
        'departureDate' => [
            self::OPERATORS => ['eq', 'gt', 'gte', 'lt', 'lte'],
            self::MAPPING => 'departure_date'
        ],
        'roomId' => [
            self::OPERATORS => ['eq', 'gt', 'gte', 'lt', 'lte'],
            self::MAPPING => 'room_id'
        ]
    ];

    public function getReservationFilterItems(Request $request): array
    {
        return $this->transform($request, $this->reservationsFilterColumnMap);
    }
}
