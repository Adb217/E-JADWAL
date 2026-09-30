@if(session('success'))
<div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
    <i class="fa-solid fa-circle-check mt-0.5"></i><span>{{ session('success') }}</span>
</div>
@endif
@if($errors->any())
<div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <p class="mb-1 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1.5"></i>Ada isian yang perlu diperbaiki</p>
    <ul class="list-disc space-y-0.5 pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif