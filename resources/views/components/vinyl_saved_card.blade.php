@props(['vinyl', 'detail' => false])
<x-card class="relative flex h-full w-full flex-col">
    @php($cover = $vinyl->cover_url)
    @php($label = $vinyl->labels->pluck('name')->join(', '))

    @if ($vinyl)
        <img src="{{ $cover }}" alt="Cover van {{ $vinyl->title }}"
             width="300" loading="lazy" referrerpolicy="no-referrer"
             class="aspect-square w-full object-cover">
    @else
        <div class="flex aspect-square items-center justify-center bg-gray-100 text-sm text-gray-500">Geen cover</div>
    @endif
    <div class="flex flex-1 flex-col gap-3 p-6 text-center">
        <span class="inline-flex items-center gap-1 self-center rounded bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.122 17.645a7.185 7.185 0 0 1-2.656 2.495 7.06 7.06 0 0 1-3.52.853 6.617 6.617 0 0 1-3.306-.718 6.73 6.73 0 0 1-2.54-2.266c-2.672-4.57.287-8.846.887-9.668A4.448 4.448 0 0 0 8.07 6.31 4.49 4.49 0 0 0 7.997 4c1.284.965 6.43 3.258 5.525 10.631 1.496-1.136 2.7-3.046 2.846-6.216 1.43 1.061 3.985 5.462 1.754 9.23Z"/></svg>
            {{ $vinyl->format }}
        </span>
        <h5 class="text-xl font-semibold text-gray-900">
            <a href="{{ route('vinyls.show', $vinyl->id) }}" class="after:absolute after:inset-0">
                {{ $vinyl->title }}
            </a>
        </h5>
        <p class="text-sm text-gray-600">
            <span class="font-bold">Release date:</span>
            {{ $vinyl->release_date ?? "Release datum onbekend" }}
        </p>
        <div class="mt-auto flex flex-col gap-3">
            <hr class="border-gray-200">
            <div class="flex flex-wrap justify-center gap-2">
                @foreach ($vinyl->artists as $artist)
                    <a href="https://www.discogs.com/artist/{{ $artist->discogs_id }}"
                       target="_blank"
                       class="relative z-10 rounded bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-500">
                        {{ $artist->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @if($detail)
            <p>{{ $vinyl->description }}</p>
        @endif
    </div>
</x-card>
