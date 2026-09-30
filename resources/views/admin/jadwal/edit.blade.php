@extends('layouts.admin')
@section('title','Edit Jadwal')
@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.jadwal.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg text-maroon hover:bg-maroon/10">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <h1 class="font-bold text-lg lg:text-2xl">Edit Jadwal</h1>
</div>
<form method="POST" action="{{ route('admin.jadwal.update', $jadwal) }}" class="max-w-4xl">
    @csrf @method('PUT')
    @include('admin.jadwal._form')
</form>
@endsection