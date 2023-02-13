<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return !empty($user) && $user->tokenCan('api-token');
    }

    public function rules(): array
    {
        return [
            'roomId' => ['required', 'integer', 'exists:rooms,id'],
            'arrivalDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'departureDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:arrivalDate'],
        ];
    }
}
