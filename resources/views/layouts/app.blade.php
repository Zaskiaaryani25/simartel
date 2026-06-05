<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring System - PT PLN (Persero) UID Lampung</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        plnBlue: '#00A2E9',
                        plnDark: '#004685',
                        softBg: '#F4F7FE',     
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { -webkit-font-smoothing: antialiased; background-color: #F4F7FE; overflow-x: hidden; }
        
        .sidebar-clean {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .nav-link {
            color: #64748b;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            overflow: hidden;
            white-space: nowrap;
        }

        .nav-active {
            background: #00A2E9;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 162, 233, 0.25);
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
            padding-left: 0 !important;
            padding-right: 0 !important;
            position: relative;
            width: 100%;
        }

        .sidebar-collapsed .nav-link i {
            margin: 0 !important;
            font-size: 1.25rem;
        }

        .sidebar-collapsed .nav-link span {
            display: none !important;
        }

        .sidebar-collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            margin-left: 10px;
            background: #1e293b;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            z-index: 100;
        }

        /* Header Tetap Di Atas (Sticky) */
        .header-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
        }

        main {
            transition: margin-left 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
    </style>
</head>

<body 
x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true',
    mobileOpen: false,
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    }
}" 
class="text-slate-900 font-sans">

    <aside 
        @click.stop
        @click.self="mobileOpen = false"
        :class="[
            sidebarOpen ? 'w-72' : 'w-20 sidebar-collapsed',
            mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
        class="sidebar-clean fixed inset-y-0 left-0 z-50 flex flex-col transform">

        <div class="lg:hidden flex justify-end p-3">
            <button @click="mobileOpen = false" class="text-xl text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="h-24 flex items-center px-6 shrink-0 border-b border-slate-50">
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="{{ asset('images/Logo-PLN.png') }}" class="h-10 w-auto shrink-0">
                <div x-show="sidebarOpen" class="whitespace-nowrap">
                    <h1 class="text-plnDark text-base font-extrabold uppercase">Monitoring</h1>
                    <p class="text-slate-400 text-[9px] font-bold uppercase mt-1">UID Lampung</p>
                </div>
            </div>
        </div>

       <nav class="flex-1 px-3 space-y-2 mt-6 overflow-y-auto">
    <a href="{{ route('dashboard') }}" 
       data-title="Dashboard"
       class="nav-link px-4 py-3.5 rounded-xl font-bold text-[13px] {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
        <i class="fa-solid fa-house-chimney text-lg w-6"></i>
        <span x-show="sidebarOpen" class="ml-4">Dashboard</span>
    </a>

    <a href="{{ route('karyawan.index') }}" 
   class="nav-link px-4 py-3.5 rounded-xl font-bold text-[13px] flex items-center {{ request()->routeIs('karyawan.*') ? 'nav-active' : '' }}">
    <i class="fa-solid fa-users fa-fw text-lg"></i> <span x-show="sidebarOpen" class="ml-4">Data Petugas</span>
</a>

<a href="{{ route('p2tl.index') }}" 
   class="nav-link px-4 py-3.5 rounded-xl font-bold text-[13px] flex items-center {{ request()->routeIs('p2tl.index') ? 'nav-active' : '' }}">
    <i class="fa-solid fa-file-invoice-dollar fa-fw text-lg"></i>
    <span x-show="sidebarOpen" class="ml-4">EPM P2TL</span>
</a>

<a href="{{ route('p2tl.awarding') }}" 
   class="nav-link px-4 py-3.5 rounded-xl font-bold text-[13px] flex items-center {{ request()->routeIs('p2tl.awarding') ? 'nav-active' : '' }}">
    <i class="fa-solid fa-award fa-fw text-lg"></i>
    <span x-show="sidebarOpen" class="ml-4">Leaderboard Capaian</span>
</a>
 
<a href="{{ route('ulp.index') }}"
   data-title="Monitoring ULP"
   class="nav-link px-4 py-3.5 rounded-xl font-bold text-[13px] {{ request()->routeIs('ulp.index') ? 'nav-active' : '' }}">
    <i class="fa-solid fa-city text-lg w-6"></i>
    <span x-show="sidebarOpen" class="ml-4">Monitoring ULP</span>
</a>

   
    </a>
</div>
    </a>
    </a>
</nav>

        </nav>

        <div class="p-4 border-t">
            <button @click="toggleSidebar()" 
                class="w-full h-10 flex items-center justify-center rounded-lg bg-slate-50">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </div>
    </aside>

    <div 
        x-show="mobileOpen"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden">
    </div>

    <main :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'" class="min-h-screen flex flex-col">
        
        <header class="h-20 header-glass sticky top-0 z-30 flex items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <button @click="mobileOpen = true" class="lg:hidden text-xl">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <h2 class="text-xl font-extrabold uppercase">Monitoring System</h2>
                    <p class="text-[10px] text-slate-400 uppercase">PT PLN (Persero) UID Lampung</p>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <span class="text-sm text-slate-600">{{ auth()->user()->username ?? 'User' }}</span>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-200">
                        <i class="fa-solid fa-user text-slate-500 text-sm"></i>
                    </div>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-100">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="p-8 flex-1">
            {{ $slot }}
        </div>

        <footer class="p-6 text-center text-xs text-slate-400">
            © 2026 PT PLN (Persero) UID Lampung. All rights reserved.
        </footer>
    </main>

    <script>
        setInterval(() => {
            const el = document.getElementById('clock');
            if (el) el.textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);
    </script>

</body>
</html>