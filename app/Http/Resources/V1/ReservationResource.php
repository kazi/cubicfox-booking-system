<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'id' => $this->id ?? null,
            'userId' => $this->user_id,
            'roomId' => $this->room_id,
            'arrivalDate' => $this->arrival_date,
            'departureDate' => $this->departure_date,
            'price' => $this->price,
            'room' => RoomResource::make($this->whenLoaded('room'))
        ];
    }
}
