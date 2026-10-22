@props([
    'title' > 'Webpage'
])
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="bg-gray-900 text-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <a href="/" class="font-medium hover:text-gray-300">Home</a>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('vinyls.create') }}"
                       class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium hover:bg-indigo-500">
                        New
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm hover:text-gray-300">Log out</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm hover:text-gray-300">Log in</a>
                    <a href="{{ route('register') }}"
                       class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium hover:bg-indigo-500">
                        Register
                    </a>
                @endguest
            </div>
        </div>
    </nav>
    {{ $slot }}
</body>
</html>
