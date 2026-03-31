<?php

namespace Database\Seeders;

use App\Models\Tip;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Bookmark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de ejemplo
        $users = [];

        $users[] = User::firstOrCreate(
            ['email' => 'eco_felix@example.com'],
            [
                'name' => 'eco_felix',
                'password' => bcrypt('password')
            ]
        );

        $users[] = User::firstOrCreate(
            ['email' => 'solar_pro@example.com'],
            [
                'name' => 'solar_pro',
                'password' => bcrypt('password')
            ]
        );

        $users[] = User::firstOrCreate(
            ['email' => 'green_kitchen@example.com'],
            [
                'name' => 'green_kitchen',
                'password' => bcrypt('password')
            ]
        );

        $users[] = User::firstOrCreate(
            ['email' => 'urban_cyclist@example.com'],
            [
                'name' => 'urban_cyclist',
                'password' => bcrypt('password')
            ]
        );

        $users[] = User::firstOrCreate(
            ['email' => 'ana_green@example.com'],
            [
                'name' => 'ana_green',
                'password' => bcrypt('password')
            ]
        );

        // Generar 50 posts de prueba SIN IMAGEN para probar la paginación
        $categories = ['Home', 'Energy', 'Consumption', 'Transport', 'Food', 'Zero Waste'];

        $titles = [
            'Reduce Water Usage with Simple Tricks',
            'How to Start a Small Garden',
            'Energy-Saving Tips for Winter',
            'Eco-Friendly Shopping Guide',
            'DIY Natural Cleaners',
            'Plant-Based Meals for Beginners',
            'Reduce Plastic in Your Kitchen',
            'Solar Panel Basics',
            'Sustainable Fashion Tips',
            'Composting 101',
            'Public Transport Benefits',
            'Zero Waste Bathroom',
            'Reusable Products Guide',
            'Home Insulation Tips',
            'Electric Vehicles Overview',
            'Organic Food Shopping',
            'Rainwater Collection',
            'Green Office Practices',
            'Recycling Best Practices',
            'Energy Efficient Appliances',
        ];

        $descriptions = [
            'Discover practical ways to conserve water in your daily routine and reduce your environmental impact.',
            'Start growing your own vegetables and herbs with these beginner-friendly tips.',
            'Keep your home warm while reducing energy consumption with these smart strategies.',
            'Learn how to make sustainable choices when shopping for everyday items.',
            'Create effective cleaning products using natural ingredients found in your pantry.',
            'Explore delicious plant-based recipes that are good for you and the planet.',
            'Simple swaps to eliminate single-use plastics from your cooking space.',
            'Everything you need to know about installing solar panels in your home.',
            'Build a sustainable wardrobe with eco-friendly fashion choices.',
            'Transform your organic waste into nutrient-rich compost for your garden.',
            'Why choosing public transportation is better for the environment.',
            'Steps to create a waste-free bathroom using sustainable products.',
            'Replace disposable items with reusable alternatives to reduce waste.',
            'Improve your home\'s insulation to save energy and money.',
            'Understanding the environmental benefits of electric vehicles.',
            'Tips for finding and choosing organic, locally-sourced food.',
            'Set up a rainwater collection system for your garden.',
            'Implement eco-friendly practices in your workplace.',
            'Master the art of proper recycling to maximize environmental impact.',
            'Choose appliances that save energy and reduce your carbon footprint.',
        ];

        for ($i = 0; $i < 50; $i++) {
            $randomUser = $users[array_rand($users)];
            $randomCategory = $categories[array_rand($categories)];
            $randomTitle = $titles[$i % count($titles)] . ' #' . ($i + 1);
            $randomDescription = $descriptions[$i % count($descriptions)];

            Tip::create([
                'user_id' => $randomUser->id,
                'category' => $randomCategory,
                'title' => $randomTitle,
                'description' => $randomDescription,
                'image' => null, // SIN IMAGEN para pruebas rápidas
            ]);
        }

        echo "✅ 50 posts de prueba creados exitosamente!\n";
    }
}

