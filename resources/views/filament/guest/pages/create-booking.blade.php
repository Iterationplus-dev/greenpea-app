<x-filament::page>
    <div class="max-w-xl mx-auto space-y-6">

        <div class="text-center space-y-1">
            <h2 class="text-2xl font-bold">
                Complete your booking
            </h2>
            <p class="text-sm text-gray-500">
                {{ $apartment->name }} • {{ $apartment->property->city }}
            </p>
        </div>

        <form wire:submit.prevent="create" class="space-y-6">
            {{ $this->form }}

            <x-filament::button
                type="submit"
                size="lg"
                class="w-full"
            >
                Confirm Booking
            </x-filament::button>
        </form>

    </div>
</x-filament::page>
