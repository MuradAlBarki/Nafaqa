<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Divorce Cases') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">{{ __('Case No') }}</th>
                        <th class="px-6 py-3">{{ __('Father Name') }}</th>
                        <th class="px-6 py-3">{{ __('Mother Name') }}</th>
                        <th class="px-6 py-3">{{ __('Status') }}</th>
                        <th class="px-6 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($divorceCases as $divorceCase)
                        @php
                            $statusEnum = $divorceCase->status;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $divorceCase->case_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ optional($divorceCase->father)->first_name ?? __('N/A') }}
                                {{ optional($divorceCase->father)->mid_name ?? '' }}
                                {{ optional($divorceCase->father)->last_name ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ optional($divorceCase->mother)->first_name ?? __('N/A') }}
                                {{ optional($divorceCase->mother)->mid_name ?? '' }}
                                {{ optional($divorceCase->mother)->last_name ?? '' }}
                            </td>
                           <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{$statusEnum->realColor()}}-500"></span>
                                {{ __($statusEnum->label()) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-3 items-center">
                            


            <a href="{{ route('divorce-cases.payments.index', $divorceCase) }}"
               title="List Payments"
               class="text-gray-600 hover:text-blue-600 mr-2">
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke-width="1.5" 
     stroke="currentColor" 
     class="h-6 w-6">
  <rect x="2.25" y="6" width="19.5" height="12" rx="2.25" ry="2.25" stroke-linecap="round" stroke-linejoin="round"/>
  <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 9h19.5M2.25 15h19.5"/>
</svg>





            </a>
   

                                    @can('update', $divorceCase)
                                    <!-- Edit -->
                                    <a href="{{ route('divorce-cases.payments.create', $divorceCase) }}" title="Edit" class="text-gray-600 hover:text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </a>
                                    @endcan

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">{{ __('No divorce cases found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $divorceCases->links() }}
        </div>
    </div>
</x-app-layout>