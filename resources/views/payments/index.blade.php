<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
        @can('create', \App\Models\Payment::class)
     <a href="{{ route('payments.create') }}"
   class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded shadow hover:bg-indigo-200 hover:text-indigo-900 transition">
   + {{ __('Create') }}
</a>

            @endcan
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded shadow-sm">{{ session('success') }}</div>
        @endif
        
               @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
        @endif

        @include('payments.list_table', ['payments' => $payments, 'showPagination' => true])
    </div>
</x-app-layout>
