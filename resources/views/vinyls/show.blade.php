<x-layout title="vinyl-detail">
    @auth
        <div class="mx-auto max-w-7xl px-4 py-6">
            <div class="mx-auto max-w-md space-y-4">
                <x-vinyl_saved_card :vinyl="$vinyl" :detail="true"/>

                <div class="flex items-center justify-between">
                    <x-favorite_button :vinyl="$vinyl" :favorite="$isFavorite"/>
                    @if(!empty($currentUserId) and $currentUserId === $vinyl->created_by)
                        <div class="flex items-center gap-2">
                            <a href="{{ route('vinyls.edit', $vinyl) }}"
                               class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('vinyls.destroy', $vinyl) }}"
                                  onsubmit="return confirm('Weet je zeker dat je dit album wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endauth
    @guest
        <h1 class="text-3xl font-bold">
            <a href="/login" class="text-blue-600 hover:underline">Login</a> or
            <a href="/register" class="text-blue-600 hover:underline">register</a>
            to view this album
        </h1>
    @endguest
</x-layout>
