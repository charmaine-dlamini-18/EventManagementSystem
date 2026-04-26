<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-bold text-gray-900 text-lg">EMS</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden sm:flex sm:ms-8 items-center gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index') || request()->routeIs('events.show')">
                        {{ __('Events') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.calendar')" :active="request()->routeIs('events.calendar')">
                        {{ __('Calendar') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('registrations.index')" :active="request()->routeIs('registrations.*')">
                            {{ __('Registrations') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <!-- Notifications -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <?php $user = Auth::user(); ?>
                                <?php $pendingCount = $user->registrations()->where('status', 'pending')->count(); ?>
                                @if($pendingCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $pendingCount }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">Notifications</div>
                            @if($pendingCount > 0)
                                <x-dropdown-link :href="route('registrations.index')">{{ $pendingCount }} pending registration(s)</x-dropdown-link>
                            @else
                                <div class="px-4 py-3 text-sm text-gray-500">No new notifications</div>
                            @endif
                        </x-slot>
                    </x-dropdown>

                    <!-- User Menu -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 p-1.5 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ explode(' ', $user->name)[0] }}</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 border-t border-gray-200">
            <x-responsive-nav-link :href="route('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')">Events</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.calendar')">Calendar</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('registrations.index')">Registrations</x-responsive-nav-link>
            @endauth
        </div>
        @auth
            <div class="pt-4 pb-3 border-t border-gray-200">
                <?php $user = Auth::user(); ?>
                <div class="px-4">
                    <div class="font-medium text-sm text-gray-900">{{ $user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>