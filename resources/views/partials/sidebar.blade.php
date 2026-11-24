@php
    $navUser = auth()->user();
    $isOwner = $navUser?->isOwner() ?? false;
@endphp

<aside id="rfSidebar" class="rf-sidebar offcanvas-md offcanvas-start" tabindex="-1" aria-labelledby="rfSidebarLabel">
    <div>
        <div class="rf-brandbar">
            <a href="{{ route('dashboard') }}" class="rf-brand" id="rfSidebarLabel">
                <span class="rf-brand-mark">
                    <span class="material-symbols-outlined rf-icon" aria-hidden="true">cruelty_free</span>
                </span>
                <span class="rf-brand-text">
                    <span class="rf-brand-name-row">
                        <span class="rf-brand-name">RabbitFlow</span>
                        <span class="rf-live-dot" title="Sistem Aktif" aria-hidden="true"></span>
                    </span>
                    <span class="rf-brand-subtitle">Farm Intelligence</span>
                </span>
            </a>
            <button type="button" class="rf-sidebar-close" data-bs-dismiss="offcanvas" data-bs-target="#rfSidebar" aria-label="Tutup menu navigasi">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
            </button>
        </div>

        <nav class="rf-nav" aria-label="Navigasi utama">
            <div class="rf-nav-group">
                <a href="{{ route('dashboard') }}" class="rf-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" title="Dashboard" @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">dashboard</span>
                        <span>Dashboard</span>
                    </span>
                </a>
            </div>

            <div class="rf-nav-group">
                <p class="rf-nav-section">Data Master</p>
                @if ($isOwner)
                    <a href="{{ route('employees.index') }}" class="rf-nav-link {{ request()->routeIs('employees.*') ? 'is-active' : '' }}" title="Akun Karyawan" @if (request()->routeIs('employees.*')) aria-current="page" @endif>
                        <span class="rf-nav-link-inner">
                            <span class="material-symbols-outlined rf-icon" aria-hidden="true">badge</span>
                            <span>Akun Karyawan</span>
                        </span>
                        <span class="rf-nav-badge">Owner</span>
                    </a>
                    <a href="{{ route('cages.index') }}" class="rf-nav-link {{ request()->routeIs('cages.*') ? 'is-active' : '' }}" title="Data Kandang" @if (request()->routeIs('cages.*')) aria-current="page" @endif>
                        <span class="rf-nav-link-inner">
                            <span class="material-symbols-outlined rf-icon" aria-hidden="true">grid_view</span>
                            <span>Data Kandang</span>
                        </span>
                        <span class="rf-nav-badge">Owner</span>
                    </a>
                @endif
                <a href="{{ route('rabbits.index') }}" class="rf-nav-link {{ request()->routeIs('rabbits.*') ? 'is-active' : '' }}" title="Data Kelinci" @if (request()->routeIs('rabbits.*')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">pets</span>
                        <span>Data Kelinci</span>
                    </span>
                </a>
            </div>

            <div class="rf-nav-group">
                <p class="rf-nav-section">Monitoring &amp; Operasional</p>
                <a href="{{ route('growth.index') }}" class="rf-nav-link {{ request()->routeIs('growth.*') ? 'is-active' : '' }}" title="Pertumbuhan Kelinci" @if (request()->routeIs('growth.*')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">monitoring</span>
                        <span>Pertumbuhan Kelinci</span>
                    </span>
                </a>
                <a href="{{ route('breeding.index') }}" class="rf-nav-link {{ request()->routeIs('breeding.*') ? 'is-active' : '' }}" title="Perkawinan" @if (request()->routeIs('breeding.*')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">favorite</span>
                        <span>Perkawinan</span>
                    </span>
                </a>
                <a href="{{ route('logistics.index') }}" class="rf-nav-link {{ request()->routeIs('logistics.*') ? 'is-active' : '' }}" title="Logistik Kandang" @if (request()->routeIs('logistics.*')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">inventory_2</span>
                        <span>Logistik Kandang</span>
                    </span>
                </a>
                <a href="{{ route('reports.index') }}" class="rf-nav-link {{ request()->routeIs('reports.*') ? 'is-active' : '' }}" title="Laporan" @if (request()->routeIs('reports.*')) aria-current="page" @endif>
                    <span class="rf-nav-link-inner">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">summarize</span>
                        <span>Laporan</span>
                    </span>
                </a>
            </div>
        </nav>
    </div>

    <div class="rf-userbar">
        <div class="rf-user">
            <span class="rf-avatar">{{ $navUser?->initials ?? 'RF' }}</span>
            <span class="rf-user-text">
                <span class="rf-user-name">{{ $navUser?->nama_lengkap ?? 'Pengguna' }}</span>
                <span class="rf-user-role">{{ $isOwner ? 'Owner • Akses Penuh' : 'Karyawan • Operasional' }}</span>
            </span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rf-logout rf-sidebar-logout" title="Keluar" aria-label="Keluar">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">logout</span>
            </button>
        </form>
    </div>
</aside>
