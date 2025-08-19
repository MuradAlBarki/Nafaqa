<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('profileRoles') }}
        </h2>
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
                        <th class="px-6 py-3">{{__('Name')}}</th>
                        <th class="px-6 py-3">{{__('Creator')}}</th>
                        <th class="px-6 py-3">{{__('Status')}}</th>
                        <th class="px-6 py-3">{{__('Actions')}}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($profileRoles as $profileRole)
                        @php
                            $statusEnum = $profileRole->status;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profileRole->first_name}} {{ $profileRole->mid_name}} {{ $profileRole->last_name}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profileRole->creatorName}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{$statusEnum->realColor()}}-500"></span>
                                {{ __($statusEnum->label()) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">

                                <div class="flex space-x-3 items-center">
@can('changeStatus', $profileRole)
    <a href="{{ route('profile-roles.show-review', ['profileRole' => $profileRole->id]) }}"
       title="{{ __('Review Profile') }}"
       class="text-gray-600 hover:text-blue-600 mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5.121 17.804A9.964 9.964 0 0112 15c2.21 0 4.248.714 5.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </a>
@endcan

@can('update', $profileRole)
                                    <div class="flex items-center">
                                    <div class="mr-2">
                                    <!-- Edit (Pencil) -->
                                    <a href="{{ route('profile-roles.edit', $profileRole) }}" title="Edit" class="text-gray-600 hover:text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </a>
                                    </div>
                                    </div>
@endcan

@can('viewAny', \Spatie\Activitylog\Models\Activity::class)
                    <a href="{{ route('logs.index', ['model' => App\Models\ProfileRole::class, 'id' => $profileRole->id]) }}"
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
                            <td colspan="4" class="text-center py-6 text-gray-500">{{__("No profileRoles found.")}}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $profileRoles->links() }}
        </div>
    </div>
</x-app-layout>

