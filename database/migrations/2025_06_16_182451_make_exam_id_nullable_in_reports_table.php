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
            // Altera a coluna exam_id para ser nullable
            $table->foreignId('exam_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Reverte a coluna exam_id para ser not nullable (requer um valor padrão se houver dados)
            // Cuidado: Se houverem dados nulos, esta operação falhará.
            // Para reverter, precisaria garantir que não há nulos ou definir um default.
            $table->foreignId('exam_id')->nullable(false)->change();
        });
    }
};
