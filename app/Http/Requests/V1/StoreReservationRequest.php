<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;            //TODO: Authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'roomId' => ['required', 'integer', 'exists:rooms,id'],
            'arrivalDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'departureDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:arrivalDate'],
        ];
    }
}
