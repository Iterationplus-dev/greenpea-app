
{{-- <div>
    <input type="file" multiple wire:model="state" class="block w-full">

    <div class="grid grid-cols-3 gap-2 mt-4">
        @foreach($getRecord()?->images ?? [] as $image)
            <div class="relative">
                <img src="{{ $image->url }}" class="rounded h-24 w-full object-cover">

                @if($image->is_featured)
                    <span class="absolute top-1 left-1 bg-green-600 text-white text-xs px-2 rounded">
                        Featured
                    </span>
                @endif
            </div>
        @endforeach
    </div>
</div> --}}

<div
    x-data="{ files: [] }"
    x-on:change="
        files = Array.from($event.target.files);
        $wire.set('{{ $getStatePath() }}', files);
    "
>
    <input
        type="file"
        multiple
        class="block w-full text-sm text-gray-600
               file:mr-4 file:py-2 file:px-4
               file:rounded file:border-0
               file:text-sm file:bg-green-50
               file:text-green-700
               hover:file:bg-green-100"
    />

    <template x-if="files.length">
        <p class="mt-2 text-xs text-gray-500">
            <span x-text="files.length"></span> file(s) selected
        </p>
    </template>
</div>


