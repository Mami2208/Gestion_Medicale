<?php

namespace App\Notifications;

use App\Models\Dossiers_Medicaux as DossierMedical;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauDossierMedical extends Notification implements ShouldQueue
{
    use Queueable;

    protected $dossier;
    

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Dossiers_Medicaux $dossier
     * @return void
     */
    public function __construct(DossierMedical $dossier)
    {
        $this->dossier = $dossier;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $patient = $this->dossier->patient->utilisateur ?? null;
        $patientName = $patient ? $patient->prenom . ' ' . $patient->nom : 'Inconnu';
        
        return (new MailMessage)
                    ->subject('Nouveau dossier médical attribué')
                    ->line('Un nouveau dossier médical vous a été attribué.')
                    ->line('Patient: ' . $patientName)
                    ->line('Numéro de dossier: ' . $this->dossier->numero_dossier)
                    ->action('Voir le dossier', route('medecin.dossiers.show', $this->dossier->id))
                    ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $patient = $this->dossier->patient->utilisateur ?? null;
        $patientName = $patient ? $patient->prenom . ' ' . $patient->nom : 'Inconnu';
        
        return [
            'type' => 'nouveau_dossier',
            'message' => 'Un nouveau dossier médical vous a été attribué: ' . $patientName,
            'dossier_id' => $this->dossier->id,
            'patient_name' => $patientName,
        ];
    }
}
