<?php

return [
    'url' => env('ORTHANC_URL', 'http://localhost:8042'),
    'username' => env('ORTHANC_USERNAME', 'admin'),
    'password' => env('ORTHANC_PASSWORD', 'secret'),
    'dicom_port' => env('ORTHANC_DICOM_PORT', 4242),
    'storage' => env('ORTHANC_STORAGE', 'C:/orthanc/db')
];