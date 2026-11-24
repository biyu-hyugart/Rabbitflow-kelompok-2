@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Operasional')
@section('breadcrumb', 'Ikhtisar Peternakan (Owner)')

@section('content')
    <section class="rf-mobile-welcome rf-mobile-only" aria-label="Ringkasan kandang">
        <div class="rf-mobile-welcome-head">
            <div>
                <span class="rf-mobile-eyebrow">Ringkasan Kandang</span>
                <h2 class="rf-mobile-title">Operasional Peternakan</h2>
            </div>
            <span class="rf-mobile-date">{{ $currentDate }}</span>
        </div>
        <div class="rf-mobile-actions">
            <a href="{{ route('rabbits.index') }}" class="rf-mobile-action rf-mobile-action--primary">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">add</span>
                <span>+ Kelinci</span>
            </a>
            <a href="{{ route('breeding.index') }}" class="rf-mobile-action rf-mobile-action--soft">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">calendar_month</span>
                <span>Jadwal Kawin</span>
            </a>
        </div>
    </section>

    <section class="rf-kpi-grid" aria-label="Ringkasan operasional peternakan">
        @foreach ($kpis as $kpi)
            <article class="rf-kpi">
                <div class="rf-kpi-head">
                    <span class="rf-kpi-label">{{ $kpi['label'] }}</span>
                    <span class="rf-kpi-icon {{ $kpi['icon_class'] }}">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">{{ $kpi['icon'] }}</span>
                    </span>
                </div>
                <div>
                    <div class="rf-kpi-value-row">
                        <h2 class="rf-kpi-value">{{ $kpi['value'] }}</h2>
                        <span class="rf-kpi-delta {{ $kpi['delta_class'] }}">
                            @if ($kpi['delta_icon'])
                                <span class="material-symbols-outlined rf-icon" aria-hidden="true">{{ $kpi['delta_icon'] }}</span>
                            @endif
                            {{ $kpi['delta'] }}
                        </span>
                    </div>
                    <div class="rf-progress" role="presentation">
                        @foreach ($kpi['segments'] as $segment)
                            <span class="{{ $segment['class'] }}" style="width: {{ $segment['width'] }}%" title="{{ $segment['title'] }}"></span>
                        @endforeach
                    </div>
                    <p class="rf-kpi-foot">
                        <span class="{{ $kpi['foot_left_class'] }}">{{ $kpi['foot_left'] }}</span>
                        <span class="{{ $kpi['foot_right_class'] }}">{{ $kpi['foot_right'] }}</span>
                    </p>
                </div>
            </article>
        @endforeach
    </section>

    <div class="rf-grid-2">
        <section class="rf-panel" aria-labelledby="growth-title">
            <div class="rf-panel-head">
                <div class="rf-panel-title">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">monitoring</span>
                    <h3 id="growth-title">Tren Pertumbuhan Bobot Rata-rata</h3>
                </div>
                <a href="{{ route('growth.index') }}" class="rf-link">Detail Rekam &rarr;</a>
            </div>

            <div class="rf-panel-content">
                <div class="rf-growth-list">
                    @foreach ($growthGroups as $group)
                        <div class="rf-growth-row">
                            <div class="rf-growth-meta">
                                <span class="rf-growth-label">{{ $group['label'] }}</span>
                                <span class="rf-growth-value">{{ $group['value'] }}</span>
                            </div>
                            <div class="rf-growth-bar">
                                <span style="width: {{ $group['percent'] }}%"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rf-note">
                    <span>Semua kelompok umur memenuhi standar kurva agribisnis.</span>
                    <span class="rf-note-chip">Pakan Efisien</span>
                </div>
            </div>
        </section>

        <section class="rf-panel rf-panel--flush" aria-labelledby="recent-rabbits-title">
            <div class="rf-panel-head">
                <div class="rf-panel-title">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">pets</span>
                    <h3 id="recent-rabbits-title">Mutasi Kelinci Terakhir</h3>
                </div>
                <a href="{{ route('rabbits.index') }}" class="rf-link">Semua Kelinci &rarr;</a>
            </div>

            <div class="rf-table-wrap rf-hide-mobile">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th scope="col">ID &amp; Nama</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Kandang</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentRabbits as $rabbit)
                            <tr>
                                <td class="is-name"><span class="rf-mono">{{ $rabbit['id'] }}</span> - {{ $rabbit['name'] }}</td>
                                <td class="rf-text-muted">{{ $rabbit['gender'] }}</td>
                                <td><span class="rf-badge {{ $rabbit['cage_class'] }}">{{ $rabbit['cage'] }}</span></td>
                                <td><span class="rf-badge rf-badge--pill {{ $rabbit['status_class'] }}">{{ $rabbit['status'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="rf-rabbit-list rf-mobile-only">
                @foreach ($recentRabbits as $rabbit)
                    <div class="rf-rabbit-item">
                        <div class="rf-rabbit-main">
                            <span class="rf-rabbit-avatar {{ $rabbit['avatar_class'] }}">{{ $rabbit['initials'] }}</span>
                            <div>
                                <p class="rf-rabbit-name">{{ $rabbit['name'] }} <span class="rf-mono">({{ $rabbit['id'] }})</span></p>
                                <p class="rf-rabbit-meta">{{ $rabbit['gender'] }} &bull; {{ $rabbit['cage'] }}</p>
                            </div>
                        </div>
                        <span class="rf-badge rf-badge--pill {{ $rabbit['status_class'] }}">{{ $rabbit['status'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="rf-mobile-mating rf-mobile-only" aria-label="Rencana kawin terdekat">
        <div class="rf-mobile-mating-head">
            <span class="rf-mobile-mating-title">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">event</span>
                Rencana Kawin Terdekat
            </span>
            <span class="rf-badge rf-badge--warning">{{ $breedingPlans[0]['day'] ?? 'Belum ada jadwal' }}</span>
        </div>
        @forelse ($breedingPlans as $plan)
            <div class="rf-mobile-mating-card">
                <div>
                    <p class="rf-rabbit-name">{{ $plan['title'] }}</p>
                    <p class="rf-rabbit-meta">{{ $plan['cage'] }} &bull; {{ $plan['status'] }}</p>
                </div>
                <a href="{{ route('breeding.index') }}" class="rf-mobile-mating-open">Buka</a>
            </div>
        @empty
            <div class="rf-empty">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">event_busy</span>
                Belum ada rencana kawin terdekat.
            </div>
        @endforelse
    </section>

    <div class="rf-grid-2">
        <section class="rf-panel" aria-labelledby="breeding-title">
            <div class="rf-panel-head">
                <div class="rf-panel-title">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">favorite</span>
                    <h3 id="breeding-title">Rencana Kawin Pekan Ini</h3>
                </div>
                <a href="{{ route('breeding.index') }}" class="rf-link">Kelola Jadwal &rarr;</a>
            </div>

            <div class="rf-panel-content">
                @foreach ($breedingPlans as $plan)
                    <div class="rf-plan">
                        <div class="rf-plan-main">
                            <p class="rf-plan-title">{{ $plan['title'] }}</p>
                            <p class="rf-plan-meta">Rencana: <strong>{{ $plan['day'] }}</strong> &bull; {{ $plan['cage'] }}</p>
                        </div>
                        <span class="rf-badge rf-badge--warning">{{ $plan['status'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rf-panel" aria-labelledby="stock-title">
            <div class="rf-panel-head">
                <div class="rf-panel-title">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">inventory_2</span>
                    <h3 id="stock-title">Status Kritis Logistik Pakan &amp; Obat</h3>
                </div>
                <a href="{{ route('logistics.index') }}" class="rf-link">Buka Logistik &rarr;</a>
            </div>

            <div class="rf-panel-content">
                @foreach ($criticalStock as $stock)
                    <div class="rf-stock {{ $stock['state'] === 'danger' ? 'rf-stock--danger' : '' }}">
                        <div class="rf-stock-main">
                            <p class="rf-stock-title">{{ $stock['name'] }}</p>
                            <p class="rf-stock-meta">
                                Sisa:
                                <strong class="{{ $stock['state'] === 'danger' ? 'rf-text-danger' : 'rf-text-forest' }}">{{ $stock['remaining'] }}</strong>
                                &bull; {{ $stock['note'] }}
                            </p>
                        </div>
                        @if ($stock['state'] === 'danger')
                            <span class="rf-badge rf-badge--danger-solid">{{ $stock['badge'] }}</span>
                        @else
                            <span class="rf-badge rf-badge--success">{{ $stock['badge'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
