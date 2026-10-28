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
                @php($id = preg_match('#/(?:master|release)/(\d+)#i', $result['uri'], $m) ? (int) $m[1] : null)
                <form method="POST" action="{{ route('vinyls.discogSearch') }}">
                    @csrf
                    <input type="hidden" name="query" value="{{ $id }}">
                    <button type="submit">{{ $result['title'] }} - {{ $result['uri'] }}</button>
                </form>
            @endforeach
        @endisset
    @elseif($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @else
        <h2>Enter Discog-link or a title</h2>
        <form method="POST" action="{{ route('vinyls.discogSearch') }}">
            @csrf
            <input type="text" id="query" name="query">
            <button type="submit">Submit</button>
        </form>
    @endif
</x-layout>
