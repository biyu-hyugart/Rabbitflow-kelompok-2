<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrowthRequest;
use App\Models\GrowthRecord;
use App\Models\Rabbit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrowthController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'rabbit_id', 'periode', 'kondisi']);

        $records = GrowthRecord::query()
            ->with('rabbit.cage')
            ->when($filters['q'] ?? null, function ($query, string $q) {
                $query->whereHas('rabbit', fn ($sub) => $sub->where('nama_kelinci', 'like', "%{$q}%"));
            })
            ->when($filters['rabbit_id'] ?? null, fn ($query, $rabbit) => $query->where('rabbit_id', $rabbit))
            ->when($filters['periode'] ?? null, function ($query, string $periode) {
                [$year, $month] = array_pad(explode('-', $periode), 2, null);
                if ($year && $month) {
                    $query->whereYear('tgl_pencatatan', (int) $year)->whereMonth('tgl_pencatatan', (int) $month);
                }
            })
            ->when($filters['kondisi'] ?? null, fn ($query, string $kondisi) => $query->where('status_kelinci', $kondisi))
            ->orderByDesc('tgl_pencatatan')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $rabbits = Rabbit::with('cage')->orderBy('nama_kelinci')->get();

        $spotlightRabbit = null;
        if (! empty($filters['rabbit_id'])) {
            $spotlightRabbit = $rabbits->firstWhere('id', (int) $filters['rabbit_id']);
        }
        $spotlightRabbit ??= $records->first()?->rabbit ?? $rabbits->first();

        $spotlight = $spotlightRabbit ? $this->buildSpotlight($spotlightRabbit) : null;

        $periods = GrowthRecord::query()
            ->selectRaw('EXTRACT(YEAR FROM tgl_pencatatan) AS tahun, EXTRACT(MONTH FROM tgl_pencatatan) AS bulan')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(fn ($row) => [
                'value' => sprintf('%04d-%02d', (int) $row->tahun, (int) $row->bulan),
                'label' => Carbon::create((int) $row->tahun, (int) $row->bulan, 1)->translatedFormat('F Y'),
            ]);

        return view('growth.index', compact('records', 'filters', 'rabbits', 'spotlight', 'spotlightRabbit', 'periods'));
    }

    public function store(GrowthRequest $request): RedirectResponse
    {
        GrowthRecord::create($this->validated($request));

        return redirect()->route('growth.index')->with('status', 'Catatan pertumbuhan berhasil disimpan.');
    }

    public function update(GrowthRequest $request, GrowthRecord $growth): RedirectResponse
    {
        $growth->update($this->validated($request));

        return redirect()->route('growth.index')->with('status', 'Catatan pertumbuhan berhasil diperbarui.');
    }

    public function destroy(GrowthRecord $growth): RedirectResponse
    {
        $growth->delete();

        return redirect()->route('growth.index')->with('status', 'Catatan pertumbuhan berhasil dihapus.');
    }

    private function validated(GrowthRequest $request): array
    {
        $data = $request->validated();

        return [
            'rabbit_id' => (int) $data['rabbit_id'],
            'tgl_pencatatan' => $data['tgl_pencatatan'],
            'berat_badan' => $data['berat_badan'],
            'panjang_badan' => $data['panjang_badan'],
            'status_kelinci' => $data['status_kelinci'],
            'kondisi_kesehatan' => $data['kondisi_kesehatan'],
            'keterangan' => $data['keterangan'] ?? null,
        ];
    }

    private function buildSpotlight(Rabbit $rabbit): array
    {
        $history = $rabbit->growthRecords()
            ->orderBy('tgl_pencatatan')
            ->orderBy('id')
            ->get();

        $latest = $history->last();
        $first = $history->first();

        $months = 1;
        if ($first && $latest && $first->tgl_pencatatan && $latest->tgl_pencatatan) {
            $months = max(1, (int) $first->tgl_pencatatan->diffInMonths($latest->tgl_pencatatan));
        }

        $gain = ($first && $latest) ? ((float) $latest->berat_badan - (float) $first->berat_badan) : 0;

        $milestones = collect();
        if ($history->isNotEmpty()) {
            $indexes = array_unique([0, (int) floor(($history->count() - 1) / 3), (int) floor(2 * ($history->count() - 1) / 3), $history->count() - 1]);
            $milestones = collect($indexes)->sort()->map(fn ($i) => $history[$i])->values();
        }

        return [
            'rabbit' => $rabbit,
            'history' => $history,
            'latest' => $latest,
            'first' => $first,
            'total' => $history->count(),
            'average_gain' => $history->count() > 1 ? $gain / $months : 0,
            'milestones' => $milestones,
        ];
    }
}
