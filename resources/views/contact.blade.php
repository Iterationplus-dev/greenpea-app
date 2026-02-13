@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="bg-brand-600 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl text-white mb-3">Contact Us</h1>
        <p class="text-white/70 text-base sm:text-lg max-w-xl mx-auto">
            Have a question or need help with a booking? We'd love to hear from you.
        </p>
    </div>
</section>

<section class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Contact Info Cards --}}
            <div class="lg:col-span-1 space-y-5">
                {{-- Phone --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-navy mb-1">Phone</h3>
                    <p class="text-sm text-gray-500">Mon - Sat, 8am - 6pm WAT</p>
                    <a href="tel:+2348000000000" class="text-sm text-brand-600 font-medium mt-2 inline-block hover:underline">
                        +234 800 000 0000
                    </a>
                </div>

                {{-- Email --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-navy mb-1">Email</h3>
                    <p class="text-sm text-gray-500">We reply within 24 hours</p>
                    <a href="mailto:help@greenpea.ng" class="text-sm text-brand-600 font-medium mt-2 inline-block hover:underline">
                        help@greenpea.ng
                    </a>
                </div>

                {{-- WhatsApp --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 rounded-full bg-[#25D366]/10 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-navy mb-1">WhatsApp</h3>
                    <p class="text-sm text-gray-500">Chat with us instantly</p>
                    <a href="https://wa.me/23480?text=Hello%20GreenPea%2C%20I%20need%20help%20with%20booking%20an%20apartment."
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-sm text-[#25D366] font-medium mt-2 inline-block hover:underline">
                        Start a chat
                    </a>
                </div>

                {{-- Office --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-navy mb-1">Office</h3>
                    <p class="text-sm text-gray-500">
                        Abuja, Nigeria
                    </p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
                    <h2 class="font-display text-xl text-navy mb-1">Send us a message</h2>
                    <p class="text-sm text-gray-500 mb-6">Fill in the form below and we'll get back to you shortly.</p>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-brand-50 border border-brand-200 text-sm text-brand-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                                    placeholder="Your full name"
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                                    placeholder="you@example.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                value="{{ old('subject') }}"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                                placeholder="What is this about?"
                            >
                            @error('subject')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="5"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none"
                                placeholder="Tell us how we can help..."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition"
                        >
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
