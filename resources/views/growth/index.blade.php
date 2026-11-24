@extends('layouts.app')

@section('title', 'Pertumbuhan Kelinci')
@section('page-title', 'Data Pertumbuhan Kelinci')
@section('breadcrumb', 'Monitoring / Pertumbuhan Kelinci')

@section('content')
    <div class="rf-page-head">
        <div>
            <h2 class="rf-page-title">Kurva Pertumbuhan &amp; Riwayat Fisik</h2>
            <p class="rf-page-sub">Pencatatan periodik berat dan panjang badan kelinci pada tabel <code class="rf-code">growth_records</code>.</p>
        </div>
        <button type="button" class="rf-btn rf-btn--primary" data-bs-toggle="modal" data-bs-target="#growthModal" data-growth-mode="create">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">add</span>
            <span>Catat Pertumbuhan Baru</span>
        </button>
    </div>

    @if ($spotlight)
        @php
            $spotRabbit = $spotlight['rabbit'];
            $latest = $spotlight['latest'];
        @endphp
        <section class="rf-spotlight" aria-label="Sorotan pertumbuhan kelinci">
            <div class="rf-spotlight-head">
                <div class="rf-spotlight-id">
                    <span class="rf-spotlight-mark">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">scale</span>
                    </span>
                    <div>
                        <div class="rf-spotlight-name">
                            <span>{{ $spotRabbit->nama_kelinci }} ({{ $spotRabbit->kode }})</span>
                            <span class="rf-badge {{ $spotRabbit->status_badge }} rf-badge--pill">{{ $spotRabbit->status_kelinci }}</span>
                        </div>
                        <p class="rf-spotlight-meta">
                            Kandang: {{ $spotRabbit->cage?->lokasi_blok ?? '-' }} &bull; Usia: {{ $spotRabbit->umur_bulan }} bulan
                            @if ($latest)
                                &bull; Bobot terkini: <strong>{{ number_format((float) $latest->berat_badan, 2, ',', '.') }} kg</strong>
                            @endif
                        </p>
                    </div>
                </div>

                @if ($spotlight['milestones']->isNotEmpty())
                    <div class="rf-spotlight-milestones">
                        @foreach ($spotlight['milestones'] as $milestone)
                            <div class="rf-milestone">
                                <span class="rf-milestone-label">{{ $milestone->tgl_pencatatan?->translatedFormat('d M Y') }}</span>
                                <span class="rf-milestone-value">{{ number_format((float) $milestone->berat_badan, 2, ',', '.') }} kg</span>
                            </div>
                            @if (! $loop->last)
                                <span class="rf-milestone-arrow" aria-hidden="true">&rarr;</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rf-metric-strip">
                <div class="rf-metric">
                    <span class="rf-metric-label">Rata-rata Kenaikan Bobot</span>
                    <p class="rf-metric-value">
                        {{ $spotlight['average_gain'] >= 0 ? '+' : '' }}{{ number_format((float) $spotlight['average_gain'], 2, ',', '.') }} kg / bulan
                    </p>
                </div>
                <div class="rf-metric">
                    <span class="rf-metric-label">Total Pencatatan</span>
                    <p class="rf-metric-value">{{ $spotlight['total'] }} Catatan</p>
                </div>
                <div class="rf-metric">
                    <span class="rf-metric-label">Bobot Terkini</span>
                    <p class="rf-metric-value {{ $latest ? 'rf-metric-value--success' : 'rf-metric-value--muted' }}">
                        {{ $latest ? number_format((float) $latest->berat_badan, 2, ',', '.') . ' kg' : '-' }}
                    </p>
                </div>
            </div>
        </section>
    @endif

    <section class="rf-card" aria-label="Riwayat pertumbuhan kelinci">
        <form method="GET" action="{{ route('growth.index') }}" class="rf-toolbar" data-rf-filter-form>
            <div class="rf-search">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">search</span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="rf-input" placeholder="Cari kelinci atau ID pencatatan..." aria-label="Cari catatan pertumbuhan">
            </div>
            <div class="rf-toolbar-filters">
                <select name="rabbit_id" class="rf-select" aria-label="Filter kelinci" data-rf-auto-submit>
                    <option value="">Semua Kelinci</option>
                    @foreach ($rabbits as $rabbit)
                        <option value="{{ $rabbit->id }}" @selected((string) ($filters['rabbit_id'] ?? '') === (string) $rabbit->id)>{{ $rabbit->kode }} - {{ $rabbit->nama_kelinci }}</option>
                    @endforeach
                </select>
                <select name="periode" class="rf-select" aria-label="Filter periode" data-rf-auto-submit>
                    <option value="">Semua Periode</option>
                    @foreach ($periods as $period)
                        <option value="{{ $period['value'] }}" @selected(($filters['periode'] ?? '') === $period['value'])>{{ $period['label'] }}</option>
                    @endforeach
                </select>
                <select name="kondisi" class="rf-select" aria-label="Filter status kelinci" data-rf-auto-submit>
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\Rabbit::STATUS as $status)
                        <option value="{{ $status }}" @selected(($filters['kondisi'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                @if (! empty($filters['q']) || ! empty($filters['rabbit_id']) || ! empty($filters['periode']) || ! empty($filters['kondisi']))
                    <a href="{{ route('growth.index') }}" class="rf-btn rf-btn--ghost rf-btn--sm">Reset</a>
                @endif
            </div>
        </form>

        <div class="rf-table-wrap rf-table-wrap--data rf-hide-mobile" style="--rf-table-min-width: 1040px;">
            <table class="rf-table">
                <thead>
                    <tr>
                        <th scope="col">Nama Kelinci</th>
                        <th scope="col">Tanggal Pencatatan</th>
                        <th scope="col">Berat Badan</th>
                        <th scope="col">Panjang Badan</th>
                        <th scope="col">Status Kelinci</th>
                        <th scope="col">Kondisi Kesehatan</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="is-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <span class="rf-cell-name">{{ $record->rabbit?->nama_kelinci ?? '-' }}</span>
                                <span class="rf-cell-sub">{{ $record->rabbit?->kode }}</span>
                            </td>
                            <td>{{ $record->tgl_pencatatan?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td><span class="rf-text-forest" style="font-weight: 700;">{{ number_format((float) $record->berat_badan, 2, ',', '.') }} kg</span></td>
                            <td>{{ number_format((float) $record->panjang_badan, 1, ',', '.') }} cm</td>
                            <td><span class="rf-badge {{ $record->status_badge }}">{{ $record->status_kelinci }}</span></td>
                            <td>{{ $record->kondisi_kesehatan }}</td>
                            <td class="rf-text-muted" style="max-width: 220px;">{{ $record->keterangan ?: '-' }}</td>
                            <td class="is-right">
                                <div class="rf-row-actions">
                                    <button
                                        type="button"
                                        class="rf-icon-btn"
                                        title="Edit Catatan"
                                        data-bs-toggle="modal"
                                        data-bs-target="#growthModal"
                                        data-growth-mode="edit"
                                        data-growth-id="{{ $record->id }}"
                                        data-growth-rabbit="{{ $record->rabbit_id }}"
                                        data-growth-date="{{ $record->tgl_pencatatan?->format('Y-m-d') }}"
                                        data-growth-weight="{{ $record->berat_badan }}"
                                        data-growth-length="{{ $record->panjang_badan }}"
                                        data-growth-status="{{ $record->status_kelinci }}"
                                        data-growth-health="{{ $record->kondisi_kesehatan }}"
                                        data-growth-notes="{{ $record->keterangan }}"
                                    >
                                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">edit</span>
                                    </button>
                                    <button
                                        type="button"
                                        class="rf-icon-btn rf-icon-btn--danger"
                                        title="Hapus Catatan"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-delete-action="{{ route('growth.destroy', $record) }}"
                                        data-delete-name="catatan {{ $record->tgl_pencatatan?->translatedFormat('d M Y') }} - {{ $record->rabbit?->nama_kelinci }}"
                                        data-delete-message="Catatan pertumbuhan akan dihapus permanen."
                                    >
                                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="rf-empty">
                                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">monitoring</span>
                                    Belum ada catatan pertumbuhan yang cocok dengan filter ini.
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
                                <span>{{ $record->rabbit?->nama_kelinci ?? '-' }}</span>
                                <span class="rf-code">{{ $record->rabbit?->kode }}</span>
                            </div>
                            <div class="rf-record-meta">{{ $record->tgl_pencatatan?->translatedFormat('d M Y') }} &bull; {{ $record->kondisi_kesehatan }}</div>
                        </div>
                        <span class="rf-badge {{ $record->status_badge }} rf-badge--pill">{{ $record->status_kelinci }}</span>
                    </div>

                    <div class="rf-record-dates">
                        <div>
                            <span>Berat Badan</span>
                            <strong>{{ number_format((float) $record->berat_badan, 2, ',', '.') }} kg</strong>
                        </div>
                        <div>
                            <span>Panjang Badan</span>
                            <strong>{{ number_format((float) $record->panjang_badan, 1, ',', '.') }} cm</strong>
                        </div>
                    </div>

                    @if ($record->keterangan)
                        <p class="rf-record-meta">{{ $record->keterangan }}</p>
                    @endif

                    <div class="rf-record-actions">
                        <button type="button" class="rf-btn rf-btn--soft" data-bs-toggle="modal" data-bs-target="#growthModal" data-growth-mode="edit" data-growth-id="{{ $record->id }}" data-growth-rabbit="{{ $record->rabbit_id }}" data-growth-date="{{ $record->tgl_pencatatan?->format('Y-m-d') }}" data-growth-weight="{{ $record->berat_badan }}" data-growth-length="{{ $record->panjang_badan }}" data-growth-status="{{ $record->status_kelinci }}" data-growth-health="{{ $record->kondisi_kesehatan }}" data-growth-notes="{{ $record->keterangan }}">Edit</button>
                        <button type="button" class="rf-btn rf-btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-action="{{ route('growth.destroy', $record) }}" data-delete-name="catatan {{ $record->tgl_pencatatan?->translatedFormat('d M Y') }}" data-delete-message="Catatan pertumbuhan akan dihapus permanen.">Hapus</button>
                    </div>
                </article>
            @empty
                <div class="rf-empty">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">monitoring</span>
                    Belum ada catatan pertumbuhan yang cocok dengan filter ini.
                </div>
            @endforelse
        </div>

        @include('partials.pagination', ['paginator' => $records])
    </section>

    {{-- Form tambah/ubah dan modal konfirmasi dipisahkan ke partial --}}
    @include('growth.partials.form-modal')
    @include('partials.delete-modal')
    @include('growth.partials.scripts')
@endsection
