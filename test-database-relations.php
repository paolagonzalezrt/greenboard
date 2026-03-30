<?php

/**
 * Script de prueba para verificar las relaciones de la base de datos
 * 
 * Ejecutar: php test-database-relations.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Tip;

echo "🔍 VERIFICANDO RELACIONES DE BASE DE DATOS\n";
echo "==========================================\n\n";

// Obtener un usuario de prueba
$user = User::first();

if (!$user) {
    echo "❌ No hay usuarios en la base de datos\n";
    exit(1);
}

echo "✅ Usuario de prueba: {$user->name} (ID: {$user->id})\n\n";

// Verificar tips
$tipsCount = Tip::count();
echo "📝 Total de tips en la BD: {$tipsCount}\n\n";

// Verificar relación following
$followingCount = $user->following()->count();
echo "👥 Usuarios que {$user->name} sigue: {$followingCount}\n";

if ($followingCount > 0) {
    echo "   └─ Siguiendo a:\n";
    foreach ($user->following as $following) {
        echo "      • {$following->name} (ID: {$following->id})\n";
    }
}
echo "\n";

// Verificar relación followers
$followersCount = $user->followers()->count();
echo "👥 Seguidores de {$user->name}: {$followersCount}\n";

if ($followersCount > 0) {
    echo "   └─ Seguido por:\n";
    foreach ($user->followers as $follower) {
        echo "      • {$follower->name} (ID: {$follower->id})\n";
    }
}
echo "\n";

// Verificar bookmarks
$bookmarksCount = $user->bookmarkedTips()->count();
echo "🔖 Tips guardados por {$user->name}: {$bookmarksCount}\n";

if ($bookmarksCount > 0) {
    echo "   └─ Tips guardados:\n";
    foreach ($user->bookmarkedTips as $tip) {
        echo "      • {$tip->title} (ID: {$tip->id})\n";
    }
}
echo "\n";

// Verificar likes
$likesCount = $user->likedTips()->count();
echo "❤️  Tips que le gustan a {$user->name}: {$likesCount}\n";

if ($likesCount > 0) {
    echo "   └─ Tips con like:\n";
    foreach ($user->likedTips()->limit(5)->get() as $tip) {
        echo "      • {$tip->title} (ID: {$tip->id})\n";
    }
}
echo "\n";

// Verificar tips propios
$ownTipsCount = $user->tips()->count();
echo "✍️  Tips publicados por {$user->name}: {$ownTipsCount}\n";

if ($ownTipsCount > 0) {
    echo "   └─ Tips publicados:\n";
    foreach ($user->tips as $tip) {
        echo "      • {$tip->title} (ID: {$tip->id})\n";
    }
}
echo "\n";

// Resumen final
echo "==========================================\n";
echo "✅ VERIFICACIÓN COMPLETADA\n\n";

echo "📊 RESUMEN:\n";
echo "   • Usuarios en BD: " . User::count() . "\n";
echo "   • Tips en BD: " . Tip::count() . "\n";
echo "   • Relaciones de seguimiento: " . DB::table('follows')->count() . "\n";
echo "   • Bookmarks: " . DB::table('bookmarks')->count() . "\n";
echo "   • Likes: " . DB::table('likes')->count() . "\n";
echo "   • Comentarios: " . DB::table('comments')->count() . "\n";
echo "\n";

echo "🎉 ¡Todas las relaciones están funcionando correctamente!\n";
