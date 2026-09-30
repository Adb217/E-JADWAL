@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

<section class="mb-8 grid grid-cols-2 overflow-hidden rounded-xl border border-line bg-white lg:grid-cols-4">
    @foreach($stats as [$icon, $label, $value, $tone])
    <div class="border-line p-5 lg:p-6
                {{ $loop->index % 2 ? 'border-l' : '' }}
                {{ $loop->index > 1 ? 'border-t lg:border-t-0' : '' }}
                {{ $loop->index > 0 ? 'lg:border-l' : '' }}">
        <i class="fa-solid {{ $icon }} text-sm text-maroon/70"></i>
        <p class="tnum mt-5 font-display text-4xl font-semibold leading-none">{{ $value }}</p>
        <p class="mt-2 text-sm text-gray-500">{{ $label }}</p>
    </div>
    @endforeach
</section>

<div class="grid items-start gap-6 lg:grid-cols-5">
    <section class="card !p-0 lg:col-span-3">
        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 class="font-display text-lg font-semibold">Jadwal hari ini</h2>
            <a href="{{ route('admin.jadwal.index') }}" class="text-sm font-semibold text-maroon hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400">
                        <th class="px-5 py-3 font-medium">Waktu</th>
                        <th class="px-5 py-3 font-medium">Kelas</th>
                        <th class="px-5 py-3 font-medium">Mata pelajaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($today as $j)
                    <tr>
                        <td class="tnum whitespace-nowrap px-5 py-3.5 font-semibold">{{ $j->mulai }} – {{ $j->selesai }}</td>
                        <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $j->kelas->nama }}</td>
                        <td class="px-5 py-3.5 font-medium">{{ $j->mapel->nama }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-5 py-10 text-center text-gray-400">Tidak ada jadwal hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card lg:col-span-2">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold">Perubahan terkini</h2>
            <a href="{{ route('admin.perubahan.index') }}" class="text-sm font-semibold text-maroon hover:underline">Kelola</a>
        </div>
        @forelse($perubahan as $p)
            @if($loop->first)<ol class="relative ml-1.5 space-y-4 border-l border-line">@endif
            <li class="relative pl-5">
                <span class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full ring-4 ring-white {{ $p->dot }}"></span>
                <div class="flex justify-between gap-2 text-sm">
                    <span class="font-semibold">{{ $p->judul }}</span>
                    <span class="whitespace-nowrap text-xs text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                </div>
                <p class="mt-1 text-sm text-gray-600">{{ $p->dari ? $p->dari.' → '.$p->ke.'. ' : '' }}{{ $p->alasan }}</p>
            </li>
            @if($loop->last)</ol>@endif
        @empty
            <p class="py-6 text-center text-sm text-gray-400">Belum ada perubahan.</p>
        @endforelse
    </section>
</div>
@endsection