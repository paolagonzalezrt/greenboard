#!/usr/bin/env php
<?php

// Test para verificar que las fechas están funcionando
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tips = App\Models\Tip::with('user')->first();

if ($tips) {
    echo "✓ Tips encontrados en la base de datos\n";
    echo "✓ Título: {$tips->title}\n";
    echo "✓ Usuario: {$tips->user->name}\n";
    echo "✓ Fecha creación: {$tips->created_at}\n";
    echo "✓ Fecha formateada: {$tips->created_at->diffForHumans()}\n";
    echo "\n";
    echo "✅ Las fechas están funcionando correctamente!\n";
} else {
    echo "❌ No se encontraron tips en la base de datos\n";
    echo "Ejecuta: php artisan db:seed --class=TipSeeder\n";
}
