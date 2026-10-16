@props(['tabs', 'aktif'])
<nav aria-label="Jadwal {{ $aktif->kelas->nama }} hari {{ $aktif->hari }}" class="mb-6 border-b border-line"
     x-data x-init="$nextTick(() => $el.querySelector('[aria-current]')?.scrollIntoView({ inline: 'center', block: 'nearest' }))">
    <p class="mb-2 text-xs text-gray-400">{{ $aktif->kelas->nama }} · {{ $aktif->hari }}</p>
    <ul class="-mb-px flex gap-1 overflow-x-auto overflow-y-hidden">
        @foreach($tabs as $t)
        @php $on = $t->id === $aktif->id; @endphp
        <li class="shrink-0">
            <a href="{{ route('viewer.jadwal.show', $t) }}" @if($on) aria-current="page" @endif
               class="block max-w-[14rem] truncate rounded-t-lg border border-b-0 px-4 py-2 text-sm transition {{ $on ? 'border-line border-t-2 border-t-maroon bg-white font-semibold text-maroon' : 'border-transparent text-gray-500 hover:bg-cream hover:text-maroon' }}">
                <span class="tnum text-xs {{ $on ? 'text-maroon' : 'text-gray-400' }}">{{ $t->mulai }}</span>
                <span class="ml-1.5">{{ $t->mapel->nama }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</nav>