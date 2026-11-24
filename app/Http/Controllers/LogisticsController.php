<?php

// DIKERJAKAN OLEH: Abelina | STATUS: KOSONG

namespace App\Http\Controllers;

use App\Http\Requests\LogisticsCategoryRequest;
use App\Http\Requests\LogisticsItemRequest;
use App\Models\LogisticsCategory;
use App\Models\LogisticsItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogisticsController extends Controller
{
    private const CATEGORY_ICONS = [
        'Pakan' => 'grass',
        'Vitamin' => 'medication_liquid',
        'Obat' => 'vaccines',
        'Peralatan' => 'handyman',
    ];

    // TODO: Tampilkan item dengan filter q/category_id/status_aktif, daftar kategori beserta jumlah dan total stok, serta pagination 10.
    public function index(Request $request): View
    {
        return view('logistics.index');
    }

    // TODO: Validasi LogisticsItemRequest lalu buat LogisticsItem.
    public function storeItem(LogisticsItemRequest $request): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Validasi LogisticsItemRequest lalu perbarui LogisticsItem.
    public function updateItem(LogisticsItemRequest $request, LogisticsItem $item): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Set status_aktif item menjadi false.
    public function deactivateItem(LogisticsItem $item): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Validasi LogisticsCategoryRequest lalu buat LogisticsCategory.
    public function storeCategory(LogisticsCategoryRequest $request): RedirectResponse
    {
        abort(501, 'Belum dikerjakan');
    }

    // TODO: Petakan data tervalidasi menjadi category_id, nama_item, stok, dan status_aktif.
    private function validatedItem(LogisticsItemRequest $request): array
    {
        abort(501, 'Belum dikerjakan');
    }
}
