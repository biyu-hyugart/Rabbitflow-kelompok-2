# Struktur Database RabbitFlow

Dokumen ini merangkum tabel, kolom, tipe data, dan relasi **langsung dari file migration dan model** yang ada di proyek. Tidak ada nama tabel, nama kolom, atau relasi yang diubah.

Sumber:

- `database/migrations/2026_11_17_000001_create_roles_table.php`
- `database/migrations/2026_11_17_000002_create_users_table.php`
- `database/migrations/2026_11_17_000003_create_cages_table.php`
- `database/migrations/2026_11_17_000004_create_rabbits_table.php`
- `database/migrations/2026_11_17_000005_create_growth_records_table.php`
- `database/migrations/2026_11_17_000006_create_logistics_categories_table.php`
- `database/migrations/2026_11_17_000007_create_logistics_items_table.php`
- `database/migrations/2026_11_17_000008_create_breeding_records_table.php`

Selain delapan tabel utama tersebut ada dua tabel bawaan Laravel dari `0001_01_01_000001_create_cache_table.php` dan `0001_01_01_000002_create_jobs_table.php` (`cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`). Tabel bawaan itu tidak dipakai sebagai data bisnis.

## 1. Tabel `roles`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment (`$table->id()`) |
| `nama_role` | `varchar(50)` | Unik. Nilai dari `Role::OWNER` = `Owner`, `Role::KARYAWAN` = `Karyawan` |
| `created_at`, `updated_at` | `timestamp` | Nullable, dari `$table->timestamps()` |

Model: `app/Models/Role.php` — konstanta `OWNER`, `KARYAWAN`; relasi `users()` = `hasMany(User::class)`.

## 2. Tabel `users`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `role_id` | `bigint unsigned` | Foreign key ke `roles.id`, `restrictOnDelete()` |
| `nama_lengkap` | `varchar` | Nama akun |
| `username` | `varchar(60)` | Unik, dipakai untuk login |
| `password` | `varchar` | Tersimpan hashed (cast `hashed` di model) |
| `status_aktif` | `boolean` | Default `true`; cast boolean di model |
| `remember_token` | `varchar(100)` | Nullable |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Model: `app/Models/User.php` — relasi `role()` = `belongsTo(Role::class)`, accessor `initials`, `role_name`, method `isOwner()`, `isKaryawan()`. Akun demo ada di `database/seeders/UserSeeder.php`.

## 3. Tabel `cages`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `lokasi_blok` | `varchar` | Lokasi/blok kandang |
| `kapasitas` | `smallint unsigned` | Jumlah maksimal kelinci; cast integer di model |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Model: `app/Models/Cage.php` — relasi `rabbits()` = `hasMany(Rabbit::class)`, accessor `kode` (`KDG-` + id 3 digit).

## 4. Tabel `rabbits`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `cage_id` | `bigint unsigned` | Foreign key ke `cages.id`, `restrictOnDelete()` |
| `nama_kelinci` | `varchar` | Nama atau tag kelinci |
| `jenis_kelamin` | `varchar(10)` | `Rabbit::JENIS_KELAMIN` = `Jantan`, `Betina` |
| `tgl_lahir` | `date` | Cast `date` di model |
| `status_kelinci` | `varchar(20)` | Default `Sehat`; `Rabbit::STATUS` = `Sehat`, `Bunting`, `Menyusui`, `Karantina`, `Afkir` |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Model: `app/Models/Rabbit.php` — relasi `cage()` = `belongsTo(Cage::class)`, `growthRecords()` = `hasMany(GrowthRecord::class)`, `breedingRecordsAsMale()` / `breedingRecordsAsFemale()` = `hasMany(BreedingRecord::class, ...)`; accessor `kode`, `initials`, `umur_bulan`, `status_badge`.

## 5. Tabel `growth_records`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `rabbit_id` | `bigint unsigned` | Foreign key ke `rabbits.id`, `cascadeOnDelete()` |
| `tgl_pencatatan` | `date` | Cast `date` di model; index bersama `rabbit_id` |
| `berat_badan` | `decimal(6,2)` | Kilogram; cast `decimal:2` |
| `panjang_badan` | `decimal(5,1)` | Sentimeter; cast `decimal:1` |
| `status_kelinci` | `varchar(20)` | Nilai dari `Rabbit::STATUS` |
| `kondisi_kesehatan` | `varchar` | Kondisi fisik saat pencatatan |
| `keterangan` | `text` | Nullable |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Index: `(['rabbit_id', 'tgl_pencatatan'])`.

Model: `app/Models/GrowthRecord.php` — relasi `rabbit()` = `belongsTo(Rabbit::class)`, accessor `status_badge`.

## 6. Tabel `logistics_categories`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `nama_kategori` | `varchar(80)` | Unik |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Model: `app/Models/LogisticsCategory.php` — relasi `items()` = `hasMany(LogisticsItem::class, 'category_id')`. Kategori awal dari `database/seeders/LogisticsCategorySeeder.php`.

## 7. Tabel `logistics_items`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `category_id` | `bigint unsigned` | Foreign key ke `logistics_categories.id`, `restrictOnDelete()` |
| `nama_item` | `varchar` | Nama item logistik |
| `stok` | `integer unsigned` | Default `0`; cast integer di model |
| `status_aktif` | `boolean` | Default `true`; cast boolean di model |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Model: `app/Models/LogisticsItem.php` — relasi `category()` = `belongsTo(LogisticsCategory::class, 'category_id')`; konstanta `STOK_MENIPIS = 5` dipakai dashboard/laporan untuk menandai stok menipis.

## 8. Tabel `breeding_records`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key, auto increment |
| `male_rabbit_id` | `bigint unsigned` | Foreign key ke `rabbits.id` (pejantan), `cascadeOnDelete()` |
| `female_rabbit_id` | `bigint unsigned` | Foreign key ke `rabbits.id` (indukan), `cascadeOnDelete()` |
| `tgl_rencana_kawin` | `date` | Cast `date` di model; index bersama status |
| `tgl_realisasi_kawin` | `date` | Nullable; cast `date` |
| `status_perkawinan` | `varchar(30)` | Default `Direncanakan`; `BreedingRecord::STATUS` = `Direncanakan`, `Terlaksana (Bunting)`, `Berhasil Melahirkan`, `Gagal Kawin` |
| `jumlah_anakan` | `tinyint unsigned` | Nullable; cast integer |
| `keterangan` | `text` | Nullable |
| `created_at`, `updated_at` | `timestamp` | Nullable |

Index: `(['tgl_rencana_kawin', 'status_perkawinan'])`.

Model: `app/Models/BreedingRecord.php` — relasi `maleRabbit()` = `belongsTo(Rabbit::class, 'male_rabbit_id')`, `femaleRabbit()` = `belongsTo(Rabbit::class, 'female_rabbit_id')`, accessor `status_badge`.

## Ringkasan relasi langsung

| Tabel/model | Relasi | Foreign key | Perilaku hapus |
| --- | --- | --- | --- |
| `Role` → `User` | `hasMany` / `belongsTo` | `users.role_id` | `restrictOnDelete()` |
| `Cage` → `Rabbit` | `hasMany` / `belongsTo` | `rabbits.cage_id` | `restrictOnDelete()` |
| `Rabbit` → `GrowthRecord` | `hasMany` / `belongsTo` | `growth_records.rabbit_id` | `cascadeOnDelete()` |
| `Rabbit` → `BreedingRecord` (pejantan) | `hasMany` / `belongsTo` | `breeding_records.male_rabbit_id` | `cascadeOnDelete()` |
| `Rabbit` → `BreedingRecord` (indukan) | `hasMany` / `belongsTo` | `breeding_records.female_rabbit_id` | `cascadeOnDelete()` |
| `LogisticsCategory` → `LogisticsItem` | `hasMany` / `belongsTo` | `logistics_items.category_id` | `restrictOnDelete()` |

## Catatan penting

- Tidak ada tabel `employees`. Halaman "Akun Karyawan" memakai tabel `users` yang difilter berdasarkan role `Karyawan`.
- Tidak ada kolom `foto`, `email`, `phone`, `position`, `kedaluwarsa`, `porsi`, atau `satuan`. Field tersebut sengaja tidak dicantumkan karena tidak ada di migration.
- Urutan migration mengikuti ketergantungan foreign key: `roles` → `users` → `cages` → `rabbits` → `growth_records` → `logistics_categories` → `logistics_items` → `breeding_records`.
- `php artisan migrate:fresh --seed` akan membuat ulang semua tabel dan mengisi data dari seeder sesuai urutan di `DatabaseSeeder`.
