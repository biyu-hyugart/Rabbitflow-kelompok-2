@extends('layouts.app')

@section('title', 'Perkawinan')
@section('page-title', 'Data Perkawinan')
@section('breadcrumb', 'Monitoring / Data Perkawinan')

@section('content')
    @php($isOwner = auth()->user()?->isOwner() ?? false)

    <div class="rf-page-head">
        <div>
            <h2 class="rf-page-title">Jadwal &amp; Riwayat Perkawinan Kelinci</h2>
            <p class="rf-page-sub">Kelola rencana kawin, realisasi, dan hasil kelahiran. Owner mengelola jadwal; Karyawan melihat jadwal dan riwayat.</p>
        </div>
        @if ($isOwner)
            <button type="button" class="rf-btn rf-btn--primary" data-bs-toggle="modal" data-bs-target="#breedingModal" data-breeding-mode="create">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">add</span>
                <span>Tambah Jadwal Kawin</span>
            </button>
        @endif
    </div>

    <div class="rf-role-banner">
        <div class="rf-role-banner-main">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">{{ $isOwner ? 'verified_user' : 'visibility' }}</span>
            <span>
                Mode Akses Saat Ini: <strong>{{ $isOwner ? 'Owner' : 'Karyawan' }}</strong>.
                @if ($isOwner)
                    Anda memiliki izin penuh menambah jadwal perkawinan, mengedit hasil realisasi, dan menghapus jadwal.
                @else
                    Anda dapat melihat jadwal dan riwayat perkawinan. Perubahan jadwal hanya dapat dilakukan Owner.
                @endif
            </span>
        </div>
    </div>

    <section class="rf-card" aria-label="Kalender rencana kawin pekan ini" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
            <h3 class="rf-page-title" style="font-size: 16px;">
                Kalender Rencana Kawin Pekan Ini
                <span class="rf-text-muted" style="font-size: 12px; font-weight: 500;">({{ $weekStart->translatedFormat('d M') }} &ndash; {{ $weekEnd->translatedFormat('d M Y') }})</span>
            </h3>
        </div>

        <div class="rf-calendar-grid">
            @foreach ($weekDays as $day)
                @php($hasRecords = $day['records']->isNotEmpty())
                <div class="rf-calendar-day {{ $hasRecords ? 'is-planned' : '' }}">
                    <span class="rf-calendar-day-label">{{ $day['date']->translatedFormat('D, d') }}</span>
                    @forelse ($day['records'] as $plan)
                        <div class="rf-calendar-item">
                            <div class="rf-calendar-item-name">{{ $plan->maleRabbit?->nama_kelinci ?? '-' }} &times; {{ $plan->femaleRabbit?->nama_kelinci ?? '-' }}</div>
                            <div class="rf-calendar-item-meta">{{ $plan->status_perkawinan }}</div>
                        </div>
                    @empty
                        <div class="rf-calendar-empty">Tidak ada jadwal</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>

    <section class="rf-card" aria-label="Daftar perkawinan">
        <form method="GET" action="{{ route('breeding.index') }}" class="rf-toolbar" data-rf-filter-form>
            <div class="rf-search">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">search</span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="rf-input" placeholder="Cari pejantan atau betina..." aria-label="Cari perkawinan">
            </div>
            <div class="rf-toolbar-filters">
                <select name="status_perkawinan" class="rf-select" aria-label="Filter status perkawinan" data-rf-auto-submit>
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\BreedingRecord::STATUS as $status)
                        <option value="{{ $status }}" @selected(($filters['status_perkawinan'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <select name="periode" class="rf-select" aria-label="Filter periode" data-rf-auto-submit>
                    <option value="">Semua Periode</option>
                    @foreach ($periods as $period)
                        <option value="{{ $period['value'] }}" @selected(($filters['periode'] ?? '') === $period['value'])>{{ $period['label'] }}</option>
                    @endforeach
                </select>
                @if (! empty($filters['q']) || ! empty($filters['status_perkawinan']) || ! empty($filters['periode']))
                    <a href="{{ route('breeding.index') }}" class="rf-btn rf-btn--ghost rf-btn--sm">Reset</a>
                @endif
            </div>
        </form>

        <div class="rf-table-wrap rf-table-wrap--data rf-hide-mobile" style="--rf-table-min-width: 1120px;">
            <table class="rf-table">
                <thead>
                    <tr>
                        <th scope="col">Kelinci Jantan</th>
                        <th scope="col">Kelinci Betina</th>
                        <th scope="col">Tgl Rencana Kawin</th>
                        <th scope="col">Tgl Realisasi Kawin</th>
                        <th scope="col">Status Perkawinan</th>
                        <th scope="col">Jumlah Anakan</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="is-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <span class="rf-cell-name">{{ $record->maleRabbit?->nama_kelinci ?? '-' }}</span>
                                <span class="rf-cell-sub">{{ $record->maleRabbit?->kode }}</span>
                            </td>
                            <td>
                                <span class="rf-cell-name">{{ $record->femaleRabbit?->nama_kelinci ?? '-' }}</span>
                                <span class="rf-cell-sub">{{ $record->femaleRabbit?->kode }}</span>
                            </td>
                            <td>{{ $record->tgl_rencana_kawin?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td>{{ $record->tgl_realisasi_kawin?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td><span class="rf-badge {{ $record->status_badge }}">{{ $record->status_perkawinan }}</span></td>
                            <td>{{ $record->jumlah_anakan !== null ? $record->jumlah_anakan . ' ekor' : '-' }}</td>
                            <td class="rf-text-muted" style="max-width: 200px;">{{ $record->keterangan ?: '-' }}</td>
                            <td class="is-right">
                                <div class="rf-row-actions">
                                    <button
                                        type="button"
                                        class="rf-icon-btn"
                                        title="Lihat Detail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#breedingModal"
                                        data-breeding-mode="view"
                                        data-breeding-male="{{ $record->male_rabbit_id }}"
                                        data-breeding-female="{{ $record->female_rabbit_id }}"
                                        data-breeding-plan="{{ $record->tgl_rencana_kawin?->format('Y-m-d') }}"
                                        data-breeding-actual="{{ $record->tgl_realisasi_kawin?->format('Y-m-d') }}"
                                        data-breeding-status="{{ $record->status_perkawinan }}"
                                        data-breeding-offspring="{{ $record->jumlah_anakan }}"
                                        data-breeding-notes="{{ $record->keterangan }}"
                                    >
                                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">visibility</span>
                                    </button>

                                    @if ($isOwner)
                                        <button
                                            type="button"
                                            class="rf-icon-btn"
                                            title="Edit Jadwal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#breedingModal"
                                            data-breeding-mode="edit"
                                            data-breeding-id="{{ $record->id }}"
                                            data-breeding-male="{{ $record->male_rabbit_id }}"
                                            data-breeding-female="{{ $record->female_rabbit_id }}"
                                            data-breeding-plan="{{ $record->tgl_rencana_kawin?->format('Y-m-d') }}"
                                            data-breeding-actual="{{ $record->tgl_realisasi_kawin?->format('Y-m-d') }}"
                                            data-breeding-status="{{ $record->status_perkawinan }}"
                                            data-breeding-offspring="{{ $record->jumlah_anakan }}"
                                            data-breeding-notes="{{ $record->keterangan }}"
                                        >
                                            <span class="material-symbols-outlined rf-icon" aria-hidden="true">edit</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="rf-icon-btn rf-icon-btn--danger"
                                            title="Hapus Jadwal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-action="{{ route('breeding.destroy', $record) }}"
                                            data-delete-name="jadwal {{ $record->maleRabbit?->nama_kelinci }} &times; {{ $record->femaleRabbit?->nama_kelinci }}"
                                            data-delete-message="Jadwal dan riwayat perkawinan ini akan dihapus permanen."
                                        >
                                            <span class="material-symbols-outlined rf-icon" aria-hidden="true">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="rf-empty">
                                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">favorite</span>
                                    Belum ada data perkawinan yang cocok dengan filter ini.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rf-record-list">
            @forelse ($records as $record)
                <article class="rf-record-card">
                    <div class="rf-record-head">
                        <div>
                            <div class="rf-record-title">
                                <span>{{ $record->maleRabbit?->nama_kelinci ?? '-' }}</span>
                                <span class="rf-text-muted">&times;</span>
                                <span>{{ $record->femaleRabbit?->nama_kelinci ?? '-' }}</span>
                            </div>
                            <div class="rf-record-meta">{{ $record->maleRabbit?->cage?->lokasi_blok ?? '-' }}</div>
                        </div>
                        <span class="rf-badge {{ $record->status_badge }} rf-badge--pill">{{ $record->status_perkawinan }}</span>
                    </div>

                    <div class="rf-record-dates">
                        <div>
                            <span>Rencana Kawin</span>
                            <strong>{{ $record->tgl_rencana_kawin?->translatedFormat('d M Y') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>Realisasi Kawin</span>
                            <strong>{{ $record->tgl_realisasi_kawin?->translatedFormat('d M Y') ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="rf-record-actions">
                        <button type="button" class="rf-btn rf-btn--ghost" data-bs-toggle="modal" data-bs-target="#breedingModal" data-breeding-mode="view" data-breeding-male="{{ $record->male_rabbit_id }}" data-breeding-female="{{ $record->female_rabbit_id }}" data-breeding-plan="{{ $record->tgl_rencana_kawin?->format('Y-m-d') }}" data-breeding-actual="{{ $record->tgl_realisasi_kawin?->format('Y-m-d') }}" data-breeding-status="{{ $record->status_perkawinan }}" data-breeding-offspring="{{ $record->jumlah_anakan }}" data-breeding-notes="{{ $record->keterangan }}">Detail</button>
                        @if ($isOwner)
                            <button type="button" class="rf-btn rf-btn--soft" data-bs-toggle="modal" data-bs-target="#breedingModal" data-breeding-mode="edit" data-breeding-id="{{ $record->id }}" data-breeding-male="{{ $record->male_rabbit_id }}" data-breeding-female="{{ $record->female_rabbit_id }}" data-breeding-plan="{{ $record->tgl_rencana_kawin?->format('Y-m-d') }}" data-breeding-actual="{{ $record->tgl_realisasi_kawin?->format('Y-m-d') }}" data-breeding-status="{{ $record->status_perkawinan }}" data-breeding-offspring="{{ $record->jumlah_anakan }}" data-breeding-notes="{{ $record->keterangan }}">Edit</button>
                            <button type="button" class="rf-btn rf-btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-action="{{ route('breeding.destroy', $record) }}" data-delete-name="jadwal {{ $record->maleRabbit?->nama_kelinci }} x {{ $record->femaleRabbit?->nama_kelinci }}" data-delete-message="Jadwal dan riwayat perkawinan ini akan dihapus permanen.">Hapus</button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rf-empty">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">favorite</span>
                    Belum ada data perkawinan yang cocok dengan filter ini.
                </div>
            @endforelse
        </div>

        @include('partials.pagination', ['paginator' => $records])
    </section>

    {{-- Form tambah/ubah dan modal konfirmasi dipisahkan ke partial --}}
    @include('breeding.partials.form-modal')
    @include('partials.delete-modal')
    @include('breeding.partials.scripts')
@endsection
