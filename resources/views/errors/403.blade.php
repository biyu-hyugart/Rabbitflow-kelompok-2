@extends('layouts.app')

@section('title', 'Akses Ditolak')
@section('page-title', 'Akses Ditolak')
@section('breadcrumb', 'Halaman tidak dapat diakses')

@section('content')
    <section class="rf-empty-state rf-empty-state--page">
        <span class="rf-empty-icon rf-empty-icon--danger">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">lock</span>
        </span>
        <h2>Anda tidak memiliki hak akses ke halaman ini</h2>
        <p>Halaman ini hanya tersedia untuk peran tertentu. Silakan kembali ke dashboard atau hubungi Owner peternakan.</p>
        <a href="{{ route('dashboard') }}" class="rf-btn rf-btn--primary">Kembali ke Dashboard</a>
    </section>
@endsection
