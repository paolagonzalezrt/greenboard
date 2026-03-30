<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener todos los usuarios y tips
        $users = User::all();
        $tips = Tip::all();

        if ($users->isEmpty() || $tips->isEmpty()) {
            $this->command->warn('Se necesitan usuarios y tips para crear bookmarks.');
            return;
        }

        // Crear bookmarks aleatorios
        foreach ($users as $user) {
            // Cada usuario guardará entre 1 y 4 tips aleatorios
            $bookmarkCount = rand(1, min(4, $tips->count()));
            
            // Obtener tips aleatorios para guardar
            $tipsToBookmark = $tips->random($bookmarkCount);

            foreach ($tipsToBookmark as $tip) {
                // Verificar que no exista ya el bookmark
                $exists = DB::table('bookmarks')
                    ->where('user_id', $user->id)
                    ->where('tip_id', $tip->id)
                    ->exists();

                if (!$exists) {
                    DB::table('bookmarks')->insert([
                        'user_id' => $user->id,
                        'tip_id' => $tip->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        $this->command->info('✅ Bookmarks creados exitosamente.');
    }
}
