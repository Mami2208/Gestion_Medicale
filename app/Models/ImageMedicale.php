<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ImageMedicale extends Model
{
    protected $fillable = ['is_dicom', 'dossiers_medicaux_id'];

    public function dicom(): HasOne
    {
        return $this->hasOne(ImageDicom::class);
    }

    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class);
    }
}