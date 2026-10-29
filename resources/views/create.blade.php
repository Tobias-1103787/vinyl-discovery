<x-layout title="create">
    <div class="mx-auto max-w-2xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-semibold text-gray-900">Add new album</h1>
        @if($vinyl)
            <x-vinyl_card :vinyl="$vinyl"/>
        @elseif(!empty($results))
            @isset($results)
                <div class="space-y-2">
                    @foreach ($results as $result)
                        @php($id = preg_match('#/(?:master|release)/(\d+)#i', $result['uri'], $m) ? (int) $m[1] : null)
                        <form method="POST" action="{{ route('vinyls.discogSearch') }}">
                            @csrf
                            <input type="hidden" name="query" value="{{ $id }}">
                            <button type="submit" class="w-full rounded border border-gray-200 bg-white px-4 py-3 text-left text-sm text-gray-900 hover:border-indigo-500 hover:bg-indigo-50">{{ $result['title'] }} - {{ $id}}</button>
                        </form>
                    @endforeach
                </div>
            @endisset
        @elseif($errors->any())
            <ul class="space-y-1 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @else
            <p class="mb-6 text-sm text-gray-600">On this page you can add new albums to the database by entering a link to the Discog-entry. Search for the title on this page or enter the
                <a href="https://www.discogs.com" target="_blank" class="text-indigo-600 hover:underline">Discog</a>-link.
            </p>
            <h2 class="mb-2 text-lg font-medium text-gray-900">Enter Discog-link or a title</h2>
            <form method="POST" action="{{ route('vinyls.discogSearch') }}" class="flex gap-2">
                @csrf
                <input type="text" id="query" name="query" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Submit</button>
            </form>
        @endif
    </div>
</x-layout>
