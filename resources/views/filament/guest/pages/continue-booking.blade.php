<x-filament::page>
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="text-center text-xs text-gray-400">
            Step 2 of 3 • Review booking
        </div>

        {{-- Header --}}
        <div class="text-center space-y-1">
            <h2 class="text-2xl font-bold tracking-tight">
                Review your booking
            </h2>
            <p class="text-sm text-gray-500">
                Confirm the details below to continue
            </p>
        </div>

        @if ($apartment->featuredImageUrl)
            <img src="{{ $apartment->featuredImageUrl }}" class="w-full h-48 object-cover rounded-lg"
                alt="{{ $apartment->name }}">
        @endif

        {{-- Booking Summary Card --}}
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">

            {{-- Apartment --}}
            <div class="p-5 space-y-3">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $apartment->name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $apartment->property->city }}
                        </p>
                    </div>

                    <span
                        class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                        Available
                    </span>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">Check-in</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($intent['start_date'])->toFormattedDateString() }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">Check-out</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($intent['end_date'])->toFormattedDateString() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t"></div>

            {{-- CTA --}}
            <div class="p-5 bg-gray-50">
                <x-filament::button color="primary" size="lg" class="w-full" tag="a"
                    href="{{ url('/guest/create-booking') }}">
                    Continue to Booking
                </x-filament::button>

                @if (!session('booking.intent'))
                    <x-filament::button disabled class="w-full">
                        Booking expired
                    </x-filament::button>
                @endif

                <p class="mt-3 text-center text-xs text-gray-500">
                    You’ll confirm payment on the next step
                </p>
            </div>
        </div>

    </div>
</x-filament::page>
