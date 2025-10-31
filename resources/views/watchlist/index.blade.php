<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Watchlist') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ✅ Success Message --}}
            @if (session('status'))
                <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 border border-green-300 shadow-sm">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3 sm:mb-0">
                        Your Tracked Cards
                    </h3>

                    <div class="flex items-center mb-4 space-x-2">
                        <!-- Search -->
                        <form action="{{ route('watchlist.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="text" name="q" placeholder="Search cards..." value="{{ request('q') }}"
                                class="w-60 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit"
                                class="p-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition flex items-center justify-center"
                                title="Search">
                                <!-- Magnifying Glass Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                                </svg>
                            </button>
                        </form>

                        <!-- Refresh Button -->
                        <form id="refresh-form" action="{{ route('cards.refresh') }}" method="POST">
                            @csrf
                            <button type="submit" id="refresh-btn"
                                class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition flex items-center justify-center"
                                title="Refresh Prices">
                                <!-- Refresh Icon -->
                                <svg id="refresh-icon" class="h-5 w-5 hidden animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0116 0M20 14a8 8 0 01-16 0" />
                                </svg>
                                <svg id="refresh-static" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0116 0M20 14a8 8 0 01-16 0" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- ⏳ Loading Overlay --}}
                    <div id="loading-overlay"
                        class="fixed inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center text-white text-lg hidden z-50">
                        <svg class="animate-spin h-10 w-10 text-white mb-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l4-4-4-4v4a8 8 0 100 16v-4l-4 4 4 4v-4a8 8 0 01-8-8z"></path>
                        </svg>
                        Refreshing prices... please wait.
                    </div>
                </div>

                {{-- 🃏 Cards Grid --}}
                @if ($cards->isEmpty())
                    <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                        No cards are being tracked yet.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($cards as $card)
                            <div
                                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                                <img src="{{ $card->image_url ?? asset('images/default-card.jpg') }}"
                                    alt="{{ $card->card_name ?? 'Card' }}" class="rounded-md mb-4 w-40 h-56 object-cover">
                                <div class="text-center">
                                    <p class="text-gray-900 dark:text-gray-100 font-medium mb-1">
                                        {{ $card->card_name ?? 'Unknown Card' }}
                                    </p>
                                    <p class="text-red-500 font-semibold mb-2">
                                        ¥{{ $card->price ?? '0' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        {{ $card->updated_at?->format('Y-m-d H:i') }}
                                    </p>
                                    <a href="{{ route('cards.show', $card->id) }}"
                                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 🌀 Loading behavior for refresh --}}
    <script>
        document.getElementById('refresh-form').addEventListener('submit', function () {
            const btn = document.getElementById('refresh-btn');
            const icon = document.getElementById('refresh-icon');
            const staticIcon = document.getElementById('refresh-static');
            const overlay = document.getElementById('loading-overlay');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            staticIcon.classList.add('hidden');
            icon.classList.remove('hidden');
            overlay.classList.remove('hidden');
        });
    </script>
</x-app-layout>