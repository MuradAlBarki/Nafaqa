<x-app-layout>
    <x-slot name="header">
        <h2><strong>{{ __('Divorce Case Details') }}</strong></h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded shadow p-6 mt-10">

        <div class="space-y-4 text-gray-700">
            <p><strong>{{ __('Case Number') }}:</strong> {{ $divorceCase->case_no }}</p>
            
            <p><strong>{{ __('Mother') }}:</strong> 
                {{ optional($divorceCase->mother)->first_name ?? __('Unknown') }}
                {{ optional($divorceCase->mother)->mid_name ?? '' }}
                {{ optional($divorceCase->mother)->last_name ?? '' }}
            </p>
            
            <p><strong>{{ __('Father') }}:</strong> 
                {{ optional($divorceCase->father)->first_name ?? __('Unknown') }}
                {{ optional($divorceCase->father)->mid_name ?? '' }}
                {{ optional($divorceCase->father)->last_name ?? '' }}
            </p>
            
            <p><strong>{{ __('Divorce Date') }}:</strong> {{ \Carbon\Carbon::parse($divorceCase->divorce_date)->format('d/m/Y') }}</p>
            
            <p><strong>{{ __('Status') }}:</strong>
                @if(isset($divorceCase->status))
                    @php
                        $status = $divorceCase->status;
                        // Customize color/label logic if you have enums or constants
                        $statusLabel = $status == 1 ? __('Active') : __('Inactive');
                        $statusColor = $status == 1 ? 'green' : 'red';
                    @endphp
                    <span class="text-{{ $statusColor }}-600 font-semibold">{{ $statusLabel }}</span>
                @else
                    <span class="text-gray-600">{{ __('Unknown') }}</span>
                @endif
            </p>

            @if($divorceCase->court_document)
                <p>
                    <strong>{{ __('Court Document') }}:</strong>
                    <a href="{{ asset('storage/' . $divorceCase->court_document) }}" target="_blank" class="text-indigo-600 hover:underline">
                        {{ __('View Document') }}
                    </a>
                </p>
            @endif
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <a href="{{ route('divorce-cases.edit', $divorceCase) }}"
               class="px-6 py-2 text-sm bg-blue-100 text-blue-700 font-medium rounded-md
                      hover:bg-blue-200 focus:outline-none focus:ring-1 focus:ring-blue-300
                      transition duration-150 ease-in-out">
                {{ __('Edit') }}
            </a>
            <a href="{{ url()->previous() }}"
               class="px-6 py-2 text-sm bg-gray-100 text-gray-700 font-medium rounded-md
                      hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-gray-300
                      transition duration-150 ease-in-out">
                {{ __('Back') }}
            </a>
        </div>

    </div>
</x-app-layout>
