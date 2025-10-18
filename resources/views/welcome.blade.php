<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent: {{ $accentColor }};
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen flex flex-col">

    <header class="p-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[color:var(--primary)]">{{ $appName }}</h1>
            <a href="{{ $focusUrl }}" class="text-sm font-medium text-white bg-[color:var(--accent)] px-4 py-2 rounded-full shadow hover:opacity-90 transition">
                Accedir
            </a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center">
        <div class="max-w-5xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-4xl font-extrabold mb-4 text-gray-900 leading-tight">
                   <span class="text-[color:var(--primary)]">{{ $appName }}</span>
                </h2>
                <p class="text-lg text-gray-600 mb-6">{{ $description }}</p>
                @if($documentation_url)
                    <a href="{{ $documentation_url }}"
                    class="inline-block px-6 py-3 bg-[color:var(--accent)] text-white text-base font-semibold rounded-lg shadow-md hover:shadow-lg transition">
                        Docs
                    </a>
                @endif
            </div>
            <div>
                <img src="img/ginebro-logo.png" alt="Ilustración" class="w-full max-w-md mx-auto">
            </div>
        </div>
    </main>

    <footer class="bg-white border-t mt-10 text-center p-4 text-sm text-gray-500">
        {{ $footerText }}
    </footer>

</body>
</html>