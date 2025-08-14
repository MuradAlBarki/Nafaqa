<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Divorce Cases') }}
            </h2>
            @can('create', \App\Models\DivorceCase::class)
     <a href="{{ route('divorce-cases.create') }}"
   class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded shadow hover:bg-indigo-200 hover:text-indigo-900 transition">
   + {{ __('Create') }}
</a>

            @endcan
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
                            



                                    @can('update', $divorceCase)
                                    <!-- Edit -->
                                    <a href="{{ route('divorce-cases.edit', $divorceCase) }}" title="Edit" class="text-gray-600 hover:text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </a>
                                    @endcan
&nbsp;
&nbsp;
&nbsp;

 @can('viewAny', [\App\Models\DivorceCase::class, $divorceCase])
<a href="{{ route('divorce-cases.children.index', $divorceCase) }}"
   class="text-indigo-600 hover:text-indigo-900"
   title="View Children">
   <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
       <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
   </svg>
</a>
@endcan

@if ($divorceCase->obligation)
    @can('view', $divorceCase->obligation)
        <a href="{{ route('divorce-cases.obligations.show', ['divorce_case' => $divorceCase->id, 'obligation' => $divorceCase->obligation->id]) }}"
           title="Show Obligation"
           class="text-green-600 hover:text-green-800 mr-2">
            <!-- Solid banknote / cash stack -->
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3 1a1 1 0 100 2h6a1 1 0 100-2H5zm0 4a1 1 0 100 2h6a1 1 0 100-2H5z"/>
</svg>
        </a>
    @endcan
@else
    @can('create', \App\Models\Obligation::class)
        <a href="{{ route('divorce-cases.obligations.create', ['divorce_case' => $divorceCase->id]) }}"
           title="Create Obligation"
           class="text-gray-600 hover:text-blue-600 mr-2">
            <!-- Outline banknote / cash stack -->
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3 1a1 1 0 100 2h6a1 1 0 100-2H5zm0 4a1 1 0 100 2h6a1 1 0 100-2H5z"/>
</svg>
        </a>
    @endcan
@endif







                                    @can('delete', $divorceCase)
                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('divorce-cases.destroy', $divorceCase) }}" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" title="Delete" class="delete-btn text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M5 7h14" />
                                                <path d="M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                            </svg>
                                        </button>
                                    </form>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لا يمكن التراجع عن هذا الإجراء!",
                icon: 'warning',
                iconColor: '#e07b7b',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#d9534f',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                buttonsStyling: true,
                customClass: {
                    popup: 'font-sans text-base p-6',
                    title: 'text-lg font-semibold',
                    confirmButton: 'px-6 py-2 rounded-md shadow-md',
                    cancelButton: 'px-6 py-2 rounded-md shadow-md',
                    content: 'mt-4 text-sm text-gray-700',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
