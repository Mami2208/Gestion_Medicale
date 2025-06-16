<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('parametres')) {
            Schema::create('parametres', function (Blueprint $table) {
                $table->id();
                $table->string('groupe');
                $table->string('cle');
                $table->text('valeur')->nullable();
                $table->text('description')->nullable();
                $table->string('type')->default('texte');
                $table->json('options')->nullable();
                $table->integer('ordre')->default(0);
                $table->timestamps();

                // Index
                $table->unique(['groupe', 'cle']);
                $table->index('groupe');
            });

            // Insérer les paramètres de sécurité par défaut
            DB::table('parametres')->insert([
                [
                    'groupe' => 'securite',
                    'cle' => 'tentatives_connexion',
                    'valeur' => '5',
                    'description' => 'Nombre maximum de tentatives de connexion avant blocage',
                    'type' => 'nombre',
                    'ordre' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'groupe' => 'securite',
                    'cle' => 'duree_verrouillage',
                    'valeur' => '30',
                    'description' => 'Durée du verrouillage du compte en minutes après échecs de connexion',
                    'type' => 'nombre',
                    'ordre' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'groupe' => 'securite',
                    'cle' => 'longueur_min_mdp',
                    'valeur' => '8',
                    'description' => 'Longueur minimale du mot de passe',
                    'type' => 'nombre',
                    'ordre' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne pas supprimer la table pour éviter de perdre des données
        // Schema::dropIfExists('parametres');
    }
};
