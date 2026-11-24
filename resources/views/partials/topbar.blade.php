@php
    $topbarUser = auth()->user();
@endphp

<header class="rf-topbar">
    <div class="rf-topbar-left">
        <button type="button" class="rf-topbar-toggle" data-bs-toggle="offcanvas" data-bs-target="#rfSidebar" aria-controls="rfSidebar" aria-label="Buka menu navigasi">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">menu</span>
        </button>

        <div class="rf-topbar-brand">
            <span class="rf-brand-mark">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">cruelty_free</span>
            </span>
            <span class="rf-topbar-brand-name">RabbitFlow</span>
        </div>

        <div class="rf-topbar-title">
            <h1>@yield('page-title', 'Dashboard Operasional')</h1>
            <nav class="rf-breadcrumb" aria-label="Breadcrumb">
                <span>RabbitFlow</span>
                <span aria-hidden="true">/</span>
                <span class="is-current">@yield('breadcrumb', 'Ikhtisar Peternakan')</span>
            </nav>
        </div>
    </div>

    <div class="rf-topbar-right">
        <div class="rf-chip rf-hide-mobile">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">calendar_today</span>
            <span>Bulan Berjalan: {{ $currentMonth ?? now()->translatedFormat('F Y') }}</span>
        </div>
        <span class="rf-role-chip">{{ $topbarUser?->role_name ?? 'Tamu' }}</span>
    </div>
</header>
