<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Affiche la liste des sauvegardes disponibles
     */
    public function index()
    {
        // Récupérer les sauvegardes existantes
        $backups = $this->getBackups();
        
        return view('admin.backups.index', compact('backups'));
    }
    
    /**
     * Crée une nouvelle sauvegarde
     */
    public function create(Request $request)
    {
        // Générer un nom de fichier pour la sauvegarde
        $filename = 'backup_' . date('Y-m-d_His') . '.zip';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le répertoire de sauvegarde s'il n'existe pas
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }
        
        // Créer une sauvegarde de la base de données
        $dbFilename = 'database_' . date('Y-m-d_His') . '.sql';
        $dbBackupPath = storage_path('app/backups/' . $dbFilename);
        
        // Récupérer les paramètres de la base de données
        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
        
        // Commande pour exporter la base de données
        $command = "mysqldump -h {$dbHost} -u {$dbUser} " . ($dbPassword ? "-p\"{$dbPassword}\"" : "") . " {$dbName} > {$dbBackupPath}";
        
        // Exécuter la commande
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            return redirect()->route('admin.backups.index')->with('error', 'La sauvegarde de la base de données a échoué.');
        }
        
        // Créer l'archive ZIP contenant la sauvegarde de la base de données et les fichiers
        $zip = new ZipArchive();
        
        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Ajouter le fichier SQL à l'archive
            $zip->addFile($dbBackupPath, 'database.sql');
            
            // Ajouter les fichiers importants de l'application
            $this->addDirectoryToZip($zip, public_path('uploads'), 'uploads');
            $this->addDirectoryToZip($zip, storage_path('app/public'), 'storage');
            
            // Fermer l'archive
            $zip->close();
            
            // Supprimer le fichier SQL temporaire
            File::delete($dbBackupPath);
            
            return redirect()->route('admin.backups.index')->with('success', 'Sauvegarde créée avec succès.');
        } else {
            return redirect()->route('admin.backups.index')->with('error', 'La création de l\'archive de sauvegarde a échoué.');
        }
    }
    
    /**
     * Télécharge une sauvegarde
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (File::exists($backupPath)) {
            return Response::download($backupPath);
        } else {
            return redirect()->route('admin.backups.index')->with('error', 'Le fichier de sauvegarde n\'existe pas.');
        }
    }
    
    /**
     * Supprime une sauvegarde
     */
    public function delete($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (File::exists($backupPath)) {
            File::delete($backupPath);
            return redirect()->route('admin.backups.index')->with('success', 'Sauvegarde supprimée avec succès.');
        } else {
            return redirect()->route('admin.backups.index')->with('error', 'Le fichier de sauvegarde n\'existe pas.');
        }
    }
    
    /**
     * Récupère la liste des sauvegardes
     */
    private function getBackups()
    {
        $backups = [];
        $backupDir = storage_path('app/backups');
        
        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            
            foreach ($files as $file) {
                if (File::extension($file) === 'zip') {
                    $backups[] = [
                        'filename' => File::basename($file),
                        'size' => File::size($file),
                        'date' => Carbon::createFromTimestamp(File::lastModified($file))->format('d/m/Y H:i:s'),
                    ];
                }
            }
        }
        
        // Trier les sauvegardes par date (plus récentes en premier)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $backups;
    }
    
    /**
     * Ajoute un répertoire à l'archive ZIP
     */
    private function addDirectoryToZip($zip, $directory, $zipDirectory)
    {
        if (!File::exists($directory)) {
            return;
        }
        
        $files = File::allFiles($directory);
        
        foreach ($files as $file) {
            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            $zip->addFile($file->getPathname(), $zipDirectory . '/' . $relativePath);
        }
    }
}
