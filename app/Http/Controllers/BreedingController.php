<?php

namespace App\Http\Controllers;

use App\Http\Requests\BreedingRequest;
use App\Models\BreedingRecord;
use App\Models\Rabbit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreedingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status_perkawinan', 'periode']);

        $records = BreedingRecord::query()
            ->with(['maleRabbit', 'femaleRabbit'])
            ->when($filters['q'] ?? null, function ($query, string $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('maleRabbit', fn ($r) => $r->where('nama_kelinci', 'like', "%{$q}%"))
                        ->orWhereHas('femaleRabbit', fn ($r) => $r->where('nama_kelinci', 'like', "%{$q}%"));
                });
            })
            ->when($filters['status_perkawinan'] ?? null, fn ($query, string $status) => $query->where('status_perkawinan', $status))
            ->when($filters['periode'] ?? null, function ($query, string $periode) {
                [$year, $month] = array_pad(explode('-', $periode), 2, null);
                if ($year && $month) {
                    $query->whereYear('tgl_rencana_kawin', (int) $year)->whereMonth('tgl_rencana_kawin', (int) $month);
                }
            })
            ->orderByDesc('tgl_rencana_kawin')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $weekRecords = BreedingRecord::query()
            ->with(['maleRabbit', 'femaleRabbit'])
            ->whereBetween('tgl_rencana_kawin', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('tgl_rencana_kawin')
            ->get()
            ->groupBy(fn (BreedingRecord $record) => $record->tgl_rencana_kawin->toDateString());

        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($weekStart, $weekRecords) {
            $date = $weekStart->copy()->addDays($offset);

            return [
                'date' => $date,
                'records' => $weekRecords->get($date->toDateString(), collect()),
            ];
        });

        $rabbits = Rabbit::orderBy('nama_kelinci')->get();
        $maleRabbits = $rabbits->where('jenis_kelamin', 'Jantan')->values();
        $femaleRabbits = $rabbits->where('jenis_kelamin', 'Betina')->values();

        $periods = BreedingRecord::query()
            ->selectRaw('EXTRACT(YEAR FROM tgl_rencana_kawin) AS tahun, EXTRACT(MONTH FROM tgl_rencana_kawin) AS bulan')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(fn ($row) => [
                'value' => sprintf('%04d-%02d', (int) $row->tahun, (int) $row->bulan),
                'label' => Carbon::create((int) $row->tahun, (int) $row->bulan, 1)->translatedFormat('F Y'),
            ]);

        return view('breeding.index', compact('records', 'filters', 'weekDays', 'weekStart', 'weekEnd', 'rabbits', 'maleRabbits', 'femaleRabbits', 'periods'));
    }

    public function store(BreedingRequest $request): RedirectResponse
    {
        BreedingRecord::create($this->validated($request));

        return redirect()->route('breeding.index')->with('status', 'Jadwal perkawinan berhasil disimpan.');
    }

    public function update(BreedingRequest $request, BreedingRecord $breeding): RedirectResponse
    {
        $breeding->update($this->validated($request));

        return redirect()->route('breeding.index')->with('status', 'Data perkawinan berhasil diperbarui.');
    }

    public function destroy(BreedingRecord $breeding): RedirectResponse
    {
        $breeding->delete();

        return redirect()->route('breeding.index')->with('status', 'Jadwal perkawinan berhasil dihapus.');
    }

    private function validated(BreedingRequest $request): array
    {
        $data = $request->validated();

        return [
            'male_rabbit_id' => (int) $data['male_rabbit_id'],
            'female_rabbit_id' => (int) $data['female_rabbit_id'],
            'tgl_rencana_kawin' => $data['tgl_rencana_kawin'],
            'tgl_realisasi_kawin' => $data['tgl_realisasi_kawin'] ?? null,
            'status_perkawinan' => $data['status_perkawinan'],
            'jumlah_anakan' => $data['jumlah_anakan'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
        ];
    }
}
