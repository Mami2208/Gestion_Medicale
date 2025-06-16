<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres du système
     */
    public function index()
    {
        // Récupérer les paramètres actuels
        $settings = [
            'app_name' => config('app.name'),
            'app_debug' => config('app.debug'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];
        
        // Liste des fuseaux horaires disponibles
        $timezones = [
            'Africa/Casablanca' => 'Casablanca',
            'Europe/Paris' => 'Paris',
            'Europe/London' => 'Londres',
            'America/New_York' => 'New York',
            'America/Chicago' => 'Chicago',
            'America/Denver' => 'Denver',
            'America/Los_Angeles' => 'Los Angeles',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Dubai' => 'Dubai',
            'Australia/Sydney' => 'Sydney',
        ];
        
        // Langues disponibles
        $locales = [
            'fr' => 'Français',
            'en' => 'Anglais',
            'ar' => 'Arabe',
            'es' => 'Espagnol',
        ];
        
        return view('admin.settings.index', compact('settings', 'timezones', 'locales'));
    }
    
    /**
     * Met à jour les paramètres du système
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'locale' => 'required|string|max:5',
            'mail_driver' => 'nullable|string|max:20',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:0|max:65535',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);
        
        // Mettre à jour le fichier .env
        $this->updateEnvironmentFile([
            'APP_NAME' => '"' . $request->app_name . '"',
            'APP_DEBUG' => $request->has('app_debug') ? 'true' : 'false',
            'APP_TIMEZONE' => $request->timezone,
            'APP_LOCALE' => $request->locale,
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
        ]);
        
        // Effacer le cache de configuration
        Artisan::call('config:clear');
        
        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour avec succès');
    }
    
    /**
     * Met à jour le fichier .env
     */
    private function updateEnvironmentFile($data)
    {
        $path = app()->environmentFilePath();
        
        if (File::exists($path)) {
            $content = File::get($path);
            
            foreach ($data as $key => $value) {
                // Si la clé existe déjà dans le fichier
                if (preg_match("/^{$key}=/m", $content)) {
                    // Remplacer la ligne existante
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
                } else {
                    // Sinon, ajouter une nouvelle ligne
                    $content .= "\n{$key}={$value}";
                }
            }
            
            File::put($path, $content);
        }
    }
}
