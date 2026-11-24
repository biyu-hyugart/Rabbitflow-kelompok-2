<?php

// DIKERJAKAN OLEH: Lena | STATUS: KOSONG

namespace App\Http\Controllers;

use App\Http\Requests\RabbitRequest;
use App\Http\Requests\RabbitStatusRequest;
use App\Models\Cage;
use App\Models\Rabbit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RabbitController extends Controller
{
    // TODO: Tampilkan daftar kelinci dengan filter q/jenis_kelamin/cage_id/status_kelinci, statistik, daftar kandang, dan pagination 10.
    public function index(Request $request): View
    {
        return view('rabbits.index');
    }

    // TODO: Validasi data, cek kapasitas kandang, lalu buat Rabbit.
    public function store(RabbitRequest $request): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Validasi data, cek kapasitas jika kandang berubah, lalu perbarui Rabbit.
    public function update(RabbitRequest $request, Rabbit $rabbit): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Validasi RabbitStatusRequest lalu perbarui status_kelinci.
    public function updateStatus(RabbitStatusRequest $request, Rabbit $rabbit): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Hapus Rabbit dan kembalikan ke halaman data kelinci.
    public function destroy(Rabbit $rabbit): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Petakan data tervalidasi menjadi field nama_kelinci, jenis_kelamin, tgl_lahir, cage_id, dan status_kelinci.
    private function validated(RabbitRequest $request): array
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Cek keberadaan kandang dan bandingkan rabbits_count dengan kapasitas.
    private function cageHasRoom(int $cageId): bool
    {
        abort(501, 'Belum dikerjakan');
    }
}
