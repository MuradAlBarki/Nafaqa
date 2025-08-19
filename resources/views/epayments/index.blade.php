<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Epayments') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
               @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">{{__('Id')}}</th>
                        <th class="px-6 py-3">{{__('Gate')}}</th>
                        <th class="px-6 py-3">{{__('Status')}}</th>
                        <th class="px-6 py-3">{{__('Actions')}}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($epayments as $epayment)
                        @php
                            $statusEnum = $epayment->status;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $epayment->id}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $epayment->gateway}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{$statusEnum->realColor()}}-500"></span>
                                {{ __($statusEnum->label()) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">

                                <div class="flex space-x-3 items-center">
@can('view', $epayment)
    <a href="{{ route('payments.epayments.show', ['epayment' => $epayment->id]) }}"
       title="{{ __('Review Profile') }}"
       class="text-gray-600 hover:text-blue-600 mr-2">
<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
</svg>
    </a>
@endcan


                                    @can('viewAny', \Spatie\Activitylog\Models\Activity::class)
                                     <a href="{{ route('logs.index', ['model' => App\Models\Epayment::class, 'id' => $epayment->id]) }}"
                                    title="{{ __('View Logs') }}"
                                    class="text-gray-600 hover:text-blue-600 mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                        </svg>
                                    </a>
                                    @endcan

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No epayments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $epayments->links() }}
        </div>
    </div>
</x-app-layout>





