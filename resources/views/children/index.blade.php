<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Children') }}
        </h2>

        <div class="flex space-x-2 gap-3">
            @can('create', \App\Models\Child::class)
                <a href="{{ route('divorce-cases.children.create', $divorceCase) }}"
                   class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded shadow hover:bg-indigo-200 hover:text-indigo-900 transition">
                    + {{ __('Add') }}
                </a>
            @endcan

            <a href="{{ url()->previous() }}"
               class="inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded shadow hover:bg-gray-200 hover:text-gray-900 transition">
                {{ __('Back') }}
            </a>
        </div>
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
                        <th class="px-6 py-3">{{ __('Name') }}</th>
                        <th class="px-6 py-3">{{ __('National No') }}</th>
                        <th class="px-6 py-3">{{ __('Gender') }}</th>
                        <th class="px-6 py-3">{{ __('Date of Birth') }}</th>
                        <!-- <th class="px-6 py-3">{{ __('Status') }}</th> -->
                        <th class="px-6 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($children as $child)
                        @php
                            $statusEnum = $child->status;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $child->first_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $child->nationality_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ __($child->gender->label()) ?? __('Unknown') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $child->date_of_birth->format('Y-m-d') }}
                            </td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{ $statusEnum->realColor() }}-500"></span>
                                {{ __($statusEnum->label()) }}
                            </td> -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-3 items-center">
                                @can('update', $child)
<!-- Edit Link -->
<a href="{{ route('divorce-cases.children.edit', [$child->case_id, $child]) }}" title="Edit" class="text-gray-600 hover:text-yellow-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
    </svg>
</a>
@endcan

@can('delete', $child)
<!-- Delete -->
<form method="POST" action="{{ route('divorce-cases.children.destroy', [$child->case_id, $child]) }}" class="delete-form">
    @csrf
    @method('DELETE')
    <button type="button" title="Delete" class="delete-btn text-red-600 hover:text-red-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7M10 11v6M14 11v6M5 7h14M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
        </svg>
    </button>
</form>
@endcan

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">{{ __('No children found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $children->links() }}
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
