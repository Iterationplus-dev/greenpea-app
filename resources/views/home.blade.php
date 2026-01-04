@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8"
     x-data="{ city: 'Abuja' }"
>

    {{-- City Selector --}}
    <div class="flex gap-3 mb-6">
        <button
            @click="city = 'Abuja'"
            :class="city === 'Abuja' ? 'bg-green-600 text-white' : 'bg-gray-100'"
            class="px-4 py-2 rounded"
        >
            Abuja
        </button>

        <button
            @click="city = 'Lagos'"
            :class="city === 'Lagos' ? 'bg-green-600 text-white' : 'bg-gray-100'"
            class="px-4 py-2 rounded"
        >
            Lagos
        </button>

        <button
            @click="city = 'Port Harcourt'"
            :class="city === 'Port Harcourt' ? 'bg-green-600 text-white' : 'bg-gray-100'"
            class="px-4 py-2 rounded"
        >
            Port Harcourt
        </button>
    </div>

    {{-- Apartment Grid --}}
    @foreach($apartments as $cityName => $list)
        <div
            x-show="city === '{{ $cityName }}'"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            @forelse($list as $apartment)
                <div class="bg-white border rounded-lg overflow-hidden">

                    <img
                        src="{{ $apartment->featuredImageUrl ?? asset('img/placeholder.jpg') }}"
                        class="w-full h-48 object-cover"
                        alt="{{ $apartment->name }}"
                    >

                    <div class="p-4">
                        <h3 class="font-semibold text-lg">
                            {{ $apartment->name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $apartment->property->city }}
                        </p>

                        <p class="mt-2 font-bold text-green-600">
                            ₦{{ number_format($apartment->monthly_price) }} / month
                        </p>

                        <a
                            href="{{ url('/apartments/'.$apartment->id) }}"
                            class="block mt-4 bg-green-600 text-white text-center py-2 rounded"
                        >
                            View & Book
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-full">
                    No apartments available in this city.
                </p>
            @endforelse
        </div>
    @endforeach

</div>
@endsection
