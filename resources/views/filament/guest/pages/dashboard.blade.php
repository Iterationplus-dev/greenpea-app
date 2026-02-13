<x-filament-panels::page>

    {{-- Welcome Header --}}
    <div class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 p-6 text-white shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="mt-1 text-sm text-white/80">
                    Manage your bookings, payments and wallet from here.
                </p>
            </div>
            <div class="flex gap-3">
                <a href="/"
                   class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-4 py-2 text-sm font-medium text-white backdrop-blur hover:bg-white/25 transition">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                    Browse Apartments
                </a>
                <a href="{{ url('/guest/bookings') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-primary-700 hover:bg-white/90 transition">
                    <x-heroicon-o-calendar-days class="h-4 w-4" />
                    My Bookings
                </a>
            </div>
        </div>
    </div>

    {{-- Pending Booking Intent Notice --}}
    @if (session()->has('booking.intent'))
        @php
            $intent = session('booking.intent');
            $intentApt = \App\Models\Apartment::with('property')->find($intent['apartment_id']);
        @endphp
        @if ($intentApt)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-amber-600" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">You have a pending booking</p>
                        <p class="text-xs text-amber-600">
                            {{ $intentApt->name }} in {{ $intentApt->property->city }}
                            &middot; {{ \Carbon\Carbon::parse($intent['start_date'])->format('M d') }} – {{ \Carbon\Carbon::parse($intent['end_date'])->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <a href="{{ url('/guest/continue-booking') }}"
                   class="inline-flex items-center gap-1 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 transition shrink-0">
                    Continue Booking
                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                </a>
            </div>
        @endif
    @endif

    {{-- Widgets --}}
    <x-filament-widgets::widgets
        :widgets="$this->getVisibleWidgets()"
        :columns="$this->getColumns()"
        :data="[...$this->getWidgetData()]"
    />

</x-filament-panels::page>
