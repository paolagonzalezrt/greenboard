<?php

namespace Database\Seeders;

use App\Models\TranslatableText;
use Illuminate\Database\Seeder;

class TranslatableTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $texts = [
            // Configuraciones del sitio
            [
                'key' => 'site.welcome_message',
                'group' => 'site',
                'source_text' => '¡Bienvenido a GreenBoard! Comparte tus mejores consejos ecológicos.',
                'source_locale' => 'es',
            ],
            [
                'key' => 'site.footer_description',
                'group' => 'site',
                'source_text' => 'GreenBoard es una comunidad dedicada a compartir consejos y prácticas sostenibles para un mundo mejor.',
                'source_locale' => 'es',
            ],
            [
                'key' => 'site.about_us',
                'group' => 'site',
                'source_text' => 'Somos una plataforma comprometida con el medio ambiente. Nuestra misión es conectar personas que quieren hacer del mundo un lugar más verde.',
                'source_locale' => 'es',
            ],

            // Notificaciones
            [
                'key' => 'notification.new_follower',
                'group' => 'notifications',
                'source_text' => '¡Tienes un nuevo seguidor!',
                'source_locale' => 'es',
            ],
            [
                'key' => 'notification.tip_liked',
                'group' => 'notifications',
                'source_text' => 'A alguien le gustó tu consejo.',
                'source_locale' => 'es',
            ],
            [
                'key' => 'notification.new_comment',
                'group' => 'notifications',
                'source_text' => 'Tienes un nuevo comentario en tu publicación.',
                'source_locale' => 'es',
            ],

            // Categorías (editables desde admin)
            [
                'key' => 'category.energy_saving',
                'group' => 'categories',
                'source_text' => 'Ahorro de Energía',
                'source_locale' => 'es',
            ],
            [
                'key' => 'category.recycling',
                'group' => 'categories',
                'source_text' => 'Reciclaje',
                'source_locale' => 'es',
            ],
            [
                'key' => 'category.sustainable_living',
                'group' => 'categories',
                'source_text' => 'Vida Sostenible',
                'source_locale' => 'es',
            ],
            [
                'key' => 'category.organic_gardening',
                'group' => 'categories',
                'source_text' => 'Jardinería Orgánica',
                'source_locale' => 'es',
            ],
        ];

        foreach ($texts as $text) {
            TranslatableText::updateOrCreate(
                ['key' => $text['key']],
                $text
            );
        }

        $this->command->info('Translatable texts seeded successfully!');
    }
}
