@props(['item', 'destroy', 'msg' => 'Hapus data ini?'])
<div class="flex justify-end gap-1">
    <button type="button" @click="edit(@js($item))" title="Edit" aria-label="Edit"
            class="grid h-8 w-8 place-items-center rounded-lg text-gray-500 transition hover:bg-cream hover:text-maroon">
        <i class="fa-solid fa-pen-to-square text-sm"></i>
    </button>
    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm(@js($msg))">
        @csrf @method('DELETE')
        <button title="Hapus" aria-label="Hapus"
                class="grid h-8 w-8 place-items-center rounded-lg text-gray-500 transition hover:bg-red-50 hover:text-red-600">
            <i class="fa-solid fa-trash text-sm"></i>
        </button>
    </form>
</div>