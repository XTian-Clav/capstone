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
        $equipments = [
            [
                'equipment_name' => 'Printer, Epson L3110',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Arduino Part - Adafruit Trinket - 5V',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Conference Table for Executive',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'A4Tech OP-330s',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'AVR Secure',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'HP LaserJet Pro MFP M130nw Printer',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Whiteboard',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'High Back Mesh Chair',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Extension wire Omni',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'External Hard Drive',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Ethernet cable Black',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'DSLR camera, Canon EOS 6D (WG)',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Epson EB-S41 Projector',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Parallax Boebot Robot for Arduino kit',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Macro Ring Lite',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'HDMI 4 Pi-7” Display 1280x800 (720p) IPS',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Hand Held Gimbal Stabilizer',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'CCTV Camera',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Desktop Computer',
                'location'       => 'PITBI Office',
            ],
            [
                'equipment_name' => 'Asus ROG Strix XG32VQR Monitor',
                'location'       => 'PITBI Office',
            ],
        ];              

        foreach ($equipments as $item) {
            Equipment::create([
                'equipment_name' => $item['equipment_name'],
                'quantity' => $faker->numberBetween(2, 14),
                'property_no' => strtoupper($faker->bothify('PROPERTY-###??')),
                'location' => $item['location'],
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
            // Generate a Philippine mobile number: 09XXXXXXXXX
            $phNumber = '09' . $faker->numerify('#########');

            Mentor::create([
                'name' => $faker->name(),
                'contact' => $phNumber,
                'email' => $faker->unique()->safeEmail(),
                'expertise' => $faker->randomElement(array_values(Mentor::EXPERTISE)),
                'personal_info' => $faker->paragraph(3),
            ]);
        }

        // --- Supply ---
        $supplies = [
            [
                'item_name' => 'A4 Paper Ream',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Vellum Board 8.5 x 11',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Short Oslo Paper',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Sandisk Extreme Pro - 128 GB',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Security White Envelope',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pixma Ink Magenta',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pixma Ink Cyan',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pixma Ink Yellow',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pilot Super Color Marker (BLUE)',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pilot Super Color Marker (BLACK)',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Pilot Super Color Marker (RED)',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Photo Paper A4',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Scissors',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Tape',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Manila Paper',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Nylon Cable Tie',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Epson Ink 003 Black',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Epson Ink 003 Magenta',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'All Purpose Glue',
                'location'  => 'PITBI Office',
            ],
            [
                'item_name' => 'Epson Ink 003 Cyan',
                'location'  => 'PITBI Office',
            ],
        ];        

        foreach ($supplies as $item) {
            Supply::create([
                'item_name' => $item['item_name'],
                'quantity' => $faker->numberBetween(3, 30),
                'location' => $item['location'],
            ]);
        }

        // --- Room ---
        $roomCapacities = [
            'Small Meeting Room' => '6–10 pax',
            'Training Room' => '40–50 pax',
            'Co-Working Space' => '40–50 pax',
        ];
        
        foreach ($roomCapacities as $roomType => $capacity) {
            Room::create([
                'room_type' => $roomType,
                'location' => $faker->city(),
                'capacity' => $capacity,
                'room_rate' => 0,
                'inclusions' => $faker->sentence(),
                'is_available' => true,
            ]);
        }        

        // --- Startup ---
        $startups = [
            [
                'startup_name' => 'Docufy',
                'founder'      => 'Juan Dela Cruz',
                'description'  => 'Docufy is a mobile application and website-based service that provides on-demand printing services that process your requests online and deliver it to your door 24/7.',
            ],
            [
                'startup_name' => 'La Kniterras',
                'founder'      => 'Maria Santos',
                'description'  => 'Turning local resources found in Palawan into yarn.',
            ],
            [
                'startup_name' => 'Al Accad Travel and Tours',
                'founder'      => 'Carlos Reyes',
                'description'  => 'Pay now, travel later.',
            ],
            [
                'startup_name' => 'Fundr Business Support Services',
                'founder'      => 'Ana Lopez',
                'description'  => 'Fundr is an innovative financial technology platform that helps aspiring and small businesses seeking capitalization.',
            ],
            [
                'startup_name' => 'Galareya',
                'founder'      => 'Miguel Tan',
                'description'  => 'FishMatic is a multifunctional 3-in-1 tool made to prepare fish easier. It helps dried fish and lamayo makers by combining scaling, cleaning, and cutting in one tool.',
            ],
            [
                'startup_name' => 'KarinderyaGo',
                'founder'      => 'Isabella Cruz',
                'description'  => 'KarinderyaGo is an app that expands the opportunity of serving local cuisine by bridging the gap between Karinderya businesses and customers.',
            ],
            [
                'startup_name' => 'Yeomju',
                'founder'      => 'Rafael Gomez',
                'description'  => 'Yeomju provides personalized clothing accessories, helping users express themselves through style and improve their overall fashion identity.',
            ],
            [
                'startup_name' => 'Kalinangan',
                'founder'      => 'Luz Mendoza',
                'description'  => 'Kalinangan merges sustainability and cultural heritage in packaging, creating eco-friendly solutions inspired by the rich culture of Palawan.',
            ],
            [
                'startup_name' => 'Ecocoal PH',
                'founder'      => 'Ricardo Villanueva',
                'description'  => 'Producers of charcoal briquettes made from leftover wood, sawdust, coconut shells, and other recycled materials.',
            ],
            [
                'startup_name' => 'Silinga',
                'founder'      => 'Paula Lim',
                'description'  => 'A roastery business focused on locally sourced coffee beans with various types of coffee offerings.',
            ],
            [
                'startup_name' => 'Artisanal Local Coffee Roasters',
                'founder'      => 'Victor Santos',
                'description'  => 'A roastery business that focuses on locally sourced coffee beans, offering different kinds of coffee.',
            ],
            [
                'startup_name' => 'Cleaning Your Pathway Solutions',
                'founder'      => 'Karen Tan',
                'description'  => 'A business creating cleaning chemicals and solutions for pathways and outdoor spaces.',
            ],
            [
                'startup_name' => 'Serenelinen',
                'founder'      => 'James Navarro',
                'description'  => 'Serenelinen operates under the textile industry, offering tailoring and custom-made fabric services.',
            ],
        ];        

        foreach ($startups as $item) {
            $created_at = $faker->dateTimeThisYear();

            Startup::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'startup_name' => $item['startup_name'],
                'founder' => $item['founder'],
                'description' => $item['description'],
                'created_at' => $created_at,
                'status' => $faker->randomElement(array_values(Startup::STATUS)),
            ]);
        }

        // --- Video ---
        $videos = [
            [
                'title' => 'PITBI sample video',
                'url'   => 'https://drive.google.com/file/d/1SAwTRtdk3OoxEb3JGYGhz-fxTQGX16wG/view',
            ],
            [
                'title' => 'How to Pitch Your Startup to Investors',
                'url'   => 'https://www.youtube.com/watch?v=J3QZ5gfCtAg',
            ],
            [
                'title' => 'Writing a Business Plan Step by Step',
                'url'   => 'https://www.youtube.com/watch?v=Fqch5OrUPvA',
            ],
            [
                'title' => 'Startup Funding Explained — Seed, Series A, B, C',
                'url'   => 'https://www.youtube.com/watch?v=Tq4nrmn0SBE',
            ],
            [
                'title' => 'Marketing Strategies for Small Businesses',
                'url'   => 'https://www.youtube.com/watch?v=L9Q2pN3J6yE',
            ],
            [
                'title' => 'Agile Methodology in 10 Minutes',
                'url'   => 'https://www.youtube.com/watch?v=Z9QbYZh1YXY',
            ],
            [
                'title' => 'What is UI/UX Design? A Beginner\'s Guide',
                'url'   => 'https://www.youtube.com/watch?v=9B1V7xYrhAc',
            ],
            [
                'title' => 'Social Media Marketing Full Course',
                'url'   => 'https://www.youtube.com/watch?v=2EU4pZPNsJY',
            ],
            [
                'title' => 'Time Management Tips for Entrepreneurs',
                'url'   => 'https://www.youtube.com/watch?v=lHfjvYzr-5A',
            ],
            [
                'title' => 'How Artificial Intelligence Is Changing Business',
                'url'   => 'https://www.youtube.com/watch?v=2ePf9rue1Ao',
            ],
            [
                'title' => 'Cloud Computing Explained',
                'url'   => 'https://www.youtube.com/watch?v=M988_fsOSWo',
            ],
            [
                'title' => 'Top Presentation Skills for Entrepreneurs',
                'url'   => 'https://www.youtube.com/watch?v=HAnw168huqA',
            ],
            [
                'title' => 'Effective Team Building Strategies',
                'url'   => 'https://www.youtube.com/watch?v=asA-2I78O08',
            ],
            [
                'title' => 'Business Networking Tips for Founders',
                'url'   => 'https://www.youtube.com/watch?v=J5C1fD6x5Xo',
            ],
            [
                'title' => 'Beginner’s Guide to Entrepreneurship',
                'url'   => 'https://www.youtube.com/watch?v=VfGW0Qiy2I0',
            ],
            [
                'title' => 'How to Validate Your Startup Idea',
                'url'   => 'https://www.youtube.com/watch?v=0Uk1G2G3R3s',
            ],
        ];        

        foreach ($videos as $video) {
            Video::create([
                'title' => $video['title'],
                'description' => $faker->sentence(),
                'url' => $video['url'],
            ]);
        }

        // --- Guide ---
        $guides = [
            [
                'title' => 'Startup Legal Checklist',
                'url'   => 'https://www.promise.legal/resources/startup-legal-checklist',
            ],
            [
                'title' => 'Important Legal Requirements for Small Businesses',
                'url'   => 'https://www.nerdwallet.com/business/legal/learn/startup-checklist',
            ],
            [
                'title' => '16-Step Legal Checklist for Startups',
                'url'   => 'https://lydagroup.com/blog/startups-checklist/',
            ],
            [
                'title' => 'Business Model Canvas – Instruction Manual',
                'url'   => 'https://www.strategyzer.com/library/the-business-model-canvas-instruction-manual',
            ],
            [
                'title' => 'Free Business Model Canvas PDF',
                'url'   => 'https://assets.strategyzer.com/assets/resources/the-business-model-canvas.pdf',
            ],
            [
                'title' => 'Startup Legal Checklist for Indian Startups',
                'url'   => 'https://startupspark.in/startup-legal-checklist-in-india/',
            ],
            [
                'title' => 'Startup & Tech Legal Checklist (Vietnam)',
                'url'   => 'https://www.startuplaw.vn/en/legal-checklist-for-startup',
            ],
        ];        

        foreach ($guides as $guide) {
            Guide::create([
                'title' => $guide['title'],
                'description' => $faker->sentence(),
                'url' => $guide['url'],
            ]);
        }        
    }
}
