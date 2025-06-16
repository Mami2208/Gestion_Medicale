<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DicomController;
use App\Http\Controllers\DicomApiController;

// Route de redirection après authentification
Route::middleware('auth')->get('/home', function() {
    $role = strtoupper(auth()->user()->role);

    return match($role) {
        'MEDECIN'    => redirect()->route('medecin.dashboard'),
        'PATIENT'    => redirect()->route('patient.dashboard'),
        'SECRETAIRE' => redirect()->route('secretaire.dashboard'),
        'INFIRMIER'  => redirect()->route('infirmier.dashboard'),
        'ADMIN'      => redirect()->route('admin.dashboard'),
        default      => redirect('/'),
    };
})->name('home');

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SecretaireDossierMedicalController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\HopitalController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SecretaireController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Medecin\DashboardController as MedecinDashboardController;
use App\Http\Controllers\Medecin\PatientController as MedecinPatientController;
use App\Http\Controllers\Medecin\DossierController;

use App\Http\Controllers\Medecin\PrescriptionController;
use App\Http\Controllers\Medecin\NotificationController;
use App\Http\Controllers\Medecin\HistoriqueController;
use App\Http\Controllers\Medecin\ConsultationController;
use App\Http\Controllers\Medecin\ExamenController;
use App\Http\Controllers\Medecin\ImagerieController;
use App\Http\Controllers\Medecin\HistoriqueMedicalController;
use App\Http\Controllers\Medecin\DossierMedicalController;
use App\Http\Controllers\Medecin\RendezVousController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\DicomDownloadController;

// Routes de test
Route::get('/test/medicaments', [TestController::class, 'checkMedicaments'])->name('test.medicaments');

// Route pour créer un administrateur par défaut (à supprimer en production)
Route::get('/create-default-admin', [AuthController::class, 'createDefaultAdmin'])->name('create.default.admin');

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Authentification
Route::controller(LoginController::class)->group(function () {
    // Route de connexion accessible uniquement aux invités
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', 'show')->name('login');
        Route::post('/login', 'login');
    });
    
    // Route de déconnexion accessible uniquement aux utilisateurs authentifiés
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    // Les routes de login et logout sont déjà définies dans le groupe middleware
});

// Gestion des mots de passe
Route::controller(\App\Http\Controllers\Auth\PasswordController::class)->middleware('auth')->group(function () {
    Route::get('/password/change', 'showChangeForm')->name('password.change');
    Route::post('/password/change', 'change')->name('password.update');
});

// Routes pour l'interface patient
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':PATIENT'])->prefix('patient')->name('patient.')->group(function () {
    // Profil
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [PatientController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [PatientController::class, 'updatePassword'])->name('password.update');
    
    // Tableau de bord
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    // Dossier médical
    Route::get('/dossier-medical', [PatientController::class, 'dossierMedical'])->name('dossier.medical');
    
    // Rendez-vous
    Route::get('/rendez-vous', [PatientController::class, 'appointments'])->name('appointments');
    Route::post('/rendez-vous', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/rendez-vous/{rendezVous}/annuler', [PatientController::class, 'cancelAppointment'])->name('appointments.cancel');
    
    // DICOM
    Route::get('/dicom', [PatientController::class, 'dicomViewer'])->name('dicom.viewer');
});

// Groupe principal des routes authentifiu00e9es
Route::middleware(['auth'])->group(function () {
    // Système de notifications par rôle
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RoleNotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [\App\Http\Controllers\RoleNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [\App\Http\Controllers\RoleNotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/{id}', [\App\Http\Controllers\RoleNotificationController::class, 'delete'])->name('delete');
        Route::delete('/all', [\App\Http\Controllers\RoleNotificationController::class, 'deleteAll'])->name('delete.all');
    });
    
    // Routes pour l'interface mu00e9decin
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':MEDECIN'])->prefix('medecin')->name('medecin.')->group(function () {
        // Tableau de bord
        Route::get('/dashboard', [MedecinDashboardController::class, 'index'])->name('dashboard');
        
        // Rendez-vous
        Route::get('/rendez-vous', [MedecinController::class, 'rendezVousIndex'])->name('rendez-vous.index');
        Route::get('/rendez-vous/create', [MedecinController::class, 'rendezVousCreate'])->name('rendez-vous.create');
        Route::post('/rendez-vous', [MedecinController::class, 'rendezVousStore'])->name('rendez-vous.store');
        Route::get('/rendez-vous/{rendezVous}', [MedecinController::class, 'rendezVousShow'])->name('rendez-vous.show');
        Route::get('/rendez-vous/{rendezVous}/edit', [MedecinController::class, 'rendezVousEdit'])->name('rendez-vous.edit');
        Route::put('/rendez-vous/{rendezVous}', [MedecinController::class, 'rendezVousUpdate'])->name('rendez-vous.update');
        Route::delete('/rendez-vous/{rendezVous}', [MedecinController::class, 'rendezVousDestroy'])->name('rendez-vous.destroy');
        
        // Consultations
        Route::resource('consultations', ConsultationController::class);
        
        // DICOM intégré aux consultations
        Route::get('/consultations/{consultation}/dicom', [ConsultationController::class, 'dicomViewer'])->name('consultations.dicom.viewer');
        Route::get('/consultations/{consultation}/dicom/upload', [ConsultationController::class, 'showUploadForm'])->name('consultations.dicom.upload');
        Route::post('/consultations/{consultation}/dicom/upload', [ConsultationController::class, 'uploadDicom'])->name('consultations.dicom.upload.submit');
        Route::get('/consultations/{consultation}/dicom/studies', [ConsultationController::class, 'getPatientStudies'])->name('consultations.dicom.studies');
        Route::get('/consultations/{consultation}/dicom/study/{studyId}/images', [ConsultationController::class, 'getStudyImages'])->name('consultations.dicom.images');
        Route::get('/consultations/{consultation}/dicom/preview/{instanceId}', [ConsultationController::class, 'getImagePreview'])->name('consultations.dicom.preview');
        Route::get('/consultations/{consultation}/dicom/image/{instanceId}', [ConsultationController::class, 'getImage'])->name('consultations.dicom.image');
        
        // Patients
        Route::resource('patients', MedecinPatientController::class);
        
        // Dossiers médicaux
        Route::get('/dossiers', [DossierController::class, 'index'])->name('dossiers.index');
        Route::get('/dossiers/create', [DossierController::class, 'create'])->name('dossiers.create');
        Route::post('/dossiers', [DossierController::class, 'store'])->name('dossiers.store');
        Route::get('/dossiers/{dossier}', [DossierController::class, 'show'])->name('dossiers.show');
        Route::get('/dossiers/{dossier}/edit', [DossierController::class, 'edit'])->name('dossiers.edit');
        Route::put('/dossiers/{dossier}', [DossierController::class, 'update'])->name('dossiers.update');
        Route::delete('/dossiers/{dossier}', [DossierController::class, 'destroy'])->name('dossiers.destroy');
        
        // Historique médical (DossierMedicalController)
        Route::get('/historique-medical/create', [DossierMedicalController::class, 'create'])->name('historique-medical.create');
        Route::post('/historique-medical', [DossierMedicalController::class, 'store'])->name('historique-medical.store');
        Route::get('/historique-medical/{dossier}', [DossierMedicalController::class, 'show'])->name('historique-medical.show');
        
        // Prescriptions
        Route::resource('prescriptions', PrescriptionController::class);
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        
        // Statistiques
        Route::get('/statistiques', [MedecinController::class, 'statistiques'])->name('statistiques.index');
        
        // Historique
        Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique.index');
        Route::get('/historique/consultation/{consultation}', [HistoriqueController::class, 'showConsultation'])->name('historique.consultation');
        Route::get('/historique/examen/{examen}', [HistoriqueController::class, 'showExamen'])->name('historique.examen');
        Route::get('/historique/prescription/{prescription}', [HistoriqueController::class, 'showPrescription'])->name('historique.prescription');
        
        // Examens
        Route::resource('examens', ExamenController::class);
        
        // Imagerie DICOM
        Route::resource('imagerie', ImagerieController::class);
        Route::get('/imagerie/{imagerie}/viewer', [ImagerieController::class, 'viewer'])->name('imagerie.viewer');
        
        // Délégations
        Route::resource('delegations', \App\Http\Controllers\Medecin\DelegationController::class);
        Route::post('/delegations/{delegation}/toggle-status', [\App\Http\Controllers\Medecin\DelegationController::class, 'toggleStatus'])->name('delegations.toggle-status');
        
        // Gestion des images DICOM
        Route::prefix('dicom')->name('dicom.')->group(function() {
            // Configuration Orthanc
            Route::get('/config', function() {
                return response()->json([
                    'success' => true,
                    'config' => [
                        'orthanc_url' => config('orthanc.base_url'),
                        'orthanc_http_port' => parse_url(config('orthanc.base_url'), PHP_URL_PORT) ?: '8042',
                        'orthanc_dicom_port' => '4242' // Port DICOM par défaut d'Orthanc
                    ]
                ]);
            })->name('config');
            
            // Upload d'images DICOM
            Route::get('/upload', [\App\Http\Controllers\Medecin\DicomController::class, 'create'])->name('upload');
            Route::post('/upload', [\App\Http\Controllers\Medecin\DicomController::class, 'store'])->name('store');
            
            // Visualisation
            Route::get('/view/{id}', [\App\Http\Controllers\Medecin\DicomController::class, 'view'])->name('view');
            
            // Liste des études
            Route::get('/studies', [\App\Http\Controllers\Medecin\DicomController::class, 'index'])->name('index');
        });
        
        // Paramètres
        Route::get('/parametres', [MedecinController::class, 'parametres'])->name('parametres.index');
        Route::put('/parametres', [MedecinController::class, 'parametresUpdate'])->name('parametres.update');
        Route::put('/parametres/profil', [MedecinController::class, 'profilUpdate'])->name('parametres.profil.update');
        Route::put('/parametres/password', [MedecinController::class, 'passwordUpdate'])->name('parametres.password.update');
        Route::put('/parametres/preferences', [MedecinController::class, 'preferencesUpdate'])->name('parametres.preferences.update');

        // Profil
        Route::get('/profile', [MedecinController::class, 'profile'])->name('profile.index');
        Route::get('/profile/edit', [MedecinController::class, 'profileEdit'])->name('profile.edit');
        Route::put('/profile', [MedecinController::class, 'profileUpdate'])->name('profile.update');

        // Routes pour l'historique médical
        Route::prefix('historique-medical')->group(function () {
            Route::get('create/{patient}', [HistoriqueMedicalController::class, 'create'])
                ->name('medecin.historique-medical.create');
            Route::post('store/{patient}', [HistoriqueMedicalController::class, 'store'])
                ->name('medecin.historique-medical.store');
        });

        // Routes pour les dossiers médicaux
        Route::prefix('dossiers-medicaux')->name('dossiers-medicaux.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Medecin\DossierMedicalController::class, 'index'])->name('index');
            Route::get('create', [\App\Http\Controllers\Medecin\DossierMedicalController::class, 'create'])->name('create');
            Route::post('store', [\App\Http\Controllers\Medecin\DossierMedicalController::class, 'store'])->name('store');
            Route::get('{dossier}', [\App\Http\Controllers\Medecin\DossierMedicalController::class, 'show'])->name('show');
        });
    });

    // Route de débogage directe pour le tableau de bord secrétaire
    Route::get('/debug-secretary-dashboard', function () {
        // Désactiver temporairement le middleware
        config(['auth.defaults.guard' => 'web']);
        
        // Créer une requête factice
        $request = request();
        
        // Simuler une connexion
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non connecté']);
        }
        
        // Afficher les informations de débogage
        $debugInfo = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'is_secretary' => $user->role === 'SECRETAIRE' ? 'OUI' : 'NON',
            'session' => session()->all(),
            'auth_check' => auth()->check(),
            'auth_user' => auth()->user() ? auth()->user()->toArray() : null
        ];
        
        // Essayer d'accéder au tableau de bord
        try {
            $controller = new \App\Http\Controllers\SecretaireController();
            $response = $controller->dashboard();
            $debugInfo['dashboard_response'] = 'SUCCÈS';
            return $response;
        } catch (\Exception $e) {
            $debugInfo['error'] = $e->getMessage();
            $debugInfo['trace'] = $e->getTraceAsString();
            return response()->json($debugInfo);
        }
    })->middleware(['auth']);
    
    // Route de débogage pour vérifier le rôle de l'utilisateur
    Route::get('/debug-role', function () {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non connecté']);
        }
        
        $role = $user->role;
        $roleType = gettype($role);
        $roleLength = strlen($role);
        $roleHex = bin2hex($role);
        $isSecretary = $role === 'SECRETAIRE' ? 'OUI' : 'NON';
        
        return response()->json([
            'email' => $user->email,
            'role' => $role,
            'role_type' => $roleType,
            'role_length' => $roleLength,
            'role_hex' => $roleHex,
            'is_secretary' => $isSecretary,
            'all_attributes' => $user->toArray()
        ]);
    })->middleware(['auth']);
    
    // Route de débogage pour vérifier l'utilisateur connecté
    Route::get('/debug-auth', function () {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['authenticated' => false]);
        }
        
        // Vérifier si l'utilisateur a un rôle valide
        $validRoles = ['ADMIN', 'MEDECIN', 'INFIRMIER', 'SECRETAIRE', 'PATIENT'];
        $role = strtoupper(trim($user->role));
        $isRoleValid = in_array($role, $validRoles);
        
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'role_type' => gettype($user->role),
                'role_length' => strlen($user->role),
                'role_hex' => bin2hex($user->role),
                'is_role_valid' => $isRoleValid,
                'is_secretary' => $role === 'SECRETAIRE',
                'session' => session()->all()
            ]
        ]);
    })->middleware('auth');
    
    // Route pour afficher les informations de l'utilisateur actuel
    Route::get('/current-user', function () {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non connecté']);
        }
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'role_type' => gettype($user->role),
            'role_length' => strlen($user->role),
            'role_ord' => array_map('ord', str_split($user->role))
        ]);
    })->middleware(['auth']);
    
    // Route pour afficher tous les utilisateurs et leur rôle
    Route::get('/all-users', function () {
        $users = \App\Models\User::all();
        $result = [];
        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'role_type' => gettype($user->role),
                'role_length' => strlen($user->role)
            ];
        }
        return response()->json($result);
    })->middleware(['auth']);
    
    // Route pour afficher les informations de l'utilisateur connecté
    Route::get('/user-info', function () {
        $user = auth()->user();
        \Log::info('Informations de l\'utilisateur connecté:', [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'role_type' => gettype($user->role),
            'role_length' => strlen($user->role)
        ]);
        
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'role_type' => gettype($user->role),
            'role_length' => strlen($user->role)
        ]);
    })->middleware(['auth']);
    
    // Route de test pour le tableau de bord de la secrétaire
    Route::get('/test-secretaire-dashboard', function () {
        \Log::info('Accès à la route de test du tableau de bord secrétaire');
        return app(\App\Http\Controllers\SecretaireController::class)->dashboard();
    })->middleware(['auth']);
    
    // Routes pour l'interface secrétaire
    Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':SECRETAIRE'])->prefix('secretaire')->name('secretaire.')->group(function () {
        \Log::info('=== MIDDLEWARE CHECKROLE:SECRETAIRE APPLIQUÉ ===');
        \Log::info('Utilisateur: ' . (auth()->user() ? auth()->user()->email : 'non connecté'));
        \Log::info('Rôle: ' . (auth()->user() ? auth()->user()->role : 'inconnu'));
        
        // Tableau de bord
        Route::get('/dashboard', [SecretaireController::class, 'dashboard'])->name('dashboard');
        
        // Création complète d'un patient et de son dossier médical
        Route::post('/dossiers/store-complet', [SecretaireController::class, 'storeDossierComplet'])->name('storeDossierComplet');
        
        // Routes patients
        Route::prefix('patients')->name('patients.')->group(function() {
            Route::get('/', [SecretaireController::class, 'patients'])->name('index');
            Route::get('/create', [SecretaireController::class, 'createPatient'])->name('create');
            Route::post('/', [SecretaireController::class, 'storePatient'])->name('store');
            Route::get('/{patient}', [SecretaireController::class, 'showPatient'])->name('show');
            Route::get('/{patient}/edit', [SecretaireController::class, 'editPatient'])->name('edit');
            Route::put('/{patient}', [SecretaireController::class, 'updatePatient'])->name('update');
            Route::delete('/{patient}', [SecretaireController::class, 'deletePatient'])->name('destroy');
        });
        
        // Dossiers médicaux
        Route::resource('dossiers-medicaux', SecretaireDossierMedicalController::class)->names([
            'index' => 'dossiers-medicaux.index',
            'create' => 'dossiers-medicaux.create',
            'store' => 'dossiers-medicaux.store',
            'show' => 'dossiers-medicaux.show',
            'edit' => 'dossiers-medicaux.edit',
            'update' => 'dossiers-medicaux.update',
            'destroy' => 'dossiers-medicaux.destroy',
        ]);
        
        // Rendez-vous
        Route::get('/rendez-vous', [SecretaireController::class, 'rendezVous'])->name('rendez-vous.index');
        Route::get('/rendez-vous/create', [SecretaireController::class, 'createRendezVous'])->name('rendez-vous.create');
        Route::post('/rendez-vous', [SecretaireController::class, 'storeRendezVous'])->name('rendez-vous.store');
        Route::get('/rendez-vous/{id}', [SecretaireController::class, 'showRendezVous'])->name('rendez-vous.show');
        Route::get('/rendez-vous/{id}/edit', [SecretaireController::class, 'editRendezVous'])->name('rendez-vous.edit');
        Route::put('/rendez-vous/{id}', [SecretaireController::class, 'updateRendezVous'])->name('rendez-vous.update');
        Route::delete('/rendez-vous/{id}', [SecretaireController::class, 'deleteRendezVous'])->name('rendez-vous.destroy');
        
        // Notifications
        Route::get('/notifications', [SecretaireController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{notificationId}/read', [SecretaireController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::delete('/notifications/{id}', [SecretaireController::class, 'supprimerNotification'])->name('notifications.delete');
        
        // Profil
        Route::get('/profile', [SecretaireController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [SecretaireController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [SecretaireController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/update-password', [SecretaireController::class, 'updatePassword'])->name('profile.update-password');
        
        // Recherche globale
        Route::get('/search', [SecretaireController::class, 'search'])->name('search');
        
        // Route de test
        Route::get('/test-route', function() {
            return 'Cette route fonctionne pour le secrétaire!';
        })->name('test.route');
        
        // Attribution de patients aux infirmiers (anciennes routes qui causent des erreurs 404)
        Route::get('/patients/assign', [SecretaireController::class, 'showPatientAssignment'])->name('patients.assign');
        Route::post('/patients/assign', [SecretaireController::class, 'assignPatientToNurse'])->name('patients.doAssign');
        Route::post('/patients/assign-multiple', [SecretaireController::class, 'assignMultiplePatientsToNurse'])->name('patients.doAssignMultiple');
        
        // Nouvelles routes alternatives pour l'attribution de patients
        Route::get('/gestion-attribution-patients', [SecretaireController::class, 'showPatientAssignment'])->name('patients.attribution');
        Route::post('/gestion-attribution-patients', [SecretaireController::class, 'assignPatientToNurse'])->name('patients.attribution.assign');
        Route::post('/gestion-attribution-patients-multiple', [SecretaireController::class, 'assignMultiplePatientsToNurse'])->name('patients.attribution.multiple');
    });

    // Routes pour l'interface infirmier
    Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':INFIRMIER'])->prefix('infirmier')->name('infirmier.')->group(function () {
        // Tableau de bord
        Route::get('/dashboard', [\App\Http\Controllers\InfirmierController::class, 'dashboard'])->name('dashboard');
        
        // Gestion des patients
        Route::get('/patients', [\App\Http\Controllers\InfirmierController::class, 'patientsIndex'])->name('patients.index');
        Route::get('/patients/{id}', [\App\Http\Controllers\InfirmierController::class, 'patientShow'])->name('patients.show');
        
        // Gestion des traitements
        Route::get('/traitements', [\App\Http\Controllers\InfirmierController::class, 'traitementsIndex'])->name('traitements.index');
        Route::get('/patients/{patient}/traitements/create', [\App\Http\Controllers\InfirmierController::class, 'createTraitement'])->name('traitements.create');
        Route::post('/patients/{patient}/traitements', [\App\Http\Controllers\InfirmierController::class, 'storeTraitement'])->name('traitements.store');
        Route::put('/traitements/{traitement}/status', [\App\Http\Controllers\InfirmierController::class, 'updateTraitementStatus'])->name('traitements.update.status');
        
        // Gestion des observations
        Route::get('/patients/{patient}/observations', [\App\Http\Controllers\InfirmierController::class, 'patientObservations'])->name('patients.observations');
        Route::get('/observations/create', [\App\Http\Controllers\InfirmierController::class, 'createObservation'])->name('observations.create');
        Route::post('/observations', [\App\Http\Controllers\InfirmierController::class, 'storeObservation'])->name('observations.store');
        Route::get('/observations/{observation}', [\App\Http\Controllers\InfirmierController::class, 'showObservation'])->name('observations.show');
        
        // Alertes patients à risque
        Route::get('/alertes', [\App\Http\Controllers\InfirmierController::class, 'alertesIndex'])->name('alertes.index');
        Route::get('/alertes/{id}', [\App\Http\Controllers\InfirmierController::class, 'alerteShow'])->name('alertes.show');
        
        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\InfirmierController::class, 'notificationsIndex'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\InfirmierController::class, 'markNotificationAsRead'])->name('notifications.read');
        
        // Gestion du profil
        Route::get('/profile', [\App\Http\Controllers\InfirmierController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [\App\Http\Controllers\InfirmierController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\InfirmierController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\InfirmierController::class, 'updatePassword'])->name('profile.password');
    });

    // Dashboard Patient
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':PATIENT'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
        Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [PatientController::class, 'updatePassword'])->name('profile.password.update');
        
        // Dossier médical
        Route::get('/dossier-medical', [PatientController::class, 'dossierMedical'])->name('dossier-medical');
        
        Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
        Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/medical-records', [PatientController::class, 'medicalRecords'])->name('medical_records');
        
        // Routes pour l'accès aux images DICOM
        Route::get('/dicom/viewer', [PatientController::class, 'dicomViewer'])->name('dicom.viewer');
        Route::get('/dicom/my-studies', [PatientController::class, 'getMyStudies'])->name('dicom.studies');
        Route::get('/dicom/study/{studyId}/images', [PatientController::class, 'getStudyImages'])->name('dicom.images');
        Route::get('/dicom/preview/{instanceId}', [PatientController::class, 'getImagePreview'])->name('dicom.preview');
        Route::get('/dicom/image/{instanceId}', [PatientController::class, 'getImage'])->name('dicom.image');
    });

    // Administration (ru00e9servu00e9 aux ADMIN)
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Gestion des mu00e9decins
        Route::controller(MedecinController::class)->prefix('medecins')->group(function () {
            Route::get('/', 'index')->name('medecins.index');
            Route::get('/create', 'create')->name('medecins.create');
            Route::post('/', 'store')->name('medecins.store');
        });

        // Gestion des secru00e9taires
        Route::prefix('secretaires')->group(function () {
            Route::get('/', [AdminController::class, 'indexSecretaires'])->name('secretaires.index');
            Route::get('/create', [AdminController::class, 'createSecretaire'])->name('secretaires.create');
            Route::post('/', [AdminController::class, 'storeSecretaire'])->name('secretaires.store');
        });

        // Gestion des infirmiers
        Route::prefix('infirmiers')->group(function () {
            Route::get('/', [AdminController::class, 'indexInfirmiers'])->name('infirmiers.index');
            Route::get('/create', [AdminController::class, 'createInfirmier'])->name('infirmiers.create');
            Route::post('/', [AdminController::class, 'storeInfirmier'])->name('infirmiers.store');
        });

        // Gestion des hu00f4pitaux
        Route::prefix('hopitaux')->group(function () {
            Route::get('/', [HopitalController::class, 'index'])->name('hopitaux.index');
            Route::get('/create', [HopitalController::class, 'create'])->name('hopitaux.create');
            Route::post('/', [HopitalController::class, 'store'])->name('hopitaux.store');
            Route::get('/{hopital}/edit', [HopitalController::class, 'edit'])->name('hopitaux.edit');
        });

        // Logs
        Route::get('/logs', [LogController::class, 'index'])->name('logs');
        Route::get('/logs/export', [LogController::class, 'export'])->name('logs.export');
        
        // Activity Logs
        Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('/activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'delete'])->name('activity-logs.delete');
        Route::post('/activity-logs/export', [\App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::get('/activity-logs/mark-all-read', [\App\Http\Controllers\Admin\ActivityLogController::class, 'markAllAsRead'])->name('activity-logs.mark-all-read');
        
        // Security
        Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
        Route::post('/security/update', [SecurityController::class, 'updateSettings'])->name('security.update');
        Route::post('/security/reset-login-attempts/{id}', [SecurityController::class, 'resetLoginAttempts'])->name('security.reset-login-attempts');
        Route::post('/security/toggle-lock/{id}', [SecurityController::class, 'toggleLock'])->name('security.toggle-lock');

        // Statistics
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

        // Backups
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/create', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/delete/{filename}', [BackupController::class, 'delete'])->name('backups.delete');

        // Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    });
});

// Routes DICOM pour l'imagerie médicale - Accessible par les médecins et patients
Route::middleware(['auth', 'role:MEDECIN,PATIENT'])
     ->prefix('dicom')
     ->name('dicom.')
     ->group(function () {
         // Visionneuse DICOM avec ID d'étude optionnel
         Route::get('/viewer/{id?}', [DicomController::class, 'viewer'])->name('viewer');
         
         // Liste des études
         Route::get('/studies', [DicomController::class, 'studies'])->name('studies');
         
         // Images d'une étude
         Route::get('/studies/{id}/images', [DicomController::class, 'images'])->name('images');
         
         // Aperçu d'une instance
         Route::get('/instances/{id}/preview', [DicomController::class, 'preview'])->name('preview');
         
         // Gestion des études DICOM (uniquement pour les médecins)
         Route::middleware('role:MEDECIN')->group(function () {
             // Téléversement
             Route::get('/upload', [DicomController::class, 'showUploadForm'])->name('upload.form');
             Route::post('/upload', [DicomController::class, 'upload'])->name('upload');
             
             // Suppression d'une étude
             Route::delete('/studies/{id}', [DicomController::class, 'destroy'])->name('studies.destroy');
             
             // Mise à jour des métadonnées d'une étude
             Route::put('/studies/{id}', [DicomController::class, 'update'])->name('studies.update');
             
             // Exportation d'une étude
             Route::get('/studies/{id}/export', [DicomController::class, 'export'])->name('studies.export');
         });
         
         // API DICOM (pour les appels AJAX)
         Route::prefix('api')->name('api.')->group(function () {
             Route::get('/patients/{patientId}/studies', [DicomApiController::class, 'getPatientStudies']);
             Route::get('/studies/{studyId}/series', [DicomApiController::class, 'getStudySeries']);
         });
     });

// Routes de débogage
Route::get('/force-secretary-dashboard', [\App\Http\Controllers\Auth\LoginController::class, 'forceSecretaryDashboard'])->name('force.secretary.dashboard');

// Routes de test
Route::get('/test/medicaments', [TestController::class, 'checkMedicaments'])->name('test.medicaments');
Route::get('/test/logging', [TestController::class, 'testLogging'])->name('test.logging');

// Routes pour les journaux d'activité avec la nouvelle interface moderne
Route::get('/admin/activity-logs-direct', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('admin.activity-logs-direct');
Route::get('/admin/activity-logs-index', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('admin.activity-logs.index');

// Route temporaire pour assigner des patients au médecin connecté
Route::get('/fix-patients', [\App\Http\Controllers\FixPatientsController::class, 'assignPatientsToDoctor'])->middleware('auth')->name('fix.patients');

// Route de débogage pour analyser les patients
Route::get('/debug-patients', [\App\Http\Controllers\DebugPatientController::class, 'debugPatients'])->middleware('auth')->name('debug.patients');

// Route directe pour test d'assignation de patients (dépannage)
Route::get('/test-assignment', [\App\Http\Controllers\SecretaireController::class, 'showPatientAssignment']);

// Route de test simple sans contrôleur
Route::get('/test-simple', function() {
    return 'Cette route fonctionne!';
});

// Route pour générer des journaux de test (à supprimer en production)
Route::get('/admin/generate-test-logs', [\App\Http\Controllers\Admin\TestLogsController::class, 'generateLogs'])->name('admin.generate-test-logs');

// Route pour corriger et remplir la table des journaux d'activité
Route::get('/admin/fix-logs', [\App\Http\Controllers\Admin\FixLogsController::class, 'fixAndGenerateLogs'])->name('admin.fix-logs');

// Route pour mettre à jour la relation entre patients et infirmiers
Route::get('/admin/update-patient-infirmier-relation', [\App\Http\Controllers\UpdatePatientInfirmierRelationController::class, 'updateRelation'])->name('admin.update-patient-infirmier-relation');

// Routes pour l'API Orthanc
Route::middleware(['auth'])->prefix('orthanc')->name('orthanc.')->group(function () {
    // Patients
    Route::get('/patients', [\App\Http\Controllers\Orthanc\PatientController::class, 'index'])
         ->name('patients.index');
         
    Route::get('/patients/{id}', [\App\Http\Controllers\Orthanc\PatientController::class, 'show'])
         ->name('patients.show');
         
    // Études
    Route::get('/studies', [\App\Http\Controllers\Orthanc\StudyController::class, 'index'])
         ->name('studies.index');
         
    Route::get('/studies/{id}', [\App\Http\Controllers\Orthanc\StudyController::class, 'show'])
         ->name('studies.show');
         
    // Séries
    Route::get('/series/{id}', [\App\Http\Controllers\Orthanc\SeriesController::class, 'show'])
         ->name('series.show');
         
    // Instances
    Route::get('/instances/{id}', [\App\Http\Controllers\Orthanc\InstanceController::class, 'show'])
         ->name('instances.show');
         
    // Téléchargements
    Route::get('/download/study/{id}', [\App\Http\Controllers\Orthanc\DownloadController::class, 'study'])
         ->name('download.study');
         
    Route::get('/download/series/{id}', [\App\Http\Controllers\Orthanc\DownloadController::class, 'series'])
         ->name('download.series');
         
    Route::get('/download/instance/{id}', [\App\Http\Controllers\Orthanc\DownloadController::class, 'instance'])
         ->name('download.instance');
         
    // Visualisation
    Route::get('/viewer/study/{id}', [\App\Http\Controllers\Orthanc\ViewerController::class, 'study'])
         ->name('viewer.study');
         
    Route::get('/viewer/series/{id}', [\App\Http\Controllers\Orthanc\ViewerController::class, 'series'])
         ->name('viewer.series');
});
