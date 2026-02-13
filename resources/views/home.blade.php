@extends('layouts.app')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="relative min-h-[520px] sm:min-h-[560px] flex items-start overflow-hidden">
    {{-- Background Image --}}
    <div class="absolute inset-0">
        <img
            src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1920&q=80"
            alt="Luxury apartment interior"
            class="w-full h-full object-cover"
        >
        <div class="hero-overlay absolute inset-0"></div>
    </div>

    {{-- Hero Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 w-full pt-6 pb-10">
        <div class="max-w-2xl">
            <h1 class="font-display text-2xl sm:text-3xl lg:text-5xl text-white leading-tight mb-2">
                Find your perfect stay in Nigeria
            </h1>
            <p class="text-white/80 text-base sm:text-lg mb-8 leading-relaxed">
                Browse verified shortlet apartments in Abuja, Lagos, Port Harcourt & Enugu. Flexible monthly stays with transparent pricing.
            </p>
        </div>

        {{-- ===== SEARCH FORM (Hotels.com style) ===== --}}
        <div class="search-glass rounded-2xl p-4 sm:p-6 max-w-4xl"
             x-data="{
                 city: '',
                 cityQuery: '',
                 cityOpen: false,
                 guests: 1,
                 guestsOpen: false,
                 fpIn: null,
                 fpOut: null,
                 cities: [
                     { name: 'Abuja', desc: 'The capital city' },
                     { name: 'Lagos', desc: 'The business hub' },
                     { name: 'Port Harcourt', desc: 'Garden city' },
                     { name: 'Enugu', desc: 'Coal city' },
                 ],
                 get filteredCities() {
                     if (!this.cityQuery.trim()) return this.cities;
                     const q = this.cityQuery.toLowerCase();
                     return this.cities.filter(c => c.name.toLowerCase().includes(q));
                 },
                 selectCity(name) {
                     this.city = name;
                     this.cityQuery = name || '';
                     this.cityOpen = false;
                 },
                 openCitySearch() {
                     this.cityOpen = true;
                     this.guestsOpen = false;
                     this.cityQuery = '';
                 },
                 initDates() {
                     this.fpIn = window.flatpickr(this.$refs.checkinInput, {
                         dateFormat: 'M d, Y',
                         minDate: 'today',
                         disableMobile: true,
                         monthSelectorType: 'static',
                         onChange: (dates) => {
                             if (this.fpOut && dates[0]) {
                                 this.fpOut.set('minDate', dates[0]);
                             }
                         },
                     });
                     this.fpOut = window.flatpickr(this.$refs.checkoutInput, {
                         dateFormat: 'M d, Y',
                         minDate: 'today',
                         disableMobile: true,
                         monthSelectorType: 'static',
                     });
                 },
                 search() {
                     if (this.city) {
                         const slug = this.city.toLowerCase().replace(/\s+/g, '-');
                         document.getElementById('city-' + slug)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     } else {
                         document.getElementById('apartments')?.scrollIntoView({ behavior: 'smooth' });
                     }
                 }
             }"
             x-init="initDates()"
        >
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Where to (Searchable Dropdown) --}}
                <div class="relative" @click.outside="cityOpen = false">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Where to</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input
                            type="text"
                            x-model="cityQuery"
                            @focus="openCitySearch()"
                            @input="cityOpen = true"
                            placeholder="Search city..."
                            class="w-full pl-10 pr-9 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                        >
                        {{-- Clear button --}}
                        <button
                            type="button"
                            x-show="cityQuery"
                            @click="selectCity(''); $refs.cityInput?.focus()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Dropdown Panel --}}
                    <div
                        x-show="cityOpen"
                        x-transition
                        class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
                        style="display: none;"
                    >
                        {{-- All Cities option (shown only when no search query) --}}
                        <template x-if="!cityQuery.trim()">
                            <div>
                                <button type="button" @click="selectCity('')"
                                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-left"
                                        :class="!city && 'bg-brand-50'">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">All Cities</p>
                                        <p class="text-xs text-gray-400">Browse everywhere</p>
                                    </div>
                                </button>
                                <div class="border-t border-gray-50"></div>
                            </div>
                        </template>

                        {{-- Filtered city options --}}
                        <template x-for="c in filteredCities" :key="c.name">
                            <button type="button" @click="selectCity(c.name)"
                                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-left"
                                    :class="city === c.name && 'bg-brand-50'">
                                <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800" x-text="c.name"></p>
                                    <p class="text-xs text-gray-400" x-text="c.desc"></p>
                                </div>
                                <svg x-show="city === c.name" class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </template>

                        {{-- No results --}}
                        <div x-show="cityQuery.trim() && filteredCities.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                            No cities match your search
                        </div>
                    </div>
                </div>

                {{-- Check-in (Flatpickr) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Check-in</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input
                            type="text"
                            x-ref="checkinInput"
                            readonly
                            placeholder="Select date"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent cursor-pointer"
                        >
                    </div>
                </div>

                {{-- Check-out (Flatpickr) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Check-out</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input
                            type="text"
                            x-ref="checkoutInput"
                            readonly
                            placeholder="Select date"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent cursor-pointer"
                        >
                    </div>
                </div>

                {{-- Guests + Search --}}
                <div class="flex gap-2">
                    {{-- Guests (Custom Dropdown) --}}
                    <div class="flex-1 relative" @click.outside="guestsOpen = false">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Guests</label>
                        <button
                            type="button"
                            @click="guestsOpen = !guestsOpen; cityOpen = false"
                            class="w-full flex items-center gap-2 pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-left relative cursor-pointer"
                        >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span x-text="guests + (guests === 1 ? ' Guest' : ' Guests')" class="text-gray-900"></span>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition" :class="guestsOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Guests Dropdown --}}
                        <div
                            x-show="guestsOpen"
                            x-transition
                            class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 p-4"
                            style="display: none;"
                        >
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">Guests</span>
                                <div class="flex items-center gap-3">
                                    <button type="button"
                                            @click.stop="guests > 1 && guests--"
                                            class="w-9 h-9 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-600 hover:border-brand-500 hover:text-brand-600 transition"
                                            :class="guests <= 1 && 'opacity-30 cursor-not-allowed'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="text-base font-bold w-6 text-center" x-text="guests"></span>
                                    <button type="button"
                                            @click.stop="guests < 7 && guests++"
                                            class="w-9 h-9 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-600 hover:border-brand-500 hover:text-brand-600 transition"
                                            :class="guests >= 7 && 'opacity-30 cursor-not-allowed'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="guestsOpen = false"
                                    class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
                                Done
                            </button>
                        </div>
                    </div>

                    {{-- Search Button --}}
                    <div class="flex items-end">
                        <button
                            type="button"
                            @click="search()"
                            class="bg-brand-600 hover:bg-brand-700 text-white p-3 rounded-xl transition flex items-center justify-center"
                            title="Search"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== WHY GREENPEA STRIP ===== --}}
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-700">Verified Properties</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-700">Transparent Pricing</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-700">Flexible Stays</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-700">24/7 Support</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== EXPLORE CITIES ===== --}}
<section id="cities" class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="mb-8">
            <h2 class="font-display text-2xl sm:text-3xl text-navy mb-2">Explore our cities</h2>
            <p class="text-gray-500">Discover apartments in Nigeria's top destinations</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $cityImages = [
                    'Abuja' => 'https://images.unsplash.com/photo-1618828665011-0abd973f7bb8?auto=format&fit=crop&w=600&q=80',
                    'Lagos' => 'https://images.unsplash.com/photo-1618828665011-0abd973f7bb8?auto=format&fit=crop&w=600&q=80',
                    'Port Harcourt' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=600&q=80',
                    'Enugu' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80',
                ];
                $cityDescriptions = [
                    'Abuja' => 'The capital city',
                    'Lagos' => 'The business hub',
                    'Port Harcourt' => 'Garden city',
                    'Enugu' => 'Coal city',
                ];
            @endphp

            @foreach(['Abuja', 'Lagos', 'Port Harcourt', 'Enugu'] as $cityName)
                <a href="#city-{{ Str::slug($cityName) }}"
                   class="group relative rounded-2xl overflow-hidden aspect-[4/3]"
                   onclick="event.preventDefault(); document.getElementById('city-{{ Str::slug($cityName) }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                >
                    <img
                        src="{{ $cityImages[$cityName] }}"
                        alt="{{ $cityName }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-4">
                        <h3 class="text-white font-semibold text-lg">{{ $cityName }}</h3>
                        <p class="text-white/70 text-sm">{{ $cityDescriptions[$cityName] }}</p>
                        <p class="text-white/50 text-xs mt-1">
                            {{ isset($apartments[$cityName]) ? $apartments[$cityName]->count() : 0 }} {{ Str::plural('apartment', isset($apartments[$cityName]) ? $apartments[$cityName]->count() : 0) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== APARTMENTS LISTINGS ===== --}}
<section id="apartments" class="pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6"
         x-data="{ activeCity: 'all' }"
    >
        {{-- Section Header + City Tabs --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display text-2xl sm:text-3xl text-navy mb-2">Available apartments</h2>
                <p class="text-gray-500">Hand-picked shortlet apartments for your next stay</p>
            </div>

            {{-- City Filter Pills --}}
            <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1">
                <button
                    @click="activeCity = 'all'"
                    :class="activeCity === 'all' ? 'city-pill-active' : 'bg-white text-gray-600 border border-gray-200 hover:border-gray-300'"
                    class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition"
                >
                    All Cities
                </button>
                @foreach(['Abuja', 'Lagos', 'Port Harcourt', 'Enugu'] as $cityName)
                    <button
                        @click="activeCity = '{{ $cityName }}'"
                        :class="activeCity === '{{ $cityName }}' ? 'city-pill-active' : 'bg-white text-gray-600 border border-gray-200 hover:border-gray-300'"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition"
                    >
                        {{ $cityName }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Apartment Grids by City --}}
        @foreach($apartments as $cityName => $list)
            <div
                id="city-{{ Str::slug($cityName) }}"
                x-show="activeCity === 'all' || activeCity === '{{ $cityName }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mb-10 last:mb-0"
            >
                {{-- City Header (only shown in "All" mode) --}}
                <div x-show="activeCity === 'all'" class="flex items-center gap-3 mb-5">
                    <h3 class="text-lg font-semibold text-navy">{{ $cityName }}</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                        {{ $list->count() }} {{ Str::plural('apartment', $list->count()) }}
                    </span>
                </div>

                {{-- Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @forelse($list as $apartment)
                        <a href="{{ url('/apartments/'.$apartment->slug) }}" class="property-card bg-white rounded-2xl overflow-hidden border border-gray-100 block">
                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img
                                    src="{{ $apartment->featuredImageUrl ?? asset('img/placeholder.jpg') }}"
                                    alt="{{ $apartment->name }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                                {{-- Badge --}}
                                @if($apartment->is_available)
                                    <span class="absolute top-3 left-3 bg-brand-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Available
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="p-4">
                                <h4 class="font-semibold text-navy text-base mb-1 truncate">
                                    {{ $apartment->name }}
                                </h4>

                                <div class="flex items-center gap-1 text-gray-400 text-sm mb-3">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $apartment->property->city }}
                                </div>

                                {{-- Amenities --}}
                                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                    @if($apartment->bedrooms)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                            {{ $apartment->bedrooms }} {{ Str::plural('bed', $apartment->bedrooms) }}
                                        </span>
                                    @endif
                                    @if($apartment->bathrooms)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                            {{ $apartment->bathrooms }} {{ Str::plural('bath', $apartment->bathrooms) }}
                                        </span>
                                    @endif
                                    @if($apartment->square_feet)
                                        <span>{{ $apartment->square_feet }} sqft</span>
                                    @endif
                                </div>

                                {{-- Price + CTA --}}
                                <div class="flex items-end justify-between pt-3 border-t border-gray-50">
                                    <div>
                                        <span class="text-lg font-bold text-brand-600">
                                            &#8358;{{ number_format($apartment->monthly_price) }}
                                        </span>
                                        <span class="text-xs text-gray-400"> / month</span>
                                    </div>
                                    <span class="text-xs font-medium text-brand-600 group-hover:underline">
                                        View &rarr;
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">No apartments available in {{ $cityName }} yet.</p>
                            <p class="text-gray-300 text-xs mt-1">Check back soon for new listings.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach

        {{-- Empty state if no apartments at all --}}
        @if($apartments->isEmpty())
            <div class="text-center py-16">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No apartments listed yet</h3>
                <p class="text-gray-400">We're adding new properties soon. Check back later!</p>
            </div>
        @endif
    </div>
</section>

{{-- ===== CTA SECTION ===== --}}
<section class="bg-navy py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="font-display text-2xl sm:text-3xl text-white mb-4">Ready to find your next stay?</h2>
        <p class="text-white/60 mb-8 max-w-lg mx-auto">
            Create a free account to book apartments, manage your stays, and get exclusive deals.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/guest/register"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Create Free Account
            </a>
            <a href="#apartments"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-white/20 hover:border-white/40 text-white font-medium rounded-full transition"
               onclick="event.preventDefault(); document.getElementById('apartments')?.scrollIntoView({ behavior: 'smooth' })">
                Browse Apartments
            </a>
        </div>
    </div>
</section>

@endsection
