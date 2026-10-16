@extends('layouts.admin')
@section('title','Kehadiran Guru')
@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <h1 class="font-display text-2xl font-semibold">Kehadiran Guru</h1>
        <p class="mt-1 text-sm text-gray-500">Status hari ini, {{ now()->translatedFormat('l, d F Y') }}. Otomatis kembali ke "Belum datang" tiap hari.</p>
    </div>
    <form method="GET" class="relative sm:w-72">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau kode..." class="inp !mt-0 pl-10">
    </form>
</div>

<div class="card divide-y divide-line !p-0">
    @forelse($guru as $g)
    <form method="POST" action="{{ route('admin.kehadiran.update', $g) }}" class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
        @csrf
        <div class="min-w-0">
            <p class="truncate font-semibold">{{ $g->nama }}</p>
            <p class="text-xs text-gray-400">Kode: {{ $g->kode ?? '-' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach(\App\Models\KehadiranGuru::BADGE as $status => $b)
            <button name="status" value="{{ $status }}"
                    class="rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ $g->status_hari_ini === $status ? $b['class'] . ' border-transparent' : 'border-line bg-white text-gray-500 hover:border-maroon/40' }}">
                {{ $b['label'] }}
            </button>
            @endforeach
        </div>
    </form>
    @empty
    <p class="p-6 text-sm text-gray-400">Guru tidak ditemukan.</p>
    @endforelse
</div>
@endsection