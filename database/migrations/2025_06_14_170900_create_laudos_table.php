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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_id')->constrained()->onDelete('cascade'); // Chave estrangeira para exames
        $table->longText('report_content'); // Para o texto do laudo, pode ser grande
        $table->date('generation_date'); // Data em que o laudo foi gerado
        // Adicione outras colunas (ex: generated_by_llm_model, confidence_score)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laudos');
    }
};
