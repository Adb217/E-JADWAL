@props(['title', 'store', 'update'])
<div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
     class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center sm:p-4">
    <div class="absolute inset-0 bg-ink/50" @click="open = false"></div>
    <form method="POST" :action="editing ? '{{ $update }}'.replace('_id_', editing) : '{{ $store }}'"
          class="relative flex max-h-[90vh] w-full flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:max-w-lg sm:rounded-2xl">
        @csrf
        <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>

        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 class="font-display text-lg font-semibold" x-text="(editing ? 'Edit ' : 'Tambah ') + '{{ $title }}'"></h2>
            <button type="button" @click="open = false" class="grid h-8 w-8 place-items-center rounded-lg text-gray-500 hover:bg-cream hover:text-maroon" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="space-y-4 overflow-y-auto p-5">{{ $slot }}</div>

        <div class="flex justify-end gap-3 border-t border-line bg-paper px-5 py-4">
            <button type="button" @click="open = false" class="btn-ghost">Batal</button>
            <button class="btn"><i class="fa-solid fa-floppy-disk"></i>Simpan</button>
        </div>
    </form>
</div>