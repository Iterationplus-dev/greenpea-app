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
                <span class="text-sm font-semibold text-gray-900">Review</span>
            </div>
            <div class="w-8 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 text-gray-400 text-xs font-bold">3</span>
                <span class="text-sm font-medium text-gray-400">Confirm</span>
            </div>
        </div>

        {{-- Header --}}
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                Review your booking
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Please confirm the details below before proceeding
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Left: Apartment Details (3 cols) --}}
            <div class="lg:col-span-3 space-y-5">

                {{-- Apartment Card --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    @if ($apartment->featuredImageUrl)
                        <img src="{{ $apartment->featuredImageUrl }}"
                             alt="{{ $apartment->name }}"
                             class="w-full h-52 object-cover">
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $apartment->name }}
                                </h3>
                                <div class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $apartment->property->city }}
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-600/10">
                                Available
                            </span>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 text-sm text-gray-500">
                            @if($apartment->bedrooms)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                    {{ $apartment->bedrooms }} {{ Str::plural('Bed', $apartment->bedrooms) }}
                                </span>
                            @endif
                            @if($apartment->bathrooms)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                    {{ $apartment->bathrooms }} {{ Str::plural('Bath', $apartment->bathrooms) }}
                                </span>
                            @endif
                            @if($apartment->square_feet)
                                <span>{{ number_format($apartment->square_feet) }} sqft</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Stay Dates Card --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Stay Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-gray-50 p-4 text-center">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Check-in</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($intent['start_date'])->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4 text-center">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Check-out</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($intent['end_date'])->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="inline-flex items-center gap-1 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $days }} {{ Str::plural('night', $days) }} stay
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right: Price Summary (2 cols) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 lg:sticky lg:top-24">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Price Summary</h4>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nightly rate</span>
                            <span class="font-medium text-gray-900">&#8358;{{ number_format($apartment->daily_price) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Duration</span>
                            <span class="font-medium text-gray-900">{{ $days }} {{ Str::plural('night', $days) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-primary-600">&#8358;{{ number_format($totalAmount) }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-filament::button
                            color="primary"
                            size="lg"
                            class="w-full"
                            tag="a"
                            href="{{ url('/guest/create-booking') }}"
                        >
                            Continue to Booking
                        </x-filament::button>

                        <p class="mt-3 text-center text-xs text-gray-400">
                            You'll confirm payment on the next step
                        </p>
                    </div>

                    {{-- Trust Signals --}}
                    <div class="mt-5 pt-5 border-t border-gray-100 space-y-2.5">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Verified property
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Secure payment
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            24/7 support
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament::page>
