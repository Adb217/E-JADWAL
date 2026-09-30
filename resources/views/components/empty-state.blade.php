@props(['icon' => 'fa-inbox', 'text' => 'Belum ada data.'])
<div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white/60 px-6 py-14 text-center">
    <span class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-full bg-cream text-maroon"><i class="fa-solid {{ $icon }}"></i></span>
    <p class="text-sm text-gray-500">{{ $text }}</p>
</div>