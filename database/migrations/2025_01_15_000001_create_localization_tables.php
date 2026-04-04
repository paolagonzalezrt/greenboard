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
        // Tabla para textos traducibles (textos originales del sistema)
        Schema::create('translatable_texts', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Clave única para identificar el texto');
            $table->string('group')->default('general')->comment('Grupo de categorización');
            $table->text('source_text')->comment('Texto original');
            $table->string('source_locale', 5)->default('es')->comment('Idioma original');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['group', 'is_active']);
        });

        // Tabla para las traducciones de textos dinámicos
        Schema::create('dynamic_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translatable_text_id')
                ->constrained('translatable_texts')
                ->onDelete('cascade');
            $table->string('locale', 5)->comment('Código de idioma (es, en, de)');
            $table->text('translated_text');
            $table->boolean('is_auto_translated')->default(false)->comment('Si fue traducido automáticamente');
            $table->boolean('is_reviewed')->default(false)->comment('Si fue revisado manualmente');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['translatable_text_id', 'locale']);
            $table->index('locale');
        });

        // Tabla para cache de traducciones de API
        Schema::create('translation_cache', function (Blueprint $table) {
            $table->id();
            $table->string('cache_key')->unique();
            $table->string('source_locale', 5);
            $table->string('target_locale', 5);
            $table->text('source_text');
            $table->text('translated_text');
            $table->string('service')->default('deepl')->comment('Servicio usado: deepl, google');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['source_locale', 'target_locale']);
            $table->index('expires_at');
        });

        // Agregar columna de preferencia de idioma a usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->string('preferred_locale', 5)->default('es')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_locale');
        });

        Schema::dropIfExists('translation_cache');
        Schema::dropIfExists('dynamic_translations');
        Schema::dropIfExists('translatable_texts');
    }
};
