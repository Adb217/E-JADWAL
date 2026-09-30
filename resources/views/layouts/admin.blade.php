<!DOCTYPE html>
<html lang="id">
<head>
<title>@yield('title', 'Admin') · SMK Kosgoro</title>
@include('partials.head')
</head>
<body x-data="{ drawer: false }">

@include('partials.sidebar')

<div class="flex min-h-screen flex-col lg:pl-64">
    <header class="sticky top-0 z-30 border-b border-line bg-paper/85 backdrop-blur">
        <div class="flex h-16 items-center justify-between px-4 lg:px-10">
            <div class="flex items-center gap-3">
                <button @click="drawer = true" class="grid h-9 w-9 place-items-center rounded-lg text-maroon hover:bg-cream lg:hidden" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <p class="font-display text-lg font-semibold">@yield('title')</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-gray-500 sm:block">{{ auth()->user()->name }}</span>
                <span class="grid h-9 w-9 place-items-center rounded-full bg-maroon text-sm font-semibold text-white">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 pb-28 lg:px-10 lg:py-8 lg:pb-10">
        @include('partials.flash')
        @yield('content')
    </main>
</div>

@include('partials.bottom-nav-admin')
</body>
</html>