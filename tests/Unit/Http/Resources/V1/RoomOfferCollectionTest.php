<?php

namespace Unit\Http\Resources\V1;

use App\Http\Resources\V1\RoomOfferCollection;
use App\Http\Resources\V1\RoomOfferResource;
use PHPUnit\Framework\TestCase;

class RoomOfferCollectionTest extends TestCase
{
    public function testRoomOffersCollectionContainsRoomOfferResources(): void
    {
        $roomOffers = [
            [
                'id' => 1,
                'name' => 'Standard Room',
                'hotel_name' => 'Luxury Hotel',
                'total_price' => 500.0,
                'arrival_date' => '2022-12-01',
                'departure_date' => '2022-12-05',
                'available_for_reservation' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Deluxe Room',
                'hotel_name' => 'Luxury Hotel',
                'total_price' => 1000.0,
                'arrival_date' => '2022-12-01',
                'departure_date' => '2022-12-05',
                'available_for_reservation' => 1,
            ],
        ];
        $roomOfferCollection = new RoomOfferCollection($roomOffers);

        foreach ($roomOfferCollection as $resource) {
            $this->assertInstanceOf(RoomOfferResource::class, $resource);
        }
    }
}
