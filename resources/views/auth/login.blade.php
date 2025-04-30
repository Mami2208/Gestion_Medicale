<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Connexion</title>

    <!-- Scripts et Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <div class="w-3/5 bg-green-500">
            <!-- Left green background area -->
        </div>
        <div class="w-2/5 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
                <h1 class="text-2xl font-bold text-center mb-6">Connexion</h1>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            Adresse email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-green-300">
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            Mot de passe
                        </label>
                        <input id="password" type="password" name="password" required 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-green-300">
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <input id="remember" type="checkbox" name="remember" class="rounded text-green-500">
                            <label for="remember" class="ml-2 text-sm text-gray-600">
                                Se souvenir de moi
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-green-500 hover:text-green-700">
                                Mot de passe oublié?
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:ring-green-300">
                            Se connecter
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte? 
                        <a href="{{ route('register') }}" class="text-green-500 hover:text-green-700">
                            S'inscrire
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>