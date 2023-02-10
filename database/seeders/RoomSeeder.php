<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public const ROOM_NAMES = [
        1 => ['Elnöki szoba', 'Királyi szoba'],
        2 => ['Arany szoba', 'Ezüst szoba', 'Bronz szoba', 'Vas szoba'],
        3 => ['Luxus szoba', 'VIP szoba', 'Prémium szoba']
    ];

    public function run()
    {
        foreach (self::ROOM_NAMES as $hotelId => $roomNames) {
            foreach ($roomNames as $name) {
                DB::table('rooms')->insert(
                    [
                        'hotel_id' => $hotelId,
                        'name' => $name
                    ]
                );
            }
        }
    }
}
