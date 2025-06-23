<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Exam;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use Exception;

class EvolutionController extends Controller
{
    /**
     * Exibe o formulário para selecionar o paciente e gerar o laudo de evolução.
     * Mapeado para GET /evolucao
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $patients = Patient::all(); // Busca todos os pacientes para o select
        return view('evolucao.index', compact('patients'));
    }

    /**
     * Analisa a evolução de um paciente com base em seus exames ao longo do tempo.
     * Mapeado para POST /evolucao/analisar
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function analyzeEvolution(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patientId = $request->input('patient_id');
        $patient = Patient::with(['exams.report'])->find($patientId);

        if (!$patient) {
            return back()->with('error', 'Paciente não encontrado.');
        }

        $allExamsText = [];
        foreach ($patient->exams->sortBy('upload_date') as $exam) {
            $pdfPath = Storage::disk('public')->path($exam->file_path);
            if (file_exists($pdfPath)) {
                try {
                    $parser = new Parser();
                    $pdf = $parser->parseFile($pdfPath);
                    $cleanedText = preg_replace('/\s+/', ' ', trim($pdf->getText()));
                    if (!empty($cleanedText)) {
                        $allExamsText[] = "Exame ID: {$exam->id}, Data: " . ($exam->upload_date->format('d/m/Y')) . "\nResultados:\n" . $cleanedText;
                    }
                } catch (Exception $e) {
                    \Log::error("Erro ao extrair PDF para evolução (Exame ID: {$exam->id}): " . $e->getMessage());
                }
            }
        }

        if (empty($allExamsText)) {
            return back()->with('error', 'Nenhum exame com texto extraível encontrado para este paciente no período.');
        }

        $prompt = "Você é um assistente especializado em fisioterapia respiratória. Analise a sequência de exames de espirometria do paciente {$patient->name} (ID: {$patient->id}) apresentados abaixo. Forneça um laudo de EVOLUÇÃO, destacando mudanças significativas nas métricas ao longo do tempo, possíveis progressões ou melhorias, e conclusões sobre o estado respiratório geral do paciente com base no histórico.\n\n" .
                  "Histórico de Exames:\n\n" . implode("\n\n---\n\n", $allExamsText);

        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ];

        try {
            $aiResponse = Http::timeout(120)
                            ->post($apiUrl, $payload)->json();

            $evolutionReportContent = $aiResponse['candidates'][0]['content']['parts'][0]['text']
                                      ?? 'Não foi possível gerar o laudo de evolução. Resposta da IA vazia ou estrutura inesperada.';

            if (empty($evolutionReportContent) || str_contains($evolutionReportContent, 'Não foi possível gerar o laudo.')) {
                 throw new Exception('A API da IA não retornou um laudo de evolução válido. Verifique o prompt ou a resposta da API.');
            }

            $evolutionReport = Report::create([
                'patient_id' => $patient->id,
                'exam_id' => null, // Agora é anulável no BD
                'report_content' => $evolutionReportContent,
                'generation_date' => now(),
            ]);

            return redirect()->route('reports.show', $evolutionReport->id)->with('success', 'Laudo de evolução gerado com sucesso para ' . $patient->name . '!');

        } catch (Exception $e) {
            \Log::error('Erro ao gerar laudo de evolução para paciente ' . $patient->name . ': ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao gerar o laudo de evolução: ' . $e->getMessage());
        }
    }
}
