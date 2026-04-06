<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapeo de categorías antiguas a nuevas
        $categoryMap = [
            'home' => 'hogar',
            'energy' => 'energia',
            'consumption' => 'consumo',
            'transport' => 'transporte',
            'food' => 'alimentacion',
            'zero_waste' => 'residuos',
        ];

        // Actualizar todas las categorías en la tabla tips
        foreach ($categoryMap as $old => $new) {
            DB::table('tips')->where('category', $old)->update(['category' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mapeo inverso
        $categoryMap = [
            'hogar' => 'home',
            'energia' => 'energy',
            'consumo' => 'consumption',
            'transporte' => 'transport',
            'alimentacion' => 'food',
            'residuos' => 'zero_waste',
        ];

        // Revertir cambios
        foreach ($categoryMap as $new => $old) {
            DB::table('tips')->where('category', $new)->update(['category' => $old]);
        }
    }
};
