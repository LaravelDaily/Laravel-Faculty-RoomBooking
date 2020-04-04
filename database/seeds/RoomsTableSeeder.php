<?php

use App\Room;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $rooms = [];

        for ($i = 1; $i <= 50; $i++) {
            $rooms[] = [
                'id'          => $i,
                'name'        => 'Room ' . intval($i + 100),
                'description' => $faker->paragraph,
                'capacity'    => mt_rand(10, 100),
            ];
        }

        Room::insert($rooms);
    }
}
