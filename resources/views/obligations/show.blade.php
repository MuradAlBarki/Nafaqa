<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ __('Obligation Details') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8 mt-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
            <!-- Amount -->
            <div class="flex flex-col">
                <label for="amount" class="text-sm font-medium mb-1 bg-white px-1">{{ __('Amount') }}</label>
                <input
                    type="number"
                    name="amount"
                    id="amount"
                    value="{{ $obligation->amount ?? '' }}"
                    placeholder="{{ __('Enter amount') }}"
                    disabled
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"
                />
            </div>

            <!-- Start Date -->
            <div class="flex flex-col">
                <label for="start_date" class="text-sm font-medium mb-1 bg-white px-1">{{ __('Start Date') }}</label>
                <input
                    type="string"
                    name="start_date"
                    id="start_date"
                    value="{{ isset($obligation->start_date) ? $obligation->start_date->format('Y-m-d') : '' }}"
                    disabled
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"
                />
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center gap-4">

   <a href="{{ route('divorce-cases.obligations.edit', [$divorceCase, $divorceCase->obligation]) }}"
    class="px-6 py-3 text-sm bg-blue-100 text-blue-700 font-medium rounded-md
                      hover:bg-blue-200 focus:outline-none focus:ring-1 focus:ring-blue-300
                      transition duration-150 ease-in-out">
    {{ __('Edit') }}
</a>

            <a href="{{ url()->previous() }}"
               class="px-6 py-3 text-sm font-semibold bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                {{ __('Back') }}
            </a>
        </div>

    </div>
<div class="mt-8 flex items-center justify-between bg-white px-3 py-2 rounded shadow-sm border-b">
    <h3 class="text-lg font-semibold">
        {{ __('Payments') }}
    </h3>

@can('create', \App\Models\Payment::class)
    <a href="{{ route('divorce-cases.payments.create', ['divorce_case' => $divorceCase->id]) }}" 
       class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded shadow hover:bg-indigo-200 hover:text-indigo-900 transition">
        + {{ __('Create') }}
    </a>
@endcan

</div>

        @include('payments.list_table', ['payments' => $payments, 'showPagination' => false])
</x-app-layout>

