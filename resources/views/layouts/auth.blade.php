<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RabbitFlow') &mdash; RabbitFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="rf-body rf-body--auth">
    <header class="rf-auth-header">
        <a href="{{ route('login') }}" class="rf-auth-brand">
            <span class="rf-brand-mark">
                <span class="material-symbols-outlined rf-icon" aria-hidden="true">cruelty_free</span>
            </span>
            <span class="rf-auth-brand-text">
                <span class="rf-brand-name">RabbitFlow</span>
                <span class="rf-auth-brand-tag">Sistem Informasi Peternakan Kelinci</span>
            </span>
        </a>
        <span class="rf-auth-status">
            <span class="rf-auth-status-dot" aria-hidden="true"></span>
            Server Peternakan Aktif
        </span>
    </header>

    <main class="rf-auth-main">
        @yield('content')
    </main>

    <footer class="rf-auth-footer">
        &copy; {{ now()->year }} RabbitFlow &bull; Sistem Informasi Manajemen Peternakan Kelinci Berbasis Web. Laravel &amp; Bootstrap.
    </footer>

    @stack('scripts')
</body>
</html>
