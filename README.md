# RabbitFlow

Sistem Informasi Manajemen Peternakan Kelinci berbasis web untuk mata kuliah PWEB. Pengguna sistem dibagi menjadi dua peran: **Owner** dan **Karyawan**.

## Menjalankan project

### 1. Instal dependensi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Siapkan database PostgreSQL

Pastikan PostgreSQL sudah berjalan, lalu buat database `rabbitflow` (sesuaikan nama, user, dan password di file `.env`):

```sql
CREATE DATABASE rabbitflow;
```

Contoh isi `.env` untuk database lokal:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rabbitflow
DB_USERNAME=postgres
DB_PASSWORD=
```

Isi `DB_PASSWORD` dengan password PostgreSQL lokal Anda. Jangan commit file `.env`.

### 3. Isi tabel dan jalankan aplikasi

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk mengulang database dari awal beserta data contoh:

```bash
php artisan migrate:fresh --seed
```

## Akun demo

Data akun berasal dari `database/seeders/UserSeeder.php`.

| Username | Password | Role | Status |
| --- | --- | --- | --- |
| `hendra_owner` | `rahasia123` | Owner | Aktif |
| `budi_kandang` | `karyawan123` | Karyawan | Aktif |
| `siti_logistik` | `karyawan123` | Karyawan | Aktif |
| `dedi_aris` | `karyawan123` | Karyawan | Nonaktif (tidak bisa login) |

Akun nonaktif sengaja disediakan untuk menguji penolakan login.

## Fitur aplikasi

- **Autentikasi**: login, logout, penolakan akun nonaktif, pembatasan akses berdasarkan role.
- **Dashboard**: ringkasan populasi, okupansi kandang, jadwal perkawinan, stok logistik, tren pertumbuhan, mutasi kelinci terakhir, rencana kawin, dan status stok kritis.
- **Data kelinci**: tambah/ubah/hapus, ubah status, filter nama, gender, kandang, dan status; penolakan kandang penuh.
- **Data kandang**: tambah/ubah/hapus (Owner), pantau okupansi, penolakan hapus kandang yang masih berisi kelinci.
- **Pertumbuhan**: catat, ubah, hapus berat/panjang dan kondisi kelinci; filter periode dan status; sorotan riwayat pertumbuhan.
- **Perkawinan**: jadwal & riwayat perkawinan; Owner mengelola jadwal, Karyawan hanya melihat; kalender rencana kawin pekan ini.
- **Logistik**: kelola item dan kategori (Karyawan), pantau stok dan status aktif; Owner hanya melihat; penonaktifan item memakai konfirmasi.
- **Akun karyawan**: tambah/ubah, nonaktifkan/aktifkan kembali akun (Owner); akun Owner tidak bisa diubah dari halaman ini.
- **Laporan**: empat jenis laporan (Owner) dan tiga laporan operasional (Karyawan), filter periode/kategori, ringkasan angka, dan cetak A4 lewat `window.print()`.

## Pembagian modul

Nama anggota sengaja dikosongkan dengan placeholder. Isi sesuai pembagian kelompok Anda.

| Modul | Route utama | File controller | File view | Nama anggota |
| --- | --- | --- | --- | --- |
| Autentikasi & Dashboard | `/login`, `/dashboard` | `Auth/LoginController.php`, `DashboardController.php` | `auth/`, `dashboard/` | `(isi nama anggota)` |
| Kelinci | `/data-kelinci` | `RabbitController.php` | `rabbits/` | `(isi nama anggota)` |
| Kandang | `/data-kandang` | `CageController.php` | `cages/` | `(isi nama anggota)` |
| Pertumbuhan | `/pertumbuhan` | `GrowthController.php` | `growth/` | `(isi nama anggota)` |
| Perkawinan | `/perkawinan` | `BreedingController.php` | `breeding/` | `(isi nama anggota)` |
| Logistik | `/logistik` | `LogisticsController.php` | `logistics/` | `(isi nama anggota)` |
| Akun Karyawan | `/akun-karyawan` | `EmployeeController.php` | `employees/` | `(isi nama anggota)` |
| Laporan | `/laporan` | `ReportController.php` | `reports/` | `(isi nama anggota)` |

## Pemeriksaan

```bash
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
```

## Struktur singkat

- `app/Http/Requests/` — aturan validasi per modul (pesan bahasa Indonesia).
- `app/Http/Middleware/` — middleware `role` dan `active`.
- `app/Models/` — model Eloquent dan relasi antar tabel.
- `database/migrations/` — delapan migration sesuai ERD.
- `database/seeders/` — seeder role, akun awal, kategori logistik, dan data contoh.
- `routes/web.php` — daftar route yang dikelompokkan per fitur.
- `resources/views/` — tampilan Blade dengan partial per modul.
- `tests/Feature/` — pengujian alur aplikasi.
- `docs/PETA_KODE.md` — peta alur route → controller → request → model → view.
- `docs/STRUKTUR_DATABASE.md` — tabel, kolom, tipe data, dan relasi dari migration/model.

Dokumen perencanaan, use case, ERD, dan laporan akhir disimpan terpisah di file Word kelompok.
