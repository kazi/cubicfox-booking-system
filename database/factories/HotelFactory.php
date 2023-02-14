<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company()
        ];
    }
}
