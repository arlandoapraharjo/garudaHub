# Garuda-Hub (Laravel MVC Edition)

Refactoring sistem lelang proyek IT mahasiswa dari PHP Native monolitik (*spaghetti code*) menjadi arsitektur MVC berbasis framework Laravel, database relasional, dan Eloquent ORM.

## Ringkasan Perubahan Arsitektur

| Komponen | Versi Legacy | Versi Refactor (Laravel) |
|---|---|---|
| Arsitektur | 1 file monolitik (`src/index.php`) | MVC terstruktur (`app/`, `resources/views/`, `routes/web.php`) |
| Basis Data | Skema statis tanpa relasi FK | Migrasi relasional dengan Foreign Key, Indexing & Composite Unique |
| Akses Data | Raw SQL procedural (`mysqli`) | Eloquent ORM (`User`, `Project`, `Bid`) |
| Transaksi | Manual `begin_transaction` | `DB::transaction()` atomik untuk pemilihan pemenang lelang |
| Keamanan | Sesi manual & otentikasi native | Laravel Auth + Role-based Middleware (`klien`, `developer`, `admin`) |
| Antarmuka | String HTML concatenations | Blade Engine dengan tata letak utilitarian monokrom |

## Kebutuhan Sistem

- PHP >= 8.2 (ekstensi `pdo_mysql`, `mbstring`, `openssl`)
- Composer 2.x
- MySQL 8.x / MariaDB (misal via Laragon atau XAMPP)

## Panduan Instalasi

```bash
# 1. Klon dan masuk ke direktori
git clone https://github.com/arlandoapraharjo/garudaHub.git
cd garudaHub
git checkout kelompok-alan

# 2. Pasang dependensi
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# Pastikan konfigurasi database di .env mengarah ke database Anda:
# DB_DATABASE=garuda_hub, DB_USERNAME=root, DB_PASSWORD=

# 4. Migrasi dan seeder data
php artisan migrate:fresh --seed

# 5. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

## Akun Demo Pengujian

| Peran | Email | Password | Keterangan |
|---|---|---|---|
| Admin | admin@example.com | admin123 | Manajemen pengguna (ubah peran, hapus) dan proyek |
| Klien | budi@example.com | klien123 | Memasang proyek dan memilih pemenang lelang |
| Developer | dewi@example.com | dev123 | Menjelajahi proyek dan mengajukan penawaran (*bid*) |

Pendaftaran akun baru (Klien atau Developer) juga dapat dilakukan via menu pendaftaran.

## Skema Database Relasional

- **users**: `id`, `name`, `email` (UNIQUE), `password`, `role` (enum: klien, developer, admin).
- **projects**: `id`, `client_id` (FK -> users, CASCADE), `judul`, `deskripsi`, `kategori`, `budget_min`, `budget_max`, `batas_lelang`, `status` (enum: terbuka, ditutup).
- **bids**: `id`, `project_id` (FK -> projects, CASCADE), `developer_id` (FK -> users, CASCADE), `harga`, `durasi`, `proposal`, `is_winner` (boolean), UNIQUE(`project_id`, `developer_id`).

## Riwayat Commit Git (Branch kelompok-alan)

1. `chore: setup laravel framework structure and dependencies`
2. `feat(database): create relational migrations for users, projects, and bids`
3. `feat(models): implement eloquent models with relationships and mass assignment`
4. `feat(seeders): add database seeders for users, projects, and bids`
5. `feat(auth): implement authentication and role-based middleware`
6. `feat(controllers): implement CRUD operations for projects, bidding, and winner selection`
7. `feat(views): implement blade templates and layout for all modules`
8. `feat(views): clean up ui to ultra-clean utilitarian monochrome style`
9. `docs: update readme with laravel setup guide and architectural documentation`

## Lisensi
MIT License.
