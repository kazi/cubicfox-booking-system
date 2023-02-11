<?php

namespace App\Filters\Api\V1;

use App\Filters\Api\ApiFilter;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class OffersFilter extends ApiFilter
{
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

    /**
     * @param Request $request
     * @return int|null
     * @throws Exception
     */
    public function getDaysBetweenDates(Request $request): ?int
    {
        $firstDay = $request->query('firstDay');
        $lastDay = $request->query('lastDay');

        if (empty($firstDay) || empty($lastDay)) {
            return null;
        }

        $firstDay = new DateTime(reset($firstDay));
        $lastDay = new DateTime(reset($lastDay));
        $difference = $firstDay->diff($lastDay);

        return $difference->days + 1;
    }

    public function getOfferFilterItems(Request $request): array
    {
        $offerFilterItems = $this->transform($request, $this->offerFilterColumnMap);
        $offerFilterItems[] = ['is_available', '=', 1];

        return $offerFilterItems;
    }
}
