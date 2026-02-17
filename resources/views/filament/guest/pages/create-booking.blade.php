<x-filament::page>
    <div class="max-w-3xl mx-auto">

        {{-- Progress Steps --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold">1</span>
                <span class="text-sm font-medium text-gray-500">Select</span>
            </div>
            <div class="w-8 h-px bg-primary-300"></div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold">2</span>
                <span class="text-sm font-medium text-gray-500">Review</span>
            </div>
            <div class="w-8 h-px bg-primary-300"></div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-primary-600 text-white text-xs font-bold">3</span>
                <span class="text-sm font-semibold text-gray-900">Confirm</span>
            </div>
        </div>

        {{-- Header --}}
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                Complete your booking
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Confirm your details and finalize your reservation
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Left: Form (3 cols) --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
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
            </div>

            {{-- Right: Booking Summary (2 cols) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden lg:sticky lg:top-24">

                    {{-- Apartment Thumbnail --}}
                    @if ($apartment->featuredImageUrl)
                        <img src="{{ $apartment->featuredImageUrl }}"
                             alt="{{ $apartment->name }}"
                             class="w-full h-36 object-cover">
                    @endif

                    <div class="p-5 space-y-4">
                        {{-- Apartment Info --}}
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $apartment->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $apartment->property->city }}</p>
                        </div>

                        {{-- Dates --}}
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs text-gray-400 mb-0.5">Check-in</p>
                                <p class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($intent['start_date'])->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs text-gray-400 mb-0.5">Check-out</p>
                                <p class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($intent['end_date'])->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">&#8358;{{ number_format($apartment->daily_price) }} x {{ $days }} {{ Str::plural('night', $days) }}</span>
                                <span class="font-medium text-gray-900">&#8358;{{ number_format($totalAmount) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-100 pt-2">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-primary-600">&#8358;{{ number_format($totalAmount) }}</span>
                            </div>
                        </div>

                        {{-- Back Link --}}
                        <div class="text-center pt-2">
                            <a href="{{ url('/guest/continue-booking') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                &larr; Back to review
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament::page>
