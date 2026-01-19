<x-filament::page>

    <!-- FULL SCREEN LOADING OVERLAY -->
    <div wire:loading.flex
        class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm transition-all duration-200">
        <div class="flex flex-col items-center text-white space-y-4 animate-pulse">
            <x-filament::loading-indicator class="w-14 h-14" />

            <span class="text-xl font-bold">
                Processing Booking...
            </span>

            <span class="text-sm opacity-80">
                Finalizing booking and payment
            </span>
        </div>
    </div>

    <!-- FORM CONTAINER -->
    <div wire:loading.class="opacity-40 pointer-events-none">
        <form wire:submit.prevent="submit">

            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4" wire:loading.attr="disabled">
                Complete Walk-In Booking
            </x-filament::button>

        </form>
    </div>

</x-filament::page>
