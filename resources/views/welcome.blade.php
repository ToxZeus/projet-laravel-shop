<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Shop') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-gray-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">
            <header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30">
                        <x-icon name="sparkles" class="w-6 h-6" />
                    </span>
                    <span class="font-bold text-xl">{{ config('app.name', 'Shop') }}</span>
                </div>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">
                            Aller au catalogue
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
                    @endauth
                </nav>
            </header>

            <main class="max-w-7xl mx-auto px-6 pt-12 pb-24">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="badge-indigo mb-4">
                            <x-icon name="sparkles" class="w-3.5 h-3.5" /> Boutique en ligne
                        </span>
                        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Achetez vos articles préférés, simplement.
                        </h1>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                            Parcourez un catalogue mis à jour en temps réel, gérez votre panier et suivez vos
                            commandes du paiement à la livraison.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-primary">
                                    <x-icon name="box" class="w-4 h-4" /> Voir le catalogue
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn-primary">
                                    <x-icon name="user-circle" class="w-4 h-4" /> Commencer
                                </a>
                                <a href="{{ route('login') }}" class="btn-secondary">Se connecter</a>
                            @endauth
                        </div>

                        <dl class="mt-12 grid grid-cols-3 gap-6 max-w-md">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Articles</dt>
                                <dd class="text-2xl font-bold">{{ \App\Models\Article::count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Catégories</dt>
                                <dd class="text-2xl font-bold">{{ \App\Models\Category::count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Commandes</dt>
                                <dd class="text-2xl font-bold">{{ \App\Models\Order::count() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="card card-body">
                            <span class="icon-btn !bg-indigo-50 !text-indigo-600 dark:!bg-indigo-900/40 dark:!text-indigo-300 mb-3">
                                <x-icon name="search" class="w-5 h-5" />
                            </span>
                            <h3 class="font-semibold mb-1">Recherche &amp; filtres</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Trouvez vite un article par nom ou par catégorie.</p>
                        </div>
                        <div class="card card-body">
                            <span class="icon-btn !bg-emerald-50 !text-emerald-600 dark:!bg-emerald-900/40 dark:!text-emerald-300 mb-3">
                                <x-icon name="cart" class="w-5 h-5" />
                            </span>
                            <h3 class="font-semibold mb-1">Panier intelligent</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Stock vérifié en temps réel avant chaque commande.</p>
                        </div>
                        <div class="card card-body">
                            <span class="icon-btn !bg-amber-50 !text-amber-600 dark:!bg-amber-900/40 dark:!text-amber-300 mb-3">
                                <x-icon name="truck" class="w-5 h-5" />
                            </span>
                            <h3 class="font-semibold mb-1">Suivi de commande</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Statut mis à jour à chaque étape, par email.</p>
                        </div>
                        <div class="card card-body">
                            <span class="icon-btn !bg-blue-50 !text-blue-600 dark:!bg-blue-900/40 dark:!text-blue-300 mb-3">
                                <x-icon name="shield-check" class="w-5 h-5" />
                            </span>
                            <h3 class="font-semibold mb-1">Paiement sécurisé</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Paiement géré via Stripe, sans friction.</p>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="border-t border-gray-200 dark:border-gray-800 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                {{ config('app.name', 'Shop') }} &copy; {{ date('Y') }} — Laravel v{{ app()->version() }}
            </footer>
        </div>
    </body>
</html>
