<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangeAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:change-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change le mot de passe de l\'administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Entrez l\'email de l\'administrateur');
        
        $admin = DB::table('utilisateurs')
            ->where('email', $email)
            ->where('role', 'ADMIN')
            ->first();

        if (!$admin) {
            $this->error('Aucun administrateur trouvé avec cet email.');
            return 1;
        }

        $password = $this->secret('Entrez le nouveau mot de passe');
        $passwordConfirmation = $this->secret('Confirmez le nouveau mot de passe');

        if ($password !== $passwordConfirmation) {
            $this->error('Les mots de passe ne correspondent pas.');
            return 1;
        }

        if (strlen($password) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');
            return 1;
        }

        try {
            DB::table('utilisateurs')
                ->where('id', $admin->id)
                ->update([
                    'mot_de_passe' => Hash::make($password),
                    'password_changed_at' => now(),
                    'force_password_change' => false,
                    'updated_at' => now()
                ]);

            $this->info('Le mot de passe a été modifié avec succès !');
            return 0;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue lors du changement de mot de passe : ' . $e->getMessage());
            return 1;
        }
    }
}
