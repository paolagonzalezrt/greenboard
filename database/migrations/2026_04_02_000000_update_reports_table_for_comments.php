<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Hacer tip_id nullable
            $table->foreignId('tip_id')->nullable()->change();
            // Agregar comment_id
            $table->foreignId('comment_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Revertir tip_id a NOT NULL
            $table->foreignId('tip_id')->nullable(false)->change();
            // Eliminar comment_id
            $table->dropForeignIdFor('Comment');
            $table->dropColumn('comment_id');
        });
    }
};
