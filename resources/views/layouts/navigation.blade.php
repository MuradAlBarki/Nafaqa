<nav x-data="{ open: false, openNotifications: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                       <img src="{{ asset('images/logo.png') }}" alt="شعار الموقع" class="h-16 mx-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link class="me-8" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Admin or user with permission -->
                    @if(auth()->user()->can('users.viewAny'))
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->can('profileRoles.viewAny'))
                        <x-nav-link :href="route('profile-roles.index')" :active="request()->routeIs('profile-roles.*')">
                        {{ __('profileRoles') }}
                        </x-nav-link>

                    @endif

                    @if(auth()->user()->can('divorceCases.viewAny'))
                        <x-nav-link :href="route('divorce-cases.index')" :active="request()->routeIs('divorce-cases.*')">
                            {{ __('Divorce Cases') }}
                        </x-nav-link>
                    @endif

                    <!-- Normal user -->
                    @if(auth()->user()->getAllPermissions()->isEmpty())
                        @if(auth()->user()->profileRole()->exists())
                            <x-nav-link :href="route('profile-roles.show', auth()->user()->profileRole->id)" 
                                        :active="request()->routeIs('profile-roles.show')">
                                {{ __('My Profile') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('profile-roles.create')" 
                                        :active="request()->routeIs('profile-roles.create')">
                                {{ __('Create') }} {{ __('Profile') }}
                            </x-nav-link>
                        @endif
                        
                        <x-nav-link :href="route('divorce-cases.userIndex')" :active="request()->routeIs('divorce-cases.*')">
                            {{ __('Divorce Cases') }}
                        </x-nav-link>
                    @endif


@if(auth()->user()->can('exportLatePayments', App\Models\Payment::class) 
    || auth()->user()->can('export', App\Models\ProfileRole::class))              
<div x-data="{
        open: {{ request()->routeIs('profile-roles.export','payments.export-late') ? 'true' : 'false' }},
        toggle() { this.open = !this.open },
        isActive() { return this.open || {{ request()->routeIs('profile-roles.export','payments.export-late') ? 'true' : 'false' }} }
    }" 
     x-on:click.away="open = false"
     class="relative space-x-8 sm:-my-px sm:ms-10 sm:flex">

    <!-- Parent Reports -->
    <x-nav-link 
        href="#"
        @click.prevent="toggle()"
        :active="request()->routeIs('profile-roles.export','payments.export-late')"
        class="flex items-center"
    >
        {{ __('Reports') }}
        <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-1 transform transition-transform"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </x-nav-link>

    <!-- Child links -->
    <div x-show="open" x-collapse 
         class="absolute top-full right-0 bg-white border rounded-lg shadow-md mt-1 w-48 z-50">
        <div class="py-1">
            @can('export', App\Models\ProfileRole::class)
                <x-nav-link :href="route('profile-roles.export')" 
                            :active="request()->routeIs('profile-roles.export')"
                            class="block px-4 py-2">
                    {{ __('Export Profiles') }}
                </x-nav-link>
            @endcan

            @can('exportLatePayments', App\Models\Payment::class)
                <x-nav-link :href="route('payments.export-late')" 
                            :active="request()->routeIs('payments.export-late')"
                            class="block px-4 py-2">
                    {{ __('Export Late Payments') }}
                </x-nav-link>
            @endcan
        </div>
    </div>
</div>

@endif







                </div>
            </div>

            <!-- Settings Dropdown + Notifications -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications Bell -->
                <div class="relative ml-4">
                    @php
                        $notifications = auth()->user()->unreadNotifications;
                        $unreadCount = $notifications->count();
                    @endphp

                    <button @click="openNotifications = !openNotifications" class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <!-- Bell Icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>

                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="openNotifications" @click.away="openNotifications = false"
                         class="absolute left-0 mt-2 w-80 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                        <div class="p-2">
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" 
   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __($notification->data['message']) ?? 'Notification' }}
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-2 text-sm text-gray-500">{{__('No new notifications')}}</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
<!-- User Dropdown like Reports -->
<div x-data="{ open: false }" x-on:click.away="open = false" class="relative ms-4">
    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
        <div>{{ Auth::user()->name }}</div>
        <div class="ms-1">
            <svg :class="{'rotate-180': open}" class="fill-current h-4 w-4 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-collapse class="absolute top-full left-0 bg-white border rounded-lg shadow-md mt-1 w-48 z-50">
        <div class="py-1">
            <x-nav-link :href="route('profile.edit')" class="block px-4 py-2">
                {{ __('Profile') }}
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link :href="route('logout')" class="block px-4 py-2"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-nav-link>
            </form>
        </div>
    </div>
</div>

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
