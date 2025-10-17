<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PROTALIST</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles') <!-- Stack untuk CSS tambahan -->

    <!-- jQuery and Select2 CDN - Stable versions without integrity issues -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js?v={{ time() }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css?v={{ time() }}" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js?v={{ time() }}"></script>

    <!-- Dependencies ready signal -->
    <script>
        $(document).ready(function() {
            // Signal that dependencies are ready
            window.dependenciesReady = true;
            window.$ = $;
            window.jQuery = $;
            window.dispatchEvent(new CustomEvent('dependenciesLoaded'));
            console.log('✅ jQuery and Select2 loaded from CDN and ready');
        });
    </script>

    <!-- Global cities data -->
    <script src="{{ asset('js/cities-data.js') }}"></script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay opacity-0 pointer-events-none lg:hidden"></div>

    <div class="flex h-full">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar sidebar-mobile">
            <!-- Close button for mobile -->
            <button class="lg:hidden absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200" id="sidebarClose" aria-label="Close sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Logo -->
            <div class="flex items-center px-6 py-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">PROTALIST</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dashboard</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 px-4 pb-4 overflow-y-auto custom-scrollbar">
                <nav class="space-y-2">
                    <!-- Project -->
                    <a href="{{ route('home') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Master Dropdown -->
                    <div class="space-y-1">
                        <button class="nav-link w-full justify-between group" id="masterDropdown" data-dropdown-toggle="masterSubmenu">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                                <span>Master</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="masterSubmenu" class="pl-8 space-y-1 {{ request()->is('master*') ? 'block' : 'hidden' }}">
                            @can('manage-master')
                            <a href="{{ route('master.project.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/project*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                                </svg>
                                <span>Project</span>
                            </a>
                            <a href="{{ route('master.worker.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/worker') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span>Pekerja</span>
                            </a>
                            <a href="{{ route('master.category.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/category*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v13a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4" />
                                </svg>
                                <span>Kategori</span>
                            </a>
                            <a href="{{ route('master.material.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/material') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Material</span>
                            </a>
                            <a href="{{ route('master.equipment.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/equipment') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Peralatan</span>
                            </a>
                            @endcan

                            <!-- Jurnal Pekerjaan -->
                            <a href="{{ route('master.journal.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/journal*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                                </svg>
                                <span>Jurnal Pekerjaan</span>
                            </a>

                            <!-- AHS -->
                            <a href="{{ route('master.estimation.index') }}" class="nav-link text-sm py-2 {{ request()->is('master/estimation') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4M7 7h.01M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                </svg>
                                <span>AHS</span>
                            </a>
                        </div>
                    </div>

                    <!-- HPP -->
                    <a href="{{ route('hpp.index') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>HPP</span>
                    </a>

                    <!-- Form Service -->
                    <a href="{{ route('service.index') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Form Service</span>
                    </a>

                    <!-- Users Management -->
                    @can('manage-users')
                    <a href="{{ route('users.index') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <span>Users</span>
                    </a>
                    @endcan

                    <!-- Settings -->
                    <a href="{{ route('settings.edit') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Settings</span>
                    </a>

                    <!-- Support -->
                    <!-- <a href="{{ url('/support') }}" class="nav-link group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 14v.01M12 10v.01M16 10v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Support</span>
                    </a> -->

                </nav>

            </div>

            <!-- Sidebar Footer -->
            <div class="px-6 py-4 dark:border-gray-700 text-bottom-0">
                <!-- User dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User" class="w-8 h-8 rounded-full object-cover">
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="dropdown-menu">
                        <a href="{{ route('settings.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 gap-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 gap-2">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    </div>
                </div>
            </div>

        </nav>

        <!-- Main Content -->
        <div class="main-content flex-1 flex flex-col">
            <!-- Header -->
            <header class="header flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-start space-x-4">
                    <!-- Logo Pertamina -->
                    <div class="flex items-center space-x-3 rounded-lg">
                        <img src="{{ asset('images/PGN MAS.png') }}" style="width: 155px;" alt="PGN MAS">
                    </div>
                </div>

                <!-- Kiri: tombol menu & logo mobile -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200"
                        id="sidebarToggle" aria-label="Toggle sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Logo for mobile -->
                    <div class="flex items-center space-x-3 lg:hidden">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">TKDN</span>
                    </div>
                </div>

                <!-- Kanan: toggle dark mode & logo Pertamina -->
                <div class="flex items-center justify-end space-x-4">
                    <!-- Dark mode toggle -->
                    <!-- <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="darkIcon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                        <svg class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="lightIcon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </button> -->

                    <!-- Logo Pertamina -->
                    <div class="flex items-center space-x-3 rounded-lg">
                        <img src="{{ asset('images/icon-pertamina.png') }}" class="w-8" alt="Pertamina">
                        <h2 class="text-xl font-bold m-0">PERTAMINA</h2>
                    </div>
                </div>
            </header>


            <!-- Page Content -->
            <main class=" flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js for dropdowns -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Sidebar mobile toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
            sidebarOverlay.classList.add('opacity-100', 'pointer-events-auto');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
            sidebarOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        }

        sidebarToggle?.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
                sidebarOverlay.classList.remove('opacity-100', 'pointer-events-auto');
            }
        });

        // Master dropdown toggle
        const masterDropdown = document.getElementById('masterDropdown');
        const masterSubmenu = document.getElementById('masterSubmenu');

        masterDropdown?.addEventListener('click', function() {
            const isOpen = masterSubmenu.classList.contains('hidden');
            masterSubmenu.classList.toggle('hidden');

            // Rotate arrow icon
            const arrow = this.querySelector('svg:last-child');
            if (isOpen) {
                arrow.style.transform = 'rotate(180deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        });

        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkIcon = document.getElementById('darkIcon');
        const lightIcon = document.getElementById('lightIcon');
        const html = document.documentElement;

        function updateDarkMode() {
            const isDark = html.classList.contains('dark');
            darkIcon.classList.toggle('hidden', isDark);
            lightIcon.classList.toggle('hidden', !isDark);
        }

        // Initialize dark mode
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
        updateDarkMode();

        darkModeToggle?.addEventListener('click', function() {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            updateDarkMode();
        });

        // Logout
        const logoutTime = 15 * 60 * 1000; // 15 menit
        let logoutTimer = setTimeout(autoLogout, logoutTime);

        function autoLogout() {
            window.location.href = '{{ route("welcome") }}';
        }

        // Reset timer jika ada aktivitas
        ['click', 'mousemove', 'keydown', 'scroll'].forEach(event => {
            window.addEventListener(event, () => {
                clearTimeout(logoutTimer);
                logoutTimer = setTimeout(autoLogout, logoutTime);
            });
        });
    </script>

    <!-- Initialize Select2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for dependencies to be loaded
            if (window.dependenciesReady) {
                initializeSelect2();
            } else {
                window.addEventListener('dependenciesLoaded', initializeSelect2);
            }
        });

        function initializeSelect2() {
            // Initialize all select2 elements
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2({
                    placeholder: 'Pilih opsi...',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "Tidak ada hasil ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                });
                console.log('✅ Select2 initialized successfully');
            } else {
                console.error('❌ Select2 not available');
            }
        }
    </script>

    @stack('scripts') <!-- Stack untuk JS tambahan -->
</body>

</html>