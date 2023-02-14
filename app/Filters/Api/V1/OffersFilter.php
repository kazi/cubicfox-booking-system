<?php

namespace App\Filters\Api\V1;

use App\Filters\Api\ApiFilter;
use DateTime;
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

    public function calculateInclusiveDateDifference(string $firstDay, string $lastDay): int
    {
        $start = new DateTime($firstDay);
        $end = new DateTime($lastDay);

        return $start->diff($end)->days + 1;
    }

    public function getDaysBetweenDates(Request $request): int
    {
        $firstDay = $request->query('firstDay', $this->getDefaultValueForField($request, 'firstDay'));
        $lastDay = $request->query('lastDay', $this->getDefaultValueForField($request, 'lastDay'));

        return $this->calculateInclusiveDateDifference(reset($firstDay), reset($lastDay));
    }

    public function getOfferFilterItems(Request $request): array
    {
        $offerFilterItems = $this->transform($request, $this->getColumnMapWithDefaultValues($request));
        $offerFilterItems[] = ['is_available', '=', 1];

        return $offerFilterItems;
    }

    private function getColumnMapWithDefaultValues(Request $request): array
    {
        $columnMap = $this->offerFilterColumnMap;
        $firstDayDefault = ['gte' => date('Y-m-d', strtotime('now'))];

        $columnMap['firstDay'][self::DEFAULT_VALUE] = $firstDayDefault;

        if (empty($request->query('lastDay'))) {
            $firstDayValue = $request->query('firstDay', $firstDayDefault);
            $columnMap['lastDay'][self::DEFAULT_VALUE] = !is_null($firstDayValue) ? ['lte' => $firstDayValue['gte']] : ['lte' => date('Y-m-d', strtotime('now'))];
        }

        return $columnMap;
    }

    private function getDefaultValueForField(Request $request, string $fieldName): ?array
    {
        $columnMap = $this->getColumnMapWithDefaultValues($request);

        return $columnMap[$fieldName][self::DEFAULT_VALUE] ?? null;
    }
}
