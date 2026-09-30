@php
$menu = [
    ['admin.dashboard',        'admin.dashboard',    'fa-chart-pie',       'Dashboard'],
    ['admin.jadwal.index',     'admin.jadwal.*',     'fa-calendar-days',   'Jadwal'],
    ['admin.kelas.index',      'admin.kelas.*',      'fa-school',          'Kelas'],
    ['admin.guru.index',       'admin.guru.*',       'fa-chalkboard-user', 'Guru'],
    ['admin.mapel.index',      'admin.mapel.*',      'fa-book-open',       'Mapel'],
    ['admin.ruangan.index',    'admin.ruangan.*',    'fa-door-open',       'Ruangan'],
    ['admin.guru-piket.index', 'admin.guru-piket.*', 'fa-id-card',         'Guru piket'],
    ['admin.perubahan.index',  'admin.perubahan.*',  'fa-bell',            'Perubahan'],
];
@endphp

<div x-show="drawer" x-cloak x-transition.opacity
     class="fixed inset-0 z-40 bg-ink/50 lg:hidden" @click="drawer = false"></div>

<aside class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-y-auto bg-maroon-dark px-4 py-6 text-white transition-transform duration-200 lg:w-64 lg:translate-x-0"
       :class="drawer && '!translate-x-0'">

    <div class="mb-8 flex items-center justify-between px-2">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-lg border border-gold/60 font-display text-lg font-semibold text-gold">K</span>
            <span class="leading-tight">
                <span class="block font-display text-[17px] font-semibold">SMK Kosgoro</span>
                <span class="block text-xs text-white/50">Panel admin</span>
            </span>
        </div>
        <button @click="drawer = false" class="grid h-8 w-8 place-items-center rounded-lg hover:bg-white/10 lg:hidden" aria-label="Tutup">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="flex-1 space-y-0.5">
        @foreach($menu as [$route, $pattern, $icon, $label])
        <a href="{{ route($route) }}"
           class="relative flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition
                  {{ request()->routeIs($pattern)
                      ? 'bg-white/10 font-semibold text-white before:absolute before:inset-y-2 before:left-0 before:w-0.5 before:rounded-full before:bg-gold'
                      : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
            <i class="fa-solid {{ $icon }} w-5 text-center text-[13px] {{ request()->routeIs($pattern) ? 'text-gold' : '' }}"></i>{{ $label }}
        </a>
        @endforeach
    </nav>

    <div class="mt-6 border-t border-white/10 pt-4">
        <div class="mb-2 flex items-center gap-3 px-2">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-gold/20 text-sm font-semibold text-gold">
                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-white/50">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-sm text-white/65 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-right-from-bracket w-5 text-center text-[13px]"></i>Keluar
            </button>
        </form>
    </div>
</aside>