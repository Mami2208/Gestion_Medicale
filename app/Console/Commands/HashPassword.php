<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashPassword extends Command
{
    protected $signature = 'password:hash {password}';
    protected $description = 'Hash un mot de passe';

    public function handle()
    {
        $password = $this->argument('password');
        $hashedPassword = Hash::make($password);

        $this->info('Mot de passe original : ' . $password);
        $this->info('Mot de passe hashé : ' . $hashedPassword);
    }
} 