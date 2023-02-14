<?php

namespace App\Services;

use App\Exceptions\RoomNotAvailableException;
use App\Models\Offer;
use App\Models\Reservation;
use DateTime;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservationService
{
    private const NOT_AVAILABLE = false;
    private const AVAILABLE = true;
    private const ERROR_MESSAGE_ROOM_NOT_AVAILABLE = 'The selected room is not available for that period.';

    private Offer $offerModel;
    private Reservation $reservationModel;

    function __construct(Reservation $reservationModel, Offer $offerModel) {
        $this->reservationModel = $reservationModel;
        $this->offerModel = $offerModel;
    }

    public function makeReservationForRoom(int $userId, int $roomId, string $firstDay, string $lastDay): ?Reservation
    {
        if (!$this->isRoomAvailableForInterval($roomId, $firstDay, $lastDay)) {
            throw new RoomNotAvailableException(self::ERROR_MESSAGE_ROOM_NOT_AVAILABLE);
        }

        try {
            DB::beginTransaction();

            $reservation = $this->reservationModel::create(
                [
                    'user_id' => $userId,
                    'room_id' => $roomId,
                    'arrival_date' => $firstDay,
                    'departure_date' => $lastDay,
                    'price' => $this->sumPriceForInterval($roomId, $firstDay, $lastDay)
                ]
            );

            $this->setRoomAvailabilityForInterval(self::NOT_AVAILABLE, $roomId, $firstDay, $lastDay);

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }

        return $reservation ?? null;
    }

    /**
     * @param int $reservationId
     * @param int $userId
     * @throws Throwable
     */
    public function cancelReservation(int $reservationId, int $userId): void
    {
        $reservation = $this->reservationModel::where(['id' => $reservationId, 'user_id' => $userId])->firstOrFail();

        try {
            DB::beginTransaction();

            $this->setRoomAvailabilityForInterval(
                self::AVAILABLE,
                $reservation->room_id,
                $reservation->arrival_date,
                $reservation->departure_date
            );

            $reservation->destroy($reservationId);

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw $throwable;
        }
    }

    private function setRoomAvailabilityForInterval(
        bool $isAvailable,
        int $roomId,
        string $firstDay,
        string $lastDay
    ): void {
        $this->offerModel::whereBetween('offers.day', [$firstDay, $lastDay])
            ->where('offers.room_id', '=', $roomId)
            ->update(['is_available' => $isAvailable]);
    }

    private function isRoomAvailableForInterval(int $roomId, string $firstDay, string $lastDay): bool
    {
        $availableDaysForRoom = $this->offerModel::whereBetween('offers.day', [$firstDay, $lastDay])
            ->where('offers.room_id', '=', $roomId)
            ->where('offers.is_available', '=', self::AVAILABLE)
            ->count();

        return $availableDaysForRoom == $this->daysBetweenDates($firstDay, $lastDay);
    }

    private function sumPriceForInterval(int $roomId, string $firstDay, string $lastDay): float
    {
        return $this->offerModel::whereBetween('day', [$firstDay, $lastDay])
            ->where('room_id', '=', $roomId)
            ->sum('price');
    }

    private function daysBetweenDates(string $firstDay, string $lastDay): int
    {
        $earlier = new DateTime($firstDay);
        $later = new DateTime($lastDay);

        return intval($later->diff($earlier)->format("%a")) + 1;
    }
}
