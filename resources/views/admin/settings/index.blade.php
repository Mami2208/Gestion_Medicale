@extends('layouts.admin')

@section('title', 'Configuration du système')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800">Configuration du système</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Paramètres généraux -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Paramètres généraux</h2>
                    
                    <div class="mb-4">
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'application</label>
                        <input type="text" name="app_name" id="app_name" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ $settings['app_name'] }}">
                    </div>
                    
                    <div class="mb-4">
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Fuseau horaire</label>
                        <select name="timezone" id="timezone" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            @foreach($timezones as $value => $label)
                                <option value="{{ $value }}" {{ $settings['timezone'] == $value ? 'selected' : '' }}>
                                    {{ $label }} ({{ $value }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">Langue par défaut</label>
                        <select name="locale" id="locale" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            @foreach($locales as $value => $label)
                                <option value="{{ $value }}" {{ $settings['locale'] == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="app_debug" id="app_debug" 
                            class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            {{ $settings['app_debug'] ? 'checked' : '' }}>
                        <label for="app_debug" class="ml-2 block text-sm text-gray-700">Mode débogage</label>
                    </div>
                </div>
                
                <!-- Paramètres d'email -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Configuration des emails</h2>
                    
                    <div class="mb-4">
                        <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-1">Service d'envoi</label>
                        <select name="mail_driver" id="mail_driver" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="smtp" {{ $settings['mail_driver'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ $settings['mail_driver'] == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="postmark" {{ $settings['mail_driver'] == 'postmark' ? 'selected' : '' }}>Postmark</option>
                            <option value="ses" {{ $settings['mail_driver'] == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="log" {{ $settings['mail_driver'] == 'log' ? 'selected' : '' }}>Log (Développement)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">Serveur SMTP</label>
                        <input type="text" name="mail_host" id="mail_host" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ $settings['mail_host'] }}">
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">Port SMTP</label>
                        <input type="number" name="mail_port" id="mail_port" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ $settings['mail_port'] }}">
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse d'expédition</label>
                        <input type="email" name="mail_from_address" id="mail_from_address" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ $settings['mail_from_address'] }}">
                    </div>
                    
                    <div class="mb-4">
                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">Nom d'expédition</label>
                        <input type="text" name="mail_from_name" id="mail_from_name" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            value="{{ $settings['mail_from_name'] }}">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-right">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-md">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
