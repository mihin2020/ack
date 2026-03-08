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
      <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 shrink-0 min-h-[44px] items-center">
        <span class="animate-float"><x-logo-ack class="h-10 w-10 sm:h-12 sm:w-auto object-contain" /></span>
      </a>
      
      <div class="hidden lg:flex items-center gap-6 xl:gap-8">
        <a href="{{ url('/') }}#accueil" class="nav-link text-sm font-medium">Accueil</a>
        <a href="{{ url('/') }}#apropos" class="nav-link text-sm font-medium">À propos</a>
        <a href="{{ url('/') }}#programmes" class="nav-link text-sm font-medium">Programmes</a>
        <a href="{{ url('/') }}#actualites" class="nav-link text-sm font-medium">Actualités</a>
        <a class="text-sm font-medium transition-colors bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md shrink-0" href="{{ url('/inscription') }}">Inscription</a>
        <a class="text-sm font-medium transition-colors bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md shrink-0" href="{{ url('/reservation') }}">Réservation</a>
      </div>

      <button id="mobile-menu-btn" type="button" class="lg:hidden text-white p-3 rounded-lg hover:bg-white/10 active:bg-white/20 transition-colors touch-manipulation min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Ouvrir le menu" aria-expanded="false">
        <span class="material-symbols-outlined text-3xl">menu</span>
      </button>
    </div>
  </div>
  <!-- Menu mobile (overlay + panneau) - z-index élevé pour passer au-dessus du contenu -->
  <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black/70 z-[100] backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" aria-hidden="true"></div>
  <div id="mobile-menu" class="fixed top-0 right-0 bottom-0 w-[min(100%,20rem)] max-w-sm bg-slate-900 shadow-2xl z-[110] flex flex-col opacity-0 pointer-events-none translate-x-full transition-all duration-300 ease-out lg:hidden border-l border-slate-700" aria-hidden="true" style="padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); padding-right: env(safe-area-inset-right);">
    <div class="flex items-center justify-between p-4 border-b border-slate-700 shrink-0">
      <span class="text-white font-bold text-lg">Menu</span>
      <button id="mobile-menu-close" type="button" class="text-white p-3 -m-2 rounded-lg hover:bg-white/10 active:bg-white/20 transition-colors touch-manipulation min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Fermer le menu">
        <span class="material-symbols-outlined text-3xl">close</span>
      </button>
    </div>
    <div class="flex flex-col gap-1 p-4 overflow-y-auto flex-1 min-h-0">
      <a href="{{ url('/') }}#accueil" class="nav-link-mobile rounded-lg px-4 py-3.5 min-h-[48px] flex items-center text-white font-medium">Accueil</a>
      <a href="{{ url('/') }}#apropos" class="nav-link-mobile rounded-lg px-4 py-3.5 min-h-[48px] flex items-center text-white font-medium">À propos</a>
      <a href="{{ url('/') }}#programmes" class="nav-link-mobile rounded-lg px-4 py-3.5 min-h-[48px] flex items-center text-white font-medium">Programmes</a>
      <a href="{{ url('/') }}#actualites" class="nav-link-mobile rounded-lg px-4 py-3.5 min-h-[48px] flex items-center text-white font-medium">Actualités</a>
      <a href="{{ url('/inscription') }}" class="rounded-lg px-4 py-3.5 min-h-[48px] flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-medium mt-2">Inscription</a>
      <a href="{{ url('/reservation') }}" class="rounded-lg px-4 py-3.5 min-h-[48px] flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium">Réservation</a>
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
      var closeBtn = document.getElementById('mobile-menu-close');
      var menu = document.getElementById('mobile-menu');
      var backdrop = document.getElementById('mobile-menu-backdrop');
      function openMenu() {
        if (!menu || !backdrop) return;
        menu.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-full');
        menu.classList.add('translate-x-0', 'opacity-100', 'pointer-events-auto');
        menu.setAttribute('aria-hidden', 'false');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');
        if (btn) btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
      }
      function closeMenu() {
        if (!menu || !backdrop) return;
        menu.classList.add('opacity-0', 'pointer-events-none', 'translate-x-full');
        menu.classList.remove('translate-x-0', 'opacity-100', 'pointer-events-auto');
        menu.setAttribute('aria-hidden', 'true');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        if (btn) btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
      if (btn) btn.addEventListener('click', openMenu);
      if (closeBtn) closeBtn.addEventListener('click', closeMenu);
      if (backdrop) backdrop.addEventListener('click', closeMenu);
      document.querySelectorAll('#mobile-menu .nav-link-mobile, #mobile-menu a').forEach(function(link) {
        link.addEventListener('click', closeMenu);
      });
    })();
    </script>
    @stack('scripts')
    <livewire:scripts />
</body>
</html>





