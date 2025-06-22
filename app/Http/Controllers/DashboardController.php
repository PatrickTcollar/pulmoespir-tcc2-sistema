<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient; // Certifique-se de importar os modelos
use App\Models\Exam;
use App\Models\Report;

class DashboardController extends Controller
{
    /**
     * Exibe o painel principal do sistema.
     * Mapeado para GET /dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Contagem total de pacientes
        $totalPatients = Patient::count();

        // Contagem total de exames
        $totalExams = Exam::count();

        // Contagem total de laudos gerados
        $totalReports = Report::count();

        // Você pode adicionar mais l\u00F3gica aqui, como exames recentes, laudos pendentes, etc.

        return view('dashboard', compact('totalPatients', 'totalExams', 'totalReports'));
    }
}
