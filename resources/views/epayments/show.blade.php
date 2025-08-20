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

    <div class="mt-2 text-sm text-gray-800 bg-gray-100 p-4 rounded shadow-inner">
        <dl class="divide-y divide-gray-200">
            @foreach($epayment->response_json as $key => $value)
                <div class="py-2 grid grid-cols-3 gap-4 items-center">
                    <dt class="font-semibold text-gray-600 capitalize col-span-1">
                        {{ str_replace('_', ' ', $key) }}
                    </dt>
                    <dd class="col-span-2 text-gray-900 break-all">
                        @if(in_array($key, ['Amount']))
                            <span class="font-bold text-green-700">{{ $value ?? '—' }}</span>
                        @elseif(in_array($key, ['PaidThrough', 'PayerName']))
                            <span class="text-blue-700">{{ $value ?? '—' }}</span>
                        @else
                            {{ $value ?? '—' }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</div>



<!-- Back Button -->
<div class="flex justify-center mt-4">
    <a href="{{ url()->previous() }}"
       class="inline-block px-6 py-2 bg-gray-100 text-gray-700 rounded shadow hover:bg-gray-200 transition">
        {{ __('Back') }}
    </a>
</div>


    </div>
</x-app-layout>
