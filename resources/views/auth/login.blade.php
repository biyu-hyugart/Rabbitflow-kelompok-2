@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="rf-auth-card">
        <div class="rf-auth-heading">
            <span class="rf-auth-logo">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">cruelty_free</span>
            </span>
            <h1 class="rf-auth-title">Masuk ke RabbitFlow</h1>
            <p class="rf-auth-sub">Sistem Manajemen Operasional Kandang &amp; Kelinci</p>
        </div>

        @include('partials.flash')

        @if (app()->isLocal())
            <div class="rf-auth-demo">
                <div class="rf-auth-demo-head">
                    <span>AKUN DEMO PERAN:</span>
                    <span class="rf-auth-demo-tag">Laravel Auth RBAC</span>
                </div>
                <div class="rf-auth-demo-grid">
                    <button type="button" class="rf-auth-demo-btn" data-rf-demo-username="hendra_owner" data-rf-demo-password="rahasia123">
                        <span>1. Owner</span>
                        <code>hendra_owner</code>
                    </button>
                    <button type="button" class="rf-auth-demo-btn" data-rf-demo-username="budi_kandang" data-rf-demo-password="karyawan123">
                        <span>2. Karyawan</span>
                        <code>budi_kandang</code>
                    </button>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="rf-auth-form">
            @csrf

            <div class="rf-field">
                <label for="username" class="rf-label">Username</label>
                <div class="rf-input-wrap">
                    <span class="material-symbols-outlined rf-input-icon" aria-hidden="true">person</span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="rf-input rf-input--icon"
                        placeholder="Masukkan username Anda"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="rf-field">
                <label for="password" class="rf-label">Password</label>
                <div class="rf-input-wrap">
                    <span class="material-symbols-outlined rf-input-icon" aria-hidden="true">lock</span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="rf-input rf-input--icon rf-input--with-action"
                        placeholder="Masukkan password Anda"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="rf-input-action" data-rf-password-toggle="#password" aria-label="Tampilkan atau sembunyikan kata sandi">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">visibility</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="rf-btn rf-btn--primary rf-btn--block">
                <span>Masuk ke Sistem</span>
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">login</span>
            </button>
        </form>

        <div class="rf-auth-notice">
            <p>Akses terbatas hanya untuk Owner &amp; Karyawan resmi.</p>
            <p>Pendaftaran akun baru dikelola langsung oleh Pemilik Peternakan.</p>
        </div>
    </div>
@endsection
