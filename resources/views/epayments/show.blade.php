<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ __('Epayment Details') }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6 mt-8">

        <!-- Gateway -->
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <span class="text-sm font-semibold text-blue-700">{{ __('Payment Gateway') }}</span>
            <p class="text-gray-800 mt-1">{{ $epayment->gateway }}</p>
        </div>

        <!-- Response JSON -->
        <div class="mb-4 p-4 bg-gray-50 border-l-4 border-gray-400 rounded">
            <span class="text-sm font-semibold text-gray-700">{{ __('Response Data') }}</span>
            <pre class="mt-2 text-sm text-gray-800 bg-gray-100 p-2 rounded overflow-x-auto">
{{ json_encode($epayment->response_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
            </pre>
        </div>

        <!-- Back Button -->
        <a href="{{ url()->previous() }}"
           class="inline-block px-6 py-2 bg-gray-100 text-gray-700 rounded shadow hover:bg-gray-200 transition">
            {{ __('Back') }}
        </a>

    </div>
</x-app-layout>
