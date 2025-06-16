<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('utilisateurs')->insert([
            'nom' => 'Administrateur',
            'prenom' => 'Système',
            'email' => 'admin@system.com',
            'mot_de_passe' => Hash::make('Admin@123'),
            'role' => 'ADMIN',
            'telephone' => null,
            'adresse' => null,
            'code_postal' => null,
            'ville' => null,
            'date_naissance' => null,
            'photo' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('utilisateurs')->where('email', 'admin@system.com')->delete();
    }
}; 