    <style>
        [x-cloak] {
            display: none !important;
        }

        .noc-sidebar {
            --noc-bg-1: #0B1220;
            --noc-bg-2: #111827;
            --noc-bg-3: #09111F;
            --noc-cyan: rgb(34, 211, 238);
            --noc-blue: rgb(59, 130, 246);
            --noc-text: #CBD5E1;
            --noc-text-dim: #64748B;
            --noc-text-white: #F1F5F9;
            --noc-border: rgba(148, 163, 184, 0.08);

            background: linear-gradient(180deg, var(--noc-bg-2) 0%, var(--noc-bg-1) 55%, var(--noc-bg-3) 100%);
            box-shadow: 4px 0 28px -10px rgba(0, 0, 0, 0.65), inset -1px 0 0 var(--noc-border);
            overflow: hidden;
        }

        /* ---------- ambient light / glass layer ---------- */
        .noc-glow-top,
        .noc-glow-bottom {
            position: absolute;
            border-radius: 9999px;
            pointer-events: none;
            filter: blur(26px);
            z-index: 0;
        }

        .noc-glow-top {
            top: -90px;
            left: -70px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.14), transparent 70%);
            animation: noc-float 9s ease-in-out infinite;
        }

        .noc-glow-bottom {
            bottom: -110px;
            right: -70px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12), transparent 70%);
            animation: noc-float 11s ease-in-out infinite reverse;
        }

        @keyframes noc-float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(18px);
            }
        }

        .noc-grid-overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.035) 1px, transparent 1px);
            background-size: 26px 26px;
            mask-image: linear-gradient(180deg, black, transparent 88%);
            -webkit-mask-image: linear-gradient(180deg, black, transparent 88%);
        }

        /* ---------- header logo ---------- */
        .noc-logo-badge {
            box-shadow:
                0 0 0 1px rgba(34, 211, 238, 0.25),
                0 0 22px -2px rgba(34, 211, 238, 0.55);
        }

        /* ---------- nav links ---------- */
        .nc-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            margin: 0 0.35rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            color: var(--noc-text);
            border: 1px solid transparent;
            transition: transform .3s cubic-bezier(.4, 0, .2, 1),
                background-color .3s cubic-bezier(.4, 0, .2, 1),
                border-color .3s cubic-bezier(.4, 0, .2, 1),
                color .3s cubic-bezier(.4, 0, .2, 1),
                box-shadow .3s cubic-bezier(.4, 0, .2, 1);
        }

        .nc-link i {
            width: 1.1rem;
            text-align: center;
            color: var(--noc-text-dim);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .nc-link:hover {
            transform: translateX(5px);
            background: rgba(34, 211, 238, 0.08);
            border-color: rgba(34, 211, 238, 0.14);
            color: var(--noc-text-white);
        }

        .nc-link:hover i {
            color: var(--noc-cyan);
            transform: scale(1.12);
        }

        .nc-link-active {
            background: linear-gradient(90deg, rgba(34, 211, 238, 0.18), rgba(59, 130, 246, 0.10));
            border-color: rgba(34, 211, 238, 0.28);
            color: var(--noc-text-white);
            box-shadow: 0 0 18px -4px rgba(34, 211, 238, 0.4), inset 0 0 0 1px rgba(34, 211, 238, 0.08);
        }

        .nc-link-active::before {
            content: '';
            position: absolute;
            left: -0.35rem;
            top: 0.35rem;
            bottom: 0.35rem;
            width: 3px;
            border-radius: 4px;
            background: linear-gradient(180deg, var(--noc-cyan), var(--noc-blue));
            box-shadow: 0 0 8px 1px rgba(34, 211, 238, 0.75);
        }

        .nc-link-active i {
            color: var(--noc-cyan);
        }

        /* ---------- submenu links ---------- */
        .nc-submenu {
            position: relative;
        }

        .nc-submenu::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, rgba(34, 211, 238, 0.35), transparent);
        }

        .nc-sublink {
            position: relative;
            display: block;
            padding: 0.5rem 0.9rem;
            border-radius: 0.6rem;
            font-size: 0.8125rem;
            color: var(--noc-text-dim);
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
        }

        .nc-sublink:hover {
            color: var(--noc-text-white);
            background: rgba(34, 211, 238, 0.06);
            transform: translateX(4px);
        }

        .nc-sublink-active {
            color: var(--noc-cyan);
            font-weight: 600;
            background: rgba(34, 211, 238, 0.08);
        }

        /* ---------- section labels / dividers ---------- */
        .nc-section-label {
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(103, 232, 249, 0.45);
            padding: 0 1.1rem;
            margin: 1.1rem 0 0.4rem;
        }

        .nc-divider {
            height: 1px;
            margin: 0.85rem 0.6rem;
            background: linear-gradient(90deg, transparent, rgba(34, 211, 238, 0.18), transparent);
        }

        /* ---------- dropdown chevron / icon spin ---------- */
        .nc-chevron {
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            color: var(--noc-text-dim);
        }

        .nc-chevron-open {
            transform: rotate(180deg);
            color: var(--noc-cyan);
        }

        .nc-spin {
            animation: nc-spin-once .5s cubic-bezier(.4, 0, .2, 1);
        }

        @keyframes nc-spin-once {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* ---------- badges ---------- */
        .nc-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 0 10px -1px rgba(239, 68, 68, 0.6);
            animation: nc-pulse 2.2s ease-in-out infinite;
        }

        @keyframes nc-pulse {

            0%,
            100% {
                box-shadow: 0 0 6px -1px rgba(239, 68, 68, 0.5);
            }

            50% {
                box-shadow: 0 0 15px 0px rgba(239, 68, 68, 0.85);
            }
        }

        /* ---------- bottom actions ---------- */
        .nc-btn {
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .nc-btn-password:hover {
            color: var(--noc-cyan);
            background: rgba(34, 211, 238, 0.08);
            box-shadow: 0 0 12px -2px rgba(34, 211, 238, 0.45);
        }

        .nc-btn-logout:hover {
            color: #fff;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            box-shadow: 0 0 14px -2px rgba(239, 68, 68, 0.55);
        }

        /* ---------- scrollbar ---------- */
        .noc-sidebar nav::-webkit-scrollbar {
            width: 4px;
        }

        .noc-sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(34, 211, 238, 0.25);
            border-radius: 4px;
        }

        .noc-sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ---------- toggle button ---------- */
        .noc-toggle-btn {
            background: linear-gradient(135deg, rgb(34, 211, 238), rgb(59, 130, 246));
            box-shadow: 0 0 12px -2px rgba(34, 211, 238, 0.6);
        }

    </style>

    <div class="noc-sidebar fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">


        {{-- ambient effects, purely decorative --}}
        <div class="noc-glow-top"></div>
        <div class="noc-glow-bottom"></div>
        <div class="noc-grid-overlay"></div>

        <div class="relative z-10 flex flex-col h-full">

            <div class="flex items-center justify-center h-16 bg-black/30 border-b border-cyan-500/10 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <div class="noc-logo-badge h-10 w-10 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-network-wired text-white"></i>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">{{ companyName() }}</span>
                </div>
            </div>

            <nav class="mt-4 px-2 space-y-1 overflow-y-auto flex-1" style="max-height: calc(100vh - 180px);">

                <p class="nc-section-label">Main Menu</p>

                <a href="{{ route('admin.dashboard') }}" class="nc-link {{ request()->routeIs('admin.dashboard') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <div x-data="{ openCustomers: {{ request()->routeIs('admin.customers.*') ? 'true' : 'false' }}, spinCustomers: false }">
                    <button @click="openCustomers = !openCustomers; spinCustomers = true; setTimeout(() => spinCustomers = false, 500)" class="nc-link w-full {{ request()->routeIs('admin.customers.*') ? 'nc-link-active' : '' }}">
                        <i class="fas fa-users" :class="spinCustomers ? 'nc-spin' : ''"></i>
                        <span>Customers</span>
                        <i class="fas fa-chevron-down nc-chevron ml-auto text-xs" :class="openCustomers ? 'nc-chevron-open' : ''"></i>
                    </button>

                    <div x-show="openCustomers" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 -translate-y-2 scale-95" class="nc-submenu mt-1 ml-4 pl-4 space-y-1 origin-top">
                        <a href="{{ route('admin.customers.index') }}" class="nc-sublink">All Customers</a>
                        <a href="{{ route('admin.customers.index', ['status' => 'active']) }}" class="nc-sublink">Active</a>
                        <a href="{{ route('admin.customers.index', ['status' => 'inactive']) }}" class="nc-sublink">Inactive</a>
                        <a href="{{ route('admin.customers.index', ['status' => 'suspended']) }}" class="nc-sublink">Suspended</a>
                    </div>
                </div>

                <a href="{{ route('admin.packages.index') }}" class="nc-link {{ request()->routeIs('admin.packages.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Packages</span>
                </a>

                <a href="{{ route('admin.invoices.index') }}" class="nc-link {{ request()->routeIs('admin.invoices.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Invoices</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="nc-link {{ request()->routeIs('admin.orders.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                    @php $pendingOrders = \App\Models\Order::where('status', 'pending')->count() ?? 0; @endphp
                    @if($pendingOrders > 0)
                    <span class="nc-badge ml-auto text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingOrders }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.expenses.index') }}" class="nc-link {{ request()->routeIs('admin.expenses.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-wallet"></i>
                    <span>Expenses</span>
                </a>

                <div class="nc-divider"></div>
                <p class="nc-section-label">Staff</p>

                <a href="{{ route('admin.technicians.index') }}" class="nc-link {{ request()->routeIs('admin.technicians.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-tools"></i>
                    <span>Technicians</span>
                </a>

                <a href="{{ route('admin.collectors.index') }}" class="nc-link {{ request()->routeIs('admin.collectors.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Collectors</span>
                </a>

                @if(!auth()->user()->is_superadmin)
                <a href="{{ route('admin.attendance.index') }}" class="nc-link {{ request()->routeIs('admin.attendance.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-fingerprint"></i>
                    <span>Absen </span>
                </a>
                @endif

                @if(!auth()->user()->is_superadmin)
                <a href="{{ route('admin.leave.index') }}" class="nc-link {{ request()->routeIs('admin.leave.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-umbrella-beach"></i>
                    <span>Pengajuan Libur</span>
                </a>
                @endif

                <a href="{{ route('admin.riwayat-libur.index') }}" class="nc-link {{ request()->routeIs('admin.riwayat-libur.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Libur Teknisi</span>
                </a>




                @if(auth()->user()->is_superadmin)

                <a href="{{ route('admin.admins.index') }}" class="nc-link {{ request()->routeIs('admin.admins.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Data Admin</span>
                </a>

                <a href="{{ route('admin.attendance-admin.history') }}" class="nc-link {{ request()->routeIs('admin.attendance-admin.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-user-clock"></i>
                    <span>Riwayat Absensi Admin</span>
                </a>

                <a href="{{ route('admin.riwayat-libur-admin.index') }}" class="nc-link {{ request()->routeIs('admin.riwayat-libur-admin.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-user-clock"></i>
                    <span>Riwayat Libur Admin</span>
                </a>

                @endif

                <a href="{{ route('admin.attendance.history') }}" class="nc-link {{ request()->routeIs('admin.attendance.history') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Absensi Teknisi</span>
                </a>



                <a href="{{ route('admin.payroll.index') }}" class="nc-link {{ request()->routeIs('admin.attendance.history') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-money-bills"></i>
                    <span>Payroll</span>
                </a>

                <a href="{{ route('admin.agents.index') }}" class="nc-link {{ request()->routeIs('admin.agents.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Agents</span>
                </a>

                <div class="nc-divider"></div>
                <p class="nc-section-label">Network</p>

                <a href="{{ route('admin.olt.index') }}" class="nc-link {{ request()->routeIs('admin.olt.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>OLT Management</span>
                </a>

                <a href="{{ route('admin.vouchers.index') }}" class="nc-link {{ request()->routeIs('admin.vouchers.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Vouchers</span>
                </a>

                <a href="{{ route('admin.network.odps.index') }}" class="nc-link {{ request()->routeIs('admin.network.odps.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>ODP Management</span>
                </a>

                <a href="{{ route('admin.network.map') }}" class="nc-link {{ request()->routeIs('admin.network.map') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Network Map</span>
                </a>

                <a href="{{ route('admin.network.tiangs.index') }}" class="nc-link {{ request()->routeIs('admin.network.tiangs.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-heading"></i>
                    <span>Tiang Management</span>
                </a>

                <div x-data="{ openModems: {{ request()->routeIs('admin.network.modems.*') ? 'true' : 'false' }}, spinModems: false }">
                    <button @click="openModems = !openModems; spinModems = true; setTimeout(() => spinModems = false, 500)" class="nc-link w-full {{ request()->routeIs('admin.network.modems.*') ? 'nc-link-active' : '' }}">
                        <i class="fas fa-hdd" :class="spinModems ? 'nc-spin' : ''"></i>
                        <span>Modem Management</span>
                        <i class="fas fa-chevron-down nc-chevron ml-auto text-xs" :class="openModems ? 'nc-chevron-open' : ''"></i>
                    </button>

                    <div x-show="openModems" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 -translate-y-2 scale-95" class="nc-submenu mt-1 ml-4 pl-4 space-y-1 origin-top">

                        <a href="{{ route('admin.network.modems.index') }}" class="nc-sublink">Semua Modem</a>
                        <a href="{{ route('admin.network.modems.index', ['status' => 'stok_gudang']) }}" class="nc-sublink">Stok Gudang</a>
                        <a href="{{ route('admin.network.modems.index', ['status' => 'terpasang']) }}" class="nc-sublink">Terpasang</a>
                        <a href="{{ route('admin.network.modems.index', ['status' => 'rusak']) }}" class="nc-sublink">Rusak</a>
                    </div>
                </div>

                <a href="{{ route('admin.network.backbones.index') }}" class="nc-link {{ request()->routeIs('admin.network.backbones.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-route"></i>
                    <span>Backbone Management</span>
                </a>

                <div class="nc-divider"></div>
                <p class="nc-section-label">Services</p>

                <a href="{{ route('admin.mikrotik.index') }}" class="nc-link {{ request()->routeIs('admin.mikrotik.index') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-server"></i>
                    <span>Mikrotik</span>
                </a>

                <a href="{{ route('admin.mikrotik.sync.index') }}" class="nc-link {{ request()->routeIs('admin.mikrotik.sync.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-sync-alt"></i>
                    <span>Mikrotik Sync</span>
                </a>

                <a href="{{ route('admin.radius.index') }}" class="nc-link {{ request()->routeIs('admin.radius.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>RADIUS</span>
                </a>

                <a href="{{ route('admin.cpe.index') }}" class="nc-link {{ request()->routeIs('admin.cpe.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-wifi"></i>
                    <span>CPE / ONU</span>
                </a>

                <a href="{{ route('admin.snmp.index') }}" class="nc-link {{ request()->routeIs('admin.snmp.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>SNMP Monitor</span>
                </a>

                <a href="{{ route('admin.ip-monitor.index') }}" class="nc-link {{ request()->routeIs('admin.ip-monitor.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-network-wired"></i>
                    <span>IP Monitor</span>
                    @php $downIps = \App\Models\IpMonitor::active()->down()->count() ?? 0; @endphp
                    @if($downIps > 0)
                    <span class="nc-badge ml-auto text-white text-xs px-2 py-0.5 rounded-full">{{ $downIps }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.whatsapp.index') }}" class="nc-link {{ request()->routeIs('admin.whatsapp.*') ? 'nc-link-active' : '' }}">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>

                <a href="{{ route('admin.payment.index') }}" class="nc-link {{ request()->routeIs('admin.payment.*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment Gateway</span>
                </a>

                <div class="nc-divider"></div>
                <p class="nc-section-label">GenieACS Dashboard</p>
                <a href="{{ route('admin.genieacs.dashboard') }}" class="nc-link {{ request()->routeIs('admin.genieacs.dasboard') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.genieacs.devices') }}" class="nc-link {{ request()->routeIs('admin.genieacs.devices') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-network-wired"></i>
                    <span>Devices</span>
                </a>
                <a href="{{ route('admin.genieacs.configuration') }}" class="nc-link {{ request()->routeIs('admin.genieacs.configuration') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-sliders"></i>
                    <span>Configuration</span>
                </a>



                <div class="nc-divider"></div>
                <p class="nc-section-label">Reports</p>

                <a href="{{ route('admin.reports.index') }}" class="nc-link {{ request()->routeIs('admin.reports.index') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Overview</span>
                </a>

                <a href="{{ route('admin.reports.daily') }}" class="nc-link {{ request()->routeIs('admin.reports.daily') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-calendar-day"></i>
                    <span>Daily Report</span>
                </a>

                <a href="{{ route('admin.reports.monthly') }}" class="nc-link {{ request()->routeIs('admin.reports.monthly') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Monthly Report</span>
                </a>

                <div class="nc-divider"></div>
                <p class="nc-section-label">Support</p>

                <div x-data="{ openTickets: {{ request()->routeIs('admin.ticket_gangguan.*') || request()->routeIs('admin.tickets.*') ? 'true' : 'false' }}, spinTickets: false }">
                    <button @click="openTickets = !openTickets; spinTickets = true; setTimeout(() => spinTickets = false, 500)" class="nc-link w-full {{ request()->routeIs('admin.ticket_gangguan.*') || request()->routeIs('admin.tickets.*') ? 'nc-link-active' : '' }}">
                        <i class="fas fa-headset flex-shrink-0" :class="spinTickets ? 'nc-spin' : ''"></i>
                        <span class="truncate text-left flex-1">Ticket Management</span>
                        <i class="fas fa-chevron-down nc-chevron text-xs flex-shrink-0" :class="openTickets ? 'nc-chevron-open' : ''"></i>
                    </button>

                    <div x-show="openTickets" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 -translate-y-2 scale-95" class="nc-submenu mt-1 ml-4 pl-4 space-y-1 origin-top">
                        <a href="{{ route('admin.ticket_gangguan.index') }}" class="nc-sublink {{ request()->routeIs('admin.ticket_gangguan.*') ? 'nc-sublink-active' : '' }}">Tiket Gangguan</a>
                        <a href="{{ route('admin.tickets.index') }}" class="nc-sublink {{ request()->routeIs('admin.tickets.*') ? 'nc-sublink-active' : '' }}">Tiket Umum</a>
                    </div>
                </div>

                <div class="nc-divider"></div>
                <p class="nc-section-label">Settings</p>

                <a href="{{ route('admin.settings.integrations') }}" class="nc-link {{ request()->routeIs('admin.settings.integrations') || request()->routeIs('admin.settings.mikrotik*') || request()->routeIs('admin.settings.radius*') || request()->routeIs('admin.settings.genieacs*') || request()->routeIs('admin.settings.whatsapp*') || request()->routeIs('admin.settings.midtrans*') || request()->routeIs('admin.settings.xendit*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-plug"></i>
                    <span>Integrasi</span>
                </a>

                <a href="{{ route('admin.api-docs') }}" class="nc-link {{ request()->routeIs('admin.api-docs') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-code"></i>
                    <span>API Docs</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="nc-link {{ request()->routeIs('admin.settings') && !request()->is('admin/settings/*') ? 'nc-link-active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>General</span>
                </a>
            </nav>

            <div class="p-4 bg-gradient-to-t from-black/40 to-transparent border-t border-cyan-500/10 backdrop-blur-sm">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.change-password') }}" class="nc-btn nc-btn-password flex-1 flex items-center justify-center px-3 py-2 text-gray-300 rounded-lg text-sm">
                        <i class="fas fa-key mr-2"></i>Password
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="nc-btn nc-btn-logout w-full flex items-center justify-center px-3 py-2 text-gray-300 rounded-lg text-sm">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Backdrop mobile - klik buat nutup. Sengaja diluar .noc-sidebar supaya fixed-nya nempel ke layar, bukan ke sidebar --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Tombol toggle - nempel di tengah tepi sidebar, juga diluar .noc-sidebar --}}
    <button @click="sidebarOpen = !sidebarOpen" class="noc-toggle-btn fixed top-1/2 -translate-y-1/2 z-50 flex items-center justify-center w-8 h-8 rounded-full text-white transition-all duration-300 ease-in-out lg:hidden" :style="{ left: sidebarOpen ? '16rem' : '0' }">
        <i class="fas fa-chevron-left text-xs transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'"></i>
    </button>
