@extends('layouts.admin')
@section('title','Kelola Jadwal')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-bold">Manajemen Jadwal</h1>
        <p class="text-gray-500 text-sm">Kelola jadwal pelajaran, ruangan, dan pengajar.</p>
    </div>
    <a href="{{ route('admin.jadwal.create') }}" class="btn"><i class="fa-solid fa-plus"></i> Tambah Jadwal</a>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
    <select name="hari" onchange="this.form.submit()" class="inp !mt-0 sm:w-48">
        <option value="">Semua Hari</option>
        @foreach(App\Models\Jadwal::HARI as $h)
        <option value="{{ $h }}" @selected(request('hari') === $h)>{{ $h }}</option>
        @endforeach
    </select>
    <select name="kelas_id" onchange="this.form.submit()" class="inp !mt-0 sm:w-56">
        <option value="">Semua Kelas</option>
        @foreach($kelas as $k)
        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
        @endforeach
    </select>
    @if(request()->hasAny(['hari','kelas_id']))
    <a href="{{ route('admin.jadwal.index') }}" class="btn-ghost"><i class="fa-solid fa-rotate-left"></i> Reset</a>
    @endif
</form>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @forelse($jadwal as $j)
    <div class="bg-white rounded-2xl border-l-4 {{ $j->border }} border border-gray-100 p-4 shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <span class="bg-cream text-gray-500 text-xs px-2 py-1 rounded-full">
                <i class="fa-regular fa-clock mr-1"></i>{{ $j->hari }} • {{ $j->mulai }} - {{ $j->selesai }}
            </span>
            <div class="flex gap-1">
                <a href="{{ route('admin.jadwal.edit', $j) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50" aria-label="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form method="POST" action="{{ route('admin.jadwal.destroy', $j) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                    @csrf @method('DELETE')
                    <button class="w-8 h-8 rounded-lg text-red-600 hover:bg-red-50" aria-label="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
        <h3 class="font-bold text-lg">{{ $j->mapel->nama }}</h3>
        <p class="text-gray-500 text-sm mb-2"><i class="fa-solid fa-user w-4 text-center mr-1"></i>{{ $j->guru->nama }}</p>
        <hr class="mb-2">
        <p class="text-sm text-gray-600 flex flex-wrap gap-x-4 gap-y-1">
            <span><i class="fa-solid fa-door-open w-4 text-center mr-1"></i>{{ $j->ruangan->nama }}</span>
            <span><i class="fa-solid fa-users w-4 text-center mr-1"></i>{{ $j->kelas->nama }}</span>
        </p>
    </div>
    @empty
    <x-empty-state icon="fa-calendar-xmark" text="Belum ada jadwal." />
    @endforelse
</div>

<a href="{{ route('admin.jadwal.create') }}" class="lg:hidden fixed bottom-24 right-5 w-14 h-14 bg-maroon text-white rounded-2xl flex items-center justify-center text-xl shadow-lg" aria-label="Tambah">
    <i class="fa-solid fa-plus"></i>
</a>
@endsection