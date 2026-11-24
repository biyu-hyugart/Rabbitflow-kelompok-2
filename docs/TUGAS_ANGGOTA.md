# Tugas Anggota — Modul Dikosongkan

Dokumen ini merangkum file yang perlu diisi kembali dan ringkasan tugas tiap method **berdasarkan kode asli** sebelum modul dikosongkan. Salinan kode asli ada di `../backup_modul/` dengan struktur path yang sama.

Penanda di setiap file: `DIKERJAKAN OLEH: [nama] | STATUS: KOSONG`.

---

## Lena

### File yang harus diisi

**Controller**
- `app/Http/Controllers/EmployeeController.php`
- `app/Http/Controllers/CageController.php`
- `app/Http/Controllers/RabbitController.php`

**Form Request**
- `app/Http/Requests/EmployeeRequest.php`
- `app/Http/Requests/CageRequest.php`
- `app/Http/Requests/RabbitRequest.php`
- `app/Http/Requests/RabbitStatusRequest.php`

**View**
- `resources/views/employees/index.blade.php`
- `resources/views/employees/partials/form-modal.blade.php`
- `resources/views/employees/partials/modals.blade.php`
- `resources/views/employees/partials/scripts.blade.php`
- `resources/views/cages/index.blade.php`
- `resources/views/cages/partials/form-modal.blade.php`
- `resources/views/cages/partials/scripts.blade.php`
- `resources/views/rabbits/index.blade.php`
- `resources/views/rabbits/partials/form-modal.blade.php`
- `resources/views/rabbits/partials/status-modal.blade.php`
- `resources/views/rabbits/partials/scripts.blade.php`

### Ringkasan method controller

**EmployeeController**
- `index(Request $request): View` — memfilter karyawan berdasarkan `q`, `status`, `role`; menghitung statistik `total`, `aktif`, `nonaktif`; mengirim `employees`, `filters`, `stats` ke `employees.index` dengan pagination 10.
- `store(EmployeeRequest $request): RedirectResponse` — mengambil data tervalidasi, membuat `User` dengan `role_id` Karyawan, `nama_lengkap`, `username`, `password`, `status_aktif`.
- `update(EmployeeRequest $request, User $employee): RedirectResponse` — menolak akun Owner, memperbarui `nama_lengkap`, `username`, `status_aktif`; `password` hanya diperbarui jika diisi.
- `deactivate(Request $request, User $employee): RedirectResponse` — menolak akun Owner dan akun sendiri, lalu menetapkan `status_aktif = false`.
- `activate(User $employee): RedirectResponse` — menolak akun Owner, lalu menetapkan `status_aktif = true`.

**CageController**
- `index(Request $request): View` — memfilter kandang berdasarkan `q` dan `blok`, menghitung `rabbits_count`, statistik `total`, `kapasitas`, `terisi`, lalu merender `cages.index`.
- `store(CageRequest $request): RedirectResponse` — membuat `Cage` dari data tervalidasi `lokasi_blok` dan `kapasitas`.
- `update(CageRequest $request, Cage $cage): RedirectResponse` — menolak kapasitas lebih kecil dari jumlah kelinci penghuni, lalu memperbarui kandang.
- `destroy(Cage $cage): RedirectResponse` — menolak penghapusan jika masih ada kelinci, jika tidak menghapus kandang.
- `validated(CageRequest $request): array` — memetakan `lokasi_blok` dan `kapasitas` (integer) dari data tervalidasi.

**RabbitController**
- `index(Request $request): View` — memfilter `q`, `jenis_kelamin`, `cage_id`, `status_kelinci`; menghitung statistik total/jantan/betina/karantina; mengirim daftar kandang ke `rabbits.index`.
- `store(RabbitRequest $request): RedirectResponse` — memvalidasi data, memeriksa kapasitas kandang, lalu membuat `Rabbit`.
- `update(RabbitRequest $request, Rabbit $rabbit): RedirectResponse` — memvalidasi data, memeriksa kapasitas jika kandang berubah, lalu memperbarui `Rabbit`.
- `updateStatus(RabbitStatusRequest $request, Rabbit $rabbit): RedirectResponse` — memperbarui `status_kelinci` dari data tervalidasi.
- `destroy(Rabbit $rabbit): RedirectResponse` — menghapus `Rabbit`.
- `validated(RabbitRequest $request): array` — memetakan `nama_kelinci`, `jenis_kelamin`, `tgl_lahir`, `cage_id`, `status_kelinci`.
- `cageHasRoom(int $cageId): bool` — memastikan kandang ada dan `rabbits_count < kapasitas`.

### Aturan validasi yang harus dikembalikan

- `EmployeeRequest`: `nama_lengkap` required|string|max:100; `username` required|string|max:60|alpha_dash|lowercase|unique (diabaikan saat update); `password` required saat create / nullable saat update|string|min:8|max:100; `status_aktif` required|boolean. Pesan bahasa Indonesia sesuai file backup.
- `CageRequest`: `lokasi_blok` required|string|max:120; `kapasitas` required|integer|min:1|max:100.
- `RabbitRequest`: `nama_kelinci` required|string|max:100; `jenis_kelamin` required|in `Rabbit::JENIS_KELAMIN`; `tgl_lahir` required|date|before_or_equal:today; `cage_id` required|integer|exists:cages,id; `status_kelinci` required|in `Rabbit::STATUS`.
- `RabbitStatusRequest`: `status_kelinci` required|in `Rabbit::STATUS`.

### Elemen view yang harus dibuat

- `index`: tabel data, filter, form modal tambah/ubah, konfirmasi hapus/nonaktif.
- `employees/partials/form-modal`: form karyawan; `modals`: konfirmasi nonaktif/aktif; `scripts`: pembuka modal, toggle password, auto-submit filter.
- `cages/partials/form-modal`: form kandang dengan input `_action`/`_method`; `scripts`: pembuka modal, filter, konfirmasi hapus.
- `rabbits/partials/form-modal`: form kelinci; `status-modal`: ubah status; `scripts`: pembuka modal, filter, konfirmasi hapus.

---

## Abelina

### File yang harus diisi

**Controller**
- `app/Http/Controllers/LogisticsController.php`
- `app/Http/Controllers/ReportController.php`

**Form Request**
- `app/Http/Requests/LogisticsItemRequest.php`
- `app/Http/Requests/LogisticsCategoryRequest.php`

**View**
- `resources/views/logistics/index.blade.php`
- `resources/views/logistics/partials/form-modal.blade.php`
- `resources/views/logistics/partials/modals.blade.php`
- `resources/views/logistics/partials/scripts.blade.php`
- `resources/views/reports/index.blade.php`

### Ringkasan method controller

**LogisticsController**
- `index(Request $request): View` — memfilter item berdasarkan `q`, `category_id`, `status_aktif`; mengambil kategori dengan `withCount('items')` dan `withSum('items', 'stok')`; merender `logistics.index`.
- `storeItem(LogisticsItemRequest $request): RedirectResponse` — membuat `LogisticsItem` dari data tervalidasi.
- `updateItem(LogisticsItemRequest $request, LogisticsItem $item): RedirectResponse` — memperbarui `LogisticsItem`.
- `deactivateItem(LogisticsItem $item): RedirectResponse` — menetapkan `status_aktif = false`.
- `storeCategory(LogisticsCategoryRequest $request): RedirectResponse` — membuat `LogisticsCategory`.
- `validatedItem(LogisticsItemRequest $request): array` — memetakan `category_id`, `nama_item`, `stok`, `status_aktif`.

**ReportController**
- `index(Request $request): View` — menentukan daftar jenis laporan sesuai role Owner/Karyawan, memilih jenis, mem-parsing `dari`/`sampai`, mengambil baris data, opsi filter, judul, nomor dokumen, dan ringkasan; merender `reports.index`.
- `parseDate(mixed $value, Carbon $fallback): Carbon` — mengurai tanggal query dengan fallback jika kosong/invalid.
- `rabbitRows(Carbon $start, Carbon $end, mixed $filter)` — query `Rabbit` pada rentang tanggal dengan filter `cage_id`.
- `growthRows(Carbon $start, Carbon $end, mixed $filter)` — query `GrowthRecord` pada rentang `tgl_pencatatan` dengan filter `status_kelinci`.
- `breedingRows(Carbon $start, Carbon $end, mixed $filter)` — query `BreedingRecord` pada rentang `tgl_rencana_kawin` dengan filter `status_perkawinan`.
- `logisticsRows(Carbon $start, Carbon $end, mixed $filter)` — query `LogisticsItem` pada rentang tanggal dengan filter `category_id`.
- `summary(string $jenis, $rows): array` — memilih perhitungan ringkasan sesuai jenis laporan.
- `rabbitSummary($rows, int $total): array` — total kelinci, pejantan, indukan, karantina.
- `growthSummary($rows, int $total): array` — jumlah pencatatan, rata-rata berat, kelinci terpantau, kenaikan berat.
- `breedingSummary($rows, int $total): array` — total jadwal, masih rencana, terlaksana, total anakan.
- `logisticsSummary($rows, int $total): array` — total item, total stok, stok habis, item nonaktif.
- `summaryItem(string $label, string $value, string $hint): array` — membentuk satu item ringkasan.
- `formatNumber(float $value, int $decimals): string` — format angka dengan pemisah ribuan titik dan desimal koma.
- `romanMonth(int $month): string` — konversi bulan ke angka Romawi.

### Aturan validasi yang harus dikembalikan

- `LogisticsItemRequest`: `category_id` required|integer|exists:logistics_categories,id; `nama_item` required|string|max:150; `stok` required|integer|min:0|max:1000000; `status_aktif` required|in `0`, `1`, `true`, `false`.
- `LogisticsCategoryRequest`: `nama_kategori` required|string|max:80|unique:logistics_categories,nama_kategori.

### Elemen view yang harus dibuat

- `logistics/index`: tabel item, filter q/kategori/status, form modal item dan kategori, konfirmasi nonaktif.
- `logistics/partials/form-modal`: form item dan kategori; `modals`: konfirmasi nonaktif; `scripts`: pembuka modal, filter, konfirmasi nonaktif.
- `reports/index`: filter jenis/dari/sampai/kategori, tabel baris laporan, ringkasan angka, dan tombol cetak via `window.print()`.
