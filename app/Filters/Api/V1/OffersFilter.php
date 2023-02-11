<?php

namespace App\Filters\Api\V1;

use App\Filters\Api\ApiFilter;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class OffersFilter extends ApiFilter {

    private array $offerFilterColumnMap = [
        'firstDay' => [
            self::OPERATORS => ['gte'],
            self::MAPPING => 'day'
        ],
        'lastDay' => [
            self::OPERATORS => ['lte'],
            self::MAPPING => 'day'
        ],
        'roomId' => [
            self::OPERATORS => ['eq', 'gt', 'gte', 'lt', 'lte'],
            self::MAPPING => 'room_id'
        ],
        'price' => [
            self::OPERATORS => ['eq', 'gt', 'gte', 'lt', 'lte'],
            self::MAPPING => 'price'
        ]
    ];

    private array $roomFilterColumnMap = [
        'isAvailable' => [
            self::OPERATORS => ['eq'],
            self::MAPPING => 'available_for_reservation'
        ]
    ];

    /**
     * @param Request $request
     * @return int|null
     * @throws Exception
     */
    public function getDaysBetweenDates(Request $request): ?int
    {
        if (empty($request->query('firstDay')) || empty($request->query('lastDay'))) {
            return null;
        }

        $firstDay = new DateTime(array_values($request->query('firstDay'))[0]);
        $lastDay = new DateTime(array_values($request->query('lastDay'))[0]);
        $difference = $firstDay->diff($lastDay);

        return $difference->days + 1;
    }

    public function getOfferFilterItems(Request $request): array
    {
        $offerFilterItems = $this->transform($request, $this->offerFilterColumnMap);
        $offerFilterItems[] = ['is_available', '=', 1];

        return $offerFilterItems;
    }

    public function getRoomFilterItems(Request $request): array
    {
        return $this->transform($request, $this->roomFilterColumnMap);
    }
}
