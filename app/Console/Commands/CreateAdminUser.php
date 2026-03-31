<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario administrador (superusuario)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('  Crear Usuario Administrador');
        $this->info('===========================================');
        $this->newLine();

        // Solicitar información del administrador
        $name = $this->ask('Nombre del administrador');
        $email = $this->ask('Email del administrador');
        $password = $this->secret('Contraseña (mínimo 8 caracteres)');
        $passwordConfirmation = $this->secret('Confirmar contraseña');

        // Validar que las contraseñas coincidan
        if ($password !== $passwordConfirmation) {
            $this->error('Las contraseñas no coinciden.');
            return 1;
        }

        // Validar los datos
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->newLine();
            $this->error('Error en la validación:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  • ' . $error);
            }
            return 1;
        }

        // Crear el usuario administrador
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->newLine();
        $this->info('✓ Usuario administrador creado exitosamente!');
        $this->newLine();
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $admin->id],
                ['Nombre', $admin->name],
                ['Email', $admin->email],
                ['Admin', $admin->is_admin ? 'Sí' : 'No'],
            ]
        );
        $this->newLine();
        $this->info('Puedes iniciar sesión con estas credenciales.');

        return 0;
    }
}

