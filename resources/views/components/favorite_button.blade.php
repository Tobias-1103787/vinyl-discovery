@props(['vinyl', 'favorite' => false, 'icon' => false])
<form method="POST" action="{{ route('vinyls.favorite', $vinyl) }}">
    @csrf
    @if ($icon)
        <button type="submit"
                aria-label="{{ $favorite ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}"
                class="rounded-full bg-white/80 p-1.5 text-red-600 transition hover:scale-110">
            @if ($favorite)
                {{-- gevuld hart --}}
                <img src="{{ asset('images/heart-svgrepo-com.svg') }}" class="size-6">
            @else
                {{-- hart met alleen een rand --}}
                <img src="{{ asset('images/heart-seethrough.svg') }}" class="size-6">
            @endif
        </button>
    @else
        <button type="submit"
                class="rounded px-4 py-2 text-sm font-medium text-white {{ $favorite ? 'bg-red-600 hover:bg-red-500' : 'bg-indigo-600 hover:bg-indigo-500' }}">
            {{ $favorite ? 'Unfavorite' : 'Favorite' }}
        </button>
    @endif
</form>
