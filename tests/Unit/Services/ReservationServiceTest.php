<?php

namespace Unit\Services;

use App\Exceptions\RoomNotAvailableException;
use App\Models\Offer;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testMakeReservation(): void
    {
        $userId = 1;
        $roomId = 1;
        $arrivalDate = '2023-02-10';
        $departureDate = '2023-02-12';
        $daysBetween = 3;
        $price = 30000;

        $mockReservationModel = Mockery::mock(Reservation::class);
        $mockOfferModel = Mockery::mock(Offer::class);

        $mockOfferModel->shouldReceive('whereBetween')->andReturnSelf();
        $mockOfferModel->shouldReceive('where')->andReturnSelf();
        $mockOfferModel->shouldReceive('count')->andReturn($daysBetween);
        $mockOfferModel->shouldReceive('sum')->andReturn($price);
        $mockOfferModel->shouldReceive('update')->andReturnSelf();

        $mockReservationModel->shouldReceive('create')
            ->andReturn(
                new Reservation(
                    [
                        'user_id' => 1,
                        'room_id' => 1,
                        'arrival_date' => '2023-02-10',
                        'departure_date' => '2023-02-12',
                        'price' => 30000
                    ]
                )
            );

        $reservationService = new ReservationService($mockReservationModel, $mockOfferModel);

        $result = $reservationService->makeReservationForRoom(
            $userId,
            $roomId,
            $arrivalDate,
            $departureDate
        );

        $this->assertInstanceOf(Reservation::class, $result);

        $this->assertEquals($userId, $result->user_id);
        $this->assertEquals($roomId, $result->room_id);
        $this->assertEquals($arrivalDate, $result->arrival_date);
        $this->assertEquals($departureDate, $result->departure_date);
        $this->assertEquals($price, $result->price);
    }

    public function testMakeReservationThrowsExceptionWhenRoomNotAvailable(): void
    {
        $userId = 1;
        $roomId = 1;
        $arrivalDate = '2023-02-10';
        $departureDate = '2023-02-12';
        $daysBetween = 1;

        $mockReservationModel = Mockery::mock(Reservation::class);
        $mockOfferModel = Mockery::mock(Offer::class);

        $mockOfferModel->shouldReceive('whereBetween')->andReturnSelf();
        $mockOfferModel->shouldReceive('where')->andReturnSelf();
        $mockOfferModel->shouldReceive('count')->andReturn($daysBetween);

        $reservationService = new ReservationService($mockReservationModel, $mockOfferModel);

        $this->expectException(RoomNotAvailableException::class);

        $reservationService->makeReservationForRoom(
            $userId,
            $roomId,
            $arrivalDate,
            $departureDate
        );
    }

    public function testCancelReservation(): void
    {
        $mockReservationModel = Mockery::mock(Reservation::class);
        $mockOfferModel = Mockery::mock(Offer::class);

        $mockReservationModel->shouldReceive('where')->with(['id' => 100, 'user_id' => 10])->andReturnSelf();
        $mockReservationModel->shouldReceive('firstOrFail')->andReturn(
            new Reservation(
                [
                    'user_id' => 10,
                    'room_id' => 99,
                    'arrival_date' => '2023-02-10',
                    'departure_date' => '2023-02-12',
                    'price' => 30000
                ]
            )
        );

        $mockReservationModel->shouldReceive('getAttribute')->with('room_id')->andReturn(99);
        $mockReservationModel->shouldReceive('getAttribute')->with('arrival_date')->andReturn('2023-02-10');
        $mockReservationModel->shouldReceive('getAttribute')->with('departure_date')->andReturn('2023-02-12');

        $mockOfferModel->shouldReceive('whereBetween')->andReturnSelf();
        $mockOfferModel->shouldReceive('where')->andReturnSelf();
        $mockOfferModel->shouldReceive('update')->andReturnTrue();

        $mockReservationModel->shouldReceive('destroy')->andReturnTrue();

        $this->expectNotToPerformAssertions();

        $reservationService = new ReservationService($mockReservationModel, $mockOfferModel);
        $reservationService->cancelReservation(100, 10);
    }

    public function testCancelReservationWhenNotFound(): void
    {
        $mockReservationModel = Mockery::mock(Reservation::class);
        $mockOfferModel = Mockery::mock(Offer::class);

        $mockReservationModel->shouldReceive('where')
            ->with(['id' => 100, 'user_id' => 10])
            ->andReturnSelf();

        $mockReservationModel->shouldReceive('firstOrFail')->andThrow(ModelNotFoundException::class);

        $mockReservationModel->shouldReceive('destroy')->never();

        $this->expectException(ModelNotFoundException::class);

        $reservationService = new ReservationService($mockReservationModel, $mockOfferModel);
        $reservationService->cancelReservation(100, 10);
    }
}
