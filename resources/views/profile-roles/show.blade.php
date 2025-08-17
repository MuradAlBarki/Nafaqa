<x-app-layout>
    <x-slot name="header">
        <h2><strong>{{ __('Profile Role Details')  }}</strong></h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded shadow p-6 mt-10">

        <div class="space-y-4 text-gray-700">
            <p><strong>{{ __('Name') }}:</strong> {{ $profileRole->first_name }} {{ $profileRole->mid_name }} {{ $profileRole->last_name }}</p>
            <p><strong>{{ __('Gender') }}:</strong> {{ __($profileRole->gender->label()) }}</p>
            @if($profileRole->national_no)
            <p><strong>{{ __('National No') }}:</strong> {{ $profileRole->national_no }}</p>
            @endif
            <p><strong>{{ __('IBAN') }}:</strong> {{ $profileRole->IBAN }}</p>
            <p>
                <strong>{{ __('Document') }}:</strong>
                @php
                    $docTypeLabel = \App\DocumentTypeEnum::tryFrom($profileRole->document_type)?->label() ?? $profileRole->document_type;
                @endphp
                {{ __($docTypeLabel) }} - {{ $profileRole->document_no }}
            </p>
            <p><strong>{{ __('Date of Birth') }}:</strong> {{ \Carbon\Carbon::parse($profileRole->date_of_birth)->format('d/m/Y') }}</p>
            <p><strong>{{ __('Nationality') }}:</strong> {{ $nationalityName}}</p>
         @php
            $statusEnum = $profileRole->status;
        @endphp

        <p><strong>{{ __('Status') }}:</strong>
        @if ($statusEnum)
            <span class="text-{{ $statusEnum->color() }}">
                {{ __($statusEnum->label()) }}
            </span>
        @else
            <span class="text-gray-600">{{ __('Unknown') }}</span>
        @endif
        </p>




            @if($profileRole->document_file_url)
                <p>
                    <strong>{{ __('Document File') }}:</strong>
                    <a href="{{ asset('storage/' . $profileRole->document_file_url) }}" target="_blank" class="text-indigo-600 hover:underline">
                        {{ __('View')}} {{ __('the')}}{{ __('File') }}
                    </a>
                </p>
            @endif
        </div>
<div class="mt-6 flex justify-center gap-2">
    <a href="{{ route('profile-roles.edit', $profileRole) }}"
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
