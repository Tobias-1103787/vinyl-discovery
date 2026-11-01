<x-layout title="vinyl-overview">
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-semibold text-gray-900">Overview</h1>
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            @auth
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Id</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Created by</th>
                        <th class="px-4 py-3">Intact</th>
                        <th class="px-4 py-3">Status <span class="font-medium normal-case">(editable)</span></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($vinyls as $vinyl)
                        <tr class="hover:bg-indigo-50">
                            <td class="px-4 py-2 font-medium text-gray-900">
                                {{ $vinyl->id }}
                            </td>
                            <td class="px-4 py-2 font-medium text-gray-900">
                                <a href="{{ route('vinyls.show', $vinyl) }}" class="hover:text-indigo-600 hover:underline">
                                    {{ $vinyl->title }}
                                </a>
                            </td>
                            <td class="px-4 py-2 font-medium text-gray-900">
                                {{ $vinyl->creator->name }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $vinyl->intact ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $vinyl->intact ? 'Yes' : 'Edited' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('vinyls.change-status', $vinyl) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="cursor-pointer rounded-full border px-2 py-0.5 text-xs font-medium transition
                                    border-red-300 bg-red-100 text-red-700
                                    has-[:checked]:border-green-300 has-[:checked]:bg-green-100 has-[:checked]:text-green-700
                                    has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-indigo-500">
                                        <input type="hidden" name="active" value="0">
                                        <input type="checkbox" name="active" value="1" class="sr-only"
                                               @checked($vinyl->active) onchange="this.form.submit()">
                                        {{ $vinyl->active ? 'Active' : 'Disabled' }}
                                    </label>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Geen albums gevonden.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            @endauth
            @guest
                <h1 class="text-3xl font-bold">
                    <a href="/login" class="text-blue-600 hover:underline">Login</a> or
                    <a href="/register" class="text-blue-600 hover:underline">register</a>
                    to see overview
                </h1>
            @endguest
        </div>
    </div>
</x-layout>
