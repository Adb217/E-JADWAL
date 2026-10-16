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