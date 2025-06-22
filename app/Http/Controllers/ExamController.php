<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Patient; // Garante que o model Patient também está sendo usado

class ExamController extends Controller
{
    /**
     * Exibe uma lista de todos os exames.
     * Mapeado para GET /exames
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Busca todos os exames do banco de dados, ordenados pelo mais recente.
        // Carrega os relacionamentos 'report' e 'patient' junto.
        $exams = Exam::with(['report', 'patient'])->orderBy('upload_date', 'desc')->get();

        return view('exams.index', compact('exams'));
    }

    /**
     * Exibe os detalhes de um exame espec\u00EDfico.
     * Mapeado para GET /exames/{exam}
     *
     * @param  \App\Models\Exam  $exam O modelo Exam injetado automaticamente pelo Laravel
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        abort(404, 'Detalhes do exame n\u00E3o implementados.');
    }

    // Você pode adicionar outros m\u00E9todos para gerenciar exames aqui, se necess\u00E1rio (create, store, edit, update, destroy)
}
