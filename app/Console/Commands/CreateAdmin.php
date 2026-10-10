<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create
                            {--first-name= : Prénom}
                            {--last-name= : Nom}
                            {--email= : Adresse email (identifiant de connexion)}
                            {--password= : Mot de passe (min. 8 caractères)}';

    protected $description = 'Crée un compte administrateur (ou promeut un utilisateur existant par email)';

    public function handle(): int
    {
        $firstName = $this->option('first-name') ?: $this->ask('Prénom');
        $lastName = $this->option('last-name') ?: $this->ask('Nom');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Mot de passe (min. 8 caractères)');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email invalide.');
            return self::FAILURE;
        }

        if (strlen((string) $password) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');
            return self::FAILURE;
        }

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::where('email', $email)->first();

        if ($user && $user->hasRole('admin')) {
            $this->error("{$email} est déjà administrateur.");
            return self::FAILURE;
        }

        if ($user) {
            if (! $this->confirm("{$email} existe déjà. La promouvoir administrateur et remplacer son mot de passe ?")) {
                return self::FAILURE;
            }
            $user->update(['password' => Hash::make($password), 'status' => 'validated']);
        } else {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'status' => 'validated',
            ]);
        }

        $user->assignRole('admin');

        $this->info("Administrateur créé : {$user->full_name()} <{$email}>");

        return self::SUCCESS;
    }
}
