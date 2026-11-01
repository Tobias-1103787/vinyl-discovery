<x-layout title="index">
    @auth
        <div class="mx-auto max-w-7xl px-4 py-6">
            <h1 class="mb-6 text-3xl font-bold">Albums</h1>
            @error('error')
            <p class="mb-6 text-3xl font-bold text-red-600">{{ $message }}</p>
            @enderror
            <form method="GET" action="{{ url()->current() }}"
                  class="mb-6 flex flex-wrap items-center justify-between gap-3">

                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($filters as $key => $value)
                        <label class="cursor-pointer rounded-full border border-gray-300 px-3 py-1 text-sm text-gray-700 transition
                          hover:border-indigo-500
                          has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-600 has-[:checked]:text-white
                          has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-indigo-500">
                            <input type="checkbox" name="{{ $key }}" value="1" class="sr-only"
                                   @checked(request()->boolean($key)) onchange="this.form.submit()">
                            {{ $value }}
                        </label>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <input type="text" name="searchValue" value="{{ request('searchValue') }}" placeholder="Zoek op titel of artiest"
                           class="rounded border border-gray-300 px-3 py-1.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <button type="submit"
                            class="rounded bg-indigo-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-indigo-500">
                        Zoek
                    </button>
                </div>
            </form>
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
