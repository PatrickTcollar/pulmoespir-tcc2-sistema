// routes/web.php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExameLaudoController;
use App\Http\Controllers\ExamUploadController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\EvolutionController;
use App\Http\Controllers\ExamChatController; // Importe o controlador de Chat

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de perfil de usu\u00e1rio
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Pacientes
    Route::resource('pacientes', PatientController::class);

    // Rotas para Upload de Exames
    Route::get('/exames/upload', [ExamUploadController::class, 'showUploadForm'])->name('exams.upload.form');
    Route::post('/exames/upload', [ExamUploadController::class, 'handleUpload'])->name('exams.upload.handle');

    // Rotas para Exames (listagem e visualiza\u00e7\u00e3o)
    Route::get('/exames', [ExamController::class, 'index'])->name('exames.index');
    Route::get('/exames/{exam}', [ExamController::class, 'show'])->name('exames.show');

    // Rota para Gerar Laudo Automatizado
    Route::post('/exames/{exam}/gerar-laudo', [ExameLaudoController::class, 'gerarLaudoAutomotizado'])->name('exams.generate_report');

    // Rota para exibir um laudo espec\u00edfico (reports.show)
    Route::get('/laudos/{report}', [ExameLaudoController::class, 'showReport'])->name('reports.show');

    // Rota para a listagem de Laudos
    Route::get('/laudos', [ExameLaudoController::class, 'index'])->name('laudos.index');

    // Rotas para Evolu\u00e7\u00e3o do Paciente
    Route::get('/evolucao', [EvolutionController::class, 'index'])->name('evolucao.index');
    Route::post('/evolucao/analisar', [EvolutionController::class, 'analyzeEvolution'])->name('evolucao.analyze');

    // **** Rota para a interface de chat do exame ****
    Route::get('/exames/{exam}/chat', [ExamChatController::class, 'showChatInterface'])->name('exames.chat');
});

// Rota de API para chat (esta j\u00e1 estava presente e deve funcionar)
// Colocada no web.php para facilitar o uso do CSRF token.
Route::post('/api/exames/{exam}/chat', [ExamChatController::class, 'handleChatMessage'])->name('api.exames.chat');

require __DIR__.'/auth.php';
