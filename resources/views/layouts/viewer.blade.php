<!DOCTYPE html>
<html lang="id">
<head>
<title>@yield('title', 'Monitoring Jadwal') · SMK Kosgoro</title>
@include('partials.head')
</head>
<body class="pb-24 lg:pb-0">

@php
$nav = [
    ['viewer.dashboard',       'viewer.dashboard',   'fa-house',         'Beranda'],
    ['viewer.jadwal.index',    'viewer.jadwal.*',    'fa-calendar-days', 'Jadwal'],
    ['viewer.ruangan.index',   'viewer.ruangan.*',   'fa-door-open',     'Ruangan'],
    ['viewer.perubahan.index', 'viewer.perubahan.*', 'fa-bell',          'Perubahan'],
];
@endphp

<header class="sticky top-0 z-30 border-b border-line bg-paper/85 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 lg:px-8">
        <a href="{{ route('viewer.dashboard') }}" class="flex items-center gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-maroon font-display text-lg font-semibold text-gold">K</span>
            <span class="leading-tight">
                <span class="block font-display text-[17px] font-semibold">SMK Kosgoro</span>
                <span class="block text-xs text-gray-500">Monitoring jadwal</span>
            </span>
        </a>

        <nav class="hidden items-stretch gap-8 self-stretch lg:flex">
            @foreach($nav as [$route, $pattern, $icon, $label])
            <a href="{{ route($route) }}"
               class="relative flex items-center text-sm {{ request()->routeIs($pattern) ? 'font-semibold text-maroon after:absolute after:inset-x-0 after:bottom-0 after:h-0.5 after:bg-gold' : 'text-gray-600 hover:text-maroon' }}">
                {{ $label }}
            </a>
            @endforeach
        </nav>

        <a href="{{ route('login') }}" class="btn-ghost !px-4 !py-2">
            <i class="fa-solid fa-lock text-xs"></i><span class="hidden sm:inline">Masuk admin</span>
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 lg:px-8 lg:py-10">
@yield('content')
</main>

@include('partials.bottom-nav-viewer')
</body>
</html>