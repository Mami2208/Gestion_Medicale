<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DicomFile implements Rule
{
    public function passes($attribute, $value)
    {
        // Relax MIME type check to allow common DICOM MIME types or skip MIME check
        $extension = $value->getClientOriginalExtension();
        $mimeType = $value->getMimeType();

        $allowedMimeTypes = [
            'application/dicom',
            'application/dicom+json',
            'application/octet-stream',
            'application/x-dicom',
            'application/dicom+xml',
        ];

        return $extension === 'dcm' && in_array($mimeType, $allowedMimeTypes);
    }

    public function message()
    {
        return 'Le fichier doit être un vrai fichier DICOM (.dcm)';
    }
}