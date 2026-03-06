@extends('layouts.public')

@section('title', 'Academy Charles Kabore - Accueil')

@section('content')
<div class="container-fluid mx-auto">
<!-- Hero Slider -->
<section id="accueil" class="slider">
  <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1920')">
    <div class="relative z-10 h-full flex items-center">
      <div class="container mx-auto px-6">
        <div class="max-w-4xl animate-slide-in-left">
          <h1 class="text-6xl md:text-8xl font-black mb-6 leading-tight">
            DEVENEZ<br/>
            <span class="gradient-text">UN CHAMPION</span>
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-gray-200 font-light">
            Former les champions de demain, sur le terrain et dans la vie
          </p>
          <div class="flex flex-wrap gap-4">
            <button class="px-8 py-4 bg-primary rounded-full font-bold text-lg hover:bg-primary/90 transition-all glow-button">
              Rejoignez-nous
            </button>
            <button class="px-8 py-4 bg-white/10 backdrop-blur-sm rounded-full font-bold text-lg hover:bg-white/20 transition-all border-2 border-white/30">
              En savoir plus
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=1920')">
    <div class="relative z-10 h-full flex items-center">
      <div class="container mx-auto px-6">
        <div class="max-w-4xl">
          <h1 class="text-6xl md:text-8xl font-black mb-6 leading-tight">
            ENTRAINEMENT<br/>
            <span class="gradient-text">D'EXCELLENCE</span>
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-gray-200 font-light">
            Encadrement professionnel par des coachs certifiés
          </p>
          <div class="flex flex-wrap gap-4">
            <button class="px-8 py-4 bg-primary rounded-full font-bold text-lg hover:bg-primary/90 transition-all glow-button">
              Découvrir nos programmes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1511886929837-354d827aae26?w=1920')">
    <div class="relative z-10 h-full flex items-center">
      <div class="container mx-auto px-6">
        <div class="max-w-4xl">
          <h1 class="text-6xl md:text-8xl font-black mb-6 leading-tight">
            PASSION<br/>
            <span class="gradient-text">& DISCIPLINE</span>
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-gray-200 font-light">
            Des valeurs fortes pour former des champions complets
          </p>
          <div class="flex flex-wrap gap-4">
            <button class="px-8 py-4 bg-primary rounded-full font-bold text-lg hover:bg-primary/90 transition-all glow-button">
              Nos valeurs
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Slider Navigation Dots -->
  <div class="slider-nav">
    <div class="slider-dot active" onclick="currentSlide(0)"></div>
    <div class="slider-dot" onclick="currentSlide(1)"></div>
    <div class="slider-dot" onclick="currentSlide(2)"></div>
  </div>
</section>

<!-- About Section -->
<section id="apropos" class="py-24 bg-slate-900">
  <div class="container mx-auto px-6">
    <div class="grid md:grid-cols-2 gap-16 items-center">
      <div class="relative reveal-left">
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl"></div>
        <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=800" alt="Charles Kaboré" class="rounded-2xl shadow-2xl relative z-10 w-full" loading="lazy" width="800" height="533"/>
      </div>
      
      <div class="reveal-right">
        <h3 class="text-primary font-bold mb-4 text-sm uppercase tracking-wider">Le mot du fondateur</h3>
        <h2 class="text-5xl font-black mb-6">Charles Kaboré</h2>
        <p class="text-xl text-gray-400 mb-4 font-light">Ancien Capitaine des Étalons du Burkina Faso</p>
        <p class="text-lg text-gray-300 leading-relaxed mb-6">
          "Passionné par le football et convaincu de son pouvoir éducatif, j'ai créé cette académie pour offrir aux jeunes talents un environnement où ils peuvent s'épanouir. Notre mission est de former la prochaine génération de champions, sur le terrain et dans la vie."
        </p>
        <button class="px-8 py-4 bg-primary rounded-full font-bold hover:bg-primary/90 transition-all glow-button">
          Notre histoire
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Programs Section -->
<section id="programmes" class="py-24 bg-slate-800">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16 reveal">
      <h3 class="text-primary font-bold mb-4 text-sm uppercase tracking-wider">Nos offres</h3>
      <h2 class="text-5xl font-black mb-6 text-white">Programmes de Formation</h2>
      <p class="text-xl text-gray-400 max-w-3xl mx-auto">
        Des programmes adaptés à tous les niveaux pour un développement complet
      </p>
    </div>

    <!-- École de Football - bloc horizontal -->
    <div class="card-hover reveal-scale bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
      <div class="flex flex-col md:flex-row md:items-center">
        <div class="md:w-1/3 p-8 md:p-12 flex items-center justify-center bg-slate-800/50">
          <div class="w-24 h-24 md:w-32 md:h-32 bg-primary/20 rounded-2xl flex items-center justify-center">
            <span class="material-symbols-outlined text-5xl md:text-7xl text-primary">sports_soccer</span>
          </div>
        </div>
        <div class="md:w-2/3 p-8 md:p-12">
          <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">École de Football</h3>
          <p class="text-gray-400 text-lg mb-8">Formation technique et tactique pour les 5 à 13 ans.</p>
          <div class="grid sm:grid-cols-2 gap-6 mb-8">
            <div class="flex items-start gap-4 p-4 bg-slate-800/60 rounded-xl border border-slate-700">
              <span class="material-symbols-outlined text-primary text-2xl shrink-0">check_circle</span>
              <div>
                <p class="font-bold text-white text-lg">Inscription : 20 000 F</p>
                <p class="text-gray-400 text-sm">Donne droit à un maillot + bas</p>
              </div>
            </div>
            <div class="flex items-start gap-4 p-4 bg-slate-800/60 rounded-xl border border-slate-700">
              <span class="material-symbols-outlined text-primary text-2xl shrink-0">check_circle</span>
              <div>
                <p class="font-bold text-white text-lg">Abonnement : 5 000 F la séance</p>
                <p class="text-gray-400 text-sm">Paiement à la séance</p>
              </div>
            </div>
          </div>
          <a href="{{ route('public.inscription') }}" class="inline-block px-8 py-4 bg-primary rounded-full font-bold hover:bg-primary/90 transition-all text-white">
            S'inscrire maintenant
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Actualités Section -->
<section id="actualites" class="py-24 bg-slate-900">
  <div class="container mx-auto px-6">
    <!-- Header -->
    <div class="text-center mb-16">
      <h3 class="text-primary font-bold mb-4 text-sm uppercase tracking-wider">Les dernières nouvelles</h3>
      <h2 class="text-5xl font-black mb-6 text-white">Actualités</h2>
      <p class="text-xl text-gray-400 max-w-3xl mx-auto">
        Suivez toutes les actualités de l'académie et les exploits de nos jeunes talents
      </p>
    </div>

    <!-- Articles Grid -->
    <div class="grid md:grid-cols-3 gap-8">
      
      <!-- Article 1 -->
      <article class="card-hover bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 group">
        <div class="relative h-64 overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800" 
            alt="Tournoi Inter-quartiers" 
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"
          />
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 bg-primary rounded-full text-sm font-bold">VICTOIRE</span>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
        </div>
        
        <div class="p-6">
          <div class="flex items-center gap-3 mb-4 text-sm text-gray-400">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">calendar_today</span>
              15 Juillet 2024
            </span>
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">schedule</span>
              5 min
            </span>
          </div>
          
          <h3 class="text-2xl font-bold mb-3 group-hover:text-primary transition-colors">
            Tournoi Inter-quartiers Remporté
          </h3>
          
          <p class="text-gray-400 mb-6 leading-relaxed">
            Nos jeunes U13 ont brillamment remporté le tournoi inter-quartiers de Ouagadougou avec une performance exceptionnelle. Bravo à toute l'équipe !
          </p>
          
          <button class="flex items-center gap-2 text-primary font-bold hover:gap-4 transition-all">
            Lire la suite
            <span class="material-symbols-outlined">arrow_forward</span>
          </button>
        </div>
      </article>

      <!-- Article 2 -->
      <article class="card-hover bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 group">
        <div class="relative h-64 overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=800" 
            alt="Visite Charles Kaboré" 
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"
          />
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 bg-secondary rounded-full text-sm font-bold">ÉVÉNEMENT</span>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
        </div>
        
        <div class="p-6">
          <div class="flex items-center gap-3 mb-4 text-sm text-gray-400">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">calendar_today</span>
              02 Août 2024
            </span>
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">schedule</span>
              3 min
            </span>
          </div>
          
          <h3 class="text-2xl font-bold mb-3 group-hover:text-primary transition-colors">
            Visite Inspirante de Charles Kaboré
          </h3>
          
          <p class="text-gray-400 mb-6 leading-relaxed">
            Le fondateur est venu encourager les académiciens et partager son expérience de capitaine des Étalons. Un moment inoubliable pour tous.
          </p>
          
          <button class="flex items-center gap-2 text-primary font-bold hover:gap-4 transition-all">
            Lire la suite
            <span class="material-symbols-outlined">arrow_forward</span>
          </button>
        </div>
      </article>

      <!-- Article 3 -->
      <article class="card-hover bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 group">
        <div class="relative h-64 overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?w=800" 
            alt="Inscriptions ouvertes" 
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"
          />
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 bg-accent text-slate-900 rounded-full text-sm font-bold">IMPORTANT</span>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
        </div>
        
        <div class="p-6">
          <div class="flex items-center gap-3 mb-4 text-sm text-gray-400">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">calendar_today</span>
              20 Août 2024
            </span>
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-lg">schedule</span>
              2 min
            </span>
          </div>
          
          <h3 class="text-2xl font-bold mb-3 group-hover:text-primary transition-colors">
            Inscriptions Saison 2024-2025 Ouvertes
          </h3>
          
          <p class="text-gray-400 mb-6 leading-relaxed">
            La nouvelle saison approche ! Les inscriptions pour 2024-2025 sont officiellement ouvertes. Places limitées, ne tardez pas !
          </p>
          
          <button class="flex items-center gap-2 text-primary font-bold hover:gap-4 transition-all">
            Lire la suite
            <span class="material-symbols-outlined">arrow_forward</span>
          </button>
        </div>
      </article>

    </div>

    <!-- View All Button -->
    <div class="text-center mt-12">
      <button class="px-8 py-4 bg-primary rounded-full font-bold text-lg hover:bg-primary/90 transition-all glow-button">
        Voir toutes les actualités
      </button>
    </div>
  </div>
</section>

@push('scripts')
<script src="{{ asset('js/welcome.js') }}" defer></script>
@endpush

<!-- CTA Banner -->
<section class="py-24 bg-gradient-to-r from-primary to-secondary relative overflow-hidden">
  <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1920')] opacity-10 bg-cover bg-center"></div>
  <div class="container mx-auto px-6 text-center relative z-10">
    <h2 class="text-5xl font-black mb-6">Prêt à commencer l'aventure ?</h2>
    <p class="text-2xl mb-10 font-light">Rejoignez l'Academy Charles Kaboré dès aujourd'hui</p>
    <a href="{{ route('public.inscription') }}" class="inline-block px-12 py-5 bg-white text-primary rounded-full font-bold text-lg hover:bg-gray-100 transition-all glow-button">
      Inscription en ligne
    </a>
  </div>
</section>






                </div>
@endsection
