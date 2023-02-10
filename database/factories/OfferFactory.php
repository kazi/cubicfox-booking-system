<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'room_id' => $this->faker->numberBetween(1,10),
            'day' => $this->faker->date('Y-m-d'),
            'price' => $this->faker->numberBetween(1000,2000),
            'is_available' => $this->faker->boolean()
        ];
    }
}
