<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Track New Card') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Add a new card to track
                </h3>

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('cards.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="source_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Yuyu Tei Card URL
                        </label>
                        <input type="source_url" id="source_url" name="source_url"
                            placeholder="https://yuyu-tei.jp/game_ws/sell/sell_price.php?..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('watchlist.index') }}"
                            class="text-gray-500 dark:text-gray-400 hover:underline mr-3">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Track Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
