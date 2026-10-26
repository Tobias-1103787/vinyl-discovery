<x-layout title="create">
    <h1>Add new album</h1>
    <p>On this page you can add new albums to the database by entering a link to the Discog-entry. Search for the title on this page or enter the
        <a href="https://www.discogs.com" target="_blank" class="divide-blue-600">Discog</a>-link.
    </p>
    @if($vinyl)
        <x-vinyl_card :vinyl="$vinyl"/>
    @elseif(!empty($results))
        @isset($results)
            @foreach ($results as $result)
                <form method="POST" action="{{ route('vinyls.discogSearch') }}">
                    @csrf
                    <input type="hidden" name="query" value="{{ $result['id'] }}">
                    <button>{{ $result['title'] }}</button>
                </form>
            @endforeach
        @endisset
    @else
        <h2>Enter Discog-link or a title</h2>
        <form method="POST" action="{{ route('vinyls.discogSearch') }}">
            @csrf
            <input type="text" id="query" name="query">
            <button type="submit">Submit</button>
        </form>
    @endif
</x-layout>
