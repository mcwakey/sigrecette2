<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin {name : Le nom complet de l\'administrateur} {email : L\'email de l\'administrateur} {password : Le mot de passe de l\'administrateur}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Création d\'un administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        if (empty($email) || empty($password) || empty($name)) {
            $this->error('Veuillez fournir une adresse e-mail, un mot de passe et un nom complet valides.');
            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('Un utilisateur avec cette adresse e-mail existe déjà.');
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);


        if ($user && $user->assignRole('administrateur')) {
            $this->info('Administrateur créé avec succès.');
        } else {
            $this->error('Une erreur est survenue lors de la création de l\'administrateur.');
        }
    }
}
