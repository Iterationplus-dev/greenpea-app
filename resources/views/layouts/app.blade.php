<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Greenpea Apartments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/greenpea-favicon.png') }}"
        type="image/x-icon" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-green-600">
                Greenpea
            </a>

            <div class="text-sm text-gray-600">
                Abuja · Lagos · Port Harcourt
            </div>
        </div>
    </header>

    <!-- Page content -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">

        <span>
            © {{ date('Y') }} Greenpea Apartments. All rights reserved.
        </span>

        <a
            href="http://admin.greenpea-app.test"
            class="mt-2 sm:mt-0 text-gray-400 hover:text-green-600 transition"
            rel="nofollow"
        >
            Admin Login
        </a>
    </div>
</footer>

</body>
</html>
