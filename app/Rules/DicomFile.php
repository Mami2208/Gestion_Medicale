<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DicomFile implements Rule
{
    public function passes($attribute, $value)
    {
        return $value->getClientOriginalExtension() === 'dcm' &&
            $value->getMimeType() === 'application/dicom';
    }

    public function message()
    {
        return 'Le fichier doit être un vrai fichier DICOM (.dcm)';
    }
}