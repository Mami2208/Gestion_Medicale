<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleNotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->roleNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->roleNotifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
    
    public function markAllAsRead()
    {
        auth()->user()->roleNotifications()->update(['lu' => true]);
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
    
    public function delete($id)
    {
        $notification = auth()->user()->roleNotifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification supprimée');
    }
    
    public function deleteAll()
    {
        auth()->user()->roleNotifications()->delete();
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été supprimées');
    }
    
    /**
     * Créer une notification pour un utilisateur spécifique
     */
    public static function createForUser($userId, $titre, $message, $type = 'info', $icone = null, $url = null)
    {
        return \App\Models\RoleNotification::create([
            'user_id' => $userId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'icone' => $icone,
            'url' => $url,
            'lu' => false
        ]);
    }
    
    /**
     * Créer une notification pour tous les utilisateurs ayant un rôle spécifique
     */
    public static function createForRole($role, $titre, $message, $type = 'info', $icone = null, $url = null)
    {
        $users = \App\Models\User::where('role', $role)->get();
        
        foreach ($users as $user) {
            self::createForUser($user->id, $titre, $message, $type, $icone, $url);
        }
        
        return count($users);
    }
    
    /**
     * Créer une notification pour plusieurs rôles
     */
    public static function createForRoles(array $roles, $titre, $message, $type = 'info', $icone = null, $url = null)
    {
        $count = 0;
        foreach ($roles as $role) {
            $count += self::createForRole($role, $titre, $message, $type, $icone, $url);
        }
        
        return $count;
    }
}
