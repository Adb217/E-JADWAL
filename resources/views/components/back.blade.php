@props(['href', 'label' => 'Kembali'])
<a href="{{ $href }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-maroon hover:underline">
    <i class="fa-solid fa-arrow-left text-xs"></i>{{ $label }}
</a>