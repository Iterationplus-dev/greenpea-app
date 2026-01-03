
<div>
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
</div>

