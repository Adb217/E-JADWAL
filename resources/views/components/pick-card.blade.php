@props(['href', 'icon' => 'fa-folder', 'title', 'sub' => null, 'meta' => null])
<a href="{{ $href }}" class="card card-link flex items-center gap-4">
    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cream text-maroon"><i class="fa-solid {{ $icon }}"></i></span>
    <span class="min-w-0 flex-1">
        <span class="block font-semibold leading-snug">{{ $title }}</span>
        @if($sub)<span class="block text-sm text-gray-500">{{ $sub }}</span>@endif
    </span>
    @if($meta)<span class="chip whitespace-nowrap">{{ $meta }}</span>@endif
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
</a>