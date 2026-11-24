<?php

namespace App\Http\Controllers;

use App\Models\BreedingRecord;
use App\Models\Cage;
use App\Models\LogisticsItem;
use App\Models\Rabbit;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function owner(): View
    {
        return view('dashboard.owner', [
            'currentMonth' => now()->translatedFormat('F Y'),
            'currentDate' => now()->translatedFormat('d M Y'),
            'kpis' => $this->kpis(),
            'growthGroups' => $this->growthGroups(),
            'recentRabbits' => $this->recentRabbits(),
            'breedingPlans' => $this->breedingPlans(),
            'criticalStock' => $this->criticalStock(),
        ]);
    }

    private function kpis(): array
    {
        return [
            $this->populasiKpi(),
            $this->okupansiKpi(),
            $this->perkawinanKpi(),
            $this->logistikKpi(),
        ];
    }

    private function populasiKpi(): array
    {
        $total = Rabbit::count();
        $jantan = Rabbit::where('jenis_kelamin', 'Jantan')->count();
        $betina = Rabbit::where('jenis_kelamin', 'Betina')->count();
        $baru = Rabbit::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        $persenJantan = $total > 0 ? (int) round($jantan / $total * 100) : 0;

        return [
            'label' => 'Total Populasi',
            'icon' => 'pets',
            'icon_class' => '',
            'value' => (string) $total,
            'delta' => '+' . $baru . ' bulan ini',
            'delta_class' => 'rf-kpi-delta--success',
            'delta_icon' => $baru > 0 ? 'trending_up' : null,
            'segments' => [
                ['class' => 'is-forest', 'width' => $persenJantan, 'title' => $jantan . ' Jantan'],
                ['class' => 'is-chartreuse', 'width' => 100 - $persenJantan, 'title' => $betina . ' Betina'],
            ],
            'foot_left' => $jantan . ' Jantan (' . $persenJantan . '%)',
            'foot_left_class' => '',
            'foot_right' => $betina . ' Betina (' . (100 - $persenJantan) . '%)',
            'foot_right_class' => '',
        ];
    }

    private function okupansiKpi(): array
    {
        $totalKandang = Cage::count();
        $kapasitas = (int) Cage::sum('kapasitas');
        $terisi = Rabbit::count();
        $tersisa = max($kapasitas - $terisi, 0);
        $persen = $kapasitas > 0 ? (int) round($terisi / $kapasitas * 100) : 0;

        return [
            'label' => 'Kandang & Okupansi',
            'icon' => 'grid_view',
            'icon_class' => '',
            'value' => (string) $totalKandang,
            'delta' => '/ ' . $kapasitas . ' Ekor Maks',
            'delta_class' => '',
            'delta_icon' => null,
            'segments' => [
                ['class' => 'is-forest', 'width' => $persen, 'title' => 'Terisi ' . $persen . '%'],
            ],
            'foot_left' => 'Terisi ' . $persen . '%',
            'foot_left_class' => '',
            'foot_right' => 'Tersisa ' . $tersisa . ' Slot',
            'foot_right_class' => 'rf-text-forest is-semibold',
        ];
    }

    private function perkawinanKpi(): array
    {
        $total = BreedingRecord::count();
        $pekanIni = BreedingRecord::whereBetween('tgl_rencana_kawin', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $terlaksana = BreedingRecord::where('status_perkawinan', '!=', 'Direncanakan')->count();
        $persen = $total > 0 ? (int) round($terlaksana / $total * 100) : 0;

        return [
            'label' => 'Jadwal Perkawinan',
            'icon' => 'favorite',
            'icon_class' => 'rf-kpi-icon--warning',
            'value' => (string) $total,
            'delta' => $pekanIni . ' pekan ini',
            'delta_class' => 'rf-kpi-delta--warning',
            'delta_icon' => null,
            'segments' => [
                ['class' => 'is-warning', 'width' => 100 - $persen, 'title' => ($total - $terlaksana) . ' Rencana'],
                ['class' => 'is-forest', 'width' => $persen, 'title' => $terlaksana . ' Terlaksana'],
            ],
            'foot_left' => ($total - $terlaksana) . ' Rencana',
            'foot_left_class' => '',
            'foot_right' => $terlaksana . ' Terlaksana',
            'foot_right_class' => 'rf-text-forest is-semibold',
        ];
    }

    private function logistikKpi(): array
    {
        $total = LogisticsItem::where('status_aktif', true)->count();
        $aman = LogisticsItem::where('status_aktif', true)->where('stok', '>', LogisticsItem::STOK_MENIPIS)->count();
        $menipis = max($total - $aman, 0);
        $persen = $total > 0 ? (int) round($aman / $total * 100) : 0;

        return [
            'label' => 'Item Logistik',
            'icon' => 'inventory_2',
            'icon_class' => '',
            'value' => (string) $total,
            'delta' => $menipis . ' Restok',
            'delta_class' => $menipis > 0 ? 'rf-kpi-delta--danger' : '',
            'delta_icon' => null,
            'segments' => [
                ['class' => 'is-forest', 'width' => $persen, 'title' => $aman . ' Stok Aman'],
                ['class' => 'is-danger', 'width' => 100 - $persen, 'title' => $menipis . ' Menipis'],
            ],
            'foot_left' => $aman . ' Stok Aman',
            'foot_left_class' => 'rf-text-success is-medium',
            'foot_right' => $menipis . ' Menipis',
            'foot_right_class' => 'rf-text-danger is-semibold',
        ];
    }

    private function growthGroups(): array
    {
        $rabbits = Rabbit::with(['growthRecords' => fn ($query) => $query->orderByDesc('tgl_pencatatan')->orderByDesc('id')])
            ->whereHas('growthRecords')
            ->get()
            ->map(function (Rabbit $rabbit) {
                $records = $rabbit->growthRecords->values();
                $latest = $records->first();
                $previous = $records->skip(1)->first();
                $delta = $previous ? $latest->berat_badan - $previous->berat_badan : 0.0;

                return [
                    'label' => $rabbit->nama_kelinci . ' (' . $records->count() . ' rekaman)',
                    'weight' => $latest->berat_badan,
                    'value' => number_format($latest->berat_badan, 2) . ' kg (' . ($delta >= 0 ? '+' : '') . number_format($delta, 2) . ' kg)',
                ];
            })
            ->sortByDesc('weight')
            ->take(4)
            ->values();

        $maks = $rabbits->max('weight') ?: 1;

        return $rabbits->map(fn (array $group) => [
            'label' => $group['label'],
            'value' => $group['value'],
            'percent' => max((int) round($group['weight'] / $maks * 100), 4),
        ])->all();
    }

    private function recentRabbits(): array
    {
        return Rabbit::with('cage')->latest()->limit(4)->get()
            ->map(fn (Rabbit $rabbit) => [
                'id' => $rabbit->kode,
                'name' => $rabbit->nama_kelinci,
                'initials' => $rabbit->initials,
                'gender' => $rabbit->jenis_kelamin,
                'cage' => $rabbit->cage?->lokasi_blok ?? '-',
                'cage_class' => 'rf-badge--sage',
                'status' => $rabbit->status_kelinci,
                'status_class' => $rabbit->status_badge,
                'avatar_class' => '',
            ])
            ->all();
    }

    private function breedingPlans(): array
    {
        return BreedingRecord::with(['maleRabbit.cage', 'femaleRabbit'])
            ->where('status_perkawinan', 'Direncanakan')
            ->where('tgl_rencana_kawin', '>=', Carbon::today())
            ->orderBy('tgl_rencana_kawin')
            ->limit(2)
            ->get()
            ->map(fn (BreedingRecord $record) => [
                'title' => $record->maleRabbit?->nama_kelinci . ' (J) × ' . $record->femaleRabbit?->nama_kelinci . ' (B)',
                'day' => $record->tgl_rencana_kawin?->translatedFormat('d M Y'),
                'cage' => $record->maleRabbit?->cage?->lokasi_blok ?? 'Belum ditentukan',
                'status' => $record->status_perkawinan,
            ])
            ->all();
    }

    private function criticalStock(): array
    {
        return LogisticsItem::with('category')
            ->where('status_aktif', true)
            ->orderBy('stok')
            ->limit(2)
            ->get()
            ->map(function (LogisticsItem $item) {
                $menipis = $item->stok <= LogisticsItem::STOK_MENIPIS;

                return [
                    'name' => $item->nama_item,
                    'remaining' => $item->stok . ' pcs',
                    'note' => 'Kategori ' . $item->category?->nama_kategori,
                    'state' => $menipis ? 'danger' : 'safe',
                    'badge' => $menipis ? 'Perlu Restok' : 'Stok Aman',
                ];
            })
            ->all();
    }
}
