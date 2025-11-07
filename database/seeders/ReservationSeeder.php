<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\Supply;
use App\Models\Equipment;
use App\Models\ReserveRoom;
use Faker\Factory as Faker;
use App\Models\ReserveSupply;
use Illuminate\Database\Seeder;
use App\Models\ReserveEquipment;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get existing inventory IDs
        $roomIds = Room::pluck('id')->toArray();
        $supplyIds = Supply::pluck('id')->toArray();
        $equipmentIds = Equipment::pluck('id')->toArray();

        // Get existing user IDs
        $userIds = User::pluck('id')->toArray();

        // --- Equipment ---
        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeThisYear();
            $createdAt = (clone $startDate)->modify('-' . rand(1, 7) . ' days');
            $endDate = (clone $startDate)->modify('+' . rand(1, 7) . ' days');

            ReserveEquipment::create([
                'reserved_by' => $faker->name(),
                'user_id' => $faker->randomElement($userIds),
                'equipment_id' => $faker->randomElement($equipmentIds),
                'quantity' => rand(1, 5),
                'status' => $faker->randomElement(['Pending', 'Approved', 'Rejected']),
                'company' => $faker->company(),
                'contact' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_at'=> $createdAt,
                'accept_terms' => true,
            ]);
        }

        // --- Room ---
        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeThisYear();
            $createdAt = (clone $startDate)->modify('-' . rand(1, 7) . ' days');
            $endDate = (clone $startDate)->modify('+' . rand(1, 7) . ' days');
        
            $room = Room::inRandomOrder()->first();
        
            ReserveRoom::create([
                'reserved_by' => $faker->name(),
                'user_id' => $faker->randomElement($userIds),
                'room_id' => $room->id,
                'room_type' => $room->room_type,
                'status' => $faker->randomElement(['Pending', 'Approved', 'Rejected']),
                'company' => $faker->company(),
                'contact' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'accept_terms' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // --- Supply ---
        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeThisYear();
            $createdAt = (clone $startDate)->modify('-' . rand(1, 7) . ' days');
            $endDate = (clone $startDate)->modify('+' . rand(1, 7) . ' days');

            ReserveSupply::create([
                'reserved_by' => $faker->name(),
                'user_id' => $faker->randomElement($userIds),
                'supply_id' => $faker->randomElement($supplyIds),
                'quantity' => rand(1, 10),
                'status' => $faker->randomElement(['Pending', 'Approved', 'Rejected']),
                'company' => $faker->company(),
                'contact' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'accept_terms' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
