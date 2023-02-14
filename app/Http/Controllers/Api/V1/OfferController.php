<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RoomOfferCollection;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class OfferController extends Controller
{
    public function index(
        Request $request,
        OfferService $offerService
    ): RoomOfferCollection|JsonResponse {
        try {
            $roomOffers = $offerService->getRoomOffers($request);
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $throwable->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new RoomOfferCollection($roomOffers);
    }
}
