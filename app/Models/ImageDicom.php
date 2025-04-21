<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageDicom extends Model
{
    protected $fillable = ['orthanc_id', 'study_id', 'series_id'];
    
    public function medicalImage()
    {
        return $this->belongsTo(ImageMedicale::class);
    }
}