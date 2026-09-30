@extends('layouts.viewer')
@section('title','Detail Ruangan')
@section('content')
<a href="{{ route('viewer.ruangan.index') }}" class="inline-flex items-center gap-2 text-maroon font-semibold mb-4">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

<div class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 lg:mb-0 overflow-hidden">
        <div class="p-5">
            <h2 class="text-2xl font-bold">{{ $ruangan->nama }}</h2>
            <p class="text-gray-500 text-sm mb-2">
                <i class="fa-solid {{ $ruangan->tipe === 'Laboratorium Komputer' ? 'fa-laptop-code' : 'fa-door-open' }} mr-1"></i>{{ $ruangan->tipe }}
            </p>
            <span class="{{ $sekarang ? 'bg-maroon text-white' : 'bg-green-100 text-green-700' }} text-xs font-semibold px-3 py-1 rounded-full">
                {{ $sekarang ? '● TERPAKAI' : '● KOSONG' }}
            </span>
        </div>
        @if($sekarang)
        <div class="bg-maroon text-white p-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <p class="text-xs uppercase tracking-wide text-white/70">Sedang Berlangsung</p>
                <p class="font-bold">{{ $sekarang->guru->nama }}</p>
                <p class="text-sm text-white/80">{{ $sekarang->mapel->nama }} - Kelas {{ $sekarang->kelas->nama }}</p>
            </div>
        </div>
        @endif
    </div>

    <section class="lg:col-span-2">
        <h2 class="font-bold text-lg mb-3">Jadwal Hari Ini</h2>
        <ol class="relative border-l-2 border-gray-200 ml-1.5 space-y-4">
            @forelse($jadwal as $j)
            @php $aktif = $j->status === 'Berlangsung'; $selesai = $j->status === 'Selesai'; @endphp
            <li class="relative pl-6">
                <span class="absolute -left-[7px] top-5 w-3 h-3 rounded-full ring-4 ring-gray-50 {{ $aktif ? 'bg-maroon' : ($selesai ? 'bg-gray-300' : 'bg-blue-400') }}"></span>
                <div class="bg-white rounded-xl border {{ $aktif ? 'border-maroon' : 'border-gray-100' }} p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold {{ $aktif ? 'text-maroon' : ($selesai ? 'text-gray-400' : '') }}">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $j->mulai }} - {{ $j->selesai }}
                        </span>
                        <span class="{{ $j->badge }} text-xs px-2 py-1 rounded-full">{{ strtoupper($j->status) }}</span>
                    </div>
                    <p class="font-bold {{ $selesai ? 'text-gray-400' : '' }}">{{ $j->mapel->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $j->kelas->nama }} • {{ $j->guru->nama }}</p>
                </div>
            </li>
            @empty
            <li class="pl-6 text-gray-400"><i class="fa-solid fa-door-open mr-1"></i>Ruangan kosong sepanjang hari.</li>
            @endforelse
        </ol>
    </section>
</div>
@endsection