@extends('layouts.viewer')
@section('title','Info Perubahan')
@section('content')
<h1 class="text-xl lg:text-2xl font-bold text-maroon mb-1">Info Perubahan</h1>
<p class="text-gray-500 text-sm mb-6">Update terkini mengenai jadwal mengajar dan ruang kelas.</p>

<div class="grid gap-4 md:grid-cols-2">
    @forelse($changes as $c)
    <div class="bg-cream rounded-2xl border-l-4 {{ $c->border }} p-4">
        <div class="flex justify-between items-start gap-2 mb-3">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid {{ $c->icon }} text-maroon"></i>{{ $c->judul }}
            </h3>
            <span class="{{ $c->badge }} text-white text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">{{ $c->status }}</span>
        </div>
        @if($c->dari || $c->ke)
        <div class="bg-white rounded-xl p-3 flex justify-between items-center gap-2 mb-3 text-sm">
            <div><p class="text-gray-400 text-xs font-semibold">{{ strtoupper($c->meta['from']) }}</p><p class="font-bold">{{ $c->dari ?? '-' }}</p></div>
            <i class="fa-solid fa-arrow-right text-gray-400"></i>
            <div class="text-right"><p class="text-gray-400 text-xs font-semibold">{{ strtoupper($c->meta['to']) }}</p><p class="font-bold text-maroon">{{ $c->ke ?? '-' }}</p></div>
        </div>
        @endif
        <p class="text-sm text-gray-600"><i class="fa-solid fa-circle-info mr-1"></i>Alasan: {{ $c->alasan }}</p>
        <p class="text-xs text-gray-400 mt-2">{{ $c->created_at->diffForHumans() }}</p>
    </div>
    @empty
    <x-empty-state icon="fa-bell-slash" text="Belum ada info perubahan." />
    @endforelse
</div>
@endsection