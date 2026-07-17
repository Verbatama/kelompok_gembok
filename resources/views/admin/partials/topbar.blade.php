<!-- Top Bar -->
<div class="sticky top-0 z-40 bg-white dark:bg-slate-900 shadow-md">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page Title (optional) -->
        <div class="hidden lg:block">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-white">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-4 ml-auto">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme(this)" class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-100 hover:bg-gray-200 border transition" title="Toggle Dark/Light Mode">

                <i class="fas fa-moon"></i>

            </button>
            <!-- Notification -->
            <div class="relative" x-data="{ open: false }">

                <!-- Bell -->
                <button @click="open = !open" class="relative flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition">

                    <i class="fas fa-bell text-lg text-gray-600 dark:text-gray-300"></i>

                    @if(($unreadCount ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px]
                       px-1 rounded-full bg-red-500 text-white
                       text-[10px] font-bold flex items-center justify-center">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-1/2 translate-x-1/2 mt-3 w-96 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 z-50" style="display:none;">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-3 border-b dark:border-slate-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white">
                            Notifications
                        </h3>

                        @if(($unreadCount ?? 0) > 0)
                        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                            @csrf
                            <button class="text-xs text-cyan-600 hover:text-cyan-700 font-medium">
                                Mark all as read
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- List -->
                    <div class="max-h-96 overflow-y-auto">

                        @forelse(($notifications ?? collect()) as $notification)

                        @php
                        $type = $notification->data['type'] ?? 'info';

                        $icons = [
                        'success' => 'fa-circle-check text-green-500',
                        'warning' => 'fa-triangle-exclamation text-yellow-500',
                        'danger' => 'fa-circle-xmark text-red-500',
                        'error' => 'fa-circle-xmark text-red-500',
                        'info' => 'fa-circle-info text-blue-500',
                        ];
                        @endphp

                        <a href="{{ route('admin.notifications.read', $notification->id) }}" class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-800 transition border-b border-gray-100 dark:border-slate-800">

                            <!-- Icon -->
                            <div class="pt-1">
                                <i class="fas {{ $icons[$type] ?? $icons['info'] }}"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">

                                <div class="flex justify-between items-start">

                                    <h4 class="font-medium text-sm text-gray-800 dark:text-white">
                                        {{ $notification->data['title'] }}
                                    </h4>

                                    @if(is_null($notification->read_at))
                                    <span class="w-2 h-2 bg-cyan-500 rounded-full mt-2"></span>
                                    @endif

                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $notification->data['message'] }}
                                </p>

                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>

                            </div>

                        </a>

                        @empty

                        <div class="py-10 text-center">

                            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>

                            <p class="text-gray-500">
                                No notifications
                            </p>

                        </div>

                        @endforelse

                    </div>

                </div>

            </div>
            <!-- Language Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-1 px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 border rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-globe"></i>
                    <span>{{ app()->getLocale() == 'id' ? 'ID' : 'EN' }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border z-50">
                    <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-50 {{ app()->getLocale() == 'en' ? 'text-cyan-600 font-medium' : 'text-gray-700' }}">
                        <span class="mr-2">🇺🇸</span> English
                    </a>
                    <a href="{{ route('language.switch', 'id') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-50 {{ app()->getLocale() == 'id' ? 'text-cyan-600 font-medium' : 'text-gray-700' }}">
                        <span class="mr-2">🇮🇩</span> Indonesia
                    </a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-3 py-2 transition">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">superadmin</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold shadow">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 hidden sm:block"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border z-50">
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="{{ route('admin.change-password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-key w-5 mr-3 text-gray-400"></i>
                            Ganti Password
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-cog w-5 mr-3 text-gray-400"></i>
                            Settings
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="border-t py-1">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
