<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\Event;
use App\Models\Guide;
use App\Models\Video;
use App\Models\Mentor;
use App\Models\Supply;
use App\Models\Startup;
use App\Models\Equipment;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);   //1st
        $this->seedOtherData();                 //2nd
        $this->call(ReservationSeeder::class);  //3rd
    }

    private function seedOtherData(): void
    {
        $faker = Faker::create();

        // Helper for guaranteed unique names
        $uniqueWord = fn($prefix = 'Name') => ucfirst($prefix . '_' . rand(1000, 9999));

        // --- Equipment ---
        for ($i = 0; $i < 25; $i++) {
            Equipment::create([
                'equipment_name' => $uniqueWord('Equipment'),
                'quantity' => $faker->numberBetween(1, 20),
                'property_no' => strtoupper($faker->bothify('PROPERTY-###??')),
                'location' => $faker->city(),
                'remarks' => $faker->sentence(),
            ]);
        }

        // --- Event ---
        for ($i = 0; $i < 25; $i++) {
            $start = $faker->dateTimeBetween('-1 month', '+1 month');
            $end = (clone $start)->modify('+' . rand(1, 5) . ' days');
            Event::create([
                'event' => $uniqueWord('Event'),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'start_date' => $start,
                'end_date' => $end,
                'status' => $faker->randomElement(array_values(Event::STATUS)),
            ]);
        }

        // --- Mentor ---
        for ($i = 0; $i < 25; $i++) {
            Mentor::create([
                'name' => $uniqueWord('Mentor'),
                'contact' => $faker->phoneNumber(),
                'email' => $faker->unique()->safeEmail(),
                'expertise' => $faker->randomElement(array_values(Mentor::EXPERTISE)),
                'personal_info' => $faker->paragraph(3),
            ]);
        }

        // --- Supply ---
        for ($i = 0; $i < 25; $i++) {
            Supply::create([
                'item_name' => $uniqueWord('Item'),
                'quantity' => $faker->numberBetween(1, 50),
                'location' => $faker->city(),
                'remarks' => $faker->sentence(),
            ]);
        }

        // --- Room ---
        for ($i = 0; $i < 25; $i++) {
            Room::create([
                'room_name' => $uniqueWord('Room'),
                'room_type' => $faker->randomElement(array_values(Room::ROOM_TYPE)),
                'location' => $faker->city(),
                'capacity' => $faker->numberBetween(5, 50),
                'room_rate' => $faker->numberBetween(500, 1000),
                'inclusions' => $faker->sentence(),
                'is_available' => $faker->boolean(),
            ]);
        }

        // --- Startup ---
        for ($i = 0; $i < 25; $i++) {
            $created_at = $faker->dateTimeThisYear();
            Startup::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'startup_name' => $uniqueWord('Startup'),
                'founder' => $faker->name(),
                'description' => $faker->paragraph(4),
                'created_at'=> $created_at,
                'status' => $faker->randomElement(array_values(Startup::STATUS)),
            ]);
        }

        // --- Video ---
        for ($i = 0; $i < 25; $i++) {
            Video::create([
                'title' => $uniqueWord('Video'),
                'description' => $faker->paragraph(1),
                'url' => 'https://www.youtube.com/watch?v=' . $faker->regexify('[A-Za-z0-9_-]{11}'),
            ]);
        }

        // --- Guide ---
        for ($i = 0; $i < 25; $i++) {
            Guide::create([
                'title' => $uniqueWord('Guide'),
                'description' => $faker->paragraph(1),
                'url' => 'https://example.com/' . $faker->slug(),
            ]);
        }
    }
}
