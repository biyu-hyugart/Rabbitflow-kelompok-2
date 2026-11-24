# Peta Kode RabbitFlow

Dokumen ini menunjukkan alur singkat setiap fitur dari alamat (route) sampai tampilan (view).
Tujuannya memudahkan mencari file saat ada perubahan. Nama route, method HTTP, dan alamat URL tidak diubah.

## Alur umum (semua modul)

```
Route (routes/web.php)
  -> Middleware auth / active / role (bootstrap/app.php + app/Http/Middleware/)
  -> Controller (app/Http/Controllers/<Modul>Controller.php)
  -> Form Request (app/Http/Requests/<Nama>Request.php) -- validasi
  -> Model (app/Models/<Model>.php) -- relasi & aturan bisnis
  -> Migration (database/migrations/) -- struktur tabel
  -> View (resources/views/<modul>/) -- tampilan + partial
```

Keterangan:

- `auth` membuat pengguna yang belum login diarahkan ke `/login`.
- `active` menolak akun yang sudah dinonaktifkan.
- `role:Owner` hanya boleh diakses Owner; `role:Karyawan` hanya boleh diakses Karyawan.
- Validasi dipindahkan ke Form Request, pesan error tetap bahasa Indonesia.
- `web.php` dikelompokkan per fitur dengan komentar singkat; isi (nama, URL, method) tidak diubah.

## Autentikasi

| Aksi | Method + URL | Nama route | Controller | View |
| --- | --- | --- | --- | --- |
| Form login | `GET /login` | `login` | `Auth\LoginController@show` | `auth/login.blade.php` |
| Proses login | `POST /login` | `login.store` | `Auth\LoginController@store` | - |
| Logout | `POST /logout` | `logout` | `Auth\LoginController@destroy` | - |

Middleware: `guest` untuk halaman login, `auth` untuk logout.

## Dashboard

| Aksi | Method + URL | Nama route | Controller | View |
| --- | --- | --- | --- | --- |
| Dashboard (Owner dan Karyawan) | `GET /dashboard` | `dashboard` | `DashboardController@owner` | `dashboard/owner.blade.php` |
| Beranda | `GET /` | - | `Route::redirect('/', '/dashboard')` | - |

`DashboardController` memakai method private bernama jelas (`kpis()`, `populasiKpi()`, `okupansiKpi()`, `perkawinanKpi()`, `logistikKpi()`, `growthGroups()`, `recentRabbits()`, `breedingPlans()`, `criticalStock()`) sehingga query besar tidak menumpuk di satu method.

## Modul Rabbit / Kelinci

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter | `GET /data-kelinci` | `rabbits.index` | - | `RabbitController@index` | `Rabbit` | `rabbits/index.blade.php` |
| Simpan baru | `POST /data-kelinci` | `rabbits.store` | `RabbitRequest` | `RabbitController@store` | `Rabbit` | `rabbits/partials/form-modal.blade.php` |
| Simpan ubah | `PUT /data-kelinci/{rabbit}` | `rabbits.update` | `RabbitRequest` | `RabbitController@update` | `Rabbit` | `rabbits/partials/form-modal.blade.php` |
| Ubah status | `PATCH /data-kelinci/{rabbit}/status` | `rabbits.status` | `RabbitStatusRequest` | `RabbitController@updateStatus` | `Rabbit` | `rabbits/partials/status-modal.blade.php` |
| Hapus | `DELETE /data-kelinci/{rabbit}` | `rabbits.destroy` | - | `RabbitController@destroy` | `Rabbit` | `partials/delete-modal.blade.php` |

- Migration: `database/migrations/2026_11_17_000004_create_rabbits_table.php`.
- Aturan penting: kandang penuh ditolak; status diambil dari `Rabbit::STATUS`.

## Modul Cage / Kandang (Owner)

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter | `GET /data-kandang` | `cages.index` | - | `CageController@index` | `Cage` | `cages/index.blade.php` |
| Simpan baru | `POST /data-kandang` | `cages.store` | `CageRequest` | `CageController@store` | `Cage` | `cages/partials/form-modal.blade.php` |
| Simpan ubah | `PUT /data-kandang/{cage}` | `cages.update` | `CageRequest` | `CageController@update` | `Cage` | `cages/partials/form-modal.blade.php` |
| Hapus | `DELETE /data-kandang/{cage}` | `cages.destroy` | - | `CageController@destroy` | `Cage` | `partials/delete-modal.blade.php` |

- Migration: `database/migrations/2026_11_17_000003_create_cages_table.php`.
- Relasi: `Cage` memiliki banyak `Rabbit`. Kandang berisi kelinci tidak bisa dihapus; kapasitas tidak boleh lebih kecil dari jumlah kelinci di dalamnya.

## Modul Growth / Pertumbuhan

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter | `GET /pertumbuhan` | `growth.index` | - | `GrowthController@index` | `GrowthRecord` | `growth/index.blade.php` |
| Simpan baru | `POST /pertumbuhan` | `growth.store` | `GrowthRequest` | `GrowthController@store` | `GrowthRecord` | `growth/partials/form-modal.blade.php` |
| Simpan ubah | `PUT /pertumbuhan/{growth}` | `growth.update` | `GrowthRequest` | `GrowthController@update` | `GrowthRecord` | `growth/partials/form-modal.blade.php` |
| Hapus | `DELETE /pertumbuhan/{growth}` | `growth.destroy` | - | `GrowthController@destroy` | `GrowthRecord` | `partials/delete-modal.blade.php` |

- Migration: `database/migrations/2026_11_17_000005_create_growth_records_table.php`.
- Relasi: `GrowthRecord` dimiliki `Rabbit`. Halaman juga menampilkan sorotan (`buildSpotlight()`) dari data yang sama.

## Modul Breeding / Perkawinan

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter (semua role) | `GET /perkawinan` | `breeding.index` | - | `BreedingController@index` | `BreedingRecord` | `breeding/index.blade.php` |
| Simpan baru (Owner) | `POST /perkawinan` | `breeding.store` | `BreedingRequest` | `BreedingController@store` | `BreedingRecord` | `breeding/partials/form-modal.blade.php` |
| Simpan ubah (Owner) | `PUT /perkawinan/{breeding}` | `breeding.update` | `BreedingRequest` | `BreedingController@update` | `BreedingRecord` | `breeding/partials/form-modal.blade.php` |
| Hapus (Owner) | `DELETE /perkawinan/{breeding}` | `breeding.destroy` | - | `BreedingController@destroy` | `BreedingRecord` | `partials/delete-modal.blade.php` |

- Migration: `database/migrations/2026_11_17_000008_create_breeding_records_table.php`.
- Relasi: `BreedingRecord` dimiliki `Rabbit` pejantan (`maleRabbit`) dan `Rabbit` indukan (`femaleRabbit`).
- Catatan: `GET /perkawinan` sengaja di luar grup `role:Owner` agar Karyawan bisa melihat. Route POST/PUT/DELETE tetap Owner saja. Ini tidak diubah.

## Modul Logistics / Logistik

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter (semua role) | `GET /logistik` | `logistics.index` | - | `LogisticsController@index` | `LogisticsItem` | `logistics/index.blade.php` |
| Simpan item (Karyawan) | `POST /logistik/item` | `logistics.items.store` | `LogisticsItemRequest` | `LogisticsController@storeItem` | `LogisticsItem` | `logistics/partials/form-modal.blade.php` |
| Ubah item (Karyawan) | `PUT /logistik/item/{item}` | `logistics.items.update` | `LogisticsItemRequest` | `LogisticsController@updateItem` | `LogisticsItem` | `logistics/partials/form-modal.blade.php` |
| Nonaktifkan item (Karyawan) | `PATCH /logistik/item/{item}/nonaktifkan` | `logistics.items.deactivate` | - | `LogisticsController@deactivateItem` | `LogisticsItem` | `logistics/partials/modals.blade.php` |
| Tambah kategori (Karyawan) | `POST /logistik/kategori` | `logistics.categories.store` | `LogisticsCategoryRequest` | `LogisticsController@storeCategory` | `LogisticsCategory` | `logistics/partials/modals.blade.php` |

- Migration: `database/migrations/2026_11_17_000006_create_logistics_categories_table.php` dan `..._000007_create_logistics_items_table.php`.
- Relasi: `LogisticsItem` dimiliki `LogisticsCategory`. Ambang stok menipis ada di `LogisticsItem::STOK_MENIPIS`.
- Tidak ada route hapus logistik; yang ada hanya nonaktifkan. Ini sengaja dipertahankan.

## Modul Employee / Akun Karyawan (Owner)

Halaman ini mengelola akun dengan role `Karyawan` lewat model `User`. Tidak ada model atau tabel `employees` terpisah.

| Aksi | Method + URL | Nama route | Request | Controller | Model | View |
| --- | --- | --- | --- | --- | --- | --- |
| Lihat daftar + filter | `GET /akun-karyawan` | `employees.index` | - | `EmployeeController@index` | `User` | `employees/index.blade.php` |
| Simpan baru | `POST /akun-karyawan` | `employees.store` | `EmployeeRequest` | `EmployeeController@store` | `User` | `employees/partials/form-modal.blade.php` |
| Simpan ubah | `PUT /akun-karyawan/{employee}` | `employees.update` | `EmployeeRequest` | `EmployeeController@update` | `User` | `employees/partials/form-modal.blade.php` |
| Nonaktifkan | `PATCH /akun-karyawan/{employee}/nonaktifkan` | `employees.deactivate` | - | `EmployeeController@deactivate` | `User` | `employees/partials/modals.blade.php` |
| Aktifkan lagi | `PATCH /akun-karyawan/{employee}/aktifkan` | `employees.activate` | - | `EmployeeController@activate` | `User` | `employees/index.blade.php` |

- Migration: `database/migrations/2026_11_17_000001_create_roles_table.php` dan `..._000002_create_users_table.php`.
- Akun Owner tidak boleh diubah/dinonaktifkan dari halaman ini (dicegah controller).
- Tidak ada route hapus akun; yang ada hanya nonaktifkan/aktifkan. Ini sengaja dipertahankan.

## Laporan

| Aksi | Method + URL | Nama route | Controller | View |
| --- | --- | --- | --- | --- |
| Lihat & cetak laporan | `GET /laporan` | `reports.index` | `ReportController@index` | `reports/index.blade.php` |

- Filter: `jenis`, `dari`, `sampai`, `filter` (kandang/status/kategori tergantung jenis).
- Ringkasan angka dihitung `ReportController@summary()` dari baris hasil filter yang sama.
- Tombol "Cetak Dokumen Laporan" memanggil `window.print()`. CSS cetak ada di `resources/css/rabbitflow-pages.css` (`@media print`).
- Owner melihat empat jenis laporan; Karyawan hanya tiga jenis operasional.

## File pendukung lintas modul

- `resources/views/layouts/app.blade.php`: layout utama (header, sidebar, footer, notifikasi).
- `resources/views/partials/delete-modal.blade.php`: konfirmasi hapus (Rabbit, Cage, Growth, Breeding).
- `resources/views/<modul>/partials/`: form tambah/ubah, modal konfirmasi, dan skrip modal per modul.
- `resources/css/rabbitflow.css` dan `resources/css/rabbitflow-pages.css`: gaya visual + responsif + cetak.
- `tests/Feature/`: pengujian alur di atas.
- `docs/STRUKTUR_DATABASE.md`: daftar tabel, kolom, dan relasi dari migration/model.

## Cara mencari cepat

- Cari nama route di `routes/web.php` untuk tahu URL, method, dan middleware.
- Cari nama class Request di `app/Http/Requests/` untuk tahu aturan validasi.
- Cari method controller untuk tahu alur simpan/ubah/hapus.
- Cari nama tabel di `docs/STRUKTUR_DATABASE.md` untuk tahu kolom dan relasi.
- Cari nama file view di `resources/views/` untuk mengubah tampilan.
