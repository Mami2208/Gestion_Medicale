<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pour MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE utilisateurs 
                MODIFY COLUMN role ENUM('ADMIN','MEDECIN','INFIRMIER','SECRETAIRE','PATIENT') 
                DEFAULT 'PATIENT'");
        }
        
        // Pour PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE utilisateurs 
                DROP CONSTRAINT utilisateurs_role_check");
                
            DB::statement("ALTER TABLE utilisateurs 
                ADD CONSTRAINT utilisateurs_role_check 
                CHECK (role IN ('ADMIN', 'MEDECIN', 'INFIRMIER', 'SECRETAIRE', 'PATIENT'))");
                
            DB::statement("ALTER TABLE utilisateurs 
                ALTER COLUMN role SET DEFAULT 'PATIENT'");
        }
        
        // Pour SQLite (nécessite de recréer la table)
        if (DB::getDriverName() === 'sqlite') {
            // Désactiver les contraintes de clé étrangère
            DB::statement('PRAGMA foreign_keys = OFF');
            
            // Créer une table temporaire avec la nouvelle structure
            Schema::create('utilisateurs_temp', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('prenom');
                $table->string('email')->unique();
                $table->string('mot_de_passe');
                $table->enum('role', ['ADMIN','MEDECIN','INFIRMIER','SECRETAIRE','PATIENT'])->default('PATIENT');
                $table->string('telephone')->nullable();
                $table->string('adresse')->nullable();
                $table->string('code_postal')->nullable();
                $table->string('ville')->nullable();
                $table->date('date_naissance')->nullable();
                $table->string('photo')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
            
            // Copier les données de l'ancienne table vers la nouvelle
            DB::statement('INSERT INTO utilisateurs_temp SELECT * FROM utilisateurs');
            
            // Supprimer l'ancienne table
            Schema::drop('utilisateurs');
            
            // Renommer la nouvelle table
            Schema::rename('utilisateurs_temp', 'utilisateurs');
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pour MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE utilisateurs 
                MODIFY COLUMN role ENUM('ADMIN','MEDECIN','INFIRMIER','SECRETAIRE') 
                DEFAULT 'ADMIN'");
        }
        
        // Pour PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE utilisateurs 
                DROP CONSTRAINT utilisateurs_role_check");
                
            DB::statement("ALTER TABLE utilisateurs 
                ADD CONSTRAINT utilisateurs_role_check 
                CHECK (role IN ('ADMIN', 'MEDECIN', 'INFIRMIER', 'SECRETAIRE'))");
                
            DB::statement("ALTER TABLE utilisateurs 
                ALTER COLUMN role SET DEFAULT 'ADMIN'");
        }
        
        // Pour SQLite (nécessite de recréer la table)
        if (DB::getDriverName() === 'sqlite') {
            // Désactiver les contraintes de clé étrangère
            DB::statement('PRAGMA foreign_keys = OFF');
            
            // Créer une table temporaire avec l'ancienne structure
            Schema::create('utilisateurs_temp', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('prenom');
                $table->string('email')->unique();
                $table->string('mot_de_passe');
                $table->enum('role', ['ADMIN','MEDECIN','INFIRMIER','SECRETAIRE'])->default('ADMIN');
                $table->string('telephone')->nullable();
                $table->string('adresse')->nullable();
                $table->string('code_postal')->nullable();
                $table->string('ville')->nullable();
                $table->date('date_naissance')->nullable();
                $table->string('photo')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
            
            // Copier les données de l'ancienne table vers la nouvelle (en excluant les patients)
            DB::statement("INSERT INTO utilisateurs_temp SELECT * FROM utilisateurs WHERE role != 'PATIENT'");
            
            // Supprimer l'ancienne table
            Schema::drop('utilisateurs');
            
            // Renommer la nouvelle table
            Schema::rename('utilisateurs_temp', 'utilisateurs');
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
};
