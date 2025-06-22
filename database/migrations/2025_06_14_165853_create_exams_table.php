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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Chave estrangeira para pacientes
        $table->string('file_path'); // Onde o arquivo será armazenado
        $table->string('original_filename'); // Nome original do arquivo
        $table->date('upload_date'); // Data do upload ou da realização do exame
        // Adicione outras colunas que você julgar relevantes para o exame (ex: type, description, status)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
