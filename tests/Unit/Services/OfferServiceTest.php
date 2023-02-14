<?php

namespace Unit\Services;

use App\Filters\Api\V1\OffersFilter;
use App\Models\Offer;
use App\Models\Room;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class OfferServiceTest extends TestCase
{
    public function testGetRoomOffers(): void
    {
        $mockRoomModel = Mockery::mock(Room::class);
        $mockOfferModel = Mockery::mock(Offer::class);

        $mockRequest = Mockery::mock(Request::class);
        $mockOffersFilter = Mockery::mock(OffersFilter::class);

        $mockRoomModel->shouldReceive('select')->andReturnSelf();
        $mockRoomModel->shouldReceive('distinct')->andReturnSelf();
        $mockRoomModel->shouldReceive('joinSub')->andReturnSelf();
        $mockRoomModel->shouldReceive('join')->andReturnSelf();
        $mockRoomModel->shouldReceive('paginate')->andReturnSelf();
        $mockRoomModel->shouldReceive('appends')->andReturnSelf();
        $mockOfferModel->shouldReceive('select')->andReturnSelf();
        $mockOfferModel->shouldReceive('where')->andReturnSelf();
        $mockOfferModel->shouldReceive('groupBy')->andReturnSelf();
        $mockOffersFilter->shouldReceive('getOfferFilterItems');
        $mockRequest->shouldReceive('query');

        $mockOffersFilter->shouldReceive('getDaysBetweenDates');

        $offerService = new OfferService($mockRoomModel, $mockOfferModel, $mockOffersFilter);
        $roomOffers = $offerService->getRoomOffers($mockRequest);

        $this->assertInstanceOf(Room::class, $roomOffers);
    }

}
