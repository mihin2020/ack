<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ACADEMY CHARLES KABORE</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @if(request()->routeIs('admin.dashboard', 'admin.statistiques'))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    <livewire:styles />
    
    <!-- Alpine.js pour gérer les modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style type="text/tailwindcss">
        :root {
            --red-500: #EF4444;
            --orange-500: #F97316;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --white: #FFFFFF;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-100);
        }
        .sidebar {
            background-color: var(--gray-900);
            color: var(--white);
        }
        .sidebar-link {
            color: var(--gray-400);
        }
        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: var(--gray-700);
            color: var(--white);
        }
        .sidebar-link.active {
            background-color: var(--red-500);
        }
        .btn-primary {
            background-color: var(--red-500);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: #D93E3E;
        }
        .text-red {
            color: var(--red-500);
        }
        .text-orange {
            color: var(--orange-500);
        }
        .bg-red-soft {
            background-color: rgba(239, 68, 68, 0.1);
        }
        .bg-orange-soft {
            background-color: rgba(249, 115, 22, 0.1);
        }
        .bg-green-soft {
            background-color: rgba(34, 197, 94, 0.1);
        }
        .text-green-500 {
            color: #22C55E;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="sidebar w-64 flex flex-col p-4 space-y-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 pb-4 border-b border-gray-700">
                <x-logo-ack class="h-12 w-auto object-contain" />
                <span class="font-bold text-lg text-white">Academy Charles Kabore</span>
            </a>
            
            <nav class="flex-grow">
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center p-2 rounded-lg space-x-3" href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Aperçu</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.inscriptions') ? 'active' : '' }} flex items-center p-2 rounded-lg space-x-3" href="{{ route('admin.inscriptions') }}">
                    <span class="material-symbols-outlined">person_add</span>
                    <span>Inscriptions</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.liste-inscrits') ? 'active' : '' }} flex items-center p-2 rounded-lg space-x-3" href="{{ route('admin.liste-inscrits') }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>Liste des Inscrits</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }} flex items-center p-2 rounded-lg space-x-3" href="{{ route('admin.statistiques') }}">
                    <span class="material-symbols-outlined">query_stats</span>
                    <span>Statistiques</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.parametres') ? 'active' : '' }} flex items-center p-2 rounded-lg space-x-3" href="{{ route('admin.parametres') }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Paramètres</span>
                </a>
            </nav>
            
            <!-- Section Utilisateur Connecté -->
            <div class="border-t border-gray-700 pt-4 mb-4">
                <a href="{{ route('admin.profil') }}" class="flex items-center space-x-3 p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white truncate">{{ Auth::user()->nom_complet ?? 'Admin' }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ ucfirst(Auth::user()->role ?? 'Administrateur') }}</div>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                </a>
            </div>
            
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full flex items-center p-2 rounded-lg space-x-3">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
    <livewire:scripts />
</body>
</html>
