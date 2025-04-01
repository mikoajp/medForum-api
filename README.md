# README - Aplikacja Laravel Events

Aplikacja do zarządzania wydarzeniami zbudowana przy użyciu Laravel i Vue.js.

## Wymagania systemowe

- PHP >= 8.2
- Composer
- Node.js
- Git
- SQLite (dla środowiska lokalnego) lub PostgreSQL (dla produkcji)

## Instalacja lokalna

Poniższe instrukcje przeprowadzą Cię przez proces instalacji i uruchomienia aplikacji na lokalnym środowisku deweloperskim.

### Krok 1: Sklonuj repozytorium

```bash
git clone  git@github.com:mikoajp/medForum-api.git
cd medForum-api
```

### Krok 2: Zainstaluj zależności PHP

```bash
composer install
```

### Krok 3: Skonfiguruj plik .env

```bash
cp .env.example .env
php artisan key:generate
```

Następnie otwórz plik `.env` i dostosuj ustawienia, szczególnie dotyczące bazy danych:

```
DB_CONNECTION=sqlite
```

### Krok 4: Utwórz plik bazy danych SQLite

```bash
touch database/database.sqlite
```

### Krok 5: Uruchom migracje i seedery

```bash
php artisan migrate
php artisan db:seed --class=EventSeeder
```

### Krok 6: Zainstaluj zależności JavaScript i skompiluj zasoby

```bash
npm install
npm run build
npm run dev
```

### Krok 7: Uruchom serwer deweloperski

```bash
php artisan serve
```

Aplikacja będzie dostępna pod adresem [http://localhost:8000](http://localhost:8000).

## Struktura API

Aplikacja udostępnia następujące endpointy API:

- `GET /api/events` - Lista wszystkich wydarzeń
- `GET /api/events/{id}` - Szczegóły konkretnego wydarzenia

Każde wydarzenie zawiera:

- ID
- Tytuł
- Opis
- Datę wydarzenia

## Funkcjonalności

- Przeglądanie listy wydarzeń
- Sortowanie wydarzeń według tytułu lub daty (rosnąco/malejąco)
- Wyświetlanie szczegółów wydarzenia
- Licznik odliczający czas do wydarzenia (dni, godziny, minuty, sekundy)
