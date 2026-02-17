@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="/" class="hover:text-brand-600 transition">Home</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="/#apartments" class="hover:text-brand-600 transition">{{ $apartment->property->city }}</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600">{{ $apartment->name }}</span>
    </nav>

    {{-- Apartment Images --}}
    @if($apartment->images->count())
        @php
            $allImages = $apartment->images->sortByDesc('is_featured')->values();
        @endphp

        {{-- Mobile Gallery (swipeable carousel) --}}
        <div class="md:hidden mb-8 rounded-2xl overflow-hidden"
             x-data="{
                 current: 0,
                 total: {{ $allImages->count() }},
                 touchStartX: 0,
                 touchEndX: 0,
                 next() { this.current = (this.current + 1) % this.total },
                 prev() { this.current = (this.current - 1 + this.total) % this.total },
                 handleSwipe() {
                     const diff = this.touchStartX - this.touchEndX;
                     if (Math.abs(diff) > 50) { diff > 0 ? this.next() : this.prev() }
                 }
             }"
             @touchstart="touchStartX = $event.changedTouches[0].screenX"
             @touchend="touchEndX = $event.changedTouches[0].screenX; handleSwipe()"
        >
            <div class="relative">
                @foreach($allImages as $index => $image)
                    <img
                        x-show="current === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        src="{{ $image->mobile_url }}"
                        alt="{{ $image->alt_text ?? $apartment->name }}"
                        class="w-full h-72 object-cover"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        {{ $index !== 0 ? 'style=display:none' : '' }}
                    >
                @endforeach
{{-- check --}}
                {{-- Navigation Arrows --}}
                @if($allImages->count() > 1)
                    <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 backdrop-blur flex items-center justify-center shadow hover:bg-white transition">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 backdrop-blur flex items-center justify-center shadow hover:bg-white transition">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    {{-- Dot Indicators --}}
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5">
                        @foreach($allImages as $index => $image)
                            <button
                                @click="current = {{ $index }}"
                                class="w-2 h-2 rounded-full transition"
                                :class="current === {{ $index }} ? 'bg-white w-4' : 'bg-white/50'"
                            ></button>
                        @endforeach
                    </div>

                    {{-- Counter --}}
                    <span class="absolute top-3 right-3 bg-black/50 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur"
                          x-text="(current + 1) + ' / {{ $allImages->count() }}'">
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop Grid --}}
        <div class="hidden md:grid md:grid-cols-4 gap-2 mb-8 rounded-2xl overflow-hidden">
            {{-- Featured Image --}}
            <div class="md:col-span-2 md:row-span-2">
                <img
                    src="{{ $apartment->featuredImageUrl }}"
                    alt="{{ $apartment->name }}"
                    class="w-full h-full min-h-100 object-cover"
                >
            </div>

            {{-- Gallery Images --}}
            @foreach($apartment->images->where('is_featured', false)->take(4) as $image)
                <div>
                    <img
                        src="{{ $image->url }}"
                        alt="{{ $image->alt_text ?? $apartment->name }}"
                        class="w-full h-49.5 object-cover"
                        loading="lazy"
                    >
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl overflow-hidden mb-8">
            <img
                src="{{ asset('img/placeholder.jpg') }}"
                class="w-full h-72 object-cover"
                alt="{{ $apartment->name }}"
            >
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Apartment Info --}}
        <div class="lg:col-span-2">
            {{-- Title & Location --}}
            <div class="mb-6">
                <h1 class="font-display text-2xl sm:text-3xl text-navy mb-2">
                    {{ $apartment->name }}
                </h1>
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm">{{ $apartment->property->city }}</span>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                @if($apartment->bedrooms)
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <svg class="w-5 h-5 text-brand-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                        <p class="text-lg font-bold text-navy">{{ $apartment->bedrooms }}</p>
                        <p class="text-xs text-gray-400">{{ Str::plural('Bedroom', $apartment->bedrooms) }}</p>
                    </div>
                @endif
                @if($apartment->bathrooms)
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <svg class="w-5 h-5 text-brand-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                        <p class="text-lg font-bold text-navy">{{ $apartment->bathrooms }}</p>
                        <p class="text-xs text-gray-400">{{ Str::plural('Bathroom', $apartment->bathrooms) }}</p>
                    </div>
                @endif
                @if($apartment->floor)
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <svg class="w-5 h-5 text-brand-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <p class="text-lg font-bold text-navy">{{ $apartment->floor }}</p>
                        <p class="text-xs text-gray-400">Floor</p>
                    </div>
                @endif
                @if($apartment->square_feet)
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <svg class="w-5 h-5 text-brand-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <p class="text-lg font-bold text-navy">{{ number_format($apartment->square_feet) }}</p>
                        <p class="text-xs text-gray-400">Sq. Ft.</p>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($apartment->description)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-navy mb-3">About this apartment</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {!! $apartment->description !!}
                    </p>
                </div>
            @endif

            {{-- Amenities --}}
            @if($apartment->amenities->count())
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-navy mb-4">What's included</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($apartment->amenities as $amenity)
                            <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                                @if($amenity->icon)
                                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                        <x-dynamic-component
                                            :component="$amenity->icon"
                                            class="w-4 h-4 text-brand-600"
                                        />
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-gray-700">{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Booking Card --}}
        <div class="lg:col-span-1">
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-24 shadow-sm"
                x-data="{
                    start: '',
                    end: '',
                    startISO: '',
                    endISO: '',
                    price: {{ (int) $apartment->daily_price }},
                    total: {{ (int) $apartment->daily_price }},
                    fpIn: null,
                    fpOut: null,
                    calc() {
                        if (!this.startISO || !this.endISO) {
                            this.total = this.price
                            return
                        }
                        let s = new Date(this.startISO)
                        let e = new Date(this.endISO)
                        let days = Math.round((e - s) / (1000 * 60 * 60 * 24))
                        this.total = Math.max(days, 1) * this.price
                    },
                    initDates() {
                        this.fpIn = window.flatpickr(this.$refs.startDate, {
                            dateFormat: 'M d, Y',
                            altInput: false,
                            minDate: 'today',
                            disableMobile: true,
                            monthSelectorType: 'static',
                            onChange: (dates, str) => {
                                this.start = str;
                                this.startISO = dates[0] ? dates[0].toISOString().split('T')[0] : '';
                                this.$refs.startDateHidden.value = this.startISO;
                                if (this.fpOut) { this.fpOut.set('minDate', dates[0]); }
                                this.calc();
                            },
                        });
                        this.fpOut = window.flatpickr(this.$refs.endDate, {
                            dateFormat: 'M d, Y',
                            altInput: false,
                            minDate: 'today',
                            disableMobile: true,
                            monthSelectorType: 'static',
                            onChange: (dates, str) => {
                                this.end = str;
                                this.endISO = dates[0] ? dates[0].toISOString().split('T')[0] : '';
                                this.$refs.endDateHidden.value = this.endISO;
                                this.calc();
                            },
                        });
                    }
                }"
                x-init="initDates()"
            >
                {{-- Price --}}
                <div class="mb-5">
                    <span class="text-2xl font-bold text-navy">
                        &#8358;{{ number_format($apartment->daily_price) }}
                    </span>
                    <span class="text-sm text-gray-400"> / night</span>
                </div>

                <form
                    method="POST"
                    action="{{ route('booking.intent', $apartment) }}"
                    class="space-y-4"
                >
                    @csrf

                    {{-- Check-in --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Check-in</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input
                                type="text"
                                x-ref="startDate"
                                readonly
                                required
                                placeholder="Select date"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent cursor-pointer"
                            >
                            <input type="hidden" name="start_date" x-ref="startDateHidden">
                        </div>
                    </div>

                    {{-- Check-out --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Check-out</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input
                                type="text"
                                x-ref="endDate"
                                readonly
                                required
                                placeholder="Select date"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent cursor-pointer"
                            >
                            <input type="hidden" name="end_date" x-ref="endDateHidden">
                        </div>
                    </div>

                    {{-- Estimated Total --}}
                    <div class="bg-brand-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Estimated Total</span>
                            <span class="text-xl font-bold text-brand-700">
                                &#8358;<span x-text="total.toLocaleString()"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-3.5 rounded-full transition text-sm"
                    >
                        Continue Booking
                    </button>

                    <p class="text-xs text-gray-400 text-center leading-relaxed">
                        You'll be asked to sign in or create an account to complete your booking.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
