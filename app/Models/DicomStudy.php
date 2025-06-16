<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DicomStudy extends Model
{
    protected $table = 'dicom_studies';
    
    protected $fillable = [
        'patient_id',
        'study_uid',
        'study_date',
        'description',
        'study_data',
        'uploaded_by',
    ];
    
    protected $casts = [
        'study_date' => 'datetime',
        'study_data' => 'array',
    ];
    
    /**
     * Relation avec le patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    
    /**
     * Relation avec l'utilisateur qui a uploadé l'étude
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    /**
     * Récupère l'URL de l'étude dans Orthanc
     */
    public function getOrthancUrlAttribute(): string
    {
        return config('orthanc.base_url') . "/studies/" . $this->study_uid;
    }
    
    /**
     * Récupère l'URL de la visionneuse web
     */
    public function getViewerUrlAttribute(): string
    {
        return config('orthanc.web_viewer_url') . "#study?study=" . $this->study_uid;
    }
}
