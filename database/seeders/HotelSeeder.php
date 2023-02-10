<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    private const HOTEL_NAMES = [
        'Palatinus Grand Hotel',
        'Hotel Kikelet',
        'Fenyves Hotel***'
    ];

    public function run()
    {
        foreach (self::HOTEL_NAMES as $name) {
            DB::table('hotels')->insert(
                [
                    'name' => $name
                ]
            );
        }
    }
}
