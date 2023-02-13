<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DestroyReservationRequest;
use App\Http\Requests\V1\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\V1\ReservationCollection;
use App\Http\Resources\V1\ReservationResource;
use App\Models\Reservation;
use App\Filters\Api\V1\ReservationsFilter;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ReservationController extends Controller
{
    public function index(Request $request, ReservationsFilter $reservationsFilter)
    {
        return
            new ReservationCollection(
                Reservation::where($reservationsFilter->getReservationFilterItems($request))
                    ->where('reservations.user_id', '=', Auth::id())
                    ->with('room.hotel')
                    ->paginate()
                    ->appends($request->query())
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    public function store(StoreReservationRequest $request, ReservationService $reservationService)
    {
        try {
            $reservation = $reservationService->makeReservationForRoom(
                Auth::id(),
                $request->roomId,
                $request->arrivalDate,
                $request->departureDate
            );

            return new ReservationResource($reservation);
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $throwable->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return Response
     */
    public function show(Reservation $reservation): ReservationResource
    {
        return new ReservationResource($reservation->loadMissing('room.hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReservationRequest  $request
     * @param  \App\Models\Reservation  $reservation
     * @return Response
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    public function destroy(DestroyReservationRequest $request, ReservationService $reservationService)
    {
        try {
            $reservationService->cancelReservation($request->reservationId, 1);
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $throwable->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
