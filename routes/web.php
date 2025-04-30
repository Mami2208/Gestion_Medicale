
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DicomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedecinController;
use App\Http\Middleware\CheckRole;




// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Authentification
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login')->middleware('guest');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});


Route::middleware(['auth'])->group(function () {
    // Tableau de bord principal
    Route::get('/dashboard', [DicomController::class, 'dashboard'])->name('dashboard');
    
    // Dashboard Médecin
    Route::middleware([CheckRole::class . ':MEDECIN'])->prefix('medecin')->group(function () {
        Route::get('/dashboard', [MedecinController::class, 'dashboard'])->name('medecin.dashboard');
    });

    // Dashboard Patient
    Route::middleware([CheckRole::class . ':PATIENT'])->prefix('patient')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PatientController::class, 'dashboard'])->name('patient.dashboard');
        Route::get('/profile', [App\Http\Controllers\PatientController::class, 'profile'])->name('patient.profile');
        Route::get('/appointments', [App\Http\Controllers\PatientController::class, 'appointments'])->name('patient.appointments');
        Route::get('/medical-records', [App\Http\Controllers\PatientController::class, 'medicalRecords'])->name('patient.medical_records');
    });

    // Gestion DICOM
    Route::controller(DicomController::class)->group(function () {
        Route::get('/view/{orthancId}', 'viewer')->name('dicom.viewer');
    });

    // Administration (réservé aux ADMIN)
    Route::middleware([CheckRole::class . ':ADMIN'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
    // Gestion des médecins
    Route::controller(MedecinController::class)->prefix('medecins')->group(function () {
        Route::get('/', 'index')->name('admin.medecins.index');
        Route::get('/create', 'create')->name('admin.medecins.create');
        Route::post('/', 'store')->name('admin.medecins.store');
    });

    // Gestion des secrétaires
    Route::prefix('secretaires')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'indexSecretaires'])->name('admin.secretaires.index');
        Route::get('/create', [App\Http\Controllers\AdminController::class, 'createSecretaire'])->name('admin.secretaires.create');
        Route::post('/', [App\Http\Controllers\AdminController::class, 'storeSecretaire'])->name('admin.secretaires.store');
    });
    });

    // Dashboard Secrétaire
    Route::middleware([CheckRole::class . ':SECRETAIRE MEDICAL'])->prefix('secretaire')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SecretaireController::class, 'dashboard'])->name('secretaire.dashboard');
        Route::get('/medical-records/create', [App\Http\Controllers\SecretaireController::class, 'createMedicalRecord'])->name('secretaire.medical_records.create');
        Route::post('/medical-records', [App\Http\Controllers\SecretaireController::class, 'storeMedicalRecord'])->name('secretaire.medical_records.store');
    });
});

/*
// Temporarily removed upload routes as per user request
Route::controller(DicomController::class)->middleware('can:upload-dicom')->group(function () {
    Route::get('/upload', 'uploadForm')->name('dicom.upload.form');
    Route::post('/upload', 'upload')->name('dicom.upload');
});
*/
