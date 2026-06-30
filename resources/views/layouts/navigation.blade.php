<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white">
                            <x-icon name="sparkles" class="w-5 h-5" />
                        </span>
                        <span class="font-bold text-lg text-gray-800 dark:text-gray-100 hidden sm:inline">{{ config('app.name', 'Shop') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <x-icon name="box" class="w-4 h-4" />
                        {{ __('Catalogue') }}
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cart.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <x-icon name="cart" class="w-4 h-4" />
                        {{ __('Panier') }}
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('orders.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <x-icon name="truck" class="w-4 h-4" />
                        {{ __('Mes commandes') }}
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <span class="mx-1 h-5 w-px bg-gray-200 dark:bg-gray-700"></span>
                        <a href="{{ route('categories.index') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            <x-icon name="tag" class="w-4 h-4" />
                            {{ __('Catégories') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            <x-icon name="users" class="w-4 h-4" />
                            {{ __('Utilisateurs') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @if(auth()->user()->role === 'admin')
                    <span class="badge-indigo">
                        <x-icon name="shield-check" class="w-3.5 h-3.5" /> Admin
                    </span>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 font-semibold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden lg:inline">{{ Auth::user()->name }}</span>
                            <x-icon name="chevron-down" class="w-4 h-4" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Catalogue') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                {{ __('Panier') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                {{ __('Mes commandes') }}
            </x-responsive-nav-link>
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Catégories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Utilisateurs') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 font-semibold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
