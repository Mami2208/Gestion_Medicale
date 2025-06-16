<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Consultation;
use App\Models\Examen;
use App\Models\Prescription;

class Dossier extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dossiers_medicaux';
    
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'numero_dossier',
        'numero_securite_sociale',
        'statut',
        'groupe_sanguin',
        'taille',
        'poids',
        'notes',
        'date_creation',
        'observations',
        'antecedents',
        'antecedents_medicaux',
        'allergies',
        'motif_consultation',
        'traitements_en_cours'
    ];

    protected $casts = [
        'taille' => 'decimal:2',
        'poids' => 'decimal:2',
        'antecedents_medicaux' => 'array',
        'allergies' => 'array',
        'date_creation' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Les statuts possibles du dossier
     */
    public static function statuts(): array
    {
        return [
            'ACTIF' => 'Actif',
            'ARCHIVE' => 'Archivé',
            'FERME' => 'Fermé'
        ];
    }

    /**
     * Relation avec le patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }

    /**
     * Relation avec les historiques médicaux
     */
    public function historiquesMedicaux(): HasMany
    {
        return $this->hasMany(HistoriqueMedical::class, 'dossier_medical_id');
    }
    
    /**
     * Relation avec les consultations
     * Utilise patient_id comme clé étrangère car c'est ainsi que les consultations sont liées
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'patient_id', 'patient_id');
    }
    
    /**
     * Relation avec les examens médicaux
     */
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'dossiers_medicaux_id');
    }
    
    /**
     * Relation avec les prescriptions via la table traitements
     */
    public function prescriptions()
    {
        return $this->hasManyThrough(
            Prescription::class,
            'App\Models\Traitement',
            'patient_id', // Clé étrangère dans la table traitements
            'traitement_id', // Clé étrangère dans la table prescriptions
            'patient_id', // Clé locale dans la table dossiers_medicaux
            'id' // Clé locale dans la table traitements
        );
    }

    /**
     * Génère un numéro de dossier unique
     * Optimisé pour les performances avec une approche transactionnelle
     */
    public static function genererNumeroDossier(): string
    {
        $now = now();
        $annee = $now->format('Y');
        $mois = $now->format('m');
        
        // Utilisation d'une transaction pour éviter les doublons
        return DB::transaction(function () use ($annee, $mois) {
            // Incrémenter un compteur dans une table séparée pour éviter les verrous de table
            $counter = DB::table('dossier_counters')
                ->where('year', $annee)
                ->where('month', $mois)
                ->lockForUpdate()
                ->first();
            
            if ($counter) {
                // Mettre à jour le compteur existant
                $sequence = $counter->sequence + 1;
                DB::table('dossier_counters')
                    ->where('id', $counter->id)
                    ->update([
                        'sequence' => $sequence,
                        'updated_at' => now()
                    ]);
            } else {
                // Créer un nouveau compteur pour ce mois
                $sequence = 1;
                DB::table('dossier_counters')->insert([
                    'year' => $annee,
                    'month' => $mois,
                    'sequence' => $sequence,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            return sprintf('%s%s%03d', $annee, $mois, $sequence);
        });
    }

    /**
     * Génère un numéro de sécurité sociale unique basé sur la date de naissance
     * Optimisé pour les performances avec une approche transactionnelle
     */
    public static function genererNumeroSecuriteSociale($dateNaissance): string
    {
        if (!$dateNaissance) {
            throw new \Exception('La date de naissance est requise pour générer le numéro de sécurité sociale');
        }

        $date = \Carbon\Carbon::parse($dateNaissance);
        
        // Utilisation d'une transaction pour éviter les doublons
        return DB::transaction(function () use ($date) {
            // Format de clé unique pour ce jour de naissance
            $key = $date->format('Ymd');
            
            // Incrémenter un compteur spécifique à cette date de naissance
            $counter = DB::table('nss_counters')
                ->where('birth_date_key', $key)
                ->lockForUpdate()
                ->first();
            
            if ($counter) {
                // Mettre à jour le compteur existant
                $sequence = $counter->sequence + 1;
                DB::table('nss_counters')
                    ->where('id', $counter->id)
                    ->update([
                        'sequence' => $sequence,
                        'updated_at' => now()
                    ]);
            } else {
                // Créer un nouveau compteur pour cette date de naissance
                $sequence = 1;
                DB::table('nss_counters')->insert([
                    'birth_date_key' => $key,
                    'sequence' => $sequence,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Formater le numéro de sécurité sociale
            $sequenceStr = str_pad($sequence, 3, '0', STR_PAD_LEFT);
            
            return sprintf(
                '%02s%02s%02s%s',
                $date->format('y'), // Année sur 2 chiffres
                $date->format('m'), // Mois
                $date->format('d'), // Jour
                $sequenceStr        // Séquence sur 3 chiffres
            );
        });
    }
}