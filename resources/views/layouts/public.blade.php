<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Academy Charles Kabore')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="{{ asset('css/public.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
    tailwind.config = { darkMode: "class", theme: { extend: { colors: { primary: "#DC2626", secondary: "#F97316", accent: "#FCD34D" }, fontFamily: { sans: ["Poppins", "sans-serif"] } } } };
    </script>
    <livewire:styles />
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-200">
    <div class="flex flex-col min-h-screen">
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300">
  <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
    <div class="flex items-center justify-between gap-2">
      <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 shrink-0">
        <span class="animate-float"><x-logo-ack class="h-10 w-10 sm:h-12 sm:w-auto object-contain" /></span>
      </a>

      <div class="hidden lg:flex items-center gap-6 xl:gap-8">
        <a href="{{ url('/') }}#accueil" class="nav-link text-sm font-medium">Accueil</a>
        <a href="{{ url('/') }}#apropos" class="nav-link text-sm font-medium">À propos</a>
        <a href="{{ url('/') }}#programmes" class="nav-link text-sm font-medium">Programmes</a>
        <a href="{{ url('/') }}#actualites" class="nav-link text-sm font-medium">Actualités</a>
        <a class="text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg" href="{{ url('/inscription') }}">Inscription</a>
        <a class="text-sm font-medium bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg" href="{{ url('/reservation') }}">Réservation</a>
      </div>

      <button id="mobile-menu-btn" type="button" class="lg:hidden text-white p-2 rounded-lg hover:bg-white/10" aria-label="Menu">
        <span class="material-symbols-outlined text-3xl">menu</span>
      </button>
    </div>

    <!-- Menu mobile : bloc sous la barre, affiché au clic -->
    <div id="mobile-menu" class="nav-mobile-menu">
      <a href="{{ url('/') }}#accueil" class="nav-mobile-link">Accueil</a>
      <a href="{{ url('/') }}#apropos" class="nav-mobile-link">À propos</a>
      <a href="{{ url('/') }}#programmes" class="nav-mobile-link">Programmes</a>
      <a href="{{ url('/') }}#actualites" class="nav-mobile-link">Actualités</a>
      <a href="{{ url('/inscription') }}" class="nav-mobile-link nav-mobile-btn nav-mobile-btn-red">Inscription</a>
      <a href="{{ url('/reservation') }}" class="nav-mobile-link nav-mobile-btn nav-mobile-btn-green">Réservation</a>
    </div>
  </div>
</nav>



        <main class="flex-grow {{ request()->routeIs(['public.inscription', 'public.reservation']) ? 'pt-20 sm:pt-24' : '' }}">
            @yield('content')
        </main>
        
       <!-- Footer -->
<footer class="bg-slate-950 py-10 sm:py-12 md:py-16">
  <div class="container mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10 md:gap-12 mb-8 md:mb-12">
      <div>
        <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 mb-4">
          <x-logo-ack class="h-10 w-10 sm:h-14 sm:w-auto object-contain" />
          <span class="text-lg sm:text-xl text-white font-bold">Academy Charles Kabore</span>
        </a>
        <p class="text-gray-400">Former les champions de demain</p>
      </div>

      <div>
        <h4 class="font-bold text-lg mb-4 text-white">Navigation</h4>
        <ul class="space-y-2">
          <li><a href="{{ url('/') }}#accueil" class="text-gray-400 hover:text-primary transition-colors">Accueil</a></li>
          <li><a href="{{ url('/') }}#apropos" class="text-gray-400 hover:text-primary transition-colors">À propos</a></li>
          <li><a href="{{ url('/') }}#programmes" class="text-gray-400 hover:text-primary transition-colors">Programmes</a></li>
          <li><a href="{{ url('/') }}#actualites" class="text-gray-400 hover:text-primary transition-colors">Actualités</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-bold text-lg mb-4 text-white">Contact</h4>
        <ul class="space-y-3 text-gray-400">
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">call</span>
            (+226) 78495542 / 64707171
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">mail</span>
            <a href="mailto:academiecharleskabore@gmail.com" class="hover:text-primary transition-colors">academiecharleskabore@gmail.com</a>
          </li>
          <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">location_on</span>
            OUAGA 2000
          </li>
        </ul>
      </div>
    </div>

    <div class="border-t border-slate-800 pt-6 sm:pt-8 text-center text-gray-400 text-sm sm:text-base">
      <p>© 2025 Academy Charles Kaboré. Tous droits réservés.</p>
    </div>
  </div>
</footer>
    </div>
    <script>
    (function() {
      var btn = document.getElementById('mobile-menu-btn');
      var nav = document.getElementById('navbar');
      var menu = document.getElementById('mobile-menu');
      if (btn && nav) {
        btn.addEventListener('click', function() { nav.classList.toggle('open'); });
        if (menu) {
          menu.querySelectorAll('a').forEach(function(a) {
            a.addEventListener('click', function() { nav.classList.remove('open'); });
          });
        }
      }
    })();
    </script>
    @stack('scripts')
    <livewire:scripts />
</body>
</html>





