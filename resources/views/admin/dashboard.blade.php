<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent: {{ $accentColor }};
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen flex flex-col">

    <header class="p-6 border-b bg-white shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[color:var(--primary)]">{{ $appName }} — Admin</h1>
            <a href="/" class="text-sm font-medium text-white bg-[color:var(--accent)] px-4 py-2 rounded-full shadow hover:opacity-90 transition">
                Home
            </a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-10 border border-gray-100 text-center">
            <h2 class="text-3xl font-extrabold text-[color:var(--primary)] mb-6">Panell d'administració</h2>

            <div class="space-y-4 mb-6">
                <a href="/telescope" class="block px-6 py-3 bg-[color:var(--accent)] text-white font-semibold rounded-lg shadow hover:opacity-90 transition">
                    📊 Accedir a Telescope
                </a>
                <a href="/pulse" class="block px-6 py-3 bg-[color:var(--accent)] text-white font-semibold rounded-lg shadow hover:opacity-90 transition">
                    📈 Accedir a Pulse
                </a>
                <a href="/" class="block px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
                    🏡 Tornar a la Home
                </a>
            </div>

            <form method="GET" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-red-500 text-white font-semibold py-2 rounded-lg shadow hover:bg-red-600 transition">
                    Tancar sessió
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t mt-10 text-center p-4 text-sm text-gray-500">
        {{ $footerText }}
    </footer>

</body>
</html>
