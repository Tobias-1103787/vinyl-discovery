<x-layout title="vinyl-edit">
    <div class="mx-auto max-w-2xl px-4 py-8">
        @if ($errors->any())
            <ul class="mb-6 space-y-1 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @php($input = 'w-full rounded border px-3 py-2 text-sm focus:outline-none focus:ring-1')

        <form method="POST" action="{{ route('vinyls.update', $vinyl) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-gray-900">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $vinyl->title) }}"
                       class="{{ $input }} @error('title') border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-gray-900">Description</label>
                <textarea id="description" name="description" rows="5"
                          class="{{ $input }} @error('description') border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">{{ old('description', $vinyl->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="release_date" class="mb-1 block text-sm font-medium text-gray-900">Release date</label>
                <input type="text" id="release_date" name="release_date" value="{{ old('release_date', $vinyl->release_date) }}"
                       class="{{ $input }} @error('release_date') border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                @error('release_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="format" class="mb-1 block text-sm font-medium text-gray-900">Format</label>
                <input type="text" id="format" name="format" value="{{ old('format', $vinyl->format) }}"
                       class="{{ $input }} @error('format') border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                @error('format') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Opslaan</button>
        </form>
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
</x-layout>
