<?php

namespace App\Services;

use App\Filters\Api\V1\OffersFilter;
use App\Models\Offer;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OfferService
{
    public function __construct(private Room $roomModel, private Offer $offerModel, private OffersFilter $offersFilter)
    {
    }

    public function getRoomOffers(Request $request)
    {
        $roomOffers = $this->roomModel->select(
            [
                'rooms.id',
                'rooms.name',
                'hotels.name as hotel_name',
                'price_sum.total_price',
                'price_sum.arrival_date',
                'price_sum.departure_date',
                DB::raw(
                    'CASE WHEN availability.number_of_days = ' . $this->offersFilter->getDaysBetweenDates($request) .
                    ' THEN TRUE ELSE FALSE END AS available_for_reservation'
                )
            ]
        )
            ->distinct()
            ->joinSub(
                $this->offerModel::select(
                    [
                        'room_id',
                        DB::raw('SUM(price) as total_price'),
                        DB::raw('MIN(day) as arrival_date'),
                        DB::raw('MAX(day) as departure_date'),
                    ]
                )
                    ->where($this->offersFilter->getOfferFilterItems($request))
                    ->groupBy('room_id'),
                'price_sum',
                function ($join) {
                    $join->on('rooms.id', '=', 'price_sum.room_id');
                }
            )
            ->joinSub(
                $this->offerModel::select(['room_id', DB::raw('COUNT(*) as number_of_days')])
                    ->where($this->offersFilter->getOfferFilterItems($request))
                    ->groupBy('room_id'),
                'availability',
                function ($join) {
                    $join->on('rooms.id', '=', 'availability.room_id');
                }
            )
            ->join('hotels', 'hotels.id', '=', 'rooms.hotel_id');

        if (!empty($request->query('isAvailable'))) {
            $roomOffers->having('available_for_reservation', '=', $request->query('isAvailable'));
        }

        return $roomOffers
            ->paginate()
            ->appends($request->query());
    }

}
