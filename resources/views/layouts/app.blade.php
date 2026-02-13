<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GreenPea Apartments — Shortlet Stays in Nigeria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Book premium shortlet apartments in Abuja, Lagos, Port Harcourt & Enugu. Flexible stays, verified properties.">
    <link rel="shortcut icon" href="{{ asset('img/greenpea-favicon.png') }}" type="image/x-icon" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-warm text-gray-800 antialiased">

    <!-- Navigation -->
    <header class="bg-brand-600 text-white sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Primary Nav -->
            <div class="flex items-center justify-between h-16 sm:h-18">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('img/greenpea-favicon.png') }}" alt="GreenPea" class="h-8 w-8 rounded-full brightness-0 invert">
                    <span class="text-lg font-bold tracking-tight">GreenPea</span>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="/" class="text-white/90 hover:text-white transition">Home</a>
                    <a href="/#apartments" class="text-white/90 hover:text-white transition">Apartments</a>
                    <a href="/#cities" class="text-white/90 hover:text-white transition">Cities</a>
                </nav>

                <!-- Desktop Actions -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="/guest/login"
                       class="px-5 py-2 text-sm font-medium text-white transition rounded-full border border-white/30 hover:border-white hover:bg-white/10">
                        Sign In
                    </a>
                    <a href="/guest/register"
                       class="px-5 py-2 text-sm font-medium bg-white text-brand-700 hover:bg-white/90 rounded-full transition">
                        List with Us
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Nav -->
            <div x-show="mobileOpen" x-transition class="md:hidden pb-4 border-t border-white/10" x-cloak>
                <nav class="flex flex-col gap-1 pt-3">
                    <a href="/" class="px-3 py-2 rounded-lg text-white/90 hover:bg-white/10 transition">Home</a>
                    <a href="/#apartments" class="px-3 py-2 rounded-lg text-white/90 hover:bg-white/10 transition">Apartments</a>
                    <a href="/#cities" class="px-3 py-2 rounded-lg text-white/90 hover:bg-white/10 transition">Cities</a>
                    <hr class="border-white/10 my-2">
                    <a href="/guest/login" class="px-3 py-2 rounded-lg text-white/90 hover:bg-white/10 transition">Sign In</a>
                    <a href="/guest/register" class="px-3 py-2 rounded-lg bg-white text-brand-700 text-center font-medium hover:bg-white/90 transition">List with Us</a>
                </nav>
            </div>
        </div>

        <!-- Secondary Nav: City Indicator -->
        <div class="bg-navy border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center gap-6 h-10 text-xs font-medium text-white/60 overflow-x-auto hide-scrollbar">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Abuja
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lagos
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Port Harcourt
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Enugu
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Footer Top -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 py-12 border-b border-white/10">
                <!-- Brand -->
                <div>
                    <a href="/" class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('img/greenpea-favicon.png') }}" alt="GreenPea" class="h-8 w-8 rounded-full">
                        <span class="text-lg font-bold">GreenPea</span>
                    </a>
                    <p class="text-sm text-white/50 leading-relaxed">
                        Premium shortlet apartments across Nigeria. Verified properties, flexible stays.
                    </p>
                </div>

                <!-- Cities -->
                <div>
                    <h4 class="text-sm font-semibold mb-4 text-white/80">Our Cities</h4>
                    <ul class="space-y-2 text-sm text-white/50">
                        <li><a href="/#apartments" class="hover:text-white transition">Abuja</a></li>
                        <li><a href="/#apartments" class="hover:text-white transition">Lagos</a></li>
                        <li><a href="/#apartments" class="hover:text-white transition">Port Harcourt</a></li>
                        <li><a href="/#apartments" class="hover:text-white transition">Enugu</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-sm font-semibold mb-4 text-white/80">Company</h4>
                    <ul class="space-y-2 text-sm text-white/50">
                        <li><a href="/guest/login" class="hover:text-white transition">Sign In</a></li>
                        <li><a href="/guest/register" class="hover:text-white transition">Create Account</a></li>
                        <li><a href="/guest" class="hover:text-white transition">My Bookings</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-sm font-semibold mb-4 text-white/80">Support</h4>
                    <ul class="space-y-2 text-sm text-white/50">
                        <li>help@greenpea.ng</li>
                        <li>+234 800 000 0000</li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="flex items-center gap-3 py-6 text-xs text-white/40">
                <span>&copy; {{ date('Y') }} GreenPea Apartments. All rights reserved.</span>
                <span class="text-white/20">|</span>
                <a href="{{ config('app.admin_url') }}" class="hover:text-white/60 transition" rel="nofollow">Admins Access</a>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Chat Button -->
    <a
        href="https://wa.me/2348000000000?text=Hello%20GreenPea%2C%20I%20need%20help%20with%20booking%20an%20apartment."
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2 bg-[#25D366] hover:bg-[#1ebe57] text-white pl-4 pr-5 py-3 rounded-full shadow-lg hover:shadow-xl transition group"
        title="Chat with us on WhatsApp"
    >
        <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="text-sm font-semibold hidden sm:inline">Chat with us</span>
    </a>
    <!-- Cookie Consent Banner -->
    <div x-data="{ show: !localStorage.getItem('cookie_consent') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-0 inset-x-0 z-60 p-4 sm:p-6"
         x-cloak>
        <div class="max-w-4xl mx-auto rounded-xl bg-white shadow-2xl border border-gray-200 p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic.
                        By clicking "Accept All", you consent to our use of cookies.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="localStorage.setItem('cookie_consent', 'essential'); show = false"
                            class="px-4 py-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Essential Only
                    </button>
                    <button @click="localStorage.setItem('cookie_consent', 'all'); show = false"
                            class="px-5 py-2 text-sm font-medium bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition">
                        Accept All
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
