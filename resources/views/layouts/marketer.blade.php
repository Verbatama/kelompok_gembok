<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Marketer')</title>

    @vite([
    'resources/css/app.css'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-100">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 z-50 h-screen w-64 bg-slate-900 text-slate-400 border-r border-slate-800 transition-all duration-300">

        <!-- Header -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-slate-800">

            <span id="logo" class="text-lg font-semibold whitespace-nowrap text-white">
                Marketer
            </span>

            <button id="toggleSidebar" class="w-9 h-9 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

        <!-- Menu -->
        <nav class="p-3 space-y-1">

            <a href="{{ route('marketer.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-3 transition
                       {{ request()->routeIs('marketer.dashboard')
                          ? 'bg-indigo-600 text-white shadow-sm'
                          : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-house w-5 text-center"></i>

                <span class="menu-text whitespace-nowrap">
                    Dashboard
                </span>

            </a>

            <a href="{{ route('marketer.create_prospect') }}" class="flex items-center gap-3 rounded-lg px-3 py-3 transition
                       {{ request()->routeIs('marketer.create_prospect')
                          ? 'bg-indigo-600 text-white shadow-sm'
                          : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-user-plus w-5 text-center"></i>

                <span class="menu-text whitespace-nowrap">
                    Prospect
                </span>

            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-slate-400 hover:bg-slate-800 hover:text-white transition">

                <i class="fa-solid fa-cart-shopping w-5 text-center"></i>

                <span class="menu-text whitespace-nowrap">
                    Order
                </span>

            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-slate-400 hover:bg-slate-800 hover:text-white transition">

                <i class="fa-solid fa-chart-line w-5 text-center"></i>

                <span class="menu-text whitespace-nowrap">
                    Laporan
                </span>

            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-3 text-slate-400 hover:bg-slate-800 hover:text-white transition">

                <i class="fa-solid fa-gear w-5 text-center"></i>

                <span class="menu-text whitespace-nowrap">
                    Pengaturan
                </span>

            </a>

        </nav>

    </aside>

    <!-- Content -->
    <main id="content" class="ml-64 min-h-screen transition-all duration-300">

        <!-- Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">

            <h1 class="text-lg font-semibold text-slate-800">
                @yield('title', 'Dashboard')
            </h1>

            <div class="flex items-center gap-3">

                <div class="text-right">
                    <p class="text-sm font-medium text-slate-800">
                        Marketer
                    </p>

                    <p class="text-xs text-slate-500">
                        Online
                    </p>
                </div>

                <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center">
                    <i class="fa-solid fa-user"></i>
                </div>

            </div>

        </header>

        <!-- Page -->
        <div class="p-6">

            @yield('content')

        </div>

    </main>
    <script>
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const logo = document.getElementById("logo");
        const button = document.getElementById("toggleSidebar");

        function setSidebar(collapsed) {

            if (collapsed) {
                sidebar.classList.remove("w-64");
                sidebar.classList.add("w-20");

                content.classList.remove("ml-64");
                content.classList.add("ml-20");

                logo.classList.add("hidden");

                document.querySelectorAll(".menu-text").forEach(item => {
                    item.classList.add("hidden");
                });

                document.querySelectorAll("#sidebar a").forEach(item => {
                    item.classList.add("justify-center");
                });

            } else {

                sidebar.classList.remove("w-20");
                sidebar.classList.add("w-64");

                content.classList.remove("ml-20");
                content.classList.add("ml-64");

                logo.classList.remove("hidden");

                document.querySelectorAll(".menu-text").forEach(item => {
                    item.classList.remove("hidden");
                });

                document.querySelectorAll("#sidebar a").forEach(item => {
                    item.classList.remove("justify-center");
                });

            }

            localStorage.setItem("sidebarCollapsed", collapsed);
        }

        // Jalankan saat halaman dibuka
        const collapsed = localStorage.getItem("sidebarCollapsed") === "true";
        setSidebar(collapsed);

        // Saat tombol ditekan
        button.addEventListener("click", () => {
            const current = localStorage.getItem("sidebarCollapsed") === "true";
            setSidebar(!current);
        });

    </script>

</body>

</html>
