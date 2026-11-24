<nav class="rf-mobile-nav" aria-label="Navigasi bawah">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">dashboard</span>
        <span>Beranda</span>
    </a>
    <a href="{{ route('rabbits.index') }}" class="{{ request()->routeIs('rabbits.*') ? 'is-active' : '' }}" @if (request()->routeIs('rabbits.*')) aria-current="page" @endif>
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">pets</span>
        <span>Kelinci</span>
    </a>
    <a href="{{ route('growth.index') }}" class="{{ request()->routeIs('growth.*') ? 'is-active' : '' }}" @if (request()->routeIs('growth.*')) aria-current="page" @endif>
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">scale</span>
        <span>Bobot</span>
    </a>
    <a href="{{ route('breeding.index') }}" class="{{ request()->routeIs('breeding.*') ? 'is-active' : '' }}" @if (request()->routeIs('breeding.*')) aria-current="page" @endif>
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">favorite</span>
        <span>Kawin</span>
    </a>
    <a href="{{ route('logistics.index') }}" class="{{ request()->routeIs('logistics.*') ? 'is-active' : '' }}" @if (request()->routeIs('logistics.*')) aria-current="page" @endif>
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">inventory_2</span>
        <span>Logistik</span>
    </a>
</nav>
