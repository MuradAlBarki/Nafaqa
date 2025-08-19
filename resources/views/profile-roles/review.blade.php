<x-app-layout>
    <x-slot name="header">
        <h2><strong>{{ __('Profile Role Details')  }}</strong></h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded shadow p-6 mt-10">

        <div class="space-y-4 text-gray-700">
            <p><strong>{{ __('Name') }}:</strong> {{ $profileRole->first_name }} {{ $profileRole->mid_name }} {{ $profileRole->last_name }}</p>
            <p><strong>{{ __('National No') }}:</strong> {{ $profileRole->national_no }}</p>
            <p><strong>{{ __('IBAN') }}:</strong> {{ $profileRole->IBAN }}</p>
            <p>
                <strong>{{ __('Document') }}:</strong>
                @php
                    $docTypeLabel = $profileRole->document_type?->label() ?? $profileRole->document_type;
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
<div class="mt-6 flex justify-center space-x-6">
    <!-- Approve Button -->
@can('changeStatus', $profileRole)
@if($profileRole->status->value == \App\StatusEnum::Pending->value)
    <form action="{{ route('profile-roles.review', $profileRole) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="{{ \App\StatusEnum::Active->value }}" />
        <button type="submit"
            class="px-5 py-2 bg-green-300 text-green-800 rounded-md hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
            {{ __('Approve') }}
        </button>
    </form>
&nbsp;
&nbsp;
&nbsp;
    <!-- Reject Button -->
    <form action="{{ route('profile-roles.review', $profileRole) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="{{ \App\StatusEnum::Rejected->value }}" />
        <button type="submit"
            class="px-5 py-2 bg-red-300 text-red-800 rounded-md hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
            {{ __('Reject') }}
        </button>
    </form>
@endif
@endcan
    <!-- Back Button -->
    <a href="{{ url()->previous() }}"
       class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
        {{ __('Back') }}
    </a>
</div>




    </div>
</x-app-layout>
