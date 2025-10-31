<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Card Tracker') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 🔍 Search Form --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form action="{{ route('cards.index') }}" method="GET" class="flex space-x-2">
                    <input type="text" name="q" placeholder="Search cards..."
                        value="{{ request('q') }}"
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Search
                    </button>
                </form>
            </div>

            {{-- 📋 Card List --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cards as $card)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 flex flex-col justify-between">
                        <div class="text-center">
                            @if ($card->image_url)
                                <img src="{{ $card->image_url }}" alt="{{ $card->card_name }}"
                                    class="w-full h-48 object-contain mb-3 rounded">
                            @endif
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                {{ $card->card_name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-3">
                                ¥{{ number_format($card->price ?? 0) }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ $card->source_url }}" target="_blank"
                                class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                                View on Yuyu Tei
                            </a>
                            <a href="{{ route('cards.show', $card->id) }}"
                                class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                                Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-600 dark:text-gray-400">
                        No cards found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div>
                {{ $cards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
