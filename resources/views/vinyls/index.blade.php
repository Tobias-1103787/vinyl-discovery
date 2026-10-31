<x-layout title="index">
    @auth
        <div class="mx-auto max-w-7xl px-4 py-6">
            <h1 class="mb-6 text-3xl font-bold">Albums</h1>
            <div>
                <a href=""></a>
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
