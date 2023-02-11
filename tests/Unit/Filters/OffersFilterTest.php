<?php

namespace Unit\Filters;

use App\Filters\Api\V1\OffersFilter;
use Exception;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class OffersFilterTest extends TestCase
{
    /**
     * @dataProvider providerGetOfferFilterItems
     */
    public function testGetOfferFilterItems(array $requestQueryArray, array $expectedResult): void
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('query')
            ->andReturnUsing(
                function (string $fieldName) use ($requestQueryArray) {
                    return $requestQueryArray[$fieldName] ?? null;
                }
            );

        $offersFilter = new OffersFilter();
        $result = $offersFilter->getOfferFilterItems($mockRequest);

        $this->assertEquals($expectedResult, $result);
    }

    public function providerGetOfferFilterItems(): array
    {
        return [
            'test_data_set_1' => [
                [
                    'firstDay' => ['gte' => '2022-10-01'],
                    'lastDay' => ['lte' => '2022-10-30'],
                    'roomId' => ['gte' => 1, 'lte' => 10],
                    'price' => ['gte' => 50, 'lte' => 100]
                ],
                [
                    ['day', '>=', '2022-10-01'],
                    ['day', '<=', '2022-10-30'],
                    ['room_id', '>=', 1],
                    ['room_id', '<=', 10],
                    ['price', '>=', 50],
                    ['price', '<=', 100],
                    ['is_available', '=', 1]
                ]
            ],
            'test_data_set_2' => [
                [
                    'firstDay' => ['gte' => '2022-11-01'],
                    'lastDay' => ['lte' => '2022-11-30'],
                    'roomId' => ['gte' => 5, 'lte' => 15],
                    'price' => ['gte' => 75, 'lte' => 150]
                ],
                [
                    ['day', '>=', '2022-11-01'],
                    ['day', '<=', '2022-11-30'],
                    ['room_id', '>=', 5],
                    ['room_id', '<=', 15],
                    ['price', '>=', 75],
                    ['price', '<=', 150],
                    ['is_available', '=', 1]
                ]
            ],
            'with_no_filtering' => [
                [],
                [
                    ['is_available', '=', 1]
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerGetDaysBetweenDates
     */
    public function testGetDaysBetweenDates(string $firstDay, string $lastDay, int $daysBetween): void
    {
        $mockRequest = Mockery::mock(Request::class);

        $mockRequest->shouldReceive('query')
            ->with('firstDay')
            ->andReturn([$firstDay]);

        $mockRequest->shouldReceive('query')
            ->with('lastDay')
            ->andReturn([$lastDay]);

        $offersFilter = new OffersFilter();

        $this->assertEquals($daysBetween, $offersFilter->getDaysBetweenDates($mockRequest));
    }

    public function providerGetDaysBetweenDates(): array
    {
        return [
            'basic' => ['2023-02-11', '2023-02-14', 4],
            'february_with_only_28_days' => ['2023-02-25', '2023-03-04', 8],
            'leap_year_february_with_29_days' => ['2024-02-25', '2024-03-04', 9],
            'one_day_offer_should_result_1' => ['2023-02-11', '2023-02-11', 1]
        ];
    }

    /**
     * @dataProvider providerGetDaysBetweenDatesReturnsNull
     */
    public function testGetDaysBetweenDatesReturnsNull(?string $firstDay, ?string $lastDay): void
    {
        $mockRequest = Mockery::mock(Request::class);

        $mockRequest->shouldReceive('query')
            ->with('firstDay')
            ->andReturn($firstDay);

        $mockRequest->shouldReceive('query')
            ->with('lastDay')
            ->andReturn($lastDay);

        $offersFilter = new OffersFilter();
        $this->assertNull($offersFilter->getDaysBetweenDates($mockRequest));
    }

    public function providerGetDaysBetweenDatesReturnsNull(): array
    {
        return [
            'firstday_is_null' => [
                'firstDay' => null,
                'lastDay' => '2023-02-12'
            ],
            'lastday_is_null' => [
                'firstDay' => '2023-02-12',
                'lastDay' => null
            ],
            'both_are_null' => [
                'firstDay' => null,
                'lastDay' => null
            ]
        ];
    }

    /**
     * @dataProvider providerGetDaysBetweenDatesThrowsException
     */
    public function testGetDaysBetweenDatesThrowsException(?string $firstDay, ?string $lastDay): void
    {
        $mockRequest = Mockery::mock(Request::class);

        $mockRequest->shouldReceive('query')
            ->with('firstDay')
            ->andReturn([$firstDay]);

        $mockRequest->shouldReceive('query')
            ->with('lastDay')
            ->andReturn([$lastDay]);

        $offersFilter = new OffersFilter();

        $this->expectException(Exception::class);

        $offersFilter->getDaysBetweenDates($mockRequest);
    }

    public function providerGetDaysBetweenDatesThrowsException(): array
    {
        return [
            'malformed_dates_1' => [
                'firstDay' => 'non-date',
                'lastDay' => '2023-02-12'
            ],
            'both_unparseable' => [
                'firstDay' => 'non-date',
                'lastDay' => 'non-date'
            ]
        ];
    }
}
