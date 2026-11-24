<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Controllers;

use App\Http\Requests\CageRequest;
use App\Models\Cage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CageController extends Controller
{
    // TODO: Tampilkan daftar kandang dengan filter q/blok, jumlah kelinci per kandang, statistik kapasitas/terisi, dan pagination 10.
    public function index(Request $request): View
    {
        return view('cages.index');
    }

    // TODO: Validasi CageRequest lalu buat Cage dari lokasi_blok dan kapasitas.
    public function store(CageRequest $request): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Validasi data, tolak kapasitas lebih kecil dari jumlah kelinci penghuni, lalu perbarui kandang.
    public function update(CageRequest $request, Cage $cage): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Tolak penghapusan jika kandang masih memiliki kelinci, jika tidak hapus kandang.
    public function destroy(Cage $cage): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Petakan data tervalidasi menjadi lokasi_blok dan kapasitas sebagai integer.
    private function validated(CageRequest $request): array
    {
        abort(501, 'Belum dikerjakan');
    }
}
