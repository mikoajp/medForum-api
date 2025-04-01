<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Event::create(['title' => 'Koncert', 'description' => 'Super koncert', 'event_date' => '2025-05-01 18:00:00']);
        Event::create(['title' => 'Spotkanie', 'description' => 'Spotkanie zespołu', 'event_date' => '2025-04-15 10:00:00']);
    }
}
