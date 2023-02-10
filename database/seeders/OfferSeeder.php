<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    private const DAYS_TO_SEED_OFFERS_FOR = 30;

    public function run()
    {
        $rooms = array_sum(array_map("count", RoomSeeder::ROOM_NAMES));

        for ($roomId = 1; $roomId <= $rooms; $roomId++) {
            for ($days = 0; $days < self::DAYS_TO_SEED_OFFERS_FOR; $days++) {
                DB::table('offers')->insert(
                    [
                        'room_id' => $roomId,
                        'day' => date('Y-m-d', strtotime('now + ' . $days . ' days')),
                        'price' => $roomId * 1000,
                        'is_available' => true
                    ]
                );
            }
        }
    }
}
