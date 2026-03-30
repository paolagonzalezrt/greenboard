<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener todos los usuarios
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->warn('Se necesitan al menos 2 usuarios para crear relaciones de seguimiento.');
            return;
        }

        // Crear relaciones de seguimiento de manera aleatoria
        foreach ($users as $user) {
            // Cada usuario seguirá entre 1 y 3 usuarios aleatorios
            $followingCount = rand(1, min(3, $users->count() - 1));
            
            // Obtener usuarios aleatorios para seguir (excluyendo al usuario actual)
            $usersToFollow = $users->where('id', '!=', $user->id)
                ->random($followingCount);

            foreach ($usersToFollow as $userToFollow) {
                // Verificar que no exista ya la relación
                $exists = DB::table('follows')
                    ->where('follower_id', $user->id)
                    ->where('following_id', $userToFollow->id)
                    ->exists();

                if (!$exists) {
                    DB::table('follows')->insert([
                        'follower_id' => $user->id,
                        'following_id' => $userToFollow->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Relaciones de seguimiento creadas exitosamente.');
    }
}
