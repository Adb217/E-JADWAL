@extends('layouts.guest')
@section('title', 'Masuk admin')
@section('content')
<div class="min-h-screen lg:grid lg:grid-cols-[1.05fr_1fr]" x-data="{ show: false }">

    <aside class="relative hidden flex-col justify-between bg-maroon-dark bg-grid p-14 text-white lg:flex">
        <div class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-lg border border-gold/60 font-display text-xl font-semibold text-gold">K</span>
            <span class="font-display text-lg">SMK Kosgoro Kota Bogor</span>
        </div>
        <div>
            <div class="mb-6 h-px w-16 bg-gold"></div>
            <h2 class="max-w-md font-display text-5xl font-semibold leading-[1.1] tracking-tight">Jadwal, ruangan, dan pengajar dalam satu tempat.</h2>
            <p class="mt-5 max-w-sm text-white/60">Sistem monitoring jadwal pelajaran untuk admin dan seluruh warga sekolah.</p>
        </div>
        <p class="text-xs text-white/40">Versi 1.0.4</p>
    </aside>

    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <div class="w-full max-w-sm">
            <span class="mb-6 grid h-11 w-11 place-items-center rounded-lg bg-maroon font-display text-xl font-semibold text-gold lg:hidden">K</span>

            <h1 class="font-display text-3xl font-semibold tracking-tight">Masuk admin</h1>
            <p class="mb-8 mt-1 text-sm text-gray-500">Gunakan akun admin untuk mengelola jadwal.</p>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <i class="fa-solid fa-circle-exclamation mr-1.5"></i>{{ $errors->first() }}
                </div>
                @endif

                <div>
                    <label class="lbl">Username</label>
                    <div class="relative mt-1.5">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                        <input type="text" name="username" value="{{ old('username') }}" autofocus placeholder="Masukkan username" class="inp !mt-0 pl-10">
                    </div>
                </div>

                <div>
                    <label class="lbl">Password</label>
                    <div class="relative mt-1.5">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Masukkan password" class="inp !mt-0 pl-10 pr-11">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-maroon" aria-label="Tampilkan password">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn w-full !py-3">Masuk</button>
            </form>

            <a href="{{ route('viewer.dashboard') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-maroon hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i>Kembali ke beranda
            </a>
        </div>
    </main>
</div>
@endsection