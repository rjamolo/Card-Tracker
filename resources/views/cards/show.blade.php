<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $card->card_name }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
                {{-- Image --}}
                <div class="flex-shrink-0">
                    <img src="{{ $card->image_url }}" alt="{{ $card->card_name }}"
                        class="rounded-lg shadow-md w-64 md:w-72 border border-gray-200 dark:border-gray-700">
                </div>

                {{-- Card info --}}
                <div class="text-center md:text-left flex-1">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ $card->card_name }}</h3>
                    <p class="text-3xl font-semibold text-blue-600 dark:text-blue-400 mb-2">
                        ¥{{ number_format($card->price ?? 0) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Last updated: {{ $card->collected_at?->format('Y-m-d H:i') }}
                    </p>

                    <a href="{{ $card->source_url }}" target="_blank"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                        View on Yuyu Tei
                    </a>
                    <a href="{{ route('watchlist.index') }}"
                        class="inline-block text-gray-500 dark:text-gray-400 hover:underline ml-3">
                        Back
                    </a>
                </div>
            </div>

            {{-- Price History --}}
            <div class="mt-10">
                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Price History</h4>

                @if($card->history->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No history recorded yet.</p>
                @else
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300 mb-6">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Old Price</th>
                                <th class="py-2 px-4 text-left">New Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($card->history as $entry)
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-2 px-4">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="py-2 px-4">¥{{ number_format($entry->old_price ?? 0) }}</td>
                                    <td class="py-2 px-4 text-blue-600 dark:text-blue-400">
                                        ¥{{ number_format($entry->new_price ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <canvas id="priceChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('priceChart');

        const labels = @json($card->history->pluck('created_at')->map(fn($d) => $d->format('m/d H:i')));
        const prices = @json($card->history->pluck('new_price'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Price (¥)',
                    data: prices,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#e5e7eb' }
                    }
                },
                scales: {
                    x: {
                        reverse: true, // 👈 this flips the graph direction
                        ticks: { color: '#e5e7eb' },
                        grid: { color: '#374151' }
                    },
                    y: {
                        ticks: { color: '#e5e7eb' },
                        grid: { color: '#374151' }
                    }
                }
            }
        });
    </script>

</x-app-layout>