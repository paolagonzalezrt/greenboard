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

        $tips = [
            [
                'user_index' => 0,
                'category' => 'Zero Waste',
                'title' => 'Mastering the Art of Backyard Composting',
                'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
                'image' => 'https://picsum.photos/id/10/400/300',
            ],
            [
                'user_index' => 1,
                'category' => 'Energy',
                'title' => 'Switch to LED Bulbs',
                'description' => 'Save energy and money by replacing traditional bulbs with LED alternatives.',
                'image' => null,
            ],
            [
                'user_index' => 2,
                'category' => 'Food',
                'title' => 'Reduce Food Waste',
                'description' => 'Plan your meals ahead and use leftovers creatively to minimize waste.',
                'image' => 'https://picsum.photos/id/20/400/300',
            ],
            [
                'user_index' => 3,
                'category' => 'Transport',
                'title' => 'Bike to Work',
                'description' => 'Reduce your carbon footprint by cycling instead of driving.',
                'image' => null,
            ],
            [
                'user_index' => 4,
                'category' => 'Home',
                'title' => 'Use Natural Cleaning Products',
                'description' => 'Make your own cleaning products with vinegar, baking soda, and lemon.',
                'image' => 'https://picsum.photos/id/30/400/300',
            ],
            [
                'user_index' => 0,
                'category' => 'Consumption',
                'title' => 'Buy Second-Hand',
                'description' => 'Give items a second life by shopping at thrift stores and online marketplaces.',
                'image' => null,
            ],
            [
                'user_index' => 1,
                'category' => 'Energy',
                'title' => 'Solar Panels Investment',
                'description' => 'A comprehensive guide to installing solar panels and calculating your ROI.',
                'image' => 'https://picsum.photos/id/40/400/300',
            ],
            [
                'user_index' => 2,
                'category' => 'Food',
                'title' => 'Plant-Based Mondays',
                'description' => 'Start your week with delicious plant-based meals that are good for you and the planet.',
                'image' => null,
            ],
        ];

        $createdTips = [];
        foreach ($tips as $tipData) {
            $tip = Tip::create([
                'user_id' => $users[$tipData['user_index']]->id,
                'category' => $tipData['category'],
                'title' => $tipData['title'],
                'description' => $tipData['description'],
                'image' => $tipData['image'],
            ]);
            $createdTips[] = $tip;
        }

        // Crear likes de ejemplo
        foreach ($createdTips as $tip) {
            $numLikes = rand(5, 50);
            for ($i = 0; $i < $numLikes; $i++) {
                $randomUser = $users[array_rand($users)];
                Like::firstOrCreate([
                    'user_id' => $randomUser->id,
                    'tip_id' => $tip->id,
                ]);
            }
        }

        // Crear comentarios de ejemplo
        $sampleComments = [
            'Great tip! I\'ll definitely try this.',
            'This is so helpful, thank you for sharing!',
            'I\'ve been doing this for years and it works great.',
            'Does anyone have more information about this?',
            'Amazing! I never thought of it that way.',
            'This should be shared more widely!',
        ];

        foreach ($createdTips as $tip) {
            $numComments = rand(2, 10);
            for ($i = 0; $i < $numComments; $i++) {
                $randomUser = $users[array_rand($users)];
                Comment::create([
                    'user_id' => $randomUser->id,
                    'tip_id' => $tip->id,
                    'content' => $sampleComments[array_rand($sampleComments)],
                ]);
            }
        }

        // Crear bookmarks de ejemplo
        foreach ($createdTips as $index => $tip) {
            if ($index % 2 == 0) { // Solo algunos tips
                $numBookmarks = rand(1, 3);
                for ($i = 0; $i < $numBookmarks; $i++) {
                    $randomUser = $users[array_rand($users)];
                    Bookmark::firstOrCreate([
                        'user_id' => $randomUser->id,
                        'tip_id' => $tip->id,
                    ]);
                }
            }
        }
    }
}

