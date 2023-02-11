<?php

namespace Unit\Http\Resources\V1;

use App\Http\Resources\V1\RoomOfferResource;
use Tests\TestCase;

class RoomOfferResourceTest extends TestCase
{
    public function testToArray(): void
    {
        $roomOffer =
            (object)[
                'id' => 1,
                'name' => 'Deluxe Room',
                'hotel_name' => 'Grand Hotel',
                'total_price' => 15000,
                'arrival_date' => '2023-02-11',
                'departure_date' => '2023-02-12',
                'available_for_reservation' => 1
            ];

        $resource = new RoomOfferResource($roomOffer);

        $result = $resource->toArray(request());

        $this->assertIsArray($result);
        $this->assertEquals(
            [
                'roomId' => 1,
                'name' => 'Deluxe Room',
                'hotel' => 'Grand Hotel',
                'totalPrice' => 15000,
                'arrivalDate' => '2023-02-11',
                'departureDate' => '2023-02-12',
                'isAvailableForReservation' => true
            ],
            $result
        );
    }
}
