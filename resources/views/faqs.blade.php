@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="bg-brand-600 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl text-white mb-3">Frequently Asked Questions</h1>
        <p class="text-white/70 text-base sm:text-lg max-w-xl mx-auto">
            Find answers to common questions about booking, payments, and staying with GreenPea.
        </p>
    </div>
</section>

<section class="py-12 sm:py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        @php
            $categories = [
                'Booking & Reservations' => [
                    [
                        'q' => 'How do I book an apartment?',
                        'a' => 'Browse our available apartments, select your preferred dates, and click "Book Now". If you don\'t have an account, you\'ll be prompted to create one. Once registered, you can review your booking details and proceed to payment.',
                    ],
                    [
                        'q' => 'What is the minimum stay duration?',
                        'a' => 'Our apartments are designed for shortlet stays. The minimum booking duration is typically one month, though this may vary by property. Check the individual apartment listing for specific details.',
                    ],
                    [
                        'q' => 'Can I cancel or modify my booking?',
                        'a' => 'Yes, you can manage your bookings from your guest dashboard. Cancellation policies vary depending on the property and how far in advance you cancel. Please review the cancellation terms before booking.',
                    ],
                    [
                        'q' => 'How far in advance should I book?',
                        'a' => 'We recommend booking at least 2 weeks in advance to secure your preferred apartment, especially during peak seasons. However, last-minute bookings are also accepted subject to availability.',
                    ],
                ],
                'Payments & Pricing' => [
                    [
                        'q' => 'What payment methods do you accept?',
                        'a' => 'We accept payments via Paystack, which supports bank transfers, debit/credit cards (Visa, Mastercard, Verve), and USSD. All transactions are processed securely.',
                    ],
                    [
                        'q' => 'Are there any hidden fees?',
                        'a' => 'No. The price displayed on each apartment listing is the monthly rate. All charges are shown clearly before you confirm your booking. There are no hidden fees or surprise costs.',
                    ],
                    [
                        'q' => 'Is a security deposit required?',
                        'a' => 'Some properties may require a refundable security deposit. This will be clearly stated during the booking process. Deposits are refunded after checkout, subject to property inspection.',
                    ],
                ],
                'Apartments & Amenities' => [
                    [
                        'q' => 'Are the apartments furnished?',
                        'a' => 'Yes, all GreenPea apartments are fully furnished and equipped with essential amenities including beds, kitchen appliances, Wi-Fi, and air conditioning. Specific amenities are listed on each apartment\'s page.',
                    ],
                    [
                        'q' => 'In which cities are apartments available?',
                        'a' => 'We currently have apartments in Abuja, Lagos, Port Harcourt, and Enugu. We are continuously expanding to more cities across Nigeria.',
                    ],
                    [
                        'q' => 'Are the properties verified?',
                        'a' => 'Absolutely. Every property listed on GreenPea is personally verified by our team. We inspect each apartment to ensure it meets our quality and safety standards before listing.',
                    ],
                ],
                'Account & Support' => [
                    [
                        'q' => 'How do I create an account?',
                        'a' => 'Click "List with Us" or "Create Free Account" on the homepage. You\'ll need to provide your name, email address, and create a password. You can also create an account during the booking process.',
                    ],
                    [
                        'q' => 'How can I contact support?',
                        'a' => 'You can reach us via WhatsApp for instant support, send an email to help@greenpea.ng, call us at +234 800 000 0000, or use our contact form. We respond within 24 hours.',
                    ],
                    [
                        'q' => 'I forgot my password. What do I do?',
                        'a' => 'Click "Sign In" and then "Forgot Password" on the login page. Enter your registered email address and we\'ll send you a password reset link.',
                    ],
                ],
            ];
        @endphp

        <div class="space-y-10">
            @foreach($categories as $category => $faqs)
                <div>
                    <h2 class="font-display text-lg sm:text-xl text-navy mb-4">{{ $category }}</h2>
                    <div class="space-y-3" x-data="{ open: null }">
                        @foreach($faqs as $index => $faq)
                            @php $key = Str::slug($category) . '-' . $index; @endphp
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                                <button
                                    type="button"
                                    @click="open = open === '{{ $key }}' ? null : '{{ $key }}'"
                                    class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left"
                                >
                                    <span class="text-sm font-medium text-navy">{{ $faq['q'] }}</span>
                                    <svg
                                        class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                                        :class="open === '{{ $key }}' && 'rotate-180'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div
                                    x-show="open === '{{ $key }}'"
                                    x-collapse
                                >
                                    <div class="px-5 pb-4 text-sm text-gray-500 leading-relaxed border-t border-gray-50 pt-3">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="mt-12 text-center bg-white rounded-2xl border border-gray-100 p-8">
            <h3 class="font-display text-lg text-navy mb-2">Still have questions?</h3>
            <p class="text-sm text-gray-500 mb-5">We're here to help. Reach out and we'll get back to you shortly.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Us
                </a>
                <a href="https://wa.me/23480?text=Hello%20GreenPea%2C%20I%20have%20a%20question."
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-medium rounded-full transition">
                    <svg class="w-4 h-4 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>

    </div>
</section>

@endsection
