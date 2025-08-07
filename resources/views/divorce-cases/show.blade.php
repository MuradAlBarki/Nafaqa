<x-app-layout>
    <x-slot name="header">
        <h2><strong> {{ __('Divorce Case') }}</strong></h2>
    </x-slot>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Divorce Case Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Case Number --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500">Case Number</h4>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $divorceCase->case_no }}</p>
            </div>

            {{-- Divorce Date --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500">Divorce Date</h4>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($divorceCase->divorce_date)->format('F j, Y') }}</p>
            </div>

            {{-- Mother --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500">Mother</h4>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $divorceCase->mother->name ?? 'N/A' }}</p>
            </div>

            {{-- Father --}}
            <div>
                <h4 class="text-sm font-medium text-gray-500">Father</h4>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $divorceCase->father->name ?? 'N/A' }}</p>
            </div>

            {{-- Court Document --}}
            <div class="md:col-span-2">
                <h4 class="text-sm font-medium text-gray-500">Court Document</h4>
                @if ($divorceCase->court_document)
                    <a href="{{ asset('storage/' . $divorceCase->court_document) }}" target="_blank" class="mt-1 inline-block text-indigo-600 hover:underline">
                        View Document
                    </a>
                @else
                    <p class="mt-1 text-gray-700">No document uploaded.</p>
                @endif
            </div>
        </div>

        {{-- Back button --}}
        <div class="mt-8">
            <a href="{{ route('divorce-cases.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back to List
            </a>
        </div>
    </div>
</div>
</x-app-layout>