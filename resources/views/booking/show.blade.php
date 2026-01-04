@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Apartment Card --}}
        <div class="bg-white border rounded-lg overflow-hidden mb-6">
            @if ($apartment->images->count())
                <div class="grid grid-cols-3 gap-2 mb-6">

                    {{-- Featured image (large) --}}
                    <div class="col-span-3">
                        <img src="{{ $apartment->featuredImageUrl }}" class="w-full h-64 object-cover rounded"
                            alt="{{ $apartment->name }}">
                    </div>

                    {{-- Other images --}}
                    @foreach ($apartment->images->where('is_featured', false) as $image)
                        <img src="{{ $image->url }}" class="h-32 w-full object-cover rounded"
                            alt="{{ $image->alt_text ?? $apartment->name }}">
                    @endforeach

                </div>
            @else
                <img src="{{ asset('img/placeholder.jpg') }}" class="w-full h-64 object-cover rounded mb-6">
            @endif


            <div class="p-4">
                <h2 class="text-xl font-semibold">
                    {{ $apartment->name }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $apartment->property->city }}
                </p>

                <p class="mt-2 font-bold text-green-600">
                    ₦{{ number_format($apartment->price_per_month) }} / month
                </p>
            </div>
        </div>

        {{-- Booking Form --}}
        <form method="POST" action="{{ url('/book/' . $apartment->id) }}" x-data="{
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
            class="bg-white border rounded-lg p-4 space-y-4">
            @csrf

            <div>
                <label class="block text-sm mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" required class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm mb-1">Start Date</label>
                <input type="date" name="start_date" x-model="start" @change="calc" required
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm mb-1">End Date</label>
                <input type="date" name="end_date" x-model="end" @change="calc" required
                    class="w-full border rounded p-2">
            </div>

            <div class="bg-gray-100 rounded p-3">
                <p class="text-sm">Total Amount</p>
                <p class="text-lg font-bold text-green-600">
                    ₦<span x-text="total.toLocaleString()"></span>
                </p>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">
                Request Booking
            </button>

            <p class="text-xs text-gray-500 text-center">
                Booking will be reviewed before payment.
            </p>
        </form>

    </div>
@endsection
