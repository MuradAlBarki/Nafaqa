<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">{{ __('Activity Logs') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">{{ __('Action') }}</th>
                        <th class="px-6 py-3">{{ __('Model') }}</th>
                        <th class="px-6 py-3">{{ __('Performed By') }}</th>
                        <th class="px-6 py-3">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $log->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ class_basename($log->subject_type) ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $log->causer?->name ?? 'System' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">{{ __('No logs found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
