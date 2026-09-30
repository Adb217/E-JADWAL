@props(['title', 'sub' => null, 'add' => null, 'search' => null])
<div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <h1 class="font-display text-2xl font-semibold tracking-tight lg:text-3xl">{{ $title }}</h1>
        @if($sub)<p class="mt-1 max-w-xl text-sm text-gray-500">{{ $sub }}</p>@endif
    </div>
    <div class="flex flex-col gap-3 sm:flex-row">
        @if($search)
        <form method="GET" class="relative flex-1 sm:w-72">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
            <input name="q" value="{{ request('q') }}" placeholder="{{ $search }}" class="inp !mt-0 pl-10">
        </form>
        @endif
        @if($add)
        <button type="button" @click="add()" class="btn"><i class="fa-solid fa-plus"></i>{{ $add }}</button>
        @endif
    </div>
</div>