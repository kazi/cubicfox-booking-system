<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'roomId' => $this->id,
            'name' => $this->name,
            'hotel' => $this->hotel_name,
            'number_of_days' => $this->number_of_days,
            'totalPrice' => $this->total_price,
            'arrivalDate' => $this->arrival_date,
            'departureDate' => $this->departure_date,
            'isAvailableForReservation' => (bool)$this->available_for_reservation
        ];
    }
}
