@php
$items = [
    ['admin.dashboard',     'admin.dashboard', 'fa-house',           'Beranda'],
    ['admin.jadwal.index',  'admin.jadwal.*',  'fa-calendar-days',   'Jadwal'],
    ['admin.guru.index',    'admin.guru.*',    'fa-chalkboard-user', 'Guru'],
    ['admin.ruangan.index', 'admin.ruangan.*', 'fa-door-open',       'Ruangan'],
];
@endphp
<nav class="fixed inset-x-0 bottom-0 z-20 border-t border-line bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur lg:hidden">
    <div class="grid grid-cols-4 text-[11px]">
        @foreach($items as [$route, $pattern, $icon, $label])
        @php $on = request()->routeIs($pattern); @endphp
        <a href="{{ route($route) }}" class="flex flex-col items-center gap-1 py-2 {{ $on ? 'font-semibold text-maroon' : 'text-gray-500' }}">
            <span class="grid h-7 w-12 place-items-center rounded-full transition {{ $on ? 'bg-maroon/10' : '' }}">
                <i class="fa-solid {{ $icon }} text-[15px]"></i>
            </span>{{ $label }}
        </a>
        @endforeach
    </div>
</nav>