<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DestroyReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return !empty($user) && $user->tokenCan('api-token');
    }

    public function rules(): array
    {
        return [
            'reservationId' => ['required', 'integer']
        ];
    }
}
