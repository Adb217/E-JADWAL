@extends('layouts.viewer')
@section('title','Status Ruangan')
@section('content')
<div x-data x-init="setTimeout(() => location.reload(), 60000)">
    <h1 class="text-xl lg:text-2xl font-bold mb-1">Status Ruangan</h1>
    <p class="text-gray-500 text-sm mb-4">Pantau ketersediaan ruangan secara real-time.</p>

    <div class="flex gap-2 mb-6 overflow-x-auto lg:flex-wrap">
        <a href="{{ route('viewer.ruangan.index') }}"
           class="{{ request('tipe') ? 'bg-white border border-gray-200 hover:bg-cream' : 'bg-maroon text-white' }} px-4 py-2 rounded-full text-sm whitespace-nowrap">Semua</a>
        @foreach($tipe as $t)
        <a href="{{ route('viewer.ruangan.index', ['tipe' => $t]) }}"
           class="{{ request('tipe') === $t ? 'bg-maroon text-white' : 'bg-white border border-gray-200 hover:bg-cream' }} px-4 py-2 rounded-full text-sm whitespace-nowrap">{{ $t }}</a>
        @endforeach
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($rooms as $r)
        @php $now = $r->sekarang; @endphp
        <a href="{{ route('viewer.ruangan.show', $r) }}"
           class="flex flex-col bg-white rounded-2xl border-l-4 {{ $now ? 'border-l-maroon' : 'border-l-green-500' }} border border-gray-100 p-4 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-center gap-2 mb-2">
                <h3 class="font-bold uppercase">{{ $r->nama }}</h3>
                <span class="{{ $now ? 'bg-maroon text-white' : 'bg-green-100 text-green-700' }} text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                    {{ $now ? 'TERPAKAI' : '● KOSONG' }}
                </span>
            </div>
            @if($now)
            <div class="space-y-1 text-sm text-gray-600">
                <p><i class="fa-solid fa-users w-4 text-center mr-1"></i>{{ $now->kelas->nama }}</p>
                <p><i class="fa-solid fa-book-open w-4 text-center mr-1"></i>{{ $now->mapel->nama }}</p>
                <p><i class="fa-solid fa-user w-4 text-center mr-1"></i>{{ $now->guru->nama }}</p>
                <p><i class="fa-regular fa-clock w-4 text-center mr-1"></i>Selesai {{ $now->selesai }} (sisa {{ $now->sisa }})</p>
            </div>
            @else
            <div class="text-center py-4 text-gray-400 my-auto">
                <i class="fa-solid fa-door-open text-2xl mb-1"></i>
                <p class="text-sm">Ruangan tersedia untuk digunakan.</p>
                <p class="text-sm">
                    @if($r->berikutnya) Jadwal berikutnya: {{ $r->berikutnya->mulai }} • {{ $r->berikutnya->kelas->nama }}
                    @else Tidak ada jadwal lagi hari ini @endif
                </p>
            </div>
            @endif
        </a>
        @empty
        <x-empty-state icon="fa-door-open" text="Belum ada data ruangan." />
        @endforelse
    </div>
</div>
@endsection