# RabbitFlow

Sistem Informasi Manajemen Peternakan Kelinci Berbasis Web untuk mata kuliah PWEB. Pengguna sistem adalah Owner dan Karyawan.

## Menjalankan project

```bash
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Pemeriksaan

```bash
php artisan migrate:status
php artisan route:list
php artisan test
```

## Struktur

- `app/Models/` — model Eloquent dan relasi antar tabel.
- `database/migrations/` — delapan migration sesuai ERD.
- `database/seeders/` — seeder role, akun awal, dan kategori logistik.
- `routes/web.php` — daftar route aplikasi.
- `resources/views/` — tampilan Blade.
- `tests/` — pengujian fitur.

Dokumen perencanaan, use case, ERD, dan laporan disimpan terpisah di file Word kelompok.
