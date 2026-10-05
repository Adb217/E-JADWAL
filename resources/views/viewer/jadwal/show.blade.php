@extends('layouts.viewer')
@section('title', $jadwal->mapel->nama)
@section('content')
<a href="{{ route('viewer.jadwal.index') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-maroon hover:underline">
    <i class="fa-solid fa-arrow-left text-xs"></i>Kembali ke jadwal
</a>

<div class="grid gap-6 lg:grid-cols-3 lg:items-start">
    <section class="card">
        <div class="flex items-start justify-between gap-2">
            <h1 class="font-display text-2xl font-semibold leading-snug">{{ $jadwal->mapel->nama }}</h1>
            <span class="{{ $jadwal->badge }} whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium">
                {{ $jadwal->status === 'Terjadwal' ? $jadwal->hari : $jadwal->status }}
            </span>
        </div>
        <dl class="mt-5 space-y-3 text-sm">
            @foreach([
                ['fa-calendar-days', 'Waktu', $jadwal->hari . ', ' . $jadwal->mulai . ' - ' . $jadwal->selesai],
                ['fa-school', 'Kelas', $jadwal->kelas->nama],
                ['fa-user', 'Guru', $jadwal->guru->nama],
                ['fa-door-open', 'Ruangan', $jadwal->ruangan->nama],
            ] as [$ic, $lb, $vl])
            <div class="flex items-start gap-3">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-cream text-maroon"><i class="fa-solid {{ $ic }} text-xs"></i></span>
                <div><dt class="text-xs text-gray-400">{{ $lb }}</dt><dd class="font-semibold">{{ $vl }}</dd></div>
            </div>
            @endforeach
        </dl>
    </section>

    <section class="lg:col-span-2">
        <h2 class="mb-4 font-display text-xl font-semibold">Materi {{ $jadwal->mapel->nama }}</h2>
        <div class="space-y-3">
            @forelse($materi as $m)
            <article class="card">
                <h3 class="font-semibold leading-snug">{{ $m->judul }}</h3>
                @if($m->deskripsi)<p class="mt-1 text-sm text-gray-500">{{ $m->deskripsi }}</p>@endif
                <p class="mt-1.5 text-xs text-gray-400"><i class="fa-regular fa-clock mr-1"></i>Diupload {{ $m->created_at->translatedFormat('l, d F Y · H:i') }}</p>

                <ul class="mt-3 space-y-1.5">
                    @foreach($m->files as $f)
                    <li>
                        <a href="{{ route('viewer.materi.download', $f) }}"
                           class="flex items-center gap-3 rounded-lg border border-line px-3 py-2.5 text-sm transition hover:border-maroon/40 hover:text-maroon">
                            <i class="fa-regular {{ $f->icon }} w-4 text-center text-maroon"></i>
                            <span class="min-w-0 flex-1 truncate">{{ $f->nama_asli }}</span>
                            <span class="hidden text-xs text-gray-400 sm:inline">{{ $f->ukuran_teks }}</span>
                            <i class="fa-solid fa-download text-xs"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </article>
            @empty
            <x-empty-state icon="fa-folder-open" text="Belum ada materi untuk mata pelajaran ini." />
            @endforelse
        </div>
    </section>
</div>
@endsection