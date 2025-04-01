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
        Event::create(['title' => 'Konferencja medyczna', 'description' => 'Międzynarodowa konferencja poświęcona najnowszym osiągnięciom w medycynie.', 'event_date' => '2025-06-10 09:00:00']);
        Event::create(['title' => 'Warsztat programistyczny', 'description' => 'Praktyczne warsztaty z programowania w Laravel i Vue.js.', 'event_date' => '2025-04-28 13:00:00']);
        Event::create(['title' => 'Targi kariery', 'description' => 'Spotkaj potencjalnych pracodawców i poznaj nowe możliwości rozwoju zawodowego.', 'event_date' => '2025-05-15 10:00:00']);
        Event::create(['title' => 'Festiwal filmowy', 'description' => 'Przegląd najlepszych niezależnych produkcji filmowych z całego świata.', 'event_date' => '2025-07-05 16:00:00']);
        Event::create(['title' => 'Maraton', 'description' => 'Doroczny maraton miejski - 42 km przez centrum miasta.', 'event_date' => '2025-09-12 08:00:00']);
        Event::create(['title' => 'Wystawa sztuki', 'description' => 'Ekspozycja dzieł młodych artystów z regionu.', 'event_date' => '2025-05-20 11:00:00']);
        Event::create(['title' => 'Hackathon', 'description' => '24-godzinne wydarzenie dla programistów, projektantów i entuzjastów technologii.', 'event_date' => '2025-06-15 09:00:00']);
        Event::create(['title' => 'Targi książki', 'description' => 'Największe w regionie wydarzenie dla miłośników literatury z udziałem znanych autorów.', 'event_date' => '2025-08-05 10:00:00']);
        Event::create(['title' => 'Seminarium naukowe', 'description' => 'Dyskusja nad najnowszymi badaniami w dziedzinie sztucznej inteligencji.', 'event_date' => '2025-04-22 14:30:00']);
        Event::create(['title' => 'Piknik rodzinny', 'description' => 'Wydarzenie dla całych rodzin z grami, zabawami i poczęstunkiem.', 'event_date' => '2025-06-01 12:00:00']);
    }
}
