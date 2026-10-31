<x-layout title="index">
    @auth
        <div class="mx-auto max-w-7xl px-4 py-6">
            <h1 class="mb-6 text-3xl font-bold">Albums</h1>
            @error('error')
            <p class="mb-6 text-3xl font-bold text-red-600">{{ $message }}</p>
            @enderror
            <div class="mb-6 flex flex-wrap gap-2">
                @foreach ($filters as $key => $label)
                    @php($on = request()->boolean($key))
                    <a href="{{ $on ? request()->fullUrlWithoutQuery($key) : request()->fullUrlWithQuery([$key => 1]) }}"
                       class="rounded-full border px-3 py-1 text-sm {{ $on ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-300 text-gray-700 hover:border-indigo-500' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($vinyls as $vinyl)
                    <div class="relative h-full rounded-lg transition hover:-translate-y-1 hover:shadow-lg">
                        <x-vinyl_saved_card :vinyl="$vinyl" />
                        <div class="absolute right-2 top-2 z-10">
                            <x-favorite_button :vinyl="$vinyl" :favorite="$vinyl->is_favorite" :icon="true" />
                        </div>
                    </div>
                @empty
                    <p class="col-span-full">Er staan nog geen albums in de database.</p>
                @endforelse
            </div>
        </div>
    @endauth
    @guest
        <h1 class="text-3xl font-bold">
            <a href="/login" class="text-blue-600 hover:underline">Login</a> or
            <a href="/register" class="text-blue-600 hover:underline">register</a>
            to save/browse albums
        </h1>
    @endguest
</x-layout>
