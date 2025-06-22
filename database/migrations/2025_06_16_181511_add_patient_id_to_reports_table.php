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
            // Adiciona a coluna patient_id, pode ser nullable se nem todo report tiver um patient_id direto (ex: laudos por exame)
            // Mas para laudos de evolução, ela seria preenchida.
            $table->foreignId('patient_id')->nullable()->after('exam_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Remove a chave estrangeira primeiro
            $table->dropForeign(['patient_id']);
            // Em seguida, remove a coluna
            $table->dropColumn('patient_id');
        });
    }
}; // <--- ESTE PONTO E VÍRGULA É CRUCIAL E PROVAVELMENTE ESTAVA EM FALTA
