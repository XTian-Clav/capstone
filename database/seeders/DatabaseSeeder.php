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
            ['equipment_name' => 'Printer, Epson L3110', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Arduino Part - Adafruit Trinket - 5V', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Conference Table for Executive', 'location' => 'PITBI Office'],
            ['equipment_name' => 'A4Tech OP-330s', 'location' => 'PITBI Office'],
            ['equipment_name' => 'AVR Secure', 'location' => 'Server Room'],
            ['equipment_name' => 'Printer, HP LaserJet Pro MFP M130nw', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Whiteboard', 'location' => 'Training Room'],
            ['equipment_name' => 'Conference Chair, High Back Mesh Chair', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Extension wire Omni', 'location' => 'PITBI Office'],
            ['equipment_name' => 'External Hard Drive', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Ethernet cable Black', 'location' => 'PITBI Office'],
            ['equipment_name' => 'DSLR camera, Canon EOS 6D (WG)', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Multimedia Projector, Epson EB-S41', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Parallax Boebot Robot for Arduino kit', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Macro Ring Lite', 'location' => 'PITBI Office'],
            ['equipment_name' => 'HDMI 4 Pi-7” Display 1280x800 (720p) IPS', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Hand Held Gimbal Stabilizer', 'location' => 'PITBI Office'],
            ['equipment_name' => 'CCTV Camera', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Desktop Computer', 'location' => 'PITBI Office'],
            ['equipment_name' => 'Curve LED Backlit LCD Monitor, Asus ROG Strix XG32VQR', 'location' => 'PITBI Office'],
        ];        

        foreach ($equipments as $item) {
            Equipment::create([
                'equipment_name' => $item['equipment_name'],
                'quantity' => $faker->numberBetween(2, 14),
                'property_no' => strtoupper($faker->bothify('PROPERTY-###??')),
                'location' => $item['location'],
                'remarks' => $faker->randomElement(['Checked', 'Missing', 'No reference no.']),
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
            ['item_name' => 'A4 Paper Ream', 'location' => 'PITBI Office'],
            ['item_name' => 'Pens', 'location' => 'PITBI Office'],
            ['item_name' => 'Pencils', 'location' => 'PITBI Office'],
            ['item_name' => 'Markers', 'location' => 'PITBI Office'],
            ['item_name' => 'Notebooks', 'location' => 'PITBI Office'],
            ['item_name' => 'Staplers', 'location' => 'PITBI Office'],
            ['item_name' => 'Staples', 'location' => 'PITBI Office'],
            ['item_name' => 'Whiteboard Markers', 'location' => 'PITBI Office'],
            ['item_name' => 'Erasers', 'location' => 'PITBI Office'],
            ['item_name' => 'Folders', 'location' => 'PITBI Office'],
            ['item_name' => 'Highlighters', 'location' => 'PITBI Office'],
            ['item_name' => 'Glue Sticks', 'location' => 'PITBI Office'],
            ['item_name' => 'Scissors', 'location' => 'PITBI Office'],
            ['item_name' => 'Tape', 'location' => 'PITBI Office'],
            ['item_name' => 'Post-it Notes', 'location' => 'PITBI Office'],
            ['item_name' => 'Printer Ink', 'location' => 'PITBI Office'],
            ['item_name' => 'USB Drives', 'location' => 'PITBI Office'],
            ['item_name' => 'Envelopes', 'location' => 'PITBI Office'],
            ['item_name' => 'Labels', 'location' => 'PITBI Office'],
            ['item_name' => 'Folders (Plastic)', 'location' => 'PITBI PITBI Office'],
        ];

        foreach ($supplies as $item) {
            Supply::create([
                'item_name' => $item['item_name'],
                'quantity' => $faker->numberBetween(3, 30),
                'location' => $item['location'],
                'remarks' => $faker->randomElement(['New', 'Used']),
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
            ['startup_name' => 'TechNova', 'founder' => 'Juan Dela Cruz'],
            ['startup_name' => 'GreenWave Solutions', 'founder' => 'Maria Santos'],
            ['startup_name' => 'SmartFarm PH', 'founder' => 'Carlos Reyes'],
            ['startup_name' => 'EduTech Labs', 'founder' => 'Ana Lopez'],
            ['startup_name' => 'PixelHive', 'founder' => 'Miguel Tan'],
            ['startup_name' => 'AquaPure', 'founder' => 'Isabella Cruz'],
            ['startup_name' => 'BioSense', 'founder' => 'Rafael Gomez'],
            ['startup_name' => 'DroneXpress', 'founder' => 'Luz Mendoza'],
            ['startup_name' => 'SolarSmart', 'founder' => 'Ricardo Villanueva'],
            ['startup_name' => 'QuickDeliver', 'founder' => 'Paula Lim'],
            ['startup_name' => 'UrbanGrow', 'founder' => 'Victor Santos'],
            ['startup_name' => 'CodeBridge', 'founder' => 'Karen Tan'],
            ['startup_name' => 'MediScan PH', 'founder' => 'James Navarro'],
            ['startup_name' => 'SafeRide', 'founder' => 'Monica Flores'],
            ['startup_name' => 'FoodLoop', 'founder' => 'Emilio Ramos'],
            ['startup_name' => 'GreenFleet', 'founder' => 'Sophia Garcia'],
            ['startup_name' => 'DataWave', 'founder' => 'Antonio Perez'],
            ['startup_name' => 'CleanAir Tech', 'founder' => 'Gloria Santos'],
            ['startup_name' => 'SmartLock PH', 'founder' => 'David Yu'],
            ['startup_name' => 'EcoHome', 'founder' => 'Liza Reyes'],
        ];

        foreach ($startups as $item) {
            $created_at = $faker->dateTimeThisYear();

            Startup::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'startup_name' => $item['startup_name'],
                'founder' => $item['founder'],
                'description' => $faker->paragraph(3), // random paragraph
                'created_at' => $created_at,
                'status' => $faker->randomElement(array_values(Startup::STATUS)),
            ]);
        }

        // --- Video ---
        $videoTitles = [
            'How to Pitch Your Startup',
            'Introduction to Agile Methodology',
            'Basics of Web Development',
            'Creating Effective Business Plans',
            'Startup Funding 101',
            'Marketing Strategies for Startups',
            'UX/UI Design Principles',
            'Social Media Growth Hacks',
            'Time Management for Entrepreneurs',
            'Productivity Tips for Teams',
            'Introduction to AI in Business',
            'Cloud Computing Basics',
            'Effective Presentation Skills',
            'Team Building Exercises',
            'Networking Strategies for Founders',
        ];

        foreach ($videoTitles as $title) {
            Video::create([
                'title' => $title,
                'description' => $faker->paragraph(1),
                'url' => 'https://www.youtube.com/watch?v=' . $faker->regexify('[A-Za-z0-9_-]{11}'),
            ]);
        }

        // --- Guide ---
        $guideTitles = [
            'Startup Legal Checklist',
            'Guide to Financial Management',
            'Branding Essentials',
            'Creating a Minimum Viable Product',
            'Customer Validation Techniques',
            'Pitch Deck Template Guide',
            'Market Research Steps',
            'Lean Startup Methodology',
            'Social Media Content Plan',
            'Email Marketing Guide',
            'Project Management Basics',
            'Fundraising Tips for Startups',
            'Hiring Your First Employees',
            'Business Model Canvas Guide',
            'SEO Optimization for Beginners',
        ];

        foreach ($guideTitles as $title) {
            Guide::create([
                'title' => $title,
                'description' => $faker->paragraph(1),
                'url' => 'https://example.com/' . $faker->slug(),
            ]);
        }
    }
}
