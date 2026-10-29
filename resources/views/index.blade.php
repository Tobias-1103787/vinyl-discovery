<x-layout title="index">
    <h1 class="text-3xl font-bold">Albums</h1>

    @forelse ($vinyls as $vinyl)
        <x-vinyl_saved_card :vinyl="$vinyl" />
    @empty
        <p>Er staan nog geen albums in de database.</p>
    @endforelse
</x-layout>
