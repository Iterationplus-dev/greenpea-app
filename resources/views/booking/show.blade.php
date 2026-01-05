@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Apartment Images --}}
    @if($apartment->images->count())
        <div class="grid grid-cols-3 gap-2 mb-8">
            {{-- Featured --}}
            <div class="col-span-3">
                <img
                    src="{{ $apartment->featuredImageUrl }}"
                    alt="{{ $apartment->name }}"
                    class="w-full h-72 object-cover rounded"
                >
            </div>

            {{-- Gallery --}}
            @foreach($apartment->images->where('is_featured', false) as $image)
                <img
                    src="{{ $image->url }}"
                    alt="{{ $image->alt_text ?? $apartment->name }}"
                    class="h-32 w-full object-cover rounded"
                >
            @endforeach
        </div>
    @else
        <img
            src="{{ asset('img/placeholder.jpg') }}"
            class="w-full h-72 object-cover rounded mb-8"
        >
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Apartment Info --}}
        <div class="md:col-span-2">
            <h1 class="text-2xl font-semibold mb-1">
                {{ $apartment->name }}
            </h1>

            <p class="text-gray-500 mb-4">
                {{ $apartment->property->city }}
            </p>

            <p class="text-gray-700 leading-relaxed mb-6">
                {{ $apartment->description }}
            </p>

            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div>Bedrooms: {{ $apartment->bedrooms }}</div>
                <div>Bathrooms: {{ $apartment->bathrooms }}</div>
                <div>Floor: {{ $apartment->floor ?? '—' }}</div>
                <div>Size: {{ $apartment->square_feet ?? '—' }} sqft</div>
            </div>
        </div>

        {{-- Booking Card --}}
        <div
            class="bg-white border rounded-lg p-4"
            x-data="{
                start: '',
                end: '',
                price: {{ (int) $apartment->monthly_price }},
                total: {{ (int) $apartment->monthly_price }},
                calc() {
                    if (!this.start || !this.end) {
                        this.total = this.price
                        return
                    }

                    let s = new Date(this.start)
                    let e = new Date(this.end)

                    let months =
                        (e.getFullYear() - s.getFullYear()) * 12 +
                        (e.getMonth() - s.getMonth())

                    this.total = Math.max(months, 1) * this.price
                }
            }"
        >
            <p class="text-lg font-semibold mb-2">
                ₦{{ number_format($apartment->monthly_price) }} / month
            </p>

            <form
                method="POST"
                action="{{ route('booking.intent', $apartment) }}"
                class="space-y-4"
            >
                @csrf

                <div>
                    <label class="block text-sm mb-1">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        x-model="start"
                        @change="calc"
                        required
                        class="w-full border rounded p-2"
                    >
                </div>

                <div>
                    <label class="block text-sm mb-1">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        x-model="end"
                        @change="calc"
                        required
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="bg-gray-100 rounded p-3">
                    <p class="text-sm text-gray-600">Estimated Total</p>
                    <p class="text-lg font-bold text-green-600">
                        ₦<span x-text="total.toLocaleString()"></span>
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700"
                >
                    Continue Booking
                </button>

                <p class="text-xs text-gray-500 text-center">
                    You’ll be asked to log in or register to complete your booking.
                </p>
            </form>
        </div>

    </div>
</div>
@endsection

