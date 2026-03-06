<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACADEMY CHARLES KABORE - Admin Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #EF4444;
            --primary-orange: #F97316;
            --background-light: #F9FAFB;
            --card-light: #FFFFFF;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border-color: #E5E7EB;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background-light);
        }
    </style>
</head>
<body class="font-display">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-8 bg-[var(--card-light)] rounded-xl shadow-lg">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center mb-4">
                    <x-logo-ack class="h-20 w-auto object-contain" fallback-class="w-20 h-20" />
                </a>
                <h1 class="text-2xl font-bold text-[var(--text-dark)]">Connexion</h1>
                <p class="mt-2 text-sm text-[var(--text-light)]">Bienvenue, veuillez entrer vos identifiants.</p>
            </div>
            
            @if ($errors->any())
                <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-[var(--text-dark)]" for="email">Email</label>
                    <div class="mt-1">
                        <input autocomplete="email" class="w-full px-4 py-3 text-[var(--text-dark)] bg-gray-50 border border-[var(--border-color)] rounded-lg focus:ring-[var(--primary-orange)] focus:border-[var(--primary-orange)] placeholder-[var(--text-light)]" id="email" name="email" placeholder="you@example.com" required type="email" value="{{ old('email') }}" />
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[var(--text-dark)]" for="password">Mot de passe</label>
                    <div class="mt-1">
                        <input autocomplete="current-password" class="w-full px-4 py-3 text-[var(--text-dark)] bg-gray-50 border border-[var(--border-color)] rounded-lg focus:ring-[var(--primary-orange)] focus:border-[var(--primary-orange)] placeholder-[var(--text-light)]" id="password" name="password" placeholder="••••••••" required type="password" />
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[var(--primary-red)] hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-orange)] text-center transition-colors">
                        Se connecter
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <a href="/" class="text-sm text-[var(--primary-red)] hover:underline font-semibold">Retour à l'accueil</a>
            </div>
            
            <p class="text-center text-xs text-[var(--text-light)] mt-4">
                © {{ date('Y') }} ACADEMY CHARLES KABORE. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
